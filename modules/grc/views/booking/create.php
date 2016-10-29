<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcBooking */

$this->title = 'Create Booking';
$this->params['breadcrumbs'][] = ['label' => 'Grc Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-booking-create">

    <h3><?= Html::encode($this->title) ?></h3>
    <?= $this->render('_form', [
        'model' => $model, 'guests'=>$guests, 'agents'=>$agents, 'rooms'=>$rooms
    ]) ?>
  
</div>

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
                $('#bkng_package_days_modal').modal('show');
                //$('#modal_content').html(result.result);

                var bookingObj = new Booking(result);
                $('#modal_content').append(bookingObj.view);

                //console.log(result);
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
    console.log(repo.id);
    return markup;
  }

  function formatRepoSelection (repo) {
    
    return repo.address || repo.text;
  }


$(document).ready(function(){

    $(".js-data-example-ajax").select2({
      ajax: {
        url: "fetch-guests",
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            q: params.term, // search term
            page: params.page
          };
        },
        processResults: function (data, page) {
          // parse the results into the format expected by Select2.
          // since we are using custom formatting functions we do not need to
          // alter the remote JSON data
          return {
            results: data.items  
          };
        },
        cache: true
      },
      escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
      minimumInputLength: 1,
      templateResult: formatRepo, // omitted for brevity, see the source of this page
      templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
      
        
    });
  });   

JS;
$this->registerJs($script);

?>


<script>
$('#grcbooking-reservation_id').click(function(){
    BookingUtilities.openResvSearchModal();
});

$('#btn_search_room_rsvs').click(function(){
   BookingUtilities.searchReservations();
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
  }        
  
  
  
  
};
</script>

