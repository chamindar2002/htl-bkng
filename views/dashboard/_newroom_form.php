<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<form id="f" style="padding:20px;">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />    
    <span class="label label-primary">New Room</span><br/>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="" class="form-control  input-sm" required/>
    </div>
    <div class="form-group">
        <label for="capacity">Capacity</label>
        <select id="capacity" name="capacity" class="form-control  input-sm">
            <option value='1'>1</option>
            <option value='2'>2</option>
            <option value='4'>4</option>
        </select>
    </div>
    
    <div class="button-group">
        <input type="submit" value="Save" class="btn btn-primary" /> 
        <a href="javascript:close();" class="btn btn-link">Cancel</a>
    </div>
    
</form>

<script type="text/javascript">
    function close(result) {
        DayPilot.Modal.close(result);
    }

    $("#f").submit(function() {
        var f = $("#f");
        $.post('<?= Url::to(['/dashboard/create-new-room']) ?>', f.serialize(), function(result) {
            close(eval(result));
        });
        return false;
    });

    $(document).ready(function() {
        $("#name").focus();
    });

</script>