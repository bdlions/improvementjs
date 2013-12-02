<ul id="nav">
   <li>
       <a href="<?php echo base_url(); ?>auth">Projects</a>
        <ul>
            <?php
                echo anchor('auth', 'My Projects');
                echo anchor('programs/all_programs', 'My Programs');
                echo anchor('scripts/all_scripts', 'My Scripts');
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
