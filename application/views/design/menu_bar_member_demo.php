<div class="nav navbar-inverse row" style="background-color: #5cb85c;">
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Projects<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><?php echo anchor('auth', 'My Projects'); ?></li>
            <li><?php echo anchor('programs/all_programs', 'My Programs'); ?></li> 
            <li><?php echo anchor('scripts/all_scripts', 'My Scripts'); ?> </li>
            <li><?php echo anchor('programs/create_program', 'Create Program'); ?> </li>
            <li><?php echo anchor('scripts/create_script', 'Create Script'); ?> </li>
        </ul>
    </div>
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Profile<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><?php echo anchor('auth/show_user', 'Show'); ?></li>
            <li><?php echo anchor('auth/edit_user', 'Edit'); ?></li> 
            <li><?php echo anchor('auth/logout', 'Logout'); ?></li> 
        </ul>
    </div>
</div>

