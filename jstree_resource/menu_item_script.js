if(typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, '').replace(/[/\u00a0/]+/g,'');
    }
}

function save()
{
    updateClientEndOperationCounter();
    var left_panel_content = $("#selectable").html();
    $.blockUI({
        message: 'Saving Project...',
        theme: false,
        baseZ: 500
    });
    $.ajax({
        type: "POST",
        url: "../../auth/update_project",
        data: {
            project_content: left_panel_content
        },
        success: function (data) {            
            $.unblockUI();
            if(data == null || data == "")
            {
                alert("Your session is expired.");
            }
            else
            {
                alert(data);
            }            
        }
    });
}

function generate_code()
{
    updateClientEndOperationCounter();
    //alert("On Progress.");
    var code = "";
    var is_valid_code = true;
    var error_message = "";
    var temp_text = "";
    var totalSpacesForBracket = new Array();
    var totalSpacesForBracketCounter = -1;
    var spacesForBracket = new Array();
    var spacesForBracketCounter = -1;

    //clearing natural language panel, code panel, parameter table and tree
    $('#changing_stmt').html("");
    $('#code_stmt').html("");
    $('#parameters_table').html("");
    $("li", $("#demo1")).each(function ()
    {
        $(this).hide();
    });
    for(var variable_counter = 0 ; variable_counter < project_variable_list.length ; variable_counter++)
    {
        var variable = project_variable_list[variable_counter];
        if(variable.variable_type == "BOOLEAN")
        {
            code = code +"boolean "+variable.variable_name+" = "+variable.variable_value+";"+getLineBreakSequence();
        }
        else
        {
            code = code +"double "+variable.variable_name+" = "+variable.variable_value+";"+getLineBreakSequence();
        }
    }
    $('#selectable').each(function()
    {
        $("li", $(this)).each(function ()
        {
            //removing the selected item before user selects generate -> code from menu item
            $(this).attr("class",$(this).attr("class").replace(" ui-selected", "")); 
            
            if(!is_valid_code)
                return;

            var total_spaces = $(this).attr("id");
            var counter = 0;
            $("div", $(this)).each(function ()
            {
                 var starting_space = "";
                 for(var i = 0 ; i < total_spaces ; i++){
                    starting_space = starting_space + " ";
                 }

                var is_action = 0;
                //code = code + starting_space;
                $("input", $(this)).each(function ()
                {
                    if(counter == 0 && $(this).attr("name") == "action")
                    {
                        code = code+starting_space;
                        is_action = 1;
                    }
                    code = code + $(this).attr("value");
                    //alert($(this).attr("value"));
                    //code_segment = code_segment + $(this).attr("value")+" ";
                    counter++;
                });
                if(is_action == 1)
                {
                    code = code +";"+getLineBreakSequence();
                }
                
            });
            if(counter == 0)
            {

                if($(this).text().trim() == "Click here to edit block")
                {
                    is_valid_code = false;
                    error_message = "There is undefined item.";
                    //focusing current item from left panel for which there is error while generating code
                    $(this).attr("class",$(this).attr("class")+" ui-selected"); 
                    //alert("text is : "+$(this).text().trim());
                    return;
                }
                else if($(this).text().trim() == "Click here to edit action")
                {
                    is_valid_code = false;
                    error_message = "Action is not defined.";
                    //focusing current item from left panel for which there is error while generating code
                    $(this).attr("class",$(this).attr("class")+" ui-selected");
                    //alert("text is : "+$(this).text().trim());
                    return;
                }
                else if($(this).text().trim() == "Click here to edit condition")
                {
                    is_valid_code = false;
                    error_message = "Condition is not defined.";
                    //focusing current item from left panel for which there is error while generating code
                    $(this).attr("class",$(this).attr("class")+" ui-selected");
                    //alert("text is : "+$(this).text().trim());
                    return;
                }


                if($(this).text().trim().toLowerCase() == "if" )
                {
                    temp_text = $(this).text();
                    temp_text = temp_text.replace($(this).text().trim(), "");
                    while(totalSpacesForBracket[totalSpacesForBracketCounter] >= temp_text.length)
                    {
                        code = code +spacesForBracket[spacesForBracketCounter--]+"}"+getLineBreakSequence();
                        totalSpacesForBracketCounter--;
                    }


                    if($(this).next("li").text().trim() != "(")
                    {
                        code = code + $(this).text().toLowerCase()+" ( ";
                    }
                    else{
                        code = code + $(this).text().toLowerCase()+" ";
                    }
                    
                }
                else if($(this).text().trim() == "(" )
                {
                    code = code + $(this).text().trim();
                }
                else if( $(this).text().trim() == ")")
                {
                    code = code +$(this).text().trim();
                }
                else if( $(this).text().trim() == "}")
                {
                    code = code + getLineBreakSequence()+ $(this).text();
                }
                else if($(this).text().trim() == "THEN" )
                {
                    //if(code.lastIndexOf(")") != code.length-1)
                    //{
                        code = code + " ) "+getLineBreakSequence();
                    //}
                    //else{
                    //    code = code + getLineBreakSequence();
                    //}
                    if($(this).next("li").text().trim() != "{")
                    {
                        temp_text = $(this).text();
                        temp_text = temp_text.replace($(this).text().trim(), "");
                        code = code + temp_text+"{ "+getLineBreakSequence();

                        totalSpacesForBracket[++totalSpacesForBracketCounter] = temp_text.length;
                        spacesForBracket[++spacesForBracketCounter] = temp_text;
                    }
                    //skipping then inside code
                    //code = code + getLineBreakSequence();
                }
                else if($(this).text().trim().toLowerCase() == "else" )
                {
                    temp_text = $(this).text();
                    temp_text = temp_text.replace($(this).text().trim(), "");
                    while(totalSpacesForBracket[totalSpacesForBracketCounter] >= temp_text.length)
                    {
                        code = code +spacesForBracket[spacesForBracketCounter--]+"}"+getLineBreakSequence();
                        totalSpacesForBracketCounter--;
                    }

                    //code = code + getLineBreakSequence()+$(this).text().toLowerCase()+getLineBreakSequence();
                    code = code +$(this).text().toLowerCase()+getLineBreakSequence();
                    if($(this).next("li").text().trim() != "{")
                    {
                        temp_text = $(this).text();
                        temp_text = temp_text.replace($(this).text().trim(), "");
                        code = code + temp_text+"{ "+getLineBreakSequence();

                        totalSpacesForBracket[++totalSpacesForBracketCounter] = temp_text.length;
                        spacesForBracket[++spacesForBracketCounter] = temp_text;
                    }
                }
                else
                {
                    code = code + $(this).text()+getLineBreakSequence();
                }
            }

        });
    });
    //alert(code);
    while(spacesForBracketCounter > -1)
    {
        code = code +spacesForBracket[spacesForBracketCounter--]+"}"+getLineBreakSequence();
        //alert(code);
    }
    //alert("code is : "+code);
    if(is_valid_code){
        $.blockUI({
            message: '',
            theme: false,
            baseZ: 500
        });
        $.ajax({
            type: "POST",
            url: "../../CodeProcess/save_project_code",
            data: {
                code: code
            },
            success: function (ajaxReturnedData) {
                if(ajaxReturnedData == "true")
                {
                    $('#generate_code_div_modal').dialog('open');
                    document.getElementById("generated_code_text_area").value = code;
                }
                else
                {
                    alert("Server processing error. Please try again.");
                }
                $.unblockUI();
            }
        });        
    }
    else
    {
        alert("Invalid expression to generate code."+error_message);
    }

}

