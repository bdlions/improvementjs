<div class='box'>
    <p>Are you sure you want to delete this project?</p>
    <?php echo form_open("auth/delete_project/".$project_id);?>
        <input type="submit" id="delete_project_yes" name="delete_project_yes" value="Yes"/>
        <input type="submit" id="delete_project_no" name="delete_project_no" value="No"/>
    <?php echo form_close();?>
</div>