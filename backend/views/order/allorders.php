<h3>Оборот за выбраный период: <?=$oborot?> грн.</h3>
<?php
 use yii\helpers\Html;

 echo yii\grid\GridView::widget([
     'dataProvider' => $data,
     'columns' => [
         ['class' => 'yii\grid\SerialColumn'],
         [
             'attribute' => 'ud',
             'label' => 'Дата'
         ],
         [
             'attribute' => 'name',
             'label' => 'Покупатель'
         ],
         [
             'attribute' => 'orders',
             'label' => 'Заказ',
             'content' => function ($data)
              {
               $out = '';
               $orders = explode(',||', $data['orders']);
               foreach ($orders as $line)
                if ($line)
                 $out .= str_replace("||", '', $line) . "<br>";

               $out .= "<hr><b>Сума: " . $data['ord_sum'] . ' грн. </b>';

               return Html::decode($out);
              }
         ],
         [
             'attribute' => 'ord_sum',
             'label' => 'Сума',
             'content' => function($data) {
              return "<b>".$data['ord_sum']. " грн.</b>";
             }

         ],
     ],
 ]);