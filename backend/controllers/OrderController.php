<?php

 namespace backend\controllers;

 use Yii;
 use backend\models\Order;
 use backend\models\OrderSearch;
 use yii\web\Controller;
 use yii\web\NotFoundHttpException;
 use yii\filters\VerbFilter;
 use yii\data\ActiveDataProvider;
 use yii\helpers\Url;

 /**
  * OrderController implements the CRUD actions for Order model.
  */
 class OrderController extends Controller
 {
  public function behaviors()
   {
    return [
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
            ],
        ],
    ];
   }

  /**
   * Lists all Order models.
   * @return mixed
   */
  public function actionIndex()
   {
    $searchModel = new OrderSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
   }


  private function Query()
   {

    $query = (new \yii\db\Query())
        ->select(["FROM_UNIXTIME(date, '%d.%m.%Y') as ud", "U.name", "GROUP_CONCAT('||',O.count, \"x \", O.price, \" = \", O.count*O.price, '   ', P.product) as orders",
            "SUM(O.count*O.price) as ord_sum",
            "SUM(O.count) as cnt_pos",
            "count(*) as dif_pos"])
        ->from("orders O")
        ->join('JOIN', 'users U', 'U.id = O.customer_id')
        ->join('JOIN', 'product P', 'O.product_id = P.id');

    if ($id = Yii::$app->request->get('id'))
     $query->where(['O.customer_id' => $id]);

    if (($from = Yii::$app->request->get('from')))
     $query->andWhere(['>=', 'O.date', strtotime($from)]);


    $query->groupBy(['ud', 'O.customer_id'])
        ->orderBy('O.date DESC');


    return $query;


   }


  public function actionAllorders()
   {
    $dataProvider = new ActiveDataProvider([
        'query' => $this->Query(),
    ]);

    $Oborot = (new \yii\db\Query())->select('SUM(O.count*O.price) as oborot')->from("orders O")->all();

    return $this->render('allorders', ['data' => $dataProvider,
        'oborot' => $Oborot[0]['oborot']
    ]);

   }

  public function actionThisMonth()
   {
    $_GET['from'] = date('Y-m-d H:i:s', (strtotime('first day of this month') - 18 * 3600));
    $Oborot = (new \yii\db\Query())
        ->select('SUM(O.count*O.price) as oborot')
        ->from("orders O")
        ->where(['>=', 'O.date', (strtotime('first day of this month') - 18 * 3600)])
        ->all();
    $dataProvider = new ActiveDataProvider([
        'query' => $this->Query(),

    ]);

    return $this->render('allorders', ['data' => $dataProvider, 'oborot' => $Oborot[0]['oborot']]);

   }

  public static function getCntAllOrders()
   {
    return self::Query()->count();
   }

  public static function getCntOrdersThisMonth()
   {
    $_GET['from'] = date('Y-m-d H:i:s', (strtotime('first day of this month') - 18 * 3600));
    $cnt = self::Query()->count();
    $_GET['from'] = '';
    return $cnt;
   }


  /**
   * Displays a single Order model.
   * @param integer $id
   * @return mixed
   */
  public function actionView($id)
   {
    return $this->render('view', [
        'model' => $this->findModel($id),
    ]);
   }

  /**
   * Creates a new Order model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
   {
    $this->enableCsrfValidation = false;
    //print_r(Yii::$app->request->post());
    $Order = Yii::$app->request->post();
    $order_id = Order::find()->select('order_id')->max('order_id') + 1;
    for ($i = 0; $i < count($Order['Order']['customer_id']); $i++)
     {
      $model = new Order();
      $model->product_id = $Order['Order']['product_id'][$i];
      $model->date = strtotime(str_replace('/', '-', $Order['Order']['date'][$i])) + 4 * 3600;
      $model->order_id = $order_id;
      $model->customer_id = $Order['Order']['customer_id'][$i];
      $model->count = $Order['Order']['count'][$i];
      $model->price = $Order['Order']['price'][$i];
      if ($model->save())
       \Yii::$app->session->setFlash('add_user', "Заказ пользователя <b>$model->customer_id</b> успешно добавлен");

     }

    return $this->redirect(Url::previous());


    if ($model->load(Yii::$app->request->post()) && $model->save())
     {
      $model->date = strtotime('now');
      $model->order_id = Order::find()->select('id')->max('id') + 1;

      return $this->redirect(['view', 'id' => $model->id]);
     } else
     {
      return $this->render('create', [
          'model' => $model,
      ]);
     }
   }

  /**
   * Updates an existing Order model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   */
  public function actionUpdate($id)
   {
    $model = $this->findModel($id);

    if ($model->load(Yii::$app->request->post()) && $model->save())
     {
      return $this->redirect(['view', 'id' => $model->id]);
     } else
     {
      return $this->render('update', [
          'model' => $model,
      ]);
     }
   }

  /**
   * Deletes an existing Order model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   */
  public function actionDelete($id)
   {
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
   }

  /**
   * Finds the Order model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Order the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
   {
    if (($model = Order::findOne($id)) !== null)
     {
      return $model;
     } else
     {
      throw new NotFoundHttpException('The requested page does not exist.');
     }
   }
 }
