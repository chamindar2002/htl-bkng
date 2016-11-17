<?php
use app\assets\PusherAsset;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
$this->title = 'Orders';

PusherAsset::register($this)
?>
<style>
    .line-item-box{
        float: left;
        width:165px;
        margin: 5px;
        padding: 5px;
        background: #f1f1f1;
        border: 2px solid #ccc;
    }
    .line-item-box h5{
        font-weight: bold;
    }
    .lb-full{width:100%;}
    
    .high-light{
        border: 1px dotted black;
        margin-right: 10px;
    }
</style>
<div class="form-inline">
  <div class="form-group high-light" id="kot-area">
      <h2>KOT</h2>   
   <?php if($orders){ ?>
      
      <?php foreach($orders As $order): ?>
      
          <?php if($order->send_notification == 'KOT'){ ?>
                <?= $this->render('@app/views/common/_order_item', ['order'=>$order]); ?>
          <?php } ?>
      
      <?php endforeach; ?>
      
   <?php } ?>   
  </div>
  <div class="form-group high-light" id="bot-area">
    <h2>BOT</h2>     
    <?php if($orders){ ?>
      
      <?php foreach($orders As $order): ?>
      
          <?php if($order->send_notification == 'BOT'){ ?>
                <?= $this->render('@app/views/common/_order_item', ['order'=>$order]); ?>
          <?php } ?>
      
      <?php endforeach; ?>
      
   <?php } ?>   
  </div>
  
</div>



<!-- ------------------------- print priview modal begin -------------------------------- -->

<style>

    .print {display: block;}
</style>
<div id="kot_bot_print_preview" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Kitchen/Bar Order Ticket</h4>
            </div>
            <div class="modal-body myDivToPrint">
                <div id="printable-area" class="print"></div>
            </div>
            <div class="modal-footer">

                <button id='btn' class="btn btn-primary" value='Print' onclick='Printable.printIt()'>
                    <i class="glyphicon glyphicon-print"></i>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>


            </div>
        </div>

    </div>
</div>


<!-- ------------------------- print priview modal end -------------------------------- -->


<script>


var Printable = {

        fetchOrder: function (id) {
            $.ajax({
                url: "orders/fetch-order",
                type: "POST",
                data: {order_id: id}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                    if(response.result == 'success'){

                      $('#printable-area').html(response.html);

                    }
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
        },
       printIt: function(){
           var prntstyle = '<link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/sbadmin/bootstrap/css/bootstrap.min.css" />';

           w=window.open(null, 'Print_Page', 'scrollbars=yes');
           w.document.write(prntstyle + jQuery('#printable-area').html());
           w.document.close();
           w.print();
       }
}


Pusher.logToConsole = true;

    var pusher = new Pusher('f61b79b5a60a9cf8df35', {
        encrypted: true
    });

    var channel = pusher.subscribe('kot_channel');
    channel.bind('my_event', function(data) {
        //alert(data.message);
        if(data.message.status == 'OPEN'){
            $('#kot-area').append(data.message.message);
        }else if(data.message.status == 'CANCELED'){
            var d = $.parseJSON(data.message.message);
            console.log(d);
            $('#order_item_header_' + d.id).removeClass('btn-primary');
            $('#order_item_header_' + d.id).removeClass('btn-warning');
            $('#order_item_header_' + d.id).addClass('btn-danger');
            $('#order-item-status_' + d.id).html(d.status);

        }else if(data.message.status == 'UPDATED'){
            var d = $.parseJSON(data.message.message);
            console.log(d);
            $('#order_item_header_' + d.invoice_item_id).removeClass('btn-primary');
            $('#order_item_header_' + d.invoice_item_id).addClass('btn-warning');
            $('#order-item-status_' + d.invoice_item_id).html(d.order_status)
            $('#order-item-qty_' + d.invoice_item_id).html(d.order_quantity);
            $('#order-steward_' + d.invoice_item_id).html(d.employee_name);
            $('#order-table_' + d.invoice_item_id).html(d.title);
        }

    });
    
    var channel2 = pusher.subscribe('bot_channel');
    channel2.bind('my_event', function(data) {
        //alert(data.message);
        if(data.message.status == 'OPEN'){
            $('#bot-area').append(data.message.message);
        }else if(data.message.status == 'CANCEL'){
            var d = $.parseJSON(data.message.message);
            //console.log(d.id);
            $('#order_item_header_' + d.id).removeClass('btn-primary');
            $('#order_item_header_' + d.id).addClass('btn-danger');

        }

    });

    Pusher.log = function(message) {
        if (window.console && window.console.log) {
            window.console.log(message);
        }
    };
</script>