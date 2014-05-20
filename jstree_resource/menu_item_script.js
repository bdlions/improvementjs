var _gaq = [
            ['_setAccount', 'UA-7409939-1'],
            ['_trackPageview']
        ];
        (function (d, t) {
            var g = d.createElement(t),
                s = d.getElementsByTagName(t)[0];
            g.src = '//www.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g, s);
        }(document, 'script'));


$(function () {

            read_settings_from_cookie();

            var default_text =
                "// This is just a sample script. Paste your real code (javascript or HTML) here.\n\nif ('this_is'==/an_example/){of_beautifer();}else{var a=b?(c%d):e[f];}";
            var textArea = $('#source')[0];

            if (the.use_codemirror && typeof CodeMirror !== 'undefined') {
                the.editor = CodeMirror.fromTextArea(textArea, {
                        theme: 'default',
                        lineNumbers: true
                    });
                the.editor.focus();

                the.editor.setValue(default_text);
                $('.CodeMirror').click(function () {
                    if (the.editor.getValue() == default_text) {
                        the.editor.setValue('');
                    }
                });
            } else {
                $('#source').val(default_text).bind('click focus', function () {
                    if ($(this).val() == default_text) {
                        $(this).val('');
                    }
                }).bind('blur', function () {
                    if (!$(this).val()) {
                        $(this).val(default_text);
                    }
                });
            }


            $(window).bind('keydown', function (e) {
                if (e.ctrlKey && e.keyCode == 13) {
                    //beautify();
                }
            })
             //$('.submit').click(beautify);
             //$('select').change(beautify);


        });

var the = {
            use_codemirror: (!window.location.href.match(/without-codemirror/)),
            beautify_in_progress: false,
            editor: null // codemirror editor
        };

function run_tests() {
    var st = new SanityTest();
    run_beautifier_tests(st, Urlencoded, js_beautify, html_beautify, css_beautify);
    JavascriptObfuscator.run_tests(st);
    P_A_C_K_E_R.run_tests(st);
    Urlencoded.run_tests(st);
    MyObfuscate.run_tests(st);
    var results = st.results_raw().replace(/ /g, '&nbsp;').replace(/\r/g, '·').replace(/\n/g, '<br>');
    $('#testresults').html(results).show();
}


function any(a, b) {
    return a || b;
}

function read_settings_from_cookie() {
    $('#tabsize').val(any($.cookie('tabsize'), '4'));
    $('#brace-style').val(any($.cookie('brace-style'), 'collapse'));
    $('#detect-packers').prop('checked', $.cookie('detect-packers') !== 'off');
    $('#max-preserve-newlines').val(any($.cookie('max-preserve-newlines'), '5'));
    $('#keep-array-indentation').prop('checked', $.cookie('keep-array-indentation') === 'on');
    $('#break-chained-methods').prop('checked', $.cookie('break-chained-methods') === 'on');
    $('#indent-scripts').val(any($.cookie('indent-scripts'), 'normal'));
    $('#space-before-conditional').prop('checked', $.cookie('space-before-conditional') !== 'off');
    $('#wrap-line-length').val(any($.cookie('wrap-line-length'), '0'));
    $('#unescape-strings').prop('checked', $.cookie('unescape-strings') === 'on');
}

function store_settings_to_cookie() {
    var opts = {
        expires: 360
    };
    $.cookie('tabsize', $('#tabsize').val(), opts);
    $.cookie('brace-style', $('#brace-style').val(), opts);
    $.cookie('detect-packers', $('#detect-packers').prop('checked') ? 'on' : 'off', opts);
    $.cookie('max-preserve-newlines', $('#max-preserve-newlines').val(), opts);
    $.cookie('keep-array-indentation', $('#keep-array-indentation').prop('checked') ? 'on' : 'off', opts);
    $.cookie('break-chained-methods', $('#break-chained-methods').prop('checked') ? 'on' : 'off', opts);
    $.cookie('space-before-conditional', $('#space-before-conditional').prop('checked') ? 'on' : 'off',
        opts);
    $.cookie('unescape-strings', $('#unescape-strings').prop('checked') ? 'on' : 'off', opts);
    $.cookie('wrap-line-length', $('#wrap-line-length').val(), opts);
    $.cookie('indent-scripts', $('#indent-scripts').val(), opts);
}

function unpacker_filter(source) {
    var trailing_comments = '',
        comment = '',
        unpacked = '',
        found = false;

    // cut trailing comments
    do {
        found = false;
        if (/^\s*\/\*/.test(source)) {
            found = true;
            comment = source.substr(0, source.indexOf('*/') + 2);
            source = source.substr(comment.length).replace(/^\s+/, '');
            trailing_comments += comment + "\n";
        } else if (/^\s*\/\//.test(source)) {
            found = true;
            comment = source.match(/^\s*\/\/.*/)[0];
            source = source.substr(comment.length).replace(/^\s+/, '');
            trailing_comments += comment + "\n";
        }
    } while (found);

    var unpackers = [P_A_C_K_E_R, Urlencoded, /*JavascriptObfuscator,*/ MyObfuscate];
    for (var i = 0; i < unpackers.length; i++) {
        if (unpackers[i].detect(source)) {
            unpacked = unpackers[i].unpack(source);
            if (unpacked != source) {
                source = unpacker_filter(unpacked);
            }
        }
    }

    return trailing_comments + source;
}


function beautify(unindent_code) {
    //alert("HI");
    if (the.beautify_in_progress) return;

    store_settings_to_cookie();

    the.beautify_in_progress = true;

    var source = the.editor ? the.editor.getValue() : unindent_code,output, opts = {};

    opts.indent_size = "4";
    opts.indent_char = opts.indent_size == 1 ? '\t' : ' ';
    opts.max_preserve_newlines = "-1";
    opts.preserve_newlines = true;
    opts.keep_array_indentation = false;
    opts.break_chained_methods = false;
    opts.indent_scripts = "normal";
    opts.brace_style = "collapse";
    opts.space_before_conditional = true;
    opts.unescape_strings = false;
    opts.wrap_line_length = "0";
    opts.space_after_anon_function = true;

    if (looks_like_html(source)) 
    {
        output = html_beautify(source, opts);
    } 
    else {
        if (false) {
            source = unpacker_filter(source);
        }
        output = js_beautify(source, opts);
        //console.log(output);
    }
    if (the.editor)
    {
        //console.log(output);
        the.editor.setValue(output);
    } 
    else {
        $('#generated_code_text_area').val(output);
    }

    the.beautify_in_progress = false;
}

function looks_like_html(source) {
    // <foo> - looks like html
    // <!--\nalert('foo!');\n--> - doesn't look like html

    var trimmed = source.replace(/^[ \t\n\r]+/, '');
    var comment_mark = '<' + '!-' + '-';
    return (trimmed && (trimmed.substring(0, 1) === '<' && trimmed.substring(0, 4) !== comment_mark));
}

if(typeof String.prototype.trim !== 'function') {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, '').replace(/[/\u00a0/]+/g,'');
    }
}

function save()
{
    updateClientEndOperationCounter();
    var left_panel_content = $("#selectable").html();
    $.blockUI({
        message: 'Saving Project...',
        theme: false,
        baseZ: 500
    });
    $.ajax({
        type: "POST",
        url: "../../general_process/update_project",
        data: {
            project_content: left_panel_content
        },
        success: function (data) {            
            $.unblockUI();
            $('#label_alert_message').text(data);
            $('#div_alert_message').dialog('open');
            //alert(data);                       
        }
    });
}

