<?php
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\View;
use fedemotta\datatables\DataTables;
use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
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
          
<div class="form-group">
    
    
    <?= GridView::widget([
      'dataProvider'=> $orderDataProvider,
      'filterModel' => $orderSearchModel,
      'columns' => [
            'room_name',
            'full_name',
            'item_description',
            'order_quantity',
            'total',
             [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{new_view}{new_edit}',
              'buttons' => [
                'new_view' => function ($url, $orderSearchModel) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
                                'title' => Yii::t('app', 'View'),
                                'class'=>'order-item-view', 'attribute-id'=>$orderSearchModel->invoice_item_id
                    ]);
                },
                /*'new_edit' => function ($url, $orderSearchModel) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'update?id='.$orderSearchModel->id, [
                                'title' => Yii::t('app', 'New Action1'),
                    ]);
                } */       
              ],


        ],
      ],
      'toolbar' => [
          [
              'content'=>Html::button('<i class="glyphicon glyphicon-repeat"></i>', [
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
              'type' => GridView::TYPE_PRIMARY,
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
           'id'=>'grid-demo-invn-items',
       ],
       'neverTimeout'=>true,
       'beforeGrid'=>'My fancy content before.',
       'afterGrid'=>'My fancy content after.',
      ]
  ]);

  ?>

    
</div>


<div id="modal-item-view" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Order Item View</h4>
      </div>
      <div class="modal-body">
       <div id="order-items-view-placeholder">
            <table class="table" id="order-items-view-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="order-items-view-table-tbody"></tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
