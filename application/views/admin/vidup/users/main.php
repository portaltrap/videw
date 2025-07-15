<?php $this->load->view('admin/includes/head'); ?>
<div class="wrapper fullheight-side">
<?php $this->load->view('admin/includes/header');
$this->load->view('admin/includes/sidebar'); 
$this->load->view('admin/includes/navbar'); ?>

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
                  <a href="<?php anchor_to(VIDUP_CONTROLLER . '/users') ?>">
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
                        <div class="card-title">Manage your Registered Users 
                            <form method="POST" action="<?php anchor_to(VIDUP_CONTROLLER . '/search_users') ?>" class="float-right">
                                <div class="input-group">
                                    <input class="form-control form-control-sm" type="text" placeholder="Search.." id="search" name="search">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
				<div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-right">Showing <?php echo count($end_users) ?> result(s)</span>
                        <?php $this->load->view('admin/layout/pagination') ?>
                    </div>
                    <div class="row">
                        <div class="col-12">
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
                            <div id="sortable-list" class="pages-area">
                                <?php if(count($end_users) > 0) { foreach($end_users as $i => $end_user) { ?>
                                    <div class="mt-2 p-3 bg-light text-dark border rounded">
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
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-right">Showing <?php echo count($end_users) ?> result(s)</span>
                        <?php $this->load->view('admin/layout/pagination') ?>
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