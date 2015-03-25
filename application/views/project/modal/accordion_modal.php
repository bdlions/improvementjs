<!-- start of arithmetic operator condition div modal -->
<div class="modal fade" id="modal_arithmetic_operator_condition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Current Conditions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div id="arithmetic_operator_condition_left_part" style="float:left; padding-left:10px;"></div>                
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div id="arithmetic_operator_condition_accordion" style="width:300px; font-size:10pt; float:left;">
                                    <?php
                                    foreach ($fObjectArray as $key => $objectList) {
                                        if ($key != "comparison" && $key != "action") {
                                            echo "<h5><a href='#'>{$key}</a></h5>";
                                            echo "<div><p>";
                                            foreach ($objectList as $customObj) {
                                                $displayNatural = $customObj->natural;
                                                $searchedPattern = array();
                                                $replacementPattern = array();
                                                foreach ($customObj->parameters as $param) {
                                                    $searchedPattern[] = "$" . $param->name;
                                                    $replacementPattern[] = $param->default;
                                                }
                                                $displayNatural = str_replace($searchedPattern, $replacementPattern, $displayNatural);
                                                echo "<a style='text-decoration:none;' href='#' id='a_o_c_l_p' onclick='arithmeticOperatorConditionSetChangingStmt(this)'><input id='natural' type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                echo "<br/>";
                                            }
                                            echo "</p></div>";
                                        }
                                    }
                                    echo "<h5><a href='#'>Number Variables</a></h5>";
                                    echo "<div><p>";
                                    foreach ($custom_variables as $cv) {
                                        //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                        if ($cv->variable_type == "NUMBER") {
                                            echo "<a style='text-decoration:none;' href='#' id='a_o_c_l_p' onclick='arithmeticOperatorConditionSetChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                            echo "<br/>";
                                        }
                                    }
                                    echo "</p></div>";
                                    ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <input type = "hidden" value="" name="arithmetic_operator_selected_item" id="arithmetic_operator_selected_item" />                
                        </div>

                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>                
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_add_variable_ok" onclick="arithmetic_operator_condition_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



