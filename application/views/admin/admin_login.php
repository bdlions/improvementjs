<div class="row" style="padding-top: 40px;">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-success">
            <div class="panel-heading">
                <strong class="">Admin Login</strong>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <?php echo form_open("admin/login", "class='form-horizontal' role='form'"); ?>
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
<!--                                <div class="checkbox">
                                    <label style="float: left;"><?php echo form_checkbox('remember', 'remember', $remember); ?>Remember me</label>
                                </div>-->
                            </div>
                            <div class="col-md-4">
                                <button style="width: 100%" type="submit" class="btn btn-success btn-md" name="submit">Login</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="form-group">
                            <div class="col-md-offset-4 col-md-8">
                                <?php echo anchor('auth/forgot_password', 'Forgot password?', 'title="Forgot password"');?> &nbsp; or &nbsp; <?php echo anchor('auth/forgot_user_name', 'Forgot user name?', 'title="Forgot user name"');?>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-offset-5 col-md-7">
                        <a href="#" class=""><?php echo anchor('auth/create_user', 'Create an account', 'title="Create an account"');?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
