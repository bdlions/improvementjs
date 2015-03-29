
<div class="modal fade" id="modal_logical_connector_boolean" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Boolean Variables</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div id="logical_connector_boolean_variables_left_part" style="float:left; padding-left:10px;"></div>
                                <div id="logical_connector_boolean_variables_middle_part" style="float:left; padding-left:10px;"></div>
                                <div id="logical_connector_boolean_variables_right_part" style="float:left; padding-left:10px;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingBooleanLeft">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseBooleanLeft" aria-expanded="true" aria-controls="collapseBooleanLeft">
                                                    Boolean Variables
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseBooleanLeft" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingBooleanLeft">
                                            <div class="panel-body">
                                                <?php
                                                foreach ($custom_variables as $cv) {
                                                    //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                                    if ($cv->variable_type == "BOOLEAN") {
                                                        echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_l_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariable(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                                        echo "<br/>";
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingBooleanCondition">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseBooleanCondition" aria-expanded="true" aria-controls="collapseBooleanCondition">
                                                    Comparison
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseBooleanCondition" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingBooleanCondition">
                                            <div class="panel-body">
                                                <?php
                                                echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_m_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is equal to' />is equal to</a>";
                                                echo "<br/>";
                                                echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_m_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is not equal to' />is not equal to</a>";
                                                echo "<br/>";
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingBooleanRight">
                                            <h4 class="panel-title">
                                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseBooleanRight" aria-expanded="true" aria-controls="collapseBooleanRight">
                                                    Boolean Value
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseBooleanRight" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingBooleanRight">
                                            <div class="panel-body">
                                                <?php
                                                echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_r_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='true' />true</a>";
                                                echo "<br/>";
                                                echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_r_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='false' />false</a>";
                                                echo "<br/>";
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type = "hidden" value="" name="logical_connector_boolean_variable_selected_index" id="logical_connector_boolean_variable_selected_index" />
                    <input type = "hidden" value="" name="logical_connector_boolean_variable_selected_anchor_id" id="logical_connector_boolean_variable_selected_anchor_id" />
                    <input type = "hidden" value="" name="logical_connector_boolean_variable_selected_anchor_title" id="logical_connector_boolean_variable_selected_anchor_title" />                
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="logical_connector_boolean_ok_pressed" onclick="logical_connector_boolean_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->