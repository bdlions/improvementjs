<script type="text/javascript">
    $(function() {
        //configuraing smarty urls to generate code
        template_service = '<?php echo base_url() ?>' + '../smartycode/service_language.php';
        template_service_condition_url = '<?php echo base_url() ?>' + '../smartycode/code_condition_service.php';
        template_service_action_url = '<?php echo base_url() ?>' + '../smartycode/code_action_service.php';
        //storing previously selected anchor in natural language panel
        natural_language_panel_selected_anchor_id = '<?php echo $selected_anchor_id ?>';

        current_user_type = '<?php echo $user_type ?>';
        user_type_member = '<?php echo MEMBER ?>';
        user_type_demo = '<?php echo DEMO ?>';

        maximum_if_per_project = '<?php echo MAXIMUM_IF_PER_PROJECT ?>';
        project_xml_path = '<?php echo '../../xml/' . $project_id . '.xml'; ?>';
        //loading project xml
        load_xml();
        //filtering left panel content
        reset_left_panel_content();
        var user_projects_name_string = '<?php echo $user_project_name_list ?>';
        var user_projects_id_string = '<?php echo $user_project_id_list ?>';
        var user_project_name_list = user_projects_name_string.split(',');
        var user_project_id_list = user_projects_id_string.split(',');
        set_project_name_list(user_project_name_list);
        set_project_id_list(user_project_id_list);

        var custom_variable_list = JSON.parse('<?php
$variables = array();
foreach ($custom_variables as $cv) {
    $variable = array("variable_id" => $cv->variable_id, "variable_name" => $cv->variable_name, "variable_type" => $cv->variable_type, "variable_value" => $cv->variable_value);
    $variables[] = $variable;
}
echo json_encode(array('variable_list' => $variables));
?>');
        set_project_variables(custom_variable_list.variable_list);

        var base_url = '<?php echo base_url(); ?>';

        has_external_variables = '<?php echo $has_external_variables ?>';
        is_cancel_pressed_external_variable_upload = '<?php echo $is_cancel_pressed_external_variable_upload ?>';
        external_file_content_error = '<?php echo $external_file_content_error ?>';
        external_variable_values = "";
        var external_variable_length = '<?php echo count($external_variable_values) ?>';
        if (external_variable_length > 0 || is_cancel_pressed_external_variable_upload == 'true' || external_file_content_error == 'true')
        {
            if (is_cancel_pressed_external_variable_upload == 'true')
            {
                $("#label_show_messages_content").html("Press upload button again to load external variables.");
                $("#modal_show_messages").modal('show');
                //alert("Press upload button again to load external variables.");
            }
            else if (external_file_content_error == 'true')
            {
                $("#label_show_messages_content").html("Each variable must be in a separated line");
                $("#modal_show_messages").modal('show');
                //alert("Each variable must be in a separated line");
            }
            else if (external_variable_length > 0) {
                //$('#external_variable_list').dialog('open');
                external_variable_values = '<?php
$variable_values_counter = 0;
$variable_values = "";
foreach ($external_variable_values as $external_variable_value) {
    if ($variable_values_counter == 0) {
        $variable_values = $variable_values . $external_variable_value;
    } else {
        $variable_values = $variable_values . "," . $external_variable_value;
    }
    $variable_values_counter++;
}
echo $variable_values;
?>';
            }
        }

        set_server_base_url(base_url);

        trackUserOperation();

        $("#parameters_table").on("click", '#button_external_variable_upload', function() {
            var selected_anchor_id = "";
            $("a", $('#changing_stmt')).each(function() {
                //selected expression anchor id in natural language panel
                if ($(this).attr("class") == "selected_expression")
                {
                    selected_anchor_id = $(this).attr("id");
                }
            });
            //document.cookie= "selected_anchor_id" + "=" + selected_anchor_id;
            updateClientEndOperationCounter();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: '<?php echo base_url(); ?>' + "projects/update_project_left_panel_backup",
                data: {
                    project_id: '<?php echo $project_id; ?>',
                    left_panel_content: $("#selectable").html(),
                    selected_anchor_id: selected_anchor_id
                },
                success: function(data) {
                    window.location = '<?php echo base_url() . 'projects/upload_external_variables/' . $project_id; ?>';
                }
            });
        });
    });
    function save_project() {
        updateClientEndOperationCounter();
        waitScreen.show();
        $.ajax({
            dataType: 'json',
            type: "POST",
            url: '<?php echo base_url(); ?>' + "projects/update_project_left_panel",
            data: {
                project_id: '<?php echo $project_id; ?>',
                left_panel_content: $("#selectable").html()
            },
            success: function(data) {
                $("#label_show_messages_content").html(data.message);
                waitScreen.hide();
                $("#modal_show_messages").modal('show');
            }
        });
    }
    function set_language_c()
    {
        selected_language_id = language_id_c;
        $("#anchor_language_c").attr('class', "active");
        $("#anchor_language_java").attr('class', "");
    }

    function set_language_java()
    {
        selected_language_id = language_id_java;
        $("#anchor_language_c").attr('class', "");
        $("#anchor_language_java").attr('class', "active");
    }
