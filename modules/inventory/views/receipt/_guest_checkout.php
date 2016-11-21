
<div id="guest-checkout-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Checkout Guest</h4>
            </div>
            <div class="modal-body">
                <?php //\yii\helpers\VarDumper::dump($data) ?>

                <div class="form-group">
                    <h4>
                        <?= $data['booking_data']['guest'] ?> | <?= $data['booking_data']['room'] ?>
                    </h4>

                    [Check-in : <?= $data['booking_data']['checkin'] ?> &nbsp; Check-out : <?= $data['booking_data']['checkout'] ?>]

                    <br />

                    <?php if(strtotime(date('Y-m-d', strtotime($data['booking_data']['checkout'])))- strtotime(date('Y-m-d')) > 0): ?>
                        <span class="label label-danger">Checkout date is on <?= $data['booking_data']['checkout'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <table class="table table-hover" width="100%">
                        <tr>
                            <td>Total Dues</td><td class="text-right"><?= number_format($data['order_item_charges'] + $data['acomadation_charges'], 2)  ?></td>
                        </tr>
                        <tr>
                            <td>Total Received</td><td class="text-right"><?= number_format($data['receipt_total'], 2) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Total to be paid</strong></td>
                            <td class="text-right"><strong><?= number_format(($data['order_item_charges'] + $data['acomadation_charges']) - $data['receipt_total'], 2) ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <?php $disabled = ($data['order_item_charges'] + $data['acomadation_charges']) - $data['receipt_total'] > 0 ? 'disabled' : '' ?>

                <button type="button" class="btn btn-success" onclick="Bookings.confirmCheckout(<?= $data['booking_data']['booking_id'] ?>)" <?= $disabled ?>>Confirm Guest Checkout</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>



    </div>
</div>
