<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use fedemotta\datatables\DataTables;
use yii\bootstrap\Html;
?>
<br>

<div class="container">
  <button type="button" class="btn btn-primary">Todays Arrivals <span class="badge">7</span></button>
  <button type="button" class="btn btn-success">Todays Bookings <span class="badge">3</span></button>
  <button type="button" class="btn btn-danger">New Reservations <span class="badge">5</span></button>
  <?= Html::a('Create Booking', ['create'], ['class' => 'btn btn-success']) ?>
</div>
  
<hr/>  
    
<?= DataTables::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
 
        [
         'label'=> 'Room',
         'attribute' => 'room_name',
         'value' => 'room_name'
        ],
        'full_name',
        [
         'label'=> 'In',
         'attribute' => 'checkin_date',
         'value' => 'checkin_date'
        ],
        [
         'label'=> 'Out',
         'attribute' => 'checkout_date',
         'value' => 'checkout_date'
        ],
        [
         'label'=> 'agent_name',
         'attribute' => 'agent_name',
         'value' => 'agent_name'
        ],
        [
         'label'=> 'Adults',
         'attribute' => 'no_of_adults',
         'value' => 'no_of_adults'
        ],
        [
         'label'=> 'Children',
         'attribute' => 'no_of_children',
         'value' => 'no_of_children'
        ],
        [
         'label'=> 'Status',
         'attribute' => 'booking_status',
         'value' => 'booking_status'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{new_view}{new_edit}',
            'buttons' => [
              'new_view' => function ($url, $model) {
                  return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'view?id='.$model->booking_id, [
                              'title' => Yii::t('app', 'New Action1'),
                  ]);
              },
              'new_edit' => function ($url, $model) {
                  return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'update?id='.$model->booking_id, [
                              'title' => Yii::t('app', 'New Action1'),
                  ]);
              }        
            ],
      ],
      //['class' => 'yii\grid\ActionColumn'],
    ],
]);?>