function getLineBreakSequence()
{
    return "\n";
}

function add_block()
{
    updateClientEndOperationCounter();
    $("#selectable li:last-child").after("<li class='ui-widget-content' id='0'>Click here to edit block</li>");
}

function add_action()
{
    updateClientEndOperationCounter();
    var selectedItem = $('#selectable .ui-selected').text();
    if(selectedItem.trim() == "THEN" || selectedItem.trim().toLowerCase() == "else")
    {
        var total_spaces = $("#selectable .ui-selected").attr("id");
        var starting_space = "";
        for(var i = 0 ; i < total_spaces ; i++){
           starting_space = starting_space + "&nbsp;";
        }
        starting_space = starting_space + indentation_spaces;
        var inside_space_counter = parseInt(total_spaces)+parseInt(indentation_space_length);
        if($('#selectable .ui-selected').next("li").text().trim() == "{")
        {
            $('#selectable .ui-selected').next("li").after("<li class='ui-widget-content' id='"+inside_space_counter+"'>"+starting_space+"Click here to edit action</li>");
        }
        else
        {
            $('#selectable .ui-selected').after("<li class='ui-widget-content' id='"+inside_space_counter+"'>"+starting_space+"Click here to edit action</li>");
        }
    }
    else
    {
        alert("You are not allowed to add action here.");
    }
}

