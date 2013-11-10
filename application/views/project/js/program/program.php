<div class='mainInfo'>

    <h1>Programs</h1>
    <div id="infoMessage"><?php if (isset($message)) echo $message; ?></div>
    <table>
        <caption>Below is a list of programs</caption>
        <thead>
            <tr>
                <th>Program Name</th>
                <th>Type</th>
                <th>Open</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($programs as $program): ?>
                <tr>
                    <td><?php echo $program->project_name; ?></td>
                    <td><?php echo $program->description; ?></td>
                    <td><?php echo anchor("programs/load_program/" . $program->project_id, 'Open'); ?></td>
                    <td><?php echo anchor("programs/delete_program/" . $program->project_id, 'Delete'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
