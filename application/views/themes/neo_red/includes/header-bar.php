<header>
    <div class="container">
        <div class="logo">
            <a href="<?php anchor_to(); ?>"><img class="img-responsivee" src="<?php uploads('img/' . $general['logo']) ?>" alt=""></a>
        </div>
        <a href="<?php anchor_to() ?>" class="btn headerUploadBtn"><img src="<?php $assets('images/uploadBtnIcon.svg') ?>" alt=""><span><?php echo lang('upload_images') ?></span></a>
        <?php if($general['enable_registration']) { ?>
        <div class="loginBtnsRight">
            <?php if(!$user) { ?>
                <a href="<?php anchor_to(USER_CONTROLLER . '/login') ?>" class="headerLoginBtn"><?php echo lang('user_login') ?></a>
                <a href="<?php anchor_to(USER_CONTROLLER . '/register') ?>" class="btn headerRegisterBtn"><img src="<?php $assets('images/registerUserBtnIcon.svg') ?>" alt=""><span><?php echo lang('user_register') ?></span></a>
            <?php } else { ?>
                <span class="dropdownItem ddMain dropdown">
                    <a class="dropdown-toggle headerUserIconSection" href="#" id="aboutDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="userNavIcon"><img class="img-responsivee rounded-circle" src="<?php uploads('user/' . $user['avatar']) ?>"
                                alt=""></span>
                        <span class="userName"><?php echo esc($user['username']) ?></span>
                    </a>
                    <div class="dropdown-menu headerMenudd headerDD" aria-labelledby="aboutDropdown"
                        x-placement="bottom-start">
                        <div class="headerMenuddMain">
                            <a href="<?php anchor_to(USER_CONTROLLER . '/profile') ?>"><i class="far fa-user"></i> <?php echo lang('profile') ?></a>
                            <a href="<?php anchor_to(USER_CONTROLLER . '/settings') ?>"><i class="fas fa-cogs"></i> <?php echo lang('settings') ?></a>
                            <a href="<?php anchor_to(USER_CONTROLLER . '/logout') ?>"><i class="fas fa-power-off"></i> <?php echo lang('logout') ?></a>
                        </div>
                    </div>
                </span>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</header>

<?php $theme_view('includes/top-ad') ?>