<div class="line-item-box">
    <?php
    $status_class = 'btn btn-success';
    if($order->order_status == 'CANCELED')
    {
        $status_class = 'btn btn-danger';
    }else if($order->order_status == 'UPDATED')
    {
        $status_class = 'btn btn-warning';
    }

    ?>
   <button type="button" class="<?= $status_class ?> lb-full" id="order_item_header_<?= $order->invoice_item_id ?>" title="<?= $order->item_description ?>"><?= substr($order->item_description, 0, 14) ?>
       <span class="badge" id="order-item-qty_<?= $order->invoice_item_id ?>">X <?= $order->order_quantity ?></span>
   </button>
   <span id="order-item-status_<?= $order->invoice_item_id ?>"><?= $order->order_status ?></span>
   <br />
   <span id="order-item-guest"><?= substr($order->full_name,0,24) ?></span>
   <br />Order #: <?= $order->invoice_item_id ?>
   <br /><span id="order-steward_<?= $order->invoice_item_id ?>"><?= $order->employee_name != '' ? substr($order->employee_name,0,14) : '' ?></span>
   <br /><span id="order-table_<?= $order->invoice_item_id ?>"><?= $order->title != '' ? substr($order->title,0,10) : '' ?></span>
   <br /><span><?= $order->room_name ?></span>
   <br />
   <button class="btn btn-default" data-toggle="modal" data-target="#kot_bot_print_preview" onClick="Printable.fetchOrder(<?= $order->invoice_item_id ?>)"><i class="glyphicon glyphicon-search"></i></button>
   
</div>