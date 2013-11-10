<div class='box'>
    <p>Are you sure you want to delete this script?</p>
    <?php echo form_open("scripts/delete_script/".$project_id);?>
        <?php echo form_submit($submit_button_yes); ?>
        <?php echo form_submit($submit_button_no); ?>
    <?php echo form_close();?>
</div>