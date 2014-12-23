<div class="nav navbar-inverse row" style="background-color: #5cb85c;">
    <div class="btn-group">
        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" style="border: 0px">
            Users<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><?php echo anchor('admin/load_search', 'Search'); ?></li>
            <li><?php echo anchor('admin', 'Show'); ?></li>
        </ul>
    </div>
    <div class="btn-group">    
        <a href="<?php echo base_url()?>admin/logout">
            <button type="button" class="btn btn-success" data-hover="dropdown" style="border: 0px">
                Logout
            </button> 
        </a>             
    </div>
</div>