function generate_code()
{
    if( !is_expression_valid() )
    {
        return;
    }
        
    var parentBlock = new Array();
    
    var li_list = new Array();
    var li_list_counter = 0;
    $('#selectable').each(function()
    {
        $("li", $(this)).each(function ()
        {
            li_list[li_list_counter++] = $(this);
        });
    });
    parentBlock = generate_if_blocks(li_list);

    var parentScript = new Script();
    parentScript.block=(parentBlock);

    var scripts = new Scripts();
    scripts.script=(parentScript);

    var sprite = new Sprite();
    sprite.scripts=(scripts);

    var sprites = new Sprites();
    sprites.sprite=(sprite);

    var stage = new Stage();
    stage.sprites=(sprites);

    var project = new Project();
    project.stage=(stage);
    //console.log('project');
    //console.log(project);
    $.blockUI({
        message: '',
        theme: false,
        baseZ: 500
    });
    $.get('../../json/blockMap.json', function(mapping){
        $.get('../../json/sample.xml', function(xml) {
            var jsonObj = $.xml2json(xml);
            //console.log(jsonObj);
            //console.log(project);
            mapping['variables'] = get_project_variables();
            $.ajax({
                type: "POST",
                url: server_base_url+'../smartycode/service.php',
                dataType: "json",
                data: {project_xml : project, mapping:mapping},
                complete:function(data){
                    $('#generate_code_div_modal').dialog('open');
                    //console.log(data.responseText.replace(/( |\r\n|\t|\r|\n)/gi, ''));
                    var generated_code = data.responseText.replace(/(\r\n|\t|\r|\n)/gi, '').replace(/({)/gi,'\r\n{\r\n').replace(/(})/gi,'\r\n}\r\n').replace(/(;)/gi,';\r\n');
                    //document.getElementById("generated_code_text_area").value = data.responseText.replace(/( |\r\n|\t|\r|\n)/gi, '').replace(/({)/gi,'\r\n{\r\n').replace(/(})/gi,'\r\n}\r\n').replace(/(;)/gi,';\r\n');
                    beautify(generated_code.trim());
                    //beautify(data);
                    $.ajax({
                        type: "POST",
                        url: "../../general_process/save_project_code",
                        data: {
                            code: $('#generated_code_text_area').val()
                        },
                        success: function (ajaxReturnedData) {
                            if(ajaxReturnedData === "false")
                            {
                                $('#label_alert_message').text("Server processing error. Please try again.");
                                $('#div_alert_message').dialog('open');
                                //alert("Server processing error. Please try again.");
                            }
                            $.unblockUI();
                        }
                    });
                    
                }
            });

        });   
    });
    /*$.get('../../json/blockMap.json', function(mapping){
        $.get('../../json/sample.xml', function(xml) {
            var jsonObj = $.xml2json(xml);
            //console.log(jsonObj);
            //console.log(jsonObj);

            var action = new Block();
            action.setS('run');
            action.setL('Monday');

            var action2 = new Block();
            action2.setS('turn');
            action2.setL('15');

            var actionBlock = new Array();
            actionBlock[0] = action;
            //actionBlock[1] = action2;

            var actionScript = new Script();
            actionScript.setBlock(actionBlock);

            var conditionBlock = new Block();
            conditionBlock.setS('>');

            var leftOperatorBlock = new Block();
            leftOperatorBlock.setS('Age');
            var paramArray = new Array();
            paramArray[0] = '10';
            paramArray[1] = 'naz';
            //leftOperatorBlock.setL('10');
            leftOperatorBlock.setL(paramArray);

            var rightOperatorBlock = new Block();
            rightOperatorBlock.setS('Height');
            rightOperatorBlock.setL('10');

            var operatorBlock = new Array();
            operatorBlock[0] = leftOperatorBlock;
            operatorBlock[1] = rightOperatorBlock;

            conditionBlock.setBlock(operatorBlock);

            var expressionBlock = new Block();
            expressionBlock.setS('doIf');
            expressionBlock.setBlock(conditionBlock);
            expressionBlock.setScript(actionScript);

            var parentBlock = new Array();
            parentBlock[0] = expressionBlock;

            var parentScript = new Script();
            parentScript.setBlock(parentBlock);

            var scripts = new Scripts();
            scripts.setScript(parentScript);

            var sprite = new Sprite();
            sprite.setScripts(scripts);

            var sprites = new Sprites();
            sprites.setSprite(sprite);

            var stage = new Stage();
            stage.setSprites(sprites);

            var project = new Project();
            project.setStage(stage);
            
            $.ajax({
                type: "POST",
                url: 'http://localhost/smartycode/service.php',
                dataType: "json",
                data: {project_xml : project, mapping:mapping},
                complete:function(data){
                    //alert(data.responseText);
                    $('#generate_code_div_modal').dialog('open');
                    document.getElementById("generated_code_text_area").value = data.responseText;
                }
            });

        });   
    });*/
    
}
function is_expression_valid()
{
    var is_valid_code = true;
    var error_message = "";
    $('#selectable').each(function()
    {
        $("li", $(this)).each(function ()
        {
            //removing the selected item before user selects generate -> code from menu item
            $(this).attr("class",$(this).attr("class").replace(" ui-selected", ""));
            if(!is_valid_code)
            {
                return;
            }
            if($(this).text().trim() == "Click here to edit block")
            {
                is_valid_code = false;
                error_message = "There is undefined item.";
                //focusing current item from left panel for which there is error while generating code
                $(this).attr("class",$(this).attr("class")+" ui-selected");
                return;
            }
            else if($(this).text().trim() == "Click here to edit action")
            {
                is_valid_code = false;
                error_message = "Action is not defined.";
                //focusing current item from left panel for which there is error while generating code
                $(this).attr("class",$(this).attr("class")+" ui-selected");
                return;
            }
            else if($(this).text().trim() == "Click here to edit condition")
            {
                is_valid_code = false;
                error_message = "Condition is not defined.";
                //focusing current item from left panel for which there is error while generating code
                $(this).attr("class",$(this).attr("class")+" ui-selected");
                return;
            }
        });
    });
    if(!is_valid_code)
    {
        $('#label_alert_message').text(error_message);
        $('#div_alert_message').dialog('open');
        //alert(error_message);
        return false;
    }
    return is_valid_code;
}

function generate_if_blocks(li_list)
{
    var parentBlock = new Array();
    var parentBlockCounter = 0;
    
    var if_block = new Block();
    var condition_block = new Block();
    var then_action_script = new Script();
    var else_action_script = new Script();
    var then_action_block = new Block();
    var else_action_block = new Block();
    var script_actions_array = new Array();
    
    var first_li = li_list[0];
    /*$("li", li_list).each(function ()
    {
        first_li = $(this);
    });*/
    var first_li_length = first_li.attr("id");
    for(var counter = 0 ; counter < li_list.length ; counter++)
    {
        var single_li = li_list[counter];
        //handling root 
        if( single_li.attr("id") == first_li_length)
        {
            if(single_li.text().trim().toLowerCase() == "if")
            {
                if_block = new Block();
                condition_block = new Block();
                then_action_script = new Script();
                else_action_script = new Script();
                then_action_block = new Block();
                else_action_block = new Block();
                script_actions_array = new Array();
                condition_block = generate_condition_block(single_li);
            }
            else if(single_li.text().trim().toLowerCase() == "then")
            {
                then_action_block = generate_action_block(single_li);
                then_action_script.block=(then_action_block); 
                if_block.s=("doIf");    
                if_block.block=(condition_block);
                if_block.script=(then_action_script);
                parentBlock[parentBlockCounter++] = if_block;
            }
            else if(single_li.text().trim().toLowerCase() == "else")
            {
                else_action_block = generate_action_block(single_li);
                else_action_script.block=(else_action_block);
                script_actions_array[0] = then_action_script;
                script_actions_array[1] = else_action_script;
                if_block.s=("doIfElse");  
                if_block.script=(script_actions_array);
                parentBlock[--parentBlockCounter] = if_block;
                parentBlockCounter++;
            } 
        }
    }
    return parentBlock;
}

/*function generate_condition_block(condition)
{
    condition = condition.next("li");
    //consider all actions
    var anchor_id_to_name = {};
    var anchor_id_to_value = {};
    $("a", condition).each(function ()
    {
        var anchor_id = 0;
        if( $(this).attr("id") )
        {
            anchor_id = $(this).attr("id");
        }
        $("input", $(this)).each(function ()
        {
            if( $(this).attr("name") && $(this).attr("value") )
            {
                anchor_id_to_name[anchor_id] = $(this).attr("name");
                anchor_id_to_value[anchor_id] = $(this).attr("value");
            }

        });
    });
    var result_array;
    var comparison_value = "";
    var left_block = new Block();
    var left_value = "";
    var right_block = new Block();
    var right_value = "";
    var condition_block = new Block();
    var counter = 0;
    $("div", condition).each(function ()
    {
        $("input", $(this)).each(function ()
        {
            var value = anchor_id_to_value[$(this).attr("id")];
            if(counter == 0)
            {
               //add external opation later
               if(value == "basicfunction" || value == "advancedfunction")
               {
                    result_array = reverse_code_process(anchor_id_to_value[$(this).attr("id")], anchor_id_to_name[$(this).attr("id")], $(this).attr("value"));
                    left_block.setS(result_array[2]);                    
                    left_block.setL(result_array[1]);
               }
               else
               {
                   left_value = $(this).attr("value");
               }
            }
            else if(counter == 2)
            {
               if(value == "basicfunction" || value == "advancedfunction")
               {
                    result_array = reverse_code_process(anchor_id_to_value[$(this).attr("id")], anchor_id_to_name[$(this).attr("id")], $(this).attr("value"));
                    right_block.setS(result_array[2]);  
                    right_block.setL(result_array[1]);
               }
               else
               {
                   right_value = $(this).attr("value");
               }
            }
            
            else if(value == "comparison")
            {
                comparison_value = $(this).attr("value").trim();                
            }
            counter++;            
        });
    });
    if( left_value == "" && right_value == "")
    {
        var function_array = new Array();
        function_array[0] = left_block;
        function_array[1] = right_block;
        condition_block.setBlock(function_array);
    }
    else if( left_value != "" && right_value != "")
    {
        var value_array = new Array();
        value_array[0] = left_value;
        value_array[1] = right_value;
        condition_block.setL(value_array);
    }
    else
    {
        if(left_value != "")
        {
            condition_block.setL(left_value);
        }
        else
        {
            condition_block.setBlock(left_block);
        }
        if(right_value != "")
        {
            condition_block.setL(right_value);
        }
        else
        {
            condition_block.setBlock(right_block);
        }
    }
    condition_block.setS(comparison_value);
    //console.log(condition_block);
    return condition_block;
}*/

