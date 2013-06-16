<?php $user = Session::getvar("user");?>

<style type="text/css">
    #chart-display-block {
        min-height: 295px;
        background: #fff;
        color: #323232;
    }

    #chart-display-block #block-body {
        text-align: center;
        text-transform: uppercase;
    }

    .container-fluid {
        background: #E9E9E9 url(templates/images/tiny_grid.png) repeat 0 0
    }

    .breadcrumb {
        margin-bottom: 0px;
    }

    .block-body {
        margin: 10px 0px 0px 0px;
    }

    .block-body p {
        margin: 3px;
        text-align: center;
    }
</style>

<div class="row-fluid dashboard-blocks">
    <div id="template-content">
        <div class="span12">

            <?php $announcements = Template::getvar("announcements"); ?>
                <?php if($announcements && is_array($announcements)):?>
                    <?php foreach($announcements as $some_announcement):?>
                        <?php if(strlen(trim($some_announcement->message)) > 0):?>
                            <div class="alert alert-<?php echo $some_announcement->type; ?>">
                                <?php echo $some_announcement->message; ?>
                            </div>
                        <?php endif;?>
                    <?php endforeach;?>
                <?php endif;?>


            <div class="row-fluid">

                <div class="block span6">
                    <p class="block-heading">
                        <i class="icon-home"></i>
                        Welcome</p>
                    <div class="block-body">
                        <p><strong>Welcome to Framework26 Dashboard</strong></p>
                        <br /><br /><br /><br />
                    </div>
                </div>

                <div class="block span6">
                    <p class="block-heading">
                        <i class="icon-globe"></i>
                        Quick Links
                    </p>
                    <div class="block-body">
                        <div class="quick-links">

                            <?php

                            $quick_links = Helper_ACL::get_quick_links();

                            foreach($quick_links as $some_quick_link){

                                $quick_link_desc = Helper_ACL::get_link_desc($some_quick_link);

                            ?>
                                <a class="link" href="<?php echo Config::url($quick_link_desc["url"]);?>">
                                    <i class="link-icon icon-<?php echo $quick_link_desc["icon"];?>"></i>
                                    <span class="link-label"><?php echo ucwords($quick_link_desc["display"]);?></span>
                                </a>                                

                            <?php } ?>

                        </div>
                    </div>
                </div>

            </div>


            <div class="row-fluid">
                <div class="block span6">
                    <p class="block-heading">
                        <i class="icon-th"></i>
                        Account information
                    </p>
                    <div class="block-body">
                        <table class="table table-striped table-">
                            <tbody>
                                <tr>
                                    <th>Username</th>
                                    <td><?php echo $user->username;?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $user->email;?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo $user->phone;?></td>
                                </tr>
                                <tr>
                                    <th>Role</th>
                                    <td><?php echo $user->user_role;?></td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td><?php echo $user->created;?></td>
                                </tr>

                            </tbody>
                        </table>
                    </div><!--/ .block-body -->
                </div><!--/ .block .span6 -->

                <div class="span6">
                    <div class="block" id="chart-display-block">
                        <p class="block-heading">
                            <i class="icon-bookmark"></i>
                            Stats
                        </p>
                        <?php echo Template::getvar("chart_block_content");?>
                    </div><!--/ .block -->
                </div><!--/ .span6 -->
            </div><!--/ .row-fluid -->                                                              
        </div><!--/span-->
    </div><!--/ #template-content -->
</div><!--/ .row-fluid --> 