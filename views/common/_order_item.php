<div class="line-item-box">
    <?php $status_class = $order->order_status == 'OPEN' ? 'btn btn-success' : 'btn btn-danger' ?> 
   <button type="button" class="<?= $status_class ?> lb-full" id="order_item_header_<?= $order->invoice_item_id ?>" title="<?= $order->item_description ?>"><?= substr($order->item_description, 0, 14) ?>
       <span class="badge">X <?= $order->order_quantity ?></span>
   </button>
   <span id="order-item-status_<?= $order->invoice_item_id ?>"><?= $order->order_status ?></span>
   <br />
   <span id="order-item-guest"><?= $order->full_name ?></span>
</div>