//user selects if or THEN or else and then selects Add Brackets button
function add_brackets()
{
    updateClientEndOperationCounter();
    var selectedItemText = $('#selectable .ui-selected').text().trim();
    if(selectedItemText.toLowerCase() == "if" || selectedItemText.toLowerCase() == "then" || selectedItemText.toLowerCase() == "else")
    {
        alert("You are not allowed to add bracket here.")
        return;
    }
    if(selectedItemText.trim() == "Click here to edit condition" || selectedItemText.trim() == "Click here to edit action" || selectedItemText.trim() == "Click here to edit block")
    {
        alert("You are not allowed to add bracket here.")
        return;
    }
    $('#selectable li').each(function()
    {
        if($(this).attr("class") == "ui-widget-content ui-selected")
        {
            $("a", $(this)).each(function ()
            {
                if($(this).attr("id") == "ssaid" && ($(this).attr("title") == "start_space_anchor_action" || $(this).attr("title") == "start_space_anchor_action_variable"))
                {
                    alert("You are not allowed to add bracket here.")
                    return;
                }
            });
        }
    });
    
    //user selects a condition from left panel and wants to add bracket
    $("div", $("#selectable .ui-selected")).each(function () {
        $("input", $(this)).each(function () {
            if($(this).attr("name") == "condition")
            {
                //opening logical connector div modal window
                $('#add_bracket_in_condition_div').dialog('open');
                var selected_anchor_list = "";
                $("a", $('#selectable .ui-selected')).each(function ()
                {
                    //we are not showing first anchor which contains starting spaces
                    if($(this).attr("id") != "ssaid"){
                        selected_anchor_list = selected_anchor_list+"<li class='ui-widget-content'>";
                        selected_anchor_list = selected_anchor_list+$(this).prop('outerHTML');
                        selected_anchor_list = selected_anchor_list + "</li>";
                    }
                });
                document.getElementById("add_bracket_in_condition_div_selected_items").innerHTML = selected_anchor_list;
                return;
            }
        });
    });
    //adding bracket for condition ends
    
    //text content of currently selected item from left panel
    var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    
    var bracketStart;
    var bracketEnd;
    if(selectedItemText.toLowerCase() == "if")
    {
        bracketStart = "(";
        bracketEnd = ")";
    }
    if(selectedItemText == "THEN" || selectedItemText.toLowerCase() == "else")
    {
        bracketStart = "{";
        bracketEnd = "}";
    }

    //counting total number of leading spaces of this selected item
    var total_spaces = $('#selectable .ui-selected').attr("id");
    var starting_space = "";
    for(var i = 0 ; i < total_spaces ; i++){
       starting_space = starting_space + "&nbsp;";
    }

    if(selectedItemText.toLowerCase() == "if")
    {
        var currentItem = $('#selectable .ui-selected');
        //checking whether bracket already exists or not
        if(currentItem.next("li").text().trim() == "(")
        {
            alert("Breaket already exists.")
        }
        else
        {
            //adding starting bracket
            currentItem.after("<li class='ui-widget-content' id = '"+total_spaces+"' title='"+id1+"-"+id2+"-startbracket' >"+starting_space+bracketStart+"</li>");
            while(currentItem.text().trim() != "THEN")
            {
                currentItem = currentItem.next("li");
            }
            //adding ending bracket
            currentItem.before("<li class='ui-widget-content' id = '"+total_spaces+"' title='"+id1+"-"+id2+"-endbracket' >"+starting_space+bracketEnd+"</li>");
        }
    }
    if(selectedItemText == "THEN" || selectedItemText.toLowerCase() == "else")
    {
        //checking whether bracket already exists or not
        if($('#selectable .ui-selected').next("li").text().trim() == "{")
        {
            alert("Breaket already exists.")
        }
        else
        {
            //adding starting bracket
            $('#selectable .ui-selected').after("<li class='ui-widget-content' id='"+total_spaces+"'>"+starting_space+bracketStart+"</li>");
            var selected_expression_starting_spaces = 0;
            var selected_expression_index = -1;
            var list_counter = 1;
            var closing_breaket_added = 0;
            //searching ending bracket position
            $('#selectable').each(function()
            {
                $("li", $(this)).each(function ()
                {
                    if ($(this).attr("class") == "ui-widget-content ui-selected")
                    {
                        selected_expression_index = list_counter;
                        selected_expression_starting_spaces = $(this).attr("id");                        
                    }
                    else if(list_counter > selected_expression_index+1 && selected_expression_index > -1)
                    {
                        var current_expression_spaces = $(this).attr("id");
                        if(current_expression_spaces <= selected_expression_starting_spaces)
                        {
                            //adding ending bracket
                            $(this).before("<li class='ui-widget-content' id='"+total_spaces+"'>"+starting_space+bracketEnd+"</li>");
                            //making sure that ending bracket is added
                            closing_breaket_added = 1;
                            return false;
                        }
                    }
                    list_counter++;
                });
            });
            //ending bracket will be added at the end
            if(closing_breaket_added == 0)
            {
                $("#selectable li:last-child").after("<li class='ui-widget-content' id='"+total_spaces+"'>"+starting_space+bracketEnd+"</li>");
            }
        }
    }
}

