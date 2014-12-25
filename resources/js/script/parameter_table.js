/*
 * This method adds or removes project variables from combo box type parameters based on parameter allowvar attribute
 * @param parameterCheckBox, checkbox in parameter table
 **/
function variableSelectedFunctionParameterClicked(parameterCheckBox) {
    updateClientEndOperationCounter();
    var comboCounter = 1;
    var flag = true;
    var counter = 0;
    var selectedFeatureName = "";
    var selectedFeatureValue = "";
    //retrieving selected item name and value from natural language panel
    $("#changing_stmt a").each(function() {
        if($(this).attr("class") == "selected_expression") {
            $("input", $(this)).each(function () {
                selectedFeatureName = $(this).attr("name");
                selectedFeatureValue = $(this).attr("value");
            });
        }
    });
    var allowed_var_value = "";
    var feature_parameter_list = new Array();
    var combo_parameter_counter = 0;
    var feature_parameter_list_counter = 0;
    //adding all variables of this project to the parameters of type combo
    if(parameterCheckBox.checked) {
        while(flag) {
            if(document.getElementById("customCombo"+comboCounter) != null) {
                //retrieving allowvar value of this parameter which type is combo
                allowed_var_value = "";
                for(feature_list_counter = 0; feature_list_counter<feature_list.length ; feature_list_counter++) {
                    if(feature_list[feature_list_counter].getOptions() == selectedFeatureValue && feature_list[feature_list_counter].getOptionsType() == selectedFeatureName) {
                        feature_parameter_list = feature_list[feature_list_counter].getParameterList();
                        combo_parameter_counter = 0;
                        feature_parameter_list_counter = 0;
                        for(feature_parameter_list_counter = 0 ; feature_parameter_list_counter < feature_parameter_list.length ; feature_parameter_list_counter++) {
                            if(feature_parameter_list[feature_parameter_list_counter].getAllowedValues().length > 0) {
                                combo_parameter_counter++;
                                if(combo_parameter_counter == comboCounter && feature_parameter_list[feature_parameter_list_counter].getType().toLowerCase() == "integer") {
                                    allowed_var_value = feature_parameter_list[feature_parameter_list_counter].getAllowVar();                                    
                                }
                            }
                        }
                    }
                }
                //if allowvar is Yes then we are allowed to add project variable into combo parameter
                if(allowed_var_value.toLowerCase() == "yes") {
                    var id = "";
                    var title = "";
                    $("#parameters_table table tbody tr td select option").each(function() {
                        id = $(this).attr("id");
                        title = $(this).attr("title")
                    });
                    for(counter = 0 ; counter < project_variable_list.length ; counter++) {
                        if(project_variable_list[counter].getVariableType() == "NUMBER") {
                            var DropdownBox =document.getElementById("customCombo"+comboCounter);
                            DropdownBox.options[DropdownBox.options.length] = new Option(project_variable_list[counter].getVariableName(),'');
                            DropdownBox.options[DropdownBox.options.length-1].setAttribute("value", project_variable_list[counter].getVariableName());
                            DropdownBox.options[DropdownBox.options.length-1].setAttribute("id", id);
                            DropdownBox.options[DropdownBox.options.length-1].setAttribute("title", title);                        
                        }
                    }
                }               
                comboCounter++;
            }
            else {
                flag = false;
            }
        }
    }
    //removing all variables of this project from parameters of type combo    
    else {
        while(flag) {
            if(document.getElementById("customCombo"+comboCounter) != null) {
                //retrieving allowvar value of this parameter which type is combo
                allowed_var_value = "";
                for(feature_list_counter = 0; feature_list_counter<feature_list.length ; feature_list_counter++) {
                    if(feature_list[feature_list_counter].getOptions() == selectedFeatureValue && feature_list[feature_list_counter].getOptionsType() == selectedFeatureName)
                    {
                        feature_parameter_list = feature_list[feature_list_counter].getParameterList();
                        combo_parameter_counter = 0;
                        feature_parameter_list_counter = 0;
                        for(feature_parameter_list_counter = 0 ; feature_parameter_list_counter < feature_parameter_list.length ; feature_parameter_list_counter++) {
                            if(feature_parameter_list[feature_parameter_list_counter].getAllowedValues().length > 0) {
                                combo_parameter_counter++;
                                if(combo_parameter_counter == comboCounter && feature_parameter_list[feature_parameter_list_counter].getType().toLowerCase() == "integer") {
                                    allowed_var_value = feature_parameter_list[feature_parameter_list_counter].getAllowVar();                                    
                                }
                            }
                        }
                    }
                }
                //if allowvar is Yes then we are allowed to removed project variable from combo parameter                
                if(allowed_var_value.toLowerCase() == "yes") {
                    for(counter = 0 ; counter < project_variable_list.length ; counter++) {
                        if(project_variable_list[counter].getVariableType() == "NUMBER") {
                            document.getElementById("customCombo"+comboCounter).options.length = document.getElementById("customCombo"+comboCounter).options.length - 1;
                        }
                    } 
                }                               
                comboCounter++;
            }
            else {
                flag = false;
            }
        }
    }
}

