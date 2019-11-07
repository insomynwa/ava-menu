<?php
/**
 * Presets manager view
 */
?>
<div class="cherry-ui-kit cherry-control">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Create Preset', 'ava-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Create new preset from current options configuration', 'ava-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper">
				<input type="text" class="cherry-ui-text ava-preset-name" placeholder="<?php esc_html_e( 'Preset Name', 'ava-menu' ); ?>">
				<?php $this->preset_action( 'ava-menu-create-preset', esc_html__( 'Save', 'ava-menu' ) ); ?>
			</div>
		</div>
	</div>
</div>
<div class="cherry-ui-kit cherry-control">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Update Preset', 'ava-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Save current options configuration to existing preset', 'ava-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper"><?php
				$presets = $this->get_presets();
				if ( ! empty( $presets ) ) {
					$this->preset_select(
						'ava-update-preset',
						esc_html__( 'Select preset to update...', 'ava-menu' )
					);
					$this->preset_action( 'ava-menu-update-preset', esc_html__( 'Update', 'ava-menu' ) );
				} else {
					esc_html_e( 'You haven\'t created any presets yet', 'ava-menu' );
				}
			?></div>
		</div>
	</div>
</div>
<div class="cherry-ui-kit cherry-control">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Apply This Preset Globally', 'ava-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Load preset to use it for all menu locations', 'ava-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper"><?php
				$presets = $this->get_presets();
				if ( ! empty( $presets ) ) {
					$this->preset_select(
						'ava-load-preset',
						esc_html__( 'Select preset to apply...', 'ava-menu' )
					);
					$this->preset_action( 'ava-menu-load-preset', esc_html__( 'Load', 'ava-menu' ) );
				} else {
					esc_html_e( 'You haven\'t created any presets yet', 'ava-menu' );
				}
			?></div>
		</div>
	</div>
</div>
<div class="cherry-ui-kit cherry-control ava-delete-preset-wrap">
	<div class="cherry-control__info">
		<h4 class="cherry-ui-kit__title cherry-control__title" role="banner"><?php
			esc_html_e( 'Delete Preset', 'ava-menu' );
		?></h4>
		<div class="cherry-ui-kit__description cherry-control__description" role="note"><?php
			esc_html_e( 'Delete existing preset', 'ava-menu' );
		?></div>
	</div>
	<div class="cherry-ui-kit__content cherry-control__content" role="group">
		<div class="cherry-ui-container">
			<div class="cherry-ui-control-preset-wrapper"><?php
				$presets = $this->get_presets();
				if ( ! empty( $presets ) ) {
					$this->preset_select(
						'ava-delete-preset',
						esc_html__( 'Select preset to delete...', 'ava-menu' )
					);
					$this->preset_action( 'ava-menu-delete-preset', esc_html__( 'Delete', 'ava-menu' ) );
				} else {
					esc_html_e( 'You haven\'t created any presets yet', 'ava-menu' );
				}
			?></div>
		</div>
	</div>
</div>