/*
 * User presses delete from menu item
 **/
function delete_block()
{
    updateClientEndOperationCounter();
    //validation checking before deletion
    if($('#selectable .ui-selected').text().length == 0)
    {
        alert("Please select an item to delete.");
        return;
    }
    else if($('#selectable .ui-selected').text().trim() == "Click here to edit condition")
    {
        alert("You are not allowed to remove empty condition.");
        return;
    }
    else if($('#selectable .ui-selected').text().trim() == "Click here to edit block")
    {
        alert("You are not allowed to remove an empty block.");
        return;
    }
    //user wants to remove bracket
    else if($('#selectable .ui-selected').text().trim() == ")" || $('#selectable .ui-selected').text().trim() == "}" || $('#selectable .ui-selected').text().trim() == "(" || $('#selectable .ui-selected').text().trim() == "{")
    {
        alert("Please select delete option from Bracket menu item to delete this selected item.");
        return;
    }
    
    else if($('#selectable .ui-selected').text().trim() == "THEN")
    {
        alert("You are not allowed to remove THEN expression.");
        return;
    }
    
    var is_condition_selected = false;
    var is_action_selected = false;
    var current_selected_segment = "";
    $('#selectable').each(function()
     {
        $("li", $(this)).each(function ()
        {
            if($(this).text().trim().toLowerCase() == "if")
            {
                current_selected_segment = "if";                
            }
            else if($(this).text().trim().toLowerCase() == "else")
            {
                current_selected_segment = "else";
            }
            else if($(this).text().trim() == "THEN" )
            {
                current_selected_segment = "THEN";
            }
            else
            {
                //user selects a condition to delete
                if($(this).attr("class") == "ui-widget-content ui-selected" && current_selected_segment.toLowerCase() == "if" )
                {
                    is_condition_selected = true;
                }
                //user selects an action to delete
                if($(this).attr("class") == "ui-widget-content ui-selected" && (current_selected_segment.toLowerCase() == "then" || current_selected_segment.toLowerCase() == "else") )
                {
                    is_action_selected = true;
                }
                
            }
        });
    });
    //generating custom message for user in delete confirmation dialog
    if(is_condition_selected == true)
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete this condition?";
    }
    else if(is_action_selected == true)
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete this action?";
    }
    else if($('#selectable .ui-selected').text().trim().toLowerCase() == "if")
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "You are about to delete a block. Are you sure?";
    }
    else if($('#selectable .ui-selected').text().trim().toLowerCase() == "else")
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete the \"else\"?";
    }
    else
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete this?";
    }
    //user confirmation dialog
    $('#delete_block_confirmation_div_modal').dialog('open');
}

