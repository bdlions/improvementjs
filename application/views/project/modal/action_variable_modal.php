<div class="modal fade" id="modal_action_or_block_selection" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Condition or Action</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row form-group">
                            <!--                            <div class="col-md-4">Select an option:</div>-->
                            <div class="col-md-6">
                                <select name="actonSelectionCombo" id="actonSelectionCombo" onchange="actionComboChange(this)" class="form-control">
                                    <option value="condition" selected = "selected">Condition</option>
                                    <option value="action">Action</option>
                                    <option value="change_variable_value">Change Variable Value</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-10">
                                <ol id="condition_modal_selected_item" style="font-size:8pt;">
                                    <li class='ui-widget-content form-control'>IF condition THEN action</li>
                                    <li class='ui-widget-content form-control'>IF condition THEN action ELSE action</li>
                                </ol>

                                <ol id="action_modal_selected_item" style="font-size:8pt;">
                                    <?php
                                    foreach ($fObjectArray as $key => $objectList) {
                                        if ($key == "action") {
                                            foreach ($objectList as $customObj) {
                                                $displayNatural = $customObj->natural;
                                                $searchedPattern = array();
                                                $replacementPattern = array();
                                                foreach ($customObj->parameters as $param) {
                                                    $searchedPattern[] = "$" . $param->name;
                                                    $replacementPattern[] = $param->default;
                                                }

                                                $displayNatural = str_replace($searchedPattern, $replacementPattern, $displayNatural);
                                                echo "<li class='ui-widget-content form-control'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</li>";
                                            }
                                        }
                                    }
                                    ?>
                                </ol> 
                                <div id="action_variable_selection_list">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="action_variable_selection_part" style="float:left; padding-left:10px;"></div>
                                        </div>    
                                    </div>
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingVariables">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseVariables" aria-expanded="true" aria-controls="collapseVariables">
                                                        Variables
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseVariables" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingVariables">
                                                <div class="panel-body">
                                                    <?php
                                                    foreach ($custom_variables as $cv) {
                                                        echo "<a style='text-decoration:none;' href='#' id='a_v_s' onclick='actionVariableSelection(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                                        echo "<br/>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_action_or_selection_ok" onclick="action_or_block_selection_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->