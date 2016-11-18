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
            'value' => 'no_of_adults'
        ],
        [
            'label'=> 'Children',
            'attribute' => 'no_of_children',
            'value' => 'no_of_children'
        ],
        [
            'label'=> 'Status',
            'attribute' => 'booking_status',
            'value' => 'booking_status'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{new_view}{new_edit}',
            'buttons' => [
                /*'new_view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 'view?id='.$model->booking_id, [
                        'title' => Yii::t('app', 'View Booking'),
                    ]);
                },*/
                'new_view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', '#', [
                        'title' => Yii::t('app', 'View'),
                        'class'=>'booking-payment-summary', 'attribute-id'=>$model->booking_id,
                        'onclick'=>'Bookings.fetchBookingData('.$model->booking_id.');'
                    ]);
                },
                'new_edit' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'update?id='.$model->booking_id, [
                        'title' => Yii::t('app', 'Edit Booking'),
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

<script>
    var Bookings = {
        fetchBookingData: function (booking_id) {

            alert(booking_id);

            $.ajax({
                url: "<?= Url::to('/inventory/receipt/payment-summary') ?>",
                type: "POST",
                data: {booking_id:booking_id, _csrf: yii.getCsrfToken()}, //JSON
                dataType: "json",
                cache: false,

                success:function(response, textStatus, jqXHR) {
                    console.log(response);
                    if(response.result == 'success'){

                        $('#order_item_view_placeholder').empty();

                        $('#order_item_view_placeholder').html(response.data);

                        $('#modal-item-view').modal('show');
                    }
                },
                error:function(jqXHR, textStatus, errorThrown) {

                }
            });
        }
    }
</script>