function delete_item()
{
    updateClientEndOperationCounter();
    var start_if_total_spaces = -1;
    var start_else_total_spaces = -1;
    var end_flag = 0;
    var delete_start_marker = -1;
    var delete_end_marker = -1;

    var is_allowed_delete = 1;

    var current_selected_segment = "";

    var then_else_removal_index = -1;
    var then_else_removal_total_spaces = -1;

    var start = 1;
    
     $('#selectable').each(function()
     {
        $("li", $(this)).each(function ()
        {
            if($(this).text().trim().toLowerCase() == "if")
            {
                current_selected_segment = "if";
                if ($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    start_if_total_spaces = $(this).attr("id");
                    if(start > 1 && start_if_total_spaces > 0)
                    {
                         var starting_space = "";
                         for(var i = 0 ; i < start_if_total_spaces ; i++){
                            starting_space = starting_space + "&nbsp;";
                         }
                         $(this).before("<li class='ui-widget-content' id='"+start_if_total_spaces+"'>"+starting_space+"Click here to edit action</li>");
                         start++;
                    }
                    delete_start_marker = start;
                    //alert(start);

                }
                else
                {
                    if(end_flag == 0)
                    {
                       var total_spaces = $(this).attr("id");
                        if(total_spaces < start_if_total_spaces || (total_spaces == start_if_total_spaces && $(this).text().trim().toLowerCase() == "if"))
                        {
                            delete_end_marker = start - 1;
                            //alert(start - 1);
                            end_flag = 1;
                        }
                    }
                }
            }
            else if($(this).text().trim().toLowerCase() == "else")
            {
                current_selected_segment = "else";
                if ($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    start_else_total_spaces = $(this).attr("id");
                    delete_start_marker = start;
                    //alert(start);

                }
                else
                {
                    if(end_flag == 0)
                    {
                       var total_spaces = $(this).attr("id");
                        if(total_spaces <= start_else_total_spaces)
                        {
                            delete_end_marker = start - 1;
                            //alert(start - 1);
                            end_flag = 1;
                        }
                    }
                }
            }
            else if($(this).text().trim() == "THEN" )
            {
                current_selected_segment = "THEN";
            }
            else
            {
                //user selects a condition to delete
                if($(this).attr("class") == "ui-widget-content ui-selected" && current_selected_segment.toLowerCase() == "if" )
                {
                    //selected condition is yet assigned.
                    if($(this).text().trim() == "Click here to edit condition")
                    {
                        alert("You are not allowed to remove an empty condition");
                    }
                    else
                    {
                       //retrieving total spaces before the condition                       
                       var total_initial_spaces = $(this).attr("id");
                       
                        var initial_spaces = "";
                        for(i = 0 ; i < total_initial_spaces ; i++){
                            initial_spaces = initial_spaces + "&nbsp;";
                        }
                        //appending an empty condition
                        $(this).before("<li class='ui-widget-content' id='"+total_initial_spaces+"'>"+initial_spaces+"Click here to edit condition</li>");
                        //removing current selected condition
                        $(this).remove();
                        alert("Your selected condition is successfully removed.");
                        //clearing natural language panel, code panel, parameter table and tree
                        $('#changing_stmt').html("");
                        $('#code_stmt').html("");
                        $('#parameters_table').html("");
                        $("li", $("#demo1")).each(function ()
                        {
                            $(this).hide();
                        });
                    }                    
                    
                    is_allowed_delete = 0;
                    return false;
                }
                if($(this).attr("class") == "ui-widget-content ui-selected" && (current_selected_segment.toLowerCase() == "then" || current_selected_segment.toLowerCase() == "else") )
                {
                    //selected condition is empty.
                    if($(this).text().trim() == "Click here to edit action")
                    {
                        alert("You are not allowed to remove an empty action");
                    }
                    else
                    {
                       var total_initial_spaces = $("#selectable .ui-selected").attr("id");
                       var initial_spaces = "";
                        for(i = 0 ; i < total_initial_spaces ; i++){
                            initial_spaces = initial_spaces + "&nbsp;";
                        }
                        var next_item_exists = false;
                        var previous_item_exists = false;
                        if($(this).attr("id") == $(this).prev("li").attr("id"))
                        {
                            previous_item_exists = true;
                        }
                        if($(this).next("li") != null && $(this).attr("id") == $(this).next("li").attr("id"))
                        {
                            next_item_exists = true;
                        }
                        if(!next_item_exists && !previous_item_exists)
                        {
                            //appending an empty condition
                            $(this).before("<li class='ui-widget-content' id='"+total_initial_spaces+"'>"+initial_spaces+"Click here to edit action</li>");
                        
                        }
                        //removing current selected condition
                        $(this).remove();
                        alert("Your selected action is successfully removed.");
                        //clearing natural language panel, code panel, parameter table and tree
                        $('#changing_stmt').html("");
                        $('#code_stmt').html("");
                        $('#parameters_table').html("");
                        $("li", $("#demo1")).each(function ()
                        {
                            $(this).hide();
                        });
                    }                    
                    
                    is_allowed_delete = 0;
                    return false;
                }
                else if($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    then_else_removal_total_spaces = $(this).attr("id");
                    then_else_removal_index = start;
                    end_flag = 1;
                    delete_start_marker = start;
                    delete_end_marker = start;
                    return false;
                }
            }
            start++;
        });
    });

    //alert("delete_start_marker"+delete_start_marker+";delete_end_marker"+delete_end_marker);

    if(then_else_removal_index > -1)
    {
        var parent_node_index = -1;
        var node_counter = 1;
        $('#selectable').each(function()
         {
            $("li", $(this)).each(function ()
            {
                if( $(this).text().trim() != "{" || $(this).text().trim() != "}" || $(this).text().trim() != "(" || $(this).text().trim() != ")" )
                {
                    var total_spaces = $(this).attr("id");
                    if(total_spaces < then_else_removal_total_spaces && node_counter < then_else_removal_index)
                    {
                        parent_node_index = node_counter;
                    }
                }
                node_counter++;
            });
         });
         //alert("parent node index: "+parent_node_index+ " and then_else_removal_index:"+then_else_removal_index);
         node_counter = 1;
         var total_nodes = 0;
         var flag = 1;
         $('#selectable').each(function()
         {
            $("li", $(this)).each(function ()
            {
                if( $(this).text().trim() != "{" || $(this).text().trim() != "}" || $(this).text().trim() != "(" || $(this).text().trim() != ")" )
                {
                    if(node_counter > parent_node_index && flag == 1)
                    {
                        var node_child_total_spaces = $(this).attr("id");
                        //alert("node:"+$(this).text());
                        //alert("child space:"+node_child_total_spaces+" and parent space : "+then_else_removal_total_spaces);
                        if(node_child_total_spaces == then_else_removal_total_spaces){
                            total_nodes++;
                        }
                        else if(node_child_total_spaces <= then_else_removal_total_spaces)
                        {
                            flag = 0;
                        }
                    }
                }
                node_counter++;
            });
         });
         //alert("total nodes:"+total_nodes);
    }

    if(total_nodes == 1)
    {
        $('#selectable').each(function()
        {
            $("li", $(this)).each(function ()
            {
                if ($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    $(this).remove();        
                }
            });
        });
        alert("Your selected action is successfully removed.");
        return;
        
        //alert("You are not allowed to remove expression");
        //is_allowed_delete = 0;
        //return false;
    }

    //alert("delete_start_marker:"+delete_start_marker);
    //alert("delete_end_marker:"+delete_end_marker);

    if(is_allowed_delete == 1)
    {
        if(end_flag == 0)
        {
            delete_end_marker = start - 1;
            //alert(start - 1);
        }

        //since we have removed whole expression on the left panel we have to add defaut expreesion
        if(delete_start_marker == 1 && delete_end_marker == start-1)
        {
            $("#selectable li:last-child").after("<li class='ui-widget-content' id='0'>Click here to edit block</li>");
        }


        var delete_marker = 1;
        $('#selectable').each(function()
         {
            $("li", $(this)).each(function ()
            {
                if(delete_marker >= delete_start_marker && delete_marker <= delete_end_marker)
                {
                    $(this).remove();
                }
                delete_marker++;
            });
         });
    }
    return false;
}

