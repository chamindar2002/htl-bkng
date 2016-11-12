<?php
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\View;

?>

 
<div class="form-group grc-booking-room-id">
    <h3>New Order</h3>
    <label>Room</label>
        <?= AutoComplete::widget([    
            'clientOptions' => [
            'source' => $currOccupents,
            'minLength'=>'1', 
            'autoFill'=>true,
            'select' => new JsExpression("function( event, ui ) {
                    $('#temp_booking_id').val(ui.item.id);
                    $('#temp_guest').val(ui.item.full_name);
                 }")],
            'options' => [
                    'class' => 'form-control', //single class
                    'placeholder'=>'Type in room number' 
            ],
           ]);
        ?>
    <input type="hidden" id="temp_booking_id" value="">
    <input type="text" class="form-control" id="temp_guest" value="" readonly>
</div>


<div class="form-group grc-item-master-id">
    <label>Search Item</label>
   
    <?=  AutoComplete::widget([    
                    'clientOptions' => [
                    'source' => $items,
                    'minLength'=>'1', 
                    'autoFill'=>true,
                    'select' => new JsExpression("function( event, ui ) {
                    $('#temp_item_id').val(ui.item.id);
                    $('#temp_item_price').val(ui.item.item_price);
                    $('#temp_item_name').val(ui.item.label);
                 }")],
                 'options' => [
                    'class' => 'form-control', //single class
                    'placeholder'=>'Type in item name' 
                ],
          ]);
    ?>
</div>
<div class="form-group">
    <table class="table table-hover" id="item_list_table">
        <thead>
            <tr>
                <th>Item Id</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr></thead>
        <tbody id="tbody_item_list_table"></tbody>
    </table>
</div>
<input type="hidden" id="temp_item_id" value="">
<input type="hidden" id="temp_item_price" value="">
<input type="hidden" id="temp_item_name" value="">
<input type="hidden" id="temp_row_count" value="">

<div class="form-group">
    <button type="button" class="btn btn-primary" id="btn_place_order">Place Order</button>
</div>  
          
        