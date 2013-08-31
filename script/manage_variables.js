/*
 * User changes variable type from boolean to number or number to boolean. So modal window content is refreshed here
 **/
function addVariableTypeComboChange(addVariableTypeCombo) {
    updateClientEndOperationCounter();
    document.getElementById("add_variable_type_selection_combo").selectedIndex = addVariableTypeCombo.selectedIndex;
    if(addVariableTypeCombo.selectedIndex == 0)
    {
        document.getElementById("add_variable_value_selection_combo").selectedIndex = 0;
        document.getElementById("add_variable_value_selection_combo").style.visibility='visible';
        document.getElementById('add_variable_value_label').style.visibility='hidden';
        document.getElementById('add_variable_value_text').style.visibility='hidden';
    }
    else
    {
        document.getElementById("add_variable_value_selection_combo").style.visibility='hidden';
        document.getElementById('add_variable_value').value = "";
        document.getElementById('add_variable_value_label').style.visibility='visible';
        document.getElementById('add_variable_value_text').style.visibility='visible';
    }
}
/*
 *User changes boolean value from true to false or false to true. So index is updated here
 **/
function addVariableValueComboChange(addVariableValueCombo) {
    updateClientEndOperationCounter();
    document.getElementById("add_variable_value_selection_combo").selectedIndex = addVariableValueCombo.selectedIndex;
}
/*
 *This method checks the validation of a number
 *@parameter n, a number
 *@return true if paramete type is number, otherwise returns false
 **/
function isNumber(n) {
  updateClientEndOperationCounter();
  return !isNaN(parseFloat(n)) && isFinite(n);
}

/*
 * This method checks validation while creating a project variable and also checks unique project name
 * @returns false if variable information is invalid or variable name already exists in database, otherwise
 * returns true
 **/
function button_add_variable_ok_pressed()
{
    updateClientEndOperationCounter();
    var variable_name = document.getElementById('add_variable_name').value;
    if(variable_name == "")
    {
        alert("Please assign variable name.");
        return false;
    }
    //alpha numeric variable name is checking
    var pattern = /^[A-Za-z]{1}[A-Za-z0-9]+$/;
    if (!pattern.test(variable_name)) {
        alert("Name is not valid.Please input alphanumeric value!");
        return false;
    } 
    
    var variable_type_combo = document.getElementById("add_variable_type_selection_combo");
    var variable_type = variable_type_combo.options[variable_type_combo.selectedIndex].text;
    
    var variable_value = "";
    if(variable_type == "BOOLEAN"){
        var variable_value_combo = document.getElementById("add_variable_value_selection_combo");
        variable_value = variable_value_combo.options[variable_value_combo.selectedIndex].text;
    }
    else
    {
        variable_value = document.getElementById('add_variable_value').value;
        //text is not valid as number value
        if(!isNumber(variable_value))
        {
            alert("Please assign number as variable value.");
            return false;
        }
    }
    if(variable_value == "")
    {
        alert("Please assign variable value.");
        return false;
    }
    var variable_exist = false;
    $("#demo1 ul li#variable ul li").each(function(){
        if(variable_name == $(this).text().trim())
        {
            variable_exist = true;            
        }
    });
    if(variable_exist == true)
    {
        alert("Variable name already exists. Please use a different variable name.");
        return false;
    }
    else{
        document.getElementById('project_left_panel_content_backup').value = $("#selectable").html();
        return true;
    }
}

/*
 * User presses cancel button after opening add variable modal window
 **/
function button_add_variable_cancel_pressed()
{
    updateClientEndOperationCounter();
    $('#add_variables_div').dialog("close");
}

/*
 * Before deleting a variable this method checks whether this variable is used in program or not
 **/
function is_variable_used_delete_button_clicked(variable_id)
{
    updateClientEndOperationCounter();
    var is_variable_used = false;
    //getting variable info
    var variable = get_variable_info(variable_id);
    $('#delete_variable_variable_id').val(variable_id);
    $('#delete_variable_project_left_panel_content').val($("#selectable").html());
    $('#selectable').each(function()
    {
        $("li", $(this)).each(function ()
        {
            $("a", $(this)).each(function ()
            {
               $("input", $(this)).each(function ()
                {
                    if($(this).attr("value") == "variable")
                    {
                        if($(this).attr("name") == variable.variable_name)
                        {
                            is_variable_used = true;
                        }
                    }
                });
            });
        });
    });
    if(is_variable_used == true)
    {
        alert("You have used this variable. You will not be able to delete it.");
        return false;
    }
    else
    {
        if (confirm('Are you sure you want to delete this variable?')) 
        {
            return true;
        } 
        else 
        {
            return false;
        }
        
    }    
}
