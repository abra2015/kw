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
</style>
<div class="users-form" style="height: 700px;">


 <?php

  $form = ActiveForm::begin(['action' => $model->isNewRecord ? '/admin/order/create' : '/admin/order/update?id=' . $user, 'method' => 'POST']); ?>
 <div class="row">
  <div class="col-md-1">
   <label class="control-label" for="order-count">Id</label>
  </div>
  <div class="col-md-6">
   <label class="control-label" for="order-count">Товар</label>
  </div>
  <div class="col-md-2">
   <label class="control-label" for="order-count">Кол-во</label>
  </div>
  <div class="col-md-2">
   <label class="control-label" for="order-count">Цена</label>
  </div>
  <div class="col-md-1">
   <label class="control-label" for="order-count">Add</label>
  </div>
 </div>

 <div class="row dublicate">
  <div class="col-md-1">
   <?= $form->field($model, 'customer_id[]')->textInput(['maxlength' => true, 'value' => $user])->label(false) ?>
  </div>
  <div class="col-md-6">
   <?= $form->field($model, 'product_id[]')->dropDownList(ArrayHelper::map(Product::find()->select(['id', 'product', 'id_category', 'size'])->orderBy('product')->all(), 'id', function ($model)
    {
     return $model->product . ' ' . $model->size;
    }, function ($model)
    {
     return $model->getCategoryName();
    }))->label(false) ?>
  </div>
  <div class="col-md-2">
   <?= $form->field($model, 'count[]')->dropDownList(range(1, 12))->label(false); ?>
  </div>
  <div class="col-md-2">
   <?= $form->field($model, 'price[]')->textInput(['maxlength' => true])->label(false) ?>
  </div>
  <div class="col-md-1">
   <button type="button" class="btn btn-primary btn-sm inp-add">add</button>
  </div>
 </div>

 <div class="sbm row">
  <div class="col-md-12">
   <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
  </div>
 </div>

 <?php ActiveForm::end();


 ?>

</div>
