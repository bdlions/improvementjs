<?php if(isset($error)) echo $error['error'] ?>
<?php echo form_open_multipart('project/post_upload_project');?>
    <table>
        <tr>
            <td >     
                <label>Upload a project: </label>

                    <input type="file" name="userfile" size="20" />

            </td>
        </tr>
        <tr><td style="text-align:right"><input type="submit" name="cancel" id="cancel" value="Cancel"/></td><td style="text-align:right"><input type="submit" value="upload" /></td></tr>
    </table>
 </form>    