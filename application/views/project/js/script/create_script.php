<div class='box'>

    <h1><?php echo $title;?></h1>
    <p>Please enter the script information below.</p>

    <div id="infoMessage" style="color:red"><?php echo $message;?></div>

    <?php echo form_open("scripts/create_script");?>
        <fieldset>
            <legend>Script creation information</legend>

            <label>Script Name: </label>
            <?php echo form_input($script_name);?><br/>                
            <?php echo form_submit('submit', 'Create Script');?>
        </fieldset>
    <?php echo form_close();?>

</div>