function generate_condition_block(condition)
{
    condition = condition.next("li");
    var anchor_id_to_name = {};
    var anchor_id_to_value = {};
    $("a", condition).each(function ()
    {
        var anchor_id = 0;
        if( $(this).attr("id") )
        {
            anchor_id = $(this).attr("id");
        }
        $("input", $(this)).each(function ()
        {
            if( $(this).attr("name") && $(this).attr("value") )
            {
                anchor_id_to_name[anchor_id] = $(this).attr("name");
                anchor_id_to_value[anchor_id] = $(this).attr("value");
            }

        });
    });
    set_anchor_id_to_optionstype(anchor_id_to_name);
    set_anchor_id_to_options(anchor_id_to_value);
    
    var input_list = new Array();
    var counter = 0;
    $("div", condition).each(function ()
    {
        $("input", $(this)).each(function ()
        {
            input_list[counter++] = $(this);
        });
    });
    var condition_block = new Block();    
    condition_block = process_expression(input_list);
    
    return condition_block;
}


function process_expression(temp_expression)
{
    var expression = new Array();
    if(temp_expression.length >= 2)
    {
        var f_exp = temp_expression[0];
        var l_exp = temp_expression[temp_expression.length - 1];
        if(f_exp.attr("value") === '(' && l_exp.attr("value") === ')' && f_exp.attr("title") === f_exp.attr("id")+'-'+l_exp.attr("id")+'-startbracket' && l_exp.attr("title") === f_exp.attr("id")+'-'+l_exp.attr("id")+'-endbracket')
        {
            for(var counter = 0 ; counter < temp_expression.length - 2 ; counter++)
            {
                expression[counter] = temp_expression[counter+1];
            }
        }
        else
        {
            expression = temp_expression;
        }        
    }
    else
    {
        expression = temp_expression;
    }
    var left_expression_list = new Array();
    var right_expression_list = new Array();
    var left_expression_counter = 0;
    var right_expression_counter = 0;
    var is_logical_operator_exists = false;
    var logical_operator_value = "";
    var left_block = new Block();
    var right_block = new Block();
    var block = new Block();
    var bracket_counter = 0;
    for(var counter = 0 ; counter < expression.length ; counter++)
    {
        var single_input = expression[counter];
        if(single_input.attr("value") === '(')
        {
           bracket_counter++; 
        }
        else if(single_input.attr("value") === ')')
        {
           bracket_counter--; 
        }
        if( bracket_counter === 0 && is_logical_operator_exists === false && (single_input.attr("title") === 'logical_connector_and' || single_input.attr("title") === 'logical_connector_or') )
        {
            is_logical_operator_exists = true;
            logical_operator_value = single_input.attr("value").trim();
        }
        else
        {
            if(is_logical_operator_exists === true)
            {
                right_expression_list[right_expression_counter++] = single_input;
            }
            else
            {
                left_expression_list[left_expression_counter++] = single_input;
            }
        }
    }
    if(is_logical_operator_exists === true)
    {
        left_block = process_expression(left_expression_list);
        right_block = process_expression(right_expression_list);
        
        var block_array = new Array();
        block_array[0] = left_block;
        block_array[1] = right_block;
        block.block=(block_array);
        block.s=(logical_operator_value);
    }
    else
    {
        block = process_condition(expression);
    }
    return block;
}

function process_condition(temp_expression)
{
    var expression = new Array();
    if(temp_expression.length >= 2)
    {
        var f_exp = temp_expression[0];
        var l_exp = temp_expression[temp_expression.length - 1];
        if(f_exp.attr("value") === '(' && l_exp.attr("value") === ')' && f_exp.attr("title") === f_exp.attr("id")+'-'+l_exp.attr("id")+'-startbracket' && l_exp.attr("title") === f_exp.attr("id")+'-'+l_exp.attr("id")+'-endbracket')
        {
            for(var counter = 0 ; counter < temp_expression.length - 2 ; counter++)
            {
                expression[counter] = temp_expression[counter+1];
            }
        }
        else
        {
            expression = temp_expression;
        }        
    }
    else
    {
        expression = temp_expression;
    }
    var condition_block = new Block();
    var left_operand_input_list = new Array();
    var right_operand_input_list = new Array();
    var left_operand_counter = 0;
    var right_operand_counter = 0;
    var flag = false;
    var comparison_value = "";
    for(var counter = 0 ; counter < expression.length ; counter++)
    {
        var single_input = expression[counter];
        if( single_input.attr("title") === 'comparison')
        {
            flag = true;
            comparison_value = single_input.attr("value").trim();
        }
        else
        {
            if(flag === true)
            {
                right_operand_input_list[right_operand_counter++] = single_input;
            }
            else
            {
                left_operand_input_list[left_operand_counter++] = single_input;
            }
        }
    }
    var left_operand_result = process_operand(left_operand_input_list);
    var right_operand_result = process_operand(right_operand_input_list);
    if( left_operand_result.constructor === Block && right_operand_result.constructor === Block)
    {
        var function_array = new Array();
        function_array[0] = left_operand_result;
        function_array[1] = right_operand_result;
        condition_block.block=(function_array);
    }
    else if( left_operand_result.constructor !== Block && right_operand_result.constructor !== Block)
    {
        var value_array = new Array();
        value_array[0] = left_operand_result;
        value_array[1] = right_operand_result;
        condition_block.l=(value_array);
    }
    else
    {
        if(left_operand_result.constructor !== Block)
        {
            condition_block.l=(left_operand_result);
        }
        else
        {
            condition_block.block=(left_operand_result);
        }
        if(right_operand_result.constructor !== Block)
        {
            condition_block.l=(right_operand_result);
        }
        else
        {
            condition_block.block=(right_operand_result);
        }
    }
    condition_block.s=(comparison_value);
    return condition_block;
}

function process_operand(operand_input_list)
{
    var anchor_id_to_name_list = get_anchor_id_to_optionstype();
    var anchor_id_to_value_list = get_anchor_id_to_options();
    var result_array;        
    
    //polish postfix logic starts
    var stack_array = new Array();
    var operand_array = new Array();
    var stack_array_counter = 0;
    var operand_array_counter = 0;
    for(var counter = 0 ; counter < operand_input_list.length ; counter++)
    {
        var single_input = operand_input_list[counter];
        var value = single_input.attr("value");
        if(value === '(')
        {
            stack_array[stack_array_counter++] = single_input;
        }
        else if(value === ')')
        {
           var temp_element =  stack_array[--stack_array_counter];
           while(temp_element.attr("value") !== '(')
           {
               operand_array[operand_array_counter++] = temp_element;
               temp_element =  stack_array[--stack_array_counter];
           }
        }
        else if( is_arithmetic_operator(value) )
        {
            
            while( stack_array_counter > 0 && is_arithmetic_operator(stack_array[stack_array_counter-1].attr("value")) && check_high_priority_operator(stack_array[stack_array_counter-1].attr("value") , value) )
            {
                operand_array[operand_array_counter++] = stack_array[--stack_array_counter];
            }
            stack_array[stack_array_counter++] = single_input;
        }
        else
        {
            operand_array[operand_array_counter++] = single_input;
        }
    }
    while( stack_array_counter > 0 )
    {
        operand_array[operand_array_counter++] = stack_array[--stack_array_counter];
    }
    //polish postfix logic ends
    
    //we have one single operand
    if( operand_array_counter === 1)
    {
        var block = new Block();
        
        var single_input = operand_array[operand_array_counter-1];
        var value = anchor_id_to_value_list[single_input.attr("id")];
        if(value === "basicfunction" || value === "advancedfunction")
        {
             result_array = reverse_code_process(anchor_id_to_value_list[single_input.attr("id")], anchor_id_to_name_list[single_input.attr("id")], single_input.attr("value"));
             block.s=(result_array[2]);                    
             block.l=(result_array[1]);
             return block;
        }
        else
        {
            value = single_input.attr("value");
            return value;
        }
    }
    else
    {
        //console.log(operand_array);
        var processing_stack_array = new Array();
        var processing_stack_array_counter = 0;
        for( var counter = 0 ; counter < operand_array.length ; counter++ )
        {
            //console.log(counter);
            var single_input = operand_array[counter];
            var value = single_input.attr("value");
            if( is_arithmetic_operator(value) )
            {
                //console.log('got operator');
                var second_element = processing_stack_array[--processing_stack_array_counter];
                var first_element = processing_stack_array[--processing_stack_array_counter];
                
                var left_block = new Block();
                var left_value = "";
                var right_block = new Block();
                var right_value = "";
                var operand_block = new Block();
                //operand_block.setS(value);
                if(first_element.constructor === Block)
                {
                    left_block = first_element;                    
                }
                else
                {
                    var first_element_value = anchor_id_to_value_list[first_element.attr("id")];                
                    if(first_element_value === "basicfunction" || first_element_value === "advancedfunction")
                    {
                         result_array = reverse_code_process(anchor_id_to_value_list[first_element.attr("id")], anchor_id_to_name_list[first_element.attr("id")], first_element.attr("value"));
                         left_block.s=(result_array[2]);                    
                         left_block.l=(result_array[1]);
                    }
                    else
                    {
                        left_value = first_element.attr("value");
                    }
                }
                if(second_element.constructor === Block)
                {
                    right_block = second_element;                    
                }
                else
                {
                    var second_element_value = anchor_id_to_value_list[second_element.attr("id")];                
                    if(second_element_value === "basicfunction" || second_element_value === "advancedfunction")
                    {
                         result_array = reverse_code_process(anchor_id_to_value_list[second_element.attr("id")], anchor_id_to_name_list[second_element.attr("id")], second_element.attr("value"));
                         right_block.s=(result_array[2]);                    
                         right_block.l=(result_array[1]);
                    }
                    else
                    {
                        right_value = second_element.attr("value");
                    }
                }
                if( left_value === "" && right_value === "")
                {
                    var function_array = new Array();
                    function_array[0] = left_block;
                    function_array[1] = right_block;
                    operand_block.block=(function_array);
                }
                else if( left_value !== "" && right_value !== "")
                {
                    var value_array = new Array();
                    value_array[0] = left_value;
                    value_array[1] = right_value;
                    operand_block.l=(value_array);
                }
                else
                {
                    if(left_value !== "")
                    {
                        operand_block.l=(left_value);
                    }
                    else
                    {
                        operand_block.block=(left_block);
                    }
                    if(right_value !== "")
                    {
                        operand_block.l=(right_value);
                    }
                    else
                    {
                        operand_block.block=(right_block);
                    }
                }
                operand_block.s=(value);
                processing_stack_array[processing_stack_array_counter++] = operand_block;
            }
            else
            {
                processing_stack_array[processing_stack_array_counter++] = single_input;
            }
        }
        return processing_stack_array[0];
    }
}

