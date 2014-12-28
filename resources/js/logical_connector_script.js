//user wants to add logical connector operator by selecting if from left panel
function add_logical_operators()
{
    updateClientEndOperationCounter();
    //to add logical connector operator user has to select if from left panel
    if($('#selectable .ui-selected').text().trim().toLowerCase() != "if")
    {
        $("#label_show_messages_content").html("Please select if from left panel to add logical operator.");
        $("#modal_show_messages").modal('show');
        //alert("Please select if from left panel to add logical operator.");
        return;
    }
    //user selects if from left panel and that item is selected item
    var selectedItem = $('#selectable .ui-selected');
    //currentItem is actually the condition. There can be multiple conditions in one li connected by logial operator AND / OR
    var currentItem = selectedItem.next("li");
    //if there is a bracket after if then we are moving to next item which is a condition
    if(currentItem.text().trim() == "(")
    {
        currentItem = currentItem.next("li");
    }
    //user didn't add proper condition
    if(currentItem.text().trim() == "Click here to edit condition")
    {
        $("#label_show_messages_content").html("Please edit your incomplete condition first.");
        $("#modal_show_messages").modal('show');
        //alert("Please edit your incomplete condition first.");
        return;
    }
    //next item of if on left panel is assumed to be whole condition in one li
    if(currentItem.text().trim() == "" || currentItem.text().trim() == "(" || currentItem.text().trim() == ")" || currentItem.text().trim() == "{" || currentItem.text().trim() == "}")
    {
        $("#label_show_messages_content").html("Incorrect condition.");
        $("#modal_show_messages").modal('show');
        //alert("Incorrect condition.");
        return;
    }
    var logical_connector_list_items = "";
    var add_condition_part = "false";
    var start_id = "";
    $("a", currentItem).each(function ()
    {
        var anchor_id = $(this).attr("id");
        var anchor_title = $(this).attr("title");
        
        if($(this).attr("title") != null)
        {
            //boolean variable as condition
            if($(this).attr("title").indexOf("logicalconnectorbooleanvariable") >= 0)
            {
                logical_connector_list_items = logical_connector_list_items+"<li class='ui-widget-content'>"+$(this).prop('outerHTML')+ "</li>";
            }
        }        
        
        //start of one condition
        if(anchor_title == (anchor_id.toString()+"start"))
        {
            start_id = anchor_id;
            add_condition_part = "true";
            logical_connector_list_items = logical_connector_list_items+"<li class='ui-widget-content'>";
        }
        //end of one condition
        else if(anchor_title == (start_id+"end"))
        {
            logical_connector_list_items = logical_connector_list_items+$(this).prop('outerHTML');
            logical_connector_list_items = logical_connector_list_items + "</li>";
            add_condition_part = "false";
            
        }
        //taking anchor inside of one condition
        if(add_condition_part == "true")
        {
            logical_connector_list_items = logical_connector_list_items+$(this).prop('outerHTML');
        }
    });
    //opening logical connector div modal window
    $('#logical_connector_div').dialog('open');
    //adding condition list in modal window
    document.getElementById("logical_connector_selected_item").innerHTML = logical_connector_list_items;    
}

