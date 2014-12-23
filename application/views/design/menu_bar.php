<div class="nav navbar-inverse row" style="background-color: #5cb85c;">
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Projects<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="load_project_list()">My Projects</a></li>
            <li><a onclick="save()">Save</a></li>
            <li><a onclick="save_as()">Save As</a></li>
            <li><a onclick="download_project()">Download Project</a></li>
            <li><a onclick="upload_project()">Upload Project</a></li>        
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Block<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="add_block()">Add</a></li>      
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Action<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="add_action()">Add</a></li>      
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Variable<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="add_variables()">Add</a></li>
            <li><a onclick="show_variables()">Show Variables</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Bracket<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="add_brackets()">Add</a></li>
            <li><a onclick="delete_bracket()">Delete</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Arithmetic Operator<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="add_arithmetic_operators()">Add</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Logical Operator<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="add_logical_operators()">Add</a></li>
            <li><a onclick="delete_logical_operator()">Delete</a></li>
        </ul>
    </div>
    <div class="btn-group">
        <button onclick="delete_block()" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Delete<span class="caret"></span>
        </button>    
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Generate<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a onclick="generate_code()">Code</a></li>
        </ul>
    </div>
    <div class="btn-group">    
        <button onclick="alert('hi')" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Logout
        </button>      
    </div>
    <div class="btn-group">    
        <button onclick="alert('hi')" type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Help
        </button>      
    </div>
</div>