</script>


<table class="table-responsive table" >
    <tr align="right" style="color:green">
        <td colspan="2">
            <div id="project_name_label"><b>Project Name&nbsp;:&nbsp;</b> <?php echo $selected_project->project_name ?> <b>&nbsp;Type&nbsp;:&nbsp;</b> <?php echo$project_type; ?> <b>&nbsp;Welcome&nbsp;:&nbsp;</b><?php echo $user_info['username'] ?></div>
        </td>
    </tr>
    <tr>
        <td colspan="2"><div id="condition_action_label">Condition in natural Language</div></td>
    </tr>

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
            <div id="demo1" class="demo" style="height:300px; width:100%; overflow: scroll; overflow-x: hidden">

<?php
foreach ($fObjectArray as $key => $objectList) {
    ?>
                    <ul>
                        <li id="<?php echo $key ?>" rel="folder">
                            <a href="#"><?php echo $key; ?></a>
                            <ul>
                    <?php
                    foreach ($objectList as $customObj) {
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
                foreach ($custom_variables as $cv) {
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
<!-- end of action_variable_modal -->
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
foreach ($custom_variables as $cv) {
    //sincere this will be used in arithmetic operator so boolean variable will not be visible here
    if ($cv->variable_type == "BOOLEAN") {
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
            echo "<a style='text-decoration:none;' href='#' id='l_c_l_p' onclick='logicalConnectorSetChangingStmt(this)'><input id='natural' type = 'hidden' value='{$key}' name='{$customObj->optionstype}' />{$displayNatural}</a>";
            echo "<br/>";
        }
        echo "</p></div>";
    }
}
echo "<h5><a href='#'>Variables</a></h5>";
echo "<div><p>";
foreach ($custom_variables as $cv) {
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
<!--<div id="add_variables_div" >
<?php echo form_open("variables/create_variable"); ?>    
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
    <button id="button_add_variable_ok" onclick="button_add_variable_ok_pressed()" type="button">Ok</button>
    <input type = "hidden" value="" name="project_left_panel_content_backup" id="project_left_panel_content_backup" />
    <input type="submit" id="button_add_variable_ok" onclick="return button_add_variable_ok_pressed()" value="Save"/>
    <button id="button_add_variable_cancel" onclick="button_add_variable_cancel_pressed()" type="button">Cancel</button>
<?php echo form_close(); ?>
    
</div>-->
<!-- end of add variable div modal -->

<!-- start of arithmetic operator  div modal -->
<!--<div id="arithmetic_operator_div" >
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
</div>-->
<!-- end of arithmetic operator div modal -->


<!--<div id="arithmetic_operator_condition_div" >
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
            </td>
        </tr>
        <tr>
            <td>
                <input type = "hidden" value="" name="arithmetic_operator_selected_item" id="arithmetic_operator_selected_item" />                
            </td>
        </tr>
    </table>
</div>-->
<!-- end of arithmetic operator condition div modal -->

<!--<div id="delete_block_confirmation_div_modal" >
    <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
        <tr>
            <td width="100%" height="100%" align='center'>
                <label id="label_delete_block_confirmation_div_modal" name="label_delete_block_confirmation_div_modal">Are you sure you want to delete this?</label>
            </td>
        </tr>
    </table>
</div>-->
<div class="modal fade" id="modal_delete_block_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmation Dialog</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                      <label id="label_delete_block_confirmation_div_modal" name="label_delete_block_confirmation_div_modal">Are you sure you want to delete this?</label>  
                    </div>
                    <div class="col-md-1"></div>
                </div>                
            </div>
            <div class="modal-footer">
                <input type="submit" class="btn btn-success" id="button_delete" onclick="delete_ok_pressed()" value="Delete"/>  
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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
foreach ($custom_variables as $cv) {
    //sincere this will be used in arithmetic operator so boolean variable will not be visible here
    if ($cv->variable_type == "BOOLEAN") {
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

<div id="save_as_project_div_modal" >
    <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
        <tr>
            <td width="100%" height="100%" align='center'>

<?php
$attributes = array('name' => 'form_submission', 'onsubmit' => 'return save_as_project_save_button_clicked()');
echo form_open("general_process/save_as_project", $attributes);
?>
                <label >Project Name</label>
                <input type="text" id="save_as_project_project_name" name="save_as_project_project_name" value=""/>
                <input type="hidden" id="save_as_project_left_panel_content" name="save_as_project_left_panel_content" value=""/>
                <input type="submit" id="save_as_project_save_button" value="Save"/>
<?php echo form_close(); ?>

            </td>
        </tr>
    </table>
</div>

<div id="save_as_replace_project_div_modal" >
    <table width='100%' height="100%" border='1' style='border-collapse:collapse;'>
        <tr>
            <td width="100%" height="100%" align='center'>
                <?php echo form_open("general_process/save_as_replace_project"); ?>
                <label >Project already exists. Do you want to replace it?</label><br/>
                <input type="hidden" id="save_as_replace_project_left_panel_content" name="save_as_replace_project_left_panel_content" value=""/>
                <input type="hidden" id="save_as_replace_project_name" name="save_as_replace_project_name" value=""/>
                <input type="hidden" id="save_as_replace_project_id" name="save_as_replace_project_id" value=""/>

                <input type="submit" id="save_as_replace_project_yes_button" value="Yes" onclick="return save_as_replace_project_yes_button_clicked()"/>
                <input type="submit" id="save_as_replace_project_no_button" value="No" onclick="return save_as_replace_project_no_button_clicked()"/>
<?php echo form_close(); ?>
            </td>
        </tr>
    </table>
</div>

<!--<div id="project_variable_list_div" >
    <table width="100%" border="1" style="border-collapse:collapse;">
        <tr>
            <td>Variable Name</td>
            <td>Variable Type</td>
            <td>Variable Value</td>
            <td>Delete</td>
        </tr> 
<?php echo form_open('variables/delete_variable'); ?>                
<?php
foreach ($custom_variables as $cv) {
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
<?php echo form_close(); ?>
    </table>
</div>-->

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
foreach ($external_variable_list as $external_variable) {
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
foreach ($external_variable_values as $external_variable_value) {
    if ($variable_values_counter == 0) {
        $variable_values = $variable_values . $external_variable_value;
    } else {
        $variable_values = $variable_values . " , " . $external_variable_value;
    }
    $variable_values_counter++;
}
?> 
    <table width="100%" style="border-collapse:collapse;">
        <tr>    
            <td>
        <?php echo $variable_values; ?>
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

<!--<div id="arithmetic_operator_change_div" >
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
</div>-->
<?php
$this->load->view('project/modal/my_projects_confirmation');
$this->load->view('project/modal/upload_project_confirmation');
$this->load->view('modal/show_messages_modal');
$this->load->view('modal/log_out_warning_modal');
$this->load->view('project/modal/wait_screen');
$this->load->view('project/modal/show_generated_code_modal');
$this->load->view('project/modal/bracket_add_modal');
$this->load->view('project/modal/download_project_modal');
$this->load->view('project/modal/show_variables_modal');
$this->load->view('project/modal/add_variables_modal');
$this->load->view('project/modal/add_arithmetic_modal');
$this->load->view('project/modal/accordion_modal');
$this->load->view('project/modal/action_variable_modal');
