<?php
 use yii\helpers\Html;
 use yii\widgets\ActiveForm;

 /* @var $this yii\web\View */
 /* @var $model app\models\Users */
 /* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">
 <?php

  $form = ActiveForm::begin(['action' => $model->isNewRecord ? '/admin/users/create' : '/admin/users/update?id=' . $model->id, 'method' => 'POST']); ?>

 <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

 <?=
  $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
      'mask' => '999-999-99-99, 999-999-99-99']) ?>

 <?=
  $form->field($model, 'email')->widget(\yii\widgets\MaskedInput::className(), [
    'name' => 'input-36',
    'clientOptions' => [
        'alias' =>  'email'
    ],
]) ?>

 <?= $form->field($model, 'catalog')->checkbox(['label' => 'Каталог давали?', 'checked' => $model->catalog ? true : false]) ?>

 <?= $form->field($model, 'adress')->textarea(['rows' => 6]) ?>
 <?php //= \yii\bootstrap\Html::input('text', Yii::$app->getRequest()->csrfParam, Yii::$app->request->getCsrfToken(), ['style' => 'display: none;']); ?>

 <div class="form-group">
  <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
 </div>

 <?php ActiveForm::end(); ?>

</div>
