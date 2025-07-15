<?php $theme_view('includes/head'); ?>
<div class="mainBg profileMainBg">
    <?php $theme_view('includes/header-bar') ?>
    <div class="container pt-5">
        <div class="profileTopUser">
            <div class="topUserCredentials">
                <a href="#">
                    <img class="userImage" src="<?php uploads('user/' . $user['avatar']) ?>" alt="">
                </a>
                <h1><?php echo esc($user['username']) ?></h1>
                <div class="userMeta">
                    <a class="editLink" href="<?php anchor_to(USER_CONTROLLER . '/settings'); ?>">
                        <span class="fas fa-pencil-alt"></span><span>Edit profile</span>
                    </a>
                </div>
                <div class="userMeta m-b-5">
                    <span class="numberFigures">
                        <?php if($count == 0) { ?>
                            <b><span>No Uploads.</span></b>
                        <?php } else { ?>
                            <b data-text="following-count"><?php echo $count ?></b> <span>Uploads</span>
                        <?php } ?>
                    </span>
                </div>
            </div>
        </div>
        <hr>
        <?php $theme_view('includes/alert') ?>

        <div class="card-columns profileCardColumns cardImagesArea">
            <?php foreach($user_uploads as $upload) { ?>
                <div class="card gillcard" data-liked="0">
                    <img class="card-img" src="<?php echo $upload['s3'] ? $upload['imgname'] : base_url('i/' . $upload['imgname']) ?>" alt="Card Video cap">
                    <div class="listItemDesc">
                        <div class="listItemDescTitle">
                            <h2 class="listItemDescTitleLink"><?php echo $upload['slug_id'] ?></h2>
                        </div>
                    </div>
                    <?php if($upload_cfg['can_user_delete']) { ?>
                    <ul class="listItemImageTools">
                        <li class="toolDelete">
                            <a href="<?php anchor_to(USER_CONTROLLER . '/delete/' . $upload['slug_id']) ?>">
                                <span class="btn-icon fas fa-trash-alt" title="Delete"></span>
                                <span class="label label-delete">Delete</span>
                            </a>
                        </li>
                    </ul>
                    <?php } ?>
                    <a target="_blank" href="<?php anchor_to('v/' . $upload['slug_id']) ?>" class="cardListLink"></a>
                </div>
            <?php } ?>
        </div>

        <?php if($count != 0) { ?>
            <div class="loadmoreButtonArea">
                <ul class="pagination d-inline-flex">
                    <?php if($page > 1) { ?><li class="loadpage-item mx-1"><a class="page-link" href="<?php anchor_to(USER_CONTROLLER . '/profile/1') ?>">&larr; First</a></li><?php } ?>
                    <?php if(($page - 1) > 0) { ?>
                        <li class="page-item mx-1"><a class="page-link" href="<?php anchor_to(USER_CONTROLLER . '/profile/' . ($page - 1)) ?>"><?php echo ($page - 1) ?></a></li>
                    <?php } ?>
                    <li class="page-item mx-1"><a class="page-link" href="<?php anchor_to(USER_CONTROLLER . '/profile/' . $page) ?>"><?php echo $page ?></a></li>
                    <?php if(($page + 1) <= $pagination_pages) { ?>
                        <li class="page-item mx-1"><a class="page-link" href="<?php anchor_to(USER_CONTROLLER . '/profile/' . ($page + 1)) ?>"><?php echo ($page + 1) ?></a></li>
                    <?php } ?>
                    <?php if($page < $pagination_pages) { ?><li class="page-item mx-1"><a class="page-link" href="<?php anchor_to(USER_CONTROLLER . '/profile/' . $pagination_pages) ?>">Last &rarr;</a></li><?php } ?>
                </ul>
            </div>
        <?php } ?>
    </div>
    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>