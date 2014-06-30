//user presses arithmetic operators from menu
function add_arithmetic_operators()
{
    updateClientEndOperationCounter();
    //validation checking to allow adding arithmetic operator
    var selected_part_anchor_id = "";
    var valid_selection = "true";
    var error_message = "You are not allowed to add arithmetic operator here.";
    $("a", $('#changing_stmt')).each(function ()
    {
        if(valid_selection == "false")
        {
            return;
        }
        //arithmetic operator is not allowed with comparison
        if($(this).attr("class") == "selected_expression" && $(this).attr("title") == "comparison")
        {
            valid_selection = "false";
            error_message = "You are not allowed to add arithmetic operator with comparison.";
        }
        if ($(this).attr("class") == "selected_expression" )
        {
            selected_part_anchor_id = $(this).attr("id"); 
            $("input", $(this)).each(function ()
            {
                //arithmetic operator is not allowed with action
                if($(this).attr("value") == "action")
                {
                    valid_selection = "false";
                    error_message = "You are not allowed to add arithmetic operator with action.";
                }
                
                //arithmetic operator is not allowed with boolean variable
                if($(this).attr("value") == "variable" && $(this).attr("id") == "BOOLEAN")
                {
                    valid_selection = "false";
                    error_message = $(this).attr("name")+" is a boolean variable. You are not allowed to add arithmetic operator with boolean variable.";
                }
            });
        }
    });
    if(selected_part_anchor_id == "")
    {
        $('#label_alert_message').text("Please select an item from condition.");
        $('#div_alert_message').dialog('open');
        //alert("Please select an item from condition.");
        return;
    }
    else if(valid_selection == "false")
    {
        $('#label_alert_message').text(error_message);
        $('#div_alert_message').dialog('open');
        //alert(error_message);
        return;
    }

    document.getElementById("arithmetic_operator_selection_combo").selectedIndex = 0;
    document.getElementById("arithmetic_operator_right_part_type_selection_combo").selectedIndex = 0;
    document.getElementById('arithmetic_operator_right_part_value').value = "";

    document.getElementById('arithmetic_operator_right_part_value_label').style.visibility='visible';
    document.getElementById('arithmetic_operator_right_part_value').style.visibility='visible';

    $('#arithmetic_operator_div').dialog('open');
}

function process_operator(selected_operator, name,value,language,code, is_arithmetic_operator)
{
    updateClientEndOperationCounter();
    var selected_operator_code = "";
    var selected_operator_value_tag = "";
    
    if(is_arithmetic_operator == "false")
    {
        selected_operator_value_tag = "logicalconnector";
        if(selected_operator.trim() == "AND")
        {
            selected_operator_code = " && ";
        }
        else if(selected_operator.trim() == "OR")
        {
            selected_operator_code = " || ";
        }
    }
    else
    {
        selected_operator_value_tag = "arithmeticoperator";
        selected_operator_code = selected_operator;
    }
    //retrieving selected anchor of the changing_stmt div i.e. below of condition in natural language div
    var $changing_stmt_anchor_list = "";
    var $left_panel_anchor_list = "";
    var $left_panel_code_list = "";
    var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    var selected_id = "";
    var changing_stmt_selected_id = "";
    var changing_stmt_title = "";
    var left_panel_anchor_title = "";
    var left_panel_code_title = "";
    var title_attribute = "";
    //var selected_operator = $("#arithmetic_operator_selection_combo option:selected").text();
    
    
    $("a", $("#changing_stmt")).each(function () 
    {
        
        if ($(this).attr("class") == "selected_expression") 
        {
            changing_stmt_selected_id = $(this).attr("id");
            //checking whether attribute named title exists or not
            if($(this).attr("title") !== undefined)
            {
                changing_stmt_title = $(this).attr("title");
            }
            
            if(changing_stmt_title.indexOf("end") > 0)
            {
                title_attribute = "title="+changing_stmt_title;
                $(this).removeAttr("title");
                
            }
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + $(this).prop('outerHTML');
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + "<a onclick='manageExpression(this)' style='cursor:pointer;' id="+id1+"> <input style='' value='"+selected_operator_value_tag+"' name="+selected_operator+" type='hidden'> "+selected_operator+" </a>";
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + "<a "+title_attribute+" onclick='manageExpression(this)' style='cursor:pointer;' id="+id2+"> <input style='' value="+value+" name="+name+" type='hidden'> "+language+" </a>";
            
        }
        else
        {
            $changing_stmt_anchor_list = $changing_stmt_anchor_list + $(this).prop('outerHTML');
        
        }
    });
    $("#changing_stmt").html($changing_stmt_anchor_list);
    
    //updating left panel anchors
    title_attribute = "";
    $("a", $("#selectable .ui-selected")).each(function ()
    {
        selected_id = $(this).attr("id");
        if(selected_id == changing_stmt_selected_id){
            if($(this).attr("title") !== undefined)
            {
                left_panel_anchor_title = $(this).attr("title");
            }
            
            if(left_panel_anchor_title.indexOf("end") > 0)
            {
                title_attribute = "title="+left_panel_anchor_title;
                $(this).removeAttr("title");                
            }
            $left_panel_anchor_list = $left_panel_anchor_list + $(this).prop('outerHTML');        
            $left_panel_anchor_list = $left_panel_anchor_list + "<a style='cursor:pointer;' id="+id1+"> <input style='' value='"+selected_operator_value_tag+"' name="+selected_operator+" type='hidden'> "+selected_operator+" </a>";
            $left_panel_anchor_list = $left_panel_anchor_list + "<a "+title_attribute+" style='cursor:pointer;' id="+id2+"> <input style='' value="+value+" name="+name+" type='hidden'> "+language+" </a>";
        }
        else
        {
            $left_panel_anchor_list = $left_panel_anchor_list + $(this).prop('outerHTML'); 
        }
    });
    title_attribute = "";
    $("div", $("#selectable .ui-selected")).each(function ()
    {
        $("input", $(this)).each(function ()
        {
            selected_id = $(this).attr("id");
            if(selected_id == changing_stmt_selected_id){
                if($(this).attr("title") !== undefined)
                {
                    left_panel_code_title = $(this).attr("title");
                }
                
                if(left_panel_code_title.indexOf("end") > 0)
                {
                    title_attribute = "title="+left_panel_code_title;
                    $(this).removeAttr("title");                
                }
                $left_panel_code_list = $left_panel_code_list + $(this).prop('outerHTML');        
                $left_panel_code_list = $left_panel_code_list + "<input style='' id="+id1+" type='hidden' name='condition' value="+selected_operator_code+"></input>";
                $left_panel_code_list = $left_panel_code_list + "<input style='' "+title_attribute+" id="+id2+" type='hidden' name='condition' value="+code+"></input>";
            }
            else
            {
                $left_panel_code_list = $left_panel_code_list + $(this).prop('outerHTML'); 
            } 
        });
        
    });
    $left_panel_anchor_list = $left_panel_anchor_list +"<div id='code'>"+$left_panel_code_list+"</div>";
    $("#selectable .ui-selected").html($left_panel_anchor_list);    
    //updating code panel
    generate_selected_item_code();
}

