<script type="text/javascript">
$(document).ready(function()
{
    xml_path = '<?php 
                    $CI = &get_instance();
                    $project_id = $CI->session->userdata('project_id');
                    echo '../../xml/'.$project_id.'.xml';
                ?>';
    load_xml();
    //filtering left panel content
    reset_left_panel_content();
    var user_projects_name_string = '<?php echo $user_project_name_list?>';
    var user_projects_id_string = '<?php echo $user_project_id_list?>';
    var user_project_name_list = user_projects_name_string.split(',');
    var user_project_id_list = user_projects_id_string.split(',');
    set_project_name_list(user_project_name_list);
    set_project_id_list(user_project_id_list);
    
    var custom_variable_list = JSON.parse('<?php 
        $variables = array();
        foreach ($custom_variables as $cv)
        {
            $variable =array("variable_id" => $cv->variable_id, "variable_name" => $cv->variable_name, "variable_type" => $cv->variable_type, "variable_value" => $cv->variable_value);
            $variables[] =  $variable;            
        }
        echo json_encode(array('variable_list' => $variables));
        ?>');
    set_project_variables(custom_variable_list.variable_list); 
    
    var base_url = '<?php echo base_url();?>';
    
    has_external_variables = '<?php echo $has_external_variables?>';
    is_cancel_pressed_external_variable_upload = '<?php echo $is_cancel_pressed_external_variable_upload?>';
    external_file_content_error = '<?php echo $external_file_content_error?>';
    external_variable_values = "";
    var external_variable_length = '<?php echo count($external_variable_values)?>';
    if(external_variable_length > 0 || is_cancel_pressed_external_variable_upload == 'true' || external_file_content_error == 'true')
    {
        //retrieving previously selected anchor id in natural language panel to select this expression after page reload
        var naturalLanguagePanelSelectedAnchorId = '<?php echo $selected_anchor_id?>';
        left_panel_condition_or_action_selected();
        $("a", $("#changing_stmt")).each(function () 
        {
             if(naturalLanguagePanelSelectedAnchorId == $(this).attr("id")) {
                manageExpression(this);   
             }    
        });
        if(is_cancel_pressed_external_variable_upload == 'true')
        {
            $('#label_alert_message').text("Press upload button again to load external variables.");
            $('#div_alert_message').dialog('open');
            //alert("Press upload button again to load external variables.");
        }
        else if(external_file_content_error == 'true')
        {
            $('#label_alert_message').text("Each variable must be in a separated line");
            $('#div_alert_message').dialog('open');
            //alert("Each variable must be in a separated line");
        }
        else if(external_variable_length > 0) {
            //$('#external_variable_list').dialog('open');
            external_variable_values = '<?php
            $variable_values_counter = 0;
            $variable_values = "";
            foreach ($external_variable_values as $external_variable_value) 
            {                       
                if($variable_values_counter == 0)
                {
                    $variable_values = $variable_values.$external_variable_value;
                }
                else
                {
                    $variable_values = $variable_values.",".$external_variable_value;
                }
                $variable_values_counter++;            
            }
            echo $variable_values;
            ?>';
        }
    }
       
    set_server_base_url(base_url);
    
    trackUserOperation();
});
</script>
<table class="table-responsive table" >
    <tr align="right" style="color:green">
        <td><div id="project_name_label"><b>Project Name&nbsp;:&nbsp;</b> <?php echo $selected_project->project_name?> <b>&nbsp;Type&nbsp;:&nbsp;</b> <?php echo$project_type;?> <b>&nbsp;Welcome&nbsp;:&nbsp;</b><?php echo $user_info['username']?></div></td>
    </tr>
</table>

<table class="table-responsive table" >
        <tr>
            <td><div id="condition_action_label">Condition in natural Language</div></td>
        </tr>
        <tr>
            <td>
                <table class="table-responsive table" >
                    <tr>
                        <td colspan="2" bgcolor="#999999"><p id="changing_stmt" class="modify"></p></td>
                    </tr>
                    <tr>
                        <td colspan="2">Code</td>
                    </tr>
                    <tr>
                        <td colspan="2" bgcolor="#999999"><p id="code_stmt" class="modify"></p></td>
                    </tr>
                    <tr>
                        <td align="center" height="30px">Options</td>
                        <td align="center" height="30px">Parameters</td>
                    </tr>
                    <tr >

                        <td class="list_style" width="50%" valign="top">
                            <div id="demo1" class="demo" style="height:300px; width:100%;">
                                
                                <?php
                               
                                foreach ($fObjectArray as $key => $objectList) 
                                {
                                ?>
                                    <ul>
                                        <li id="<?php echo $key ?>" rel="folder">
                                            <a href="#"><?php echo $key; ?></a>
                                            <ul>
                                        <?php
                                                foreach ($objectList as $customObj)
                                                {
                                                    echo "<li id='{$customObj->optionstype}' name='{$key}' rel='default'><a href='#' >";
                                                    echo $customObj->optionstype;
                                                    echo "</a></li>";
                                                }
                                        ?>

                                            </ul>
                                        </li>                                  

                                    </ul>
<?php
                                }
?>
                                <ul>
                                    <li id="<?php echo "variable" ?>" rel="folder">
                                        <a href="#"><?php echo "variable"; ?></a>
                                        <ul>
                                        <?php
                                            foreach ($custom_variables as $cv)
                                            {
                                                echo "<li title='{$cv->variable_type}' id='{$cv->variable_name}' name='variable' rel='default'><a href='#' >";
                                                echo $cv->variable_name;
                                                echo "</a></li>";
                                            }
                                        ?>

                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td valign="top"><p id="parameters_table"></p></td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

        <!-- start of action_variable_modal -->
        <div id="action_variable_modal" >
            <select name="actonSelectionCombo" id="actonSelectionCombo" onchange="actionComboChange(this)">
                <option value="condition" selected = "selected">Condition</option>
                <option value="action">Action</option>
                <option value="change_variable_value">Change Variable Value</option>
            </select>
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
                                    foreach ($custom_variables as $cv) 
                                    {
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
        <!-- end of action_variable_modal -->

        <!-- start of generate code div modal -->
        <div id="generate_code_div_modal" >
            <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
                <tr>
                    <td width="100%" height="100%" align='center'>
                        <textarea width='100%' height="100%" id="generated_code_text_area" rows="20" cols="90" style="font-size:8pt">

                        </textarea>
                        <!--downloading generated code-->
                        <?php echo form_open("general_process/download_project_code");?>
                        <input type="submit" id="generate_code_save_button" value="Download" onclick="generate_code_save_button_pressed()"/>
                        <?php echo form_close();?>
                    </td>
                </tr>
            </table>
        </div>
        <!-- end of generate code div modal -->

        <!-- start Modal window for conditional -->
        <div id="conditional_modal">
            <select name="conditionOrBooleanSelectionCombo" id="conditionOrBooleanSelectionCombo" onchange="conditionOrBooleanSelectionCombo(this)">
                <option value="condition" selected = "selected">Condition</option>
                <option value="action">Boolean Variable</option>
            </select>
            <div id="condition_selected_div">   
            <table width="600" style="border-collapse:collapse;">
                         
                    <tr height="30px">
                        <td>
                            <div id="left_part" style="float:left; padding-left:10px;"></div>
                            <div id="cmp_part" style="float:left; padding-left:10px;"></div>
                            <div id="ritgh_part" style="float:left; padding-left:10px;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td width="70" valign='top' >
                            <div id="item_accordion" style="width:150px; font-size:10pt; float:left;">
                                <?php
                                    foreach ($fObjectArray as $key => $objectList) 
                                    {
                                        if ($key != "comparison" && $key != "action") {
                                            echo "<h5><a href='#'>{$key}</a></h5>";
                                            //echo $key."</br>";
                                            echo "<div><p>";
                                            foreach ($objectList as $customObj) {
                                                $displayNatural = $customObj->natural;
                                                $searchedPattern = array();
                                                $replacementPattern = array();
                                                foreach ($customObj->parameters as $param) {
                                                    $searchedPattern[] = "$" . $param->name;
                                                    $replacementPattern[] = $param->default;
                                                }
                                                //$searchedPattern = "$".$customObj->parameters[0]->name;
                                                //$replacementPattern = $customObj->parameters[0]->default;
                                                $displayNatural = str_replace($searchedPattern, $replacementPattern, $displayNatural);
                                                //echo "<a style='text-decoration:none;' id='l_p' onclick='setChangingStmt(this)' href='#'>{$customObj->optionstype}</a>";
                                                echo "<a style='text-decoration:none;' href='#' id='l_p' onclick='setChangingStmt(this)'><input id='natural' type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                echo "<br/>";
                                            }
                                            echo "</p></div>";
                                        }
                                    }
                                    echo "<h5><a href='#'>Variables</a></h5>";
                                    echo "<div><p>";
                                    foreach ($custom_variables as $cv) {
                                        echo "<a style='text-decoration:none;' href='#' id='l_p' onclick='setChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                        echo "<br/>";
                                    }                                    
                                    echo "</p></div>";
                                ?>

                            </div>

                            <div id="comparison_accordion" style="width:200px; font-size:10pt; float:left;">
                                <?php
                                    foreach ($fObjectArray as $key => $objectList) {
                                        if ($key == "comparison") {
                                            echo "<h5><a href='#'>{$key}</a></h5>";
                                            //echo $key."</br>";
                                            echo "<div><p>";
                                            foreach ($objectList as $customObj) {
                                                $displayNatural = $customObj->natural;
                                                //echo "<a style='text-decoration:none;' href='#' id='c_p' onclick='setChangingStmt(this)'>{$customObj->optionstype}</a>";
                                                echo "<a style='text-decoration:none;' href='#' id='c_p' onclick='setChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                echo "<br/>";
                                            }
                                            echo "</p></div>";
                                        }
                                    }
                                ?>
                            </div>

                            <div id="action_accordion" style="width:150px; font-size:10pt; float:left;">
                                <?php
                                    foreach ($fObjectArray as $key => $objectList) {
                                        if ($key != "comparison" && $key != "action") {
                                            echo "<h5><a href='#'>{$key}</a></h5>";
                                            //echo $key."</br>";
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

                                                //echo "<a style='text-decoration:none;' href='#' id='r_p' onclick='setChangingStmt(this)'>{$customObj->optionstype}</a>";
                                                echo "<a style='text-decoration:none;' href='#' id='r_p' onclick='setChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                                echo "<br/>";
                                            }
                                            echo "</p></div>";
                                        }
                                    }
                                    echo "<h5><a href='#'>Variables</a></h5>";
                                    echo "<div><p>";
                                    foreach ($custom_variables as $cv) {
                                        echo "<a style='text-decoration:none;' href='#' id='r_p' onclick='setChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                        echo "<br/>";
                                    }                                    
                                    echo "</p></div>";
                                ?>
                            </div>
                        </td>
                    </tr>
            </table>
            </div> 
            <div id="condition_selection_div_boolean_variable">
                <table width="100%" style="border-collapse:collapse;">
                    <tr height="30px">
                        <td>
                            <div id="condition_boolean_variables_left_part" style="float:left; padding-left:10px;"></div>
                            <div id="condition_boolean_variables_middle_part" style="float:left; padding-left:10px;"></div>
                            <div id="condition_boolean_variables_right_part" style="float:left; padding-left:10px;"></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign='top' >
                            <div id="condition_boolean_variables_left_part_accordion" style="width:150px; font-size:10pt; float:left;">
                                <?php
                                    echo "<h5><a href='#'>Boolean Variables</a></h5>";
                                    echo "<div><p>";
                                    foreach ($custom_variables as $cv) 
                                    {
                                        //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                                        if($cv->variable_type == "BOOLEAN"){
                                            echo "<a style='text-decoration:none;' href='#' id='c_b_v_l_p' onclick='conditionBooleanVariablesSetSelectedVariable(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                            echo "<br/>";
                                        }
                                    }                                    
                                    echo "</p></div>";
                                ?>
                            </div> 
                            <div id="condition_boolean_variables_middle_part_accordion" style="width:200px; font-size:10pt; float:left;">
                                <?php
                                    echo "<h5><a href='#'>Comparison</a></h5>";
                                    echo "<div><p>";
                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_m_p' onclick='conditionBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is equal to' />is equal to</a>";
                                    echo "<br/>";
                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_m_p' onclick='conditionBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is not equal to' />is not equal to</a>";
                                    echo "<br/>";                            
                                    echo "</p></div>";
                                ?>
                            </div>
                            <div id="condition_boolean_variables_right_part_accordion" style="width:150px; font-size:10pt; float:left;">
                                <?php
                                    echo "<h5><a href='#'>Boolean Value</a></h5>";
                                    echo "<div><p>";
                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_r_p' onclick='conditionBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='true' />true</a>";
                                    echo "<br/>";
                                    echo "<a style='text-decoration:none;' href='#' id='c_b_v_r_p' onclick='conditionBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='false' />false</a>";
                                    echo "<br/>";                            
                                    echo "</p></div>";
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>    
        </div>
        <!-- end Modal window for conditional -->

