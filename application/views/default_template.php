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
            <div class="row">
                <div class="col-md-offset-1 col-md-10">
                    <div class="header">
                        <div class="col-md-12">
                            <h3>NATURAL LANGUAGE PROCESSING</h3>
                            <h4>Code Generation</h4>
                        </div>
                    </div>

                    <?php
                    $this->load->view($main_content);
                    ?>
                    <p class="navbar navbar-fixed-bottom navbar-default" style="text-align: center;">All rights reserved and &copy; by bdlions, 2013</p>
                </div>
            </div>
        </div>
    </body>
</html>