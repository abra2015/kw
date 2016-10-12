<?php
 use yii\bootstrap\Modal;
 use yii\helpers\Html;
 use yii\helpers\ArrayHelper;
 use frontend\models\Users;


 /* @var $this yii\web\View */
 /* @var $searchModel app\models\UsersSearch */
 /* @var $dataProvider yii\data\ActiveDataProvider */

 $this->title = 'Users';
 $this->params['breadcrumbs'][] = $this->title;
 //var_dump(ArrayHelper::map($lastUser, 'id', 'name'));
?>

<div class="users-index">
 <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

 <p>
  <? yii\bootstrap\Modal::begin([
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
 </p>

 <? \yii\widgets\Pjax::begin();
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
              'contentOptions' => ['style' => 'max-width: 250px;'], // <-- right here
              'filter' => ArrayHelper::map(Users::find()->select(['id', 'name'])->orderBy('name')->all(), 'name', 'name'),
              'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 250px; height: 35px;'],
          ],
          [

              'label' => 'Phone',
              'attribute' => 'phone',
              'format' => 'raw',
              'value' => function ($data)
               {
                return count(json_decode($data->phone, 1)) > 1 ? nl2br(implode(", <br>", json_decode($data->phone, 1))) : preg_replace('/[^0-9]/', '', $data->phone);
               },

              'contentOptions' => ['style' => 'max-width: 300px; text-align: center;'], // <-- right here
           // 'filter' => ArrayHelper::map(Users::find()->select(['id', 'phone'])->orderBy('phone')->all(), 'phone', 'phone'),
              'filterInputOptions' => ['class' => 'form-control', 'style' => 'width: 300px; height: 35px;'],
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
          ['class' => 'yii\grid\ActionColumn',
              'header' => 'Действия',
              'headerOptions' => ['width' => '200px', 'text-align' => 'center',],
              'template' => '{view}  {update}  {down} {delete}',
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
                    $link = $model->status ? '/users/down' : '/users/up';

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


              ],
          ],
      ],
  ]);
  \yii\widgets\Pjax::end();
 ?>

</div>
