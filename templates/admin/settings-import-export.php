<?php
/**
 * Import-export template
 */
?>
<div class="ava-menu-import-export">
	<a href="<?php echo ava_menu_option_page()->export_url(); ?>" class="cherry5-ui-button cherry5-ui-button-normal-style ui-button ava-menu-export-btn">
		<?php esc_html_e( 'Export', 'ava-menu' ); ?>
	</a>
	<button type="button" class="cherry5-ui-button cherry5-ui-button-normal-style ui-button ava-menu-import-btn">
		<?php esc_html_e( 'Import', 'ava-menu' ); ?>
	</button>
	<div class="ava-menu-import">
		<div class="ava-menu-import__inner">
			<input type="file" class="ava-menu-import-file" accept="application/json" multiple="false">
			<button type="button" class="cherry5-ui-button cherry5-ui-button-normal-style ui-button ava-menu-run-import-btn">
				<span class="text"><?php esc_html_e( 'Go', 'ava-menu' ); ?></span><span class="loader-wrapper"><span class="loader"></span></span><span class="dashicons dashicons-yes icon"></span>
			</button>
			<div class="ava-menu-import-messages"></div>
		</div>
	</div>
</div>
