<div class="container-fluid">
    <h1>Edit User</h1>
    <?php if ($message != NULL): ?>
        <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php echo form_open('user/edit_user', "class='form-horizontal' role='form'"); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Update user information
        </div>
        <div class="panel-body">
            <div class="row form-group">
                <label class="col-md-4 control-label">First Name:</label>
                <div class="col-md-5">
                    <?php echo form_input($first_name + array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="row form-group">
                <label class="col-md-4 control-label">Last Name:</label>
                <div class="col-md-5">
                    <?php echo form_input($last_name + array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="row form-group">
                <label class="col-md-4 control-label">Password:</label>
                <div class="col-md-5">
                    <?php echo form_input($password + array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="row form-group">
                <label class="col-md-4 control-label">Confirm Password:</label>
                <div class="col-md-5">
                    <?php echo form_input($password_confirm + array('class' => 'form-control')); ?>
                </div>
            </div>
            <div class="row form-group">
                <label class="col-md-4 control-label">Country:</label>
                <div class="col-md-5">
                    <?php echo form_dropdown('countries', $countries, $selected_country, 'class="form-control"'); ?>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-7"></div>
                <div class="col-md-2">
                    <?php echo form_input($submit_update_user+array('class'=>'form-control btn-success'));?>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>