<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>
            <?php
                if(empty($title))
                {
                    echo "Language processing";
                }
                else
                {
                    echo $title;
                }
            ?>
        </title>        
        <link rel="stylesheet" href="<?php echo base_url();?>css/template_style.css" />
        <?php

            //adding extra css if needs to load
            //a specific view
            if(!empty($css))
            {
                echo $css;
            }

            //lood some extra js if needed
            if(!empty($js))
            {
                echo $js;
            }

        ?>
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/jquery-ui.css'/>
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>jstree_resource/design.css' />
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>jstree_resource/menu_style.css' />
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>jstree_resource/_docs/syntax/!style.css'/>
        
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

    </head>
    <body>
        <div class="BackgroundGradient"></div>
        <div class="BodyContent">
            <div class="BorderBorder">
                <div class="BorderBL"><div></div></div>
                <div class="BorderBR"><div></div></div>
                <div class="BorderTL"></div>
                <div class="BorderTR"><div></div></div>
                <div class="BorderT"></div>
                <div class="BorderR"><div></div></div>
                <div class="BorderB"><div></div></div>
                <div class="BorderL"></div>
                <div class="BorderC"></div>
                <div class="Border">
                    <div class="Header">
                        <div class="HeaderTitle">
                            <h1>
                                <?php
                                if(empty($first_header))
                                { 
                                    echo "NATURAL LANGUAGE PROCESSING";
                                }
                                else
                                {
                                    echo $first_header;
                                }
                                ?>
                            </h1>
                            <h2>
                                <?php
                                    if(empty($second_header))
                                    {
                                        echo "Code Generation";
                                    }
                                    else
                                    {
                                        echo $second_header;
                                    }
                                  ?>
                            </h2>
                        </div>
                    </div>
                    <div class="Menu">
                        <?php
                            if(!empty($menu_bar))
                            {
                                $this->load->view($menu_bar);
                            }
                            ?>
                    </div>
                    <div style="clear: both"/>
                    <div class="Columns">
                        <!-- Start of column1 -->
                        <div class="Column1">
                            <?php
                                if(!empty($left_side_bar))
                                {
                                    $this->load->view($left_side_bar);
                                }
                              ?>
                        </div>
                        <!--end of column1-->

                        <!-- Start of main div-->
                        <div class="MainColumn">
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
                        <!-- End of main div -->
                    </div>
                    <div class="Footer">
                        All rights reserved and &copy; 2013 by bdlions
                    </div>
                    <span class="BackLink"></span>
                </div>
            </div>
        </div>
    </body>
</html>