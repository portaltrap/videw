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
                        <a href="<?php anchor_to(VIDUP_CONTROLLER . '/upload_settings') ?>">
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
                            <div class="card-title">Control Video Upload Settings for your website</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="POST" action="<?php anchor_to(VIDUP_CONTROLLER . '/upload_settings') ?>">
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input <?php echo_if($page_data['upload']['can_user_delete'], 'checked') ?> id="can-user-delete" name="can-user-delete" type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label" for="can-user-delete">Let users delete their own uploads.</label>
                                            </div>
                                            <small>Deleting will removing the file from your Server or S3 as well.</small>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label for="filesize">Max. Filesize in Kilobytes <span class="text-danger">*</span></label>
                                            <?php echo form_error('filesize', '<br><span class="text-danger">', '</span>'); ?>
                                            <div class="input-group">
                                                <input id="filesize" name="filesize" placeholder="Maximum allowed Filesize (KBs)" value="<?php echo esc($page_data['upload']['max_size_kb']); ?>" type="number" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">KB</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="chunksize">Max. Chunk Size in Kilobytes <span class="text-danger">*</span></label>
                                            <?php echo form_error('chunksize', '<br><span class="text-danger">', '</span>'); ?>
                                            <div class="input-group">
                                                <input id="chunksize" name="chunksize" placeholder="Maximum allowed chunksize (KBs)" value="<?php echo esc($page_data['upload']['chunk_size']); ?>" type="number" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">KB</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="mimetype">Select Video Types <span class="text-danger">*</span></label>
                                            <?php echo form_error('mime_types', '<br><span class="text-danger">', '</span>'); ?>
											<input type="text" id="tagsinput" class="form-control" value="<?php echo esc(rtrim($page_data['upload']['mime_types'], ',')); ?>" data-role="tagsinput" name="mime_types">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input <?php echo_if($page_data['upload']['enable_api'], 'checked') ?> id="enable-api" name="enable-api" type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label" for="enable-api">Enable Remote Uploading API.</label>
                                            </div>
                                            <small><code>POST</code> 'upload' : FILE to <code><?php anchor_to('api/upload') ?></code></small>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input <?php echo_if($page_data['upload']['enable_popup'], 'checked') ?> id="enable-popup" name="enable-popup" type="checkbox" class="custom-control-input">
                                                <label class="custom-control-label" for="enable-popup">Enable Popup before showing video.</label>
                                            </div>
                                        </div>
                                        <div class="form-group pb-4">
                                            <label for="width">Seconds to Wait on Video Popup <span class="text-danger">*</span></label>
                                            <?php echo form_error('seconds-to-wait', '<br><span class="text-danger">', '</span>'); ?>
                                            <div class="input-group">
                                                <input id="seconds-to-wait" name="seconds-to-wait" placeholder="Seconds to Wait" value="<?php echo esc($page_data['upload']['seconds_to_wait']); ?>" type="number" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Seconds</span>
                                                </div>
                                            </div>
                                            <span class="badge badge-info float-right mt-2 mb-2">Set 0 for No Wait</span>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <input type="hidden" name="submit" value="Submit">
                                            <button type="submit" class="btn btn-success"><i class="fas fa-check mr-1"></i> Update Upload Settings</button>
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