function delete_logical_operator()
{
    updateClientEndOperationCounter();
    var error_message = "";
    var debugging_message = "";
    var selected_logical_operator_id = "";
    var selected_logical_operator_title = "";
    $("a", $('#changing_stmt')).each(function ()
    {
        if ($(this).attr("class") == "selected_expression" && ($(this).attr("title").indexOf("logical_connector_and") >= 0 || $(this).attr("title").indexOf("logical_connector_or") >= 0))
        {
            selected_logical_operator_id = $(this).attr("id");  
            selected_logical_operator_title = $(this).attr("title");  
        }
    });
    //alert("You are about to delete logical operator. Id:"+selected_logical_operator_id+" and title:"+selected_logical_operator_title);
    if(selected_logical_operator_id == "")
    {
        $("#label_show_messages_content").html("Please select logical connector operator from condition to delete.");
        $("#modal_show_messages").modal('show');
        //alert("Please select logical connector operator from condition to delete.");
        return;
    }
    //selected item from left panel
    var currentItem = $('#selectable .ui-selected');
    var first_condition = "";
    var second_condition = "";
    var left_condition = "";
    var add_condition_part = "false";
    var start_id = "";
    var previous_title = "";
    var remove_logical_operator_valid = "true";
    var check_left_condition = "true";
    var check_right_condition = "false";
    var first_condition_complete_second_condition_start = "false";
    var selected_logical_connector_operator_name = "";
    $("a", currentItem).each(function ()
    {
        var anchor_id = $(this).attr("id");
        var anchor_title = $(this).attr("title");
        if(first_condition_complete_second_condition_start == "true")
        {
            debugging_message += "first condition completes and second condition starts.";
            first_condition_complete_second_condition_start = "false";
            if(anchor_title.indexOf("startbracket") >= 0)
            {
                debugging_message += "second condition starts with starting bracket.";
                remove_logical_operator_valid = "false";
                check_left_condition = "false";
                check_right_condition = "false";
                error_message = "Can't remove"+selected_logical_connector_operator_name+" . Remove the expression  at the right side of"+selected_logical_connector_operator_name;
                return;
            }
        }
        if(remove_logical_operator_valid == "false")
        {
            return;
        }
        if(anchor_title == "logical_connector_and" || anchor_title == "logical_connector_or")
        {
            if(anchor_title == "logical_connector_and")
            {
                selected_logical_connector_operator_name = " AND ";
            }
            else if (anchor_title == "logical_connector_or")
            {
                selected_logical_connector_operator_name = " OR ";
            }
            debugging_message += "identified a logical operator with title "+anchor_title+".";
            if(anchor_id == selected_logical_operator_id)
            {
                debugging_message += "anchor id is same as selected operator anchor id.";
                if(previous_title.indexOf("endbracket") >= 0)
                {
                    debugging_message += "first conditions ends with bracket.";
                    remove_logical_operator_valid = "false";
                    error_message = "Can't remove"+selected_logical_connector_operator_name+" . Remove the expression  at the left side of"+selected_logical_connector_operator_name;
                    //return;
                }
                //storing selected logical connector anchor id
                document.getElementById('logical_connector_removing_condition_selected_operator_anchor_id').value = anchor_id;
                
                first_condition = left_condition;
                check_left_condition = "false";
                check_right_condition = "true";
                add_condition_part = "false";
                first_condition_complete_second_condition_start = "true";
            }
            else
            {
                debugging_message += "anchor id is different as selected operator anchor id.";
                left_condition = "";
                check_left_condition = "true";
                check_right_condition = "false";
                add_condition_part = "false";
            }
        }
        if(check_left_condition == "true")
        {
            if($(this).attr("title") != null)
            {
                //boolean variable as left part of a logical connector operator
                if($(this).attr("title").indexOf("logicalconnectorbooleanvariable") >= 0)
                {
                    left_condition = "<li class='ui-widget-content'>"+$(this).prop('outerHTML')+ "</li>";                
                }
            }            
            //start of one condition
            if(anchor_title == (anchor_id.toString()+"start"))
            {
                debugging_message += "start of first condition.";
                left_condition = "";
                start_id = anchor_id;
                add_condition_part = "true";
                left_condition = left_condition+"<li class='ui-widget-content'>";
            }
            //end of one condition
            else if(anchor_title == (start_id+"end"))
            {
                debugging_message += "end of first condition.";
                left_condition = left_condition+$(this).prop('outerHTML');
                left_condition = left_condition + "</li>";
                add_condition_part = "false";

            }
            //taking anchor inside of one condition
            if(add_condition_part == "true")
            {
                debugging_message += "inside of first condition.";
                left_condition = left_condition+$(this).prop('outerHTML');
            }
        }
        if(check_right_condition == "true")
        {
            if($(this).attr("title") != null)
            {
                //boolean variable as right part of a logical connector operator
                if($(this).attr("title").indexOf("logicalconnectorbooleanvariable") >= 0)
                {
                    second_condition = "<li class='ui-widget-content'>"+$(this).prop('outerHTML')+ "</li>";
                    return;
                }
            }            
            
            //start of one condition
            if(anchor_title == (anchor_id.toString()+"start"))
            {
                debugging_message += "start of second condition.";
                second_condition = "";
                start_id = anchor_id;
                add_condition_part = "true";
                second_condition = second_condition+"<li class='ui-widget-content'>";
            }
            //end of one condition
            else if(anchor_title == (start_id+"end"))
            {
                debugging_message += "end of second condition.";
                second_condition = second_condition+$(this).prop('outerHTML');
                second_condition = second_condition + "</li>";
                add_condition_part = "false";
                return;
            }
            //taking anchor inside of one condition
            if(add_condition_part == "true")
            {
                debugging_message += "inside of second condition.";
                second_condition = second_condition+$(this).prop('outerHTML');
            }
        }
        
        previous_title = $(this).attr("title");
    });
    //alert("first condition:"+first_condition);
    //alert("second condition:"+second_condition);
    //alert("debugging message:"+debugging_message);
    if(remove_logical_operator_valid == "false")
    {
        $("#label_show_messages_content").html(error_message);
        $("#modal_show_messages").modal('show');
        //alert(error_message);
        return;
    }
    
    //blocking current window before rendering modal window to select logical connector operator
    $.blockUI({
        message: '',
        theme: false,
        baseZ: 500
    });
    //opening logical connector div modal window
    $('#logical_connector_removing_condition_div').dialog('open');
    //adding condition list in modal window
    document.getElementById("logical_connector_removing_condition_selected_item").innerHTML = first_condition+second_condition; 
}

