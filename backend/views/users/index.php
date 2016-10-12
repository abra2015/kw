<?php
 use yii\bootstrap\Modal;
 use yii\helpers\Html;
 use yii\helpers\ArrayHelper;
 use backend\models\Users;
 use yii\grid\GridView;


 /* @var $this yii\web\View */
 /* @var $searchModel app\models\UsersSearch */
 /* @var $dataProvider yii\data\ActiveDataProvider */

 $this->title = 'Покупатели';
 $this->params['breadcrumbs'][] = $this->title;
 //var_dump(ArrayHelper::map($lastUser, 'id', 'name'));
 $ks = <<<JS
$(document).on("click", "button.inp-add", function() {
console.log("ok");
  var el = $(".dublicate")[0].innerHTML;
  $("<div class=\"row\">" + el + "</div>").insertBefore($("div.sbm"));
});
JS;

 $this->registerJs($ks);

?>

<div class="users-index">
 <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


 <p>
  <?php yii\bootstrap\Modal::begin([
      'header' => '<h2>Новый Покупатель</h2>',
      'toggleButton' => ['label' => 'Новый Покупатель', 'class' => 'btn btn-primary'],
  ]);
   $user = ArrayHelper::map($lastUser, 'id', 'name');
   echo $this->render('_form', [
       'model' => $model,
       'user' => array_shift($user)
   ]);

   Modal::end();

  ?>
  <a class="btn btn-success" href="users/last">За прошлый месяц</a>
  <a class="btn btn-warning" href="users/last">Заказы за месяц</a>
 </p>

 <?php \yii\widgets\Pjax::begin();
  echo GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
   //      'layout'=>"{sorter}\n{pager}\n{summary}\n{items}",
      'rowOptions' => function ($model, $key, $index, $grid)
       {
        if ($model->status == false)
         {
          return ['style' => 'background-color:#FFADAD;'];
         }
       },
      'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          [

              'label' => 'Name',
              'attribute' => 'name',
              'contentOptions' => ['style' => 'max-width: 200px;'], // <-- right here
              'filter' => ArrayHelper::map(Users::find()->select(['id', 'name'])->orderBy('name')->all(), 'name', 'name'),
              'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 200px; height: 35px;'],
          ],
          [

              'label' => 'Phone',
              'attribute' => 'phone',
              'format' => 'raw',
              'value' => function ($data)
               {
                return count(json_decode($data->phone, 1)) > 1 ? nl2br(implode(", <br>", json_decode($data->phone, 1))) : preg_replace('/[^0-9]/', '', $data->phone);
               },

              'contentOptions' => ['style' => 'max-width: 150px; text-align: center;'], // <-- right here
           // 'filter' => ArrayHelper::map(Users::find()->select(['id', 'phone'])->orderBy('phone')->all(), 'phone', 'phone'),
              'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 150px; height: 35px;'],
          ],
          [

              'label' => 'Email',
              'attribute' => 'email',
              'format' => 'raw',
              'contentOptions' => ['style' => 'max-width: 200; text-align: center;'], // <-- right here
           // 'filter' => ArrayHelper::map(Users::find()->select(['id', 'phone'])->orderBy('phone')->all(), 'phone', 'phone'),
              'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 200px; height: 35px;'],
          ],
          [

              'label' => 'Adress',
              'attribute' => 'adress',
              'format' => 'raw',
              'value' => function ($data)
               {
                return nl2br($data->adress);
               },
              'contentOptions' => ['style' => 'max-width: 360px;'], // <-- right here
              'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 360px; height: 35px;'],
          ],
          [

              'label' => 'Каталог',
              'attribute' => 'catalog',
              'format' => 'raw',
              'value' => function ($data)
               {
                return $data->catalog ? 'Есть' : 'Нету';
               },
              'contentOptions' => ['style' => 'max-width: 70px; text-align: center;'], // <-- right here
              'filter' => ['Нету', 'Есть'],
              'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 70px; height: 35px;'],
          ],
          ['class' => 'yii\grid\ActionColumn',
              'header' => 'Действия',
              'headerOptions' => ['width' => '220px', 'text-align' => 'center',],
              'template' => '{order} {view} {update} {down} {delete}',
              'buttons' => [
                  'update' => function ($url, $model)
                   {
                    return Html::a(
                        '<span class="glyphicon glyphicon-pencil"></span>',
                        $url, [
                        'title' => Yii::t('app', 'Редактировать Профиль'),
                        'class' => 'btn btn-xs',
                    ]);
                   },
                  'down' => function ($url, $model, $key)
                   {
                    $class = !$model->status ? 'glyphicon glyphicon-thumbs-up' : 'glyphicon glyphicon-thumbs-down';
                    $status = !$model->status ? 'Активные' : 'Неактивные';
                    $link = $model->status ? 'users/down' : 'users/up';

                    return Html::a(
                        '<span class="' . $class . '"></span>',
                        [$link, 'id' => $key], [
                        'title' => Yii::t('app', 'Перевести Покупателя в ' . $status),
                        'class' => 'btn btn-primary btn-xs',
                    ]);
                   },
                  'view' => function ($url, $model, $key)
                   {
                    return Html::a('<span class="glyphicon glyphicon-user"></span>',
                        $url, [
                            'title' => Yii::t('app', 'Просмотреть полный профиль'),
                            'class' => 'btn btn-xs',
                        ]);
                   },
                  'order' => function ($url, $model)
                   {
                    return Html::a('<span class="glyphicon glyphicon-check"></span>', '#mymodal', [
                        'title' => 'Добавить заказ', 'data-toggle' => 'modal', 'data-backdrop' => false, 'data-remote' => $url
                    ]);
//                    return Html::a('<span class="glyphicon glyphicon-qrcode"></span>',
//                        $url, [
//                            'title' => Yii::t('app', 'Добавить заказ'),
//                            'class' => 'btn btn-xs',
//                        ]);
                   }


              ],
          ],
      ],
  ]);
  \yii\bootstrap\Modal::begin(['header' => 'Добавить заказ покупателя', 'id' => 'mymodal']);

  \yii\bootstrap\Modal::end();
  \yii\widgets\Pjax::end();
  $js = <<<JS
$(document).on("click","[data-remote]",function(e) {
    e.preventDefault();
    $("div#mymodal .modal-body").load($(this).data('remote'));
});
$('#Assigs').on('hidden.bs.modal', function (e) {
  $("div#mymodal .modal-body").html('');
}); 
JS;


  $this->registerJs($js);
 ?>

</div>
