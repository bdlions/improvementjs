<div class='mainInfo'>

    <h1>Projects</h1>
    <div id="infoMessage"><?php if(isset($message)) echo $message; ?></div>

    <table>
        <caption>Below is a list of projects</caption>
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Type</th>
                <th>Open</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?php echo $project->project_name; ?></td>
                    <td><?php echo $project->description; ?></td>
                    <td><?php 
                    if( $project->description == "Program" )
                    {
                        echo anchor("programs/load_program/". $project->project_id, 'Open');
                    }
                    else if( $project->description == "Script" )
                    {
                        echo anchor("scripts/load_script/". $project->project_id, 'Open');
                    }                     
                    ?></td>
                    <td><?php 
                    if( $project->description == "Program" )
                    {
                        echo anchor("programs/delete_program/" . $project->project_id, 'Delete'); 
                    }
                    else if( $project->description == "Script" )
                    {
                        echo anchor("scripts/delete_script/". $project->project_id, 'Delete');
                    }
                    
                    ?></td>
                </tr>
            <?php endforeach; ?>
                    </tbody>
                    
                </table>


</div>
