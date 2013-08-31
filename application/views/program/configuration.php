<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript"  src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript" src="<?php echo base_url()?>script/basic_function.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>script/advanced_function.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>script/action.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>script/parameters.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>script/conf_gen.js"></script>
<style type="text/css" >
    #program_selectable_item .ui-selecting,#configuration_item .ui-selecting,#type_list .ui-selecting,#number_type_list .ui-selecting {
        background:#FECA40;
    }

    #program_selectable_item .ui-selected,#configuration_item .ui-selected,#type_list .ui-selected,#number_type_list .ui-selected {
        background:#F39814;
        color:#FFF;
    }

    #program_selectable_item,#configuration_item,#type_list,#number_type_list {
        list-style-type:none;
        width:300px;
        margin:0;
        padding:0;
    }

    #program_selectable_item li,#configuration_item li,#type_list li,#number_type_list li {
        font-size:1.4em;
        height:18px;
        margin:3px;
        padding:.4em;
    }
</style>

<script type="text/javascript" >
    var basicFunctions = new Array();
    var advancedFunctions = new Array();
    var actions = new Array();

    $(function()
    {
        //$("#configuration_list").hide();
        $("#item_type").hide();
        //$("#add_variable").hide();
        $("#div_comparison_list").hide();
        $("#div_number_type").hide();
        $("#div_constant_value").hide();
        $("#div_decimal_constant_value").hide();
        $("#div_parameter_range").hide();
        $("#div_parameter_allowed_values").hide();
        $("#div_basic_function").hide();
        $("#div_advanced_function").hide();
        $("#div_advanced_function_parameter_range").hide();
        $("#div_advanced_function_parameter_allowed_values").hide();
        
        $("#div_action").hide();
        $("#div_action_parameter_range").hide();
        $("#div_action_parameter_allowed_values").hide();


        $("#parameter_types").val('0');
        $("#advanced_function_parameter_types").val('0');
        $("#action_parameter_types").val('0');

        $("#program_selectable_item").selectable(
        {
            selected: function (event, ui)
            {
                $(".ui-selected:first", this).each(function ()
                {
                    var item_value = $(this).text().trim();
                    
                    if(item_value == "Create Configuration")
                    {
                        $("#program").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#configuration_list").show("slide", { direction: "right" }, 500);
                        });
                    }
                    else if(item_value == "Upload Configuration File")
                    {
                    }
                });
            },
            unselected: function (event, ui)
            {

            }
        });

        $("#configuration_item").selectable(
        {
            selected: function (event, ui)
            {
                $(".ui-selected:first", this).each(function ()
                {
                    
                });
            },
            unselected: function (event, ui)
            {

            }
        });

        $("#number_type_list").selectable(
        {
            selected: function (event, ui)
            {
                $(".ui-selected:first", this).each(function ()
                {
                    var number_type = $(this).text().trim();

                    if(number_type == "Constant")
                    {
                        $("#div_number_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#div_constant_value").show("slide", { direction: "right" }, 500);
                        });
                    }
                    else if(number_type == "Decimal Constant")
                    {
                        $("#div_number_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#div_decimal_constant_value").show("slide", { direction: "right" }, 500);
                        });
                    }
                });
            },
            unselected: function (event, ui)
            {

            }
        });

        $("#type_list").selectable(
        {
            selected: function (event, ui)
            {
                $(".ui-selected:first", this).each(function ()
                {
                    var type_value = $(this).text().trim();

                    //when variable is selected as a type
                    /*if(type_value == "Variable")
                    {
                        $("#item_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#add_variable").show("slide", { direction: "right" }, 500);
                        });
                    }*/
                    //when Number is selected as a type
                    if(type_value == "Number")
                    {
                        $("#item_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#div_number_type").show("slide", { direction: "right" }, 500);
                        });
                    }
                    //when Comparison is selected as a type
                    else if(type_value == "Comparison")
                    {
                        $("#item_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#div_comparison_list").show("slide", { direction: "right" }, 500);
                        });
                    }
                    //when basic function is selected as a type
                    else if(type_value == "Basic Function")
                    {
                        $("#item_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#div_basic_function").show("slide", { direction: "right" }, 500);
                        });
                    }
                    
                    //when advanced function is selected as a type
                    else if(type_value == "Advanced Function")
                    {
                        $("#item_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#div_advanced_function").show("slide", { direction: "right" }, 500);
                        });
                    }
                    
                    //when advanced function is selected as a type
                    else if(type_value == "Action")
                    {
                        $("#item_type").hide("slide", { direction: "left" }, 500, function ()
                        {
                            $("#div_action").show("slide", { direction: "right" }, 500);
                        });
                    }

                });
            },
            unselected: function (event, ui)
            {

            }
        });


        <!-- All function event handler here -->

        $("#button_add_item").click(function ()
        {
            $("#configuration_list").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });
        });
        
        /*$("#button_add_variable").click(function ()
        {
            var variable_name = $("#input_variable_name").val();
            if(variable_name == "")
            {
                alert("Please input a valid variable name.");
                return;
            }
            $("#variable_list").append('<li>'+variable_name+'</li>');
            //$("#configuration_item").append('<li class="ui-widget-content">'+variable_name+'</li>');
        });

        $("#button_add_variable_ok").click(function ()
        {
            $("#add_variable").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });
        });*/

        $("#button_comparision_list_ok").click(function ()
        {
            $("#div_comparison_list").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });
        });

        $("#button_number_type_ok").click(function ()
        {
            $("#div_number_type").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });
        });

        $("#button_add_constant").click(function ()
        {
            var constant_name = $("#input_constant_name").val();
            var constant_value = $("#input_constant_value").val();

            if(constant_name == "")
            {
                alert("Please input a valid constant name.");
                return;
            }
            var constant_info = "<li id='constant_'"+constant_name+">Name: "+constant_name+" Value: "+constant_value+
                                "<input type ='hidden' value='"+$("#input_constant_name").val()+"'/>"+
                                "<input type ='hidden' value='"+$("#input_constant_value").val()+"'/>"+"</li>";

            $("#constant_list").append(constant_info);
            $("#configuration_item").append('<li class="ui-widget-content">'+constant_name+'</li>');
        });

        $("#button_add_decimal_constant").click(function ()
        {
            var decimal_constant_name = $("#input_decimal_constant_name").val();
            var decimal_constant_value = $("#input_decimal_constant_value").val();

            if(decimal_constant_value == "")
            {
                alert("Please input a valid decimal constant name.");
                return;
            }

            var decimal_constant_info =
                                "<li id='decimal_constant_'"+decimal_constant_name+">Name: "+decimal_constant_name+" Value: "+decimal_constant_value+
                                "<input type ='hidden' value='"+$("#input_decimal_constant_name").val()+"'/>"+
                                "<input type ='hidden' value='"+$("#input_decimal_constant_value").val()+"'/>"+"</li>";

            $("#decimal_constant_list").append(decimal_constant_info);

            $("#configuration_item").append('<li class="ui-widget-content">'+decimal_constant_name+'</li>');
        });

        $("#button_decimal_constant_list_ok").click(function ()
        {
            $("#div_decimal_constant_value").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#div_number_type").show("slide", { direction: "right" }, 500);
            });
        });

        $("#button_constant_list_ok").click(function ()
        {
            $("#div_constant_value").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#div_number_type").show("slide", { direction: "right" }, 500);
            });
        });
        $("#button_add_range_value").click(function ()
        {
            var parameter_name = $("#parameter_name").val();
            if(parameter_name == "")
            {
                alert("Please input a valid parameter name.");
                return;
            }
            var param = "<li> Parameter Type: "+$("#parameter_types").val()+" Parameter Name: "+parameter_name+" Parameter Default Value: "+$("#parameter_default").val()+
                    "<input type ='hidden' value='"+$("#parameter_name").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#parameter_types").val()+"'/>"+
                    "<input type='hidden' value='>"+$("#parameter_default").val()+"'/>"+
                "</li>";
            $("#parameter_number_list").append(param);
           
        });
        
        $("#button_add_advanced_function_range_value").click(function ()
        {
            var parameter_name = $("#advanced_function_parameter_name").val();
            if(parameter_name == "")
            {
                alert("Please input a valid parameter name.");
                return;
            }
            var param = "<li>"+$("#advanced_function_parameter_types").val()+"-"+parameter_name+"(Lowerlimit "+$("#advanced_function_parameter_range_lowest").val()+" Upperlimit "+$("#advanced_function_parameter_range_highest").val()+" Default "+$("#advanced_function_parameter_default").val()+")"+
                    "<input type ='hidden' value='"+$("#advanced_function_parameter_name").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#advanced_function_parameter_types").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#advanced_function_parameter_range_lowest").val()+"'/>"+
                    "<input type='hidden' value='"+$("#advanced_function_parameter_range_highest").val()+"'/>"+
                    "<input type='hidden' value='>"+$("#advanced_function_parameter_default").val()+"'/>"+
                "</li>";
            $("#parameter_advanced_function_number_list").append(param);
           
        });
        
        $("#button_add_action_range_value").click(function ()
        {
            var parameter_name = $("#action_parameter_name").val();
            if(parameter_name == "")
            {
                alert("Please input a valid parameter name.");
                return;
            }
            var param = "<li>"+$("#action_parameter_types").val()+"-"+parameter_name+"(Lowerlimit "+$("#action_parameter_range_lowest").val()+" Upperlimit "+$("#action_parameter_range_highest").val()+" Default "+$("#action_parameter_default").val()+")"+
                    "<input type ='hidden' value='"+$("#action_parameter_name").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#action_parameter_types").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#action_parameter_range_lowest").val()+"'/>"+
                    "<input type='hidden' value='"+$("#action_parameter_range_highest").val()+"'/>"+
                    "<input type='hidden' value='>"+$("#action_parameter_default").val()+"'/>"+
                "</li>";
            $("#parameter_action_number_list").append(param);
           
        });

        $("#button_add_allowed_value").click(function ()
        {
            var parameter_name = $("#input_allowed_text").val();
            var default_value = $("#input_basic_function_text_parameter_default").val();
            
            if(parameter_name == "" || default_value == "")
            {
                alert("Please input a valid parameter name or default value.");
                return;
            }
            var param;

            /*if($("#parameter_text_"+parameter_name).val() == undefined)
            {
                param = "<li id='parameter_text_"+parameter_name+"'>"+
                    $("#parameter_types").val()+"-"+parameter_name+"<br/>"+
                    "<input type ='text' disabled='disabled' value='"+$("#input_allowed_value").val()+"'/>"+
                "</li>";
                $("#parameter_allowed_value_list").append(param);
                
            }
            else
            {
                $("#parameter_text_"+parameter_name).append("<input type ='text' disabled='disabled' value='"+$("#input_allowed_value").val()+"'/>");
            }*/
            
            param = "<li id='basic_function_parameter_text'>"+
                    "Parameter type: "+$("#parameter_types").val()+" Parameter name: "+parameter_name+" Default: "+default_value+"<br/>"+
                    "<input type ='hidden' value='"+$("#parameter_types").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#input_allowed_text").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#input_basic_function_text_parameter_default").val()+"'/>"+
                    "</li>";
            $("#parameter_allowed_value_list").append(param);
        });
        
        $("#button_advanced_function_add_allowed_value").click(function ()
        {
            var parameter_name = $("#input_advanced_function_allowed_text").val();
            var default_value = $("#input_advanced_function_text_parameter_default").val();
            if(parameter_name == "" || default_value == "")
            {
                alert("Please input a valid parameter name or default value.");
                return;
            }
            var allowed_values = $("#input_advanced_function_allowed_value").val();
            if(allowed_values == "")
            {
                alert("Please assign allowed values.");
                return;
            }
            var allowed_value_array = allowed_values.split(";");
            var allowed_value_tags = "";
            var allowed_value_list = "";
            for(var counter = 0 ; counter < allowed_value_array.length ; counter++)
            {
                allowed_value_tags = allowed_value_tags + "<input type ='hidden' value='"+allowed_value_array[counter]+"'/>";
                allowed_value_list = allowed_value_list + allowed_value_array[counter] + " ";
            }
            var param;

            /*if($("#advanced_function_parameter_text_"+parameter_name).val() == undefined)
            {
                param = "<li id='advanced_function_parameter_text_"+parameter_name+"'>"+
                    "Parameter type: "+$("#advanced_function_parameter_types").val()+" Parameter name: "+parameter_name+" Default: "+default_value+"<br/>"+
                    "<input type ='text' disabled='disabled' value='"+$("#input_advanced_function_allowed_value").val()+"'/>"+
                "</li>";
                $("#parameter_advanced_function_allowed_value_list").append(param);
                
            }
            else
            {
                $("#advanced_function_parameter_text_"+parameter_name).append("<input type ='text' disabled='disabled' value='"+$("#input_advanced_function_allowed_value").val()+"'/>");
            }*/
            param = "<li id='advanced_function_parameter_text'>"+
                    "Parameter type: "+$("#advanced_function_parameter_types").val()+" Parameter name: "+parameter_name+" Default: "+default_value+" Allowed values : "+allowed_value_list+"<br/>"+
                    "<input type ='hidden' value='"+$("#advanced_function_parameter_types").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#input_advanced_function_allowed_text").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#input_advanced_function_text_parameter_default").val()+"'/>"+
                    allowed_value_tags+"</li>";
            $("#parameter_advanced_function_allowed_value_list").append(param);
        });
        
        $("#button_action_add_allowed_value").click(function ()
        {
            var parameter_name = $("#input_action_allowed_text").val();
            var default_value = $("#input_action_text_parameter_default").val();
            if(parameter_name == "" || default_value == "")
            {
                alert("Please input a valid parameter name or default value.");
                return;
            }
            var allowed_values = $("#input_action_allowed_value").val();
            if(allowed_values == "")
            {
                alert("Please assign allowed values.");
                return;
            }
            var allowed_value_array = allowed_values.split(";");
            var allowed_value_tags = "";
            var allowed_value_list = "";
            for(var counter = 0 ; counter < allowed_value_array.length ; counter++)
            {
                allowed_value_tags = allowed_value_tags + "<input type ='hidden' value='"+allowed_value_array[counter]+"'/>";
                allowed_value_list = allowed_value_list + allowed_value_array[counter] + " ";
            }
            var param;
            
            param = "<li id='action_parameter_text'>"+
                    "Parameter type: "+$("#action_parameter_types").val()+" Parameter name: "+parameter_name+" Default: "+default_value+" Allowed values : "+allowed_value_list+"<br/>"+
                    "<input type ='hidden' value='"+$("#action_parameter_types").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#input_action_allowed_text").val()+"'/>"+
                    "<input type ='hidden' value='"+$("#input_action_text_parameter_default").val()+"'/>"+
                    allowed_value_tags+"</li>";
            $("#parameter_action_allowed_value_list").append(param);
        });

        $("#parameter_types").change(function ()
        {
            if($("#parameter_types").val() == "Integer" || $("#parameter_types").val() == "Real")
            {
                $("#div_parameter_allowed_values").hide();
                $("#div_parameter_range").show();
            }
            else if($("#parameter_types").val() == "Text")
            {
                $("#div_parameter_range").hide();
                $("#div_parameter_allowed_values").show();
            }
            else
            {
                $("#div_parameter_range").hide();
                $("#div_parameter_allowed_values").hide();
            }
        });
        
        $("#advanced_function_parameter_types").change(function ()
        {
            if($("#advanced_function_parameter_types").val() == "Integer" || $("#advanced_function_parameter_types").val() == "Real")
            {
                $("#div_advanced_function_parameter_allowed_values").hide();
                $("#div_advanced_function_parameter_range").show();
            }
            else if($("#advanced_function_parameter_types").val() == "Text")
            {
                $("#div_advanced_function_parameter_range").hide();
                $("#div_advanced_function_parameter_allowed_values").show();
            }
            else
            {
                $("#div_advanced_function_parameter_range").hide();
                $("#div_advanced_function_parameter_allowed_values").hide();
            }
        });
        
        $("#action_parameter_types").change(function ()
        {
            if($("#action_parameter_types").val() == "Integer" || $("#action_parameter_types").val() == "Real")
            {
                $("#div_action_parameter_allowed_values").hide();
                $("#div_action_parameter_range").show();
            }
            else if($("#action_parameter_types").val() == "Text")
            {
                $("#div_action_parameter_range").hide();
                $("#div_action_parameter_allowed_values").show();
            }
            else
            {
                $("#div_action_parameter_range").hide();
                $("#div_action_parameter_allowed_values").hide();
            }
        });

        $("#button_basic_function_ok").click(function()
        {
            $("#div_basic_function").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });

            $("#configuration_item").append('<li class="ui-widget-content">'+$("#basic_function_natural").val()+'</li>');

            var basicFunction = new BasicFunction();
            basicFunction.optionType = $('#basic_function_option_type').val();
            basicFunction.code = $('#basic_function_code').val();
            basicFunction.natural = $('#basic_function_natural').val();
            
            $('#parameter_number_list li').each(function(index)
            {
                var parameter_name, parameter_type, default_value;
                var input_tags = $(this).find('input');

                if(input_tags.length>=2)
                {
                    parameter_name = input_tags[0].value;
                    parameter_type = input_tags[1].value;
                    default_value = input_tags[2].value;
                    
                    var parameter = new Parameter();
                    parameter.name = parameter_name;
                    parameter.type = parameter_type;
                    parameter.default_val = default_value;
                    
                    basicFunction.parameters.push(parameter);
                }
            });
            $('#parameter_allowed_value_list li').each(function(index)
            {
                var input_tags = $(this).find('input');
                //alert("input_tags length : "+input_tags.length);
                if(input_tags.length == 3)
                {                    
                    var parameter = new Parameter();                    
                    for(var counter = 0; counter < input_tags.length ; counter++)
                    {
                        if(counter == 0)
                        {
                            parameter.type = input_tags[counter].value;
                        }
                        else if(counter == 1)
                        {
                            parameter.name = input_tags[counter].value;
                        }
                        else if(counter == 2)
                        {
                            parameter.default_val = input_tags[counter].value;
                        }                        
                    }
                    //alert("type:"+parameter.type+"name:"+parameter.name+"default_val:"+parameter.default_val);
                    basicFunction.parameters.push(parameter);
                }
            });

            basicFunctions.push(basicFunction);


            alert("A basic function is successfully added.");
            
            //$("#div_basic_function_list").append('<ul>'+"Option Type:"+$("#basic_function_option_type").val()+" , Code:"+$("#basic_function_code").val()+" , Natural:"+$("#basic_function_natural").val()+$("#parameter_number_list").html()+'</ul>');
            $("#parameter_number_list").html("");
        });
        
        $("#button_advanced_function_ok").click(function()
        {
            $("#div_advanced_function").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });

            $("#configuration_item").append('<li class="ui-widget-content">'+$("#advanced_function_natural").val()+'</li>');

            var advancedFunction = new AdvancedFunction();
            advancedFunction.optionType = $('#advanced_function_option_type').val();
            advancedFunction.code = $('#advanced_function_code').val();
            advancedFunction.natural = $('#advanced_function_natural').val();
            
            $('#parameter_advanced_function_number_list li').each(function(index)
            {
                var parameter_name, parameter_type, default_value, lower_limit, upper_limit;
                var input_tags = $(this).find('input');

                if(input_tags.length>=2)
                {
                    parameter_name = input_tags[0].value;
                    parameter_type = input_tags[1].value;
                    lower_limit = input_tags[2].value;
                    upper_limit = input_tags[3].value;
                    default_value = input_tags[4].value;
                    
                    var parameter = new Parameter();
                    parameter.name = parameter_name;
                    parameter.type = parameter_type;
                    parameter.default_val = default_value;
                    
                    parameter.interval.push(lower_limit);
                    parameter.interval.push(upper_limit);
                    
                    advancedFunction.parameters.push(parameter);
                }
            });
            
            
            $('#parameter_advanced_function_allowed_value_list li').each(function(index)
            {
                var input_tags = $(this).find('input');
                //alert("input_tags length : "+input_tags.length);
                if(input_tags.length>=3)
                {                    
                    var parameter = new Parameter();                    
                    for(var counter = 0; counter < input_tags.length ; counter++)
                    {
                        if(counter == 0)
                        {
                            parameter.type = input_tags[counter].value;
                        }
                        else if(counter == 1)
                        {
                            parameter.name = input_tags[counter].value;
                        }
                        else if(counter == 2)
                        {
                            parameter.default_val = input_tags[counter].value;
                        }
                        else
                        {
                            parameter.allowed_values.push(input_tags[counter].value);
                        }
                    }
                    //alert("allowed value list:"+parameter.allowed_values);
                    advancedFunction.parameters.push(parameter);
                }
            });
            
            //alert("parameter length:"+advancedFunction.parameters.length);
            advancedFunctions.push(advancedFunction);
            //alert("advancedFunctions length:"+advancedFunctions.length);
            alert("An advanced function is successfully added");
            //resetting parameter list of currently created advanced parameter
            $("#parameter_advanced_function_number_list").html("");
            $("#parameter_advanced_function_allowed_value_list").html("");
        });
        
        $("#button_action_ok").click(function()
        {
            $("#div_action").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });

            $("#configuration_item").append('<li class="ui-widget-content">'+$("#action_natural").val()+'</li>');

            var action = new Action();
            action.optionType = $('#action_option_type').val();
            action.code = $('#action_code').val();
            action.natural = $('#action_natural').val();
            
            $('#parameter_action_number_list li').each(function(index)
            {
                var parameter_name, parameter_type, default_value, lower_limit, upper_limit;
                var input_tags = $(this).find('input');

                if(input_tags.length>=2)
                {
                    parameter_name = input_tags[0].value;
                    parameter_type = input_tags[1].value;
                    lower_limit = input_tags[2].value;
                    upper_limit = input_tags[3].value;
                    default_value = input_tags[4].value;
                    
                    var parameter = new Parameter();
                    parameter.name = parameter_name;
                    parameter.type = parameter_type;
                    parameter.default_val = default_value;
                    
                    parameter.interval.push(lower_limit);
                    parameter.interval.push(upper_limit);
                    
                    action.parameters.push(parameter);
                }
            });
            
            
            $('#parameter_action_allowed_value_list li').each(function(index)
            {
                var input_tags = $(this).find('input');
                //alert("input_tags length : "+input_tags.length);
                if(input_tags.length>=3)
                {                    
                    var parameter = new Parameter();                    
                    for(var counter = 0; counter < input_tags.length ; counter++)
                    {
                        if(counter == 0)
                        {
                            parameter.type = input_tags[counter].value;
                        }
                        else if(counter == 1)
                        {
                            parameter.name = input_tags[counter].value;
                        }
                        else if(counter == 2)
                        {
                            parameter.default_val = input_tags[counter].value;
                        }
                        else
                        {
                            parameter.allowed_values.push(input_tags[counter].value);
                        }
                    }
                    //alert("allowed value list:"+parameter.allowed_values);
                    action.parameters.push(parameter);
                }
            });
            
            actions.push(action);
            alert("An action is successfully added");
            //resetting parameter list of currently created action parameter
            $("#parameter_action_number_list").html("");
            $("#parameter_action_allowed_value_list").html("");
        });

        $("#button_item_type_ok").click(function()
        {
            $("#item_type").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#configuration_list").show("slide", { direction: "right" }, 500);
            });
        });
        
        $("#button_basic_function_back").click(function()
        {
            $("#div_basic_function").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });
        });
        $("#button_advanced_function_back").click(function()
        {
            $("#div_advanced_function").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });
        });
        $("#button_action_back").click(function()
        {
            $("#div_action").hide("slide", { direction: "left" }, 500, function ()
            {
                $("#item_type").show("slide", { direction: "right" }, 500);
            });
        });

    });
