<?php
/**
 * Admin view for the Advanced settings subpage.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/admin/templates
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die();

settings_errors();
?>
<div class="wrap scopenine-wrap">
	<div class="scopenine-admin-header">
		<div class="scopenine-admin-header-inner">
			<span class="dashicons dashicons-megaphone scopenine-header-icon"></span>
			<div>
				<h1 class="scopenine-admin-title"><?php esc_html_e( 'Advanced Settings', 'scope-nine-alert-bar' ); ?></h1>
				<p class="scopenine-admin-subtitle"><?php esc_html_e( 'Data management and advanced options.', 'scope-nine-alert-bar' ); ?></p>
			</div>
		</div>
	</div>

	<form method="post" action="options.php">
		<?php settings_fields( 'scopenine_advanced_settings' ); ?>

		<div class="scopenine-card">
			<div class="scopenine-card-header">
				<h2 class="scopenine-card-title">
					<span class="dashicons dashicons-database"></span>
					<?php esc_html_e( 'Data Management', 'scope-nine-alert-bar' ); ?>
				</h2>
			</div>
			<div class="scopenine-card-body">
				<table class="form-table scopenine-form-table" role="presentation">
					<tr>
						<th scope="row"><?php esc_html_e( 'Remove Plugin Data', 'scope-nine-alert-bar' ); ?></th>
						<td>
							<?php do_action( 'scopenine_advanced_remove_data_field' ); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<?php submit_button( __( 'Save Changes', 'scope-nine-alert-bar' ) ); ?>
	</form>
</div>
