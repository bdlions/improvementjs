var template_service_url_c = "";
var template_service_url_java = "";
var template_service_condition_url = "";
var template_service_action_url = "";

var project_xml_path = "";

var language_id_c = 1;
var language_id_c_title = "C";
var language_id_java = 2;
var language_id_java_title = "Java";
var selected_language_id = 1;

var current_user_type = "";
var user_type_member = "";
var user_type_demo = "";

var maximum_if_per_project = 0;



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
var sessionExpirationTime = 600; 
var showWarningBeforeSessionExpiration = 30;
var sesstionExpirationThreshHold = 5;
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
    console.log("idleTimeDuration"+idleTimeDuration);
    console.log("clientEndOperationCounter"+clientEndOperationCounter);
    if(clientEndOperationCounter <= 0)
    {
        if(idleTimeDuration > (sessionExpirationTime - showWarningBeforeSessionExpiration - sesstionExpirationThreshHold) )
        {
            if(sessionRenewConfirmed)
            {
                $('#modal_log_out_warning').modal('show');
            }  
        }  
        if(idleTimeDuration > sessionExpirationTime)
        {
            window.location.replace(server_base_url);
        }
    }
    else
    {
        clientEndOperationCounter = 0;
        $.ajax({
            type: "POST",
            url: server_base_url+"general_process/keep_server_alive",
            data:{                
            },
            success: function () { 
                
            }
        });
    }
}

/*
 * This method will return title string to be displayed code modal window
 * @returns {String}
 */
function get_generated_code_title()
{
    var language_title = "Code ";
    if(selected_language_id === language_id_c)
    {
        language_title = language_title + "(" + language_id_c_title + ")";
    }
    else if(selected_language_id === language_id_java)
    {
        language_title = language_title + "(" + language_id_java_title + ")";
    }
    return language_title;
}