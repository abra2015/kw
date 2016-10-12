<?php

 namespace backend\controllers;

 use Yii;
 use backend\models\SystemLog;
 use backend\models\search\SystemLogSearch;
 use yii\helpers\VarDumper;
 use yii\web\Controller;
 use yii\web\NotFoundHttpException;
 use yii\filters\VerbFilter;

 /**
  * LogController implements the CRUD actions for SystemLog model.
  */
 class ReportsController extends Controller
 {
  public function behaviors()
   {
    return [
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'delete' => ['post'],
                'clear' => ['post'],
            ],
        ],
    ];
   }

  /**
   * Lists all SystemLog models.
   * @return mixed
   */
  public function actionIndex()
   {
    $r = $_GET['r'];
    return $this->render($r);

   }

  public function actionAll()
   {
    return $this->render('all');

   }

  public function actionGallery()
   {
    return $this->render('/gallery/gallery');
   }

  /**
   * Displays a single SystemLog model.
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
   * Deletes an existing SystemLog model.
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
   * Finds the SystemLog model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   * @param integer $id
   * @return SystemLog the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id)
   {
    if (($model = SystemLog::findOne($id)) !== null)
     {
      return $model;
     } else
     {
      throw new NotFoundHttpException('The requested page does not exist.');
     }
   }
 }
