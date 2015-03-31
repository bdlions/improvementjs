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

<!--<div id="condition_boolean_middle_part_change_confirmation_div" >
    <table width="100%" border="1" style="border-collapse:collapse;">
        <tr>
            <td><label id="lable_condition_boolean_middle_part_change_confirmation"></label></td>

        </tr>
    </table>
</div>-->

<!--<div id="condition_boolean_right_part_change_confirmation_div" >
    <table width="100%" border="1" style="border-collapse:collapse;">
        <tr>
            <td><label id="lable_condition_boolean_right_part_change_confirmation"></label></td>

        </tr>
    </table>
</div>-->

<!--<div id="external_variable_list" >
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
    </table>-->
<!--</div>-->


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
$this->load->view('project/modal/change_arithmetic_operator_modal');
$this->load->view('project/modal/condition_edit_modal');
$this->load->view('project/modal/action_variable_modal');
$this->load->view('project/modal/arithmetic_operator_condition_modal');
$this->load->view('project/modal/add_logical_connector_modal');
$this->load->view('project/modal/logical_connector_condition_modal');
$this->load->view('project/modal/logical_connector_boolean_modal');
$this->load->view('project/modal/change_logical_operator_modal');
$this->load->view('project/modal/delete_block_modal');
$this->load->view('project/modal/save_project_modal');
$this->load->view('project/modal/external_variables_show_modal');
$this->load->view('project/modal/Boolean_right_part_change_modal');
$this->load->view('project/modal/Boolean_middle_part_change_modal');
?>

