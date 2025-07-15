<?php $theme_view('includes/head'); ?>
<div class="mainBg">
    <?php $theme_view('includes/header-bar') ?>
    <div class="container">
        <div class="profileSetting clearfix">
            <h1 class="profileTitle"><?php echo lang('settings'); ?></h1>
            <div class="profileTabList row">
                <div class="nav profileTabsColumn col-md-3 col-lg-2" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link <?php echo (!isset($tab) ? 'active' : ($tab == 'account' ? 'active' : '')) ?>" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                        aria-controls="nav-home" aria-selected="true"><?php echo lang('account_tab') ?></a>
                    <a class="nav-item nav-link <?php echo (!isset($tab) ? '' : ($tab == 'password' ? 'active' : '')) ?>" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab"
                        aria-controls="nav-contact" aria-selected="false"><?php echo lang('password_tab') ?></a>
                    <a class="nav-item nav-link <?php echo (!isset($tab) ? '' : ($tab == 'connections' ? 'active' : '')) ?>" id="nav-connections-tab" data-toggle="tab" href="#nav-connections" role="tab"
                        aria-controls="nav-contact" aria-selected="false"><?php echo lang('connections_tab') ?></a>
                </div>

                <div class="tab-content col-md-9 col-lg-10" id="nav-tabContent">
                    <div class="tab-pane fade <?php echo (!isset($tab) ? 'show active' : ($tab == 'account' ? 'show active' : '')) ?>" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-6">
                                    <?php $theme_view('includes/alert') ?>

                                    <div class="form-group">
                                        <label for="username"><b><?php echo lang('username') ?></b></label>
                                        <?php echo form_error('username', '<span class="float-right text-danger">', '</span>') ?>
                                        <input value="<?php echo esc($user['username']) ?>" type="text" class="form-control formControlInput"
                                            aria-describedby="emailHelp" name="username" id="username" placeholder="Your Username">
                                    </div>
                                    <div class="form-group m-b-0">
                                        <label for="exampleInputEmail1"><b><?php echo lang('email') ?></b></label>
                                        <input disabled type="email" value="<?php echo esc($user['email']) ?>" class="form-control formControlInput" id="exampleInputEmail1"
                                            aria-describedby="emailHelp" placeholder="Enter email">
                                        <small id="emailHelp"
                                            class="form-text text-muted"><?php echo lang('email_change_disallowed') ?></small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <img width="150" height="150" class="userImage border rounded-circle" src="<?php uploads('user/' . $user['avatar']) ?>">
                                    <input type="file" name="avatar" class="form-control mt-2">
                                </div>
                            </div>
                            <hr class="m-t-20 m-b-20">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-b-0">
                                        <div class="custom-control custom-checkbox">
                                            <input <?php echo_if($user['privacy'], 'checked') ?> type="checkbox" name="privacy-check" id="privacy-check" class="custom-control-input" />
                                            <label class="custom-control-label" for="privacy-check"><?php echo lang('privacy_check') ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="m-t-20 m-b-20">
                            <input class="btn curtomButton" name="submit-acc" type="submit" value="<?php echo lang('update_account') ?>">
                        </form>
                    </div>
                    <div class="tab-pane fade <?php echo (!isset($tab) ? '' : ($tab == 'password' ? 'show active' : '')) ?>" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php $theme_view('includes/alert'); if(!$user['password_set']) { ?>
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="password"><b><?php echo lang('choose_password') ?></b></label>
                                        <?php echo form_error('password', '<span class="float-right text-danger">', '</span>') ?>
                                        <input type="password" class="form-control formControlInput"
                                            aria-describedby="emailHelp" id="password" name="password" placeholder="Set New Password">
                                    </div>
                                    <hr class="m-t-5 m-b-20">
                                    <input type="submit" value="<?php echo lang('set_new_password') ?>" name="submit-pass-new" class="btn curtomButton">
                                </form>
                                <?php } else { ?>
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="password"><b><?php echo lang('old_password') ?></b></label>
                                        <?php echo form_error('password', '<span class="float-right text-danger">', '</span>') ?>
                                        <input type="password" class="form-control formControlInput"
                                            aria-describedby="emailHelp" id="password" name="password" placeholder="Your current Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="newpassword"><b><?php echo lang('new_password') ?></b></label>
                                        <?php echo form_error('newpassword', '<span class="float-right text-danger">', '</span>') ?>
                                        <input type="password" class="form-control formControlInput" placeholder="Your New Password"
                                                aria-describedby="emailHelp" id="newpassword" name="newpassword">
                                    </div>
                                    <hr class="m-t-5 m-b-20">
                                    <input type="submit" value="<?php echo lang('update_password') ?>" name="submit-pass" class="btn curtomButton">
                                </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade <?php echo (!isset($tab) ? '' : ($tab == 'connections' ? 'show active' : '')) ?>" id="nav-connections" role="tabpanel" aria-labelledby="nav-connections-tab">
                        <h6><?php echo lang('external_connections') ?></h6>
                        <div class="row">
                            <div class="col-lg-6">
                                <?php if($user['facebook']) { ?>
                                <div class="accountLinked">
                                    <h3 class="bg-primary text-white px-2 py-1 rounded"><i class="fab fa-facebook-f mr-1"></i> Facebook</h3>
                                    <p class="mt-1 mb-0 mx-0"><?php echo lang('connected_to_facebook') ?></p>
                                </div>
                                <?php } if($user['google']) { ?>
                                <div class="accountLinked">
                                    <h3 class="bg-danger text-white px-2 py-1 rounded"><i class="fab fa-google-plus-g mr-1"></i> Google</h3>
                                    <p class="mt-1 mb-0 mx-0"><?php echo lang('connected_to_google') ?></p>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $theme_view('includes/footer-bar') ?>
</div>
<?php $theme_view('includes/foot'); ?>