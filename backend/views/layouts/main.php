<?php
 /**
  * @var $this yii\web\View
  */
 use yii\bootstrap\Alert;
?>
<?php $this->beginContent('@backend/views/layouts/common.php'); ?>
 <div class="box">
  <div class="box-body">

   <?php
    if ($flash = Yii::$app->session->getFlash('add_user'))
     {
      echo Alert::widget(['options' => ['class' => 'alert alert-success'], 'body' => $flash]);
     }
    echo $content ?>
  </div>
 </div>
<?php $this->endContent(); ?>