/*
 * This method is called when user changes any parameter value of type text from parameter table
 * @param childNodeName, i.e. optionstype of the feature of this parameter
 * @param parentNodeName, i.e. options of the feature of this parameter
 * @param hasLimit, i.e. a flag to indicate whether this text input has limit or not
 * @param lowerLimit, i.e. minimum allowable value of INTEGER type text input
 * @param upperLimit, i.e. maximum allowable value of INTEGER type text input
 * @param inputType, i.e. type of text input. Possible types are INTEGER, REAL, text
 * @param textInputId, i.e. id of text input which texting is changing
 **/
function customTextChange(childNodeName, parentNodeName, hasLimit, lowerLimit, upperLimit, inputType, textInputId)
{
    updateClientEndOperationCounter();
    var allowVariable = document.getElementById("variable_selected_function_parameter").checked;    
    var nameArray = new Array();
    var valueArray = new Array();
    var counter = 0;
    var totalParameters = 1;
    var comboCounter = 1;
    var isValid = 1;
    var currentTextInputId = "";
    var isParameterValueStored = false;
    $("tr", $("#parameters_table")).each(function () {
        //skipping first two rows where first row is to allow variable selection or not and second row is to display string of Name, Value
        if (totalParameters > 2) {
            var i = 1;
            $("td", $(this)).each(function () {
                isParameterValueStored = false;
                if (i == 1) {
                    //First column of a row is parameter name
                    nameArray[counter] = $(this).text();
                } 
                //Second column of a row contains parameter value                    
                else if (i == 2) {
                    //text input type parameter
                    $("input", $(this)).each(function () {
                        //for parameter named external we have more than one input tags and only first input tag contain parameter value
                        //thats why we are returning back if we store parameter value
                        if(isParameterValueStored == true)
                            return;
                        $(this).attr('value', $(this).val());
                        valueArray[counter] = $(this).attr("value");
                        isParameterValueStored = true;
                        //reading current text input id
                        currentTextInputId = $(this).attr("id");
                        if(currentTextInputId == textInputId) {
                            var regExp = "";
                            if(allowVariable) {
                                //checking valid number variables
                                var parameterValue = $(this).attr("value").trim();
                                var isBooleanVariable = false;
                                var isNumberVariable = false;
                                var variableType = "";
                                var variableName = "";
                                $("#demo1 ul li#variable ul li").each(function() {
                                    variableType = $(this).attr("title");
                                    variableName = $(this).text().trim();
                                    if(variableType == "BOOLEAN" && variableName == parameterValue) {
                                        isBooleanVariable = true;
                                    }
                                    else if(variableType == "NUMBER" && variableName == parameterValue) {
                                        isNumberVariable = true;
                                    }
                                });
                                if(isBooleanVariable) {
                                    $('#label_alert_message').text("You can't use boolean variable as function parameter. Please use number variable");
                                    $('#div_alert_message').dialog('open');
                                    isValid = 0;
                                }
                                else if(!isBooleanVariable && !isNumberVariable) {
                                    $('#label_alert_message').text("Please use number variable");
                                    $('#div_alert_message').dialog('open');
                                    isValid = 0;
                                }
                            }
                            else {
                                if(inputType == "INTEGER") {
                                    regExp = /^-?\d+$/g;
                                    if(!regExp.test($(this).attr("value").trim())) {
                                        $('#label_alert_message').text("Invalid input type.");
                                        $('#div_alert_message').dialog('open');
                                        isValid = 0;
                                    }
                                }
                                else if(inputType == "REAL") {
                                    regExp = /^-?\d+.{1}\d+$/g;
                                    if(!regExp.test($(this).attr("value").trim())) {
                                        $('#label_alert_message').text("Invalid input type.");
                                        $('#div_alert_message').dialog('open');
                                        isValid = 0;
                                    }
                                }
                                if(hasLimit == 1 && isValid == 1) {
                                    if(Number($(this).attr("value").trim()) < lowerLimit || Number($(this).attr("value").trim()) > upperLimit) {
                                        $('#label_alert_message').text("Your given input for "+$(this).attr("name")+" is out or range. Please assign value within ["+lowerLimit+","+upperLimit+"]");
                                        $('#div_alert_message').dialog('open');
                                        isValid = 0;
                                    }
                                }
                            }
                        } 
                    });
                    //combobox type parameter
                    $("select", $(this)).each(function () {
                        valueArray[counter] = $("#customCombo" + comboCounter + " option:selected").text();
                        comboCounter++;
                    });
                }
                i++;
            });
            counter++;
        }
        totalParameters++;
    });
    if(isValid == 0)
        return;
    else {
        //updating left panel, natural language panel and code panel
        updateConditionAndCode(parentNodeName, childNodeName, nameArray, valueArray);
    }
}

