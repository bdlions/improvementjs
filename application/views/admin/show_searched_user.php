<div class='mainInfo'>

    <h1><?php echo $title; ?></h1>
    <div id="infoMessage"><?php echo $message; ?></div>
    <table>
        <caption>Below is a list of the users</caption>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
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
    <p><a href="<?php echo site_url('admin/create_user'); ?>">Create a new user</a></p>

</div>
