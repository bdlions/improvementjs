<div class="container-fluid">
    <div class="page-header">
        <h2><?php echo $title; ?></h2>
    </div>
    <?php if (isset($message) && ($message != NULL)): ?>
        <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="">
        <?php echo form_open('admin', 'class="form-horizontal" role="form"'); ?>
        <div class="row">
            <div class="col-md-offset-1 col-md-8">
                <div class="form-group">
                    <label class="control-label col-md-4">
                        User Name
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input($user_name + array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        First Name
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input($first_name + array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        Last Name
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input($last_name + array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        Email Address
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input($email + array('class' => 'form-control', 'type' => 'email')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        Registration Date
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input($created_date + array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        IP Address
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input($ip_address + array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        Browser
                    </label>
                    <div class="col-md-8">
                        <?php echo form_input($browser + array('class' => 'form-control')); ?>
                    </div>
                </div>
                <div class="padding_horiz_thin"></div>
                <div class="form-group">
                    <label class="control-label col-md-4">
                        Country
                    </label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('countries', $countries, $selected_country, 'class="form-control" disabled="disabled"'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="pull-right col-md-4">
                        <?php echo form_submit('submit', 'Submit', 'class="form-control btn-success"'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="padding_horiz_thin"></div>
        <div class="padding_horiz_thin"></div>
        <div class="padding_horiz_thin"></div>
        <?php echo form_close(); ?>
    </div>
</div>
