function updateNumberVariableValue(variable_name)
{
    updateClientEndOperationCounter();
    var reg_exp = /^-?\d+$/g;
    if(!reg_exp.test(document.getElementById("textinput_variable").value.trim()))
    {
        $('#label_alert_message').text("Please assign number value.");
        $('#div_alert_message').dialog('open');
        return;
    }
    update_variable_value_in_action(variable_name, document.getElementById("textinput_variable").value);
}
function comboVariableChange(variable_name)
{
    updateClientEndOperationCounter();
    update_variable_value_in_action(variable_name, $("#combo_variable option:selected").text());
}
function update_variable_value_in_action(variable_name, booleanVariableValue)
{
    updateClientEndOperationCounter();
    //alert(variable_name+booleanVariableValue);
    var anchor_counter = 1;
    //updating expression on natural language panel
    $("a", $("#changing_stmt")).each(function () 
    {
        if(anchor_counter === 3)
        {
            $(this).text(booleanVariableValue);
        }
        anchor_counter++;        
    });
    anchor_counter = 1;
    //updating expression on left panel
    $("a", $("#selectable .ui-selected")).each(function ()
    {
        if(anchor_counter === 4)
        {
            $(this).text(booleanVariableValue);
        }
        anchor_counter++;
    });
    
    //updating code structure in left panel
    $("div",  $('#selectable .ui-selected')).each(function ()
    {
        var input_counter = 1;
        $("input", $(this)).each(function () {
            if(input_counter === 3)
            {
                $(this).attr("value",booleanVariableValue);
            }
            input_counter++;
        });
    }); 
    //updating code panel
    generate_selected_item_code(); 
}