/*
 * User selects menu item to save project left panel content
 **/
function download_project()
{
    updateClientEndOperationCounter();
    //project left panel content
    var left_panel_content = $("#selectable").html();
    $.blockUI({
        message: '',
        theme: false,
        baseZ: 500
    });
    //saving project left panel into server
    $.ajax({
        type: "POST",
        url: "../../project/save_project_left_panel_and_variables",
        data: {
            code: left_panel_content
        },
        success: function (ajaxReturnedData) 
        {
            if(ajaxReturnedData == "true")
            {
                $('#download_project_div_modal').dialog('open');
                document.getElementById("project_content_file_name").value = "";
                //window.open("../../../project/1.txt");
            }
            else
            {
                alert("Server processing error. Please try again.");
            }
            $.unblockUI();
        }
    }); 
}

function upload_project()
{
    $('#upload_project_div').dialog('open');
}
function button_yes_clicked_upload_project()
{
    $('#upload_project_project_left_panel_content').val($("#selectable").html());
    return true;
}
function button_no_clicked_upload_project()
{
    $('#upload_project_project_left_panel_content').val("");
    return true;
}

/*
 * User wants to add a new variable from menu item 
 **/
function add_variables()
{
    updateClientEndOperationCounter();
    //resetting fields and display relevant fields
    document.getElementById('add_variable_name').value = "";
    document.getElementById("add_variable_type_selection_combo").selectedIndex = 0;
    
    document.getElementById("add_variable_value_selection_combo").selectedIndex = 0;
    document.getElementById("add_variable_value_selection_combo").style.visibility='visible';
        
    document.getElementById('add_variable_value_label').style.visibility='hidden';
    document.getElementById('add_variable_value_text').style.visibility='hidden';
    $('#add_variables_div').dialog('open');
    
}

