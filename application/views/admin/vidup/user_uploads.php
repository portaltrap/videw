<?php $this->load->view('admin/includes/head'); ?>
<div class="wrapper fullheight-side">
<?php $this->load->view('admin/includes/header');
$this->load->view('admin/includes/sidebar'); 
$this->load->view('admin/includes/navbar'); ?>

<div class="main-panel">
   <div class="container">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title"><?php echo esc($page_title) ?> <a target="_blank" class="btn btn-sm btn-danger" href="<?php anchor_to(VIDUP_CONTROLLER . '/edit_user/' . $end_user['id']) ?>"><i class="fas fa-edit"></i></a></h4>
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
                  <a href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads') ?>">
                  <?php echo esc($page_title) ?>
                  </a>
               </li>
            </ul>
        </div>
        <?php $this->load->view('admin/includes/alert'); ?>
        
        <div class="d-flex justify-content-between align-items-center">
            <span class="float-right">Showing <?php echo count($user_uploads) ?> result(s)</span>
            <nav class="d-inline-block">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/1') ?>">&larr; First</a></li>
                    <?php if(($page - 1) > 0) { ?>
                        <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . ($page - 1)) ?>"><?php echo ($page - 1) ?></a></li>
                    <?php } ?>
                    <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . $page) ?>"><?php echo $page ?></a></li>
                    <?php if(($page + 1) <= $pages) { ?>
                        <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . ($page + 1)) ?>"><?php echo ($page + 1) ?></a></li>
                    <?php } ?>
                    <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . $pages) ?>">Last &rarr;</a></li>
                </ul>
            </nav>
        </div>
        
        <div class="row">
            <div class="col-12">
                    <?php if(count($user_uploads) > 0) { ?>
                    <div class="card-columns">
                    <?php foreach($user_uploads as $upload) { ?>
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                <div><?php echo esc($upload['slug_id']); if($upload['s3']) { ?><span class="badge badge-warning float-right">Amazon S3</span><?php } else { ?><span class="badge badge-info float-right">Local</span><?php } ?></div>
                                </div>
                                <div class="card-body">
                                    <img class="rounded card-img img-fluid" src="<?php echo ($upload['s3']) ? $upload['imgname'] : base_url('i/' . $upload['imgname']) ?>">
                                </div>
                                <div class="card-footer">
                                    <?php  if($upload['user']) { ?>
                                        <a target="_blank" href="<?php anchor_to(VIDUP_CONTROLLER . '/edit_user/' . $upload['user']['id']) ?>">
                                            <span class="badge badge-info">By <?php echo esc($upload['user']['username']) ?></span>
                                        </a>
                                    <?php } else { ?>
                                        <span class="badge badge-danger">Anonymous</span>
                                    <?php } ?>

                                    <a class="btn btn-danger btn-sm float-right ml-1" href="<?php anchor_to(VIDUP_CONTROLLER . '/delete_upload/' . $upload['id']) ?>" data-toggle="tooltip" data-placement="top" title="Delete Video"><i class="fas fa-trash"></i></a> 
                                    <a target="_blank" class="btn btn-success btn-sm float-right" href="<?php anchor_to('v/' . $upload['slug_id']) ?>" data-toggle="tooltip" data-placement="top" title="View Video"><i class="fas fa-external-link-alt"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    No Uploads Found.
                                </div>
                            </div>
                        </div>
                    <?php } ?>
            </div>
        </div>
        
        <hr>
        
        <div class="d-flex justify-content-between align-items-center">
            <span class="float-right">Showing <?php echo count($user_uploads) ?> result(s)</span>
            <nav class="d-inline-block">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/1') ?>">&larr; First</a></li>
                    <?php if(($page - 1) > 0) { ?>
                        <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . ($page - 1)) ?>"><?php echo ($page - 1) ?></a></li>
                    <?php } ?>
                    <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . $page) ?>"><?php echo $page ?></a></li>
                    <?php if(($page + 1) <= $pages) { ?>
                        <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . ($page + 1)) ?>"><?php echo ($page + 1) ?></a></li>
                    <?php } ?>
                    <li class="page-item"><a class="page-link" href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads/' . $pages) ?>">Last &rarr;</a></li>
                </ul>
            </nav>
        </div>

   </div>
</div>
<!-- End Page Content -->
</div>
<?php $this->load->view('admin/includes/foot'); ?>