<!-- start of logical connector div modal -->
<div id="logical_connector_div" >
    <label>Insert</label>
    <select name="logicalConnectorSelectionCombo" id="logicalConnectorSelectionCombo">
        <option value="AND" selected = "selected">AND</option>
        <option value="OR">OR</option>
    </select>
    <!-- user can adds a new condtion or a boolean variable -->
    <select name="logicalConnectorItemType" id="logicalConnectorItemType">
        <option value="Condition" selected = "selected">Condition</option>
        <option value="BooleanVariable">Boolean Variable</option>
    </select>
    <label>after</label>
    <ol id="logical_connector_selected_item" style="font-size:8pt;">       
    </ol>
</div>
<!-- end of logical connector div modal -->

<!-- start of logical connector removing condition div modal -->
<div id="logical_connector_removing_condition_div" >    
    <ol id="logical_connector_removing_condition_selected_item" style="font-size:12pt;">       
    </ol>
    
    <input type = "hidden" value="" name="logical_connector_removing_condition_selected_operator_anchor_id" id="logical_connector_removing_condition_selected_operator_anchor_id" />
    <table width="600" style="border-collapse:collapse;">
        <tr height="20px">            
        </tr>
        <tr height="20px">
            <td align="right">
                <button id="button_logical_connector_removing_condition_delete" onclick="button_logical_connector_removing_condition_delete_pressed()" type="button">Delete</button>
                <button id="button_logical_connector_removing_condition_cancel" onclick="button_logical_connector_removing_condition_cancel_pressed()" type="button">Cancel</button>
            </td>
        </tr>
    </table>    
