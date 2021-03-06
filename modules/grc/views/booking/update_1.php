<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use app\assets\DpAsset;
use app\assets\Select2Asset;
 
DpAsset::register($this);
Select2Asset::register($this);
/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcBooking */

$this->title = 'Update Booking: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grc Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grc-booking-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model, 'agents'=>$agents, 'rooms'=>$rooms, 'invoice'=>$invoice, 'available_packages'=>$available_packages
    ]) ?>

</div>

<div id="update_invitem_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reservations<div id="booking_summary"></div></h4>
      </div>
      <div class="modal-body" id="modal_content_update">
      
        <div class="row">
              
            <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Day</th>
                    <th>Package</th>
                  </tr>
                </thead>
                <tbody>
                <?php if($invoice){ ?>
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
                <?php } ?>    
                </tbody>
            </table>

        </div>
          
      </div>      
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<hr/>
start<input type="text" name="start"  id="start" value="<?= $model->reservation->attributes['start'] ?>"><br/>
end <input type="text" name="end"  id="end" value="<?= $model->reservation->attributes['end'] ?>"><br/>
<input type="button" value="check availability" id="btn_check_availability">
<input type="button" value="update" id="btn_update_resv" disabled="disabled">

<?php \yii\helpers\VarDumper::dump($model->reservation->attributes); ?>


 <?= $this->render('_script', [
        'model' => $model, 'agents'=>$agents, 'rooms'=>$rooms, 'invoice'=>$invoice
    ]) ?>

<?php

$script = <<< JS
$('form#{$model->formName()}').on('beforeSubmit', function(e)
{
	var \$form = $(this);
	$.post(
	    \$form.attr("action"), //serialize form
	    \$form.serialize()		
	)
	.done(function(result){
            if(result.result == 'success')
	    {
                if(result.data.booking_data.status != 'OPEN'){ //if OPEN invoice items to be fetched from db

                    $('#bkng_package_days_modal').modal('show');
                
                    var bookingObj = new Booking(result);

                    $('#modal_content').append(bookingObj.view);
                }

                //$('#update_invitem_modal').modal('show');

		//$.pjax.reload({container: '#commodity-grid'});
	    }else{
		//$(\$form).trigger("reset");
		$("#message").html(result.message);
	    }			
	}).fail(function(){
		console.log("server error");
	});
	return false;
});

$(".rsv_search").select2({
     maximumSelectionLength: 1
});

$(".js-data-example-ajax").select2({
  allowClear: true,
  ajax: {
    url: 'fetch-guests',
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        page: params.page
      };
    },
    processResults: function (data, params) {
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used
      params.page = params.page || 1;

      return {
        results: data.items,
        pagination: {
          more: (params.page * 30) < data.total_count
        }
      };
    },
    cache: true
  },
  escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
  minimumInputLength: 1,
  templateResult: formatRepo, // omitted for brevity, see the source of this page
  templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
});
function formatRepo (repo) {
    if (repo.loading) return repo.text;

    var markup = '<div class="clearfix">' +
    '<div class="col-sm-1">' +
    '<img src="' + '#' + '" style="max-width: 100%" />' +
    '</div>' +
    '<div clas="col-sm-10">' +
    '<div class="clearfix">' +
    '<div class="col-sm-6">' + repo.first_name + ' ' + repo.last_name +'</div>' +
    '</div>';

    if (repo.address) {
      markup += '<div>' + repo.address + '</div>';
    }

    markup += '</div></div>';
    return markup;
  }

  function formatRepoSelection (repo) {
    
    if(typeof repo.first_name == 'undefined'){
        //return repo.address || repo.text;
        return '{$model->guest->getFullName()}';
    }else{
       $('#grcbooking-guest_id').val(repo.id);
       return repo.title + ' ' + repo.first_name + ' ' + repo.last_name;
    }
  }

JS;
$this->registerJs($script);

?>


<script>
$('#btn_check_availability').click(function(){
   BookingUtilities.checkAvailability();   
})

$('#btn_update_resv').click(function(){
   BookingUtilities.updateReservations();   
})    
    
$('#grcbooking-reservation_id').click(function(){
    BookingUtilities.openResvSearchModal();
});

$('#btn_search_room_rsvs').click(function(){
   BookingUtilities.searchReservations();
});

$(".package_selector").change(function () {
   BookingUtilities.updateInvItem($(this));     
});

$('#open_update_items_modal').click(function(){
   $('#update_invitem_modal').modal('show'); 
});
    