/*
 * User wants to delete bracket from left panel or (condition/action in) natural language Panel
 **/
function delete_bracket()
{
    updateClientEndOperationCounter();
    //selected bracket to be removed is from (condition/action in) natural language Panel starts
    var start_bracket_id = "";
    var end_bracket_id = "";
    $("a", $('#changing_stmt')).each(function ()
    {
        if ($(this).attr("class") == "selected_expression" && ($(this).attr("title").indexOf("startbracket") >= 0 || $(this).attr("title").indexOf("endbracket") >= 0))
        {
            start_bracket_id = $(this).attr("title").substring(0,4);
            end_bracket_id = $(this).attr("title").substring(5,9);            
            return;
        }
    });
    if(start_bracket_id != "" && end_bracket_id != "")
    {
        //removing bracket from four panels
        $("a", $("#changing_stmt")).each(function () 
        {
            if ($(this).attr("id") == start_bracket_id) 
            {
                $(this).remove();            
            }
            else if ($(this).attr("id") == end_bracket_id) 
            {
                $(this).remove(); 
            }
        });
        $("a", $("#code_stmt")).each(function () 
        {
            if ($(this).attr("id") == start_bracket_id) 
            {
                $(this).remove();            
            }
            else if ($(this).attr("id") == end_bracket_id) 
            {
                $(this).remove(); 
            }

        });
        $("a", $("#selectable .ui-selected")).each(function ()
        {
            if ($(this).attr("id") == start_bracket_id) 
            {
                $(this).remove();            
            }
            else if ($(this).attr("id") == end_bracket_id) 
            {
                $(this).remove(); 
            }
        });
        $("div", $("#selectable .ui-selected")).each(function ()
        {
            $("input", $(this)).each(function ()
            {
                if ($(this).attr("id") == start_bracket_id) 
                {
                    $(this).remove();            
                }
                else if ($(this).attr("id") == end_bracket_id) 
                {
                    $(this).remove(); 
                } 
            });

        });
        alert("Your selected bracket is removed.");
        return;
    }
    //selected bracket to be removed is from (condition/action in) natural language Panel ends
    
    
    //user wants to remove ending bracket from left panel
    if($('#selectable .ui-selected').text().trim() == ")" || $('#selectable .ui-selected').text().trim() == "}")
    {
        alert("Please select starting breaket to remove.");
        return;
    }
    //user wants to remove starting bracket from left panel
    if($('#selectable .ui-selected').text().trim() == "(" || $('#selectable .ui-selected').text().trim() == "{")
    {
        //total number of list items on left panel
        var total_list_items = $("#selectable > li").size();
        var end_breaket = "";
        if($('#selectable .ui-selected').text().trim() == "(")
        {
            end_breaket = ")";
        }
        else if($('#selectable .ui-selected').text().trim() == "{")
        {
            end_breaket = "}";
        }

        var selected_expression_starting_spaces = $('#selectable .ui-selected').text().length - $('#selectable .ui-selected').text().trim().length;
        var currentItem = $('#selectable .ui-selected');
        var next_item_starting_space = 0;
        var list_item_counter = 1;
        while(true)
        {
            currentItem = currentItem.next("li");
            next_item_starting_space = currentItem.text().length - currentItem.text().trim().length;
            if(currentItem.text().trim() == end_breaket && next_item_starting_space == selected_expression_starting_spaces)
            {
                $('#selectable .ui-selected').remove();
                currentItem.remove();
                alert("Your selected bracket is removed.");
                return;
            }
            if(list_item_counter++ > total_list_items)
            {
                return;
            }
        }
    }
    alert("Please select a bracket to delete.");
    return;
}

