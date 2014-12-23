<div class="container-fluid">
    <div class="page-header">
        <h1>Forgot Password</h1>
        <p>Please enter your email address so we can send you an email to reset your password.</p>
    </div>
    <?php if($message != NULL):?>
    <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif;?>
    <?php echo form_open("auth/forgot_password", "class='form-horizontal' role='form'");?>
    <div class="col-md-offset-1 col-md-6">
        <div class="form-group">
            <label class="control-label col-md-4">
                Email Address
            </label>
            <div class="col-md-8">
                <?php echo form_input($email + array('class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email')); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="pull-right col-md-2">
                <?php echo form_input($submit_send_email+array('class'=>'form-control btn-success'));?>
            </div>
        </div>
    </div>
    <?php echo form_close();?>
</div>