</script>

<fieldset>
    <legend>Configure The new Program</legend>

    <!--First Step-->
    <!--<div class="program" id="program">
        <h3>Select one option from the below</h3>
        <form id="formname" name="formname"  method="post" enctype="multipart/form-data">
        <ol id="program_selectable_item">
            <li class="ui-widget-content">Create Configuration</li>
            <li class="ui-widget-content">Upload Configuration File</li>
        </ol>
        </form>    
    </div>-->


    <!--Second Step-->
    <div class="configuration_list" id="configuration_list">
        <?php echo form_open("welcome/save_project_configuration_file");?>
        <h3>List of configured Items: </h3>
        <ol id="configuration_item">
        </ol>
        <input type="hidden" name="file_content" id="file_content"/>
        <input name="button_add_item" id="button_add_item" type="button" value="Add" />
        <input name="button_delete_item" id="button_delete_item" type="button"  value="Delete"/>
        <input name="button_generate_xml" id="button_generate_xml" type="button" value="Show Config" onclick="alert(getConfigFileContent())" />
        <input name="button_generate_xml" id="button_generate_xml" type="submit" value="Save File" onclick="document.getElementById('file_content').value = getConfigFileContent()" />
        <?php echo form_close();?>
    </div>

    <!--Third Step-->
    <div class="item_type" id="item_type">
        <h3>Configured Item types: </h3>
        <ol id="type_list">
            <!--<li class="ui-widget-content">Variable</li>-->
            <li class="ui-widget-content">Number</li>
            <li class="ui-widget-content">Comparison</li>
            <li class="ui-widget-content">Basic Function</li>
            <li class="ui-widget-content">Advanced Function</li>
            <li class="ui-widget-content">Action</li>
        </ol>
        <input name="button_item_type_ok" id="button_item_type_ok" type="button"  value="Back"/>
    </div>
    
    <!--4th Step-->
    <!--<div class="add_variable" id="add_variable">
        <ol id="variable_list">

        </ol>
        <label >Variable Name: </label><input type="text" name="input_variable_name" id="input_variable_name"/><br/>
        <input name="button_add_variable" id="button_add_variable" type="button" value="Add" />
        <input name="button_add_variable_ok" id="button_add_variable_ok" type="button"  value="Ok"/>
    </div>-->


    <div class="div_comparison_list" id="div_comparison_list">
        <ol id="comparision_list">
            <li><</li>
            <li>></li>
            <li><=</li>
            <li>>=</li>
            <li>==</li>
            <li>!=</li>
        </ol>
        <input name="button_comparision_list_ok" id="button_comparision_list_ok" type="button"  value="Ok"/>
    </div>
    
    <div class="div_number_type" id="div_number_type">
         <ol id="number_type_list">
            <li class="ui-widget-content">Constant</li>
            <li class="ui-widget-content">Decimal Constant</li>
        </ol>
        <input name="button_number_type_ok" id="button_number_type_ok" type="button"  value="Back"/>
    </div>

    <div class="div_constant_value" id="div_constant_value">
        <ol id="constant_list">

        </ol>

        <table width="100%" border="0">
          <tr>
            <td width="16%"><label >Constant Name: </label></td>
            <td width="84%"> <input type="text" name="input_constant_name" id="input_constant_name"/></td>
          </tr>
          <tr>
            <td width="16%"><label >Constant Value: </label></td>
            <td width="84%"> <input type="text" name="input_constant_value" id="input_constant_value"/></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>
                <input name="button_add_constant" id="button_add_constant" type="button" value="Add" />
                <input name="button_constant_list_ok" id="button_constant_list_ok" type="button"  value="Back"/>
            </td>
          </tr>
        </table>

        
    </div>

    <div class="div_decimal_constant_value" id="div_decimal_constant_value">
        <ol id="decimal_constant_list">

        </ol>
        <table width="100%" border="0">
          <tr>
            <td width="16%"><label >Decimal Constant Name: </label></td>
            <td width="84%"> <input type="text" name="input_decimal_constant_name" id="input_decimal_constant_name"/></td>
          </tr>
          <tr>
            <td width="16%"><label >Decimal Constant Value: </label></td>
            <td width="84%"> <input type="text" name="input_decimal_constant_value" id="input_decimal_constant_value"/></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="button_add_decimal_constant" id="button_add_decimal_constant" type="button" value="Add" />
        		<input name="button_decimal_constant_list_ok" id="button_decimal_constant_list_ok" type="button"  value="Back"/></td>
          </tr>
        </table>

        
    </div>

    <div class="div_basic_function" id="div_basic_function">
        
        <div id="div_basic_function_list">
            
        </div>
        
        <table width="100%" border="0">
          <tr>
            <td width="13%"><label >Option Type: </label></td>
            <td width="87%">
                <input type="text" id="basic_function_option_type" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Natural: </label></td>
            <td width="87%">
            	<input type="text" id="basic_function_natural" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Code: </label></td>
            <td width="87%">
            	<input type="text" id="basic_function_code" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Parameter Types: </label></td>
            <td width="87%">
            	<select name="parameter_types" id="parameter_types">
                    <option value="0" selected>(please select:)</option>
                    <option value="Integer">Integer</option>
                    <option value="Real">Real</option>
                    <option value="Text">Text</option>
                </select>
            </td>
          </tr>
        </table>

        <div id="div_parameter_range">
            <ul id="parameter_number_list">

            </ul>
            <table width="100%" border="0">
              <tr>
                <td width="13%"><label>Parameter Name</label></td>
                <td width="87%">
                    <input type="text" id="parameter_name"/>
                </td>
              </tr>
              <tr>
                <td><label>Default</label></td>
                <td><input type="text" id="parameter_default"/></td>
              </tr>
              <tr>
                <td></td>
                <td><input name="button_add_range_value" id="button_add_range_value" type="button" value="Add value" /></td>
              </tr>
            </table>
        </div>

        <div id="div_parameter_allowed_values">
            <ul id="parameter_allowed_value_list">
                
            </ul>
             <table width="100%" border="0">
              <tr>
                <td width="13%"><label>Parameter Name: </label></td>
                <td width="87%">
                   <input type="text" id="input_allowed_text" />
                </td>
              </tr>
              <tr>
                <td width="13%"><label>Default: </label></td>
                <td width="87%">
                   <input type="text" id="input_basic_function_text_parameter_default"/>
                </td>
              </tr>
              <tr>
                <td></td>
                <td><input name="button_add_allowed_value" id="button_add_allowed_value" type="button" value="Add value" /></td>
              </tr>
            </table>
        </div>
        <input name="button_basic_function_ok" id="button_basic_function_ok" type="button"  value="Ok"/>
        <input name="button_basic_function_back" id="button_basic_function_back" type="button"  value="Back"/>
    </div>
    
    <div class="div_advanced_function" id="div_advanced_function">
        
        <div id="div_advanced_function_list">
            
        </div>
        
        <table width="100%" border="0">
          <tr>
            <td width="13%"><label >Option Type: </label></td>
            <td width="87%">
                <input type="text" id="advanced_function_option_type" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Natural: </label></td>
            <td width="87%">
            	<input type="text" id="advanced_function_natural" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Code: </label></td>
            <td width="87%">
            	<input type="text" id="advanced_function_code" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Parameter Types: </label></td>
            <td width="87%">
            	<select name="advanced_function_parameter_types" id="advanced_function_parameter_types">
                    <option value="0" selected>(please select:)</option>
                    <option value="Integer">Integer</option>
                    <option value="Real">Real</option>
                    <option value="Text">Text</option>
                </select>
            </td>
          </tr>
        </table>

        <div id="div_advanced_function_parameter_range">
            <ul id="parameter_advanced_function_number_list">

            </ul>
            <table width="100%" border="0">
              <tr>
                <td width="13%"><label>Parameter Name</label></td>
                <td width="87%">
                    <input type="text" id="advanced_function_parameter_name"/>
                </td>
              </tr>
              <tr>
                <td width="13%"><label>Lowest</label></td>
                <td width="87%">
                    <input type="text" id="advanced_function_parameter_range_lowest"/>
                </td>
              </tr>
              <tr>
                <td><label>Highest</label></td>
                <td><input type="text" id="advanced_function_parameter_range_highest"/></td>
              </tr>
              <tr>
                <td><label>Default</label></td>
                <td><input type="text" id="advanced_function_parameter_default"/></td>
              </tr>
              <tr>
                <td></td>
                <td><input name="button_add_advanced_function_range_value" id="button_add_advanced_function_range_value" type="button" value="Add value" /></td>
              </tr>
            </table>
        </div>

        <div id="div_advanced_function_parameter_allowed_values">
            <ul id="parameter_advanced_function_allowed_value_list">
                
            </ul>
             <table width="100%" border="0">
              <tr>
                <td width="13%"><label>Parameter Name: </label></td>
                <td width="87%">
                   <input type="text" id="input_advanced_function_allowed_text" />
                </td>
              </tr>              
              <tr>
                <td width="13%"><label>Default: </label></td>
                <td width="87%">
                   <input type="text" id="input_advanced_function_text_parameter_default"/>
                </td>
              </tr>
              <tr>
                <td width="13%"><label>Allowed value: </label></td>
                <td width="87%">
                   <input type="text" id="input_advanced_function_allowed_value"/>
                </td>                
              </tr>
              <tr>
                 <td></td>
                 <td><label>Insert multiple allowed values separated by ; </label></td>                 
              </tr>
              <tr>
                <td></td>
                <td><input name="button_advanced_function_add_allowed_value" id="button_advanced_function_add_allowed_value" type="button" value="Add Text Parameter" /></td>
              </tr>
            </table>
        </div>
        <input name="button_advanced_function_ok" id="button_advanced_function_ok" type="button"  value="Ok"/>
        <input name="button_advanced_function_back" id="button_advanced_function_back" type="button"  value="Back"/> 
    </div>
    
    <div class="div_action" id="div_action">
        
        <div id="div_action_list">
            
        </div>
        
        <table width="100%" border="0">
          <tr>
            <td width="13%"><label >Option Type: </label></td>
            <td width="87%">
                <input type="text" id="action_option_type" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Natural: </label></td>
            <td width="87%">
            	<input type="text" id="action_natural" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Code: </label></td>
            <td width="87%">
            	<input type="text" id="action_code" />
            </td>
          </tr>
          <tr>
            <td width="13%"><label >Parameter Types: </label></td>
            <td width="87%">
            	<select name="action_parameter_types" id="action_parameter_types">
                    <option value="0" selected>(please select:)</option>
                    <option value="Integer">Integer</option>
                    <option value="Real">Real</option>
                    <option value="Text">Text</option>
                </select>
            </td>
          </tr>
        </table>

        <div id="div_action_parameter_range">
            <ul id="parameter_action_number_list">

            </ul>
            <table width="100%" border="0">
              <tr>
                <td width="13%"><label>Parameter Name</label></td>
                <td width="87%">
                    <input type="text" id="action_parameter_name"/>
                </td>
              </tr>
              <tr>
                <td width="13%"><label>Lowest</label></td>
                <td width="87%">
                    <input type="text" id="action_parameter_range_lowest"/>
                </td>
              </tr>
              <tr>
                <td><label>Highest</label></td>
                <td><input type="text" id="action_parameter_range_highest"/></td>
              </tr>
              <tr>
                <td><label>Default</label></td>
                <td><input type="text" id="action_parameter_default"/></td>
              </tr>
              <tr>
                <td></td>
                <td><input name="button_add_action_range_value" id="button_add_action_range_value" type="button" value="Add value" /></td>
              </tr>
            </table>
        </div>

        <div id="div_action_parameter_allowed_values">
            <ul id="parameter_action_allowed_value_list">
                
            </ul>
             <table width="100%" border="0">
              <tr>
                <td width="13%"><label>Parameter Name: </label></td>
                <td width="87%">
                   <input type="text" id="input_action_allowed_text" />
                </td>
              </tr>              
              <tr>
                <td width="13%"><label>Default: </label></td>
                <td width="87%">
                   <input type="text" id="input_action_text_parameter_default"/>
                </td>
              </tr>
              <tr>
                <td width="13%"><label>Allowed value: </label></td>
                <td width="87%">
                   <input type="text" id="input_action_allowed_value"/>
                </td>                
              </tr>
              <tr>
                 <td></td>
                 <td><label>Insert multiple allowed values separated by ; </label></td>                 
              </tr>
              <tr>
                <td></td>
                <td><input name="button_action_add_allowed_value" id="button_action_add_allowed_value" type="button" value="Add Text Parameter" /></td>
              </tr>
            </table>
        </div>
        <input name="button_action_ok" id="button_action_ok" type="button"  value="Ok"/>
        <input name="button_action_back" id="button_action_back" type="button"  value="Back"/> 
    </div>
</fieldset>