function pre_load_project()
{
    updateClientEndOperationCounter();
    $('#load_projects_confirmation_window_div_modal').dialog('open');
}
function button_pre_load_project_ok_pressed()
{
    updateClientEndOperationCounter();
    document.getElementById('pre_load_project_left_panel_content').value = $("#selectable").html();        
    return true;
}

function save_as()
{
    updateClientEndOperationCounter();
    document.getElementById("save_as_project_project_name").value = "";
    document.getElementById("save_as_project_left_panel_content").value = $("#selectable").html();
    $('#save_as_project_div_modal').dialog('open');
}

function save_as_project_save_button_clicked()
{
    updateClientEndOperationCounter();
    var counter = 0 ;
    var save_as_project_name = document.getElementById("save_as_project_project_name").value;
    
    var projectNameRegExp = /^[a-z0-9]+$/i;
    if(projectNameRegExp.test(save_as_project_name)==false)
    {
        alert( "Please enter a valid project name." );
        return false;
    }
    
    for(counter = 0 ; counter < project_name_list.length ; counter++)
    {
        if(project_name_list[counter] == save_as_project_name)
        {
            $('#save_as_project_div_modal').dialog('close');
            $('#save_as_replace_project_div_modal').dialog('open');
            document.getElementById("save_as_replace_project_name").value = project_name_list[counter];
            document.getElementById("save_as_replace_project_id").value = project_id_list[counter];
            return false;
        }
    }
    return true;
}

function save_as_replace_project_yes_button_clicked()
{
    updateClientEndOperationCounter();
    document.getElementById("save_as_replace_project_left_panel_content").value = $("#selectable").html();
    return true;
}

function save_as_replace_project_no_button_clicked()
{
    updateClientEndOperationCounter();
    $('#save_as_replace_project_div_modal').dialog('close');
    return false;
}

function show_variables()
{
    updateClientEndOperationCounter();
    $('#project_variable_list_div').dialog('open');
}
