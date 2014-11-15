<div class="container-fluid">
    <div class="page-header">
        <h1>Projects</h1>
    </div>
    
    <?php if( isset($message) && ($message != NULL) ):?>
    <div class="alert alert-info alert-dismissible"><?php echo $message; ?></div>
    <?php endif;?>

    <table align="right" border="1" style="border-collapse:collapse" >
        <tr align="right" style="color:green">
            <td>
                 <?php echo form_open("admin/user_render_pagination/0");?>
                    <fieldset>
                        <label>Sort by status: </label>
                        <?php echo form_checkbox('status', 'accept', $sort_by_status);?>  
                        <label>Sort by registration date: </label>
                        <?php echo form_checkbox('registration_date', 'accept', $sort_by_registration_date);?>
                        <label>Descending: </label>
                        <?php echo form_checkbox('descending', 'accept', $sort_descending);?>
                        <?php echo form_submit('submit', 'Apply Filter');?>
                    </fieldset>
                <?php echo form_close();?>
            </td>
        </tr>
    </table>
    <table style="width:100%">
        <caption>Below is a list of the users</caption>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Registration Date</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Groups</th>
                <th>Status</th>
                <th>Detail</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user->username; ?></td>
                    <td><?php echo $user->email; ?></td>
                    <td><?php echo $user->created_date; ?></td>
                    <td><?php echo $user->first_name; ?></td>
                    <td><?php echo $user->last_name; ?></td>
                    <td>
                        <?php foreach ($user->groups as $group): ?>
                            <?php echo $group->name; ?><br />
                        <?php endforeach ?>
                    </td>
                    <td><?php echo ($user->active) ? anchor("admin/deactivate/" . $user->id, 'Active') : anchor("admin/activate/" . $user->id, 'Inactive'); ?></td>
                    <td><?php echo anchor("admin/show_user/" . $user->id, 'Show'); ?></td>
                    <td><?php echo anchor("admin/edit_user/" . $user->id, 'Edit'); ?></td>
                    <td><?php echo anchor("admin/delete_user/" . $user->id, 'Delete'); ?></td>
                </tr>
            <?php endforeach; ?>
                
        </tbody>                    
    </table>
    
    <?php 
        if(isset($pagination)){
            echo $pagination; 
        }
    ?>
    <ul class="list-group">
        <li class="list-group-item">
            <?php echo anchor('admin/user_render_pagination/0',"All")?>
        </li>
        <li class="list-group-item">
            <?php echo anchor('admin/create_user',"Create a new user")?>
        </li>
    </ul>
</div>
