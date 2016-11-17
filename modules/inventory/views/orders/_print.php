<table class="" width="75%">
    <tr><td colspan="2" align="center"><h4><?= $order->room_name ?> | <?= $order->full_name ?></h4><hr /></td></tr>
    <tr><td>Order#</td><td><?= $order->id ?></td></tr>
    <tr><td>Date</td><td><?= date('Y-m-d', strtotime($order->date_applicable)) ?></td></tr>
    <tr><td>Table</td><td><?= $order->title ?></td></tr>
    <tr><td>Steward</td><td><?= $order->employee_name ?></td></tr>
    <tr>
        <td colspan="2">
            <table class="table .table-condensed">
                <tr><th>Item</th><th>Quantity</th></tr>
                <tr><td><?= $order->item_description ?></td><td><?= $order->order_quantity ?></td></tr>
            </table>
        </td>
    </tr>


</table>