function action_variable_modal_ok_pressed()
{
    updateClientEndOperationCounter();
    if(document.getElementById("actonSelectionCombo").selectedIndex == 2)
    {
        //taking selected variable
        var booleanVariablePart = $("#action_variable_selection_part").html();
        var booleanVariableName = $("#action_variable_selection_part").text();
        //checking whether user selects a variable or not
        if (booleanVariablePart == "") {
            $('#label_alert_message').text("Please select a variable.");
            $('#div_alert_message').dialog('open');
            return false;
        }
    }
    
    
    //total number of characters of the selected string in left panel
    //var left_panel_selected_string_length = $("#selectable .ui-selected").text().length;
    //total number of characters of the selected trimmed string in left panel
    //var left_panel_selected_trimmed_string_length = $("#selectable .ui-selected").text().trim().length;
    //calculating total number of starting spaces of the selected string in left panel
    //var initial_space_counter = left_panel_selected_string_length - left_panel_selected_trimmed_string_length;
    
    //each li has an id containing total number of initial empty spaces
    var initial_space_counter = $("#selectable .ui-selected").attr("id");
    
    var starting_space = "";
    for(var i = 0 ; i < initial_space_counter ; i++){
        starting_space = starting_space + "&nbsp;";
    }

    var li_value = "";
    if(document.getElementById("actonSelectionCombo").selectedIndex == 0)
    {
        li_value = $("#condition_modal_selected_item .ui-selected").text();
        if(li_value == "")
        {
            $('#label_alert_message').text("Please select an item.");
            $('#div_alert_message').dialog('open');
            return false;
        }
    }
    else if(document.getElementById("actonSelectionCombo").selectedIndex == 1)
    {
        li_value = $("#action_modal_selected_item .ui-selected").text();
        if(li_value == "")
        {
            $('#label_alert_message').text("Please select an item.");
            $('#div_alert_message').dialog('open');
            return false;
        }
    }

    var id1 = "";
    var id2 = "";
    var id3 = "";
    var starting_space_anchor_id = "";
    var counter = 0;
    var parent_node_array = new Array();
    var child_node_array = new Array();
    var div_part = "";

    var if_start_space = starting_space;
    var then_start_space = starting_space;
    var else_start_space = starting_space;
    var insidespace = starting_space + indentation_spaces;
    var inside_space_counter = parseInt(initial_space_counter)+parseInt(indentation_space_length);
    //alert(li_value);
    if (li_value == "IF condition THEN action") {
        $('#selectable .ui-selected').after("<li class='ui-widget-content' id='"+initial_space_counter+"'>"+if_start_space+"IF</li><li class='ui-widget-content' id='"+inside_space_counter+"'>"+insidespace+"Click here to edit condition</li><li class='ui-widget-content' id='"+initial_space_counter+"'>"+then_start_space+"THEN</li><li class='ui-widget-content' id='"+inside_space_counter+"'>"+insidespace+"Click here to edit action</li>");
        $('#selectable .ui-selected').remove();
    } else if (li_value == "IF condition THEN action ELSE action") {
        $('#selectable .ui-selected').after("<li class='ui-widget-content' id='"+initial_space_counter+"'>"+if_start_space+"IF</li><li class='ui-widget-content' id='"+inside_space_counter+"'>"+insidespace+"Click here to edit condition</li><li class='ui-widget-content' id='"+initial_space_counter+"'>"+then_start_space+"THEN</li><li class='ui-widget-content' id='"+inside_space_counter+"'>"+insidespace+"Click here to edit action</li><li class='ui-widget-content' id='"+initial_space_counter+"'>"+else_start_space+"ELSE</li><li class='ui-widget-content' id='"+inside_space_counter+"'>"+insidespace+"Click here to edit action</li>");
        $('#selectable .ui-selected').remove();
    }
    else if (li_value != "")
    {
        //creating an id for this action
        id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        starting_space_anchor_id = "ssaid";
        
        //taking options and optionstype for action
        $("input", $("#action_modal_selected_item .ui-selected")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });            

        //generating code for action            
        var code_array = default_code_generation(parent_node_array,child_node_array);
        div_part = "<div id='code'>";
        for(var i = 0 ; i < code_array.length ; i++){
            div_part = div_part + "<input type='hidden' id ="+id1+" value='"+code_array[i]+"' name='action'/>"
        }
        div_part = div_part + "</div>";
        $('#selectable .ui-selected').html("<a title = start_space_anchor_action style='cursor:pointer;' id=" + starting_space_anchor_id + "> <input id='natural' type='hidden'>" + starting_space +"</input></a><a style='cursor:pointer;' id='"+id1+"'>" + $("#action_modal_selected_item .ui-selected").html() + "</a>"+div_part);            
    }
    else
    {
        //creating an id for this action(updating project variable)
        id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        id3 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        
        var booleanVariableValue = "";
        for(counter = 0 ; counter < project_variable_list.length ; counter++)
        {
            if(project_variable_list[counter].getVariableName() == booleanVariableName)
            {
                booleanVariableValue = project_variable_list[counter].getVariableValue();
            }
        } 

        starting_space_anchor_id = "ssaid";

        div_part = "<div id='code'>";
        div_part = div_part + "<input type='hidden' id ="+id1+" value='"+booleanVariableName+"' name='action'/>";
        div_part = div_part + "<input type='hidden' id ="+id2+" value= ' = ' name='action'/>";
        div_part = div_part + "<input type='hidden' id ="+id3+" value='"+booleanVariableValue+"' name='action'/>";
        
        div_part = div_part + "</div>";
        $('#selectable .ui-selected').html("<a title = start_space_anchor_action_variable style='cursor:pointer;' id=" + starting_space_anchor_id + "> <input id='natural' type='hidden'>" + starting_space +"</input></a><a style='cursor:pointer;' id='"+id1+"'>" +booleanVariablePart+ "</a><a style='cursor:pointer;' id='"+id2+"'> = </a><a style='cursor:pointer;' id='"+id3+"'> "+booleanVariableValue+" </a>"+div_part);
    }
    $('#action_variable_modal').dialog("close");

    left_panel_condition_or_action_selected();
    return true;
}



