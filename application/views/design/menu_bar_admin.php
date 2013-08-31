<ul id="nav">
    <li>
        <a href="#">Users</a>
        <ul>
            <?php
            echo anchor('admin/load_search', 'Search');
            echo anchor('admin', 'Show');
            ?>
        </ul>
    </li>
    <li><?php echo anchor('admin/logout', 'Logout'); ?></li>
</ul>
