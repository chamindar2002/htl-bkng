<?php
//use yii\grid\GridView;
use yii\widgets\Pjax;
use fedemotta\datatables\DataTables;
//use yii\bootstrap\Html;
use yii\helpers\Url;
use app\assets\DpAsset;
use app\assets\Select2Asset;
use yii\jui\DatePicker;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\assets\PusherAsset;

DpAsset::register($this);
Select2Asset::register($this);
PusherAsset::register($this);
?>

<?php
$this->title = 'Front Desk : Dashboard';
?>
  
<hr/> 
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Bookings</a></li>
  <li><a data-toggle="tab" href="#menu1" id="order_tab">Orders</a></li>
  <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
</ul>

<div class="tab-content">
  
  <div id="home" class="tab-pane fade in active"><p>
    <br /><?= Html::a('Check In Guest', ['create'], ['class' => 'btn btn-success']) ?>
    

    <?= GridView::widget([
      'dataProvider'=> $dataProvider,
      'filterModel' => $searchModel,
      'columns' => [
            'room_name',
            'full_name',
            'checkin_date',
            'checkout_date',
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
      ],
      'toolbar' => [
          [
              'content'=>
                  /*Html::button('<i class="glyphicon glyphicon-repeat"></i>', [
                      'type'=>'button', 
                      'title'=>'Add Book', 
                      'class'=>'btn btn-default',
                      'id'=>'reloadme',
                  ]) . ' '.
                  Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], [
                      'class' => 'btn btn-default', 
                      'title' => 'Reset Grid'
                  ]),*/
              Html::button('<i class="glyphicon glyphicon-repeat"></i>', [
                      'type'=>'button', 
                      'title'=>'Add Book', 
                      'class'=>'btn btn-default',
                      'id'=>'reloadme',
                  ]),
          ],
          '{export}',
          '{toggleData}'
      ],
      'panel' => [
              'type' => GridView::TYPE_DEFAULT,
              'heading' => 'Bookings',
          ], 
      'export' => [
              'fontAwesome' => true
          ],
        'responsive' => true,
          'hover' => true,
      'pjax'=>true,
      'pjaxSettings'=>[
          'options'=>[
              'id'=>'grid-demo',
          ],
          'neverTimeout'=>true,
          //'beforeGrid'=>'My fancy content before.',
          //'afterGrid'=>'My fancy content after.',
      ]
  ]);

  ?>
  
    
  </div>
    
    
  <div id="menu1" class="tab-pane fade">
      <p>
        <?= $this->render(
                '@app/views/common/_autocompleter',
                        ['currOccupents'=>$currOccupents, 'items'=>$items,
                            'orderDataProvider'=> $orderDataProvider,
                            'orderSearchModel'=>$orderSearchModel, 'stewards'=>$stewards, 'dinningTables'=>$dinningTables]
                );
        ?>
      </p>
  </div>
  <div id="menu2" class="tab-pane fade">
    <h3>Menu 2</h3>
    <p>Some content in menu 2.</p>
  </div>
</div>
 

<!--<hr/>-->
<!--<button id="send_push">Pusher</button>-->
<!--<div id="btnAx">No notifications</div>-->

<!--<script src="https://js.pusher.com/3.2/pusher.min.js"></script>-->
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

    var channel = pusher.subscribe('kot_channel');
    channel.bind('my_event', function(data) {
        //alert(data.message);
        $('#btnAx').html(data.message.message);
        //$('#btnAx').addClass('btn-danger');
        //$('#btnAx').removeClass('btn-primary');
    });

    Pusher.log = function(message) {
        if (window.console && window.console.log) {
            window.console.log(message);
        }
    };
    
    
</script>