<?php
/*
Plugin Name: Colored Tag Cloud Listing
Plugin URI: http://www.jimmyscode.com/wordpress/colored-tag-cloud-listing/
Description: Display a tag cloud on your site.
Version: 0.0.6
Author: Jimmy Pe&ntilde;a
Author URI: http://www.jimmyscode.com/
License: GPLv2 or later
*/
if (!defined('JPCTCL_PLUGIN_NAME')) {
	// plugin constants
	define('JPCTCL_PLUGIN_NAME', 'Colored Tag Cloud Listing');
	define('JPCTCL_VERSION', '0.0.6');
	define('JPCTCL_SLUG', 'colored-tag-cloud-listing');
	define('JPCTCL_LOCAL', 'jpctcl');
	define('JPCTCL_OPTION', 'jpctcl');
	define('JPCTCL_OPTIONS_NAME', 'jpctcl_options');
	define('JPCTCL_PERMISSIONS_LEVEL', 'manage_options');
	define('JPCTCL_PATH', plugin_basename(dirname(__FILE__)));
	/* default values */
	define('JPCTCL_DEFAULT_ENABLED', true);
	define('JPCTCL_DEFAULT_STYLE', '');
	define('JPCTCL_DEFAULT_NOFOLLOW', false);
	define('JPCTCL_DEFAULT_SHOW', false);
	define('JPCTCL_AVAILABLE_STYLES', 'green,blue,yellow,orange,red,purple,aqua,black,gray,lime,maroon,navy,olive,silver,teal');
	/* option array member names */
	define('JPCTCL_DEFAULT_ENABLED_NAME', 'enabled');
	define('JPCTCL_DEFAULT_STYLE_NAME', 'cssclass');
	define('JPCTCL_DEFAULT_NOFOLLOW_NAME', 'nofollow');
	define('JPCTCL_DEFAULT_SHOW_NAME', 'show');
}
	// oh no you don't
	if (!defined('ABSPATH')) {
		wp_die(__('Do not access this file directly.', jpctcl_get_local()));
	}
	// localization to allow for translations
	// also, register the plugin CSS file for later inclusion
	add_action('init', 'jpctcl_translation_file');
	function jpctcl_translation_file() {
		$plugin_path = jpctcl_get_path() . '/translations';
		load_plugin_textdomain(jpctcl_get_local(), '', $plugin_path);
		register_jpctcl_style();
	}
	// tell WP that we are going to use new options
	// also, register the admin CSS file for later inclusion
	add_action('admin_init', 'jpctcl_options_init');
	function jpctcl_options_init() {
		register_setting(JPCTCL_OPTIONS_NAME, jpctcl_get_option(), 'jpctcl_validation');
		register_jpctcl_admin_style();
		register_jpctcl_admin_script();
	}
	// validation function
	function jpctcl_validation($input) {
		// validate all form fields
		if (!empty($input)) {
			$input[JPCTCL_DEFAULT_ENABLED_NAME] = (bool)$input[JPCTCL_DEFAULT_ENABLED_NAME];
			$input[JPCTCL_DEFAULT_STYLE_NAME] = sanitize_text_field($input[JPCTCL_DEFAULT_STYLE_NAME]);
			$input[JPCTCL_DEFAULT_NOFOLLOW_NAME] = (bool)$input[JPCTCL_DEFAULT_NOFOLLOW_NAME];
		}
		return $input;
	}
	// add Settings sub-menu
	add_action('admin_menu', 'jpctcl_plugin_menu');
	function jpctcl_plugin_menu() {
		add_options_page(JPCTCL_PLUGIN_NAME, JPCTCL_PLUGIN_NAME, JPCTCL_PERMISSIONS_LEVEL, jpctcl_get_slug(), 'jpctcl_page');
	}
	// plugin settings page
	// http://planetozh.com/blog/2009/05/handling-plugins-options-in-wordpress-28-with-register_setting/
	// http://www.onedesigns.com/tutorials/how-to-create-a-wordpress-theme-options-page
	function JPCTCL_page() {
		// check perms
		if (!current_user_can(JPCTCL_PERMISSIONS_LEVEL)) {
			wp_die(__('You do not have sufficient permission to access this page', jpctcl_get_local()));
		}
		?>
		<div class="wrap">
			<h2 id="plugintitle"><img src="<?php echo jpctcl_getimagefilename('cloud.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php echo JPCTCL_PLUGIN_NAME; ?> by <a href="http://www.jimmyscode.com/">Jimmy Pe&ntilde;a</a></h2>
			<div>You are running plugin version <strong><?php echo JPCTCL_VERSION; ?></strong>.</div>
			
			<?php /* http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page--wp-24971 */ ?>
			<?php $active_tab = (isset($_GET['tab']) ? $_GET['tab'] : 'settings'); ?>
			<h2 class="nav-tab-wrapper">
			  <a href="?page=<?php echo jpctcl_get_slug(); ?>&tab=settings" class="nav-tab <?php echo $active_tab == 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', jpctcl_get_local()); ?></a>
				<a href="?page=<?php echo jpctcl_get_slug(); ?>&tab=parameters" class="nav-tab <?php echo $active_tab == 'parameters' ? 'nav-tab-active' : ''; ?>"><?php _e('Parameters', jpctcl_get_local()); ?></a>
				<a href="?page=<?php echo jpctcl_get_slug(); ?>&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', jpctcl_get_local()); ?></a>
			</h2>
			
			<form method="post" action="options.php">
			<?php settings_fields(JPCTCL_OPTIONS_NAME); ?>
			<?php $options = jpctcl_getpluginoptions(); ?>
			<?php update_option(jpctcl_get_option(), $options); ?>
			<?php if ($active_tab == 'settings') { ?>
			<h3 id="settings"><img src="<?php echo jpctcl_getimagefilename('settings.png'); ?>" title="" alt="" height="61" width="64" align="absmiddle" /> <?php _e('Plugin Settings', jpctcl_get_local()); ?></h3>
				<table class="form-table" id="theme-options-wrap">
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', jpctcl_get_local()); ?>" for="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_ENABLED_NAME; ?>]"><?php _e('Plugin enabled?', jpctcl_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_ENABLED_NAME; ?>]" name="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_ENABLED_NAME; ?>]" value="1" <?php checked('1', jpctcl_checkifset(JPCTCL_DEFAULT_ENABLED_NAME, JPCTCL_DEFAULT_ENABLED, $options)); ?> /></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Is plugin enabled? Uncheck this to turn it off temporarily.', jpctcl_get_local()); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Select the style you would like to use as the default.', jpctcl_get_local()); ?>" for="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_STYLE_NAME; ?>]"><?php _e('Default style', jpctcl_get_local()); ?></label></strong></th>
						<td><select id="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_STYLE_NAME; ?>]" name="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_STYLE_NAME; ?>]">
						<?php $tagstyles = explode(",", JPCTCL_AVAILABLE_STYLES);
							sort($tagstyles);
							foreach($tagstyles as $tagstyle) {
								echo '<option value="' . $tagstyle . '"' . selected($tagstyle, jpctcl_checkifset(JPCTCL_DEFAULT_STYLE_NAME, JPCTCL_DEFAULT_STYLE, $options), false) . '>' . $tagstyle . '</option>';
							} ?>
						</select></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Select the style you would like to use as the default if no style is otherwise specified.', jpctcl_get_local()); ?></td></tr>
					<tr valign="top"><th scope="row"><strong><label title="<?php _e('Check this box to add rel=nofollow to tag cloud links.', jpctcl_get_local()); ?>" for="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_NOFOLLOW_NAME; ?>]"><?php _e('Nofollow button link?', jpctcl_get_local()); ?></label></strong></th>
						<td><input type="checkbox" id="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_NOFOLLOW_NAME; ?>]" name="<?php echo jpctcl_get_option(); ?>[<?php echo JPCTCL_DEFAULT_NOFOLLOW_NAME; ?>]" value="1" <?php checked('1', jpctcl_checkifset(JPCTCL_DEFAULT_NOFOLLOW_NAME, JPCTCL_DEFAULT_NOFOLLOW, $options)); ?> /></td>
					</tr>
					<tr valign="top"><td colspan="2"><?php _e('Check this box to add rel="nofollow" to tag cloud links. You can override this at the shortcode level.', jpctcl_get_local()); ?></td></tr>
				</table>
				<?php submit_button(); ?>
			<?php } elseif ($active_tab == 'parameters') { ?>
			<h3 id="parameters"><img src="<?php echo jpctcl_getimagefilename('parameters.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Plugin Parameters and Default Values', jpctcl_get_local()); ?></h3>
			These are the parameters for using the shortcode, or calling the plugin from your PHP code.
			
			For available colors, see the dropdown list on the Settings tab.

			<?php echo jpctcl_parameters_table(jpctcl_get_local(), jpctcl_shortcode_defaults(), jpctcl_required_parameters()); ?>			

			<h3 id="examples"><img src="<?php echo jpctcl_getimagefilename('examples.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Shortcode and PHP Examples', jpctcl_get_local()); ?></h3>
			<h4><?php _e('Shortcode Format:', jpctcl_get_local()); ?></h4>
			<?php echo '<pre style="background:#FFF">' . jpctcl_get_example_shortcode('colored-tag-cloud', jpctcl_shortcode_defaults(), jpctcl_get_local()) . '</pre>'; ?>

			<h4><?php _e('PHP Format:', jpctcl_get_local()); ?></h4>
			<?php echo jpctcl_get_example_php_code('colored-tag-cloud', 'jpctcl', jpctcl_shortcode_defaults()); ?>
			<?php _e('<small>Note: \'show\' is false by default; set it to <strong>true</strong> echo the output, or <strong>false</strong> to return the output to your PHP code.</small>', jpctcl_get_local()); ?>
			<?php } else { ?>
			<h3 id="support"><img src="<?php echo jpctcl_getimagefilename('support.png'); ?>" title="" alt="" height="64" width="64" align="absmiddle" /> <?php _e('Support', jpctcl_get_local()); ?></h3>
				<div class="support">
				<?php echo jpctcl_getsupportinfo(jpctcl_get_slug(), jpctcl_get_local()); ?>
				</div>
			<?php } ?>
			</form>
		</div>
		<?php }
	// shortcode and function
	add_shortcode('colored-tag-cloud', 'jpctcl');
	add_shortcode('colored-tag-cloud-listing', 'jpctcl');
	function jpctcl($atts) {
		// get parameters
		extract(shortcode_atts(jpctcl_shortcode_defaults(), $atts));
		// plugin is enabled/disabled from settings page only
		$options = jpctcl_getpluginoptions();
		$enabled = (bool)$options[JPCTCL_DEFAULT_ENABLED_NAME];
		
		// ******************************
		// derive shortcode values from constants
		// ******************************
		if ($enabled) {
			$temp_style = constant('JPCTCL_DEFAULT_STYLE_NAME');
			$cssclass = $$temp_style;
			$temp_nofollow = constant('JPCTCL_DEFAULT_NOFOLLOW_NAME');
			$nofollow = $$temp_nofollow;
			$temp_show = constant('JPCTCL_DEFAULT_SHOW_NAME');
			$show = $$temp_show;
		}

		// ******************************
		// sanitize user input
		// ******************************
		if ($enabled) {
			$cssclass = sanitize_html_class($cssclass);
			if (!$cssclass) {
				$cssclass = JPCTCL_DEFAULT_STYLE;
			}
			$nofollow = (bool)$nofollow;
			$show = (bool)$show;
		}
		// ******************************
		// check for parameters, then settings, then defaults
		// ******************************
		if ($enabled) {
			$cssclass = jpctcl_setupvar($cssclass, JPCTCL_DEFAULT_STYLE, JPCTCL_DEFAULT_STYLE_NAME, $options);
			$nofollow = jpctcl_setupvar($nofollow, JPCTCL_DEFAULT_NOFOLLOW, JPCTCL_DEFAULT_NOFOLLOW_NAME, $options);
			
			$tagstyles = explode(",", JPCTCL_AVAILABLE_STYLES);
			if (!in_array($cssclass, $tagstyles)) {
				$cssclass = JPCTCL_DEFAULT_STYLE;
			}
			// enqueue CSS only on pages with shortcode
			jpctcl_button_styles();

			// http://www.wpbeginner.com/wp-themes/how-to-style-tags-in-wordpress/
			// http://codex.wordpress.org/Function_Reference/get_tags
			$ctcltags =  get_tags();
			$output = '<div class="ctcl_tags">';
			foreach ($ctcltags as $ctcltag) {
				$output .= '<span class="tagbox">';
				$output .= '<a ' . ($nofollow ? 'rel="nofollow" ' : '') . 'class="taglink" href="'. get_tag_link($ctcltag->term_id) .'">' . $ctcltag->name . '</a>';
				$output .= '<span class="tagcount' . (!empty($cssclass) ? ' tagcount-' . $cssclass : '') . '">'. $ctcltag->count .'</span>';
				$output .= '</span>' . "\n";
			}
			$output .= '</div>';
		} // end enabled check
		if ($show) {
			echo $output;
		} else {
			return $output;
		}
	} // end shortcode
	// show admin messages to plugin user
	add_action('admin_notices', 'jpctcl_showAdminMessages');
	function jpctcl_showAdminMessages() {
		// http://wptheming.com/2011/08/admin-notices-in-wordpress/
		global $pagenow;
		if (current_user_can(JPCTCL_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') { // we are on Settings page
				if (isset($_GET['page'])) {
					if ($_GET['page'] == jpctcl_get_slug()) { // we are on this plugin's settings page
						$options = jpctcl_getpluginoptions();
						if ($options != false) {
							$enabled = (bool)$options[JPCTCL_DEFAULT_ENABLED_NAME];
							if (!$enabled) {
								echo '<div id="message" class="error">' . JPCTCL_PLUGIN_NAME . ' ' . __('is currently disabled.', jpctcl_get_local()) . '</div>';
							}
							$cssclass = $options[JPCTCL_DEFAULT_STYLE_NAME];
							if (($cssclass === JPCTCL_DEFAULT_STYLE) || ($cssclass === false)) {
								echo '<div id="message" class="updated">' . __('Please confirm the default CSS style and click "Save".', jpctcl_get_local()) . '</div>';
							}
						}
					}
				}
			} // end page check
		} // end privilege check
	} // end admin msgs function
	// enqueue admin CSS if we are on the plugin options page
	add_action('admin_head', 'insert_jpctcl_admin_css');
	function insert_jpctcl_admin_css() {
		global $pagenow;
		if (current_user_can(JPCTCL_PERMISSIONS_LEVEL)) { // user has privilege
			if ($pagenow == 'options-general.php') {
				if (isset($_GET['page'])) {
					if ($_GET['page'] == jpctcl_get_slug()) { // we are on this plugin's settings page
						jpctcl_admin_styles();
					}
				}
			}
		}
	}
	// add helpful links to plugin page next to plugin name
	// http://bavotasan.com/2009/a-settings-link-for-your-wordpress-plugins/
	// http://wpengineer.com/1295/meta-links-for-wordpress-plugins/
	add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'jpctcl_plugin_settings_link');
	add_filter('plugin_row_meta', 'JPCTCL_meta_links', 10, 2);
	
	function jpctcl_plugin_settings_link($links) {
		return jpctcl_settingslink($links, jpctcl_get_slug(), jpctcl_get_local());
	}
	function jpctcl_meta_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$links = array_merge($links,
			array(
				sprintf(__('<a href="http://wordpress.org/support/plugin/%s">Support</a>', jpctcl_get_local()), jpctcl_get_slug()),
				sprintf(__('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', jpctcl_get_local()), jpctcl_get_slug()),
				sprintf(__('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a>', jpctcl_get_local()), jpctcl_get_slug())
			));
		}
		return $links;	
	}
	// enqueue/register the plugin CSS file
	function jpctcl_button_styles() {
		wp_enqueue_style('jpctcl_style');
	}
	function register_jpctcl_style() {
		wp_register_style('jpctcl_style', 
			plugins_url(jpctcl_get_path() . '/css/ctcl.css'), 
			array(), 
			JPCTCL_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/ctcl.css')),
			'all' );
	}
	// enqueue/register the admin CSS file
	function jpctcl_admin_styles() {
		wp_enqueue_style('jpctcl_admin_style');
	}
	function register_jpctcl_admin_style() {
		wp_register_style('jpctcl_admin_style',
			plugins_url(jpctcl_get_path() . '/css/admin.css'),
			array(),
			JPCTCL_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/css/admin.css')),
			'all');
	}
	// enqueue/register the admin JS file
	add_action('admin_enqueue_scripts', 'jpctcl_ed_buttons');
	function jpctcl_ed_buttons($hook) {
		if (($hook == 'post-new.php') || ($hook == 'post.php')) {
			wp_enqueue_script('jpctcl_add_editor_button');
		}
	}
	function register_jpctcl_admin_script() {
		wp_register_script('jpctcl_add_editor_button',
			plugins_url(jpctcl_get_path() . '/js/editor_button.js'), 
			array('quicktags'), 
			JPCTCL_VERSION . "_" . date('njYHis', filemtime(dirname(__FILE__) . '/js/editor_button.js')),
			true);
	}
	// when plugin is activated, create options array and populate with defaults
	register_activation_hook(__FILE__, 'jpctcl_activate');
	function jpctcl_activate() {
		$options = jpctcl_getpluginoptions();
		update_option(jpctcl_get_option(), $options);

		// delete option when plugin is uninstalled
		register_uninstall_hook(__FILE__, 'uninstall_jpctcl_plugin');
	}
	function uninstall_jpctcl_plugin() {
		delete_option(jpctcl_get_option());
	}
	// generic function that returns plugin options from DB
	// if option does not exist, returns plugin defaults
	function jpctcl_getpluginoptions() {
		return get_option(jpctcl_get_option(), 
			array(
				JPCTCL_DEFAULT_ENABLED_NAME => JPCTCL_DEFAULT_ENABLED,
				JPCTCL_DEFAULT_STYLE_NAME => JPCTCL_DEFAULT_STYLE, 
				JPCTCL_DEFAULT_NOFOLLOW_NAME => JPCTCL_DEFAULT_NOFOLLOW
			));
	}
	// function to return shortcode defaults
	function jpctcl_shortcode_defaults() {
		return array(
			JPCTCL_DEFAULT_STYLE_NAME => JPCTCL_DEFAULT_STYLE, 
			JPCTCL_DEFAULT_NOFOLLOW_NAME => JPCTCL_DEFAULT_NOFOLLOW, 
			JPCTCL_DEFAULT_SHOW_NAME => JPCTCL_DEFAULT_SHOW
			);
	}
	// function to return parameter status (required or not)
	function jpctcl_required_parameters() {
		return array(
			true,
			false,
			false
		);
	}
	
	// encapsulate these and call them throughout the plugin instead of hardcoding the constants everywhere
	function jpctcl_get_slug() { return JPCTCL_SLUG; }
	function jpctcl_get_local() { return JPCTCL_LOCAL; }
	function jpctcl_get_option() { return JPCTCL_OPTION; }
	function jpctcl_get_path() { return JPCTCL_PATH; }
	
	function jpctcl_settingslink($linklist, $slugname = '', $localname = '') {
		$settings_link = sprintf( __('<a href="options-general.php?page=%s">Settings</a>', $localname), $slugname);
		array_unshift($linklist, $settings_link);
		return $linklist;
	}
	function jpctcl_setupvar($var, $defaultvalue, $defaultvarname, $optionsarr) {
		if ($var == $defaultvalue) {
			$var = $optionsarr[$defaultvarname];
			if (!$var) {
				$var = $defaultvalue;
			}
		}
		return $var;
	}
	function jpctcl_getsupportinfo($slugname = '', $localname = '') {
		$output = __('Do you need help with this plugin? Check out the following resources:', $localname);
		$output .= '<ol>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/">Documentation</a>', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/faq/">FAQ</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/support/plugin/%s">Support Forum</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://www.jimmyscode.com/wordpress/%s">Plugin Homepage / Demo</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/extend/plugins/%s/developers/">Development</a><br />', $localname), $slugname) . '</li>';
		$output .= '<li>' . sprintf( __('<a href="http://wordpress.org/plugins/%s/changelog/">Changelog</a><br />', $localname), $slugname) . '</li>';
		$output .= '</ol>';
		
		$output .= sprintf( __('If you like this plugin, please <a href="http://wordpress.org/support/view/plugin-reviews/%s/">rate it on WordPress.org</a>', $localname), $slugname);
		$output .= sprintf( __(' and click the <a href="http://wordpress.org/plugins/%s/#compatibility">Works</a> button. ', $localname), $slugname);
		$output .= '<br /><br /><br />';
		$output .= __('Your donations encourage further development and support. ', $localname);
		$output .= '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW"><img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" title="Support this plugin" width="92" height="26" /></a>';
		$output .= '<br /><br />';
		return $output;
	}
	
	function jpctcl_parameters_table($localname = '', $sc_defaults, $reqparms) {
	  $output = '<table class="widefat">';
		$output .= '<thead><tr>';
		$output .= '<th title="' . __('The name of the parameter', $localname) . '"><strong>' . __('Parameter Name', $localname) . '</strong></th>';
		$output .= '<th title="' . __('Is this parameter required?', $localname) . '"><strong>' . __('Is Required?', $localname) . '</strong></th>';
		$output .= '<th title="' . __('What data type this parameter accepts', $localname) . '"><strong>' . __('Data Type', $localname) . '</strong></th>';
		$output .= '<th title="' . __('What, if any, is the default if no value is specified', $localname) . '"><strong>' . __('Default Value', $localname) . '</strong></th>';
		$output .= '</tr></thead>';
		$output .= '<tbody>';
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		$required = $reqparms;
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			$output .= '<tr>';
			$output .= '<td><strong>' . $plugin_defaults_keys[$i] . '</strong></td>';
			$output .= '<td>';
			
			if ($required[$i] === true) {
				$output .= '<strong>';
				$output .= __('Yes', $localname);
				$output .= '</strong>';
			} else {
				$output .= __('No', $localname);
			}
			
			$output .= '</td>';
			$output .= '<td>' . gettype($plugin_defaults_values[$i]) . '</td>';
			$output .= '<td>';
			
			if ($plugin_defaults_values[$i] === true) {
				$output .= '<strong>';
				$output .= __('true', $localname);
				$output .= '</strong>';
			} elseif ($plugin_defaults_values[$i] === false) {
				$output .= __('false', $localname);
			} elseif ($plugin_defaults_values[$i] === '') {
				$output .= '<em>';
				$output .= __('this value is blank by default', $localname);
				$output .= '</em>';
			} elseif (is_numeric($plugin_defaults_values[$i])) {
				$output .= $plugin_defaults_values[$i];
			} else { 
				$output .= '"' . $plugin_defaults_values[$i] . '"';
			} 
			$output .= '</td>';
			$output .= '</tr>';
		}
		$output .= '</tbody>';
		$output .= '</table>';
		
		return $output;
	}
	function jpctcl_get_example_shortcode($shortcodename = '', $sc_defaults, $localname = '') {
		$output = '[' . $shortcodename . ' ';
		
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			if ($plugin_defaults_keys[$i] !== 'show') {
				if (gettype($plugin_defaults_values[$i]) === 'string') {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=\'' . $plugin_defaults_values[$i] . '\'';
				} elseif (gettype($plugin_defaults_values[$i]) === 'boolean') {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=' . ($plugin_defaults_values[$i] == false ? 'false' : 'true');
				} else {
					$output .= '<strong>' . $plugin_defaults_keys[$i] . '</strong>=' . $plugin_defaults_values[$i];
				}
				if ($i < count($plugin_defaults_keys) - 2) {
					$output .= ' ';
				}
			}
		}
		$output .= ']';
		
		return $output;
	}
	function jpctcl_get_example_php_code($shortcodename = '', $internalfunctionname = '', $sc_defaults) {
		$plugin_defaults_keys = array_keys($sc_defaults);
		$plugin_defaults_values = array_values($sc_defaults);
		
		$output = '<pre style="background:#FFF">';
		$output .= 'if (shortcode_exists(\'' . $shortcodename . '\')) {<br />';
		$output .= '  $atts = array(<br />';
		for($i = 0; $i < count($plugin_defaults_keys); $i++) {
			$output .= '    \'' . $plugin_defaults_keys[$i] . '\' => ';
			if (gettype($plugin_defaults_values[$i]) === 'string') {
				$output .= '\'' . $plugin_defaults_values[$i] . '\'';
			} elseif (gettype($plugin_defaults_values[$i]) === 'boolean') {
				$output .= ($plugin_defaults_values[$i] == false ? 'false' : 'true');
			} else {
				$output .= $plugin_defaults_values[$i];
			}
			if ($i < count($plugin_defaults_keys) - 1) {
				$output .= ', <br />';
			}
		}
		$output .= '<br />  );<br />';
		$output .= '   echo ' . $internalfunctionname . '($atts);';
		$output .= '<br />}';
		$output .= '</pre>';
		return $output;	
	}
	function jpctcl_checkifset($optionname, $optiondefault, $optionsarr) {
		return (isset($optionsarr[$optionname]) ? $optionsarr[$optionname] : $optiondefault);
	}
	function jpctcl_getimagefilename($fname = '') {
		return plugins_url(jpctcl_get_path() . '/images/' . $fname);
	}
?>