function button_logical_connector_removing_condition_delete_pressed()
{
    updateClientEndOperationCounter();
    var selected_logical_connector_anchor_id = document.getElementById('logical_connector_removing_condition_selected_operator_anchor_id').value;
    //alert(selected_logical_connector_anchor_id);
    //selected condtion from condition list
    var currentItem = $('#logical_connector_removing_condition_selected_item .ui-selected');
    //user presses delete button without selecting a condition from condition list
    if(currentItem.text() == "")
    {
        $("#label_show_messages_content").html("Please select an item.");
        $("#modal_show_messages").modal('show');
        //alert("Please select an item.");
        return;
    }
    var start_id = "";
    var start_anchor_id = "";
    var end_anchor_id = "";
    $("a", currentItem).each(function ()
    {
        var anchor_id = $(this).attr("id");
        var anchor_title = $(this).attr("title");
        if($(this).attr("title") != null)
        {
            //boolean variable as condition
            if($(this).attr("title").indexOf("logicalconnectorbooleanvariable") >= 0)
            {
                start_anchor_id = anchor_id;
                end_anchor_id = anchor_id;
            }
        }       
        
        if(anchor_title == (anchor_id.toString()+"start"))
        {
            start_id = anchor_id;
            start_anchor_id = anchor_id;
        }
        if(anchor_title == (start_id.toString()+"end") )
        {
             end_anchor_id = anchor_id;
        }
    });
    var anchor_delete_flag = "false";
    if(start_anchor_id != "" && end_anchor_id != "" && selected_logical_connector_anchor_id != "")
    {
        
        $("a", $("#changing_stmt")).each(function () 
        {
            //removing selected logical connector id
            if ($(this).attr("id") == selected_logical_connector_anchor_id) 
            {
                $(this).remove(); 
            }
            //setting the flag to start deleting condition
            if ($(this).attr("id") == start_anchor_id) 
            {
                anchor_delete_flag = "true";            
            }
            //removing anchor of selected condition
            if(anchor_delete_flag == "true")
            {
                $(this).remove();
            }
            //since deletion is completed, so we need to reset the flag
            if ($(this).attr("id") == end_anchor_id) 
            {
                anchor_delete_flag = "false";  
            }
        });
        
        $("a", $("#selectable .ui-selected")).each(function ()
        {
            //removing selected logical connector id
            if ($(this).attr("id") == selected_logical_connector_anchor_id) 
            {
                $(this).remove(); 
            }
            //setting the flag to start deleting condition
            if ($(this).attr("id") == start_anchor_id) 
            {
                anchor_delete_flag = "true";            
            }
            //removing anchor of selected condition
            if(anchor_delete_flag == "true")
            {
                $(this).remove();
            }
            //since deletion is completed, so we need to reset the flag
            if ($(this).attr("id") == end_anchor_id) 
            {
                anchor_delete_flag = "false";  
            }
        });
        $("div", $("#selectable .ui-selected")).each(function ()
        {
            $("input", $(this)).each(function ()
            {
                //removing selected logical connector id
                if ($(this).attr("id") == selected_logical_connector_anchor_id) 
                {
                    $(this).remove(); 
                }
                //setting the flag to start deleting condition
                if ($(this).attr("id") == start_anchor_id) 
                {
                    anchor_delete_flag = "true";            
                }
                //removing anchor of selected condition
                if(anchor_delete_flag == "true")
                {
                    $(this).remove();
                }
                //since deletion is completed, so we need to reset the flag
                if ($(this).attr("id") == end_anchor_id) 
                {
                    anchor_delete_flag = "false";  
                }
            });

        });
        
        //updating code panel
        generate_selected_item_code();  
    }
    //closing logical connector removing condition modal window
    $('#logical_connector_removing_condition_div').dialog('close');
    //unblocking the user interface
    //$.unblockUI();
    $("#label_show_messages_content").html("Successfully deleted.");
    $("#modal_show_messages").modal('show');
    //alert("Successfully deleted.");
}

