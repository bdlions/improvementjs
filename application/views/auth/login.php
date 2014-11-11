<div class="row" style="padding-top: 40px;">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading"> <strong class="">Login</strong>

            </div>
            <div class="panel-body">
                <?php echo form_open("auth/login", "class='form-horizontal' role='form'"); ?>
                <div class="form-group">
                    <label for="inputEmail3" class="col-md-3 control-label">Email</label>
                    <div class="col-md-9">
                        <?php echo form_input($identity + array('class' => 'form-control', 'placeholder' => 'Email', 'type' => 'email')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-md-3 control-label">Password</label>
                    <div class="col-md-9">
                        <?php echo form_input($password + array('class' => 'form-control', 'placeholder' => 'Password', 'type' => 'password')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-3 col-md-9">
                        <div class="checkbox">
                            <label style="float: left;"><?php echo form_checkbox('remember', 'remember', $remember); ?>Remember me</label>
                        </div>
                    </div>
                </div>
                <div class="form-group last">
                    <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn btn-success btn-md" name="submit">Login</button> <?php echo anchor('auth/forgot_password', 'Forgot password', 'title="Forgot password"'); ?>? or <?php echo anchor('auth/forgot_user_name', 'Forgot email', 'title="Forgot email"'); ?>?
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
            <div class="panel-footer">Not Registered? <a href="#" class=""><?php echo anchor('auth/create_user', 'Create an account', 'title="Create an account"'); ?></a>
            </div>
        </div>
    </div>
</div>
