<?php if(isset($message)) echo $message ?>
<?php echo form_open_multipart('projects/upload_project/'.$project_id);?>
    <table>
        <tr>
            <td >     
                <label>Upload a project: </label>

                    <input type="file" name="userfile" size="20" />

            </td>
        </tr>
        <tr><td style="text-align:right"><input type="submit" name="cancel" id="cancel" value="Cancel"/></td><td style="text-align:right"><input type="submit" name="upload" id="upload" value="upload" /></td></tr>
    </table>
 </form>    