//user presses ok button in logical connector modal window
function button_logical_connector_ok_pressed()
{
    updateClientEndOperationCounter();
    var logicalConnectorItemTypeIndex = document.getElementById('logicalConnectorItemType').selectedIndex;
    
    //selected item(AND/OR) index is stored
    if(logicalConnectorItemTypeIndex == 0)
    {
        document.getElementById('logical_connector_selected_index').value = document.getElementById('logicalConnectorSelectionCombo').selectedIndex;    
    }
    else if(logicalConnectorItemTypeIndex == 1)
    {
        document.getElementById('logical_connector_boolean_variable_selected_index').value = document.getElementById('logicalConnectorSelectionCombo').selectedIndex;    
    }
    //selected condtion from condition list
    var currentItem = $('#logical_connector_selected_item .ui-selected');
    //user presses ok button without selecting a condition from condition list
    if(currentItem.text() == "")
    {
        $("#label_show_messages_content").html("Please select an item.");
        $("#modal_show_messages").modal('show');
        //alert("Please select an item.");
        return;
    }
    var start_id = "";
    var last_anchor_id = "";
    var last_anchor_title = "";
    $("a", currentItem).each(function ()
    {
        var anchor_id = $(this).attr("id");
        var anchor_title = $(this).attr("title");
        
        if($(this).attr("title") != null)
        {
            //boolean variable as condition
            if($(this).attr("title").indexOf("logicalconnectorbooleanvariable") >= 0)
            {
                last_anchor_id = anchor_id;
                last_anchor_title = anchor_title;            
            }
        }        
        
        if(anchor_title == (anchor_id.toString()+"start"))
        {
            start_id = anchor_id;
        }
        if(anchor_title == (start_id.toString()+"end") )
        {
            last_anchor_id = anchor_id;
            last_anchor_title = start_id.toString()+"end"; 
        }        
    });
    //storing anchor id and titile of selected condition
    if(logicalConnectorItemTypeIndex == 0)
    {
        document.getElementById('logical_connector_selected_anchor_id').value = last_anchor_id;
        document.getElementById('logical_connector_selected_anchor_title').value = last_anchor_title;
    }
    else if(logicalConnectorItemTypeIndex == 1)
    {
        document.getElementById('logical_connector_boolean_variable_selected_anchor_id').value = last_anchor_id;
        document.getElementById('logical_connector_boolean_variable_selected_anchor_title').value = last_anchor_title;
    }
    //closing logical connector modal window
    $('#logical_connector_div').dialog("close");
    
    if(logicalConnectorItemTypeIndex == 0)
    {
        //opening conditional modal window
        $('#logical_connector_conditional_modal').dialog('open');
    }
    else if(logicalConnectorItemTypeIndex == 1)
    {
        $('#logical_connector_boolean_variables_div').dialog('open');
    }
    
}

