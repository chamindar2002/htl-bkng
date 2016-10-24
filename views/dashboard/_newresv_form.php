<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<form id="f" method="post" action="<?= Url::to(['/dashboard/create-new-rsv']) ?>" style="padding:20px;">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />    
    <span class="label label-primary">New Reservation</span><br/>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="" class='form-control  input-sm'/>
    </div>

    <div class="form-group">
        <label for="start">Checkin</label>
        <input type="text" id="start" name="start" value="<?= $param['start'] ?>"  class='form-control  input-sm' />
    </div>

    <div class="form-group">
        <label for="end">Checkout</label>
        <input type="text" id="end" name="end" value="<?= $param['end'] ?>"  class='form-control  input-sm' />
    </div>

    <div class="form-group">
        <label for="room">Room</label>
        <select id="room" name="room" class='form-control  input-sm'>
            <?php
            foreach ($rooms as $room) {
                $selected = $param['room_id'] == $room['id'] ? ' selected="selected"' : '';
                $id = $room['id'];
                $name = $room['name'];
                print "<option value='$id' $selected>$name</option>";
            }
            ?>
        </select>
    </div>

    <div class="button-group">
        <input type="submit" value="Save" class="btn btn-primary" /> 
        <a href="javascript:close();" class="btn btn-link">Cancel</a>
    </div>
</form>

<script type="text/javascript">
    function close(result) {
        if (parent && parent.DayPilot && parent.DayPilot.ModalStatic) {
            parent.DayPilot.ModalStatic.close(result);
        }
    }

    $("#f").submit(function() {
        var f = $("#f");
        $.post(f.attr("action"), f.serialize(), function(result) {
            close(eval(result));
        });
        return false;
    });

    $(document).ready(function() {
        $("#name").focus();
    });

</script>
