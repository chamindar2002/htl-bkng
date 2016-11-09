<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcBooking */

$this->title = 'Booking#'.$model->id .' | '.$model->guest->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<br/>
   <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
   </p>
   <div class="grc-booking-form">
   <ul class="list-group">
      <li class="list-group-item">
        <span class="badge"> <span class="badge"><?= $data['reservation_data']['start'] ?></span></span>
         Check in date
      </li>
      <li class="list-group-item">
         <span class="badge"> <span class="badge"><?= $data['reservation_data']['end'] ?></span></span>
          Check out date
      </li>
      <li class="list-group-item">
          <span class="badge"> <span class="badge"><?= $data['room_data']['name'] ?></span></span>
                Room
       </li>
       <li class="list-group-item">
          <span class="badge"> <span class="badge"><?= $data['booking_data']['no_of_adults'] ?></span></span>
                Adults
       </li>
       <li class="list-group-item">
          <span class="badge"> <span class="badge"><?= $data['booking_data']['no_of_children'] ?></span></span>
                Children
       </li>
       
       <li class="list-group-item">
          <span class="badge"> <span class="badge"><?= $data['booking_data']['status'] ?></span></span>
                Status
       </li>
           
   </ul>
   
       
   <?php if($invoice == null){ ?>    
       <span class="label label-danger">No invoice data to show.</span>
   <?php }else{ ?>
       
       <h3>Invoice # <?= $invoice->attributes['id'] ?></h3>    
       
       <table class="table table-hover">
         <thead>
            <tr>
               <th>No.</th>
               <th>Description</th>
               <th style='text-align: right'>Price</th>
             </tr>
         </thead>
         <tbody>
           <?php $i=1; $total = 0; foreach ($invoice->invnInvoiceItems As $invnitm): ?>
           
           <?php if($invnitm->deleted == 0){ ?>  
           <tr>
               <td><?= $i ?></td>
               <td>Room Package Charges <?= $invnitm->item_description ?></td>
               <td style='text-align: right'><?= number_format($invnitm->price, 2) ?></td>
           </tr>
           <?php $total += $invnitm->price; ?>
           <?php } ?>
         </tbody>
           <?php $i++;  endforeach; ?>
         
         <tr>
            <td></td>
            <td style='text-align: right'>Total</td><td style='text-align: right'><?= number_format($total,2) ?></td>        
         </tr>
         <tr>
             <td></td>
             <td style='text-align: right'>Paid</td><td style='text-align: right'>0.00</td>
         </tr>
         <tr>
             <td></td>
             <td style='text-align: right'>Sub total</td><td style='text-align: right'><?= number_format($total,2) ?></td>
         </tr>
       </table>
       
   <?php } ?>    
   
   <?php //yii\helpers\VarDumper::dump($invoice->invnInvoiceItems); ?>
   <?php //yii\helpers\VarDumper::dump($data); ?>
   

    <?php /* DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'reservation_id',
            //'package_id',
            'agent_id',
            'no_of_adults',
            'no_of_children',
            'status',
            'created_by',
            'created_at',
            'updated_at',
        ],
    ]) */ ?>

</div>
