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

$this->title = 'Create Booking';
$this->params['breadcrumbs'][] = ['label' => 'Grc Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-booking-create">

     <?= $this->render('_form', [
        'model' => $model, 'agents'=>$agents, 'rooms'=>$rooms
    ]) ?>
  
</div>



 <?= $this->render('_script', [
        'model' => $model, 'agents'=>$agents, 'rooms'=>$rooms
    ]) ?>




<?php
$script = <<< JS


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
    
    $('#grcbooking-guest_id').val(repo.id);
    return markup;
  }

  function formatRepoSelection (repo) {
    
    if(typeof repo.first_name == 'undefined'){
        //return repo.address || repo.text;
        return repo.text;;
    }else{
        //return repo.fullname;
        return repo.title + ' ' + repo.first_name + ' ' + repo.last_name;
    }
  }


 

JS;
$this->registerJs($script);

?>


<script>
$('#open_crete_guest_modal').click(function () {
    BookingUtilities.openGuestModal()
})

$('#grcbooking-reservation_id').click(function(){
    BookingUtilities.openResvSearchModal();
});

$('#btn_search_room_rsvs').click(function(){
   BookingUtilities.searchReservations();
});

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
        //console.log(results.bookings_on_reservation.current_booking);
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

   openGuestModal: function () {
       $('#guest_modal').modal('show');
   }
  
  
};

function resizeIframe(obj) {
    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}
</script>

