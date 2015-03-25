<script type="text/javascript">
    $(function() {
        $("#delete_variable_variable_id").on("click", function() {
            waitScreen.show();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: '<?php echo base_url(); ?>' + "variables/delete_variablet",
                data: {
                    project_id: $("#input_project_id").val()
                },
                success: function(data) {
                    $("#modal_delete_project_confirmation").modal('hide');
                    waitScreen.hide();                    
                    window.location.reload();
                }
            });
        });
    });
  
</script>




<div class="modal fade" id="modal_show_variables" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Project Variables</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <table width="100%" border="1" style="border-collapse:collapse; border: 1px solid greenyellow">
                            <tr style="color: #fff; background-color: #090; border: 1px solid greenyellow">
                            <td style="border: 1px solid greenyellow">Variable Name</td>
                            <td style="border: 1px solid greenyellow">Variable Type</td>
                            <td style="border: 1px solid greenyellow">Variable Value</td>
                            <td style="border: 1px solid greenyellow">Delete</td>
                        </tr> 
                        <?php echo form_open('variables/delete_variable'); ?>                
                        <?php
                        foreach ($custom_variables as $cv) {
                            echo " <tr style = 'border: 1px solid greenyellow'>";
                            echo "<td style = 'border: 1px solid greenyellow'>{$cv->variable_name}</td>";
                            echo "<td style = 'border: 1px solid greenyellow'>{$cv->variable_type}</td>";
                            echo "<td style = 'border: 1px solid greenyellow'>{$cv->variable_value}</td>";
                            echo "<td style = 'border: 1px solid greenyellow'><input type='submit' id='button_delete_variable_{$cv->variable_id}' name='button_delete_variable_{$cv->variable_id}' value='Delete' class='btn button-custom' onclick='return is_variable_used_delete_button_clicked({$cv->variable_id})'/></td>";
                            echo "</tr>";
                        }
                        ?>  
                        <input type='hidden' id='delete_variable_variable_id' name='delete_variable_variable_id' value=''/>
                        <input type='hidden' id='delete_variable_project_left_panel_content' name='delete_variable_project_left_panel_content' value=''/>                
                        <?php echo form_close(); ?>
                    </table>
                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn button-custom" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->