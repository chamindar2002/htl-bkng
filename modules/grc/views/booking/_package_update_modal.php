<?php

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\Alert;

AppAsset::register($this);
?>

<div id="update_invitem_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reservations
            <div id="booking_summary">
             <?= $data['guest_data']['title'] ?>.<?= $data['guest_data']['first_name'] ?>&nbsp;<?= $data['guest_data']['last_name'] ?>      
             &nbsp;[<?= $data['room_data']['name'] ?>]
               
            </div>
        </h4>
      </div>
      <div class="modal-body" id="modal_content_update">
      
        <div class="row">
            
            <?php if($invoice != null){ ?>
            <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Day</th>
                    <th>Package</th>
                  </tr>
                </thead>
                <tbody>
                
                <?php foreach($invoice->invnInvoiceItems As $invitems): ?>
                    
                       <?php if($invitems->deleted == 0){ ?> 
                            
                        <tr>
            <!--            <td><?= $invitems->id; ?></td> 
                            <td><?= $invitems->price; ?></td>-->
                            <td><?= $invitems->item_description; ?></td>
                            <td>
                                <select name="sel_packages" invitem_id="<?= $invitems->id ?>" class="package_selector form-control">
                                    <?php foreach($available_packages As $ap): ?>
                                       <?php if($ap->package_id == $invitems->package_id){ ?>
                                            <option value="<?= $ap->package_id ?>" title="<?= $ap->price ?>" selected><?= $ap->meal_plan_name ?></option>
                                       <?php }else{ ?>
                                            <option value="<?= $ap->package_id ?>" title="<?= $ap->price ?>"><?= $ap->meal_plan_name ?></option>
                                       <?php } ?>     
                                    <?php endforeach; ?>
                                </select>
                                <div id="<?= $invitems->id ?>_result"></div>
                                <?php //print_r($available_packages) ?>
                            </td>
                        </tr>   
                        
                       <?php } ?>
                       
                    <?php endforeach; ?>
                
                </tbody>
            </table>
            <?php }else{ ?>
            <form method="post" action="<?= Url::to(['booking/confirm']) ?>" id="booking-package-confirmation-form">
            <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Day</th>
                    <th>Package</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i=0; foreach(json_decode($data['date_allocation']['date_allocation']) AS $dt): ?>
                    
                    <tr>
                        <td><?= $dt ?><input type="hidden" name="day_<?= $i ?>" value="<?= $dt ?>"></td>
                        <td>
                            
                         <select id="package_<?= $i ?>"  name="package_<?= $i ?>" class="package_selector form-control" required>
                            <?php foreach(json_decode($data['available_room_packages']) AS $package): ?> 
                            <option value="<?= $package->package_id ?>"><?= $package->meal_plan_name ?></option>
                            <?php endforeach; ?>
                         </select>
                        </td>
                    </tr>
                    
                
                <?php $i++; endforeach; ?>
                </tbody>    
            </table>
            <input type="hidden" name="count" value="<?= $i ?>">
            <input type="hidden" name="booking_id" value="<?= $data['booking_data']['id'] ?>">
            <input type="hidden" name="reservation_id" value="<?= $data['reservation_data']['id'] ?>">
            </form>    
                    
            <?php } ?>        

        </div>
          
      </div>      
      <div class="modal-footer">
          <?php if($invoice == null){ ?>
            <button type="button" class="btn btn-success" id="btn_save_package_data">Save</button>
          <?php } ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- -----------------------------------end package updatem modal -------------------------------------