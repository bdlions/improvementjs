<div class='box'>

    <h1>Create Project</h1>
    <p>Please enter the project information below.</p>

    <div id="infoMessage" style="color:red"><?php echo $message;?></div>

    <?php echo form_open("welcome/create_project");?>
        <fieldset>
            <legend>Project creation information</legend>

            <label>Project Name: </label>
            <?php echo form_input($project_name);?><br/>                
            <?php echo form_submit('submit', 'Create Project');?>
        </fieldset>
    <?php echo form_close();?>

</div>