function is_arithmetic_operator(value)
{
    if( value === '+' || value === '-' || value === '*' || value === '/')
    {
        return true;
    }
    else
    {
        return false;
    }
}

/*
 * This method will check priority of two operators
 * @param first_operator, first operator
 * @param second_operator, second operator
 * @return true if priority of first operator is higher than the second operator, otherwise false
 */
function check_high_priority_operator(first_operator, second_operator)
{
    var operator_weight = {};
    operator_weight['+'] = 1;
    operator_weight['-'] = 1;
    operator_weight['*'] = 2;
    operator_weight['/'] = 2;
    if( operator_weight[first_operator] >= operator_weight[second_operator] )
    {
        return true;
    }
    else
    {
        return false;
    }
    
}


/*function process_operand(operand_input_list)
{
    var block = new Block();
    var value = "";
    var anchor_id_to_name_list = get_anchor_id_to_optionstype();
    var anchor_id_to_value_list = get_anchor_id_to_options();
    var result_array;
    
    var left_part_input_list = new Array();
    var right_part_input_list = new Array();
    var left_part_input_list_counter = 0;
    var right_part_input_list_counter = 0;
    var arithmetic_operator_value = "";
    
    var is_arithmetic_operator_exists = false;
    
    for(var counter = 0 ; counter < operand_input_list.length ; counter++)
    {
        var single_input = operand_input_list[counter];
        if( is_arithmetic_operator_exists === false && (single_input.attr("value") === '+' || single_input.attr("value") === '-' || single_input.attr("value") === '*' || single_input.attr("value") === '/') )
        {
            is_arithmetic_operator_exists = true;
            arithmetic_operator_value = single_input.attr("value").trim();
        }
        else
        {
            if(is_arithmetic_operator_exists === true)
            {
                right_part_input_list[right_part_input_list_counter++] = single_input;
            }
            else
            {
                left_part_input_list[left_part_input_list_counter++] = single_input;
            }
        }
    }
    if(is_arithmetic_operator_exists === true)
    {
        var left_operand_result = process_operand(left_part_input_list);
        var right_operand_result = process_operand(right_part_input_list);
        if( left_operand_result.constructor === Block && right_operand_result.constructor === Block)
        {
            var function_array = new Array();
            function_array[0] = left_operand_result;
            function_array[1] = right_operand_result;
            block.setBlock(function_array);
        }
        else if( left_operand_result.constructor !== Block && right_operand_result.constructor !== Block)
        {
            var value_array = new Array();
            value_array[0] = left_operand_result;
            value_array[1] = right_operand_result;
            block.setL(value_array);
        }
        else
        {
            if(left_operand_result.constructor !== Block)
            {
                block.setL(left_operand_result);
            }
            else
            {
                block.setBlock(left_operand_result);
            }
            if(right_operand_result.constructor !== Block)
            {
                block.setL(right_operand_result);
            }
            else
            {
                block.setBlock(right_operand_result);
            }
        }
        block.setS(arithmetic_operator_value);
        return block;
    }
    else
    {
        for(var counter = 0 ; counter < operand_input_list.length ; counter++)
        {
            var single_input = operand_input_list[counter];
            var value = anchor_id_to_value_list[single_input.attr("id")];
            if(value === "basicfunction" || value === "advancedfunction")
            {
                 result_array = reverse_code_process(anchor_id_to_value_list[single_input.attr("id")], anchor_id_to_name_list[single_input.attr("id")], single_input.attr("value"));
                 block.setS(result_array[2]);                    
                 block.setL(result_array[1]);
                 return block;
            }
            else
            {
                value = single_input.attr("value");
                return value;
            }
        }
    }
}*/

function generate_action_block(action)
{
    var then_length = parseInt(action.attr("id"));
    var current_action = action;
    var current_action_length = 0;
    
    var anchor_id_to_name = {};
    var anchor_id_to_value = {};
    var action_block_array = new Array();
    var action_counter = 0;
    
    var action_type = "";
    var variable_assignment_operator = "";
    var variable_left_part_text = "";
    var variable_right_part_text = "";
    var variable_div_counter = 0;
    var variable_text_array = new Array();
    
    while( true )
    {
        current_action = current_action.next("li");
        current_action_length = parseInt(current_action.attr("id"));
        //current action list is completed and we get a new block
        if(current_action_length <= then_length)
        {
            break;
        }
        if( current_action_length == parseInt(then_length) + parseInt(indentation_space_length) )
        {
            if( current_action.text().trim().toLowerCase() == "if" || current_action.text().trim().toLowerCase() == "then" || current_action.text().trim().toLowerCase() == "else" )
            {
                //handle recursive call
                if(current_action.text().trim().toLowerCase() == "if")
                {
                    var temp_action = current_action;   
                    var li_list = new Array();
                    var li_list_counter = 0;
                    li_list[li_list_counter++] = temp_action;  
                    temp_action = temp_action.next("li");
                    //console.log('current_action_length:'+current_action_length+',temp length:'+temp_action.attr("id")+',text:'+temp_action.text());
                    while(true)
                    {
                        if( temp_action.attr("id") == current_action_length && (temp_action.text().trim().toLowerCase() == "then" || temp_action.text().trim().toLowerCase() == "else"))
                        {
                            li_list[li_list_counter++] = temp_action;
                            //console.log('then,else');
                        }
                        else if( parseInt(temp_action.attr("id")) > current_action_length )
                        {
                            li_list[li_list_counter++] = temp_action;
                        }
                        else
                        {
                            break;
                        }
                        if(temp_action.next('li').length <= 0)
                        {
                            break;
                        }
                        temp_action = temp_action.next("li");
                    }
                    action_block_array[action_counter++] = generate_if_blocks(li_list)[0];
                }
                
            }
            else
            {
                anchor_id_to_name = {};
                anchor_id_to_value = {};
                $("a", current_action).each(function ()
                {
                    var anchor_id = 0;
                    if( $(this).attr("id") )
                    {
                        anchor_id = $(this).attr("id");
                    }
                    $("input", $(this)).each(function ()
                    {
                        if( $(this).attr("name") && $(this).attr("value") )
                        {
                            action_type = $(this).attr("value");
                            anchor_id_to_name[anchor_id] = $(this).attr("name");
                            anchor_id_to_value[anchor_id] = $(this).attr("value");
                        }

                    });                    
                });
                //console.log('action_type:'+action_type);
                var action_block = new Block();
                if( action_type == 'action')
                {
                    $("div", current_action).each(function ()
                    {
                        $("input", $(this)).each(function ()
                        {
                            var value = anchor_id_to_value[$(this).attr("id")];
                            var name = anchor_id_to_name[$(this).attr("id")];
                            action_block.s=(name);
                            var result_array = reverse_code_process(anchor_id_to_value[$(this).attr("id")], anchor_id_to_name[$(this).attr("id")], $(this).attr("value"));
                            action_block.l=(result_array[1]);                        
                        });
                    });
                }
                else if( action_type == 'variable')
                {
                    $("div", current_action).each(function ()
                    {
                        $("input", $(this)).each(function ()
                        {
                            if(variable_div_counter == 0)
                            {
                                variable_left_part_text = $(this).attr("value");
                            }
                            else if(variable_div_counter == 1)
                            {
                                variable_assignment_operator = $(this).attr("value").trim();
                            }
                            else if(variable_div_counter == 2)
                            {
                                variable_right_part_text = $(this).attr("value");
                            }
                            variable_div_counter++;
                        });
                    });
                    variable_text_array[0] = variable_left_part_text;
                    variable_text_array[1] = variable_right_part_text;
                    action_block.l=(variable_text_array);
                    action_block.s=(variable_assignment_operator);
                }
                
                //console.log(action_block);
                //console.log("action_counter:"+action_counter);
                action_block_array[action_counter++] = action_block;
            }
        }
        
        if(current_action.next('li').length <= 0)
        {
            break;
        }
    }
    /*action = action.next("li");
    var anchor_id_to_name = {};
    var anchor_id_to_value = {};
    $("a", action).each(function ()
    {
        var anchor_id = 0;
        if( $(this).attr("id") )
        {
            anchor_id = $(this).attr("id");
        }
        $("input", $(this)).each(function ()
        {
            if( $(this).attr("name") && $(this).attr("value") )
            {
                anchor_id_to_name[anchor_id] = $(this).attr("name");
                anchor_id_to_value[anchor_id] = $(this).attr("value");
            }

        });
    });
    var action_block_array = new Array();
    var action_block = new Block();
    $("div", action).each(function ()
    {
        $("input", $(this)).each(function ()
        {
            var value = anchor_id_to_value[$(this).attr("id")];
            var name = anchor_id_to_name[$(this).attr("id")];
            action_block.setS(name);
            var result_array = reverse_code_process(anchor_id_to_value[$(this).attr("id")], anchor_id_to_name[$(this).attr("id")], $(this).attr("value"));
            action_block.setL(result_array[1]);
            //console.log(reverse_code_process(anchor_id_to_value[$(this).attr("id")], anchor_id_to_name[$(this).attr("id")], $(this).attr("value")));
        });
    });
    //console.log(action_block);
    action_block_array[0] = action_block;
    return action_block_array;*/
    return action_block_array;
}

