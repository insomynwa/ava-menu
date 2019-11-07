<div class="ava-content-tab-wrap">
	<div class="ava-enabled-wrap"><?php
		echo $enabled;
	?></div>
	<div class="ava-edit-content-btn">
		<?php if ( ava_menu()->has_elementor() ) : ?>
		<button class="cherry5-ui-button cherry5-ui-button-success-style button-hero ava-menu-editor"><?php
			esc_html_e( 'Edit Mega Menu Item Content', 'ava-menu' );
		?></button>
		<?php else : ?>
		<p><?php
			esc_html_e( 'This plugin requires Elementor page builder to edt Mega Menu items content', 'ava-menu' );
		?></p>
		<?php endif; ?>
	</div>
</div>