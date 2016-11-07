<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
use fedemotta\datatables\DataTables;
use yii\bootstrap\Html;
use yii\helpers\Url;
use app\assets\DpAsset;
use app\assets\Select2Asset;
use yii\jui\DatePicker;

DpAsset::register($this);
Select2Asset::register($this);
?>

<?php
$this->title = 'Front Desk : Dashboard';
?>

<br>

<div class="container">
  <button type="button" id="btnAx" class="btn btn-primary">Todays Arrivals <span class="badge">7</span></button>
  <button type="button" class="btn btn-success">Todays Bookings <span class="badge">3</span></button>
  <button type="button" class="btn btn-danger">New Reservations <span class="badge">5</span></button>
  <?= Html::a('New Booking', ['create'], ['class' => 'btn btn-success']) ?>
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

<button id="send_push">Pusher</button>

<script src="https://js.pusher.com/3.2/pusher.min.js"></script>
<script>
    $('#send_push').click(function () {

        $.ajax({
            url: "<?= Url::to('test-pusher') ?>",
            type: "POST",
            data: {}, //JSON
            dataType: "json",
            cache: false,

            success:function(data, textStatus, jqXHR) {

            },
            error:function(jqXHR, textStatus, errorThrown) {

            }
        });

    });


    Pusher.logToConsole = true;

    var pusher = new Pusher('f61b79b5a60a9cf8df35', {
        encrypted: true
    });

    var channel = pusher.subscribe('test_channel');
    channel.bind('my_event', function(data) {
        //alert(data.message);
        $('#btnAx').text(data.message);
        $('#btnAx').addClass('btn-danger');
        $('#btnAx').removeClass('btn-primary');
    });

    Pusher.log = function(message) {
        if (window.console && window.console.log) {
            window.console.log(message);
        }
    };
</script>