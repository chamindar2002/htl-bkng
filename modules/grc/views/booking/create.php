<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcBooking */

$this->title = 'Create Booking';
$this->params['breadcrumbs'][] = ['label' => 'Grc Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-booking-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model, 'guests'=>$guests, 'agents'=>$agents
    ]) ?>

</div>


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
        <button type="button" class="btn btn-success" id="btn_save_room_packages">Save</button>  
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

                var someClassInstance = new SomeClass(result);
                $('#modal_content').append(someClassInstance.view);

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



JS;
$this->registerJs($script);

?>


<script>



function SomeClass (response) {
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
            _html += '<tr><td>'+day+'</td>';
            _html += '<td>';
            _html += '<select id="package_'+i+'"  name="package_'+i+'" class="package_selector form-control">';
                $.each(avl_room_packages, function(i, package){
            _html += '<option value="'+package.package_id+'">'+package.meal_plan_name+'</option>';
                });
            _html += '</select>';
            _html += '</td>';
            _html += '</tr>';
            
        });
        
        _html += '</table>';
        
        $('#booking_summary').html(response.data.guest_data.title+
                    '. '+response.data.guest_data.first_name+
                    ' '+response.data.guest_data.last_name+
                    ' ('+response.data.room_data.name+')');
      
        var e = $('<div />', {
        'class': 'someClass',
        'id': 'sfsdfsdfsdf',
        'html': _html
        });
        
    //console.log(e);
    return e;
    }();
}

</script>

