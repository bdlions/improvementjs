<div class="container-fluid">
    <div class="page-header">
        <h3>Create User</h3>
    </div>
    <?php if($message != NULL):?>
    <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif;?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Please enter the users information
        </div>
        <div class="panel-body">
            <div class="col-md-offset-1 col-md-8">
                <?php echo form_open("auth/create_user", "class='form-horizontal' role='form'"); ?>
                <div class="form-group">
                    <label class="col-md-4 control-label">User Name</label>
                    <div class="col-md-8">
                        <?php echo form_input($user_name + array('class' => 'form-control', 'placeholder' => 'Username', 'type' => 'text')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">First Name</label>
                    <div class="col-md-8">
                        <?php echo form_input($first_name + array('class' => 'form-control', 'placeholder' => 'First name', 'type' => 'text')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Last Name</label>
                    <div class="col-md-8">
                        <?php echo form_input($last_name + array('class' => 'form-control', 'placeholder' => 'Last name', 'type' => 'text')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Email</label>
                    <div class="col-md-8">
                        <?php echo form_input($email + array('class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Confirm Email</label>
                    <div class="col-md-8">
                        <?php echo form_input($email_confirm + array('class' => 'form-control', 'placeholder' => 'Re-type Email', 'type' => 'email')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Password</label>
                    <div class="col-md-8">
                        <?php echo form_input($password + array('class' => 'form-control', 'placeholder' => 'Password', 'type' => 'password')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Confirm Password</label>
                    <div class="col-md-8">
                        <?php echo form_input($password_confirm + array('class' => 'form-control', 'placeholder' => 'Re-type Password', 'type' => 'password')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-4 control-label">Country</label>
                    <div class="col-md-8">
                        <?php echo form_dropdown('countries', $countries, "", 'class=form-control'); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="pull-right col-md-3">
                        <?php echo form_input($submit_create_user+array('class'=>'form-control btn-success'));?>
                    </div>
                </div>
                <div class="padding_horiz_thin"></div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>