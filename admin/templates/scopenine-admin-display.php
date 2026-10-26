<?php
/**
 * Admin area view for the main Alert Bar settings.
 *
 * Includes a live visual preview that updates in real-time as settings change.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/admin/templates
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die();

$scopenine_settings   = get_option( 'scopenine_settings' );
$scopenine_bg_color       = esc_attr( $scopenine_settings['background_color'] ?? '#ff0000' );
$scopenine_txt_color      = esc_attr( $scopenine_settings['text_color'] ?? '#ffffff' );
$scopenine_font_size      = absint( $scopenine_settings['font_size'] ?? 14 );
$scopenine_padding_top    = absint( $scopenine_settings['padding_top'] ?? 10 );
$scopenine_padding_right  = absint( $scopenine_settings['padding_right'] ?? 40 );
$scopenine_padding_bottom = absint( $scopenine_settings['padding_bottom'] ?? 10 );
$scopenine_padding_left   = absint( $scopenine_settings['padding_left'] ?? 40 );
$scopenine_news_title     = $scopenine_settings['news_title'] ?? '';
$scopenine_close_icon_url = $scopenine_settings['close_icon_url'] ?? '';
$scopenine_default_icon   = SCOPENINE_PLUGIN_URL . 'admin/assets/img/close-default.svg';

settings_errors();
?>
<div class="wrap scopenine-wrap">

	<!-- Admin Header -->
	<div class="scopenine-admin-header">
		<div class="scopenine-admin-header-inner">
			<span class="dashicons dashicons-megaphone scopenine-header-icon"></span>
			<div>
				<h1 class="scopenine-admin-title"><?php esc_html_e( 'Alert Bar', 'scope-nine-alert-bar' ); ?></h1>
				<p class="scopenine-admin-subtitle"><?php esc_html_e( 'Design and configure your site-wide alert bar.', 'scope-nine-alert-bar' ); ?></p>
			</div>
		</div>
		<span class="scopenine-version-badge">v<?php echo esc_html( SCOPENINE_VERSION ); ?></span>
	</div>

	<!-- Live Preview (sticky) -->
	<div class="scopenine-preview-wrapper" id="scopenine-preview-wrapper">
		<div class="scopenine-preview-label">
			<span class="dashicons dashicons-visibility"></span>
			<?php esc_html_e( 'Live Preview', 'scope-nine-alert-bar' ); ?>
		</div>
		<div id="scopenine-live-preview"
		     class="scopenine-live-preview scopenine-alert-bar"
		     style="background-color:<?php echo esc_attr( $scopenine_bg_color ); ?>;color:<?php echo esc_attr( $scopenine_txt_color ); ?>;font-size:<?php echo esc_attr( $scopenine_font_size ); ?>px;padding:<?php echo esc_attr( $scopenine_padding_top ); ?>px <?php echo esc_attr( $scopenine_padding_right ); ?>px <?php echo esc_attr( $scopenine_padding_bottom ); ?>px <?php echo esc_attr( $scopenine_padding_left ); ?>px;">
			<span class="scopenine-live-preview-content scopenine-alert-bar-content" id="scopenine-preview-content"><?php echo wp_kses_post( $scopenine_news_title ); ?></span>
			<span class="scopenine-live-preview-close scopenine-alert-bar-close" id="scopenine-preview-close">
				<?php if ( ! empty( $scopenine_close_icon_url ) ) : ?>
					<img src="<?php echo esc_url( $scopenine_close_icon_url ); ?>" alt="close" style="width:14px;height:14px;" />
				<?php else : ?>
					<svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.6746 0.3254C19.2408-0.1085 18.5373-0.1085 18.1035 0.3254L10 8.4289 1.8965 0.3254C1.4626-0.1085 0.7592-0.1085 0.3254 0.3254-0.1085 0.7592-0.1085 1.4626 0.3254 1.8965L8.4289 10 0.3254 18.1035C-0.1085 18.5374-0.1085 19.2407 0.3254 19.6746 0.7593 20.1085 1.4627 20.1085 1.8965 19.6746L10 11.5711 18.1035 19.6746C18.5373 20.1085 19.2408 20.1085 19.6746 19.6746 20.1084 19.2407 20.1084 18.5374 19.6746 18.1036L11.571 10 19.6746 1.8965C20.1084 1.4626 20.1084 0.7592 19.6746 0.3254Z" fill="<?php echo esc_attr( $scopenine_txt_color ); ?>"/></svg>
				<?php endif; ?>
			</span>
		</div>
	</div>

	<!-- Settings Form -->
	<form method="post" action="options.php" id="scopenine-settings-form">
		<div class="scopenine-settings-content">
			<?php
			/**
			 * Populates All Setting Sections and Fields
			 *
			 * @action  - SCOPENINE_Settings->do_settings_fields
			 *
			 * @since 1.0.0
			 */
			do_action( 'scopenine_settings_content' );
			?>
		</div>
		<?php submit_button( __( 'Save Changes', 'scope-nine-alert-bar' ) ); ?>
	</form>
</div>
