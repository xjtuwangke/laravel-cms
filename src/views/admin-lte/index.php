<div class="row">
    <div class="col-xs-3 text-center" style="border-right: 1px solid #f4f4f4">
        <div style="display: inline; width: 60px; height: 60px;"><input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60" data-fgcolor="#39CCCC" readonly="readonly" style="width: 34px; height: 20px; position: absolute; vertical-align: middle; margin-top: 20px; margin-left: -47px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 12px; line-height: normal; font-family: Arial; text-align: center; color: rgb(57, 204, 204); padding: 0px; -webkit-appearance: none; background: none;"></div>
        <div class="knob-label">本周新用户</div>
    </div><!-- ./col -->
    <div class="col-xs-3 text-center" style="border-right: 1px solid #f4f4f4">
        <div style="display: inline; width: 60px; height: 60px;"><input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60" data-fgcolor="#39CCCC" readonly="readonly" style="width: 34px; height: 20px; position: absolute; vertical-align: middle; margin-top: 20px; margin-left: -47px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 12px; line-height: normal; font-family: Arial; text-align: center; color: rgb(57, 204, 204); padding: 0px; -webkit-appearance: none; background: none;"></div>
        <div class="knob-label">本周订单</div>
    </div><!-- ./col -->
    <div class="col-xs-3 text-center">
        <div style="display: inline; width: 60px; height: 60px;"><input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgcolor="#39CCCC" readonly="readonly" style="width: 34px; height: 20px; position: absolute; vertical-align: middle; margin-top: 20px; margin-left: -47px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 12px; line-height: normal; font-family: Arial; text-align: center; color: rgb(57, 204, 204); padding: 0px; -webkit-appearance: none; background: none;"></div>
        <div class="knob-label">本周新商品</div>
    </div><!-- ./col -->
    <div class="col-xs-3 text-center">
        <div style="display: inline; width: 60px; height: 60px;"><input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60" data-fgcolor="#39CCCC" readonly="readonly" style="width: 34px; height: 20px; position: absolute; vertical-align: middle; margin-top: 20px; margin-left: -47px; border: 0px; font-weight: bold; font-style: normal; font-variant: normal; font-stretch: normal; font-size: 12px; line-height: normal; font-family: Arial; text-align: center; color: rgb(57, 204, 204); padding: 0px; -webkit-appearance: none; background: none;"></div>
        <div class="knob-label">本周文章</div>
    </div><!-- ./col -->
</div>
<script>
    $(".knob").knob();
</script>
<div class="row">
    <?php $role = AuthModel::getUser()->role; ?>
    <?php if( $role->checkPermission( 'admin.customer.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    <?=UserModel::count()?>
                </h3>
                <p>
                    注册用户
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person-stalker"></i>
            </div>
            <a href="<?=URL::action('admin.customer.show.index')?>" class="small-box-footer">
                查看 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
    <?php if( $role->checkPermission( 'admin.goods.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?=GoodsModel::count()?><sup style="font-size: 20px">件</sup>
                </h3>
                <p>
                    商品
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="<?=URL::action('admin.goods.show.index')?>" class="small-box-footer">
                查看<i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
    <?php if( $role->checkPermission( 'admin.issue.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?=UserIssueModel::count()?>
                </h3>
                <p>
                    客服事务
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-telephone-outline"></i>
            </div>
            <a href="<?=URL::action('admin.issue.show.index')?>" class="small-box-footer">
                查看 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
    <?php if( $role->checkPermission( 'admin.farm.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    <?=FarmModel::count()?><sup style="font-size: 20px">家</sup>
                </h3>
                <p>
                    农场信息
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-card"></i>
            </div>
            <a href="<?=URL::action('admin.farm.show.index')?>" class="small-box-footer">
                查看 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
    <?php if( $role->checkPermission( 'admin.order.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-fuchsia">
            <div class="inner">
                <h3>
                    <?=OrderModel::count()?><sup style="font-size: 20px">份</sup>
                </h3>
                <p>
                    订单
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-ios7-cart"></i>
            </div>
            <a href="<?=URL::action('admin.order.show.index')?>" class="small-box-footer">
                查看 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
    <?php if( $role->checkPermission( 'admin.article.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-teal">
            <div class="inner">
                <h3>
                    <?=PostModel::count()?><sup style="font-size: 20px">篇</sup>
                </h3>
                <p>
                    文章
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-paperclip"></i><!--ion-paperclip-->
            </div>
            <a href="<?=URL::action('admin.article.show.index')?>" class="small-box-footer">
                查看 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
    <?php if( $role->checkPermission( 'admin.cookbook.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-olive">
            <div class="inner">
                <h3>
                    <?=CookbookModel::count()?><sup style="font-size: 20px">篇</sup>
                </h3>
                <p>
                    菜谱
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-pizza"></i>
            </div>
            <a href="<?=URL::action('admin.cookbook.show.index')?>" class="small-box-footer">
                查看 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
    <?php if( $role->checkPermission( 'admin.admin.show.*' ) ):?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>
                    <?=AdminUserModel::where('id' , '!=' , '1' )->count()?><sup style="font-size: 20px">位</sup>
                </h3>
                <p>
                    管理员
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-key"></i>
            </div>
            <a href="<?=URL::action('admin.admin.show.index')?>" class="small-box-footer">
                查看 <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <?php endif;?>
</div>