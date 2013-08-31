if(typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, '').replace(/[/\u00a0/]+/g,'');
    }
}
$(function () 
{
    //accordion initialization starts
    $("#item_accordion").accordion();
    $("#comparison_accordion").accordion();
    $("#action_accordion").accordion();
    $("#action_variable_selection_accordion").accordion();
    $("#logical_connector_condition_left_item_accordion").accordion();
    $("#logical_connector_condition_comparison_item_accordion").accordion();
    $("#logical_connector_condition_right_item_accordion").accordion();    
    $("#arithmetic_operator_condition_accordion").accordion();
    $("#condition_boolean_variables_left_part_accordion").accordion(); 
    $("#condition_boolean_variables_middle_part_accordion").accordion(); 
    $("#condition_boolean_variables_right_part_accordion").accordion(); 
    $("#logical_connector_boolean_variables_left_part_accordion").accordion(); 
    $("#logical_connector_boolean_variables_middle_part_accordion").accordion();
    $("#logical_connector_boolean_variables_right_part_accordion").accordion(); 
    $("#logical_connector_part_of_conditioon_accordion").accordion();
    //accordion initialization ends
    
    $("#add_bracket_in_condition_div_selected_items").selectable({
        stop: function (e, ui) {
            var total_selected_items = 0;
            var first_selected_item_text = "";
            var second_selected_item_text ="";
            $("#add_bracket_in_condition_div_selected_items li").each(function(){
                if($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    total_selected_items++;   
                    if(total_selected_items == 1)
                    {
                        first_selected_item_text = $(this).text();
                    }
                    else if(total_selected_items == 2)
                    {
                        second_selected_item_text = $(this).text();
                    }
                }
                
            });
            if(total_selected_items == 1)
            {
                alert("'(' will be added before '"+first_selected_item_text+"'");
            }
            else if(total_selected_items == 2)
            {
                alert("'(' will be added before '"+first_selected_item_text+"' and ')' will be added after '"+second_selected_item_text+"'");
            }
        }
    });

    $("#logical_connector_removing_condition_selected_item").selectable(
    {
        stop: function (e, ui) {
            $(".ui-selected:first", this).each(function () {
                $(this).siblings().removeClass("ui-selected");
            });
        }
    });
    
    $("#selectable").selectable(
    {
        stop: function (e, ui) {
            $(".ui-selected:first", this).each(function () {
                
                updateClientEndOperationCounter();
                
                $(this).siblings().removeClass("ui-selected");
                var li_value = $(this).text().trim();
                
                if (li_value == "Click here to edit action" || li_value == "Click here to edit block") {
                    /*$.blockUI({
                        message: '',
                        theme: false,
                        baseZ: 500
                    });*/
                    $("#action_variable_selection_part").html("");
                    document.getElementById("action_variable_selection_part").style.border = "";
                    $('#action_variable_selection_accordion').hide();
                    
                    $('#action_variable_modal').dialog('open');
                    if(li_value == "Click here to edit block")
                    {
                        $('#condition_modal_selected_item .ui-selected').removeClass('ui-selected');
                        document.getElementById('actonSelectionCombo').selectedIndex = 0;
                        document.getElementById('actonSelectionCombo').style.visibility='hidden';
                        $('#condition_modal_selected_item').show();
                        $('#action_modal_selected_item').hide();
                        
                    }
                    else if(li_value == "Click here to edit action" )
                    {
                       $('#condition_modal_selected_item .ui-selected').removeClass('ui-selected');
                       document.getElementById('actonSelectionCombo').selectedIndex = 1;
                       document.getElementById('actonSelectionCombo').style.visibility='visible';
                       $('#condition_modal_selected_item').hide();
                       $('#action_modal_selected_item').show();
                    }
                    return false;
                }else if (li_value == "Click here to edit condition") {
                    /*$.blockUI({
                        message: '',
                        theme: false,
                        baseZ: 500
                    });*/
                    $( "#conditional_modal" ).dialog( "option", "width",620);
                    document.getElementById('conditionOrBooleanSelectionCombo').selectedIndex = 0;
                    $('#condition_selected_div').show();
                    $('#condition_selection_div_boolean_variable').hide();
                    $('#conditional_modal').dialog('open');
                } 
                else if (li_value.toLowerCase() == "if" || li_value == "THEN" || li_value.toLowerCase() == "else" || li_value == "(" || li_value == ")" || li_value == "{" || li_value == "}")
                {
                    //clearing natural language panel, code panel, parameter table and tree
                    $('#changing_stmt').html("");
                    $('#code_stmt').html("");
                    $('#parameters_table').html("");
                    $("li", $("#demo1")).each(function ()
                    {
                        $(this).hide();
                    });
                } 
                else if (li_value != "") {
                    left_panel_condition_or_action_selected();
                }
            });
        }
    });

    $("#l_p_c").hide();
    $("#demo1")

    .jstree({

        "plugins": ["themes", "html_data", "ui", "crrm", "hotkeys", "types"],
        "types" : {
            // I set both options to -2, as I do not need depth and children count checking
            // Those two checks may slow jstree a lot, so use only when needed
            "max_depth" : -2,
            "max_children" : -2,
            // I want only `drive` nodes to be root nodes
            // This will prevent moving or creating any other type as a root node
            "valid_children" : [ "drive" ],
            "types" : {
                // The default type
                "default" : {
                    // I want this type to have no children (so only leaf nodes)
                    // In my case - those are files
                    "valid_children" : "none",
                    // If we specify an icon for the default type it WILL OVERRIDE the theme icons
                    "icon" : {
                        "image" : "../../jstree_resource/images/a.png"
                    }
                },
                // The `folder` type
                "folder" : {
                    // can have files and other folders inside of it, but NOT `drive` nodes
                    "valid_children" : [ "default", "folder" ],
                    "icon" : {
                        "image" : "../../jstree_resource/images/i.png"
                    }
                }
            }
        }
    })
    .delegate("a","click", function(e) 
    {
        $("#demo1").jstree("toggle_node", this);
    })
    //node is selected in tree
    .bind("select_node.jstree", function (event, data)
    {
        updateClientEndOperationCounter();
        if(external_variable_values != "") {
            $.unblockUI();
            alert("Processing completed.");
            $('#external_variable_list').dialog('open');
            has_external_variables = "false";
        }
        
                
        //selected node name
        var id = data.rslt.obj.attr("id");
        //parent name of selected node
        var name = data.rslt.obj.attr("name");
        
        //if we select parent node then return
        if (!name) return;

        //creating parameters table content
        var selected_id;
        var selected_code;
        var nameArray = new Array();
        var valueArray = new Array();

        var selectedParent;
        var selectedChild;
        var cookie_contents = document.cookie.split(";");
        //retrieving tree node selected from anchor tag above code panel
        for (i = 0; i < cookie_contents.length; i++) {
            var selected_cookie = cookie_contents[i].split("=");
            if (selected_cookie[0].trim() == "selectedParent") {
                selectedParent = selected_cookie[1];
            }
            if (selected_cookie[0].trim() == "selectedChild") {
                selectedChild = selected_cookie[1];
            }
        }
        
        var is_action_selected = false;
        $("a", $("#selectable .ui-selected")).each(function ()
        {
            if($(this).attr("id") == "ssaid"){
                if($(this).attr("title") == "start_space_anchor_action" || $(this).attr("title") == "start_space_anchor_action_variable")
                {
                    is_action_selected = true;
                }
            }                
        });
        //current selected tree node differs from node selected from anchor above code panel
        //As a result we need to update left panel, panel above code, code panel, parameters table
        if (selectedParent.trim() != name.trim() || selectedChild.trim() != id.trim())
        {
            
            //resetting parameters table content
            document.getElementById("parameters_table").innerHTML = "";
            document.cookie = "selectedParent" + "=" + name;
            document.cookie = "selectedChild" + "=" + id;
            
            //validation of tree node selection starts 
            /*
             *If selected item from natural language panel is not boolean and selected tree node is boolean
             *then this will not be allowed to select tree node
             *If selected item from natural language panel is boolean and selected tree node is not boolean
             *then this will not be allowed to select tree node
            */
            var selected_anchor_text = "";
            var is_selected_item_boolean = false;
            var variable_type = "";
            var variable_name = "";
            //retrieving selected text of natural language panel
            $("a", $("#changing_stmt")).each(function () 
            {
                if ($(this).attr("class")) 
                {
                    selected_anchor_text = $(this).text().trim();
                }
            });
            //checking whether selected item of natural language panel is boolean or not
            $("#demo1 ul li#variable ul li").each(function(){
                variable_type = $(this).attr("title");
                variable_name = $(this).text().trim();
                if(variable_type == "BOOLEAN" && variable_name == selected_anchor_text)
                {
                    is_selected_item_boolean = true;
                }                        
            });
            if(!is_action_selected && is_selected_item_boolean && data.rslt.obj.attr("title") != "BOOLEAN")
            {
                alert("You are not allowed to select this. Please select a boolean variable.");
                return;
            }
            else if(!is_action_selected && !is_selected_item_boolean && data.rslt.obj.attr("title") == "BOOLEAN")
            {
                alert("You are not allowed to select this. Please select a number variable.");
                return;
            }
            //validation of tree node selection ends

            
            //if selected tree node is variable then we just need to update variable name. No need to call ajax 
            if(name == "variable")
            {                
                $("a", $("#changing_stmt")).each(function () 
                {
                    if ($(this).attr("class")) {
                        //updating expression above code panel
                        selected_id = $(this).attr("id");
                        var $custom_anchor = $(this);
                        $custom_anchor.html($custom_anchor.html().substring(0,$custom_anchor.html().lastIndexOf(">")+1)+id);
                        $("input", $(this)).each(function () {
                            $(this).attr("value", name);
                            $(this).attr("name", id);

                        });
                        //alert($custom_anchor.html().substring($custom_anchor.html().lastIndexOf(">")+1));
                        //return;
                        
                        
                        //updating expression on left panel
                        $("a", $("#selectable .ui-selected")).each(function ()
                        {
                            if($(this).attr("id") == selected_id){
                                $(this).html($custom_anchor.html());
                            }
                            $(this).removeAttr("onclick");
                            $(this).removeAttr("class");
                        });
                    }
                });
                //updating code panel
                $("a", $("#code_stmt")).each(function ()
                {
                    if ($(this).attr("id") == selected_id)
                    {
                        $(this).text(id);                               
                        //updating code on left panel
                        $("div",  $('#selectable .ui-selected')).each(function ()
                        {
                            $("input", $(this)).each(function () {
                                if($(this).attr("id") == selected_id){
                                    $(this).removeAttr("value");
                                    $(this).attr("value",id);
                                }
                            });
                        });
                        //updating parameters table
                        document.getElementById("parameters_table").innerHTML = "";

                    }
                }); 
                //updating variable default value
                if(is_action_selected)
                {
                    var var_name = data.rslt.obj.attr("id");
                    var var_value = "";
                    var var_type = "";
                    for(var counter = 0 ; counter < project_variable_list.length ; counter++)
                    {
                        if(project_variable_list[counter].getVariableName() == var_name)
                        {
                            var_value = project_variable_list[counter].getVariableValue();
                            var_type = project_variable_list[counter].getVariableType();
                        }
                    }
                    var table_content = "<table width='100%'  border='1' style='border-collapse:collapse;'>";
                    table_content = table_content+"<tr><td width='50%' align='center'>Variable Value</td><td width='50%' align='center'>";
                    if(var_type == "NUMBER")
                    {                        
                        table_content = table_content+"<input type='text' id='textinput_variable' name = 'textinput_variable' value = '"+var_value+"' onchange='updateNumberVariableValue(\""+var_name+"\")'></input></td></tr>";                    
                    }
                    else if(var_type == "BOOLEAN")
                    {
                        table_content = table_content+"<select name='combo_variable' id='combo_variable' onchange='comboVariableChange(\""+var_name+"\")'>";
                        if(var_value == "TRUE")
                        {
                            table_content = table_content+"<option value='TRUE' selected = 'selected'>TRUE</option>";
                            table_content = table_content+"<option value='FALSE'>FALSE</option>";
                        }
                        else
                        {
                            table_content = table_content+"<option value='TRUE'>TRUE</option>";
                            table_content = table_content+"<option value='FALSE' selected = 'selected'>FALSE</option>";
                        }
                    }
                    table_content = table_content+"</table>";
                    document.getElementById("parameters_table").innerHTML = table_content;
                    update_variable_value_in_action(var_name, var_value);
                }
            }
            else
            {
                var changed_tree_node_info = tree_node_change(name, id);
                var selectedExpressionCode = changed_tree_node_info[0];
                var selectedDisplayCode = changed_tree_node_info[1];
                var tableContent = changed_tree_node_info[2];
                
                $("a", $("#changing_stmt")).each(function () {
                    if ($(this).attr("class")) {
                        //updating expression above code panel
                        selected_id = $(this).attr("id");
                        var $custom_anchor = $(this);
                        //var html_text = $custom_anchor.html();
                        //var text_content = $custom_anchor.text();
                        //html_text = html_text.trim().replace(text_content.trim(), "");
                        //$custom_anchor.html(html_text + selectedExpressionCode);
                        $custom_anchor.html($custom_anchor.html().substring(0,$custom_anchor.html().lastIndexOf(">")+1)+selectedExpressionCode);
                        $("input", $(this)).each(function () {
                            $(this).attr("value", name);
                            $(this).attr("name", id);

                        });
                        //updating expression on left panel
                        $("a", $("#selectable .ui-selected")).each(function ()
                        {
                            if($(this).attr("id") == selected_id){
                                $(this).html($custom_anchor.html());
                            }
                            $(this).removeAttr("onclick");
                            $(this).removeAttr("class");
                        });
                    }
                });
                //updating code panel
                $("a", $("#code_stmt")).each(function ()
                {
                    if ($(this).attr("id") == selected_id)
                    {
                        $(this).text(selectedDisplayCode);                               
                        //updating code on left panel
                        $("div",  $('#selectable .ui-selected')).each(function ()
                        {
                            $("input", $(this)).each(function () {
                                if($(this).attr("id") == selected_id){
                                    $(this).removeAttr("value");
                                    $(this).attr("value",selectedDisplayCode);
                                }
                            });
                        });
                        //updating parameters table
                        document.getElementById("parameters_table").innerHTML = tableContent;

                    }
                });
            }            
            return;
        }

        if(selectedParent == "variable" && is_action_selected)
        {
            var var_name = "";
            var var_value = "";
            var var_type = "NUMBER";
            var anchor_counter = 1;
            $("a", $("#code_stmt")).each(function () 
            {
                if(anchor_counter == 3)
                {
                    var_value = $(this).text().trim();
                }
                anchor_counter++;        
            });
            if(var_value == "TRUE" || var_value == "FALSE")
            {
                var_type = "BOOLEAN";
            }
            var table_content = "<table width='100%'  border='1' style='border-collapse:collapse;'>";
            table_content = table_content+"<tr><td width='50%' align='center'>Variable Value</td><td width='50%' align='center'>";
            if(var_type == "NUMBER")
            {                        
                table_content = table_content+"<input type='text' id='textinput_variable' name = 'textinput_variable' value = '"+var_value+"' onchange='updateNumberVariableValue(\""+var_name+"\")'></input></td></tr>";                    
            }
            else if(var_type == "BOOLEAN")
            {
                table_content = table_content+"<select name='combo_variable' id='combo_variable' onchange='comboVariableChange(\""+var_name+"\")'>";
                if(var_value == "TRUE")
                {
                    table_content = table_content+"<option value='TRUE' selected = 'selected'>TRUE</option>";
                    table_content = table_content+"<option value='FALSE'>FALSE</option>";
                }
                else
                {
                    table_content = table_content+"<option value='TRUE'>TRUE</option>";
                    table_content = table_content+"<option value='FALSE' selected = 'selected'>FALSE</option>";
                }
            }
            table_content = table_content+"</table>";
            document.getElementById("parameters_table").innerHTML = table_content;
            update_variable_value_in_action(var_name, var_value);
            return;
        }
	
        //current selected statement
        $("a", $("#changing_stmt")).each(function () {
            if ($(this).attr("class")) {
                selected_id = $(this).attr("id");
            }
        });
        //code of current selected statement
        $("a", $("#code_stmt")).each(function () {
            if ($(this).attr("id") == selected_id) {
                selected_code = $(this).text();
            }
        });
        //generating parameters table
        //resetting parameters table content
        document.getElementById("parameters_table").innerHTML = "";
        
        var returned_array = reverse_code_process(name, id, selected_code.trim());
        nameArray = returned_array[0];
        valueArray = returned_array[1];
        
        var table_content = server_process(nameArray, valueArray, id, name);
        document.getElementById("parameters_table").innerHTML = table_content;
        
    }).delegate("a", "click", function (event, data)
    {
        event.preventDefault();
    }).delegate("li", "click", function (event, data)
    {
        updateClientEndOperationCounter();
        
        var cookie_contents = document.cookie.split(";");
        var selected_option = "";
        var node_name = "";
        for (i = 0; i < cookie_contents.length; i++) {
            var selected_cookie = cookie_contents[i].split("=");
            if (selected_cookie[0].trim()=="tree_nodes_expand_block")
            {
                selected_option = selected_cookie[1];
                if(selected_option == "true" )
                {
                    //alert("Please select condition in natural language first.");
                    node_name = "#"+$(this).attr("id");
                    setTimeout(function () {
                        $.jstree._reference(node_name).close_node(node_name);
                    }, 0);
                }
            }
			
            if (selected_cookie[0].trim() == "selectedParent") {
                selected_option = selected_cookie[1];
                if (selected_option == "comparison") {
                    if ($(this).attr("id") != "comparison") {
                        node_name = "#" + $(this).attr("id");
                        setTimeout(function () {
                            $.jstree._reference(node_name).close_node(node_name);
                        }, 0);
                    //alert("You are not allowed to expand this node");
                    }
                } else if (selected_option == "action") {
                    if ($(this).attr("id") != "action" && $(this).attr("id") != "variable") {
                        node_name = "#" + $(this).attr("id");
                        setTimeout(function () {
                            $.jstree._reference(node_name).close_node(node_name);
                        }, 0);
                    //alert("You are not allowed to expand this node");
                    }
                } else {
                    if ($(this).attr("id") == "action" || $(this).attr("id") == "comparison") {
                        node_name = "#" + $(this).attr("id");
                        setTimeout(function () {
                            $.jstree._reference(node_name).close_node(node_name);
                        }, 0);
                    //alert("You are not allowed to expand this node");
                    }
                }
            }
        }
        event.preventDefault();
    });

    $("#action_modal_selected_item").selectable(
    {
        stop: function (e, ui) {
            $(".ui-selected:first", this).each(function () {
                $(this).siblings().removeClass("ui-selected");
            });
        }
    });

    $("#condition_modal_selected_item").selectable(
    {
        stop: function (e, ui) {
            $(".ui-selected:first", this).each(function () {
                $(this).siblings().removeClass("ui-selected");
            });
        }
    });

    $("#logical_connector_selected_item").selectable(
    {
        stop: function (e, ui) {
            $(".ui-selected:first", this).each(function () {
                $(this).siblings().removeClass("ui-selected");
            });
        }
    });

    var $dialog = $('#generate_code_div_modal').dialog({
        height: 370,
        width: 640,
        autoOpen: false,
        title: 'Code'
    });
    
    var $dialog = $('#download_project_div_modal').dialog({
        height: 170,
        width: 640,
        autoOpen: false,
        title: 'Download Project'
    });
    
    var $logicalConnectorRemovingConditionDialog = $('#logical_connector_removing_condition_div').dialog({
        height: 170,
        width: 640,
        autoOpen: false,
        title: 'Select condition to delete.'
    });
    $('div#logical_connector_removing_condition_div').bind('dialogclose', function(event) {
        $.unblockUI();
    });
    
    $( "#action_variable_modal" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 500,
        modal: true,
        title: 'Condition or Action',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {                
                action_variable_modal_ok_pressed();
            }            
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#conditional_modal" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 620,
        modal: true,
        title: 'Condition',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {                
                updateClientEndOperationCounter();
                if(document.getElementById('conditionOrBooleanSelectionCombo').selectedIndex == 0)
                {
                    //alert("condition selected.");
                    checkContidionButton();                                        
                }
                else if(document.getElementById('conditionOrBooleanSelectionCombo').selectedIndex == 1)
                {
                    //alert("boolean selected.");
                    var flag = checkConditionBooleanButton();
                    if(flag)
                    {
                        $( this ).dialog( "close" );
                    }
                }
                
            }            
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    //This modal window is created to add arithmetic operator from menu item
    $( "#arithmetic_operator_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 400,
        modal: true,
        title: 'Add Arithmetic Operator',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {
                updateClientEndOperationCounter();
                var selected_operator = $("#arithmetic_operator_selection_combo option:selected").text();
    
                var right_part_value = "";
                var right_part_expression_type = $("#arithmetic_operator_right_part_type_selection_combo option:selected").text();
                if(right_part_expression_type == "CONDITION")
                {
                    //opening arithmetic operator condition div modal window
                    document.getElementById('arithmetic_operator_selected_item').value = $("#arithmetic_operator_selection_combo option:selected").text();

                    $('#arithmetic_operator_condition_div').dialog('open');                    
                }
                else if(right_part_expression_type == "CONSTANT")
                {
                    right_part_value = document.getElementById('arithmetic_operator_right_part_value').value;
                    //only number is allowed as constant value
                    if(right_part_value == "" || !isNumber(right_part_value))
                    {
                        alert("Please assign a number for the constant part.");
                        return;
                    }
                    process_operator(selected_operator, "constant","constant",right_part_value,right_part_value,"true");
                }
                $( this ).dialog( "close" );
            }
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    //This modal window is created to add condition as right part of arithmetic operator
    $( "#arithmetic_operator_condition_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 220,
        modal: true,
        title: 'Current Conditions',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {
                updateClientEndOperationCounter();
                var selected_operator = document.getElementById('arithmetic_operator_selected_item').value;
                var leftP = $("#arithmetic_operator_condition_left_part").html(); 

                if (leftP == "") {
                    alert("Please select a condition.");
                    return;
                }
                else
                {
                    $('#arithmetic_operator_condition_div').dialog('close');
                    var parent_node_array = new Array();
                    var child_node_array = new Array();
                    var language = $("#arithmetic_operator_condition_left_part").text();
                    counter = 0;
                    $("input", $("#arithmetic_operator_condition_left_part")).each(function ()
                    {
                        parent_node_array[counter] = 	$(this).attr("value");
                        child_node_array[counter] = 	$(this).attr("name");
                        counter++;
                    });
                    var code_array = default_code_generation(parent_node_array,child_node_array);
                    process_operator(selected_operator, child_node_array[0],parent_node_array[0],language,code_array[0],"true");                    
                }
                $("#arithmetic_operator_condition_left_part").html("");
                document.getElementById("arithmetic_operator_condition_left_part").style.border = "";
                $( this ).dialog( "close" );
            }
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    //This modal window is created to add boolean variable for logical connector operator
    $( "#logical_connector_boolean_variables_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 540,
        modal: true,
        title: 'Boolean Variables',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {
                updateClientEndOperationCounter();
                var flag = logical_connector_boolean_variable_selected_ok_pressed();
                if(flag)
                {
                    $( this ).dialog( "close" );
                }                
            }
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#delete_block_confirmation_div_modal" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 420,
        modal: true,
        title: 'Confirmation Dialog',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {
                updateClientEndOperationCounter();
                delete_item();
                $( this ).dialog( "close" );
            }
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#load_projects_confirmation_window_div_modal" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 420,
        modal: true,
        title: 'Confirmation Dialog',
        //setting buttons
        buttons:
        {
            
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    $( "#save_as_project_div_modal" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 420,
        modal: true,
        title: 'Save As Project',
        //setting buttons
        buttons:
        {
            
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#save_as_replace_project_div_modal" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 420,
        modal: true,
        title: 'Save As Project',
        //setting buttons
        buttons:
        {
            
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#add_variables_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 420,
        modal: true,
        title: 'Add Variables',
        //setting buttons
        buttons:
        {
            
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#project_variable_list_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 600,
        modal: true,
        title: 'Project Variables',
        //setting buttons
        buttons:
        {
            "Ok": function()
            {                
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );                              
            }
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#log_out_warning_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 500,
        modal: true,
        title: 'Warning',
        //setting buttons
        buttons:
        {
            "No": function()
            {
                sessionRenewConfirmed = false;
                $( this ).dialog( "close" );
                window.location.replace(server_base_url);
            },
            "Yes": function()
            {                
                sessionRenewConfirmed = true;
                $.ajax({
                    type: "POST",
                    url: "../../welcome/keep_server_alive",
                    data: {                
                    },
                    success: function () { 
                        lastOperationExecutionTimeInSecond = 0;
                        //alert("Server alive.");
                    }
                });
                $( this ).dialog( "close" );
            }            
        },
        close: function()
        {
            sessionRenewConfirmed = false;         
        }
    });
    
    $( "#condition_boolean_middle_part_change_confirmation_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 500,
        modal: true,
        title: 'Warning',
        //setting buttons
        buttons:
        {
            "No": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Yes": function()
            {                
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
                updateConditionBooleanVariableMiddleOrRightPart();
            }            
        },
        close: function()
        {
            updateClientEndOperationCounter();       
        }
    });
    
    $( "#condition_boolean_right_part_change_confirmation_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 500,
        modal: true,
        title: 'Warning',
        //setting buttons
        buttons:
        {
            "No": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Yes": function()
            {                
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
                updateConditionBooleanVariableMiddleOrRightPart();
            }            
        },
        close: function()
        {
            updateClientEndOperationCounter();       
        }
    });
    
    $( "#add_bracket_in_condition_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 500,
        modal: true,
        title: 'Selected Condition',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {                
                updateClientEndOperationCounter();
                button_add_bracket_in_condition_ok_pressed();                
            }            
        },
        close: function()
        {
            updateClientEndOperationCounter();       
        }
    });
    
    //This modal window is created to add logical connectors
    $( "#logical_connector_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 540,
        modal: true,
        title: 'Current Conditions',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {
                updateClientEndOperationCounter();
                button_logical_connector_ok_pressed();                                            
            }
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    //This modal window is created to add logical connectors condition
    $( "#logical_connector_conditional_modal" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 640,
        modal: true,
        title: 'Condition',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Ok": function()
            {
                updateClientEndOperationCounter();
                buttonLogicalConnectorConditionOkPressed();
            }
        },
        close: function()
        {
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#external_variable_list" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 500,
        modal: true,
        title: 'External variables',
        //setting buttons
        buttons:
        {
            "Cancel": function()
            {
                $( this ).dialog( "close" );
                updateClientEndOperationCounter();
                external_variable_values = "";
            },
            "Ok": function()
            {                
                updateClientEndOperationCounter();
                //updating text input value
                $('#externalTextInput').val(external_variable_values);
                //calling text input onchange method
                if (document.createEvent && document.getElementById('externalTextInput').dispatchEvent) {
                    var evt = document.createEvent("HTMLEvents");
                    evt.initEvent("change", true, true);
                    document.getElementById('externalTextInput').dispatchEvent(evt); // for DOM-compliant browsers
                } else if (document.getElementById('externalTextInput').fireEvent) {
                    document.getElementById('externalTextInput').fireEvent("onchange"); // for IE
                }
                $( this ).dialog( "close" );
            }            
        },
        close: function()
        {
            external_variable_values = "";
            updateClientEndOperationCounter();
            //closing the dialog            
        }
    });
    
    $( "#logical_operator_change_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 300,
        modal: true,
        title: 'Warning',
        //setting buttons
        buttons:
        {
            "No": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Yes": function()
            {                
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
                changeLogicalOperator($("#logical_operator_change_combo option:selected").text().trim());
            }            
        },
        close: function()
        {
            updateClientEndOperationCounter();       
        }
    });
    
    $( "#arithmetic_operator_change_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 300,
        modal: true,
        title: 'Warning',
        //setting buttons
        buttons:
        {
            "No": function()
            {
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
            },
            "Yes": function()
            {                
                updateClientEndOperationCounter();
                $( this ).dialog( "close" );
                changeArithmeticOperator($("#arithmetic_operator_change_combo option:selected").text().trim());
            }            
        },
        close: function()
        {
            updateClientEndOperationCounter();       
        }
    });
    
    $( "#upload_project_div" ).dialog(
    {
        //setting some properties
        autoOpen: false,
        width: 300,
        modal: true,
        title: 'Warning',
        close: function()
        {
            updateClientEndOperationCounter();       
        }
    });
    
});

//user clicks anchor from expression from above code panel
function manageExpression($href) {
    updateClientEndOperationCounter();
    //allowing user to expand tree nodes to change expression
    document.cookie= "tree_nodes_expand_block" + "=" + "false";

    var atags = $href.parentNode.getElementsByTagName("a");
    for (var i = 0; i < atags.length; i++) {
        //removing attribute named class inside anchor tag i.e. <a>
        atags[i].removeAttribute("class");
    }
    //assigning value of attribute named class
    $href.className = "selected_expression";
    
    //deselecting and closing all tree nodes
    $("#demo1").jstree('close_all', -1);
    setTimeout(function () {
        $.jstree._focused().deselect_all();
    }, 100);

    //retrieving input tag(<input>) inside anchor tag(<a>)
    var input_tag = $href.getElementsByTagName("input")[0];
    //expanding tree node based on selected anchor from above code panel
    setTimeout(function () {
        $.jstree._focused().select_node("#" + input_tag.getAttribute("name"));
    }, 100);
    document.cookie = "selectedParent" + "=" + input_tag.getAttribute("value");
    document.cookie = "selectedChild" + "=" + input_tag.getAttribute("name");
    
    if(input_tag.getAttribute("id") == "booleancomparison")
    {
        if(input_tag.getAttribute("name") == "is equal to")
        {
            document.getElementById("lable_condition_boolean_middle_part_change_confirmation").innerHTML = "Do you want to convert it to 'is not equal to'?";
        }
        else if(input_tag.getAttribute("name") == "is not equal to")
        {
            document.getElementById("lable_condition_boolean_middle_part_change_confirmation").innerHTML = "Do you want to convert it to 'is equal to'?";
        }
        $('#condition_boolean_middle_part_change_confirmation_div').dialog('open');
    }
    
    if(input_tag.getAttribute("id") == "booleanvalue")
    {
        if(input_tag.getAttribute("name") == "true")
        {
            document.getElementById("lable_condition_boolean_right_part_change_confirmation").innerHTML = "Do you want to convert it to 'false'?";
        }
        else if(input_tag.getAttribute("name") == "false")
        {
            document.getElementById("lable_condition_boolean_right_part_change_confirmation").innerHTML = "Do you want to convert it to 'true'?";
        }
        $('#condition_boolean_right_part_change_confirmation_div').dialog('open');
    }
    
    if(input_tag.getAttribute("value") == "logicalconnector")
    {
        if(input_tag.getAttribute("name").trim() == "AND")
        {
            $("select#logical_operator_change_combo")[0].selectedIndex = 0;
        }
        else if(input_tag.getAttribute("name").trim() == "OR")
        {
            $("select#logical_operator_change_combo")[0].selectedIndex = 1;
        }
        $('#logical_operator_change_div').dialog('open');
    }
    if(input_tag.getAttribute("value") == "arithmeticoperator")
    {
        if(input_tag.getAttribute("name").trim() == "+")
        {
            $("select#arithmetic_operator_change_combo")[0].selectedIndex = 0;
        }
        else if(input_tag.getAttribute("name").trim() == "-")
        {
            $("select#arithmetic_operator_change_combo")[0].selectedIndex = 1;
        }
        else if(input_tag.getAttribute("name").trim() == "*")
        {
            $("select#arithmetic_operator_change_combo")[0].selectedIndex = 2;
        }
        else if(input_tag.getAttribute("name").trim() == "/")
        {
            $("select#arithmetic_operator_change_combo")[0].selectedIndex = 3;
        }
        $('#arithmetic_operator_change_div').dialog('open');
    }
    
    
    return false;
}

function setChangingStmt($p) {
    updateClientEndOperationCounter();
    if ($p.id == "l_p") {
        document.getElementById("left_part").innerHTML = $p.innerHTML;
        document.getElementById("left_part").style.border = "inset red 4px";
    }
    if ($p.id == "c_p") {
        document.getElementById("cmp_part").innerHTML = $p.innerHTML;
        document.getElementById("cmp_part").style.border = "inset red 4px";
    }
    if ($p.id == "r_p") {
        document.getElementById("ritgh_part").innerHTML = $p.innerHTML;
        document.getElementById("ritgh_part").style.border = "inset red 4px";
    }
}

function logicalConnectorSetChangingStmt($p) {
    updateClientEndOperationCounter();
    if ($p.id == "l_c_l_p") {
        document.getElementById("logical_connector_left_part").innerHTML = $p.innerHTML;
        document.getElementById("logical_connector_left_part").style.border = "inset red 4px";
    }
    if ($p.id == "l_c_c_p") {
        document.getElementById("logical_connector_cmp_part").innerHTML = $p.innerHTML;
        document.getElementById("logical_connector_cmp_part").style.border = "inset red 4px";
    }
    if ($p.id == "l_c_r_p") {
        document.getElementById("logical_connector_right_part").innerHTML = $p.innerHTML;
        document.getElementById("logical_connector_right_part").style.border = "inset red 4px";
    }
}

function arithmeticOperatorConditionSetChangingStmt($p) {
    updateClientEndOperationCounter();
    if ($p.id == "a_o_c_l_p") {
        document.getElementById("arithmetic_operator_condition_left_part").innerHTML = $p.innerHTML;
        document.getElementById("arithmetic_operator_condition_left_part").style.border = "inset red 4px";
    }    
}

function conditionBooleanVariablesSetSelectedVariable($p) {
    updateClientEndOperationCounter();
    if ($p.id == "c_b_v_l_p") {
        document.getElementById("condition_boolean_variables_left_part").innerHTML = $p.innerHTML;
        document.getElementById("condition_boolean_variables_left_part").style.border = "inset red 4px";
    }    
}
function conditionBooleanVariablesSetSelectedVariableComparison($p) {
    updateClientEndOperationCounter();
    if ($p.id == "c_b_v_m_p") {
        document.getElementById("condition_boolean_variables_middle_part").innerHTML = $p.innerHTML;
        document.getElementById("condition_boolean_variables_middle_part").style.border = "inset red 4px";
    }    
}
function conditionBooleanVariablesSetSelectedVariableValue($p) {
    updateClientEndOperationCounter();
    if ($p.id == "c_b_v_r_p") {
        document.getElementById("condition_boolean_variables_right_part").innerHTML = $p.innerHTML;
        document.getElementById("condition_boolean_variables_right_part").style.border = "inset red 4px";
    }    
}
function actionVariableSelection($p) {
    updateClientEndOperationCounter();
    if ($p.id == "a_v_s") {
        document.getElementById("action_variable_selection_part").innerHTML = $p.innerHTML;
        document.getElementById("action_variable_selection_part").style.border = "inset red 4px";
    }    
}

function logicalConnectorBooleanVariablesSetSelectedVariable($p) {
    updateClientEndOperationCounter();
    if ($p.id == "l_c_b_v_l_p") {
        document.getElementById("logical_connector_boolean_variables_left_part").innerHTML = $p.innerHTML;
        document.getElementById("logical_connector_boolean_variables_left_part").style.border = "inset red 4px";
    }    
}
function logicalConnectorBooleanVariablesSetSelectedVariableComparison($p) {
    updateClientEndOperationCounter();
    if ($p.id == "l_c_b_v_m_p") {
        document.getElementById("logical_connector_boolean_variables_middle_part").innerHTML = $p.innerHTML;
        document.getElementById("logical_connector_boolean_variables_middle_part").style.border = "inset red 4px";
    }    
}
function logicalConnectorBooleanVariablesSetSelectedVariableValue($p) {
    updateClientEndOperationCounter();
    if ($p.id == "l_c_b_v_r_p") {
        document.getElementById("logical_connector_boolean_variables_right_part").innerHTML = $p.innerHTML;
        document.getElementById("logical_connector_boolean_variables_right_part").style.border = "inset red 4px";
    }    
}
function checkContidionButton() {
    updateClientEndOperationCounter();
    //taking three parts from conditional modal
    var leftP = $("#left_part").html();
    var cmpP = $("#cmp_part").html();
    var rightP = $("#ritgh_part").html();
    
    //boolean variable comparison starts
    var left_part_type = "";
    var right_part_type = "";
    $("input", $('#left_part')).each(function ()
    { 
        if($(this).attr("value") == "variable")
        {
            left_part_type = $(this).attr("id");
        }
    });
    $("input", $('#ritgh_part')).each(function ()
    { 
        if($(this).attr("value") == "variable")
        {
            right_part_type = $(this).attr("id");
        }
    });
    
    if((left_part_type == "BOOLEAN" && right_part_type != "BOOLEAN") || (left_part_type != "BOOLEAN" && right_part_type == "BOOLEAN"))
    {
        alert("Boolean variable can't be compared to number/function");
        return;
    }
    //boolean variable comparison ends
    
    //checking whether user selects all of the three parts of a condition
    if (leftP == "" || cmpP == "" || rightP == "") {
        alert("Incomple condition.");
        return;
    } 
    else
    {
        //unblocking the user interface
        $.unblockUI();
        //total number of characters of the selected string in left panel
        var left_panel_selected_string_length = $("#selectable .ui-selected").text().length;
        //total number of characters of the selected trimmed string in left panel
        var left_panel_selected_trimmed_string_length = $("#selectable .ui-selected").text().trim().length;
        //calculating total number of starting spaces of the selected string in left panel
        var initial_space_counter = left_panel_selected_string_length - left_panel_selected_trimmed_string_length;
        var starting_space = "";
        for(var i = 0 ; i < initial_space_counter ; i++){
            starting_space = starting_space + "&nbsp;";
        }

        //generatin random id for each part of a condition
        var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id3 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var starting_space_anchor_id = "ssaid";
		
        var id_array = new Array();
        var counter = 0;
        id_array[counter++] = id1;
        id_array[counter++] = id2;
        id_array[counter++] = id3;
		
        $('#conditional_modal').dialog("close");
		
        var parent_node_array = new Array();
        var child_node_array = new Array();
		
        counter = 0;
        //taking options and optionstype for each part of the condition
        $("input", $("#left_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });
        $("input", $("#cmp_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });
        $("input", $("#ritgh_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });        
        
        //generating codes for all of the parts of the condition        
        var code_array = default_code_generation(parent_node_array,child_node_array);				
        var div_part = "<div id='code'>";
        for(var i = 0 ; i < code_array.length ; i++){
            if(i == 0)
                div_part = div_part + "<input title = "+id_array[0]+"start type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
            else if(i == code_array.length-1)
                div_part = div_part + "<input title = "+id_array[0]+"end  type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
            else
                div_part = div_part + "<input  title='comparison' type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
        }
        div_part = div_part + "</div>";
        $('#selectable .ui-selected').html("<a title = start_space_anchor_condition style='cursor:pointer;' id=" + starting_space_anchor_id + "> <input id='natural' type='hidden'>" + starting_space +"</input></a><a title = "+id1+"start style='cursor:pointer;' id=" + id1 + "> " + leftP + "</a> <a style='cursor:pointer;' id=" + id2 + " title='comparison' > " + cmpP + "</a> <a title = "+id1+"end style='cursor:pointer;' id=" + id3 + "> " + rightP + "</a>"+div_part);        
    }
    //resetting three parts from conditional modal
    $("#left_part").html("");
    $("#cmp_part").html("");
    $("#ritgh_part").html("");
    document.getElementById("left_part").style.border = "";
    document.getElementById("cmp_part").style.border = "";
    document.getElementById("ritgh_part").style.border = "";
    
    left_panel_condition_or_action_selected();
}

function checkConditionBooleanButton()
{
    updateClientEndOperationCounter();
    //taking selected boolean variable
    var booleanVariableLeftPart = $("#condition_boolean_variables_left_part").html();
    var booleanVariableMiddlePart = $("#condition_boolean_variables_middle_part").html();
    var booleanVariableRightPart = $("#condition_boolean_variables_right_part").html();
    
    //checking whether user selects a boolean variable
    if (booleanVariableLeftPart == "") {
        alert("Please select a boolean variable.");
        return false;
    }
    else if (booleanVariableMiddlePart == "") {
        alert("Please select comparison for boolean variable.");
        return false;
    }
    else if (booleanVariableRightPart == "") {
        alert("Please select boolean variable comparison value.");
        return false;
    }
    else
    {
        //total number of characters of the selected string in left panel
        var left_panel_selected_string_length = $("#selectable .ui-selected").text().length;
        //total number of characters of the selected trimmed string in left panel
        var left_panel_selected_trimmed_string_length = $("#selectable .ui-selected").text().trim().length;
        //calculating total number of starting spaces of the selected string in left panel
        var initial_space_counter = left_panel_selected_string_length - left_panel_selected_trimmed_string_length;
        var starting_space = "";
        for(var i = 0 ; i < initial_space_counter ; i++){
            starting_space = starting_space + "&nbsp;";
        }
        
        var booleanVariableMiddlePartText = "";
        var booleanVariableRightPartText = "";
        var booleanVariableMiddlePartCode = "";
        var booleanVariableRightPartCode = "";
        
        $("input", $("#condition_boolean_variables_middle_part")).each(function ()
        {
            booleanVariableMiddlePartText = $(this).attr("name");
        });
        
        $("input", $("#condition_boolean_variables_right_part")).each(function ()
        {
            booleanVariableRightPartText = $(this).attr("name");
        });
        
        if(booleanVariableMiddlePartText.trim() == "is equal to")
        {
            booleanVariableMiddlePartCode = boolean_variable_code_panel_comparison_equal;
        }
        else if(booleanVariableMiddlePartText.trim() == "is not equal to")
        {
            booleanVariableMiddlePartCode = boolean_variable_code_panel_comparison_not_equal;
        }
        booleanVariableRightPartCode = booleanVariableRightPartText;
        
        //generatin random id for boolean variable
        var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id3 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        
        var starting_space_anchor_id = "ssaid";
		
        var id_array = new Array();
        var counter = 0;
        id_array[counter++] = id1;
        id_array[counter++] = id2;
        id_array[counter++] = id3;
	
        $('#conditional_modal').dialog("close");
		
        var parent_node_array = new Array();
        var child_node_array = new Array();
		
        counter = 0;
        //taking options and optionstype for each part of the condition
        $("input", $("#condition_boolean_variables_left_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });        
        
        //generating codes for boolean variable        
        var code_array = default_code_generation(parent_node_array,child_node_array);				
        var div_part = "<div id='code'>";
        for(var i = 0 ; i < code_array.length ; i++){
            if(i == 0)
                div_part = div_part + "<input title = "+id_array[0]+"start type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
            div_part = div_part + "<input title = "+id_array[1]+"      type='hidden' id ="+id_array[1]+" value='"+booleanVariableMiddlePartCode+"' name='condition'/>";
            div_part = div_part + "<input title = "+id_array[0]+"end   type='hidden' id ="+id_array[2]+" value='"+booleanVariableRightPartCode+"' name='condition'/>";
        }
        div_part = div_part + "</div>";
        $('#selectable .ui-selected').html("<a title = start_space_anchor_condition style='cursor:pointer;' id=" + starting_space_anchor_id + "> <input id='natural' type='hidden'>" + starting_space +"</input></a><a title = "+id1+"start style='cursor:pointer;' id=" + id1 + "> " + booleanVariableLeftPart + "</a><a title = '"+id2+"' style='cursor:pointer;' id='" + id2 + "'> " + booleanVariableMiddlePart + "</a><a title = '"+id1+"end' style='cursor:pointer;' id='" + id3 + "'> " + booleanVariableRightPart + "</a>"+div_part);
        //resetting selected boolean variable
        $("#condition_boolean_variables_left_part").html("");
        document.getElementById("condition_boolean_variables_left_part").style.border = "";
        $("#condition_boolean_variables_middle_part").html("");
        document.getElementById("condition_boolean_variables_middle_part").style.border = ""; 
        $("#condition_boolean_variables_right_part").html("");
        document.getElementById("condition_boolean_variables_right_part").style.border = ""; 
        
        left_panel_condition_or_action_selected();
        
        return true;
    }
}

function actionComboChange(action_comb)
{
    updateClientEndOperationCounter();
    $('#condition_modal_selected_item .ui-selected').removeClass('ui-selected');
    $('#action_modal_selected_item .ui-selected').removeClass('ui-selected');
    if(action_comb.selectedIndex == 0)
    {
        $('#condition_modal_selected_item').show();
        $('#action_modal_selected_item').hide();
        $('#action_variable_selection_accordion').hide();
    }
    else if(action_comb.selectedIndex == 1)
    {
        $('#condition_modal_selected_item').hide();
        $('#action_modal_selected_item').show();
        $('#action_variable_selection_accordion').hide();
    }
    else if(action_comb.selectedIndex == 2)
    {
        $('#condition_modal_selected_item').hide();
        $('#action_modal_selected_item').hide();
        $('#action_variable_selection_accordion').show();
    }
    
}

function conditionOrBooleanSelectionCombo(action_comb)
{
    updateClientEndOperationCounter();
    if(action_comb.selectedIndex == 0)
    {
        $( "#conditional_modal" ).dialog( "option", "width",620);
        $('#condition_selected_div').show();
        $('#condition_selection_div_boolean_variable').hide();
    }
    else if(action_comb.selectedIndex == 1)
    {
        $( "#conditional_modal" ).dialog( "option", "width",540);
        $('#condition_selected_div').hide();
        $('#condition_selection_div_boolean_variable').show();
    }
    
}

function arithmeticOperatorRightPartTypeComboChange(arithmeticOperatorRightPartTypeCombo) {
    updateClientEndOperationCounter();
    document.getElementById("arithmetic_operator_right_part_type_selection_combo").selectedIndex = arithmeticOperatorRightPartTypeCombo.selectedIndex;
    if(arithmeticOperatorRightPartTypeCombo.selectedIndex == 0)
    {
        document.getElementById('arithmetic_operator_right_part_value_label').style.visibility='visible';
        document.getElementById('arithmetic_operator_right_part_value').style.visibility='visible';
    }
    
    else if(arithmeticOperatorRightPartTypeCombo.selectedIndex == 1)
    {
        document.getElementById('arithmetic_operator_right_part_value_label').style.visibility='hidden';
        document.getElementById('arithmetic_operator_right_part_value').style.visibility='hidden';
    }
}


/*function customVariableListSelectionComboChange(customVariableListSelectionCombo) {
    document.getElementById("arithmetic_operator_right_part_variable_selection_combo").selectedIndex = customVariableListSelectionCombo.selectedIndex;
    
}*/

function arithmeticOperatorSelectionComboChange(arithmeticOperatorSelectionCombo) {
    updateClientEndOperationCounter();
    document.getElementById("arithmetic_operator_selection_combo").selectedIndex = arithmeticOperatorSelectionCombo.selectedIndex;
}

function button_add_bracket_in_condition_ok_pressed()
{
    updateClientEndOperationCounter();
    var first_anchor_id = "";
    var second_anchor_id = "";
    var total_selected_items  = 0;
    
    //checking bracket selection consistency
    var check_brackets = "false";
    var stop_checking_brackets = "false";
    var stack_counter = 0;
    var inconsistent_brackets = "false";
    
    var is_start_comparison = false;
    var is_end_comparison = false;
    var previous_item_check = true;
    var next_item_check = false;
    var previous_item_value = "";
    var next_item_value = "";
    var has_inside_comparison = false;
    
    $("li", $('#add_bracket_in_condition_div_selected_items')).each(function ()
    {
        if(next_item_check == true)
        {
            next_item_check = false;
            $("a", $(this)).each(function ()
            {
                $("input", $(this)).each(function ()
                {
                    next_item_value = $(this).attr("value");                
                });           
            });
            
        }
        if ($(this).attr("class") == "ui-widget-content ui-selected")
        {
            $("a", $(this)).each(function ()
            {
                if(first_anchor_id == "")
                {
                    first_anchor_id = $(this).attr("id");
                    total_selected_items = total_selected_items + 1;
                    check_brackets = "true";
                    
                    if($(this).attr("title") == "comparison")
                    {
                        is_start_comparison = true;
                    }
                    previous_item_check = false;
                    $("input", $(this)).each(function ()
                    {
                        if($(this).attr("value") == "booleancomparison")
                        {
                            is_start_comparison = true;
                        }                
                    });
                }
                else if(second_anchor_id == "")
                {
                    second_anchor_id = $(this).attr("id");
                    total_selected_items = total_selected_items + 1;
                    stop_checking_brackets = "true";
                    
                    if($(this).attr("title") == "comparison")
                    {
                        is_end_comparison = true;
                    }
                    next_item_check = true;
                    $("input", $(this)).each(function ()
                    {
                        if($(this).attr("value") == "booleancomparison")
                        {
                            is_end_comparison = true;
                        }                
                    });
                }
                else
                {
                    total_selected_items = total_selected_items + 1;                    
                }
            });
        }
        $("a", $(this)).each(function ()
        {
            if(first_anchor_id != "" && second_anchor_id == "" && $(this).attr("title") == "comparison")
            {
                has_inside_comparison = true;
            }
        });
        
        if(previous_item_check == true)
        {
            $("a", $(this)).each(function ()
            {
                $("input", $(this)).each(function ()
                {
                    previous_item_value = $(this).attr("value");                
                });              
            });
        }        
        if(check_brackets == "true")
        {
            $("a", $(this)).each(function ()
            {
                if($(this).attr("title") !== undefined)
                {
                    if($(this).attr("title").indexOf("startbracket") >= 0)
                    {
                        stack_counter++;
                    }
                    else if($(this).attr("title").indexOf("endbracket") >= 0)
                    {
                        stack_counter--;
                    }
                }
                
            });
            if(stack_counter < 0)
            {
                inconsistent_brackets = "true";
                check_brackets = "false";
            }
        }
        
        if(stop_checking_brackets == "true")
        {
            check_brackets = "false";
        }
    });
    if(total_selected_items > 2)
    {
        alert("You are not allowed to select more than two items.");
        return;
    }
    else if(total_selected_items < 2)
    {
        alert("Please select two items.");
        return;
    }
    
    // this type of syntax is not allowed "a+b(>5)"    
    if(is_start_comparison == true || is_end_comparison == true)
    {
        alert("You are not allowed to add bracket here.");
        return;
    }
    // this type of syntax is not allowed "a+(b>5)"
    if((previous_item_value == "arithmeticoperator" || next_item_value == "arithmeticoperator") && has_inside_comparison == true)
    {
        alert("You are not allowed to add bracket here.");
        return;
    }
    
    if(inconsistent_brackets == "true" || stack_counter != 0)
    {
        alert("Your selection will make the condition inconsistent. Please select correctly.");
        return;
    }
    
    var $changing_stmt_anchor_list = "";
    var $code_stmt_anchor_list = "";
    var $left_panel_anchor_list = "";
    var $left_panel_code_list = "";
    
    var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    var selected_id = "";
    var changing_stmt_selected_id = "";
    var left_panel_anchor_title = "";
    var left_panel_code_title = "";
    var title_attribute = "";
    //adding two anchors in natural language panel. title attribute is used to identify start anchor and end anchor of bracket
    $("a", $("#changing_stmt")).each(function () 
    {
        
        if ($(this).attr("id") == first_anchor_id) 
        {
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + "<a style='cursor:pointer;' id="+id1+" title='"+id1+"-"+id2+"-startbracket'  onclick = 'manageExpression(this)' > <input style='' value='bracket' name='(' type='hidden' > ( </a>";
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + $(this).prop('outerHTML');            
        }
        else if ($(this).attr("id") == second_anchor_id) 
        {
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + $(this).prop('outerHTML'); 
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + "<a style='cursor:pointer;' id="+id2+" title='"+id1+"-"+id2+"-endbracket'  onclick = 'manageExpression(this)' > <input style='' value='bracket' name=')' type='hidden'> ) </a>";                       
        }
        else
        {
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + $(this).prop('outerHTML');
        
        }
    });
    //we have generated html block after adding brackets. So, we are assigning the block to natural language panel
    $("#changing_stmt").html($changing_stmt_anchor_list);
    
    //updating code_stmt i.e. code panel
    //adding two anchors in code panel. title attribute is used to identify start anchor and end anchor of bracket
    $("a", $("#code_stmt")).each(function () 
    {
        if($(this).attr("id") == first_anchor_id)
        {
            $code_stmt_anchor_list = $code_stmt_anchor_list + "<a id="+id1+" title='"+id1+"-"+id2+"-startbracket'> ( </a>";
            $code_stmt_anchor_list = $code_stmt_anchor_list + $(this).prop('outerHTML');
        }
        else if($(this).attr("id") == second_anchor_id)
        {
            $code_stmt_anchor_list = $code_stmt_anchor_list + $(this).prop('outerHTML');
            $code_stmt_anchor_list = $code_stmt_anchor_list + "<a id="+id2+" title='"+id1+"-"+id2+"-endbracket'> ) </a>";            
        }
        else
        {
            $code_stmt_anchor_list = $code_stmt_anchor_list + $(this).prop('outerHTML'); 
        }
        
    });
    $("#code_stmt").html($code_stmt_anchor_list);
    
    //updating left panel anchors
    //adding two anchors in left panel. title attribute is used to identify start anchor and end anchor of bracket
    title_attribute = "";
    $("a", $("#selectable .ui-selected")).each(function ()
    {
        if($(this).attr("id") == first_anchor_id){
            $left_panel_anchor_list = $left_panel_anchor_list + "<a style='cursor:pointer;' id="+id1+" title='"+id1+"-"+id2+"-startbracket'> <input style='' value='bracket' name='(' type='hidden'> ( </a>";            
            $left_panel_anchor_list = $left_panel_anchor_list + $(this).prop('outerHTML');
        }
        else if($(this).attr("id") == second_anchor_id){
            $left_panel_anchor_list = $left_panel_anchor_list + $(this).prop('outerHTML');        
            $left_panel_anchor_list = $left_panel_anchor_list + "<a style='cursor:pointer;' id="+id2+" title='"+id1+"-"+id2+"-endbracket'> <input style='' value='bracket' name=')' type='hidden'> ) </a>";            
        }
        else
        {
            $left_panel_anchor_list = $left_panel_anchor_list + $(this).prop('outerHTML'); 
        }
    });
    //adding two anchors in code of left panel. title attribute is used to identify start anchor and end anchor of bracket
    title_attribute = "";
    $("div", $("#selectable .ui-selected")).each(function ()
    {
        $("input", $(this)).each(function ()
        {
            if($(this).attr("id") == first_anchor_id){
                $left_panel_code_list = $left_panel_code_list + "<input style='' id="+id1+" title='"+id1+"-"+id2+"-startbracket' type='hidden' name='bracket' value='('></input>";                
                $left_panel_code_list = $left_panel_code_list + $(this).prop('outerHTML');
            }
            else if($(this).attr("id") == second_anchor_id){
                $left_panel_code_list = $left_panel_code_list + $(this).prop('outerHTML');
                $left_panel_code_list = $left_panel_code_list + "<input style='' id="+id2+" title='"+id1+"-"+id2+"-endbracket' type='hidden' name='bracket' value=')'></input>";
            }
            else
            {
                $left_panel_code_list = $left_panel_code_list + $(this).prop('outerHTML'); 
            } 
        });
        
    });
    $left_panel_anchor_list = $left_panel_anchor_list +"<div id='code'>"+$left_panel_code_list+"</div>";
    $("#selectable .ui-selected").html($left_panel_anchor_list);
    
    
    $('#add_bracket_in_condition_div').dialog('close');
    left_panel_condition_or_action_selected();
}

function button_add_bracket_in_condition_cancel_pressed()
{
    updateClientEndOperationCounter();
    $.unblockUI();
    $('#add_bracket_in_condition_div').dialog('close');
}

/*
 * User selects a condition or action from left panel
 * this method updates condition in natural language panel and code panel
 **/
function left_panel_condition_or_action_selected()
{
    updateClientEndOperationCounter();
    $("#selectable .ui-selected:first").each(function () 
    {
        //contion action label  is dynamically updated here
        var selection_for_label = "";
        $("a", $(this)).each(function ()
        {
            if($(this).attr("id") == "ssaid")
            {
                //updating label in code panel and above code panel
                if($(this).attr("title") == "start_space_anchor_condition")
                {
                    selection_for_label = "condition";
                    $('#condition_action_label').html("Condition in natural Language");
                }
                else if($(this).attr("title") == "start_space_anchor_action")
                {
                    selection_for_label = "action";
                    $('#condition_action_label').html("Action in natural Language");
                } 
                else if($(this).attr("title") == "start_space_anchor_action_variable")
                {
                    selection_for_label = "action_variable";
                    $('#condition_action_label').html("Action in natural Language");
                }
            }
            /*$("input", $(this)).each(function ()
            {
                //updating label in code panel and above code panel
                selection_for_label = $(this).attr("value");
                if(selection_for_label == "action")
                {
                    $('#condition_action_label').html("Action in natural Language");
                }
                else
                {
                    selection_for_label = "condition";
                    $('#condition_action_label').html("Condition in natural Language");
                }
            });*/
        });

        $("li", $("#demo1")).each(function ()
        {
            $(this).show();
        });

        //based on selected expression tree node are dynamically populated
        $("li", $("#demo1")).each(function ()
        {
            var selected_id = $(this).attr("id");
            var selected_name = $(this).attr("name");
            
            if(selection_for_label == "condition"){
                if(selected_id == "action")
                {
                    $(this).hide();
                }
            }
            else if(selection_for_label == "action"){
                if(selected_id != "action")
                {
                    if(selected_name){
                        if(selected_name != "action"){
                            $(this).hide();
                        }
                    }
                    else{
                        $(this).hide();
                    }
                }
            } 
            else if(selection_for_label == "action_variable"){
                if(selected_id != "variable")
                {
                    if(selected_name){
                        if(selected_name != "variable"){
                            $(this).hide();
                        }
                    }
                    else{
                        $(this).hide();
                    }
                }
            }  
        });

        //resetting parameters table
        $('#parameters_table').html("");
        //user will not be able to open tree node until they select anchor tag above code panel
        document.cookie= "tree_nodes_expand_block" + "=" + "true";
        $( "#demo1").jstree('close_all', -1);
        setTimeout(function () {
            $.jstree._focused().deselect_all();
        }, 100);

        //updating expression above code panel
        $('#changing_stmt').html($(this).html());
        $("a", $("#changing_stmt")).each(function ()
        {
            $(this).attr('onclick', 'manageExpression(this)');
            //in left panel we have an anchor which contains initial spaces, 
            //we are removing that anchor for natural language panel
            if($(this).attr("id") == "ssaid")
            {
                $(this).remove();
            }
        });
        $("div", $("#changing_stmt")).each(function ()
        {
            $(this).remove();
        });
        //updating code panel
        $('#code_stmt').html($(this).html());
        var counter = 0;
        var id_array = new Array();
        var code_value_array = new Array();
        $("div", $("#code_stmt")).each(function ()
        {
            $("input", $(this)).each(function ()
            {
                id_array[counter] = $(this).attr("id");
                code_value_array[counter] = $(this).attr("value");
                counter++;
            });
        });
        $("div", $("#code_stmt")).each(function ()
        {
            $(this).remove();
        });
        counter = 0;
        $("a", $("#code_stmt")).each(function () {
            //in left panel we have an anchor which contains initial spaces, 
            //we are removing that anchor for code panel
            if($(this).attr("id") == "ssaid")
            {
                $(this).remove();
            }
            else{
                $(this).removeAttr("onclick");
                $(this).removeAttr("style");
                $(this).removeAttr("class");
                var $custom_anchor = $(this);
                $("input", $(this)).each(function () {
                    for(counter = 0 ; counter < id_array.length ; counter ++){
                        if(id_array[counter] == $custom_anchor.attr("id")){
                            $custom_anchor.text("");
                            var html_text = $custom_anchor.html();
                            //var text_content = $custom_anchor.text();
                            //html_text = html_text.trim().replace(text_content.trim(), "");
                            $custom_anchor.html(html_text + code_value_array[counter]);
                        }
                    }
                });
            }

        });
    });
}

/*
 * User presses download btton in code download window
 **/
function generate_code_save_button_pressed()
{
    updateClientEndOperationCounter();
    $('#generate_code_div_modal').dialog("close");
}

/*
 * User presses download button to download project left panel content
 **/
function download_project_save_button_clicked()
{
    updateClientEndOperationCounter();
    $('#download_project_div_modal').dialog("close");
}

/*
 * When a new project is loaded, left panel content is assigned from database. After assigning left panel content
 * there are some redundant tags and we need to clear those
 **/
function reset_left_panel_content()
{
    updateClientEndOperationCounter();
    $('#selectable').each(function()
     {
        $("li", $(this)).each(function ()
        {
            $(this).attr("class",$(this).attr("class").replace(" ui-selectee", ""));            
        });
     });
}

function updateConditionBooleanVariableMiddleOrRightPart()
{
    var selected_id = "";
    var current_anchor_text = "";
    var current_anchor_updated_text = "";
    var current_anchor_updated_code = "";
    $("a", $("#changing_stmt")).each(function () 
    {
        if ($(this).attr("class")) {
            //updating expression in natural language panel
            selected_id = $(this).attr("id");
            
            current_anchor_text = $(this).text().trim();
            if(current_anchor_text == "true")
            {
                current_anchor_updated_text = "false";
                current_anchor_updated_code = "false";
            }
            else if(current_anchor_text == "false")
            {
                current_anchor_updated_text = "true";
                current_anchor_updated_code = "true";
            }
            else if(current_anchor_text == "is equal to")
            {
                current_anchor_updated_text = "is not equal to";
                current_anchor_updated_code = " != ";
            }
            else if(current_anchor_text == "is not equal to")
            {
                current_anchor_updated_text = "is equal to";
                current_anchor_updated_code = " == ";
            }
            
            var $custom_anchor = $(this);
            $custom_anchor.html($custom_anchor.html().substring(0,$custom_anchor.html().lastIndexOf(">")+1)+current_anchor_updated_text);
            $("input", $(this)).each(function () {
                $(this).attr("name", current_anchor_updated_text);

            });
            //updating expression on left panel
            $("a", $("#selectable .ui-selected")).each(function ()
            {
                if($(this).attr("id") == selected_id){
                    $(this).html($custom_anchor.html());
                }
                $(this).removeAttr("onclick");
                $(this).removeAttr("class");
            });
        }
    });
    //updating code panel
    $("a", $("#code_stmt")).each(function ()
    {
        if ($(this).attr("id") == selected_id)
        {
            $(this).text(current_anchor_updated_code);                               
            //updating code on left panel
            $("div",  $('#selectable .ui-selected')).each(function ()
            {
                $("input", $(this)).each(function () {
                    if($(this).attr("id") == selected_id){
                        $(this).removeAttr("value");
                        $(this).attr("value",current_anchor_updated_code);
                    }
                });
            });
            //updating parameters table
            document.getElementById("parameters_table").innerHTML = "";
        }
    });
}

function changeLogicalOperator(selectedItem)
{
    var selected_id = "";
    var current_anchor_updated_text = "";
    var current_anchor_updated_code = "";
    $("a", $("#changing_stmt")).each(function () 
    {
        if ($(this).attr("class")) {
            //updating expression in natural language panel
            selected_id = $(this).attr("id");
            if(selectedItem == "OR")
            {
                current_anchor_updated_text = " OR ";
                current_anchor_updated_code = " || ";
                $(this).attr("title", 'logical_connector_or');
            }
            else if(selectedItem == "AND")
            {
                current_anchor_updated_text = " AND ";
                current_anchor_updated_code = " && ";
                $(this).attr("title", 'logical_connector_and');
            }            
            var $custom_anchor = $(this);
            $custom_anchor.html($custom_anchor.html().substring(0,$custom_anchor.html().lastIndexOf(">")+1)+current_anchor_updated_text);
            $("input", $(this)).each(function () {
                $(this).attr("name", current_anchor_updated_text);

            });
            //updating expression on left panel
            $("a", $("#selectable .ui-selected")).each(function ()
            {
                if($(this).attr("id") == selected_id){
                    $(this).html($custom_anchor.html());
                }
                $(this).removeAttr("onclick");
                $(this).removeAttr("class");
            });
        }
    });
    //updating code panel
    $("a", $("#code_stmt")).each(function ()
    {
        if ($(this).attr("id") == selected_id)
        {
            $(this).text(current_anchor_updated_code);                               
            //updating code on left panel
            $("div",  $('#selectable .ui-selected')).each(function ()
            {
                $("input", $(this)).each(function () {
                    if($(this).attr("id") == selected_id){
                        $(this).removeAttr("value");
                        $(this).attr("value",current_anchor_updated_code);
                    }
                });
            });
            //updating parameters table
            document.getElementById("parameters_table").innerHTML = "";
        }
    });
}

function changeArithmeticOperator(selectedItem)
{
    var selected_id = "";
    var current_anchor_updated_text = "";
    var current_anchor_updated_code = "";
    $("a", $("#changing_stmt")).each(function () 
    {
        if ($(this).attr("class")) {
            //updating expression in natural language panel
            selected_id = $(this).attr("id");
            if(selectedItem == "+")
            {
                current_anchor_updated_text = " + ";
                current_anchor_updated_code = " + ";
            }
            else if(selectedItem == "-")
            {
                current_anchor_updated_text = " - ";
                current_anchor_updated_code = " - ";
            } 
            else if(selectedItem == "*")
            {
                current_anchor_updated_text = " * ";
                current_anchor_updated_code = " * ";
            }
            else if(selectedItem == "/")
            {
                current_anchor_updated_text = " / ";
                current_anchor_updated_code = " / ";
            }
            var $custom_anchor = $(this);
            $custom_anchor.html($custom_anchor.html().substring(0,$custom_anchor.html().lastIndexOf(">")+1)+current_anchor_updated_text);
            $("input", $(this)).each(function () {
                $(this).attr("name", current_anchor_updated_text);

            });
            //updating expression on left panel
            $("a", $("#selectable .ui-selected")).each(function ()
            {
                if($(this).attr("id") == selected_id){
                    $(this).html($custom_anchor.html());
                }
                $(this).removeAttr("onclick");
                $(this).removeAttr("class");
            });
        }
    });
    //updating code panel
    $("a", $("#code_stmt")).each(function ()
    {
        if ($(this).attr("id") == selected_id)
        {
            $(this).text(current_anchor_updated_code);                               
            //updating code on left panel
            $("div",  $('#selectable .ui-selected')).each(function ()
            {
                $("input", $(this)).each(function () {
                    if($(this).attr("id") == selected_id){
                        $(this).removeAttr("value");
                        $(this).attr("value",current_anchor_updated_code);
                    }
                });
            });
            //updating parameters table
            document.getElementById("parameters_table").innerHTML = "";
        }
    });
}
