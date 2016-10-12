<?php

 use yii\helpers\Html;
 use yii\grid\GridView;
 use yii\helpers\ArrayHelper;
 use backend\models\Cat;

 /* @var $this yii\web\View */
 /* @var $searchModel backend\models\ProductSearch */
 /* @var $dataProvider yii\data\ActiveDataProvider */

 $this->title = 'Продукция';
 $this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

 <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

 <?php echo GridView::widget([
     'dataProvider' => $dataProvider,
     'filterModel' => $searchModel,
     'columns' => [
         ['class' => 'yii\grid\SerialColumn'],
         [
             'label' => 'category',
             'attribute' => 'id_category',
             'contentOptions' => ['style' => 'width: 140px;', 'class' => 'text-center'], // <-- right here
             'filter' => ArrayHelper::map(Cat::find()->select(['id', 'category'])->orderBy('category')->all(), 'id', 'category'),
             'filterInputOptions' => ['class' => 'form-control text-center', 'style' => 'width: 140px; height: 35px;'],
             'value' => function ($data)
              {
               return $data->getCategoryName();
              },
         ],
         [
             'label' => 'product',
             'attribute' => 'product',
             'filterInputOptions' => ['class' => 'form-control', 'style' => 'height: 35px;'],
         ],

         [
             'label' => 'size',
             'attribute' => 'size',
             'contentOptions' => ['style' => 'width: 190px;', 'class' => 'text-center'], // <-- right here
             'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 190px; height: 35px;'],
         ],
         [
             'label' => 'price',
             'attribute' => 'price',
             'contentOptions' => ['style' => 'width: 150px;', 'class' => 'text-center'], // <-- right here
             'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 150px; height: 35px;'],
             'value' => function ($data)
              {
               return $data->price . " грн.";
              },
         ],
         [
             'label' => 'article',
             'attribute' => 'article',
             'contentOptions' => ['style' => 'width: 50px;', 'class' => 'text-center'], // <-- right here
             'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 50px; height: 35px;'],
         ],

         ['class' => 'yii\grid\ActionColumn'],
     ],
 ]); ?>

</div>