</div>
<!-- end of logical connector removing condition div modal -->



<!-- start Modal window for logical connector conditional window-->
<div id="logical_connector_conditional_modal">
    <table width="600" style="border-collapse:collapse;">
        <tr height="30px">
            <td>
                <div id="logical_connector_left_part" style="float:left; padding-left:10px;"></div>
                <div id="logical_connector_cmp_part" style="float:left; padding-left:10px;"></div>
                <div id="logical_connector_right_part" style="float:left; padding-left:10px;"></div>
            </td>
        </tr>
        <tr>
            <td width="70" valign='top' >
                <div id="logical_connector_condition_left_item_accordion" style="width:150px; font-size:10pt; float:left;">
                    <?php
                        foreach ($fObjectArray as $key => $objectList) 
                        {
                            if ($key != "comparison" && $key != "action") 
                            {
                                echo "<h5><a href='#'>{$key}</a></h5>";
                                echo "<div><p>";
                                foreach ($objectList as $customObj) 
                                {
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
                                echo "</p></div>";                                
                            }                        
                        }
                        echo "<h5><a href='#'>Variables</a></h5>";
                        echo "<div><p>";
                        foreach ($custom_variables as $cv) 
                        {
                            echo "<a style='text-decoration:none;' href='#' id='l_c_l_p' onclick='logicalConnectorSetChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                            echo "<br/>";
                        }                                    
                        echo "</p></div>";
                    ?>

                </div>

                <div id="logical_connector_condition_comparison_item_accordion" style="width:200px; font-size:10pt; float:left;">
                    <?php
                        foreach ($fObjectArray as $key => $objectList) {
                            if ($key == "comparison") {
                                echo "<h5><a href='#'>{$key}</a></h5>";
                                echo "<div><p>";
                                foreach ($objectList as $customObj) {
                                    $displayNatural = $customObj->natural;
                                    echo "<a style='text-decoration:none;' href='#' id='l_c_c_p' onclick='logicalConnectorSetChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                    echo "<br/>";
                                }
                                echo "</p></div>";
                            }
                        }
                    ?>
                </div>

                <div id="logical_connector_condition_right_item_accordion" style="width:150px; font-size:10pt; float:left;">
                    <?php
                        foreach ($fObjectArray as $key => $objectList) {
                            if ($key != "comparison" && $key != "action") {
                                echo "<h5><a href='#'>{$key}</a></h5>";
                                //echo $key."</br>";
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
                                    echo "<a style='text-decoration:none;' href='#' id='l_c_r_p' onclick='logicalConnectorSetChangingStmt(this)'><input type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
                                    echo "<br/>";
                                }
                                echo "</p></div>";                                
                            }
                        }
                        echo "<h5><a href='#'>Variables</a></h5>";
                        echo "<div><p>";
                        foreach ($custom_variables as $cv) {
                            echo "<a style='text-decoration:none;' href='#' id='l_c_r_p' onclick='logicalConnectorSetChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                            echo "<br/>";
                        }                                    
                        echo "</p></div>";
                    ?>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <input type = "hidden" value="" name="logical_connector_selected_index" id="logical_connector_selected_index" />
                <input type = "hidden" value="" name="logical_connector_selected_anchor_id" id="logical_connector_selected_anchor_id" />
                <input type = "hidden" value="" name="logical_connector_selected_anchor_title" id="logical_connector_selected_anchor_title" />                
            </td>
        </tr>
    </table>
