<?php
 use yii\helpers\Html;
 use backend\models\Product;
 use yii\widgets\ActiveForm;
 use yii\helpers\ArrayHelper;

 /* @var $this yii\web\View */
 /* @var $model app\models\Users */
 /* @var $form yii\widgets\ActiveForm */
?>
<style>
 .col-md-3, .col-md-2, .col-md-4, .col-md-1, .col-md-6 {
  padding-right: 0px !important;
  padding-left: 4px !important;
 }

 .form-group {
  margin-bottom: 4px !important;

 div.users-form {
  height: 500px;
 }

 }
</style>
<div class="users-form">


 <?php

  $form = ActiveForm::begin(['action' => $model->isNewRecord ? '/admin/order/create' : '/admin/order/update?id=' . $user, 'method' => 'POST']); ?>
 <div class="row">
  <div class="col-md-5">
   <label class="control-label" for="order-count">Продукт</label>
  </div>
  <div class="col-md-2">
   <label class="control-label" for="order-count">Кол-во</label>
  </div>
  <div class="col-md-2">
   <label class="control-label" for="order-count">Цена</label>
  </div>
  <div class="col-md-2">
   <label class="control-label" for="order-count">Дата</label>
  </div>

  <div class="col-md-1">
   <label class="control-label" for="order-count">Add</label>
  </div>

 </div>

 <div class="row dublicate">
  <?= $form->field($model, 'customer_id[]')->hiddenInput(['maxlength' => true, 'value' => $user])->label(false) ?>
  <div class="col-md-5">
   <?= $form->field($model, 'product_id[]')->dropDownList(ArrayHelper::map(Product::find()->select(['id', 'product', 'id_category', 'size'])->orderBy('product')->all(), 'id', function ($model)
    {
     return $model->product . ' ' . $model->size;
    }, function ($model)
    {
     return $model->getCategoryName();
    }))->label(false) ?>
  </div>
  <div class="col-md-2">
   <?= $form->field($model, 'count[]')->dropDownList([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10])->label(false); ?>
  </div>
  <div class="col-md-2">
   <?= $form->field($model, 'price[]')->textInput(['maxlength' => true])->label(false) ?>
  </div>
  <div class="col-md-2">
   <?= $form->field($model, 'date[]')->textInput(['maxlength' => true, 'value' => date("d/m/Y")])->label(false) ?>
  </div>
  <div class="col-md-1">
   <button type="button" class="btn btn-primary btn-sm inp-add">add</button>
  </div>
 </div>

 <div class="sbm row">
  <div class="col-md-12">
   <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
  </div>
 </div>

 <?php ActiveForm::end();


 ?>

</div>
