<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<form id="f" style="padding:20px;">
    <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />  
    <input type="hidden" name="id" value="<?php print $data['id'] ?>" />
    <span class="label label-primary">Edit Room</span><br/>
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?= $data['name'] ?>" class="form-control input-sm" required/>
    </div>
    
    <div class="form-group">
        <label for="capacity">Capacity</label>
        <select id="capacity" name="capacity" class="form-control input-sm">
            <?php
            $options = array(1, 2, 4);
            foreach ($options as $option) {
                $selected = $option == $data['capacity'] ? ' selected="selected"' : '';
                $id = $option;
                $name = $option;
                print "<option value='$id' $selected>$name</option>";
            }
            ?>
        </select>   
    </div>
    
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" class="form-control input-sm">
            <?php
            $options = array("Ready", "Cleanup", "Dirty");
            
            foreach ($options as $option) {
                $selected = $option == $data['status'] ? ' selected="selected"' : '';
                $id = $option;
                $name = $option;
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
        DayPilot.Modal.close(result);
    }

    $("#f").submit(function() {
        var f = $("#f");
        $.post('<?= Url::to(['/dashboard/update-room']) ?>', f.serialize(), function(result) {
            close(eval(result));
        });
        return false;
    });

    $(document).ready(function() {
        $("#name").focus();
    });

</script>
