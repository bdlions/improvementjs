<div class='container-fluid'>
    <div class="page-header">
        <h2><?php echo $title; ?></h2>
    </div>
    <?php if($message != NULL):?>
    <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif;?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Please enter the User's informations below
        </div>
        <div class="panel-body">
            <div class="col-md-offset-1 col-md-8">
                <?php echo form_open("admin/create_user", "class='form-horizontal' role='form'"); ?>
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
                    <div class="pull-right col-md-4">
                        <input type="submit" name="submit" value="Create User" class="form-control btn-success">
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    <div class="padding_horiz_thin"></div>
    <div class="padding_horiz_thin"></div>
    <div class="padding_horiz_thin"></div>
</div>
    
<!--    
<div class='box'>

    <h1><?php echo $title; ?></h1>
    <p>Please enter the users information below.</p>

    <div id="infoMessage"><?php echo $message; ?></div>

    <?php echo form_open("admin/create_user"); ?>

    <fieldset>
        <legend>User creation information</legend>
        <table border="0" width="100%" >
            <tr>
                <td width="16%"><label>User Name:</label></td>
                <td width="84%"> <?php echo form_input($user_name); ?></td>
            </tr>
            <tr>
                <td><label>First Name:</label></td>
                <td><?php echo form_input($first_name); ?></td>
            </tr>
            <tr>
                <td><label>Last Name: </label></td>
                <td><?php echo form_input($last_name); ?></td>
            </tr>
            <tr>
                <td><label>Email:</label></td>
                <td><?php echo form_input($email); ?></td>
            </tr>
            <tr>
                <td><label>Password:</label></td>
                <td><?php echo form_input($password); ?></td>
            </tr>
            <tr>
                <td><label>Confirm Password: </label></td>
                <td><?php echo form_input($password_confirm); ?></td>
            </tr>
            <tr>
                <td><label>Country:</label></td>
                <td><?php echo form_dropdown('countries', $countries); ?></td>
            </tr>
        </table>
        <?php echo form_submit('submit', 'Create User'); ?>
    </fieldset>
    <?php echo form_close(); ?>
</div>-->