</div>
<!-- end conditional Modal window for logical connector -->

<!-- start of add variable  div modal -->
<div id="add_variables_div" >
    <?php echo form_open("variables/create_variable");?>    
    <table>
        <tr>
            <td aligh="left">Variable Name</td>
            <td colspan="2">
                <input type="text" size="25" name ="add_variable_name" id="add_variable_name" value=""/>
            </td>
        </tr>
        <tr>
            <td align="left">Variable Type</td>
            <td aligh="left">
                <select onchange="addVariableTypeComboChange(this)" name="add_variable_type_selection_combo" id="add_variable_type_selection_combo">
                    <option value="BOOLEAN" selected = "selected">BOOLEAN</option>
                    <option value="NUMBER">NUMBER</option>
                </select>
            </td>
            <td aligh="right">
                <select onchange="addVariableValueComboChange(this)" name="add_variable_value_selection_combo" id="add_variable_value_selection_combo">
                    <option value="TRUE" selected = "selected">TRUE</option>
                    <option value="FALSE">FALSE</option>
                </select>
            </td>
        </tr>
        <tr>
            <td align="left"><div id="add_variable_value_label">Variable value</div></td>
            <td colspan="2">
                <div id="add_variable_value_text">
                    <input type="text" size="25" name ="add_variable_value" id="add_variable_value" value=""/>
                </div>
            </td>
        </tr>        
    </table>
    <!--<button id="button_add_variable_ok" onclick="button_add_variable_ok_pressed()" type="button">Ok</button>-->
    <input type = "hidden" value="" name="project_left_panel_content_backup" id="project_left_panel_content_backup" />
    <input type="submit" id="button_add_variable_ok" onclick="return button_add_variable_ok_pressed()" value="Save"/>
    <button id="button_add_variable_cancel" onclick="button_add_variable_cancel_pressed()" type="button">Cancel</button>
    <?php echo form_close();?>
    
