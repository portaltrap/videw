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
                </ul>
            </div>
            <?php $this->load->view('admin/includes/alert'); ?>
            <div class="row">
                <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-user-plus text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Weekly Users</p>
                                                        <h4 class="card-title font-weight-bold text-primary">+<?php echo number_format($page_data['weekly_users']) ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-image text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Weekly Uploads</p>
                                                        <h4 class="card-title font-weight-bold text-primary">+<?php echo number_format($page_data['weekly_uploads']) ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="far fa-image text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Total Uploads</p>
                                                        <h4 class="card-title font-weight-bold text-primary"><?php echo number_format($page_data['total_uploads']) ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card card-stats card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="far fa-user text-primary"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Total Users</p>
                                                        <h4 class="card-title font-weight-bold text-primary"><?php echo number_format($page_data['total_users']) ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h3>10 Recent Uploads</h3>
                                    <div id="recent-uploads-table">
                                        <div class="mt-3 p-3 bg-primary text-light font-weight-bold rounded">
                                            <div class="cst-table-head">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <span>ID</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <span>Open <i class="ml-1 fas fa-external-link-alt"></i></span>
                                                    </div>
                                                    <div class="col-1">
                                                        <span>Anonymous</span>
                                                    </div>
                                                    <div class="col-1">
                                                        <span>Location</span>
                                                    </div>
                                                    <div class="col-1">
                                                        <span>Action</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(count($page_data['recent_uploads']) > 0) { foreach($page_data['recent_uploads'] as $i => $upload) { ?>
                                            <div class="mt-2 p-3 bg-light text-dark border rounded bg-white">
                                                <div class="cst-table-row">
                                                    <div class="row">
                                                        <div class="col-5">
                                                            <span><?php echo esc($upload['slug_id']) ?></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <span>
                                                                <a target="_blank" href="<?php anchor_to('i/' . $upload['slug_id']) ?>"><?php anchor_to('i/' . $upload['slug_id']) ?></a>
                                                            </span>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>
                                                            <?php if(!$upload['user']) { ?>
                                                                <span class="badge badge-danger">Anonymous</span>
                                                            <?php } else { ?>
                                                                <a target="_blank" href="<?php anchor_to(VIDUP_CONTROLLER . '/user_uploads/' . $upload['user']['id']) ?>"><span class="badge badge-success">By <?php echo esc($upload['user']['username']) ?></span></a>
                                                            <?php } ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>
                                                                <?php if($upload['s3']) { ?>
                                                                    <span class="badge badge-warning">Amazon S3</span>
                                                                <?php } else { ?>
                                                                    <span class="badge badge-info">Local</span>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>
                                                                <a class="badge badge-success" target="_blank" href="<?php anchor_to('i/' . $upload['slug_id']) ?>"><i class="fas fa-external-link-alt mr-1"></i> Manage</a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } } else { ?>
                                            <div class="mt-2 p-3 bg-light text-dark border rounded bg-white">
                                                <div class="cst-table-row">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <span>No Users Found..</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h3>10 Recent Registrations</h3>
                                    <div id="recent-uploads-table">
                                        <div class="mt-3 p-3 bg-primary text-light font-weight-bold rounded">
                                            <div class="cst-table-head">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <span>Username</span>
                                                    </div>
                                                    <div class="col-3">
                                                        <span>E-Mail</span>
                                                    </div>
                                                    <div class="col-2">
                                                        <span>Register Date</span>
                                                    </div>
                                                    <div class="col-2">
                                                        <span>Verified</span>
                                                    </div>
                                                    <div class="col-1">
                                                        <span>OAuth</span>
                                                    </div>
                                                    <div class="col-1">
                                                        <span>Edit</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if(count($page_data['recent_users']) > 0) { foreach($page_data['recent_users'] as $i => $end_user) { ?>
                                            <div class="mt-2 p-3 bg-light text-dark border rounded bg-white">
                                                <div class="cst-table-row">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <span><?php echo esc($end_user['username']) ?></span>
                                                        </div>
                                                        <div class="col-3">
                                                            <span><?php echo esc($end_user['email']) ?></span>
                                                        </div>
                                                        <div class="col-2">
                                                            <span><?php echo date('d-M-Y', strtotime($end_user['register_date'])) ?></span>
                                                        </div>
                                                        <div class="col-2">
                                                            <span>
                                                                <?php if($end_user['activated']) { ?>
                                                                    <span class="badge badge-success">Verified</span>
                                                                <?php } else { ?>
                                                                    <span class="badge badge-danger">Not Verified</span>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>
                                                                <?php if($end_user['facebook'] || $end_user['google']) { ?>
                                                                    <span class="badge badge-success">True</span>
                                                                <?php } else { ?>
                                                                    <span class="badge badge-danger">False</span>
                                                                <?php } ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-1">
                                                            <span><a href="<?php anchor_to(VIDUP_CONTROLLER . '/edit_user/' . $end_user['id']) ?>" class="badge badge-success"><i class="fas fa-edit mr-1"></i> Edit</a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } } else { ?>
                                            <div class="mt-2 p-3 bg-light text-dark border rounded bg-white">
                                                <div class="cst-table-row">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <span>No Users Found..</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
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