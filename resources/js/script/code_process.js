/*
 *This method generates code of a feature configured in xml file
 *@param parent_node_array i.e. options of a feature configured in xml file
 *@param child_node_array i.e. optionstype of a feature configured in xml file
 *@return code string i.e. replace parameter name from code of a feature by default value of that parameter
 *N.B. here array is used as input parameters because this method can generate code of a 
 *condition(left part, comparison part and right part)once at a time
 **/
function default_code_generation(parent_node_array, child_node_array)
{
    updateClientEndOperationCounter();
    var code_array = Array();
    var code_array_counter = 0;
    for (var i = 0; i < parent_node_array.length ; i++)
    {
        var parent_node_name = parent_node_array[i];
        var child_node_name = child_node_array[i];
        if(parent_node_name == 'variable')
        {
            code_array[code_array_counter++] =  child_node_name;            
        }
        else
        {
            for(var j = 0 ; j < feature_list.length ; j++)
            {
                var feature_object = feature_list[j];
                var options = feature_object.getOptions();
                var optionstype = feature_object.getOptionsType();
                if(parent_node_name == options && child_node_name == optionstype)
                {
                    var displayCode = feature_object.getCode();
                    var parameter_list = feature_object.getParameterList();
                    for(var k = 0 ; k < parameter_list.length ; k++)
                    {
                        var parameter_object = parameter_list[k];
                        var searched_code = "$"+parameter_object.getName();
                        var replaced_code = parameter_object.getDefaultValue();
                        displayCode = displayCode.replace(searched_code,replaced_code);
                    }
                    code_array[code_array_counter++] = displayCode;
                }
            }
        }
    }
    return code_array;
}
/*
 *This method generates parameters table
 *@param nameArray, i.e. list of parameter name of a feature
 *@param valueArray, i.e. list of default value of parameter of a feature
 *@param id, i.e. optionstype of a feature
 *@param name, i.e. options of a feature
 *@return html content of table
 **/
function server_process(nameArray, valueArray, id, name)
{
    updateClientEndOperationCounter();
    var table_content = "";
    var dataArray = Array();
    for (var i = 0; i < nameArray.length ; i++)
    {
        dataArray[nameArray[i]] = valueArray[i];
    }
    for(var j = 0 ; j < feature_list.length ; j++)
    {
        var feature_object = feature_list[j];
        var options = feature_object.getOptions();
        var optionstype = feature_object.getOptionsType();
        if(optionstype == id)
        {
            table_content = "<table width='100%'  border='1' style='border-collapse:collapse;'>";
            var combo_counter = 1;   
            var text_input_counter = 1;
            var parameter_list = feature_object.getParameterList();
            if(parameter_list.length == 0)
            {
                table_content = table_content+"<tr><td width='50%' align='center'>Name</td><td width='50%' align='center'>Value</td></tr>";
            }
            else
            {
                table_content = table_content+"<tr><td width='50%' align='center'>Use Variable</td><td width='50%' align='center'><input type='checkbox' id = 'variable_selected_function_parameter' name='variable_selected_function_parameter' onchange='variableSelectedFunctionParameterClicked(this)'/></td></tr><tr><td width='50%' align='center'>Name</td><td width='50%' align='center'>Value</td></tr>";
            }
            for(var k = 0 ; k < parameter_list.length ; k++)
            {
                var parameter_object = parameter_list[k];
                var parameter_name = parameter_object.getName();
                var parameter_type = parameter_object.getType();
                var parameter_allowed_values_list = parameter_object.getAllowedValues();
                var parameter_interval = parameter_object.getInterval();
                table_content = table_content+"<tr><td width='50%' align='center'>"+parameter_name+"</td><td width='50%' align='center'>";
                        
                if(parameter_allowed_values_list.length > 0)
                {
                    table_content = table_content+"<select name='customCombo"+combo_counter+"' id='customCombo"+combo_counter+"' onchange='customComboChange(this)'>";
                    combo_counter++;
                     for(var l = 0 ; l < parameter_allowed_values_list.length ; l++)
                     {
                        var selection = "";
                        var value = parameter_allowed_values_list[l];
                        if (value == dataArray[parameter_name])
                        {
                            selection = "selected = 'selected'";
                        }
                        table_content = table_content+"<option id='"+id+"' title='"+name+"' value='"+value+"' "+selection+">"+value+"</option>";
                     }   
                }
                else
                {
                    var haslimit = 0;
                    var lower = "0";
                    var upper = "0";
                    if (parameter_interval.length > 0)
                    {
                        haslimit = 1;
                        lower = parameter_interval[0];
                        upper = parameter_interval[1];
                    }
                    var options_id = options;
                    var options_name = optionstype;
                    
                    var buttonInsertionCode = "";
                    var textInputId = "textinput"+text_input_counter;
                    if(parameter_name == "external")
                    {
                        buttonInsertionCode = "<input type='button' id='button_external_variable_upload' name='button_external_variable_upload' value='Upload'>";                            
                        textInputId = "externalTextInput";
                    }
                    table_content = table_content+"<input type='text' id='"+textInputId+"' name = '"+parameter_name+"' value = '"+dataArray[parameter_name]+"' onchange='customTextChange(\""+options_name+"\",\""+options_id+"\",\""+haslimit+"\",\""+lower+"\",\""+upper+"\",\""+parameter_type+"\",\""+textInputId+"\")'></input>"+buttonInsertionCode+"</td></tr>";
                    text_input_counter++;
                }
            }
            table_content = table_content+"</table>";
            return table_content;
        }
    }
    return table_content;    
}