function Booking(response) {
// other properties and functions...

    this.view = function () {
        
        //console.log(response.data.date_allocation.no_of_nights);
        //console.log(response.data.date_allocation.date_allocation);
        
        var date_allocation_data = $.parseJSON(response.data.date_allocation.date_allocation);
        var avl_room_packages = $.parseJSON(response.data.available_room_packages);
        
       
        $("#modal_content").empty();
        var _html = '<table class="table table-hover">';
        _html += '<thead><tr><th>Date</th><th>Package</th></tr></thead>';
        
        $.each(date_allocation_data, function(i, day) {
            _html += '<tr><td>'+day+'<input type="hidden" name="day_'+i+'" value="'+day+'">'+'</td>';
            _html += '<td>';
            _html += '<select id="package_'+i+'"  name="package_'+i+'" class="package_selector form-control" required>';
                 $.each(avl_room_packages, function(i, package){
            _html += '<option value="'+package.package_id+'">'+package.meal_plan_name+'</option>';
                });
            _html += '</select>';
            _html += '</td>';
            _html += '</tr>';
            
        });
        
        _html += '</table>';
        _html += '<input type="hidden" name="count" value="'+date_allocation_data.length+'">';
        _html += '<input type="hidden" name="booking_id" value="'+response.data.booking_data.id+'">';
        _html += '<input type="hidden" name="reservation_id" value="'+response.data.reservation_data.id+'">';
        
        $('#booking_summary').html(response.data.guest_data.title+
                    '. '+response.data.guest_data.first_name+
                    ' '+response.data.guest_data.last_name+
                    ' ('+response.data.room_data.name+')');
      
        var e = $('<div />', {
        'class': 'booking-content',
        'id': 'booking-content',
        'html': _html
        });
        
       
        
    //console.log(e);
    return e;
    }();
}

var BookingUtilities = {
  openResvSearchModal: function () {
    $('#reservation_search_modal').modal('show');
  },
  searchReservations: function () {
     
     $.ajax({
        url: "<?= Url::to('search-reservations') ?>",
        type: "POST",
        data: {room_id : $('#rsv_search').val(), room_label: $('#rsv_search option:selected').text()}, //JSON
        dataType: "json",
        cache: false,

        success:function(data, textStatus, jqXHR) {
              $r = BookingUtilities.appendResvData(data);
              $("#rsv_results").empty();
              $('#rsv_results').append($r);
        },
        error:function(jqXHR, textStatus, errorThrown) {
           console.log("request failed" +textStatus);
        }
       });

  },
  appendResvData: function (results){
        
        var title = '';
        var _html = '<div class="list-group">';
        var room_name = results.room_name;
             $.each(results.resv_data, function(i, data){
                 title = data.name+': '+data.start+' -> ' + data.end + ' ['+room_name+']';
                _html +=  '<a href="#" id="'+data.id+'" title="'+title+'" class="list-group-item" onClick="BookingUtilities.selectResvItem(this.title,this.id)">'+title+ '</a>';
             });
            _html += '</div>';
        
        var e = $('<div />', {
        'class': 'booking-content',
        'id': 'booking-content',
        'html': _html
        });
        
        return e;
  },
  selectResvItem: function(text, id){
     $('#grcbooking-reservation_id').val(id);
     $('#reservation_summary').html('<span class="label label-success">'+text+'</span>');
     $('#reservation_search_modal').modal('hide');
  },     
  
  updateInvItem: function(attributes){
      
      $.ajax({
        url: "<?= Url::to('update-package-inv-item') ?>",
        type: "POST",
        data: {invitem_id : attributes.attr('invitem_id'), package_id: attributes.val()}, //JSON
        dataType: "json",
        cache: false,

        success:function(data, textStatus, jqXHR) {
           if(data.result == 'success'){
               var result = $('#'+data.data.invitem_id+'_result');
               result.html('<span class="label label-success">Saved</span>')
           }  
        },
        error:function(jqXHR, textStatus, errorThrown) {
           
        }
     });
  },
  
  checkAvailability: function()
  {
      $.ajax({
        url: "<?= Url::to('check-resv-availability') ?>",
        type: "POST",
        data: {checkin : $('#start').val(), 
               checkout: $('#end').val(),
               room_id: <?= $model->reservation->attributes['room_id'] ?>,
               reservation_id: <?= $model->reservation->attributes['id'] ?>
            }, //JSON
        dataType: "json",
        cache: false,

        success:function(data, textStatus, jqXHR) {
           if(data.result == 'success'){
               
               $('#btn_update_resv').prop('disabled',false);
           }else{
               $('#btn_update_resv').prop('disabled',true);
           }
           
        },
        error:function(jqXHR, textStatus, errorThrown) {
           
        }
     });
  },
  
  updateReservations: function(){
  
       $.ajax({
        url: "<?= Url::to('update-reservation-dates') ?>",
        type: "POST",
        data: {checkin : $('#start').val(), 
               checkout: $('#end').val(),
               room_id: <?= $model->reservation->attributes['room_id'] ?>,
               reservation_id: <?= $model->reservation->attributes['id'] ?>
            }, //JSON
        dataType: "json",
        cache: false,

        success:function(data, textStatus, jqXHR) {
           if(data.result == 'success'){
               
               $('#btn_update_resv').prop('disabled',false);
           }else{
               $('#btn_update_resv').prop('disabled',true);
           }
           
        },
        error:function(jqXHR, textStatus, errorThrown) {
           
        }
     });
     
  }
  
  
  
};


</script>


