
<div class="form-group">
    <label for="">Item</label>
    <input type="text" class="form-control" value="<?= $order->item_description ?>" readonly>
</div>

<div class="form-group">
    <label for="">Quantiy</label>
    <input type="number" name="_oi_order_qty" id="_oi_order_qty" class="form-control" value="<?= $order->order_quantity ?>" >
</div>

<div class="form-group">
    <label for="">Table</label>
    <select name="_oi_dinning_table" id="_oi_dinning_table" class="form-control">
        <?php foreach ($dinningTables AS $dt){ ?>
            <option value="<?= $dt['id'] ?>"  <?= $order->title == $dt['title'] ? 'selected' : '' ?> ><?= $dt['title'] ?></option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <label for="">Steward</label>
    <select name="_oi_steward" id="_oi_steward" class="form-control">
        <?php foreach ($stewards AS $st){ ?>
            <option value="<?= $st['id'] ?>"  <?= $order->employee_name == $st['full_name'] ? 'selected' : '' ?> ><?= $st['full_name'] ?></option>
        <?php } ?>
    </select>
</div>

<div class="form-group">
    <?php if($order->order_status){ ?>
        <input type="button" value="Cancel" class="btn btn-danger" onClick="DynamicItemsTable.cancelOrderItem('<?= $order->invoice_item_id ?>')">

        <input type="button" value="Save" class="btn btn-primary" onClick="DynamicItemsTable.updateOrderItem('<?= $order->invoice_item_id ?>')">
    <?php } ?>
</div>

