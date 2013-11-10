<div class='box'>

    <h1><?php echo $title;?></h1>
    <p>Please enter the program information below.</p>

    <div id="infoMessage" style="color:red"><?php echo $message;?></div>

    <?php echo form_open("programs/create_program");?>
        <fieldset>
            <legend>Program creation information</legend>

            <label>Program Name: </label>
            <?php echo form_input($program_name);?><br/>                
            <?php echo form_submit('submit', 'Create Program');?>
        </fieldset>
    <?php echo form_close();?>

</div>
