
<div class="modal fade" id="modal_action_variable" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                            <div class="col-md-4">Select an option:</div>
                            <div class="col-md-8">
                                <select name="actonSelectionCombo" id="actonSelectionCombo" onchange="actionComboChange(this)">
                                    <option value="condition" selected = "selected">Condition</option>
                                    <option value="action">Action</option>
                                    <option value="change_variable_value">Change Variable Value</option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-10">
                                <ol id="condition_modal_selected_item" style="font-size:8pt;">
                                    <li class='ui-widget-content'>IF condition THEN action</li>
                                    <li class='ui-widget-content'>IF condition THEN action ELSE action</li>
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
            echo "<li class='ui-widget-content'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</li>";
        }
    }
}
?>
    </ol> 
                                             </div>
                            
                        </div>
                
                                
                            </div>
                             <div id="action_variable_selection">
        <table width="100%" style="border-collapse:collapse;">
            <tr height="30px">
                <td>
                    <div id="action_variable_selection_part" style="float:left; padding-left:10px;"></div>                
                </td>
            </tr>
            <tr>
                <td valign='top' >
                    <div id="action_variable_selection_accordion" style="width:100; font-size:10pt; float:left;">
<?php
echo "<h5><a href='#'>Variables</a></h5>";
echo "<div><p>";
foreach ($custom_variables as $cv) {
    echo "<a style='text-decoration:none;' href='#' id='a_v_s' onclick='actionVariableSelection(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
    echo "<br/>";
}
echo "</p></div>";
?>

                    </div>                
                </td>
            </tr>
        </table>
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

