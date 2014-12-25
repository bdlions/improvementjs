/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var feature_list = new Array();


var feature_list_counter = 0;
var parameter_list_counter = 0;
var allowed_values_list_counter = 0;
function load_xml()
{
    updateClientEndOperationCounter();
    feature_list = new Array();
    feature_list_counter = 0;
    $.get(project_xml_path, {}, function(xml)
    {
        $('feature', xml).each(function(i)
        {
            parameter_list_counter = 0;
            parameter_list = new Array();
            var feature_object = new Feature();
            feature_object.setOptions($(this).find("options").text());
            feature_object.setOptionsType($(this).find("optionstype").text());
            feature_object.setNatural($(this).find("natural").text());
            feature_object.setCode($(this).find("code").text());
            feature_object.setHelp($(this).find("help").text());
            
            var parameter_list = new Array();
            
            $('parameters', this).each(function(i)
            {
                $('parameter', this).each(function(i)
                {
                    
                    var allowed_values_list = new Array();
                    var interval_list = new Array();
                    
                    var parameter_object = new Parameter();
                    parameter_object.setName($(this).find("name").text());
                    parameter_object.setType($(this).find("type").text());
                    parameter_object.setAllowVar($(this).find("allowvar").text());
                    parameter_object.setDefaultValue($(this).find("default").text());
                    parameter_object.setAllowedValues(new Array());
                    parameter_object.setInterval(new Array());
                    
                    $('allowedvalues', this).each(function(i)
                    {
                        allowed_values_list = new Array();
                        allowed_values_list_counter = 0;
                        $('value', this).each(function(i)
                        {
                            allowed_values_list[allowed_values_list_counter++] = $(this).text();
                        });
                        if(allowed_values_list.length > 0)
                        {
                            parameter_object.setAllowedValues(allowed_values_list);
                        }
                    });
                    $('interval', this).each(function(i)
                    {
                        interval_list = new Array();
                        interval_list[0] = $(this).find("lowerlimit").text();
                        interval_list[1] = $(this).find("upperlimit").text();
                        parameter_object.setInterval(interval_list);
                    });
                    parameter_list[parameter_list_counter++] = parameter_object;
                    //parameter_list_counter++;
                });
            });
            feature_object.setParameterList(parameter_list);
            feature_list[feature_list_counter++] = feature_object;
        });   console.log(feature_list);     
    });
}
var project_name_list = new Array();
function set_project_name_list(pnl)
{
    updateClientEndOperationCounter();
    project_name_list = new Array();
    project_name_list = pnl;
    /*var counter = 0;
    for(counter = 0 ; counter < pnl.length ; counter++)
    {
        project_name_list[counter] = pnl[counter];
    }*/
    
}

var project_id_list = new Array();
function set_project_id_list(pil)
{
    updateClientEndOperationCounter();
    project_id_list = new Array();
    project_id_list = pil; 
}