</div>
<!-- end of add variable div modal -->

<!-- start of arithmetic operator  div modal -->
<div id="arithmetic_operator_div" >
    <table>
        <tr>
            <td>Insert</td>
            <td>
                <select onchange="arithmeticOperatorSelectionComboChange(this)" name="arithmetic_operator_selection_combo" id="arithmetic_operator_selection_combo">
                    <option value="+" selected = "selected">+</option>
                    <option value="-">-</option>
                    <option value="*">*</option>
                    <option value="/">/</option>
                </select>
            </td>
            <td></td>
            <td>
                <select onchange="arithmeticOperatorRightPartTypeComboChange(this)" name="arithmetic_operator_right_part_type_selection_combo" id="arithmetic_operator_right_part_type_selection_combo">
                    <option value="CONSTANT" selected = "selected">CONSTANT</option>
                    <option value="CONDITION">CONDITION</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><div id="arithmetic_operator_right_part_value_label">Value</div></td>
            <td>
                <input type="text" name ="arithmetic_operator_right_part_value" id="arithmetic_operator_right_part_value" value=""/>
            </td>
        </tr>
    </table>    
</div>
<!-- end of arithmetic operator div modal -->

<!-- start of arithmetic operator condition div modal -->
<div id="arithmetic_operator_condition_div" >
    <table width="100" style="border-collapse:collapse;">
        <tr height="30px">
            <td>
                <div id="arithmetic_operator_condition_left_part" style="float:left; padding-left:10px;"></div>                
            </td>
        </tr>
        <tr>
            <td width="70" valign='top' >
                <div id="arithmetic_operator_condition_accordion" style="width:150px; font-size:10pt; float:left;">
                    <?php
                        foreach ($fObjectArray as $key => $objectList) 
                        {
                            if ($key != "comparison" && $key != "action") 
                            {
                                echo "<h5><a href='#'>{$key}</a></h5>";
                                echo "<div><p>";
                                foreach ($objectList as $customObj) 
                                {
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
                        foreach ($custom_variables as $cv) 
                        {
                            //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                            if($cv->variable_type == "NUMBER"){
                                echo "<a style='text-decoration:none;' href='#' id='a_o_c_l_p' onclick='arithmeticOperatorConditionSetChangingStmt(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                echo "<br/>";
                            }
                        }                                    
                        echo "</p></div>";
                    ?>

                </div>                
            </td>
        </tr>
        <tr>
            <td>
                <input type = "hidden" value="" name="arithmetic_operator_selected_item" id="arithmetic_operator_selected_item" />                
            </td>
        </tr>
    </table>
</div>
<!-- end of arithmetic operator condition div modal -->

<!-- start of logical connector div modal -->
<div id="add_bracket_in_condition_div" >
    <label style="font-size:10pt">Press your ctrl key and select where you want to add ( and then select where you want to add )</label>
    <ol id="add_bracket_in_condition_div_selected_items" style="font-size:8pt;">
       
    </ol>
</div>
<!-- end of logical connector div modal -->


<!-- start of download project div modal. This modal shows a modal window to download project left panel content-->
<div id="download_project_div_modal" >
    <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
        <tr>
            <td width="100%" height="100%" align='center'>
                <!--downloading project-->
                <?php echo form_open("general_process/download_project");?>
                     <label >File Name</label>
                    <input type="text" id="project_content_file_name" name="project_content_file_name" value=""/>
                    <input type="submit" id="download_project_save_button" value="Save" onclick="download_project_save_button_clicked()"/>
                <?php echo form_close();?>
            </td>
        </tr>
    </table>
</div>
<!-- end of download project div modal -->

<div id="delete_block_confirmation_div_modal" >
    <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
        <tr>
            <td width="100%" height="100%" align='center'>
                <label id="label_delete_block_confirmation_div_modal" name="label_delete_block_confirmation_div_modal">Are you sure you want to delete this?</label>
            </td>
        </tr>
    </table>
</div>

<!-- start of logical connector boolean variable div modal -->
<div id="logical_connector_boolean_variables_div" >
    <table width="100%" style="border-collapse:collapse;">
        <tr height="30px">
            <td>
                <div id="logical_connector_boolean_variables_left_part" style="float:left; padding-left:10px;"></div>
                <div id="logical_connector_boolean_variables_middle_part" style="float:left; padding-left:10px;"></div>
                <div id="logical_connector_boolean_variables_right_part" style="float:left; padding-left:10px;"></div>
            </td>
        </tr>
        <tr>
            <td width="100%" valign='top' >
                <div id="logical_connector_boolean_variables_left_part_accordion" style="width:150px; font-size:10pt; float:left;">
                    <?php
                        echo "<h5><a href='#'>Boolean Variables</a></h5>";
                        echo "<div><p>";
                        foreach ($custom_variables as $cv) 
                        {
                            //sincere this will be used in arithmetic operator so boolean variable will not be visible here
                            if($cv->variable_type == "BOOLEAN"){
                                echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_l_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariable(this)'><input id='{$cv->variable_type}' type = 'hidden' value='variable' name='{$cv->variable_name}' />{$cv->variable_name}</a>";
                                echo "<br/>";
                            }
                        }                                    
                        echo "</p></div>";
                    ?>

                </div> 
                <div id="logical_connector_boolean_variables_middle_part_accordion" style="width:200px; font-size:10pt; float:left;">
                    <?php
                        echo "<h5><a href='#'>Comparison</a></h5>";
                        echo "<div><p>";
                        echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_m_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is equal to' />is equal to</a>";
                        echo "<br/>";
                        echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_m_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableComparison(this)'><input id='booleancomparison' type = 'hidden' value='booleancomparison' name='is not equal to' />is not equal to</a>";
                        echo "<br/>";                            
                        echo "</p></div>";
                    ?>
                </div>
                <div id="logical_connector_boolean_variables_right_part_accordion" style="width:150px; font-size:10pt; float:left;">
                    <?php
                        echo "<h5><a href='#'>Boolean Value</a></h5>";
                        echo "<div><p>";
                        echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_r_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='true' />true</a>";
                        echo "<br/>";
                        echo "<a style='text-decoration:none;' href='#' id='l_c_b_v_r_p' onclick='logicalConnectorBooleanVariablesSetSelectedVariableValue(this)'><input id='booleanvalue' type = 'hidden' value='booleanvalue' name='false' />false</a>";
                        echo "<br/>";                            
                        echo "</p></div>";
                    ?>
                </div> 
            </td>
        </tr>
        <tr>
            <td>
                <input type = "hidden" value="" name="logical_connector_boolean_variable_selected_item" id="logical_connector_boolean_variable_selected_item" />                
            </td>
        </tr>
        <tr>
            <td>
                <input type = "hidden" value="" name="logical_connector_boolean_variable_selected_index" id="logical_connector_boolean_variable_selected_index" />
                <input type = "hidden" value="" name="logical_connector_boolean_variable_selected_anchor_id" id="logical_connector_boolean_variable_selected_anchor_id" />
                <input type = "hidden" value="" name="logical_connector_boolean_variable_selected_anchor_title" id="logical_connector_boolean_variable_selected_anchor_title" />                
            </td>
        </tr>
    </table>
</div>
<!-- end of logical connector boolean variable div modal -->

<div id="load_projects_confirmation_window_div_modal" >
    <?php echo form_open("general_process/load_project_list_project_panel");?>    
    <table>
        
        <tr>
           <td>
               <label> Do you want to save your current project?</label>
           </td>
        </tr>        
    </table>
    <input type = "hidden" value="" name="pre_load_project_left_panel_content" id="pre_load_project_left_panel_content" />
    <input type="submit" id="button_pre_load_project_ok" name="button_pre_load_project_ok" onclick="return button_pre_load_project_ok_pressed()" value="Save"/>
    <input type="submit" id="button_pre_load_project_no" name="button_pre_load_project_no" value="No"/>
    <?php echo form_close();?>
    
</div>

<div id="save_as_project_div_modal" >
    <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
        <tr>
            <td width="100%" height="100%" align='center'>
                
                <?php
                $attributes = array('name' => 'form_submission' , 'onsubmit' =>'return save_as_project_save_button_clicked()');
                echo form_open("general_process/save_as_project", $attributes);?>
                <label >Project Name</label>
                <input type="text" id="save_as_project_project_name" name="save_as_project_project_name" value=""/>
                <input type="hidden" id="save_as_project_left_panel_content" name="save_as_project_left_panel_content" value=""/>
                <input type="submit" id="save_as_project_save_button" value="Save"/>
                <?php echo form_close();?>
                
            </td>
        </tr>
    </table>
</div>

<div id="save_as_replace_project_div_modal" >
    <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
        <tr>
            <td width="100%" height="100%" align='center'>
                <?php echo form_open("general_process/save_as_replace_project");?>
                    <label >Project already exists. Do you want to replace it?</label><br/>
                    <input type="hidden" id="save_as_replace_project_left_panel_content" name="save_as_replace_project_left_panel_content" value=""/>
                    <input type="hidden" id="save_as_replace_project_name" name="save_as_replace_project_name" value=""/>
                    <input type="hidden" id="save_as_replace_project_id" name="save_as_replace_project_id" value=""/>
                    
                    <input type="submit" id="save_as_replace_project_yes_button" value="Yes" onclick="return save_as_replace_project_yes_button_clicked()"/>
                    <input type="submit" id="save_as_replace_project_no_button" value="No" onclick="return save_as_replace_project_no_button_clicked()"/>
                <?php echo form_close();?>
            </td>
        </tr>
    </table>
</div>

<div id="project_variable_list_div" >
    <table width="100%" border="1" style="border-collapse:collapse;">
        <tr>
            <td>Variable Name</td>
            <td>Variable Type</td>
            <td>Variable Value</td>
            <td>Delete</td>
        </tr> 
        <?php echo form_open('variables/delete_variable');?>                
        <?php
            foreach ($custom_variables as $cv) 
            {
                echo " <tr>";
                echo "<td>{$cv->variable_name}</td>";
                echo "<td>{$cv->variable_type}</td>";
                echo "<td>{$cv->variable_value}</td>";
                echo "<td>
                <input type='submit' id='button_delete_variable_{$cv->variable_id}' name='button_delete_variable_{$cv->variable_id}' value='Delete' onclick='return is_variable_used_delete_button_clicked({$cv->variable_id})'/>
                </td>";
                echo "</tr>";
            }
        ?>  
        <input type='hidden' id='delete_variable_variable_id' name='delete_variable_variable_id' value=''/>
        <input type='hidden' id='delete_variable_project_left_panel_content' name='delete_variable_project_left_panel_content' value=''/>                
        <?php echo form_close();?>
    </table>
</div>

<div id="log_out_warning_div" >
    <table width="100%" border="1" style="border-collapse:collapse;">
        <tr>
            <td><label>your session is about to expire. Do you want to continue working?</label></td>
            
        </tr>
    </table>
</div>

<div id="condition_boolean_middle_part_change_confirmation_div" >
    <table width="100%" border="1" style="border-collapse:collapse;">
        <tr>
            <td><label id="lable_condition_boolean_middle_part_change_confirmation"></label></td>
            
        </tr>
    </table>
</div>

<div id="condition_boolean_right_part_change_confirmation_div" >
    <table width="100%" border="1" style="border-collapse:collapse;">
        <tr>
            <td><label id="lable_condition_boolean_right_part_change_confirmation"></label></td>
            
        </tr>
    </table>
</div>

<div id="external_variable_list" >
    <table width="100%" style="border-collapse:collapse;">
        <?php
        foreach ($external_variable_list as $external_variable) 
        {
        ?>
            <tr>
                <td valign='top' >
                    <?php echo $external_variable; ?>  
                </td>
            </tr>    
        <?php   
        } 
        ?> 
    </table>
    <?php
        $variable_values_counter = 0;
        $variable_values = "";
        foreach ($external_variable_values as $external_variable_value) 
        {                       
            if($variable_values_counter == 0)
            {
                $variable_values = $variable_values.$external_variable_value;
            }
            else
            {
                $variable_values = $variable_values." , ".$external_variable_value;
            }
            $variable_values_counter++;            
        } 
    ?> 
    <table width="100%" style="border-collapse:collapse;">
        <tr>    
            <td>
                <?php echo $variable_values;?>
            </td>
        </tr>         
    </table>
</div>

<div id="logical_operator_change_div" >
    <table>        
        <tr>
            <td style="">Select Logical Operator:</td>
            <td style="">
                <select name="logical_operator_change_combo" id="logical_operator_change_combo">
                    <option value="AND">AND</option>
                    <option value="OR">OR</option>
                </select>
            </td>
        </tr>              
    </table> 
</div>

<div id="arithmetic_operator_change_div" >
    <table>        
        <tr>
            <td style="">Select Arithmetic Operator:</td>
            <td style="">
                <select name="arithmetic_operator_change_combo" id="arithmetic_operator_change_combo">
                    <option value="+">+</option>
                    <option value="-">-</option>
                    <option value="*">*</option>
                    <option value="/">/</option>
                </select>
            </td>
        </tr>              
    </table> 
</div>

<div id="upload_project_div" >
    <table>        
        <?php echo form_open("general_process/upload_project");?>
        <tr>
            <td>                
                <label >Do you want to keep your current changes?</label><br/>
                <input type="hidden" id="upload_project_project_left_panel_content" name="upload_project_project_left_panel_content" value=""/>
            </td>
        </tr>
        <tr>
            <td style="float:right;">
                <input type="submit" id="button_no_upload_project" value="No" onclick="return button_no_clicked_upload_project()"/>
            </td>
            <td style="float:right;">
                <input type="submit" id="button_yes_upload_project" value="Yes" onclick="return button_yes_clicked_upload_project()"/>                    
            </td>            
        </tr>
        <?php echo form_close();?>
    </table> 
</div>

<div id="div_alert_message" >
    <table>
        <tr>
            <td><label id="label_alert_message"></label></td>            
        </tr>
    </table>
</div>