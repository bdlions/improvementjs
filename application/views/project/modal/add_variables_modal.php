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