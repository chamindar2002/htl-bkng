<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?= GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        'room_name',
        'full_name',
        'checkin_date',
        'checkout_date',
        [
            'label'=> 'agent_name',
            'attribute' => 'agent_name',
            'value' => 'agent_name'
        ],
        [
            'label'=> 'Adults',
            'attribute' => 'no_of_adults',
            'value' => 'no_of_adults',
            'headerOptions' => ['style' => 'width:20px'],
        ],
        [
            'label'=> 'Children',
            'attribute' => 'no_of_children',
            'value' => 'no_of_children',
            'headerOptions' => ['style' => 'width:20px'],
        ],
        [
            'label'=> 'Status',
            'attribute' => 'booking_status',
            'value' => 'booking_status',
            'headerOptions' => ['style' => 'width:20px'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['style' => 'width:200px'],
            'template' => '{new_view}{new_edit}{new_checkout}',
            'buttons' => [
                /*'new_view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'view?id='.$model->booking_id, [
                        'title' => Yii::t('app', 'View Booking'),
                    ]);
                },*/
                'new_view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
                        'title' => Yii::t('app', 'Summary'),
                        'class'=>'booking-payment-summary btn btn-default btn-sm', 'attribute-id'=>$model->booking_id,
                        'onclick'=>'Bookings.fetchBookingData('.$model->booking_id.');'
                    ]);
                },
                'new_edit' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'update?id='.$model->booking_id, [
                        'title' => Yii::t('app', 'Edit Booking'),
                        'class'=>'btn btn-default btn-sm',
                    ]);
                },
                'new_checkout' => function ($url, $model) {
                    return Html::a('Checkout', '#', [
                        'title' => Yii::t('app', 'Checkout Guest'),
                        'class'=>'btn btn-warning btn-sm',
                        'onclick'=>'Bookings.checkoutGuest('.$model->booking_id.');'
                    ]);
                }

            ],


        ],
    ],
    'toolbar' => [
        [
            'content'=>
            /*Html::button('<i class="glyphicon glyphicon-repeat"></i>', [
                'type'=>'button',
                'title'=>'Add Book',
                'class'=>'btn btn-default',
                'id'=>'reloadme',
            ]) . ' '.
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], [
                'class' => 'btn btn-default',
                'title' => 'Reset Grid'
            ]),*/
                Html::button('<i class="glyphicon glyphicon-repeat"></i>', [
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
            'id'=>'grid-demo',
        ],
        'neverTimeout'=>true,
        //'beforeGrid'=>'My fancy content before.',
        //'afterGrid'=>'My fancy content after.',
    ]
]);

?>
<div id="summary_placeholder">

</div>

<script>
    var Bookings = {
        
        checkoutGuest: function (booking_id) {
            event.preventDefault();
            $.ajax({
                url: "<?= Url::to('/inventory/receipt/payment-summary') ?>",
                type: "POST",
                data: {booking_id:booking_id, _csrf: yii.getCsrfToken(), page:'_guest_checkout'}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                    //console.log(response);
                    if(response.result == 'success'){
                        console.log(response.data.htmlmarkup);

                        $('#summary_placeholder').empty();

                        $('#summary_placeholder').html(response.data.htmlmarkup);

                        $('#guest-checkout-modal').modal('show');

                    }
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
        },

        confirmCheckout: function (booking_id) {
            $.ajax({
                url: "<?= Url::to('/grc/booking/checkout-guest') ?>",
                type: "POST",
                data: {booking_id:booking_id, _csrf: yii.getCsrfToken()}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                    //console.log(response);
                    if(response.result == 'success'){
                        //console.log(response.data.htmlmarkup);

                        $('#summary_placeholder').empty();

                        $('#summary_placeholder').html(response.data.htmlmarkup);

                        $('#payment-summary-modal').modal('show');

                    }
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
        },

        fetchBookingData: function (booking_id) {


            event.preventDefault();

            $.ajax({
                url: "<?= Url::to('/inventory/receipt/payment-summary') ?>",
                type: "POST",
                data: {booking_id:booking_id, _csrf: yii.getCsrfToken(), page:'_payment_history'}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                    //console.log(response);
                    if(response.result == 'success'){
                        //console.log(response.data.htmlmarkup);

                        $('#summary_placeholder').empty();

                        $('#summary_placeholder').html(response.data.htmlmarkup);

                        $('#payment-summary-modal').modal('show');

                    }
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
        },
        printSummary: function () {
            var prntstyle = '<link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/sbadmin/bootstrap/css/bootstrap.min.css" />';

            w=window.open(null, 'Print_Page', 'scrollbars=yes');
            w.document.write(prntstyle + jQuery('#printable-area').html());
            w.document.close();
            w.print();
        }
    }
</script>