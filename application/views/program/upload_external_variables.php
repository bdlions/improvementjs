<?php if(isset($error)) echo $error['error'] ?>
<?php echo form_open_multipart('welcome/upload_external_variables_post_processing');?>
    <table>
        <tr>
            <td >     
                <label>Upload a text file: </label>

                    <input type="file" name="userfile" size="20" />

            </td>
        </tr>
        <tr><td style="text-align:right"><input type="submit" name="cancel" id="cancel" value="cancel" /></td><td style="text-align:right"><input type="submit" name="upload" id="upload" value="upload" /></td></tr>
    </table>
 <?php echo form_close();?>