/*
 *This mehtod generates new code if parameter value is changed by user
 *@param nameArray, i.e. list of parameter name of a feature
 *@param valueArray, i.e. list of default value of parameter of a feature
 *@param name, i.e. optionstype of a feature
 *@param value, i.e. options of a feature
 *@return updated code of a feature
 **/
function code_dynamic_process(nameArray, valueArray, name, value)
{
    updateClientEndOperationCounter();
    var displayCode = "";
    var dataArray = Array();
    for (var i = 0; i < nameArray.length ; i++)
    {
        dataArray[nameArray[i]] = valueArray[i];
    }
    for(var j = 0 ; j < feature_list.length ; j++)
    {
        var feature_object = feature_list[j];
        var options = feature_object.getOptions();
        var optionstype = feature_object.getOptionsType();
        if(options == value && optionstype == name)
        {
            displayCode = feature_object.getCode();
            var parameter_list = feature_object.getParameterList();
            for(var k = 0 ; k < parameter_list.length ; k++)
            {
                var parameter_object = parameter_list[k];
                var parameter_name = parameter_object.getName();
                var searched_code = "$"+parameter_object.getName();
                var replaced_code = dataArray[parameter_name];
                displayCode = displayCode.replace(searched_code,replaced_code);
            }
            return displayCode;
        }
    }
    return displayCode;    
}
/*
 *This mehtod generates new natural language of a feature if parameter value is changed by user
 *@param nameArray, i.e. list of parameter name of a feature
 *@param valueArray, i.e. list of default value of parameter of a feature
 *@param id, i.e. optionstype of a feature
 *@param name, i.e. options of a feature
 *@return updated natural language of a feature
 **/
function condition_dynamic_process(nameArray, valueArray, id, name)
{
    updateClientEndOperationCounter();
    var displayCode = "";
    var dataArray = Array();
    for (var i = 0; i < nameArray.length ; i++)
    {
        dataArray[nameArray[i]] = valueArray[i];
    }
    for(var j = 0 ; j < feature_list.length ; j++)
    {
        var feature_object = feature_list[j];
        var options = feature_object.getOptions();
        var optionstype = feature_object.getOptionsType();
        if(options == name && optionstype == id)
        {
            displayCode = feature_object.getNatural();
            var parameter_list = feature_object.getParameterList();
            for(var k = 0 ; k < parameter_list.length ; k++)
            {
                var parameter_object = parameter_list[k];
                var parameter_name = parameter_object.getName();
                var searched_code = "$"+parameter_object.getName();
                var replaced_code = dataArray[parameter_name];
                displayCode = displayCode.replace(searched_code,replaced_code);
            }
            return displayCode;
        }
    }
    return displayCode; 
}

/*
 *This mehtod generates parameter list of a code
 *@param childNode, i.e. optionstype of a feature
 *@param parentNode, i.e. options of a feature
 *@param selectedCode, i.e. code to be parsed to extract parameter list
 *@return nameArray, i.e. list of parameter name of this code
 *@return valueArray, i.e. list of parameter value of this code
 **/
