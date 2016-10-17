<?php
 namespace common\widgets;

 use yii\base\Widget;
 use yii\helpers\Html;

 class AllOrdersWidget extends Widget
 {
  public $data;

  public function init()
   {
    parent::init();
    if ($this->data === null || empty($data))
     {
      $this->data = 'Нету данных';
     } else
     {
      $this->data = $data;
     }
   }

  public function run()
   {
    return Html::encode($this->data);

//        GridView::widget([
//        'dataProvider' => $this->data,
//        'columns' => [
//            'uid',
//            'name',
//            'orders',
//            'ordsum',
//        ],
//    ]);
   }
 }

 ?>