<div class="line-item-box">
    <?php $status_class = $order->order_status == 'OPEN' ? 'btn btn-success' : 'btn btn-danger' ?> 
   <button type="button" class="<?= $status_class ?> lb-full" id="order_item_header_<?= $order->invoice_item_id ?>" title="<?= $order->item_description ?>"><?= substr($order->item_description, 0, 14) ?>
       <span class="badge">X <?= $order->order_quantity ?></span>
   </button>
   <span id="order-item-status_<?= $order->invoice_item_id ?>"><?= $order->order_status ?></span>
   <br />
   <span id="order-item-guest"><?= substr($order->full_name,0,24) ?></span>
   <br />Order #: <?= $order->invoice_item_id ?>
   <br /><span id="order-steward"><?= $order->employee_name != '' ? substr($order->employee_name,0,14) : '' ?></span>
   <br /><span id="order-table"><?= $order->title != '' ? substr($order->title,0,10) : '' ?></span>
   <br />
   <button class="btn btn-default"><i class="glyphicon glyphicon-print"></i></button>
   
</div>