function reverse_code_process(parentNode, childNode, selectedCode)
{
    updateClientEndOperationCounter();
    var returned_array = Array();
    var displayCode = "";
    var nameArray = Array();
    var valueArray = Array();
    var array_index_counter = 0;
    for(var j = 0 ; j < feature_list.length ; j++)
    {
        var feature_object = feature_list[j];
        var options = feature_object.getOptions();
        var optionstype = feature_object.getOptionsType();
        if(options == parentNode && optionstype == childNode)
        {
            var parameter_list = feature_object.getParameterList();
            
            displayCode = feature_object.getCode();
            var counter = 1;
            var start_param_index = 0;
            var start_sample_index = 0;
            var total_params = parameter_list.length;
            var sample_code = selectedCode.trim();
            var pos1;
            var pos2;
            for(var k = 0 ; k < parameter_list.length ; k++)
            {
                var parameter_object = parameter_list[k];
                var parameter_name = parameter_object.getName();
                var param_value = "";
                
                if (counter == total_params)
                {
                    pos1 = displayCode.indexOf("$"+parameter_name, start_param_index);
                    var sub_stirng1 = displayCode.substr(pos1 + parameter_name.length + 1);
                    if (sub_stirng1.length > 0)
                    {
                        pos2 = sample_code.indexOf(sub_stirng1, start_sample_index);
                        param_value = sample_code.substr(pos1, pos2 - pos1);
                    }
                    else
                    {
                        param_value = sample_code.substr(pos1, sample_code.length - pos1);
                    }
                    nameArray[array_index_counter] = parameter_name;
                    valueArray[array_index_counter] = param_value;
                    array_index_counter++;
                }
                else
                {
                    pos1 = displayCode.indexOf("$"+parameter_name, start_param_index);
                    var sub_string1 = displayCode.substr(pos1 + parameter_name.length + 1);
                    var length = sub_string1.indexOf("$", 0);
                    var sub_string2 = sub_string1.substr(0, length);
                    pos2 = sample_code.indexOf(sub_string2, pos1);
                    param_value = sample_code.substr(pos1, pos2 - pos1);
                    displayCode = displayCode.substr(pos1 + parameter_name.length + 1);
                    sample_code = sample_code.substr(pos1 + param_value.length);
                    
                    nameArray[array_index_counter] = parameter_name;
                    valueArray[array_index_counter] = param_value;
                    array_index_counter++;
                }
                counter++;                
            }
            returned_array[0] = nameArray;
            returned_array[1] = valueArray;
            if( selectedCode.indexOf("[", 0) >= 0 )
            {
                returned_array[2] = selectedCode.substr(0, selectedCode.indexOf("[", 0));
            }
            else if( selectedCode.indexOf("(", 0) >= 0 )
            {
                returned_array[2] = selectedCode.substr(0, selectedCode.indexOf("(", 0));
            }
        }
    }
    return returned_array;    
}


/*
 *This mehtod generates all types of information related to a selected feature from jstree
 *@param childNode, i.e. optionstype of a feature
 *@param parentNode, i.e. options of a feature
 **/
function tree_node_change(parentNode, childNode)
{
    updateClientEndOperationCounter();
    var returned_array = Array();
    var selectedExpressionCode = "";
    var j;
    var k;
    var feature_object;
    var options;
    var optionstype;
    var parameter_list;
    var parameter_object;
    var searched_code;
    var replaced_code;
    for(j = 0 ; j < feature_list.length ; j++)
    {
        feature_object = feature_list[j];
        options = feature_object.getOptions();
        optionstype = feature_object.getOptionsType();
        if(options == parentNode && optionstype == childNode)
        {
            selectedExpressionCode = feature_object.getNatural();
            parameter_list = feature_object.getParameterList();
            for(k = 0 ; k < parameter_list.length ; k++)
            {
                parameter_object = parameter_list[k];
                searched_code = "$"+parameter_object.getName();
                replaced_code = parameter_object.getDefaultValue();
                selectedExpressionCode = selectedExpressionCode.replace(searched_code,replaced_code);
            } 
            returned_array[0] = selectedExpressionCode;
        }
    }
    var selectedDisplayCode = "";    
    for(j = 0 ; j < feature_list.length ; j++)
    {
        feature_object = feature_list[j];
        options = feature_object.getOptions();
        optionstype = feature_object.getOptionsType();
        if(options == parentNode && optionstype == childNode)
        {
            selectedDisplayCode = feature_object.getCode();
            parameter_list = feature_object.getParameterList();
            for(k = 0 ; k < parameter_list.length ; k++)
            {
                parameter_object = parameter_list[k];
                searched_code = "$"+parameter_object.getName();
                replaced_code = parameter_object.getDefaultValue();
                selectedDisplayCode = selectedDisplayCode.replace(searched_code,replaced_code);
            }    
            returned_array[1] = selectedDisplayCode;
        }
    }
    
    var param_info = reverse_code_process(parentNode, childNode, selectedDisplayCode);
    var nameArray = param_info[0];
    var valueArray = param_info[1];
    
    var table_content = server_process(nameArray, valueArray, childNode, parentNode);
    //returned_array[2] = nameArray;
    //returned_array[3] = valueArray;
    returned_array[2] = table_content;
    
    return returned_array;
}      