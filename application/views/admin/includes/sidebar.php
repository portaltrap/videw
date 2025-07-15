<?php $uri = strtolower($this->uri->segment(1).'/'.$this->uri->segment(2)); ?>
<div class="sidebar sidebar-style-2" data-background-color="white">			
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<ul class="nav nav-primary">
						<li class="nav-item <?php echo_if($uri == GENERAL_CONTROLLER . '/dashboard', 'active'); ?>">
							<a href="<?php anchor_to(GENERAL_CONTROLLER . '/dashboard') ?>">
								<i class="fas fa-home"></i>
								<p>Home Dashboard</p>
							</a>
						</li>
						<li class="nav-item <?php echo_if($uri == GENERAL_CONTROLLER . '/settings', 'active'); ?>">
							<a href="<?php anchor_to(GENERAL_CONTROLLER . '/settings') ?>">
								<i class="fas fa-cog"></i>
								<p>General Settings</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == GENERAL_CONTROLLER . '/themes' || $uri == GENERAL_CONTROLLER . '/upload_theme', 'active'); ?>">
							<a href="<?php anchor_to(GENERAL_CONTROLLER . '/themes') ?>">
								<i class="fas fa-brush"></i>
								<p>Themes</p>
							</a>
						</li>
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Video Settings</h4>
						</li>
						<li class="nav-item <?php echo_if($uri == VIDUP_CONTROLLER . '/users' || $uri == VIDUP_CONTROLLER . '/edit_user' || $uri == VIDUP_CONTROLLER . '/delete_user', 'active'); ?>">
							<a href="<?php anchor_to(VIDUP_CONTROLLER . '/users') ?>">
								<i class="fas fa-users"></i>
								<p>Manage Users</p>
							</a>
						</li>
						<li class="nav-item <?php echo_if($uri == VIDUP_CONTROLLER . '/uploads', 'active'); ?>">
							<a href="<?php anchor_to(VIDUP_CONTROLLER . '/uploads') ?>">
								<i class="fas fa-upload"></i>
								<p>Manage Uploads</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == VIDUP_CONTROLLER . '/upload_settings', 'active'); ?>">
							<a href="<?php anchor_to(VIDUP_CONTROLLER . '/upload_settings') ?>">
								<i class="fas fa-cogs"></i>
								<p>Upload Settings</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == VIDUP_CONTROLLER . '/s3', 'active'); ?>">
							<a href="<?php anchor_to(VIDUP_CONTROLLER . '/s3') ?>">
								<i class="fab fa-aws"></i>
								<p>Amazon S3 Settings</p>
							</a>
                        </li>
                        <li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Layout Settings</h4>
                        </li>
						<li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/social_keys', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/social_keys') ?>">
								<i class="fas fa-key"></i>
								<p>Social Login Keys</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/pages', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/pages') ?>">
								<i class="fas fa-layer-group"></i>
								<p>Page Settings</p>
							</a>
                        </li>
                        <li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/scripts', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/scripts') ?>">
								<i class="fas fa-cogs"></i>
								<p>Header / Footer Scripts</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/analytics', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/analytics') ?>">
								<i class="fas fa-chart-bar"></i>
								<p>Analytics Settings</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/meta_tags', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/meta_tags') ?>">
								<i class="fas fa-code"></i>
								<p>Meta Tags Settings</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/email', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/email') ?>">
								<i class="fas fa-at"></i>
								<p>E-Mail Settings</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/recaptcha', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/recaptcha') ?>">
								<i class="fas fa-unlock"></i>
								<p>Recaptcha Settings</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == LAYOUT_CONTROLLER . '/ads', 'active'); ?>">
							<a href="<?php anchor_to(LAYOUT_CONTROLLER . '/ads') ?>">
								<i class="fas fa-expand"></i>
								<p>Ad Settings</p>
							</a>
						</li>
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Admin</h4>
                        </li>
						<li class="nav-item <?php echo_if($uri == ACCOUNT_CONTROLLER . '/me', 'active'); ?>">
							<a href="<?php anchor_to(ACCOUNT_CONTROLLER . '/me') ?>">
								<i class="fas fa-user"></i>
								<p>My Account</p>
							</a>
						</li>
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Other</h4>
                        </li>
						<li class="nav-item <?php echo_if($uri == UPDATES_CONTROLLER . '/main', 'active'); ?>">
							<a href="<?php anchor_to(UPDATES_CONTROLLER . '/main') ?>">
								<i class="fas fa-wrench"></i>
								<p>Script Updates</p>
							</a>
                        </li>
						<li class="nav-item <?php echo_if($uri == GENERAL_CONTROLLER . '/purge_cache', 'active'); ?>">
							<a href="<?php anchor_to(GENERAL_CONTROLLER . '/purge_cache') ?>">
								<i class="fas fa-trash-alt"></i>
								<p>Purge Cache</p>
							</a>
                        </li>                        
					</ul>
				</div>
			</div>
		</div>
		<!-- End Sidebar -->