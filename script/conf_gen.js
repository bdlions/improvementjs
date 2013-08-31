function getConfigFileContent()
{
    var final_xml = "";
    //setting the xml header
    var xml_header = '<?xml version="1.0"?>'+
                        '<features>';

    //setting the xml footer
    var xml_footer = '</features>';


    final_xml = final_xml + xml_header;

    var number_constant = "";

    //each constant list is a constant number
    $('#constant_list li').each(function(index)
    {
        var name, value;
        var input_tags = $(this).find('input');

        //each constant list li has two hidden input first one is name
        //another is value
        if(input_tags.length>=2)
        {
            name = input_tags[0].value;
            value = input_tags[1].value;
        }

        var feature  = "<feature>";
        feature = feature + "<options>number</options>";
        feature = feature + "<optionstype>Constant</optionstype>";
        feature = feature + "<natural>$"+name+"</natural>";
        feature = feature + "<code>$"+name+"</code>";
        feature = feature + "<help>http://www.help.com/constant</help>";

        var params =
                "<parameters>"+
			"<parameter>"+
				"<name>"+name+"</name>"+
				"<type>INTEGER</type>"+
				"<default>"+value+"</default>"+
			"</parameter>"+
		"</parameters>";
        feature = feature + params;
        feature = feature + "</feature>";

        number_constant = number_constant + feature;
    });
    final_xml = final_xml + number_constant;


    var number_decimal_constant = "";
    $('#decimal_constant_list li').each(function(index)
    {
        var name, value;
        var input_tags = $(this).find('input');
        if(input_tags.length>=2)
        {
            name = input_tags[0].value;
            value = input_tags[1].value;
        }

        var feature  = "<feature>";
        feature = feature + "<options>number</options>";
        feature = feature + "<optionstype>DecimalConstant</optionstype>";
        feature = feature + "<natural>$"+name+"</natural>";
        feature = feature + "<code>$"+name+"</code>";
        feature = feature + "<help>http://www.help.com/constant</help>";

        var params =
                "<parameters>"+
			"<parameter>"+
				"<name>"+name+"</name>"+
				"<type>REAL</type>"+
				"<default>"+value+"</default>"+
			"</parameter>"+
		"</parameters>";
        feature = feature + params;
        feature = feature + "</feature>";
        number_decimal_constant = number_decimal_constant + feature;
    });
    final_xml = final_xml + number_decimal_constant;


    var comparision =
        '<feature>'+
		'<options>comparison</options>'+
		'<optionstype>HigherThan</optionstype>'+
		'<natural>is higher than </natural>'+
		'<code> &gt; </code>'+
		'<help>http://www.help.com/higherThan</help>'+
	'</feature>'+
	'<feature>'+
		'<options>comparison</options>'+
		'<optionstype>LowerThan</optionstype>'+
		'<natural>is lower than </natural>'+
		'<code> &lt; </code>'+
		'<help></help>'	+
	'</feature>'+
	'<feature>'+
		'<options>comparison</options>'+
		'<optionstype>HigherThanOrEqualTo</optionstype>'+
		'<natural>is higher than or equal to </natural>'+
		'<code> &gt;= </code>'+
		'<help></help>'	+
	'</feature>'+
	'<feature>'+
		'<options>comparison</options>'+
		'<optionstype>LowerThanOrEqualTo</optionstype>'+
		'<natural>is lower than or equal to </natural>'+
		'<code> &lt;= </code>'+
		'<help></help>'+
	'</feature>'+
	'<feature>'+
		'<options>comparison</options>'+
		'<optionstype>EqualTo</optionstype>'+
		'<natural>is equal to </natural>'+
		'<code> == </code>'+
		'<help></help>'	+
	'</feature>';

    final_xml = final_xml + comparision;
    
    number_constant = "";
    //alert("basicFunctions length"+basicFunctions.length);
    var i = 0;
    var j = 0;
    var k = 0;
    for( i = 0; i < basicFunctions.length; i ++)
    {
        
        var feature  = "<feature>";
        feature = feature + "<options>basicfunction</options>";
        feature = feature + "<optionstype>"+basicFunctions[i].optionType+"</optionstype>";
        feature = feature + "<natural>"+basicFunctions[i].natural+"</natural>";
        feature = feature + "<code>"+basicFunctions[i].code+"</code>";
        feature = feature + "<help>http://www.help.com/constant</help>";
        
        if(basicFunctions[i].parameters.length > 0)
        {
            feature = feature + "<parameters>";
            for( j = 0; j < basicFunctions[i].parameters.length; j++)
            {
                feature = feature + "<parameter>";
                feature = feature + "<name>"+basicFunctions[i].parameters[j].name+"</name>";
                feature = feature + "<type>"+basicFunctions[i].parameters[j].type+"</type>";
                feature = feature + "<default>"+basicFunctions[i].parameters[j].default_val+"</default>";
                feature = feature + "</parameter>";
            }
            feature = feature + "</parameters>";
        }
        


        feature = feature + "</feature>";

        number_constant = number_constant + feature;
    }
    final_xml = final_xml + number_constant;
    
    var advanced_function = "";
    i = 0;
    j = 0;
    k = 0;
    //alert("advancedFunctions length"+advancedFunctions.length);
    for(i = 0; i < advancedFunctions.length; i ++)
    {
        
        var advanced_function_feature  = "<feature>";
        advanced_function_feature = advanced_function_feature + "<options>advancedfunction</options>";
        advanced_function_feature = advanced_function_feature + "<optionstype>"+advancedFunctions[i].optionType+"</optionstype>";
        advanced_function_feature = advanced_function_feature + "<natural>"+advancedFunctions[i].natural+"</natural>";
        advanced_function_feature = advanced_function_feature + "<code>"+advancedFunctions[i].code+"</code>";
        advanced_function_feature = advanced_function_feature + "<help>http://www.help.com/constant</help>";
        
        if(advancedFunctions[i].parameters.length > 0)
        {
            advanced_function_feature = advanced_function_feature + "<parameters>";
            for( j = 0; j < advancedFunctions[i].parameters.length; j++)
            {
                advanced_function_feature = advanced_function_feature + "<parameter>";
                advanced_function_feature = advanced_function_feature + "<name>"+advancedFunctions[i].parameters[j].name+"</name>";
                advanced_function_feature = advanced_function_feature + "<type>"+advancedFunctions[i].parameters[j].type+"</type>";
                //alert("allowed value length:"+advancedFunctions[i].parameters[j].allowed_values.length);
                //alert("interval length:"+advancedFunctions[i].parameters[j].interval.length);
                if(advancedFunctions[i].parameters[j].allowed_values.length >= 1)
                {
                    advanced_function_feature = advanced_function_feature + "<allowedvalues>";
                    for( k = 0 ; k < advancedFunctions[i].parameters[j].allowed_values.length ; k++)
                    {
                        advanced_function_feature = advanced_function_feature + "<value>"+advancedFunctions[i].parameters[j].allowed_values[k]+"</value>";                    
                    }
                    advanced_function_feature = advanced_function_feature + "</allowedvalues>";
                }
                
                if(advancedFunctions[i].parameters[j].interval.length == 2)
                {
                    advanced_function_feature = advanced_function_feature + "<interval>";
                    advanced_function_feature = advanced_function_feature + "<lowerlimit>"+advancedFunctions[i].parameters[j].interval[0]+"</lowerlimit>";
                    advanced_function_feature = advanced_function_feature + "<upperlimit>"+advancedFunctions[i].parameters[j].interval[1]+"</upperlimit>";
                    advanced_function_feature = advanced_function_feature + "</interval>";
                }
                
                advanced_function_feature = advanced_function_feature + "<default>"+advancedFunctions[i].parameters[j].default_val+"</default>";
                advanced_function_feature = advanced_function_feature + "</parameter>";
            }
            advanced_function_feature = advanced_function_feature + "</parameters>";
        }
        
        advanced_function_feature = advanced_function_feature + "</feature>";

        //alert("advanced_function_feature "+advanced_function_feature);

        advanced_function = advanced_function + advanced_function_feature;
    }
    final_xml = final_xml + advanced_function;
    
    var action = "";
    i = 0;
    j = 0;
    k = 0;
    //alert("action length"+actions.length);
    for(i = 0; i < actions.length; i ++)
    {
        
        var action_feature  = "<feature>";
        action_feature = action_feature + "<options>action</options>";
        action_feature = action_feature + "<optionstype>"+actions[i].optionType+"</optionstype>";
        action_feature = action_feature + "<natural>"+actions[i].natural+"</natural>";
        action_feature = action_feature + "<code>"+actions[i].code+"</code>";
        action_feature = action_feature + "<help>http://www.help.com/constant</help>";
        
        if(actions[i].parameters.length > 0)
        {
            action_feature = action_feature + "<parameters>";
            for( j = 0; j < actions[i].parameters.length; j++)
            {
                action_feature = action_feature + "<parameter>";
                action_feature = action_feature + "<name>"+actions[i].parameters[j].name+"</name>";
                action_feature = action_feature + "<type>"+actions[i].parameters[j].type+"</type>";
                //alert("allowed value length:"+actions[i].parameters[j].allowed_values.length);
                //alert("interval length:"+actions[i].parameters[j].interval.length);
                if(actions[i].parameters[j].allowed_values.length >= 1)
                {
                    action_feature = action_feature + "<allowedvalues>";
                    for( k = 0 ; k < actions[i].parameters[j].allowed_values.length ; k++)
                    {
                        action_feature = action_feature + "<value>"+actions[i].parameters[j].allowed_values[k]+"</value>";                    
                    }
                    action_feature = action_feature + "</allowedvalues>";
                }
                
                if(actions[i].parameters[j].interval.length == 2)
                {
                    action_feature = action_feature + "<interval>";
                    action_feature = action_feature + "<lowerlimit>"+actions[i].parameters[j].interval[0]+"</lowerlimit>";
                    action_feature = action_feature + "<upperlimit>"+actions[i].parameters[j].interval[1]+"</upperlimit>";
                    action_feature = action_feature + "</interval>";
                }
                
                action_feature = action_feature + "<default>"+actions[i].parameters[j].default_val+"</default>";
                action_feature = action_feature + "</parameter>";
            }
            action_feature = action_feature + "</parameters>";
        }
        
        action_feature = action_feature + "</feature>";

        //alert("action_feature "+action_feature);

        action = action + action_feature;
    }
    final_xml = final_xml + action;



    final_xml = final_xml + xml_footer;

    /*$('#constant_list li').each(function(index)
    {
        alert(index + ': ' + $(this).text());

    });*/

    return final_xml;
    
}