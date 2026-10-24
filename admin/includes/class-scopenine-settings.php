<?php
/**
 * Main Admin Settings Class.
 *
 * This file is used to define admin settings using settings API.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Scope_Nine_Alert_Bar
 * @subpackage Scope_Nine_Alert_Bar/admin/includes
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die();

/**
 * Main Admin Settings Class
 *
 * This Class is used to define admin settings
 *
 * @since 1.0.0
 */
class SCOPENINE_Settings {

	/**
	 * Holds all saved settings options
	 *
	 * @var array $scopenine_settings
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected $scopenine_settings;

	/**
	 * Class Constructor
	 */
	public function __construct() {
		$this->scopenine_settings = get_option( 'scopenine_settings' );
	}

	/**
	 * Displays settings fields
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function do_settings_fields() {
		settings_fields( 'scopenine_settings' );
		do_settings_sections( 'scopenine_settings' );
	}

	/**
	 * Registers plugin settings using WP Settings API
	 *
	 * @since 1.0.0
	 */
	public function scopenine_register_settings() {
		register_setting( 'scopenine_settings', 'scopenine_settings', array(
			'sanitize_callback' => array( $this, 'sanitize_settings_fields' ),
		) );

		add_settings_section(
			'scopenine_settings_section',
			__( 'General Settings', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_settings_section_callback' ),
			'scopenine_settings'
		);

		add_settings_field(
			'scopenine_enabled',
			__( 'Enable Alert Bar', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_enabled_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_title',
			__( 'Alert Content', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_title_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_background_colour',
			__( 'Background Color', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_bg_color_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_text_colour',
			__( 'Text Color', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_text_color_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_font_size',
			__( 'Font Size', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_font_size_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_padding',
			__( 'Padding', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_padding_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_close_icon',
			__( 'Close Icon', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_close_icon_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_position',
			__( 'Bar Position', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_selected_position_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_display_mode',
			__( 'Display On', 'scope-nine-alert-bar' ),
			array( $this, 'scopenine_display_mode_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

		add_settings_field(
			'scopenine_selected_pages',
			'',
			array( $this, 'scopenine_selected_pages_callback' ),
			'scopenine_settings',
			'scopenine_settings_section'
		);

	}

	/**
	 * Registers advanced settings (separate settings group for the Advanced subpage)
	 *
	 * @since 1.0.0
	 */
	public function scopenine_register_advanced_settings() {
		register_setting( 'scopenine_advanced_settings', 'scopenine_settings', array(
			'sanitize_callback' => array( $this, 'sanitize_settings_fields' ),
		) );
	}

	/**
	 * Main Settings Section Callback
	 *
	 * @since 1.0.0
	 */
	public function scopenine_settings_section_callback() {
	}

	/**
	 * Settings Field - Enable Alert Bar callback
	 *
	 * @since 1.0.0
	 */
	public function scopenine_enabled_callback() {
		$value = isset( $this->scopenine_settings['enabled'] ) && $this->scopenine_settings['enabled'] ? '1' : '';
		?>
		<label class="scopenine-checkbox">
			<input name="scopenine_settings[enabled]" id="scopenine_enabled" type="checkbox" value="1" <?php checked( $value, '1' ); ?> />
			<span class="slider round"></span>
		</label>
		<small class="scopenine-field-description"><?php esc_html_e( 'Turn the alert bar on or off. When disabled, the bar will not appear on your site.', 'scope-nine-alert-bar' ); ?></small>
		<?php
	}

	/**
	 * Settings Field - title callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_title_callback() {
		$content = $this->scopenine_settings['news_title'] ?? '';
		wp_editor( $content, 'scopenine_news_title', array(
			'textarea_name' => 'scopenine_settings[news_title]',
			'textarea_rows' => 5,
			'media_buttons' => false,
			'teeny'         => true,
			'quicktags'     => true,
			'tinymce'       => array(
				'toolbar1' => 'bold,italic,underline,link,unlink,forecolor',
				'toolbar2' => '',
			),
		) );
		?>
		<small class="scopenine-field-description"><?php esc_html_e( 'Enter the alert bar content. Supports bold, italic, links, and text color.', 'scope-nine-alert-bar' ); ?></small>
		<?php
	}

	/**
	 * Settings Field - Background Color Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_bg_color_callback() {
		$value = esc_attr( $this->scopenine_settings['background_color'] ?? '#ff0000' );
		?>
		<div class="scopenine-color-field">
			<input class="scopenine-color-swatch" id="scopenine_background_color_picker" type="color" value="<?php echo esc_attr( $value ); ?>" />
			<input class="scopenine-color-text" name="scopenine_settings[background_color]" id="scopenine_background_color" type="text"
			       value="<?php echo esc_attr( $value ); ?>" maxlength="7" placeholder="#000000" />
		</div>
		<?php
	}

	/**
	 * Settings Field - Text Color Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_text_color_callback() {
		$value = esc_attr( $this->scopenine_settings['text_color'] ?? '#ffffff' );
		?>
		<div class="scopenine-color-field">
			<input class="scopenine-color-swatch" id="scopenine_text_color_picker" type="color" value="<?php echo esc_attr( $value ); ?>" />
			<input class="scopenine-color-text" name="scopenine_settings[text_color]" id="scopenine_text_color" type="text"
			       value="<?php echo esc_attr( $value ); ?>" maxlength="7" placeholder="#000000" />
		</div>
		<?php
	}

	/**
	 * Settings Field - Font Size Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_font_size_callback() {
		$value = $this->scopenine_settings['font_size'] ?? '14';
		?>
		<div class="scopenine-range-field">
			<input class="scopenine-settings-field" name="scopenine_settings[font_size]" id="scopenine_font_size" type="range"
			       min="10" max="32" step="1" value="<?php echo esc_attr( $value ); ?>" />
			<span id="scopenine-font-size-value" class="scopenine-range-value"><?php echo esc_html( $value ); ?>px</span>
		</div>
		<small class="scopenine-field-description"><?php esc_html_e( 'Set the font size of the alert bar text (10px - 32px).', 'scope-nine-alert-bar' ); ?></small>
		<?php
	}

	/**
	 * Settings Field - Padding Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_padding_callback() {
		$top    = esc_attr( $this->scopenine_settings['padding_top'] ?? '10' );
		$right  = esc_attr( $this->scopenine_settings['padding_right'] ?? '40' );
		$bottom = esc_attr( $this->scopenine_settings['padding_bottom'] ?? '10' );
		$left   = esc_attr( $this->scopenine_settings['padding_left'] ?? '40' );
		?>
		<div class="scopenine-padding-field">
			<div class="scopenine-padding-inputs">
				<div class="scopenine-padding-input-group">
					<label for="scopenine_padding_top"><?php esc_html_e( 'Top', 'scope-nine-alert-bar' ); ?></label>
					<input type="number" name="scopenine_settings[padding_top]" id="scopenine_padding_top" class="scopenine-padding-input" value="<?php echo esc_attr( $top ); ?>" min="0" max="100" />
				</div>
				<div class="scopenine-padding-input-group">
					<label for="scopenine_padding_right"><?php esc_html_e( 'Right', 'scope-nine-alert-bar' ); ?></label>
					<input type="number" name="scopenine_settings[padding_right]" id="scopenine_padding_right" class="scopenine-padding-input" value="<?php echo esc_attr( $right ); ?>" min="0" max="200" />
				</div>
				<div class="scopenine-padding-input-group">
					<label for="scopenine_padding_bottom"><?php esc_html_e( 'Bottom', 'scope-nine-alert-bar' ); ?></label>
					<input type="number" name="scopenine_settings[padding_bottom]" id="scopenine_padding_bottom" class="scopenine-padding-input" value="<?php echo esc_attr( $bottom ); ?>" min="0" max="100" />
				</div>
				<div class="scopenine-padding-input-group">
					<label for="scopenine_padding_left"><?php esc_html_e( 'Left', 'scope-nine-alert-bar' ); ?></label>
					<input type="number" name="scopenine_settings[padding_left]" id="scopenine_padding_left" class="scopenine-padding-input" value="<?php echo esc_attr( $left ); ?>" min="0" max="200" />
				</div>
				<button type="button" id="scopenine-padding-link" class="scopenine-padding-link-btn" title="<?php esc_attr_e( 'Link values', 'scope-nine-alert-bar' ); ?>">
					<span class="dashicons dashicons-admin-links"></span>
				</button>
			</div>
			<small class="scopenine-field-description"><?php esc_html_e( 'Set padding in pixels. Click the link icon to sync all values.', 'scope-nine-alert-bar' ); ?></small>
		</div>
		<?php
	}

	/**
	 * Settings Field - Close Icon Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_close_icon_callback() {
		$icon_url     = $this->scopenine_settings['close_icon_url'] ?? '';
		$default_icon = SCOPENINE_PLUGIN_URL . 'admin/assets/img/close-default.svg';
		$preview_url  = ! empty( $icon_url ) ? $icon_url : $default_icon;
		?>
		<div class="scopenine-close-icon-field" data-default="<?php echo esc_url( $default_icon ); ?>">
			<div class="scopenine-close-icon-preview">
				<img id="scopenine-close-icon-img" src="<?php echo esc_url( $preview_url ); ?>" alt="close icon" style="width:16px;height:16px;" />
			</div>
			<input type="hidden" name="scopenine_settings[close_icon_url]" id="scopenine_close_icon_url" value="<?php echo esc_attr( $icon_url ); ?>" />
			<button type="button" class="button" id="scopenine-upload-close-icon"><?php esc_html_e( 'Change Icon', 'scope-nine-alert-bar' ); ?></button>
			<?php if ( ! empty( $icon_url ) ) : ?>
				<button type="button" class="button" id="scopenine-reset-close-icon"><?php esc_html_e( 'Reset to Default', 'scope-nine-alert-bar' ); ?></button>
			<?php else : ?>
				<button type="button" class="button" id="scopenine-reset-close-icon" style="display:none;"><?php esc_html_e( 'Reset to Default', 'scope-nine-alert-bar' ); ?></button>
			<?php endif; ?>
		</div>
		<small class="scopenine-field-description"><?php esc_html_e( 'Upload a custom close icon (SVG or PNG recommended, 20x20px). Leave empty for the default icon.', 'scope-nine-alert-bar' ); ?></small>
		<?php
	}

	/**
	 * Settings Field - Alert Bar Position Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_selected_position_callback() {
		$value = $this->scopenine_settings['scopenine_position'] ?? 'after-header';
		?>
		<div class="scopenine-fancy-select" data-value="<?php echo esc_attr( $value ); ?>">
			<button type="button" class="scopenine-fancy-select-trigger" aria-haspopup="listbox">
				<span class="scopenine-fancy-select-icon">
					<span class="dashicons dashicons-arrow-down-alt"></span>
				</span>
				<span class="scopenine-fancy-select-label"><?php echo 'before-header' === $value ? esc_html__( 'Before Header', 'scope-nine-alert-bar' ) : esc_html__( 'After Header', 'scope-nine-alert-bar' ); ?></span>
				<span class="scopenine-fancy-select-arrow">
					<span class="dashicons dashicons-arrow-down-alt2"></span>
				</span>
			</button>
			<ul class="scopenine-fancy-select-options" role="listbox">
				<li class="scopenine-fancy-select-option<?php echo 'after-header' === $value ? ' selected' : ''; ?>" data-value="after-header" role="option">
					<span class="dashicons dashicons-arrow-down-alt"></span>
					<?php esc_html_e( 'After Header', 'scope-nine-alert-bar' ); ?>
				</li>
				<li class="scopenine-fancy-select-option<?php echo 'before-header' === $value ? ' selected' : ''; ?>" data-value="before-header" role="option">
					<span class="dashicons dashicons-arrow-up-alt"></span>
					<?php esc_html_e( 'Before Header', 'scope-nine-alert-bar' ); ?>
				</li>
			</ul>
			<input type="hidden" name="scopenine_settings[scopenine_position]" id="scopenine-position-select" value="<?php echo esc_attr( $value ); ?>" />
		</div>
		<small class="scopenine-field-description"><?php esc_html_e( 'Select where the alert bar appears relative to the header.', 'scope-nine-alert-bar' ); ?></small>
		<?php
	}

	/**
	 * Settings Field - Display Mode Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_display_mode_callback() {
		$value = $this->scopenine_settings['display_mode'] ?? 'site_wide';

		$options = array(
			'site_wide'      => array(
				'label' => __( 'Site Wide (All Pages)', 'scope-nine-alert-bar' ),
				'icon'  => 'dashicons-admin-site-alt3',
			),
			'specific_pages' => array(
				'label' => __( 'Specific Pages Only', 'scope-nine-alert-bar' ),
				'icon'  => 'dashicons-admin-page',
			),
			'homepage_only'  => array(
				'label' => __( 'Homepage Only', 'scope-nine-alert-bar' ),
				'icon'  => 'dashicons-admin-home',
			),
		);

		$current_label = $options[ $value ]['label'] ?? $options['site_wide']['label'];
		$current_icon  = $options[ $value ]['icon'] ?? $options['site_wide']['icon'];
		?>
		<div class="scopenine-fancy-select" data-value="<?php echo esc_attr( $value ); ?>">
			<button type="button" class="scopenine-fancy-select-trigger" aria-haspopup="listbox">
				<span class="scopenine-fancy-select-icon">
					<span class="dashicons <?php echo esc_attr( $current_icon ); ?>"></span>
				</span>
				<span class="scopenine-fancy-select-label"><?php echo esc_html( $current_label ); ?></span>
				<span class="scopenine-fancy-select-arrow">
					<span class="dashicons dashicons-arrow-down-alt2"></span>
				</span>
			</button>
			<ul class="scopenine-fancy-select-options" role="listbox">
				<?php foreach ( $options as $opt_value => $opt ) : ?>
				<li class="scopenine-fancy-select-option<?php echo $value === $opt_value ? ' selected' : ''; ?>" data-value="<?php echo esc_attr( $opt_value ); ?>" data-icon="<?php echo esc_attr( $opt['icon'] ); ?>" role="option">
					<span class="dashicons <?php echo esc_attr( $opt['icon'] ); ?>"></span>
					<?php echo esc_html( $opt['label'] ); ?>
				</li>
				<?php endforeach; ?>
			</ul>
			<input type="hidden" name="scopenine_settings[display_mode]" id="scopenine-display-mode-select" value="<?php echo esc_attr( $value ); ?>" />
		</div>
		<small class="scopenine-field-description"><?php esc_html_e( 'Choose where to display the alert bar.', 'scope-nine-alert-bar' ); ?></small>
		<?php
	}

	/**
	 * Settings Field - Selected Pages Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_selected_pages_callback() {
		$selected_pages = isset( $this->scopenine_settings['selected_pages'] ) && is_array( $this->scopenine_settings['selected_pages'] )
			? $this->scopenine_settings['selected_pages']
			: array();
		$display_mode   = $this->scopenine_settings['display_mode'] ?? 'site_wide';
		$hidden_class   = ( 'specific_pages' !== $display_mode ) ? ' scopenine-hide' : '';

		$pages = get_pages( array( 'post_status' => 'publish', 'sort_column' => 'post_title', 'sort_order' => 'ASC' ) );
		$posts = get_posts( array( 'post_status' => 'publish', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
		?>
		<div id="scopenine-pages-selector-wrap" class="<?php echo esc_attr( $hidden_class ); ?>">
			<div class="scopenine-pages-selector">
				<div class="scopenine-pages-search-wrap">
					<input type="text" id="scopenine-pages-search" class="scopenine-settings-field" placeholder="<?php esc_attr_e( 'Search pages and posts...', 'scope-nine-alert-bar' ); ?>" />
				</div>
				<div class="scopenine-pages-list" id="scopenine-pages-list">
					<?php if ( ! empty( $pages ) ) : ?>
						<p class="scopenine-pages-group-title"><strong><?php esc_html_e( 'Pages', 'scope-nine-alert-bar' ); ?></strong></p>
						<?php foreach ( $pages as $page ) :
							$checked = in_array( (string) $page->ID, $selected_pages, true ) ? 'checked' : '';
							?>
							<label class="scopenine-page-item" data-title="<?php echo esc_attr( strtolower( $page->post_title ) ); ?>">
								<input type="checkbox" name="scopenine_settings[selected_pages][]" value="<?php echo esc_attr( $page->ID ); ?>" <?php echo esc_attr( $checked ); ?> />
								<?php echo esc_html( $page->post_title ); ?>
							</label>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( ! empty( $posts ) ) : ?>
						<p class="scopenine-pages-group-title"><strong><?php esc_html_e( 'Posts', 'scope-nine-alert-bar' ); ?></strong></p>
						<?php foreach ( $posts as $post ) :
							$checked = in_array( (string) $post->ID, $selected_pages, true ) ? 'checked' : '';
							?>
							<label class="scopenine-page-item" data-title="<?php echo esc_attr( strtolower( $post->post_title ) ); ?>">
								<input type="checkbox" name="scopenine_settings[selected_pages][]" value="<?php echo esc_attr( $post->ID ); ?>" <?php echo esc_attr( $checked ); ?> />
								<?php echo esc_html( $post->post_title ); ?>
							</label>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="scopenine-pages-actions">
					<a href="#" id="scopenine-select-all"><?php esc_html_e( 'Select All', 'scope-nine-alert-bar' ); ?></a>
					<span> | </span>
					<a href="#" id="scopenine-deselect-all"><?php esc_html_e( 'Deselect All', 'scope-nine-alert-bar' ); ?></a>
				</div>
			</div>
			<small class="scopenine-field-description"><?php esc_html_e( 'Select the pages and posts where the alert bar should appear.', 'scope-nine-alert-bar' ); ?></small>
		</div>
		<?php
	}

	/**
	 * Settings Field - Remove Plugin Data Callback method
	 *
	 * @since 1.0.0
	 */
	public function scopenine_remove_data_callback() {
		?>
		<label class="scopenine-checkbox">
			<input name="scopenine_settings[remove_data]" id="remove-data" type="checkbox" <?php checked( isset( $this->scopenine_settings['remove_data'] ) ); ?> />
			<span class="slider round"></span>
		</label>
		<small class="scopenine-field-inline-description"><?php esc_html_e( 'Enable to delete plugin data on Uninstall.', 'scope-nine-alert-bar' ); ?></small>
		<?php
	}

	/**
	 * Sanitize Settings Fields Before Saving Data
	 *
	 * @param array $options Settings options.
	 * @return array
	 * @since 1.0.0
	 */
	public function sanitize_settings_fields( array $options ): array {
		// Checkbox: when unchecked it is not in $_POST, so set explicitly.
		$options['enabled'] = isset( $options['enabled'] ) && $options['enabled'] ? '1' : '';

		foreach ( $options as $key => $value ) {
			if ( 'enabled' === $key ) {
				continue; // Already set above.
			}
			if ( 'selected_pages' === $key && is_array( $value ) ) {
				$options[ $key ] = array_map( 'sanitize_text_field', $value );
			} elseif ( 'news_title' === $key ) {
				$options[ $key ] = wp_kses_post( $value );
			} elseif ( 'close_icon_url' === $key ) {
				$options[ $key ] = esc_url_raw( $value );
			} elseif ( in_array( $key, array( 'font_size', 'padding_top', 'padding_right', 'padding_bottom', 'padding_left' ), true ) ) {
				$options[ $key ] = absint( $value );
			} else {
				$options[ $key ] = sanitize_text_field( $value );
			}
		}

		return $options;
	}
}
