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
                        <a href="<?php anchor_to(VIDUP_CONTROLLER . '/s3') ?>">
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
                            <div class="card-title">Update your Amazon S3 Details</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="POST" action="<?php anchor_to(VIDUP_CONTROLLER . '/s3') ?>">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input <?php echo_if($page_data['s3']['status'], 'checked') ?> id="s3-status" name="s3-status" type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label" for="s3-status">Use Amazon S3 Storage instead of Local Uploads.</label>
                                            </div>
                                            <small>All other credentials are <span class="text-danger">Required</span> if the status is turned on.</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="s3-access">S3 Access Key</label>
                                            <?php echo form_error('s3-access', '<br><span class="text-danger">', '</span>'); ?>
                                            <input id="s3-access" name="s3-access" placeholder="Enter your S3 Access Key" value="<?php echo esc($page_data['s3']['s3_access']) ?>" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="s3-access">S3 Secret Key</label>
                                            <?php echo form_error('s3-secret', '<br><span class="text-danger">', '</span>'); ?>
                                            <input id="s3-secret" name="s3-secret" placeholder="Enter your S3 Secret Key" value="<?php echo esc($page_data['s3']['s3_secret']) ?>" type="text" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="s3-bucket">S3 Bucket Name</label>
                                            <?php echo form_error('s3-bucket', '<br><span class="text-danger">', '</span>'); ?>
                                            <input id="s3-bucket" name="s3-bucket" placeholder="Enter your S3 Bucket Name" value="<?php echo esc($page_data['s3']['s3_bucket']) ?>" type="text" class="form-control">
                                            <small class="text-muted">The S3 Bucket where your uploads will be stored.</small>
                                        </div>
                                        <?php $this->config->load('s3'); $regions = $this->config->item('s3_regions'); ?>
                                        <div class="form-group">
                                            <label for="s3-region">S3 Region</label>
                                            <?php echo form_error('s3-region', '<br><span class="text-danger">', '</span>'); ?>
                                            <select class="form-control" id="s3-region" name="s3-region">
                                            <?php foreach($regions as $region) { ?>
                                                <option <?php echo_if($page_data['s3']['s3_region'] == $region, 'selected') ?> value="<?php echo esc($region) ?>"><?php echo esc($region) ?></option>
                                            <?php } ?>
                                            </select>
                                            <small class="text-muted">The region where your uploads will be stored.</small>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <input type="hidden" name="submit" value="Submit">
                                            <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i> Update S3 Details</button>
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