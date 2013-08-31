<?php if(isset($error)) echo $error['error'] ?>
<?php echo form_open_multipart('welcome/upload_configuration_file');?>
    <table>
        <tr>
            <td >     
                <label>Upload an XML: </label>

                    <input type="file" name="userfile" size="20" />

            </td>
        </tr>
        <tr><td align="right"><input type="submit" value="upload" /></td></tr>
    </table>
 </form>    