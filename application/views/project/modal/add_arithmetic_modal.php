
<div class="modal fade" id="modal_add_arithmetic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add Arithmetic Operator</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row form-group">
                            <div class="col-md-2">
                                <label>Insert</label>
                            </div>
                            <div class="col-md-4">
                                <select onchange="arithmeticOperatorSelectionComboChange(this)" name="arithmetic_operator_selection_combo" id="arithmetic_operator_selection_combo" class="form-control">
                                    <option value="+" selected = "selected">+</option>
                                    <option value="-">-</option>
                                    <option value="*">*</option>
                                    <option value="/">/</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select onchange="arithmeticOperatorRightPartTypeComboChange(this)" name="arithmetic_operator_right_part_type_selection_combo" id="arithmetic_operator_right_part_type_selection_combo" class="form-control">
                                    <option value="CONSTANT" selected = "selected">CONSTANT</option>
                                    <option value="CONDITION">CONDITION</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-2" id="arithmetic_operator_right_part_value_label">
                                <label>Value</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name ="arithmetic_operator_right_part_value" id="arithmetic_operator_right_part_value" value="" class="form-control"> 
                            </div>
                        </div>

                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_add_variable_ok" onclick="add_arithmetic_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_arithmetic_operator_change" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Warning</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select Arithmetic Operator:</label>
                            </div>
                            <div class="col-md-4">
                                <select name="arithmetic_operator_change_combo" id="arithmetic_operator_change_combo" class="form-control">
                                    <option value="+">+</option>
                                    <option value="-">-</option>
                                    <option value="*">*</option>
                                    <option value="/">/</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_change_op_ok" onclick="change_arithmetic_operator_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


