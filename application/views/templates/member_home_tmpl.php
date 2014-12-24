<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Language Processing</title>      
        
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>resources/css/jquery-ui.css'/>
        <link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>resources/css/bootstrap.min.css">
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>resources/css/styles.css'/>
        <link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>resources/css/template.css">
        <link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>resources/css/template_style.css" />
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>resources/css/design.css' />
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>resources/css/menu_style.css' />
        
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>jstree_resource/_docs/syntax/!style.css'/>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery-ui.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/bootstrap-hover-dropdown.js"></script>
        
        
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/beautify.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/custom_script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/logical_connector_script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/arithmetic_operator_script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/menu_item_script.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/_lib/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/_lib/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/jquery.jstree.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/_docs/syntax/!script.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery.xml2json.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/parse_feature_xml.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/feature.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/parameter.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/code_process.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/manage_variables.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/common.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/parameter_table.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/manage_action.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/variable.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/impjs/program/block.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/impjs/program/scripts.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/impjs/program/script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/impjs/program/project.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/impjs/program/stage.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/impjs/program/sprites.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/script/impjs/program/sprite.js"></script>
    </head>
    <body>

        <div class="container">
            <div class="row header">
            <?php
                $this->load->view("templates/sections/header");
            ?>
            </div>
            <div clas="row" style="padding-top: 2px; padding-bottom: 5px;">
            <?php
                $this->load->view("design/menu_bar_member_home");
            ?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <?php echo $contents; ?>
                </div>                
            </div>   
            <?php
                $this->load->view("templates/sections/footer");
            ?>
        </div>
    </body>
</html>