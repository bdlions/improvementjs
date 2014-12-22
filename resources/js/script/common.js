var left_panel_selected_item_type = "";
var service_url = "";

var project_variable_list = new Array();
var server_base_url = "";
var indentation_space_length = 5;
var indentation_spaces = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
var boolean_variable_natural_language_panel_comparison_equal = "is equal to";
var boolean_variable_natural_language_panel_comparison_not_equal = "is not equal to";
var boolean_variable_code_panel_comparison_equal = " == ";
var boolean_variable_code_panel_comparison_not_equal = " != ";

var timerMethodExecutionTimeIntervalInMiliSecond = 30000;
var idleTimeDurationCheckedValueInSecond = 568; 
var clientEndOperationCounter = 0;
var lastOperationExecutionTimeInSecond = 0;
var sessionRenewConfirmed = true;

var anchor_id_to_optionstype = {};
var anchor_id_to_options = {};

function set_anchor_id_to_optionstype(value)
{
    anchor_id_to_optionstype = value;
}
function get_anchor_id_to_optionstype()
{
    return anchor_id_to_optionstype;
}

function set_anchor_id_to_options(value)
{
    anchor_id_to_options = value;
}
function get_anchor_id_to_options()
{
    return anchor_id_to_options;
}

function set_server_base_url(base_url)
{
    server_base_url = base_url;
}
function get_server_base_url()
{
    return server_base_url;
}

/*
 * This method stores project variable list
 **/
function set_project_variables(variable_list)
{
    updateClientEndOperationCounter();
    var variable_counter = 0;
    for(var counter = 0 ; counter < variable_list.length ; counter++)
    {
        var variable = new Variable();
        variable.setVariableId(variable_list[counter].variable_id);
        variable.setVariableName(variable_list[counter].variable_name);
        variable.setVariableType(variable_list[counter].variable_type);
        variable.setVariableValue(variable_list[counter].variable_value);
        project_variable_list[variable_counter++] = variable;
    }
}
function get_project_variables()
{
    return project_variable_list;
}

function get_variable_info(variable_id)
{
    updateClientEndOperationCounter();
    var variable = new Variable();            
    for(var counter = 0 ; counter < project_variable_list.length ; counter++)
    {
        if(project_variable_list[counter].variable_id == variable_id)
        {
            variable.setVariableId(project_variable_list[counter].variable_id);
            variable.setVariableName(project_variable_list[counter].variable_name);
            variable.setVariableType(project_variable_list[counter].variable_type);
            variable.setVariableValue(project_variable_list[counter].variable_value);
        }
    }
    return variable;
}

function updateClientEndOperationCounter()
{
    clientEndOperationCounter++;
    var currentTime = new Date()
    lastOperationExecutionTimeInSecond = currentTime.getTime()/1000;
}

/*
 * Setting interval method
 **/
function trackUserOperation()
{
    clientEndOperationCounter = 0;
    setInterval(sessionTrackingTimerMethod, timerMethodExecutionTimeIntervalInMiliSecond)
}

/*
 * This methos will be executed on a regular interval based on variable "timerMethodExecutionTimeIntervalInMiliSecond" value 
 **/
function sessionTrackingTimerMethod()
{
    var currentTime = new Date();    
    var currentTimeInSecond = currentTime.getTime()/1000;
    var idleTimeDuration = currentTimeInSecond - lastOperationExecutionTimeInSecond;
    //alert("Idle time:"+(currentTimeInSecond - lastOperationExecutionTimeInSecond));
    if(clientEndOperationCounter <= 0)
    {
        if(idleTimeDuration > idleTimeDurationCheckedValueInSecond)
        {
            if(sessionRenewConfirmed)
            {
                $('#log_out_warning_div').dialog('open');
            }            
            //alert("Your sesssion will be expired within a minute if you are idle.");
        }  
        if(idleTimeDuration > idleTimeDurationCheckedValueInSecond + 30)
        {
            window.location.replace(server_base_url);
        }
    }
    else
    {
        //alert("clientEndOperationCounter:"+clientEndOperationCounter);
        
        clientEndOperationCounter = 0;
        $.ajax({
            type: "POST",
            url: "../../welcome/keep_server_alive",
            data: {                
            },
            success: function () { 
                //alert("Server alive.");
            }
        });
        //call ajax function
    }
}