<script type="text/javascript">
    $(function() {
        $("#button_delete").on("click", function() {
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: '<?php echo base_url(); ?>' + "variables/delete_variable",
                data: {
                    delete_variable_variable_id: $("#delete_variable_variable_id").val(),
                    delete_variable_project_left_panel_content: $("#delete_variable_project_left_panel_content").val()
                },
                success: function(data) {
                    if (data.status == 1) {
                        $("#modal_delete_variable_confirm").modal('hide');
                        window.location.reload();
                    } else
                        $("#label_show_messages_content").html("Error while deleting the variable.");
                        $("#modal_show_messages").modal('show');
                }
            });
        });
    });
</script>
<div class="modal fade" id="modal_add_variables" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <?php echo form_open("variables/create_variable"); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add Variables</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row form-group">
                            <div class="col-md-4">
                            <label >Variable Name</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name ="add_variable_name" id="add_variable_name" value="" >
                            </div> 
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                            <label >Variable Type</label>
                            </div>
                            <div class="col-md-4">
                                <select onchange="addVariableTypeComboChange(this)" name="add_variable_type_selection_combo" id="add_variable_type_selection_combo" class="form-control">
                                    <option value="BOOLEAN" selected = "selected">BOOLEAN</option>
                                    <option value="NUMBER">NUMBER</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select onchange="addVariableValueComboChange(this)" name="add_variable_value_selection_combo" id="add_variable_value_selection_combo" class="form-control">
                                        <option value="TRUE" selected = "selected">TRUE</option>
                                        <option value="FALSE">FALSE</option>
                                    </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4" id="add_variable_value_label"><label>Variable value</label></div>
                            <div class="col-md-8" id="add_variable_value_text">
                                <input type="text"  name ="add_variable_value" id="add_variable_value" value="" class="form-control">
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                       <input type = "hidden" value="" name="project_left_panel_content_backup" id="project_left_panel_content_backup" />
                    </div>

                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                
                <input type="submit" class="btn btn-success" id="button_add_variable_ok" onclick="return button_add_variable_ok_pressed()" value="Save"/>  
                        <?php echo form_close(); ?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_delete_variable_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Variables</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row form-group">
                        <div class ="col-sm-2"></div>
                        <label class="col-sm-10 control-label">Are you sure to delete this Variables?</label>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <div class ="col-md-6">

                </div>
                <div class ="col-md-3">
                    <button style="width:100%" id="button_delete" name="button_delete" value="" class="form-control btn btn-success pull-right">Delete</button>
                </div>
                <div class ="col-md-3">
                    <button style="width:100%" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


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
                            <?php // echo form_open('variables/delete_variable'); ?>                
                            <?php
                            foreach ($custom_variables as $cv) {
                                echo " <tr style = 'border: 1px solid greenyellow'>";
                                echo "<td style = 'border: 1px solid greenyellow'>{$cv->variable_name}</td>";
                                echo "<td style = 'border: 1px solid greenyellow'>{$cv->variable_type}</td>";
                                echo "<td style = 'border: 1px solid greenyellow'>{$cv->variable_value}</td>";
                                echo "<td style = 'border: 1px solid greenyellow'><input type='button' id='button_delete_variable_{$cv->variable_id}' name='button_delete_variable_{$cv->variable_id}' value='Delete' class='btn button-custom' onclick='is_variable_used_delete_button_clicked({$cv->variable_id})'/></td>";
                                echo "</tr>";
                            }
                            ?>  
                            <input type='hidden' id='delete_variable_variable_id' name='delete_variable_variable_id' value=''/>
                            <input type='hidden' id='delete_variable_project_left_panel_content' name='delete_variable_project_left_panel_content' value=''/>                
                            <?php // echo form_close(); ?>
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