/*function generate_code()
{
    updateClientEndOperationCounter();
    //alert("On Progress.");
    var code = "";
    var is_valid_code = true;
    var error_message = "";
    var temp_text = "";
    var totalSpacesForBracket = new Array();
    var totalSpacesForBracketCounter = -1;
    var spacesForBracket = new Array();
    var spacesForBracketCounter = -1;

    //clearing natural language panel, code panel, parameter table and tree
    $('#changing_stmt').html("");
    $('#code_stmt').html("");
    $('#parameters_table').html("");
    $("li", $("#demo1")).each(function ()
    {
        $(this).hide();
    });
    for(var variable_counter = 0 ; variable_counter < project_variable_list.length ; variable_counter++)
    {
        var variable = project_variable_list[variable_counter];
        if(variable.variable_type == "BOOLEAN")
        {
            code = code +"boolean "+variable.variable_name+" = "+variable.variable_value+";"+getLineBreakSequence();
        }
        else
        {
            code = code +"double "+variable.variable_name+" = "+variable.variable_value+";"+getLineBreakSequence();
        }
    }
    $('#selectable').each(function()
    {
        $("li", $(this)).each(function ()
        {
            //removing the selected item before user selects generate -> code from menu item
            $(this).attr("class",$(this).attr("class").replace(" ui-selected", "")); 
            
            if(!is_valid_code)
                return;

            var total_spaces = $(this).attr("id");
            var counter = 0;
            $("div", $(this)).each(function ()
            {
                 var starting_space = "";
                 for(var i = 0 ; i < total_spaces ; i++){
                    starting_space = starting_space + " ";
                 }

                var is_action = 0;
                //code = code + starting_space;
                $("input", $(this)).each(function ()
                {
                    if(counter == 0 && $(this).attr("name") == "action")
                    {
                        code = code+starting_space;
                        is_action = 1;
                    }
                    code = code + $(this).attr("value");
                    //alert($(this).attr("value"));
                    //code_segment = code_segment + $(this).attr("value")+" ";
                    counter++;
                });
                if(is_action == 1)
                {
                    code = code +";"+getLineBreakSequence();
                }
                
            });
            if(counter == 0)
            {

                if($(this).text().trim() == "Click here to edit block")
                {
                    is_valid_code = false;
                    error_message = "There is undefined item.";
                    //focusing current item from left panel for which there is error while generating code
                    $(this).attr("class",$(this).attr("class")+" ui-selected"); 
                    //alert("text is : "+$(this).text().trim());
                    return;
                }
                else if($(this).text().trim() == "Click here to edit action")
                {
                    is_valid_code = false;
                    error_message = "Action is not defined.";
                    //focusing current item from left panel for which there is error while generating code
                    $(this).attr("class",$(this).attr("class")+" ui-selected");
                    //alert("text is : "+$(this).text().trim());
                    return;
                }
                else if($(this).text().trim() == "Click here to edit condition")
                {
                    is_valid_code = false;
                    error_message = "Condition is not defined.";
                    //focusing current item from left panel for which there is error while generating code
                    $(this).attr("class",$(this).attr("class")+" ui-selected");
                    //alert("text is : "+$(this).text().trim());
                    return;
                }


                if($(this).text().trim().toLowerCase() == "if" )
                {
                    temp_text = $(this).text();
                    temp_text = temp_text.replace($(this).text().trim(), "");
                    while(totalSpacesForBracket[totalSpacesForBracketCounter] >= temp_text.length)
                    {
                        code = code +spacesForBracket[spacesForBracketCounter--]+"}"+getLineBreakSequence();
                        totalSpacesForBracketCounter--;
                    }


                    if($(this).next("li").text().trim() != "(")
                    {
                        code = code + $(this).text().toLowerCase()+" ( ";
                    }
                    else{
                        code = code + $(this).text().toLowerCase()+" ";
                    }
                    
                }
                else if($(this).text().trim() == "(" )
                {
                    code = code + $(this).text().trim();
                }
                else if( $(this).text().trim() == ")")
                {
                    code = code +$(this).text().trim();
                }
                else if( $(this).text().trim() == "}")
                {
                    code = code + getLineBreakSequence()+ $(this).text();
                }
                else if($(this).text().trim() == "THEN" )
                {
                    //if(code.lastIndexOf(")") != code.length-1)
                    //{
                        code = code + " ) "+getLineBreakSequence();
                    //}
                    //else{
                    //    code = code + getLineBreakSequence();
                    //}
                    if($(this).next("li").text().trim() != "{")
                    {
                        temp_text = $(this).text();
                        temp_text = temp_text.replace($(this).text().trim(), "");
                        code = code + temp_text+"{ "+getLineBreakSequence();

                        totalSpacesForBracket[++totalSpacesForBracketCounter] = temp_text.length;
                        spacesForBracket[++spacesForBracketCounter] = temp_text;
                    }
                    //skipping then inside code
                    //code = code + getLineBreakSequence();
                }
                else if($(this).text().trim().toLowerCase() == "else" )
                {
                    temp_text = $(this).text();
                    temp_text = temp_text.replace($(this).text().trim(), "");
                    while(totalSpacesForBracket[totalSpacesForBracketCounter] >= temp_text.length)
                    {
                        code = code +spacesForBracket[spacesForBracketCounter--]+"}"+getLineBreakSequence();
                        totalSpacesForBracketCounter--;
                    }

                    //code = code + getLineBreakSequence()+$(this).text().toLowerCase()+getLineBreakSequence();
                    code = code +$(this).text().toLowerCase()+getLineBreakSequence();
                    if($(this).next("li").text().trim() != "{")
                    {
                        temp_text = $(this).text();
                        temp_text = temp_text.replace($(this).text().trim(), "");
                        code = code + temp_text+"{ "+getLineBreakSequence();

                        totalSpacesForBracket[++totalSpacesForBracketCounter] = temp_text.length;
                        spacesForBracket[++spacesForBracketCounter] = temp_text;
                    }
                }
                else
                {
                    code = code + $(this).text()+getLineBreakSequence();
                }
            }

        });
    });
    //alert(code);
    while(spacesForBracketCounter > -1)
    {
        code = code +spacesForBracket[spacesForBracketCounter--]+"}"+getLineBreakSequence();
        //alert(code);
    }
    //alert("code is : "+code);
    if(is_valid_code){
        $.blockUI({
            message: '',
            theme: false,
            baseZ: 500
        });
        $.ajax({
            type: "POST",
            url: "../../CodeProcess/save_project_code",
            data: {
                code: code
            },
            success: function (ajaxReturnedData) {
                if(ajaxReturnedData == "true")
                {
                    $('#generate_code_div_modal').dialog('open');
                    document.getElementById("generated_code_text_area").value = code;
                }
                else
                {
                    alert("Server processing error. Please try again.");
                }
                $.unblockUI();
            }
        });        
    }
    else
    {
        alert("Invalid expression to generate code."+error_message);
    }

}*/

function getLineBreakSequence()
{
    return "\n";
}

