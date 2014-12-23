<div class="container-fluid">
    <h1>User profile</h1>
    <?php if ($message != NULL): ?>
        <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php echo form_open('auth', "class='form-horizontal' role='form'"); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            User information
        </div>
        <div class="panel-body">
            <div class="row form-group">
                <label class="col-md-3 control-label">User Name:</label>
                <label class="col-md-3 control-label" style="text-align:left"><?php echo $user_info['username']?></label>
            </div>
            <div class="row form-group">
                <label class="col-md-3 control-label">First Name:</label>
                <label class="col-md-3 control-label" style="text-align:left"><?php echo $user_info['first_name']?></label>
            </div>
            <div class="row form-group">
                <label class="col-md-3 control-label">Last Name:</label>
                <label class="col-md-3 control-label" style="text-align:left"><?php echo $user_info['last_name']?></label>
            </div>
            <div class="row form-group">
                <label class="col-md-3 control-label">Email:</label>
                <label class="col-md-3 control-label" style="text-align:left"><?php echo $user_info['email']?></label>
            </div>
            <div class="row form-group">
                <label class="col-md-3 control-label">Registration Date:</label>
                <label class="col-md-3 control-label" style="text-align:left"><?php echo $user_info['created_date']?></label>
            </div>
            <div class="row form-group">
                <label class="col-md-3 control-label">Country:</label>
                <label class="col-md-3 control-label" style="text-align:left"><?php echo $user_info['country']?></label>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

