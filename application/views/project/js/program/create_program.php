<div class="container-fluid">
    <div class="page-header">
        <h3>Create Program</h3>
    </div>
    <?php if($message != NULL):?>
    <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif;?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Please enter the program information
        </div>
        <div class="panel-body">
            <div class="col-md-offset-1 col-md-8">
                <?php echo form_open("projects/create_program", "class='form-horizontal' role='form'"); ?>
                <div class="form-group">
                    <label class="col-md-4 control-label">Program Name</label>
                    <div class="col-md-8">
                        <?php echo form_input($program_name + array('class' => 'form-control', 'type' => 'text')); ?>
                    </div>
                </div>                
                <div class="form-group">
                    <div class="pull-right col-md-3">
                        <?php echo form_input($submit_create_program+array('class'=>'form-control btn-success'));?>
                    </div>
                </div>
                <div class="padding_horiz_thin"></div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>