function add_block()
{
    updateClientEndOperationCounter();
    $("#selectable li:last-child").after("<li class='ui-widget-content' id='0'>Click here to edit block</li>");
}

function add_action()
{
    updateClientEndOperationCounter();
    var selectedItem = $('#selectable .ui-selected').text();
    if(selectedItem.trim() == "THEN" || selectedItem.trim().toLowerCase() == "else")
    {
        var total_spaces = $("#selectable .ui-selected").attr("id");
        var starting_space = "";
        for(var i = 0 ; i < total_spaces ; i++){
           starting_space = starting_space + "&nbsp;";
        }
        starting_space = starting_space + indentation_spaces;
        var inside_space_counter = parseInt(total_spaces)+parseInt(indentation_space_length);
        if($('#selectable .ui-selected').next("li").text().trim() == "{")
        {
            $('#selectable .ui-selected').next("li").after("<li class='ui-widget-content' id='"+inside_space_counter+"'>"+starting_space+"Click here to edit action</li>");
        }
        else
        {
            $('#selectable .ui-selected').after("<li class='ui-widget-content' id='"+inside_space_counter+"'>"+starting_space+"Click here to edit action</li>");
        }
    }
    else
    {
        $('#label_alert_message').text("You are not allowed to add action here.");
        $('#div_alert_message').dialog('open');
        //alert("You are not allowed to add action here.");
    }
}

//user selects if or THEN or else and then selects Add Brackets button
function add_brackets()
{
    updateClientEndOperationCounter();
    var selectedItemText = $('#selectable .ui-selected').text().trim();
    if(selectedItemText.toLowerCase() == "if" || selectedItemText.toLowerCase() == "then" || selectedItemText.toLowerCase() == "else")
    {
        $('#label_alert_message').text("You are not allowed to add bracket here.");
        $('#div_alert_message').dialog('open');
        //alert("You are not allowed to add bracket here.")
        return;
    }
    if(selectedItemText.trim() == "Click here to edit condition" || selectedItemText.trim() == "Click here to edit action" || selectedItemText.trim() == "Click here to edit block")
    {
        $('#label_alert_message').text("You are not allowed to add bracket here.");
        $('#div_alert_message').dialog('open');
        //alert("You are not allowed to add bracket here.")
        return;
    }
    $('#selectable li').each(function()
    {
        if($(this).attr("class") == "ui-widget-content ui-selected")
        {
            $("a", $(this)).each(function ()
            {
                if($(this).attr("id") == "ssaid" && ($(this).attr("title") == "start_space_anchor_action" || $(this).attr("title") == "start_space_anchor_action_variable"))
                {
                    $('#label_alert_message').text("You are not allowed to add bracket here.");
                    $('#div_alert_message').dialog('open');
                    //alert("You are not allowed to add bracket here.")
                    return;
                }
            });
        }
    });
    
    //user selects a condition from left panel and wants to add bracket
    $("div", $("#selectable .ui-selected")).each(function () {
        $("input", $(this)).each(function () {
            if($(this).attr("name") == "condition")
            {
                //opening logical connector div modal window
                $('#add_bracket_in_condition_div').dialog('open');
                var selected_anchor_list = "";
                $("a", $('#selectable .ui-selected')).each(function ()
                {
                    //we are not showing first anchor which contains starting spaces
                    if($(this).attr("id") != "ssaid"){
                        selected_anchor_list = selected_anchor_list+"<li class='ui-widget-content'>";
                        selected_anchor_list = selected_anchor_list+$(this).prop('outerHTML');
                        selected_anchor_list = selected_anchor_list + "</li>";
                    }
                });
                document.getElementById("add_bracket_in_condition_div_selected_items").innerHTML = selected_anchor_list;
                return;
            }
        });
    });
    //adding bracket for condition ends
    
    //text content of currently selected item from left panel
    var id1 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    var id2 = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    
    var bracketStart;
    var bracketEnd;
    if(selectedItemText.toLowerCase() == "if")
    {
        bracketStart = "(";
        bracketEnd = ")";
    }
    if(selectedItemText == "THEN" || selectedItemText.toLowerCase() == "else")
    {
        bracketStart = "{";
        bracketEnd = "}";
    }

    //counting total number of leading spaces of this selected item
    var total_spaces = $('#selectable .ui-selected').attr("id");
    var starting_space = "";
    for(var i = 0 ; i < total_spaces ; i++){
       starting_space = starting_space + "&nbsp;";
    }

    if(selectedItemText.toLowerCase() == "if")
    {
        var currentItem = $('#selectable .ui-selected');
        //checking whether bracket already exists or not
        if(currentItem.next("li").text().trim() == "(")
        {
            $('#label_alert_message').text("Breaket already exists.");
            $('#div_alert_message').dialog('open');
            alert("Breaket already exists.")
        }
        else
        {
            //adding starting bracket
            currentItem.after("<li class='ui-widget-content' id = '"+total_spaces+"' title='"+id1+"-"+id2+"-startbracket' >"+starting_space+bracketStart+"</li>");
            while(currentItem.text().trim() != "THEN")
            {
                currentItem = currentItem.next("li");
            }
            //adding ending bracket
            currentItem.before("<li class='ui-widget-content' id = '"+total_spaces+"' title='"+id1+"-"+id2+"-endbracket' >"+starting_space+bracketEnd+"</li>");
        }
    }
    if(selectedItemText == "THEN" || selectedItemText.toLowerCase() == "else")
    {
        //checking whether bracket already exists or not
        if($('#selectable .ui-selected').next("li").text().trim() == "{")
        {
            $('#label_alert_message').text("Breaket already exists.");
            $('#div_alert_message').dialog('open');
            //alert("Breaket already exists.")
        }
        else
        {
            //adding starting bracket
            $('#selectable .ui-selected').after("<li class='ui-widget-content' id='"+total_spaces+"'>"+starting_space+bracketStart+"</li>");
            var selected_expression_starting_spaces = 0;
            var selected_expression_index = -1;
            var list_counter = 1;
            var closing_breaket_added = 0;
            //searching ending bracket position
            $('#selectable').each(function()
            {
                $("li", $(this)).each(function ()
                {
                    if ($(this).attr("class") == "ui-widget-content ui-selected")
                    {
                        selected_expression_index = list_counter;
                        selected_expression_starting_spaces = $(this).attr("id");                        
                    }
                    else if(list_counter > selected_expression_index+1 && selected_expression_index > -1)
                    {
                        var current_expression_spaces = $(this).attr("id");
                        if(current_expression_spaces <= selected_expression_starting_spaces)
                        {
                            //adding ending bracket
                            $(this).before("<li class='ui-widget-content' id='"+total_spaces+"'>"+starting_space+bracketEnd+"</li>");
                            //making sure that ending bracket is added
                            closing_breaket_added = 1;
                            return false;
                        }
                    }
                    list_counter++;
                });
            });
            //ending bracket will be added at the end
            if(closing_breaket_added == 0)
            {
                $("#selectable li:last-child").after("<li class='ui-widget-content' id='"+total_spaces+"'>"+starting_space+bracketEnd+"</li>");
            }
        }
    }
}

/*
 * User presses delete from menu item
 **/
function delete_block()
{
    updateClientEndOperationCounter();
    //validation checking before deletion
    if($('#selectable .ui-selected').text().length == 0)
    {
        $('#label_alert_message').text("Please select an item to delete.");
        $('#div_alert_message').dialog('open');
        //alert("Please select an item to delete.");
        return;
    }
    else if($('#selectable .ui-selected').text().trim() == "Click here to edit condition")
    {
        $('#label_alert_message').text("You are not allowed to remove empty condition.");
        $('#div_alert_message').dialog('open');
        //alert("You are not allowed to remove empty condition.");
        return;
    }
    else if($('#selectable .ui-selected').text().trim() == "Click here to edit block")
    {
        $('#label_alert_message').text("You are not allowed to remove an empty block.");
        $('#div_alert_message').dialog('open');
        //alert("You are not allowed to remove an empty block.");
        return;
    }
    //user wants to remove bracket
    else if($('#selectable .ui-selected').text().trim() == ")" || $('#selectable .ui-selected').text().trim() == "}" || $('#selectable .ui-selected').text().trim() == "(" || $('#selectable .ui-selected').text().trim() == "{")
    {
        $('#label_alert_message').text("Please select delete option from Bracket menu item to delete this selected item.");
        $('#div_alert_message').dialog('open');
        //alert("Please select delete option from Bracket menu item to delete this selected item.");
        return;
    }
    
    else if($('#selectable .ui-selected').text().trim() == "THEN")
    {
        $('#label_alert_message').text("You are not allowed to remove THEN expression.");
        $('#div_alert_message').dialog('open');
        //alert("You are not allowed to remove THEN expression.");
        return;
    }
    
    var is_condition_selected = false;
    var is_action_selected = false;
    var current_selected_segment = "";
    $('#selectable').each(function()
     {
        $("li", $(this)).each(function ()
        {
            if($(this).text().trim().toLowerCase() == "if")
            {
                current_selected_segment = "if";                
            }
            else if($(this).text().trim().toLowerCase() == "else")
            {
                current_selected_segment = "else";
            }
            else if($(this).text().trim() == "THEN" )
            {
                current_selected_segment = "THEN";
            }
            else
            {
                //user selects a condition to delete
                if($(this).attr("class") == "ui-widget-content ui-selected" && current_selected_segment.toLowerCase() == "if" )
                {
                    is_condition_selected = true;
                }
                //user selects an action to delete
                if($(this).attr("class") == "ui-widget-content ui-selected" && (current_selected_segment.toLowerCase() == "then" || current_selected_segment.toLowerCase() == "else") )
                {
                    is_action_selected = true;
                }
                
            }
        });
    });
    //generating custom message for user in delete confirmation dialog
    if(is_condition_selected == true)
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete this condition?";
    }
    else if(is_action_selected == true)
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete this action?";
    }
    else if($('#selectable .ui-selected').text().trim().toLowerCase() == "if")
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "You are about to delete a block. Are you sure?";
    }
    else if($('#selectable .ui-selected').text().trim().toLowerCase() == "else")
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete the \"else\"?";
    }
    else
    {
        document.getElementById("label_delete_block_confirmation_div_modal").innerHTML = "Are you sure you want to delete this?";
    }
    //user confirmation dialog
    $('#delete_block_confirmation_div_modal').dialog('open');
}

