<div class='box'>

    <h1>Create Variable</h1>
    <p>Please enter the variable information below.</p>

    <div id="infoMessage"><?php echo $message;?></div>

    <?php echo form_open("welcome/create_variable");?>
        
    <table border="0">  
        <tr>
            <td>Variable Name: </td>
            <td><?php echo form_input($variable_name);?><br/></td>
        </tr>
        <tr>
            <td>Variable Type: </td>
            <td align="right"><?php echo form_dropdown('variable_type',$variable_type); ?></td>
        </tr>
        <tr>
            <td>Variable Value: </td>
            <td><?php echo form_input($variable_value);?><br/> </td>
        </tr>
    </table>
    <p><?php echo form_submit('submit', 'Create Variable');?></p>
    <?php echo form_close();?>

</div>
