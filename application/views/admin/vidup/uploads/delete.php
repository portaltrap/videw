<?php $this->load->view('admin/includes/head'); ?>
<div class="wrapper fullheight-side">
<?php $this->load->view('admin/includes/header');
$this->load->view('admin/includes/sidebar'); 
$this->load->view('admin/includes/navbar'); ?>

<div class="main-panel">
   <div class="container">
      <div class="page-inner">
         <div class="page-header">
            <h4 class="page-title">Delete Upload</h4>
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
                  Uploads
                  </a>
               </li>
               <li class="separator">
                  <i class="flaticon-right-arrow"></i>
               </li>
               <li class="nav-home">
                  <a href="<?php anchor_to(VIDUP_CONTROLLER . '/delete_upload/' . $upload['slug_id']) ?>">
                  <?php echo esc($page_title, true) ?>
                  </a>
               </li>
            </ul>
         </div>
         <?php $this->load->view('admin/includes/alert'); ?>
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     <div class="card-title">Delete Upload <u><?php echo esc($upload['slug_id'], true) ?></u></div>
                  </div>
				<div class="card-body">
					<div class="row">
						<div class="col-12">
                            <p>Confirm The Removal of Upload: <strong><?php echo esc($upload['slug_id'], true); ?></strong></p>
                            <img class="img-fluid" width="400" src="<?php echo ($upload['s3']) ? $upload['imgname'] : base_url('i/' . $upload['imgname']) ?>">
                            <br>
                            <br>
                            <a href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads') ?>" class="btn btn-success"><i class="fas fa-arrow-left mr-1"></i> All Uploads</a> <a href="<?php anchor_to(VIDUP_CONTROLLER . '/delete_upload/' . $upload['id'] . '/true') ?>" class="btn btn-danger"><i class="fas fa-trash mr-1"></i> Delete Upload</a>
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