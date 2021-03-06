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
    <div class="row">
        <div class="col-xs-6">
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
                    'placeholder'=>'Type in room number',
                    'id' => 'select2-room-search',
                ],
            ]);
            ?>
            <input type="hidden" id="temp_booking_id" value="">
            <input type="text" class="form-control" id="temp_guest" value="" readonly>
        </div>
        <div class="col-xs-3">
            <label>Table</label>
            <select name="dinningtable_id" id="dinningtable_id" class="form-control">
                <?php foreach ($dinningTables AS $dinningTable): ?><option value="<?= $dinningTable['id'] ?>"><?= $dinningTable['title'] ?></option> <?php endforeach; ?></select>
        </div>

        <div class="col-xs-3">
            <label>Steward</label>
            <select name="employee_id" id="employee_id" class="form-control">
                <?php foreach ($stewards AS $steward): ?><option value="<?= $steward['id'] ?>"><?= $steward['full_name'] ?></option> <?php endforeach; ?></select>
        </div>
    </div>


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
            'placeholder'=>'Type in item name',
            'id'=>'select2-item-search',
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
<br /><br />
<div class="form-group">


    <?= GridView::widget([
        'dataProvider'=> $orderDataProvider,
        'filterModel' => $orderSearchModel,
        'columns' => [
            'invoice_item_id',
            [
                'attribute' => 'date_applicable',
                'format' => ['date', 'php:Y-m-d']
            ],
            'room_name',
            'full_name',
            'item_description',
            'order_quantity',
            [
                'attribute'=>'total',
                'format'=>['decimal',2],
            ],
            'order_status',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{new_view}{new_edit}',
                'buttons' => [
                    'new_view' => function ($url, $orderSearchModel) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
                            'title' => Yii::t('app', 'View'),
                            'class'=>'order-item-view', 'attribute-id'=>$orderSearchModel->invoice_item_id,
                            'onclick'=>'DynamicItemsTable.fetchOrderItem('.$orderSearchModel->invoice_item_id.');'
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
            'type' => GridView::TYPE_DEFAULT,
            'heading' => 'Orders',
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
            //'beforeGrid'=>'My fancy content before.',
            //'afterGrid'=>'My fancy content after.',
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
                    <div id="order_item_view_placeholder"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
