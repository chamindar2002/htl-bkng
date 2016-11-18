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
use app\assets\PusherAsset;
use yii\helpers\Html;

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

          <?= $this->render(
              '_bookings_tab',
                  ['searchModel'=>$searchModel, 'dataProvider'=>$dataProvider]
          );
          ?>

  </div>
    
  <div id="menu1" class="tab-pane fade">
      <p>
        <?= $this->render(
                '_orders_tab',
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

        $('#btnAx').html(data.message.message);

    });

    Pusher.log = function(message) {
        if (window.console && window.console.log) {
            //window.console.log(message);
        }
    };
    
    
</script>