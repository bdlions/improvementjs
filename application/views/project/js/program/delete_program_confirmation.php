<div class='box'>
    <p>Are you sure you want to delete this program?</p>
    <?php echo form_open("programs/delete_program/".$project_id);?>
        <?php echo form_submit($submit_button_yes); ?>
        <?php echo form_submit($submit_button_no); ?>
    <?php echo form_close();?>
</div>