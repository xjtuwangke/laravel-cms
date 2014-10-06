<ul class="sidebar-menu">
    <?php foreach( $menu as $item):?>
        <?php if( ! $item->submenu) :?>
            <li class="<?=$item->active?'active':''?>">
                <a href="<?=$item->url?>">
                    <i class="fa fa-dashboard"></i><span><?=$item->title?></span>
                </a>
            </li>
        <?php else:?>
            <li class="treeview <?=$item->active?'active':''?>">
                <a href="#">
                    <i class="fa fa-bar-chart-o"></i>
                    <span><?=$item->title?></span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <?php foreach( $item->submenu as $sub ):?>
                        <li class="<?=$sub->active?'active':''?>"><a href="<?=$sub->url?>"><i class="fa fa-angle-double-right"></i><?=$sub->title?></a></li>
                    <?php endforeach;?>
                </ul>
            </li>
        <?php endif;?>
    <?php endforeach;?>
</ul>