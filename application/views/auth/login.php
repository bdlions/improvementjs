<div class="row" style="padding-top: 40px;">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong class="">Login</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <?php if ($message != NULL): ?>
                        <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
                    <?php endif; ?>
                    <div class="col-md-10 col-md-offset-1">
                        <?php echo form_open("auth/login", "class='form-horizontal' role='form'"); ?>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-md-4 control-label">Email/Username</label>
                            <div class="col-md-8">
                                <?php echo form_input($identity + array('class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-md-4 control-label">Password</label>
                            <div class="col-md-8">
                                <?php echo form_input($password + array('class' => 'form-control', 'placeholder' => 'Password', 'type' => 'password')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-4">
                            </div>
                            <div class="col-md-2 pull-right">
                                <button style="width: 100%" type="submit" class="btn btn-success btn-md" name="submit">Login</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <?php echo anchor('auth/forgot_password', 'Forgot password?', 'title="Forgot password"'); ?> &nbsp; or &nbsp; <?php echo anchor('auth/forgot_user_name', 'Forgot username?', 'title="Forgot username"'); ?>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-offset-4 col-md-8">
                        Not Registered? <a href="#" class=""><?php echo anchor('auth/create_user', 'Create an account', 'title="Create an account"'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
