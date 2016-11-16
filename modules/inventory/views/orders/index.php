<?php
/* @var $this yii\web\View */
$this->title = 'Orders';
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

<script src="https://js.pusher.com/3.2/pusher.min.js"></script>

<script>
Pusher.logToConsole = true;

    var pusher = new Pusher('f61b79b5a60a9cf8df35', {
        encrypted: true
    });

    var channel = pusher.subscribe('kot_channel');
    channel.bind('my_event', function(data) {
        //alert(data.message);
        if(data.message.status == 'OPEN'){
            $('#kot-area').append(data.message.message);
        }else if(data.message.status == 'CANCEL'){
            var d = $.parseJSON(data.message.message);
            //console.log(d.id);
            $('#order_item_header_' + d.id).removeClass('btn-primary');
            $('#order_item_header_' + d.id).addClass('btn-danger');
            //$('#order-item-status_' + d.id).html(d.status);
            //$('#kot-area').append('<hr />'+data.message.message.id);
        }else if(data.message.status == 'UPDATED'){
            var d = $.parseJSON(data.message.message);
            console.log('---->'+d.invoice_item_id)
            $('#order_item_header_' + d.invoice_item_id).removeClass('btn-primary');
            $('#order_item_header_' + d.invoice_item_id).addClass('btn-warning');
            //$('#order-item-status_' + d.invoice_item_id).html('')
        }
        //$('#btnAx').addClass('btn-danger');
        //$('#btnAx').removeClass('btn-primary');
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
            //$('#order-item-status_' + d.id).html(d.status);
            //$('#kot-area').append('<hr />'+data.message.message.id);
        }
        //$('#btnAx').addClass('btn-danger');
        //$('#btnAx').removeClass('btn-primary');
    });

    Pusher.log = function(message) {
        if (window.console && window.console.log) {
            window.console.log(message);
        }
    };
</script>