//user presses ok button in logical connector conditional modal window after selecting a new condition
//A AND B where condition B has three parts i.e. left part, comparison part and right part
function buttonLogicalConnectorConditionOkPressed()
{
    updateClientEndOperationCounter();
    var selectedIndex = document.getElementById('logical_connector_selected_index').value;
    var selectedAnchorId = document.getElementById('logical_connector_selected_anchor_id').value;
    var selectedAnchorTitle = document.getElementById('logical_connector_selected_anchor_title').value;
    //since we are inserting a new condition inside current condition list and we have a marker of the location of
    //insertion, based on marker(selectedAnchorId, selectedAnchorTitle) we are splitting conditions in three parts. 
    //Before the marker we have previous list, new condition will be in current list and after marker will be next list.
    // We are updating both anchor and code list on left panel
    var previousAnchorList = "";
    var currentAnchorList = "";
    var nextAnchorList = "";
    var previousInputCodeList = "";
    var currentInputCodeList = "";
    var nextInputCodeList = "";
    
    var anchorIndexMarker = 0;
    var codeIndexMarker = 0;
    //current item is selected if on left panel
    var currentItem = $('#selectable .ui-selected');
    var selectedItem = "";  
    //next of current item is condition list
    currentItem = currentItem.next("li");
    //if there is a bracket after if then we are moving to next item which is a condition
    if(currentItem.text().trim() == "(")
    {
        currentItem = currentItem.next("li");
    }
    selectedItem = currentItem;
    $("a", currentItem).each(function ()
    {
        if(anchorIndexMarker == 0)
        {
            //storing anchor list before the new condtion insertion marker
            previousAnchorList = previousAnchorList + $(this).prop('outerHTML');
        }
        else
        {
            //storing anchor list after the new condtion insertion marker
            nextAnchorList = nextAnchorList + $(this).prop('outerHTML');
        }
        var anchor_id = $(this).attr("id");
        var anchor_title = $(this).attr("title");
        if(anchor_id == selectedAnchorId && anchor_title == selectedAnchorTitle)
        {
            anchorIndexMarker = 1;
        }
    });
    $("div", currentItem).each(function ()
    {
        $("input", $(this)).each(function ()
        {
            if(codeIndexMarker == 0)
            {
                //storing code list before the new condtion insertion marker
                previousInputCodeList = previousInputCodeList + $(this).prop('outerHTML');
            }

            else
            {
                //storing code list after the new condtion insertion marker
                nextInputCodeList = nextInputCodeList + $(this).prop('outerHTML');
            }
            var input_code_id = $(this).attr("id");
            if(input_code_id == selectedAnchorId )
            {
                codeIndexMarker = 1;
            }
        });
    });
    
    //taking three parts of new condtion from conditional modal
    var leftP = $("#logical_connector_left_part").html();
    var cmpP = $("#logical_connector_cmp_part").html();
    var rightP = $("#logical_connector_right_part").html();

    //boolean variable comparison starts
    var left_part_type = "";
    var right_part_type = "";
    $("input", $('#logical_connector_left_part')).each(function ()
    { 
        if($(this).attr("value") == "variable")
        {
            left_part_type = $(this).attr("id");
        }
    });
    $("input", $('#logical_connector_right_part')).each(function ()
    { 
        if($(this).attr("value") == "variable")
        {
            right_part_type = $(this).attr("id");
        }
    });
    
    if((left_part_type == "BOOLEAN" && right_part_type != "BOOLEAN") || (left_part_type != "BOOLEAN" && right_part_type == "BOOLEAN"))
    {
        $("#label_show_messages_content").html("Boolean variable can't be compared to number/function");
        $("#modal_show_messages").modal('show');
        //alert("Boolean variable can't be compared to number/function");
        return;
    }
    //boolean variable comparison ends

    //checking whether user selects all of the three parts of a condition
    if (leftP == "" || cmpP == "" || rightP == "") {
        $("#label_show_messages_content").html("Incomple condition.");
        $("#modal_show_messages").modal('show');
        //alert("Incomple condition.");
        return;
    }
    else
    {
        $('#logical_connector_conditional_modal').dialog('close');
        
        //generatin random id for each part of a condition
        var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id3 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        
        //generating random id for logical connector operator
        var logical_connector_id = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);

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
        $("input", $("#logical_connector_left_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });
        $("input", $("#logical_connector_cmp_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });
        $("input", $("#logical_connector_right_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });        
        
        //generating codes for all of the parts of the condition        
        var code_array = default_code_generation(parent_node_array,child_node_array);
        var div_part = "";
        for(var i = 0 ; i < code_array.length ; i++){
            //div_part = div_part + "<input type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>"
            if(i == 0)
                div_part = div_part + "<input title = "+id_array[0]+"start type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
            else if(i == code_array.length-1)
                div_part = div_part + "<input title = "+id_array[0]+"end  type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
            else
                div_part = div_part + "<input  title='comparison' type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
        }
        //adding logical connector AND operator
        if(selectedIndex == 0)
        {
            currentAnchorList = "<a style='cursor:pointer;' id=" + logical_connector_id + " title = 'logical_connector_and'><input id='natural' type='hidden' name='AND' value='logicalconnector' style=''/>" + " AND " + "</a><a title = "+id1+"start style='cursor:pointer;' id=" + id1 + "> " + leftP + "</a> <a title='comparison' style='cursor:pointer;' id=" + id2 + "> " + cmpP + "</a> <a title = "+id1+"end style='cursor:pointer;' id=" + id3 + "> " + rightP + "</a>";
            currentInputCodeList = "<input type='hidden' id ="+logical_connector_id+" value=' && ' name='condition' title = 'logical_connector_and' />" + div_part;
        }
        //adding logical connector OR operator
        else if(selectedIndex == 1)
        {
            currentAnchorList = "<a style='cursor:pointer;' id=" + logical_connector_id + " title = 'logical_connector_or'><input id='natural' type='hidden' name='OR' value='logicalconnector' style=''/>" + " OR " + "</a><a title = "+id1+"start style='cursor:pointer;' id=" + id1 + "> " + leftP + "</a> <a title='comparison' style='cursor:pointer;' id=" + id2 + "> " + cmpP + "</a> <a title = "+id1+"end style='cursor:pointer;' id=" + id3 + "> " + rightP + "</a>";
            currentInputCodeList = "<input type='hidden' id ="+logical_connector_id+" value=' || ' name='condition' title = 'logical_connector_or' />" + div_part;
        }
        //updating left panel with new added condition
        selectedItem.html(previousAnchorList+currentAnchorList+nextAnchorList+"<div id='code'>"+previousInputCodeList+currentInputCodeList+nextInputCodeList+"</div>");
           
    }
    //resetting three parts from conditional modal
    $("#logical_connector_left_part").html("");
    $("#logical_connector_cmp_part").html("");
    $("#logical_connector_right_part").html("");
    document.getElementById("logical_connector_left_part").style.border = "";
    document.getElementById("logical_connector_cmp_part").style.border = "";
    document.getElementById("logical_connector_right_part").style.border = "";    
}

//user presses cancel button in logical connector modal window
function button_logical_connector_cancel_pressed()
{
    updateClientEndOperationCounter();
    //closing logical connector modal window
    $('#logical_connector_div').dialog("close");
    //unblocking the user interface
    //$.unblockUI();
}

function button_logical_connector_removing_condition_cancel_pressed()
{
    updateClientEndOperationCounter();
    //closing logical connector removing condition modal window
    $('#logical_connector_removing_condition_div').dialog("close");
    //unblocking the user interface
    //$.unblockUI();
}


function logical_connector_part_of_conditioon_accordion_stmt($p) {
    updateClientEndOperationCounter();
    if ($p.id == "l_c_p_o_c_a") {
        document.getElementById("logical_connector_part_of_conditioon_selected_part").innerHTML = $p.innerHTML;
        document.getElementById("logical_connector_part_of_conditioon_selected_part").style.border = "inset red 4px";
    }    
}

/*
 * User wants to add a boolean variable after logical connector operator
 **/
function logical_connector_boolean_variable_selected_ok_pressed()
{
    updateClientEndOperationCounter();
    var selectedIndex = document.getElementById('logical_connector_boolean_variable_selected_index').value;
    var selectedAnchorId = document.getElementById('logical_connector_boolean_variable_selected_anchor_id').value;
    var selectedAnchorTitle = document.getElementById('logical_connector_boolean_variable_selected_anchor_title').value;
    //since we are inserting a new boolean inside current condition list and we have a marker of the location of
    //insertion, based on marker(selectedAnchorId, selectedAnchorTitle) we are splitting conditions in three parts. 
    //Before the marker we have previous list, new condition will be in current list and after marker will be next list.
    // We are updating both anchor and code list on left panel
    var previousAnchorList = "";
    var currentAnchorList = "";
    var nextAnchorList = "";
    var previousInputCodeList = "";
    var currentInputCodeList = "";
    var nextInputCodeList = "";
    
    var anchorIndexMarker = 0;
    var codeIndexMarker = 0;
    //current item is selected if on left panel
    var currentItem = $('#selectable .ui-selected');
    var selectedItem = "";  
    //next of current item is condition list
    currentItem = currentItem.next("li");
    //if there is a bracket after if then we are moving to next item which is a condition
    if(currentItem.text().trim() == "(")
    {
        currentItem = currentItem.next("li");
    }
    selectedItem = currentItem;
    $("a", currentItem).each(function ()
    {
        if(anchorIndexMarker == 0)
        {
            //storing anchor list before the new boolean variable insertion marker
            previousAnchorList = previousAnchorList + $(this).prop('outerHTML');
        }
        else
        {
            //storing anchor list after the new condtion insertion marker
            nextAnchorList = nextAnchorList + $(this).prop('outerHTML');
        }
        var anchor_id = $(this).attr("id");
        var anchor_title = $(this).attr("title");
        if(anchor_id == selectedAnchorId && anchor_title == selectedAnchorTitle)
        {
            anchorIndexMarker = 1;
        }
    });
    $("div", currentItem).each(function ()
    {
        $("input", $(this)).each(function ()
        {
            if(codeIndexMarker == 0)
            {
                //storing code list before the new boolean variable insertion marker
                previousInputCodeList = previousInputCodeList + $(this).prop('outerHTML');
            }

            else
            {
                //storing code list after the new condtion insertion marker
                nextInputCodeList = nextInputCodeList + $(this).prop('outerHTML');
            }
            var input_code_id = $(this).attr("id");
            if(input_code_id == selectedAnchorId )
            {
                codeIndexMarker = 1;
            }
        });
    });
    
    //taking three parts of selected boolean variable
    var booleanVariableLeftPart = $("#logical_connector_boolean_variables_left_part").html();
    var booleanVariableMiddlePart = $("#logical_connector_boolean_variables_middle_part").html();
    var booleanVariableRightPart = $("#logical_connector_boolean_variables_right_part").html();
    
    //checking whether user selects three parts of a boolean variable 
    if (booleanVariableLeftPart == "") {
        $("#label_show_messages_content").html("Please select a boolean variable.");
        $("#modal_show_messages").modal('show');
        //alert("Please select a boolean variable.");
        return false;
    }
    else if (booleanVariableMiddlePart == "") {
        $("#label_show_messages_content").html("Please select comparison for boolean variable.");
        $("#modal_show_messages").modal('show');
        //alert("Please select comparison for boolean variable.");
        return false;
    }
    else if (booleanVariableRightPart == "") {
        $("#label_show_messages_content").html("Please select boolean variable comparison value.");
        $("#modal_show_messages").modal('show');
        //alert("Please select boolean variable comparison value.");
        return false;
    }
    else
    {
        $('#logical_connector_conditional_modal').dialog('close');
        var booleanVariableMiddlePartText = "";
        var booleanVariableRightPartText = "";
        var booleanVariableMiddlePartCode = "";
        var booleanVariableRightPartCode = "";
        
        $("input", $("#logical_connector_boolean_variables_middle_part")).each(function ()
        {
            booleanVariableMiddlePartText = $(this).attr("name");
        });
        
        $("input", $("#logical_connector_boolean_variables_right_part")).each(function ()
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
        
        //generating random id for new boolean variable
        var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        var id3 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
        
        //generating random id for logical connector operator
        var logical_connector_id = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);

        var id_array = new Array();
        var counter = 0;
        id_array[counter++] = id1;
        id_array[counter++] = id2;
        id_array[counter++] = id3;
        
        var parent_node_array = new Array();
        var child_node_array = new Array();

        counter = 0;
        //taking options and optionstype for each part of the condition
        $("input", $("#logical_connector_boolean_variables_left_part")).each(function ()
        {
            parent_node_array[counter] = 	$(this).attr("value");
            child_node_array[counter] = 	$(this).attr("name");
            counter++;
        });
        
        //generating codes for all of the parts of the condition        
        var code_array = default_code_generation(parent_node_array,child_node_array);
        var div_part = "";
        for(var i = 0 ; i < code_array.length ; i++){
            if(i == 0)
                div_part = div_part + "<input title = "+id_array[0]+"start type='hidden' id ="+id_array[i]+" value='"+code_array[i]+"' name='condition'/>";
            div_part = div_part + "<input title = 'booleancomparison'      type='hidden' id ="+id_array[1]+" value='"+booleanVariableMiddlePartCode+"' name='condition'/>";
            div_part = div_part + "<input title = "+id_array[0]+"end   type='hidden' id ="+id_array[2]+" value='"+booleanVariableRightPartCode+"' name='condition'/>";
        }
        //adding logical connector AND operator
        if(selectedIndex == 0)
        {
            currentAnchorList = "<a style='cursor:pointer;' id=" + logical_connector_id + " title = 'logical_connector_and'><input id='natural' type='hidden' name='AND' value='logicalconnector' style=''/>" + " AND " + "</a><a title = "+id1+"start style='cursor:pointer;' id=" + id1 + "> " + booleanVariableLeftPart + "</a><a title = '' style='cursor:pointer;' id='" + id2 + "'> " + booleanVariableMiddlePart + "</a><a title = '"+id1+"end' style='cursor:pointer;' id='" + id3 + "'> " + booleanVariableRightPart + "</a>";
            currentInputCodeList = "<input type='hidden' id ="+logical_connector_id+" value=' && ' name='condition' title = 'logical_connector_and' />" + div_part;
        }
        //adding logical connector OR operator
        else if(selectedIndex == 1)
        {
            currentAnchorList = "<a style='cursor:pointer;' id=" + logical_connector_id + " title = 'logical_connector_or'><input id='natural' type='hidden' name='OR' value='logicalconnector' style=''/>" + " OR " + "</a><a title = "+id1+"start style='cursor:pointer;' id=" + id1 + "> " + booleanVariableLeftPart + "</a><a title = '' style='cursor:pointer;' id='" + id2 + "'> " + booleanVariableMiddlePart + "</a><a title = '"+id1+"end' style='cursor:pointer;' id='" + id3 + "'> " + booleanVariableRightPart + "</a>";
            currentInputCodeList = "<input type='hidden' id ="+logical_connector_id+" value=' || ' name='condition' title = 'logical_connector_or' />" + div_part;
        }
        //updating left panel with new added condition
        selectedItem.html(previousAnchorList+currentAnchorList+nextAnchorList+"<div id='code'>"+previousInputCodeList+currentInputCodeList+nextInputCodeList+"</div>");
          
    }
    //resetting three parts from conditional modal of boolean variable in logical connector
    $("#logical_connector_boolean_variables_left_part").html("");
    document.getElementById("logical_connector_boolean_variables_left_part").style.border = ""; 
    $("#logical_connector_boolean_variables_middle_part").html("");
    document.getElementById("logical_connector_boolean_variables_middle_part").style.border = ""; 
    $("#logical_connector_boolean_variables_right_part").html("");
    document.getElementById("logical_connector_boolean_variables_right_part").style.border = ""; 
    return true;
}