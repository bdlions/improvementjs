<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Language Processing</title>
        <link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>resources/css/jquery-ui.css'/>
        <link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>resources/css/bootstrap.min.css">
        <link rel="stylesheet" type='text/css' href="<?php echo base_url(); ?>resources/css/template.css">
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/jquery-ui.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>resources/js/bootstrap-hover-dropdown.js"></script>
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
                $this->load->view("design/menu_bar_external_user");
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