<div id="motopress-content-editor" style="display: none;">

	<div id="motopress-content-editor-scene-wrapper"></div>

	<div class="motopress-content-editor-navbar">
		<div class="navbar-inner">
			<div id="motopress-logo">
				<img src="<?php echo esc_url( $logoSrc ); ?>">
			</div>
			<div class="navbar-btns">
				<div class="navigation pull-left">
					<ul>
						<li class="menu-item">
							<a href="javascript:void(0);" id="motopress-content-editor-page-settings"><?php echo __( "Page Settings", 'motopress-content-editor-lite' ); ?></a>
						</li>
<!--						<li class="menu-item">-->
<!--							<a href="javascript:void(0);"><i class="fa fa-undo"></i>--><?php //_e( 'Undo', 'motopress-content-editor-lite' ); ?><!--</a>-->
<!--						</li>-->
<!--						<li class="menu-item">-->
<!--							<a href="javascript:void(0);"><i class="fa fa-repeat"></i>--><?php //_e( 'Redo', 'motopress-content-editor-lite' ); ?><!--</a>-->
<!--						</li>-->
						<li class="menu-item">
							<a href="javascript:void(0);" id="motopress-content-editor-history"><?php _e( 'History', 'motopress-content-editor-lite' ); ?></a>
						</li>
						<li class="menu-item">
							<a href="javascript:void(0);" id="motopress-content-editor-save-page-object-template"><?php echo $savePageTemplateBtnTitle; ?></a>
						</li>
						<!--<li class="menu-item">
							<a href="javascript:void(0);"><i class="fa fa-folder-o"></i><?php //_e( 'Library', 'motopress-content-editor-lite' ); ?></a>
							<ul class="sub-menu">
								<li class="menu-item">
									<a href="javascript:void(0);" id="motopress-content-editor-save-page-object-template"><?php //echo $savePageTemplateBtnTitle; ?></a>
								</li>
							</ul>
						</li>-->
						<!--<li class="menu-item">
							<a href="javascript:void(0);"><i class="fa fa-ellipsis-h"></i><?php //_e( '', 'motopress-content-editor-lite' ); ?></a>
							<ul class="sub-menu">
								<li class="menu-item">
									<a href="javascript:void(0);"><?php //_e( 'Clear Layout', 'motopress-content-editor-lite' ); ?></a>
								</li>
							</ul>
						</li>-->
					</ul>
				</div>
				<div class="navigation pull-right">
					<ul>
						<li class="menu-item">
							<select id="mpce-responsive-switcher">
								<option value="desktop"><?php _e( 'Desktop', 'motopress-content-editor-lite' ); ?></option>
								<option value="tablet"><?php _e( 'Tablet', 'motopress-content-editor-lite' ); ?></option>
								<option value="mobile"><?php _e( 'Mobile', 'motopress-content-editor-lite' ); ?></option>
							</select>
						</li>
						<li class="menu-item">
						<?php if ( ! $isHideTutorials ) { ?>
							<a href="javascript:void(0);" id="motopress-content-editor-tutorials">
								<i class="fa fa-question-circle"></i><?php _e( 'Help', 'motopress-content-editor-lite' ); ?></a>
						<?php } ?>
						</li>
						<?php echo '<li class="menu-item">' . '<a href="' . esc_url( mpceSettings()['lite_upgrade_url'] ) .
						     '" target="_blank" id="motopress-content-editor-upgrade">' .
						     __( "Upgrade", "motopress-content-editor-lite" ) . '</a></li>';
						?>
						<li class="menu-item">
							<a href="javascript:void(0);" class="motopress-content-editor-saving-btn" id="motopress-content-editor-update">
								<?php _e( 'Save', 'motopress-content-editor-lite' ); ?>
							</a>
						</li>
						<li class="menu-item">
							<a href="javascript:void(0);" class="motopress-content-editor-saving-btn" id="motopress-content-editor-publish">
								<?php _e( 'Publish', 'motopress-content-editor-lite' ); ?>
							</a>
						</li>
						<li class="menu-item">
							<a href="javascript:void(0);" id="motopress-content-editor-close"><?php _e( 'Close', 'motopress-content-editor-lite' ); ?></a>
							<ul class="sub-menu right">
								<li class="menu-item"><a href="<?php echo get_edit_post_link(); ?>"><?php _e( 'Edit Post', 'motopress-content-editor-lite' ); ?></a></li>
								<li class="menu-item"><a href="<?php echo get_dashboard_url(); ?>"><?php _e( 'Dashboard', 'motopress-content-editor-lite' ); ?></a></li>
							</ul>
						</li>
						<li class="menu-item">
							<a href="<?php echo get_edit_user_link(); ?>">
							<?php
							$current_user = wp_get_current_user();
							if ( $current_user instanceof WP_User ) {
								echo get_avatar( $current_user->ID, 24 );
							}
							?>
							</a>
							<ul class="sub-menu right">
								<li class="menu-item"><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e( 'Log Out', 'motopress-content-editor-lite' ); ?></a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>

	<div id="motopress-flash"></div>

	<!-- Video Tutorials -->
	<div id="motopress-tutorials-modal" class="motopress-modal modal motopress-soft-hide fade">
		<div class="modal-header">
			<p id="tutsModalLabel"
			   class="modal-header-label"><?php printf( __( "%s Help & Tutorials", 'motopress-content-editor-lite' ), mpceSettings()['brand_name'] ); ?>
				<button type="button" tabindex="0" class="close massive-modal-close" data-dismiss="modal"
				        aria-hidden="true">&times;</button>
			</p>
		</div>
		<div class="modal-body"></div>
	</div>

	<!-- Code editor -->
	<div id="motopress-code-editor-modal" class="motopress-modal modal motopress-soft-hide fade" role="dialog"
	     aria-labelledby="codeModalLabel" aria-hidden="true">
		<div class="modal-header">
			<p id="codeModalLabel"
			   class="modal-header-label"><?php echo __( "Edit", 'motopress-content-editor-lite' ) . ' ' . __( "WordPress Text", 'motopress-content-editor-lite' ); ?></p>
		</div>
		<div class="modal-body">
			<div id="motopress-code-editor-wrapper">
				<?php
				wp_editor( '', 'motopresscodecontent', array(
					'textarea_rows' => false,
					'tinymce'       => array(
						'remove_linebreaks'       => false,
						'schema'                  => 'html5',
						'theme_advanced_resizing' => false,
					),
				) );
				?>
			</div>
		</div>
		<div class="modal-footer">
			<button id="motopress-save-code-content"
			        class="motopress-btn-blue"><?php echo __( "Save", 'motopress-content-editor-lite' ); ?></button>
			<button class="motopress-btn-default" data-dismiss="modal"
			        aria-hidden="true"><?php echo __( "Close", 'motopress-content-editor-lite' ); ?></button>
		</div>
	</div>

	<!-- Save Preset Modal -->
	<div id="motopress-ce-save-preset-modal" class="motopress-modal modal motopress-soft-hide fade" role="dialog"
	     aria-labelledby="codeModalLabel" aria-hidden="true">
		<div class="modal-header">
			<p id="codeModalLabel"
			   class="modal-header-label"><?php echo __( "Save Preset", 'motopress-content-editor-lite' ); ?></p>
		</div>
		<div class="modal-body">
			<p class="description motopress-ce-preset-inheritance motopress-hide"><?php echo __( "Inherit properties from preset", 'motopress-content-editor-lite' ); ?>
				"<b class="motopress-ce-preset-inheritance-name"></b>"</p>
			<?php $presets = MPCECustomStyleManager::getAllPresets(); ?>
			<div class="motopress-ce-save-preset-select-wrapper motopress-ce-modal-control-wrapper">
				<label
					for="motopress-ce-save-preset-select"><?php echo __( "Category:", 'motopress-content-editor-lite' ); ?></label>
				<select id="motopress-ce-save-preset-select">
					<option value=""><?php echo __( "Create New Preset", 'motopress-content-editor-lite' ); ?></option>
					<optgroup label="<?php echo __( "Save As Preset", 'motopress-content-editor-lite' ); ?>"
					          class="<?php echo empty( $presets ) ? 'motopress-hide' : ''; ?>">
						<?php foreach ( $presets as $name => $details ) { ?>
							<option value="<?php echo $name; ?>"><?php echo $details['label']; ?></option>
						<?php } ?>
					</optgroup>
				</select>
			</div>
			<div class="motopress-ce-save-preset-name-wrapper motopress-ce-modal-control-wrapper">
				<label
					for="motopress-ce-save-preset-name"><?php echo __( "Preset Name:", 'motopress-content-editor-lite' ); ?></label>
				<input type="text" id="motopress-ce-save-preset-name" name="preset-name"/>
				<p class="description"><?php echo __( "Leave this field blank to generate name automatically.", 'motopress-content-editor-lite' ); ?></p>
			</div>
		</div>
		<div class="modal-footer">
			<button id="motopress-ce-create-preset"
			        class="motopress-btn-blue"><?php echo __( "Create Preset", 'motopress-content-editor-lite' ); ?></button>
			<button id="motopress-ce-update-preset"
			        class="motopress-btn-blue motopress-hide"><?php echo __( "Update Preset", 'motopress-content-editor-lite' ); ?></button>
			<button class="motopress-btn-default" data-dismiss="modal"
			        aria-hidden="true"><?php echo __( "Close", 'motopress-content-editor-lite' ); ?></button>
		</div>
	</div>
	<div id="motopress-ce-save-object-modal" class="motopress-modal modal motopress-soft-hide fade"
	     role="dialog" aria-labelledby="codeModalLabel" aria-hidden="true"></div>
	<div id="mpce-layout-chooser" class="motopress-soft-hide"></div>
	<div id="mpce-template-chooser" class="motopress-soft-hide"></div>
	<div id="mpce-widgets-panel" class="motopress-soft-hide"></div>
	<div id="motopress-dialog" class="motopress-soft-hide"></div>
	<div id="mpce-page-dialog" class="motopress-soft-hide"></div>
	<div id="mpce-history-dialog" class="motopress-soft-hide"></div>
</div>

<div id="motopress-preload">
	<input type="text" id="motopress-knob" data-readOnly="true" data-thickness="0.06" data-fgColor="#51545e" data-width="150" data-height="150">

	<div id="motopress-error">
		<div
			id="motopress-error-title"><?php echo __( "An internal error occurred during loading Visual Builder. Please submit this data to support team.", 'motopress-content-editor-lite' ); ?></div>
		<div id="motopress-error-message">
			<div id="motopress-system">
				<p id="motopress-browser"></p>
				<p id="motopress-platform"></p>
			</div>
		</div>
		<div class="motopress-terminate">
			<button id="motopress-terminate"
			        class="motopress-btn-default"><?php echo __( "Terminate & Close", 'motopress-content-editor-lite' ); ?></button>
		</div>
	</div>
</div>

<?php
