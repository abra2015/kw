<?php

 namespace backend\controllers;


 use Yii;
 use backend\models\Users;
 use backend\models\Order;
 use backend\models\UsersSearch;
 use yii\data\ActiveDataProvider;
 use yii\web\Controller;
 use yii\web\Cookie;
 use yii\web\NotFoundHttpException;
 use yii\filters\VerbFilter;
 use yii\filters\AccessControl;

 /**
  * UsersController implements the CRUD actions for Users model.
  */
 class UsersController extends Controller
 {

  /**
   * Lists all Users models.
   * @return mixed
   */
  public function actionIndex()
   {
    $searchModel = new UsersSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->sort = ['defaultOrder' => ['name' => SORT_ASC]];
    $dataProvider->pagination = ['pageSize' => 20];
    $lastUser = Users::find()->orderBy('id DESC')->limit(1)->all();


    return $this->render('index', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'model' => new Users(),
        'lastUser' => $lastUser
    ]);
   }


  /**
   * Displays a single Users model.
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
   * Creates a new Users model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   * @return mixed
   */
  public function actionCreate()
   {
    $model = new Users();
    //    var_dump(Yii::$app->request->post('Users'));
    $data = Yii::$app->request->post('Users');

    if ($model->load(Yii::$app->request->post()))
     {
      $model->date_reg = date("Y-m-d H:i:s");
      $model->phone = $this->jcode($data['phone']);
      if ($model->email)
       $this->subscribeUser($model);
      $model->save();
      Yii::$app->session->setFlash('add_user', "Новый покупатель <b>$model->name</b> добавлен");
      return $this->redirect('/admin/users/');
//      return $this->redirect(['view', 'id' => $model->id]);
     } else
     {
      return $this->render('create', [
          'model' => $model,
      ]);
     }
   }

  /*
   * метод кодирования в джсон телефонов которые приходят из инпутов
   *
   */
  public function jcode($data)
   {
    //var_dump($data);
    return json_encode($this->preg_replaceArray(array_filter(explode(', ', $data))));
   }

  public function preg_replaceArray($data = [])
   {
    $newData = [];

    if (is_array($data))
     {
      foreach ($data as $val)
       {
        if (!empty($val) && $val != "___-___-__-__")
         $newData[] = str_replace('-', '', preg_replace('/[^0-9]/', '', $val));
       }

      return $newData;
     } else
     {
      return preg_replace('/[^0-9]/', '', $data);
     }
   }

  /**
   * Updates an existing Users model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id
   * @return mixed
   */

  public function actionUpdate($id)
   {
    $model = $this->findModel($id);
    $data = Yii::$app->request->post('Users');
//         Yii::$app->request->referrer;

    if ($model->load(Yii::$app->request->post()))
     {
      $model->date_updated = date("Y-m-d H:i:s");
      $model->phone = $this->jcode($data['phone']);

      if ($model->email)
       $this->subscribeUser($model);

      $model->save();
      \Yii::$app->session->setFlash('add_user', "Покупатель <b>$model->name</b> успешно обновлен");
//       return $this->redirect(['view', 'id' => $model->id]);
      return $this->redirect('/admin/users/');

     } else
     {
      return $this->render('update', [
          'model' => $model,
      ]);
     }
   }

  /*
   public function actionUpdate($id)
    {
     $model = $this->findModel($id);

     if ($model->load(Yii::$app->request->post()) && $model->save()) {
      return $this->redirect(['view', 'id' => $model->id]);
     } else {
      return $this->render('update', [
          'model' => $model,
      ]);
     }
    }
*/


  public function actionOrder($id)
   {
    $this->layout = false;
    return $this->render('_add_order', [
        'model' => new Order(),
        'user' => $id

    ]);
   }


  /**
   * Deletes an existing Users model.
   * If deletion is successful, the browser will be redirected to the 'index' page.
   * @param integer $id
   * @return mixed
   */
  public function actionDelete($id)
   {
    $this->findModel($id)->delete();

    return $this->redirect(['index']);
   }

  /*
   * Смена Статусов покупателей
   */

  public function actionDown()
   {
    $model = Users::findOne(Yii::$app->request->get('id'));
    $model->status = 0;
    $model->save();

    $this->actionIndex();
//     return $this->redirect($_SERVER['HTTP_REFERER']);
   }

  public function actionUp()
   {
    $model = Users::findOne(Yii::$app->request->get('id'));
    $model->status = 1;
    $model->save();

    $this->actionIndex();
//     return $this->redirect($_SERVER['HTTP_REFERER']);
   }

  public static function parseHttp()
   {
    $query = parse_url($_SERVER['HTTP_REFERER']);
    var_dump($query);
   }

  /**
   * Finds the Users model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return Users the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
   {
    if (($model = Users::findOne($id)) !== null)
     {
      return $model;
     } else
     {
      throw new NotFoundHttpException('The requested page does not exist.');
     }
   }

  protected function subscribeUser($model)
   {
    define('MAILGUN_API_URL_LIST', 'https://api.mailgun.net/v3/lists/');
    define('MAILGUN_MAILING_LIST', 'clients@karabasweb.com/');
    define('MAILGUN_API_KEY', 'key-776c8697010e7e9435fcf00b30a796c6');
    define('MAILGUN_SMTP_PASSWORD', '4dbd846d8b786568f5d8083e17f36bd8');


    if ($model->email)
     {
      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_RETURNTRANSFER => 1,
          CURLOPT_URL => MAILGUN_API_URL_LIST . MAILGUN_MAILING_LIST . 'members',
          CURLOPT_USERPWD => "api:" . MAILGUN_API_KEY,
          CURLOPT_POST => 1,
          CURLOPT_POSTFIELDS => array(
              'address' => $model->email,
              'subscribed' => true
          )));
      $resp = curl_exec($curl);
      curl_close($curl);

      $res = json_decode($resp, 1);
      if ($res['message'] == 'Mailing list member has been created' || $res['message'] == "Address already exists '" . $model->email . "'")
       {
        $data = date('Y.m.d H:i:s') . ': ' . $model->email . ' was Subscribed into List Customer: ' . $model->id . PHP_EOL;
        file_put_contents('/home/gennadii/karabasweb.com/logs/user_subscribed.log', $data, FILE_APPEND);
       } else
       {
        $data = date('Y.m.d H:i:s') . ': can not send POST REQUEST into MailGun Subscribed List' . PHP_EOL . json_encode($res) . " " . $model->email;
        file_put_contents('/home/gennadii/karabasweb.com/logs/user_subscribed.log', $data, FILE_APPEND);
       }
     }
   }
 }

