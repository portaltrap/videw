<?php $this->load->view('admin/includes/head'); ?>
<div class="wrapper fullheight-side">
<?php $this->load->view('admin/includes/header');
$this->load->view('admin/includes/sidebar'); 
$this->load->view('admin/includes/navbar'); ?>

<!-- Page Content -->

<div class="main-panel">
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title"><?php echo esc($page_title) ?></h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="<?php anchor_to(GENERAL_CONTROLLER . '/dashboard') ?>">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-home">
                        <a href="<?php anchor_to(VIDUP_CONTROLLER . '/edit_user/' . $end_user['id']) ?>">
                        <?php echo esc($page_title) ?>
                        </a>
                    </li>
                </ul>
            </div>
            <?php $this->load->view('admin/includes/alert'); ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Viewing Profile: <u><?php echo esc($end_user['username']) ?></u></div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="POST" action="<?php anchor_to(VIDUP_CONTROLLER . '/edit_user/' . $end_user['id']) ?>">
                                        <div class="form-group">
                                            <label for="id">User ID</label>
                                            <input id="id" type="number" disabled class="form-control" value="<?php echo $end_user['id'] ?>" placeholder="User ID">
                                        </div>
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input name="username" id="username" type="text" class="form-control" value="<?php echo esc($end_user['username']) ?>" placeholder="Username">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">E-Mail <?php echo_if($end_user['activated'], '<span class="badge badge-success">Verified</span>', '<span class="badge badge-danger">Unverified</span>') ?></label>
                                            <div class="input-group mb-3">
                                                <input name="email" id="email" type="email" class="form-control" value="<?php echo esc($end_user['email']) ?>" placeholder="E-Mail">
                                                <div class="input-group-append">
                                                    <?php if($end_user['activated']) { ?>
                                                        <a href="<?php anchor_to(VIDUP_CONTROLLER . '/toggle_verification/' . $end_user['id']) ?>" class="btn btn-danger text-white" type="button">Unverify This User</a>
                                                    <?php } else { ?>
                                                        <a href="<?php anchor_to(VIDUP_CONTROLLER . '/toggle_verification/' . $end_user['id']) ?>" class="btn btn-success text-white" type="button">Verify This User</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Registration Date</label>
                                            <input id="email" type="text" disabled class="form-control" value="<?php echo date('d-M-Y', strtotime($end_user['register_date'])) ?>" placeholder="E-Mail">
                                        </div>
                                        <div class="form-group">
                                            <label>Social Login Connections:</label>
                                            <br>
                                            <?php if($end_user['facebook'] || $end_user['google']) { ?>
                                                <?php if($end_user['facebook']) { ?>
                                                    <span class="py-1 px-2 bg-info text-white font-weight-bold rounded">Facebook</span>
                                                <?php } 
                                                
                                                if($end_user['google']) { ?>
                                                    <span class="py-1 px-2 bg-danger text-white font-weight-bold rounded">Google</span>
                                                <?php } ?>
                                                </ul>
                                            <?php } else { ?>
                                                <span class="badge badge-danger">User has not signed up using Social Login</span>
                                            <?php } ?>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <a href="<?php anchor_to(VIDUP_CONTROLLER . '/users') ?>" class="btn btn-success"><i class="fas fa-arrow-left mr-1"></i> Back to Users</a>
                                            <input type="hidden" name="submit" value="Submit">
                                            <button type="submit" class="btn btn-success float-right ml-2"><i class="fas fa-check mr-1"></i> Update User</button>
                                            <a href="<?php anchor_to(VIDUP_CONTROLLER . '/delete_user/' . $end_user['id']) ?>" class="btn btn-danger float-right"><i class="fas fa-trash mr-1"></i> Delete This User</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End Page Content -->

</div>
<?php $this->load->view('admin/includes/foot'); ?>