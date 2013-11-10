<ul id="nav">
   <li>
        <a href="#">Projects</a>
        <ul>
            <?php
                echo anchor('programs/all_programs', 'All Programs');
                echo anchor('scripts/all_scripts', 'All Scripts');
                echo anchor('programs/create_program', 'Create Program');
                echo anchor('scripts/create_script', 'Create Script');
                
            ?>
        </ul>
    </li>
    <li>
        <a href="#">Profile</a>
        <ul>
            <?php
                echo anchor('auth/show_user', 'Show');
                echo anchor('auth/edit_user', 'Edit');
                
            ?>
        </ul>
    </li>
    <li><?php echo anchor('auth/logout', 'Logout'); ?></li>
</ul>
