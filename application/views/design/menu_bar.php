<ul id="nav">
    <!--<li><?php //echo anchor('auth', 'Home');?></li>-->
    <li>
        <a href="#">Projects</a>
        <ul>
            <a onclick="load_project_list()">My Projects</a>
            <a onclick="save()">Save</a>
            <a onclick="save_as()">Save As</a>
            <a onclick="download_project()">Download Project</a>
            <a onclick="upload_project()">Upload Project</a>            
        </ul>
    </li>
    <li>
        <a href="#">Block</a>
        <ul>
            <a onclick="add_block()">Add</a>
        </ul>
    </li>
    <li>
        <a href="#">Action</a>
        <ul>
            <a onclick="add_action()">Add</a>
        </ul>
    </li>
    <li>
        <a href="#">Variable</a>
        <ul>
            <a onclick="add_variables()">Add</a>
            <a onclick="show_variables()">Show Variables</a>
        </ul>        
    </li>
    <li>
        <a href="#">Bracket</a>
        <ul>
            <a onclick="add_brackets()">Add</a>
            <a onclick="delete_bracket()">Delete</a>
        </ul>
    </li>
    <li>
        <a href="#">Arithmetic Operator</a>
        <ul>
            <a onclick="add_arithmetic_operators()">Add</a>
        </ul>
    </li>
    <li>
        <a href="#">Logical Operator</a>
        <ul>
            <a onclick="add_logical_operators()">Add</a>
            <a onclick="delete_logical_operator()">Delete</a>
        </ul>
    </li>
    <li><a onclick="delete_block()">Delete</a></li>    
    <li>
        <a href="#">Generate</a>
        <ul>
            <a onclick="generate_code()">Code</a>
        </ul>
    </li>
    <li><?php echo anchor('auth/logout', 'Logout');?></li>
    <li><a href="http://help.com" target="_blank" title="Help">Help</a></li>
</ul>
