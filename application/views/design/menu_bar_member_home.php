<div class="nav navbar-inverse row" style="background-color: #5cb85c;">
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Projects<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><?php echo anchor('projects/show_all_projects', 'My Projects'); ?></li>
            <li><?php echo anchor('projects/show_all_programs', 'My Programs'); ?></li> 
            <li><?php echo anchor('projects/show_all_scripts', 'My Scripts'); ?> </li>
            <li><?php echo anchor('projects/create_program', 'Create Program'); ?> </li>
            <li><?php echo anchor('projects/create_script', 'Create Script'); ?> </li>
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Profile<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><?php echo anchor('user/show_user', 'Show'); ?></li>
            <li><?php echo anchor('user/edit_user', 'Edit'); ?></li> 
            <li><?php echo anchor('auth/logout', 'Logout'); ?></li> 
        </ul>
    </div>
</div>