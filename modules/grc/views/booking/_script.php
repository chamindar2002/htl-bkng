<?php use yii\helpers\Url; ?>
<form method="post" action="<?= Url::to(['booking/confirm']) ?>" id="booking-package-confirmation-form">
<input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />    
<div id="bkng_package_days_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Package <div id="booking_summary"></div></h4>
      </div>
      <div class="modal-body" id="modal_content">
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" id="btn_save_room_packages">Save</button>  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
</form>

<div id="reservation_search_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reservations<div id="booking_summary"></div></h4>
      </div>
      <div class="modal-body" id="modal_content">
      
        <div class="row">
              <form>
                  <div class="form-group">
                    <label for="rsv_search">Room</label>
                    <select name="rsv_search_rooms" id="rsv_search" class="form-control rsv_search" multiple="multiple">
                      <?php foreach ($rooms As $id=>$name): ?>
                        <option value="<?= $id ?>"><?= $name ?></option>
                      <?php endforeach; ?>  
                    </select>
                    
                  </div>  
              </form>

          </div>
          <div class="row" id="rsv_results" style="margin: 10px !important">
              
          </div>
      </div>      
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" id="btn_search_room_rsvs">Search</button>  
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- ------------------------------------------------------------------------------ -->


<div id="guest_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Guest<div id="booking_summary"></div></h4>
      </div>
      <div class="modal-body" id="modal_content">

        <iframe  frameborder="0" scrolling="no" width="100%" height="auto" src="<?= Url::to('/grc/guest/if-create') ?>" onload="resizeIframe(this)"></iframe>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>