<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            Language Processing
        </title>        
        <!-- Bootstrap -->
        <link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>resources/css/bootstrap.min.css">
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>resources/css/styles.css'/>
        <link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>resources/css/template.css">
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="<?php echo base_url();?>resources/js/jquery-1.11.1.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo base_url();?>resources/js/bootstrap.min.js"></script>
        
        <link rel="stylesheet" href="<?php echo base_url();?>css/template_style.css" />
        <link rel='stylesheet' href='<?php echo base_url(); ?>jstree_resource/design.css' />
        <link rel='stylesheet' href='<?php echo base_url(); ?>jstree_resource/menu_style.css' />
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/jquery-ui.css'/>
        <link type='text/css' rel='stylesheet' href='<?php echo base_url(); ?>jstree_resource/_docs/syntax/!style.css'/>
        
        
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/js/lib/beautify.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/parse_feature_xml.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/feature.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/parameter.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/code_process.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/_lib/jquery.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/_lib/jquery.cookie.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/_lib/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/jquery.jstree.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/_docs/syntax/!script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/custom_script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/manage_variables.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/common.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/parameter_table.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/manage_action.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/variable.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/logical_connector_script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/arithmetic_operator_script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>jstree_resource/menu_item_script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.blockUI.js" ></script>
        
        <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.xml2json.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/impjs/program/block.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/impjs/program/scripts.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/impjs/program/script.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/impjs/program/project.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/impjs/program/stage.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/impjs/program/sprites.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>script/impjs/program/sprite.js"></script>
        <?php
        //adding extra css if needs to load
        //a specific view
        if (!empty($css)) {
            echo $css;
        }

        //lood some extra js if needed
        if (!empty($js)) {
            echo $js;
        }
        ?>
    </head>
    <body>
        
        <div class="container">
            <div class="row header">
                        <?php 
            $this->load->view("admin/templates/sections/header");
            ?>
                    </div>
                    <div clas="row">
                        <?php
                            if(!empty($menu_bar))
                            {
                                $this->load->view($menu_bar);
                            }
                            ?>
                    </div>
<!--                    <div style="clear: both"/>-->
                    
                    
            
            <div class="row">
                <div class="col-md-4">
                <?php
                                if(!empty($left_side_bar))
                                {
                                    $this->load->view($left_side_bar);
                                }
                              ?>
                </div>
                <div class="col-md-8">
                    <?php
                                if(empty($main_content))
                                {
                                    $this->load->view("design/main_content");
                                }
                                else
                                {
                                    $this->load->view($main_content);
                                }
                              ?>
                </div>
            </div>   
            <?php 
            $this->load->view("admin/templates/sections/footer");
            ?>
            
        </div>
        
    </body>
</html>