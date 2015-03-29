
<div class="modal fade" id="modal_conditional" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Condition</h4>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <select name="conditionOrBooleanSelectionCombo" id="conditionOrBooleanSelectionCombo" onchange="conditionOrBooleanSelectionCombo(this)">
                                <option value="condition" selected = "selected">Condition</option>
                                <option value="action">Boolean Variable</option>
                            </select>
                        </div>
                        <div id="condition_selected_div">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div id="left_part" style="float:left; padding-left:10px;"></div>
                                    <div id="cmp_part" style="float:left; padding-left:10px;"></div>
                                    <div id="ritgh_part" style="float:left; padding-left:10px;"></div>
                                    <!--<div id="arithmetic_operator_condition_left_part" style="float:left; padding-left:10px;"></div>-->                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <?php
                                        $counter = 1;
                                        foreach ($fObjectArray as $key => $objectList) {
                                            if ($key != "comparison" && $key != "action") {
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingTwo">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo<?php echo $counter ?>" aria-expanded="false" aria-controls="collapseTwo<?php echo $counter ?>">
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseTwo<?php echo $counter ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                        <div class="panel-body">
                                                            <?php
                                                            foreach ($objectList as $customObj) {
                                                                $displayNatural = $customObj->natural;
                                                                $searchedPattern = array();
                                                                $replacementPattern = array();
                                                                foreach ($customObj->parameters as $param) {
                                                                    $searchedPattern[] = "$" . $param->name;
                                                                    $replacementPattern[] = $param->default;
                                                                }
                                                                $displayNatural = str_replace($searchedPattern, $replacementPattern, $displayNatural);
                                                                echo "<a style='text-decoration:none;' href='#' id='l_p' onclick='setChangingStmt(this)'><input id='natural' type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                                echo "<br/>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            $counter++;
                                        }
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingTwo">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo<?php echo $counter ?>" aria-expanded="false" aria-controls="collapseTwo<?php echo $counter ?>">
                                                        Variables
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseTwo<?php echo $counter ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                <div class="panel-body">
                                                    <?php
                                                    foreach ($custom_variables as $cv) {
                                                        //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                                        if ($cv->variable_type == "NUMBER") {
                                                            echo "<a style='text-decoration:none;' href='#' id='l_p' onclick='setChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
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
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <?php
                                        foreach ($fObjectArray as $key => $objectList) {
                                            if ($key == "comparison") {
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingOne">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                        <div class="panel-body">
                                                            <?php
                                                            foreach ($objectList as $customObj) {
                                                                $displayNatural = $customObj->natural;
                                                                echo "<a style='text-decoration:none;' href='#' id='c_p' onclick='setChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                                echo "<br/>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>


                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <?php
                                        $count = 1;
                                        foreach ($fObjectArray as $key => $objectList) {
                                            if ($key != "comparison" && $key != "action") {
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingThree">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree<?php echo $count ?>" aria-expanded="false" aria-controls="collapseThree<?php echo $count ?>">
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseThree<?php echo $count ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                                        <div class="panel-body">
                                                            <?php
                                                            foreach ($objectList as $customObj) {
                                                                $displayNatural = $customObj->natural;
                                                                $searchedPattern = array();
                                                                $replacementPattern = array();
                                                                foreach ($customObj->parameters as $param) {
                                                                    $searchedPattern[] = "$" . $param->name;
                                                                    $replacementPattern[] = $param->default;
                                                                }
                                                                $displayNatural = str_replace($searchedPattern, $replacementPattern, $displayNatural);
                                                                echo "<a style='text-decoration:none;' href='#' id='r_p' onclick='setChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                                echo "<br/>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            $count++;
                                        }
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingThree">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree<?php echo $count ?>" aria-expanded="false" aria-controls="collapseThree<?php echo $count ?>">
                                                        Variables
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseThree<?php echo $count ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                                <div class="panel-body">
                                                    <?php
                                                    foreach ($custom_variables as $cv) {
                                                        //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                                        if ($cv->variable_type == "NUMBER") {
                                                            echo "<a style='text-decoration:none;' href='#' id='r_p' onclick='setChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                                            echo "<br/>";
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="condition_selection_div_boolean_variable">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div id="condition_boolean_variables_left_part" style="float:left; padding-left:10px;"></div>
                                    <div id="condition_boolean_variables_middle_part" style="float:left; padding-left:10px;"></div>
                                    <div id="condition_boolean_variables_right_part" style="float:left; padding-left:10px;"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingFour">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                                        Boolean Variables
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseFour" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFour">
                                                <div class="panel-body">
                                                    <?php
                                                    foreach ($custom_variables as $cv) {
                                                        //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                                        if ($cv->variable_type == "BOOLEAN") {
                                                            echo "<a style='text-decoration:none;' href='#' id='c_b_v_l_p' onclick='conditionBooleanVariablesSetSelectedVariable(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
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
                                            <div class="panel-heading" role="tab" id="headingFive">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                                        Comparison
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseFive" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFive">
                                                <div class="panel-body">
                                                    <?php
                                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_m_p' onclick='conditionBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is equal to' />is equal to</a>";
                                                    echo "<br/>";
                                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_m_p' onclick='conditionBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is not equal to' />is not equal to</a>";
                                                    echo "<br/>";
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"><div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingSix">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                                        Boolean Value
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseSix" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSix">
                                                <div class="panel-body">
                                                    <?php
                                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_r_p' onclick='conditionBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='true' />true</a>";
                                                    echo "<br/>";
                                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_r_p' onclick='conditionBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='false' />false</a>";
                                                    echo "<br/>";
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>                
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="condition_variables_ok_pressed" onclick="condition_variables_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->