/*
 * This method is called when user changes any combo box item from parameter table
 * @param selectedComboBox, i.e. combo box which item is changed 
 **/
function customComboChange(selectedComboBox) {
    updateClientEndOperationCounter();
    var childNodeName = selectedComboBox.options[selectedComboBox.selectedIndex].id;
    var parentNodeName = selectedComboBox.options[selectedComboBox.selectedIndex].title;
    var nameArray = new Array();
    var valueArray = new Array();
    var counter = 0;
    var totalParameters = 1;
    var comboCounter = 1;
    var isParameterValueStored = false;
    $("tr", $("#parameters_table")).each(function () {
        //skipping first two rows where first row is to allow variable selection or not and second row is to display string of Name, Value
        if (totalParameters > 2) {
            var i = 1;
            $("td", $(this)).each(function () {
                isParameterValueStored = false;
                if (i == 1) {
                    //First column of a row is parameter name
                    nameArray[counter] = $(this).text();
                } 
                //Second column of a row contains parameter value  
                else if (i == 2) {
                    //text input type parameter
                    $("input", $(this)).each(function () {
                        //for parameter named external we have more than one input tags and only first input tag contain parameter value
                        //thats why we are returning back if we store parameter value
                        if(isParameterValueStored == true)
                            return;
                        valueArray[counter] = $(this).attr("value");
                        isParameterValueStored = true;
                    });
                    //combobox type parameter
                    $("select", $(this)).each(function () {
                        valueArray[counter] = $("#customCombo" + comboCounter + " option:selected").text();
                        comboCounter++;
                    });
                }
                i++;
            });
            counter++;
        }
        totalParameters++;
    });
    //updating left panel, natural language panel and code panel
    updateConditionAndCode(parentNodeName, childNodeName, nameArray, valueArray);
}

/*
 * This method updates left panel, natural language panel and code panel after updating text input parameter value
 * @param parentNodeName, i.e. options of the feature of this parameter
 * @param childNodeName, i.e. optionstype of the feature of this parameter
 * @param nameArray, i.e. all parameter names of a feature
 * @param valueArray, i.e. all parameter values of a feature
 **/
function updateConditionAndCode(parentNodeName, childNodeName, nameArray, valueArray){
    updateClientEndOperationCounter();
    var natarulData = condition_dynamic_process(nameArray, valueArray, childNodeName, parentNodeName);
    var selectedId;
    var $customAnchor;
    var htmlText;
    var textContent;
    //updating natural language panel
    $("a", $("#changing_stmt")).each(function () {
        if ($(this).attr("class")) {
            selectedId = $(this).attr("id");
            $customAnchor = $(this);
            htmlText = $customAnchor.html();
            textContent = $customAnchor.text();
            htmlText = htmlText.trim().replace(textContent.trim(), "");
            $customAnchor.html(htmlText + natarulData);

            //updating left panel
            $("a", $("#selectable .ui-selected")).each(function () {
                if($(this).attr("id") == selectedId) {
                    $(this).html($customAnchor.html());
                }
                $(this).removeAttr("onclick");
                $(this).removeAttr("class");
            });
        }
    });	
    //updating code structure in left panel
    var codeData = code_dynamic_process(nameArray, valueArray, childNodeName, parentNodeName);
    $("div",  $('#selectable .ui-selected')).each(function () {
        $("input", $(this)).each(function () {
            if($(this).attr("id") === selectedId) {
                $(this).removeAttr("value");
                $(this).attr("value",codeData);
            }
        });
    });
    //updating code panel
    generate_selected_item_code();        
}
