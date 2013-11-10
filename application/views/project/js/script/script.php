<div class='mainInfo'>

    <h1>Scripts</h1>
    <div id="infoMessage"><?php if (isset($message)) echo $message; ?></div>
    <table>
        <caption>Below is a list of scripts</caption>
        <thead>
            <tr>
                <th>Script Name</th>
                <th>Type</th>
                <th>Open</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($scripts as $script): ?>
                <tr>
                    <td><?php echo $script->project_name; ?></td>
                    <td><?php echo $script->description; ?></td>
                    <td><?php echo anchor("scripts/load_script/" . $script->project_id, 'Open'); ?></td>
                    <td><?php echo anchor("scripts/delete_script/" . $script->project_id, 'Delete'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