function delete_item()
{
    updateClientEndOperationCounter();
    var start_if_total_spaces = -1;
    var start_else_total_spaces = -1;
    var end_flag = 0;
    var delete_start_marker = -1;
    var delete_end_marker = -1;

    var is_allowed_delete = 1;

    var current_selected_segment = "";

    var then_else_removal_index = -1;
    var then_else_removal_total_spaces = -1;

    var start = 1;
    
     $('#selectable').each(function()
     {
        $("li", $(this)).each(function ()
        {
            if($(this).text().trim().toLowerCase() == "if")
            {
                current_selected_segment = "if";
                if ($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    start_if_total_spaces = $(this).attr("id");
                    if(start > 1 && start_if_total_spaces > 0)
                    {
                         var starting_space = "";
                         for(var i = 0 ; i < start_if_total_spaces ; i++){
                            starting_space = starting_space + "&nbsp;";
                         }
                         $(this).before("<li class='ui-widget-content' id='"+start_if_total_spaces+"'>"+starting_space+"Click here to edit action</li>");
                         start++;
                    }
                    delete_start_marker = start;
                    //alert(start);

                }
                else
                {
                    if(end_flag == 0)
                    {
                       var total_spaces = $(this).attr("id");
                        if(total_spaces < start_if_total_spaces || (total_spaces == start_if_total_spaces && $(this).text().trim().toLowerCase() == "if"))
                        {
                            delete_end_marker = start - 1;
                            //alert(start - 1);
                            end_flag = 1;
                        }
                    }
                }
            }
            else if($(this).text().trim().toLowerCase() == "else")
            {
                current_selected_segment = "else";
                if ($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    start_else_total_spaces = $(this).attr("id");
                    delete_start_marker = start;
                    //alert(start);

                }
                else
                {
                    if(end_flag == 0)
                    {
                       var total_spaces = $(this).attr("id");
                        if(total_spaces <= start_else_total_spaces)
                        {
                            delete_end_marker = start - 1;
                            //alert(start - 1);
                            end_flag = 1;
                        }
                    }
                }
            }
            else if($(this).text().trim() == "THEN" )
            {
                current_selected_segment = "THEN";
            }
            else
            {
                //user selects a condition to delete
                if($(this).attr("class") == "ui-widget-content ui-selected" && current_selected_segment.toLowerCase() == "if" )
                {
                    //selected condition is yet assigned.
                    if($(this).text().trim() == "Click here to edit condition")
                    {
                        $('#label_alert_message').text("You are not allowed to remove an empty condition");
                        $('#div_alert_message').dialog('open');
                        //alert("You are not allowed to remove an empty condition");
                    }
                    else
                    {
                       //retrieving total spaces before the condition                       
                       var total_initial_spaces = $(this).attr("id");
                       
                        var initial_spaces = "";
                        for(i = 0 ; i < total_initial_spaces ; i++){
                            initial_spaces = initial_spaces + "&nbsp;";
                        }
                        //appending an empty condition
                        $(this).before("<li class='ui-widget-content' id='"+total_initial_spaces+"'>"+initial_spaces+"Click here to edit condition</li>");
                        //removing current selected condition
                        $(this).remove();
                        $('#label_alert_message').text("Your selected condition is successfully removed.");
                        $('#div_alert_message').dialog('open');
                        //alert("Your selected condition is successfully removed.");
                        //clearing natural language panel, code panel, parameter table and tree
                        $('#changing_stmt').html("");
                        $('#code_stmt').html("");
                        $('#parameters_table').html("");
                        $("li", $("#demo1")).each(function ()
                        {
                            $(this).hide();
                        });
                    }                    
                    
                    is_allowed_delete = 0;
                    return false;
                }
                if($(this).attr("class") == "ui-widget-content ui-selected" && (current_selected_segment.toLowerCase() == "then" || current_selected_segment.toLowerCase() == "else") )
                {
                    //selected condition is empty.
                    if($(this).text().trim() == "Click here to edit action")
                    {
                        $('#label_alert_message').text("You are not allowed to remove an empty action");
                        $('#div_alert_message').dialog('open');
                        //alert("You are not allowed to remove an empty action");
                    }
                    else
                    {
                       var total_initial_spaces = $("#selectable .ui-selected").attr("id");
                       var initial_spaces = "";
                        for(i = 0 ; i < total_initial_spaces ; i++){
                            initial_spaces = initial_spaces + "&nbsp;";
                        }
                        var next_item_exists = false;
                        var previous_item_exists = false;
                        if($(this).attr("id") == $(this).prev("li").attr("id"))
                        {
                            previous_item_exists = true;
                        }
                        if($(this).next("li") != null && $(this).attr("id") == $(this).next("li").attr("id"))
                        {
                            next_item_exists = true;
                        }
                        if(!next_item_exists && !previous_item_exists)
                        {
                            //appending an empty condition
                            $(this).before("<li class='ui-widget-content' id='"+total_initial_spaces+"'>"+initial_spaces+"Click here to edit action</li>");
                        
                        }
                        //removing current selected condition
                        $(this).remove();
                        $('#label_alert_message').text("Your selected action is successfully removed.");
                        $('#div_alert_message').dialog('open');
                        //alert("Your selected action is successfully removed.");
                        //clearing natural language panel, code panel, parameter table and tree
                        $('#changing_stmt').html("");
                        $('#code_stmt').html("");
                        $('#parameters_table').html("");
                        $("li", $("#demo1")).each(function ()
                        {
                            $(this).hide();
                        });
                    }                    
                    
                    is_allowed_delete = 0;
                    return false;
                }
                else if($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    then_else_removal_total_spaces = $(this).attr("id");
                    then_else_removal_index = start;
                    end_flag = 1;
                    delete_start_marker = start;
                    delete_end_marker = start;
                    return false;
                }
            }
            start++;
        });
    });

    //alert("delete_start_marker"+delete_start_marker+";delete_end_marker"+delete_end_marker);

    if(then_else_removal_index > -1)
    {
        var parent_node_index = -1;
        var node_counter = 1;
        $('#selectable').each(function()
         {
            $("li", $(this)).each(function ()
            {
                if( $(this).text().trim() != "{" || $(this).text().trim() != "}" || $(this).text().trim() != "(" || $(this).text().trim() != ")" )
                {
                    var total_spaces = $(this).attr("id");
                    if(total_spaces < then_else_removal_total_spaces && node_counter < then_else_removal_index)
                    {
                        parent_node_index = node_counter;
                    }
                }
                node_counter++;
            });
         });
         //alert("parent node index: "+parent_node_index+ " and then_else_removal_index:"+then_else_removal_index);
         node_counter = 1;
         var total_nodes = 0;
         var flag = 1;
         $('#selectable').each(function()
         {
            $("li", $(this)).each(function ()
            {
                if( $(this).text().trim() != "{" || $(this).text().trim() != "}" || $(this).text().trim() != "(" || $(this).text().trim() != ")" )
                {
                    if(node_counter > parent_node_index && flag == 1)
                    {
                        var node_child_total_spaces = $(this).attr("id");
                        //alert("node:"+$(this).text());
                        //alert("child space:"+node_child_total_spaces+" and parent space : "+then_else_removal_total_spaces);
                        if(node_child_total_spaces == then_else_removal_total_spaces){
                            total_nodes++;
                        }
                        else if(node_child_total_spaces <= then_else_removal_total_spaces)
                        {
                            flag = 0;
                        }
                    }
                }
                node_counter++;
            });
         });
         //alert("total nodes:"+total_nodes);
    }

    if(total_nodes == 1)
    {
        $('#selectable').each(function()
        {
            $("li", $(this)).each(function ()
            {
                if ($(this).attr("class") == "ui-widget-content ui-selected")
                {
                    $(this).remove();        
                }
            });
        });
        $('#label_alert_message').text("Your selected action is successfully removed.");
        $('#div_alert_message').dialog('open');
        //alert("Your selected action is successfully removed.");
        return;
        
        //alert("You are not allowed to remove expression");
        //is_allowed_delete = 0;
        //return false;
    }

    //alert("delete_start_marker:"+delete_start_marker);
    //alert("delete_end_marker:"+delete_end_marker);

    if(is_allowed_delete == 1)
    {
        if(end_flag == 0)
        {
            delete_end_marker = start - 1;
            //alert(start - 1);
        }

        //since we have removed whole expression on the left panel we have to add defaut expreesion
        if(delete_start_marker == 1 && delete_end_marker == start-1)
        {
            $("#selectable li:last-child").after("<li class='ui-widget-content' id='0'>Click here to edit block</li>");
        }


        var delete_marker = 1;
        $('#selectable').each(function()
         {
            $("li", $(this)).each(function ()
            {
                if(delete_marker >= delete_start_marker && delete_marker <= delete_end_marker)
                {
                    $(this).remove();
                }
                delete_marker++;
            });
         });
    }
    return false;
}

