<?php
use yii\helpers\VarDumper;
use yii\helpers\Url;
?>
<form id="f" method="post" action="<?= Url::to(['/dashboard/update-rsv']) ?>" style="padding:20px;">
            <input type="hidden" name="id" class='form-control' value="<?php print $_GET['id'] ?>" />
            <input type="hidden" name="_csrf" value="<?= Yii::$app->request->getCsrfToken() ?>" />    
            <span class="label label-primary">Edit Reservation</span><br/>
                        
            <div class="form-group">
                <label for="name">Start</label>
                <input type="text" id="start" name="start" class='form-control input-sm' value="<?= $data['start'] ?>" />
            </div>
                        
            <div class="form-group">
                <label for="name">End</label>
                <input type="text" id="end" name="end" class='form-control input-sm' value="<?= $data['end'] ?>" />
            </div>
            
            <div class="form-group">
                <label for="name">Room</label>
                <select id="room" name="room"  class='form-control input-sm'>
                    <?php 
                        foreach ($rooms as $room) {
                            $selected = $data['room_id'] == $room['id'] ? ' selected="selected"' : '';
                            $id = $room['id'];
                            $name = $room['name'];
                            print "<option value='$id' $selected>$name</option>";
                        }
                    ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class='form-control input-sm' value="<?= $data['name'] ?>" />
            </div>
            
            <div class="form-group">
                <label for="name">Status</label>
                <select id="status" name="status"  class='form-control  input-sm'>
                    <?php 
                        $options = array("New", "Confirmed", "Arrived", "CheckedOut");
                        foreach ($options as $option) {
                            $selected = $option == $data['status'] ? ' selected="selected"' : '';
                            $id = $option;
                            $name = $option;
                            print "<option value='$id' $selected>$name</option>";
                        }
                    ?>
                </select>                
            </div>
            
            <div class="form-group">
                <label for="name">Paid</label>
                <select id="paid" name="paid"  class='form-control input-sm'>
                    <?php 
                        $options = array(0, 50, 100);
                        foreach ($options as $option) {
                            $selected = $option == $data['paid'] ? ' selected="selected"' : '';
                            $id = $option;
                            $name = $option."%";
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

        $("#f").submit(function () {
            var f = $("#f");
            $.post(f.attr("action"), f.serialize(), function (result) {
                close(eval(result));
            });
            return false;
        });

        $(document).ready(function () {
            $("#name").focus();
        });
    
        </script>