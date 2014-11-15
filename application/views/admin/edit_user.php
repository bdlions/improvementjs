<div class="container-fluid">
    <div class="page-header">
        <h2><?php echo $title; ?></h2>
    </div>

    <?php if (isset($message) && ($message != NULL)): ?>
        <div class="alert alert-danger alert-dismissible"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Please enter the user information below to update</strong>
        </div>
        <div class="panel-body">
            <?php echo form_open('admin', 'class="form-horizontal" role="form"'); ?>
            <div class="row">
                <div class="col-md-offset-1 col-md-8">
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
                            Password
                        </label>
                        <div class="col-md-8">
                            <?php echo form_input($password + array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">
                            Confirm Password
                        </label>
                        <div class="col-md-8">
                            <?php echo form_input($password_confirm + array('class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">
                            Group
                        </label>
                        <div class="col-md-8">
                            <?php echo form_dropdown('groups', $groups, $selected_group, 'class="form-control"'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">
                            Country
                        </label>
                        <div class="col-md-8">
                            <?php echo form_dropdown('countries', $countries, $selected_country, 'class="form-control"'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="pull-right col-md-4">
                            <?php echo form_submit('submit', 'Update User', 'class="form-control btn-success"'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        
    </div>
        <div class="padding_horiz_thin"></div>
        <div class="padding_horiz_thin"></div>
        <div class="padding_horiz_thin"></div>
</div>

<!--
<div class='box'>

    <h1><?php echo $title; ?></h1>
    <p>Please enter the user information below to update</p>
    <div id="infoMessage"><?php echo $message; ?></div>
    <?php echo form_open('admin/edit_user/'.$user_id); ?>

    <fieldset>
        <legend>User update information</legend>
        <table border="0" width="100%" >
            <tr>
                <td><label>First Name:</label></td>
                <td><?php echo form_input($first_name); ?></td>
            </tr>
            <tr>
                <td><label>Last Name: </label></td>
                <td><?php echo form_input($last_name); ?></td>
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
                <td><label>Group:</label></td>
                <td><?php echo form_dropdown('groups', $groups, $selected_group); ?></td>
            </tr>
            <tr>
                <td><label>Country:</label></td>
                <td><?php echo form_dropdown('countries', $countries, $selected_country); ?></td>
            </tr>
        </table>
        <?php echo form_submit('submit', 'Update User'); ?>
    </fieldset>
    <?php echo form_close(); ?>

</div>-->