/*
 * User selects menu item to save project left panel content
 **/
function download_project()
{
    updateClientEndOperationCounter();
    //project left panel content
    var left_panel_content = $("#selectable").html();
    $.blockUI({
        message: '',
        theme: false,
        baseZ: 500
    });
    //saving project left panel into server
    $.ajax({
        type: "POST",
        url: "../../general_process/save_project_left_panel_and_variables",
        data: {
            code: left_panel_content
        },
        success: function (ajaxReturnedData) 
        {
            if(ajaxReturnedData === "true")
            {
                $('#download_project_div_modal').dialog('open');
                document.getElementById("project_content_file_name").value = "";
            }
            else
            {
                $('#label_alert_message').text("Server processing error. Please try again.");
                $('#div_alert_message').dialog('open');
                //alert("Server processing error. Please try again.");
            }
            $.unblockUI();
        }
    }); 
}

function upload_project()
{
    $('#upload_project_div').dialog('open');
}
function button_yes_clicked_upload_project()
{
    $('#upload_project_project_left_panel_content').val($("#selectable").html());
    return true;
}
function button_no_clicked_upload_project()
{
    $('#upload_project_project_left_panel_content').val("");
    return true;
}

/*
 * User wants to add a new variable from menu item 
 **/
function add_variables()
{
    updateClientEndOperationCounter();
    //resetting fields and display relevant fields
    document.getElementById('add_variable_name').value = "";
    document.getElementById("add_variable_type_selection_combo").selectedIndex = 0;
    
    document.getElementById("add_variable_value_selection_combo").selectedIndex = 0;
    document.getElementById("add_variable_value_selection_combo").style.visibility='visible';
        
    document.getElementById('add_variable_value_label').style.visibility='hidden';
    document.getElementById('add_variable_value_text').style.visibility='hidden';
    $('#add_variables_div').dialog('open');
    
}

/*
 * User wants to delete bracket from left panel or (condition/action in) natural language Panel
 **/
function delete_bracket()
{
    updateClientEndOperationCounter();
    //selected bracket to be removed is from (condition/action in) natural language Panel starts
    var start_bracket_id = "";
    var end_bracket_id = "";
    $("a", $('#changing_stmt')).each(function ()
    {
        if ($(this).attr("class") == "selected_expression" && ($(this).attr("title").indexOf("startbracket") >= 0 || $(this).attr("title").indexOf("endbracket") >= 0))
        {
            start_bracket_id = $(this).attr("title").substring(0,4);
            end_bracket_id = $(this).attr("title").substring(5,9);            
            return;
        }
    });
    if(start_bracket_id != "" && end_bracket_id != "")
    {
        //removing bracket from four panels
        $("a", $("#changing_stmt")).each(function () 
        {
            if ($(this).attr("id") == start_bracket_id) 
            {
                $(this).remove();            
            }
            else if ($(this).attr("id") == end_bracket_id) 
            {
                $(this).remove(); 
            }
        });
        $("a", $("#code_stmt")).each(function () 
        {
            if ($(this).attr("id") == start_bracket_id) 
            {
                $(this).remove();            
            }
            else if ($(this).attr("id") == end_bracket_id) 
            {
                $(this).remove(); 
            }

        });
        $("a", $("#selectable .ui-selected")).each(function ()
        {
            if ($(this).attr("id") == start_bracket_id) 
            {
                $(this).remove();            
            }
            else if ($(this).attr("id") == end_bracket_id) 
            {
                $(this).remove(); 
            }
        });
        $("div", $("#selectable .ui-selected")).each(function ()
        {
            $("input", $(this)).each(function ()
            {
                if ($(this).attr("id") == start_bracket_id) 
                {
                    $(this).remove();            
                }
                else if ($(this).attr("id") == end_bracket_id) 
                {
                    $(this).remove(); 
                } 
            });

        });
        $('#label_alert_message').text("Your selected bracket is removed.");
        $('#div_alert_message').dialog('open');
        //alert("Your selected bracket is removed.");
        return;
    }
    //selected bracket to be removed is from (condition/action in) natural language Panel ends
    
    
    //user wants to remove ending bracket from left panel
    if($('#selectable .ui-selected').text().trim() == ")" || $('#selectable .ui-selected').text().trim() == "}")
    {
        $('#label_alert_message').text("Please select starting breaket to remove.");
        $('#div_alert_message').dialog('open');
        //alert("Please select starting breaket to remove.");
        return;
    }
    //user wants to remove starting bracket from left panel
    if($('#selectable .ui-selected').text().trim() == "(" || $('#selectable .ui-selected').text().trim() == "{")
    {
        //total number of list items on left panel
        var total_list_items = $("#selectable > li").size();
        var end_breaket = "";
        if($('#selectable .ui-selected').text().trim() == "(")
        {
            end_breaket = ")";
        }
        else if($('#selectable .ui-selected').text().trim() == "{")
        {
            end_breaket = "}";
        }

        var selected_expression_starting_spaces = $('#selectable .ui-selected').text().length - $('#selectable .ui-selected').text().trim().length;
        var currentItem = $('#selectable .ui-selected');
        var next_item_starting_space = 0;
        var list_item_counter = 1;
        while(true)
        {
            currentItem = currentItem.next("li");
            next_item_starting_space = currentItem.text().length - currentItem.text().trim().length;
            if(currentItem.text().trim() == end_breaket && next_item_starting_space == selected_expression_starting_spaces)
            {
                $('#selectable .ui-selected').remove();
                currentItem.remove();
                $('#label_alert_message').text("Your selected bracket is removed.");
                $('#div_alert_message').dialog('open');
                //alert("Your selected bracket is removed.");
                return;
            }
            if(list_item_counter++ > total_list_items)
            {
                return;
            }
        }
    }
    $('#label_alert_message').text("Please select a bracket to delete.");
    $('#div_alert_message').dialog('open');
    //alert("Please select a bracket to delete.");
    return;
}

function load_project_list()
{
    updateClientEndOperationCounter();
    $('#load_projects_confirmation_window_div_modal').dialog('open');
}
function button_pre_load_project_ok_pressed()
{
    updateClientEndOperationCounter();
    document.getElementById('pre_load_project_left_panel_content').value = $("#selectable").html();        
    return true;
}

function save_as()
{
    updateClientEndOperationCounter();
    document.getElementById("save_as_project_project_name").value = "";
    document.getElementById("save_as_project_left_panel_content").value = $("#selectable").html();
    $('#save_as_project_div_modal').dialog('open');
}

function save_as_project_save_button_clicked()
{
    updateClientEndOperationCounter();
    var counter = 0 ;
    var save_as_project_name = document.getElementById("save_as_project_project_name").value;
    
    var projectNameRegExp = /^[a-z0-9]+$/i;
    if(projectNameRegExp.test(save_as_project_name)==false)
    {
        $('#label_alert_message').text("Please enter a valid project name.");
        $('#div_alert_message').dialog('open');
        //alert( "Please enter a valid project name." );
        return false;
    }
    
    for(counter = 0 ; counter < project_name_list.length ; counter++)
    {
        if(project_name_list[counter] == save_as_project_name)
        {
            $('#save_as_project_div_modal').dialog('close');
            $('#save_as_replace_project_div_modal').dialog('open');
            document.getElementById("save_as_replace_project_name").value = project_name_list[counter];
            document.getElementById("save_as_replace_project_id").value = project_id_list[counter];
            return false;
        }
    }
    return true;
}

function save_as_replace_project_yes_button_clicked()
{
    updateClientEndOperationCounter();
    document.getElementById("save_as_replace_project_left_panel_content").value = $("#selectable").html();
    return true;
}

function save_as_replace_project_no_button_clicked()
{
    updateClientEndOperationCounter();
    $('#save_as_replace_project_div_modal').dialog('close');
    return false;
}

function show_variables()
{
    updateClientEndOperationCounter();
    $('#project_variable_list_div').dialog('open');
}
