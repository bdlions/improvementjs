
<div class="modal fade" id="modal_logical_connector_condition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Condition</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <div id="logical_connector_left_part" style="float:left; padding-left:10px;"></div>
                                    <div id="logical_connector_cmp_part" style="float:left; padding-left:10px;"></div>
                                    <div id="logical_connector_right_part" style="float:left; padding-left:10px;"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <?php
                                        $temp_count = 1;
                                        foreach ($fObjectArray as $key => $objectList) {
                                            if ($key != "comparison" && $key != "action") {
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingLeft">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseLeft<?php echo $temp_count ?>" aria-expanded="false" aria-controls="collapseLeft<?php echo $temp_count ?>">
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseLeft<?php echo $temp_count ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingLeft">
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
                                                                echo "<a style='text-decoration:none;' href='#' id='l_c_l_p' onclick='logicalConnectorSetChangingStmt(this)'><input id='natural' type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                                echo "<br/>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            $temp_count++;
                                        }
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingLeft">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseLeft<?php echo $temp_count ?>" aria-expanded="false" aria-controls="collapseLeft<?php echo $temp_count ?>">
                                                        Variables
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseLeft<?php echo $temp_count ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingLeft">
                                                <div class="panel-body">
                                                    <?php
                                                    foreach ($custom_variables as $cv) {
                                                        //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                                        if ($cv->variable_type == "NUMBER") {
                                                            echo "<a style='text-decoration:none;' href='#' id='l_c_l_p' onclick='logicalConnectorSetChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
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
                                                    <div class="panel-heading" role="tab" id="headingLeft">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseLeft" aria-expanded="true" aria-controls="collapseLeft">
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseLeft" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLeft">
                                                        <div class="panel-body">
                                                            <?php
                                                            foreach ($objectList as $customObj) {
                                                                $displayNatural = $customObj->natural;
                                                                echo "<a style='text-decoration:none;' href='#' id='l_c_c_p' onclick='logicalConnectorSetChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
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
                                        $temp_counter = 1;
                                        foreach ($fObjectArray as $key => $objectList) {
                                            if ($key != "comparison" && $key != "action") {
                                                ?>
                                                <div class="panel panel-default">
                                                    <div class="panel-heading" role="tab" id="headingRight">
                                                        <h4 class="panel-title">
                                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseRight<?php echo $temp_counter ?>" aria-expanded="false" aria-controls="collapseRight<?php echo $temp_counter ?>">
                                                                <?php echo $key; ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapseRight<?php echo $temp_counter ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRight">
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
                                                                echo "<a style='text-decoration:none;' href='#' id='l_c_r_p' onclick='logicalConnectorSetChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                                echo "<br/>";
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            $temp_counter++;
                                        }
                                        ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingRight">
                                                <h4 class="panel-title">
                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseRight<?php echo $temp_counter ?>" aria-expanded="false" aria-controls="collapseRight<?php echo $temp_counter ?>">
                                                        Variables
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseRight<?php echo $temp_counter ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingRight">
                                                <div class="panel-body">
                                                    <?php
                                                    foreach ($custom_variables as $cv) {
                                                        //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                                        if ($cv->variable_type == "NUMBER") {
                                                           echo "<a style='text-decoration:none;' href='#' id='l_c_r_p' onclick='logicalConnectorSetChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                                            echo "<br/>";
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <input type = "hidden" value="" name="logical_connector_selected_index" id="logical_connector_selected_index" />
                                    <input type = "hidden" value="" name="logical_connector_selected_anchor_id" id="logical_connector_selected_anchor_id" />
                                    <input type = "hidden" value="" name="logical_connector_selected_anchor_title" id="logical_connector_selected_anchor_title" />
                                </div>
                            </div>
                        </div>
                    </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="logical_connector_ok_pressed" onclick="logical_connector_condition_ok_pressed()" value="Save"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->