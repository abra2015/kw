<?php
 namespace frontend\controllers;

 use Yii;
 use frontend\models\ContactForm;
 use yii\web\Controller;
 use yii\filters\VerbFilter;

 /**
  * Site controller
  */
 class SiteController extends Controller
 {

  public function beforeAction($action)
   {
    $this->enableCsrfValidation = false;
    return parent::beforeAction($action);
   }


  /**
   * @inheritdoc
   */
  public function actions()
   {
    return [
        'error' => [
            'class' => 'yii\web\ErrorAction'
        ],
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
        ],
        'set-locale' => [
            'class' => 'common\actions\SetLocaleAction',
            'locales' => array_keys(Yii::$app->params['availableLocales'])
        ],
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

  public function actionIndex()
   {
    return $this->render('index');
   }

  public function actionTest()
   {
    //$data = date('Y.m.d H:i:s').': ';
    //file_put_contents('/home/gennadii/karabasweb.com/logs/mailgun_reports.log', $data.json_encode(Yii::$app->getRequest()->getBodyParams()), FILE_APPEND );
    echo 'ok';
   }


  public function actionContact()
   {
    $model = new ContactForm();
    if ($model->load(Yii::$app->request->post()))
     {
      if ($model->contact(Yii::$app->params['adminEmail']))
       {
        Yii::$app->getSession()->setFlash('alert', [
            'body' => Yii::t('frontend', 'Thank you for contacting us. We will respond to you as soon as possible.'),
            'options' => ['class' => 'alert-success']
        ]);
        return $this->refresh();
       } else
       {
        Yii::$app->getSession()->setFlash('alert', [
            'body' => \Yii::t('frontend', 'There was an error sending email.'),
            'options' => ['class' => 'alert-danger']
        ]);
       }
     }

    return $this->render('contact', [
        'model' => $model
    ]);
   }
 }
