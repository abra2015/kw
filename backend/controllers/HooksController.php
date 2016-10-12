<?php

 namespace backend\controllers;


 use Yii;
 use backend\models\Users;
 use backend\models\UsersSearch;
 use yii\data\ActiveDataProvider;
 use yii\web\Controller;
 use yii\web\Cookie;
 use yii\web\NotFoundHttpException;
 use yii\filters\VerbFilter;
 use yii\filters\AccessControl;

 use common\components\AccessRule;
 use common\models\User;

 /**
  * UsersController implements the CRUD actions for Users model.
  */
 class HooksController extends Controller
 {

  public function beforeAction($action)
   {
    $this->enableCsrfValidation = false;
    return parent::beforeAction($action);
   }

  public function behaviors()
   {
    return [
        'verbs' => [
            'class' => VerbFilter::className(),
            'actions' => [
                'index' => ['get'],
                'view' => ['get'],
                'test' => ['put', 'get'],
                'create' => ['get', 'post'],
                'update' => ['get', 'put', 'post'],
                'delete' => ['post', 'delete'],
            ],
        ],
    ];
   }

  public function actionTest()
   {
    //$data = date('Y.m.d H:i:s').': ';
    //file_put_contents('/home/gennadii/karabasweb.com/logs/mailgun_reports.log', $data.json_encode(Yii::$app->getRequest()->getBodyParams()), FILE_APPEND );
    echo 'ok';
   }

 }
