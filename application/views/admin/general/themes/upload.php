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
                        <a href="<?php anchor_to(GENERAL_CONTROLLER . '/themes') ?>">
                        Theme Settings
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-home">
                        <a href="<?php anchor_to(GENERAL_CONTROLLER . '/upload_theme') ?>">
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
                            <div class="card-title">Upload a Brand New Theme</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form enctype="multipart/form-data" method="POST" accept="application/zip" action="<?php anchor_to(GENERAL_CONTROLLER . '/upload_theme') ?>">
                                        <div class="form-group">
                                            <input type="file" allowed="zip" name="theme" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <a class="btn btn-danger" href="<?php anchor_to(GENERAL_CONTROLLER . '/themes') ?>"><i class="fas fa-arrow-left mr-1"></i> Go Back</a>
                                            <input type="hidden"  name="submit" value="Submit">
                                            <button class="btn btn-success float-right" type="submit"><i class="fas fa-upload mr-1"></i> Upload</button>
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