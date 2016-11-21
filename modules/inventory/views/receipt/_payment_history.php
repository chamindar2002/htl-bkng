
<div id="payment-summary-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div id="printable-area">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Summary</h4>
                </div>
                <div class="modal-body">

                    <?php //\yii\helpers\VarDumper::dump($data) ?>

                    <div class="form-group">
                        <h4>
                            <?= $data['booking_data']['guest'] ?> | <?= $data['booking_data']['room'] ?>
                        </h4>

                        [Check-in : <?= $data['booking_data']['checkin'] ?> &nbsp; Check-out : <?= $data['booking_data']['checkout'] ?>]

                    </div>
                    <table class="table" width="100%">
                        <tr>
                            <td>
                                <div class="form-group">
                                    <table class="table table-hover" width="100%">
                                        <tr>
                                            <th>Description</th> <th>Charges</th>
                                        </tr>
                                        <?php foreach($data['acomadation'] AS $ac){ ?>
                                            <tr>
                                                <td width="80%"><?= $ac['item_description'] ?></td>
                                                <td class="text-right"><?= number_format($ac['price'], 2) ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td class="text-right"><strong><?= number_format($data['acomadation_charges'], 2) ?></strong></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <table class="table table-hover" width="100%">
                                        <tr>
                                            <th>Description</th> <th>Charges</th>
                                        </tr>
                                        <?php if(isset($data['ordered_items'])): ?>
                                            <?php foreach($data['ordered_items'] AS $ac){ ?>
                                                <tr>
                                                    <td width="80%"><?= $ac['item_description'] ?></td>
                                                    <td  class="text-right"><?= number_format($ac['price'], 2) ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php endif; ?>

                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td  class="text-right" class="text-right"><strong><?= number_format($data['order_item_charges'], 2) ?></strong></td>
                                        </tr>
                                    </table>
                                </div>

                            </td>
                            <td>
                                <div class="form-group">
                                    <table class="table table-hover" width="100%">
                                        <tr><th>#</th><th>Date</th><th>Paid</th></tr>
                                        <?php if(!empty($data['receipts'])): ?>
                                            <?php foreach ($data['receipts'] AS $rc){ ?>
                                                <tr><td><?= $rc['id'] ?></td><td width="80%"><?= $rc['receipt_date'] ?></td><td  class="text-right"><?= number_format($rc['amount_paid'], 2) ?></td></tr>

                                            <?php } ?>
                                        <?php endif; ?>
                                        <tr><td colspan="2" ><strong>Total</strong></td><td  class="text-right"><strong><?= number_format($data['receipt_total'], 2) ?></strong></td></tr>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <table class="table table-hover" width="100%">
                                        <tr>
                                            <td>Total Dues</td><td class="text-right"><?= number_format($data['order_item_charges'] + $data['acomadation_charges'], 2)  ?></td>
                                        </tr>
                                        <tr>
                                            <td>Total Paid</td><td class="text-right"><?= number_format($data['receipt_total'], 2) ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Grand Toal</strong></td>
                                            <td class="text-right"><strong><?= number_format(($data['order_item_charges'] + $data['acomadation_charges']) - $data['receipt_total'], 2) ?></strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <div class="modal-footer">

                <button id='btn' class="btn btn-primary" value='Print' onclick='Bookings.printSummary()'>
                    <i class="glyphicon glyphicon-print"></i>
                </button>

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
