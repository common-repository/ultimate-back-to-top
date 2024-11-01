<?php
/**
  Plugin Name: Ultimate Back To Top Button
  Plugin URI: http://www.nyamsprod.com/blog/2012/nyams-top-link-revisited/
  Description: Adds a CSS3 Back To Top Button to your Desktop Wordpress Blog
  Version: 1.0 
  Author: nyamsprod
  Author URI: http://www.nyamsprod.com/
 */
if ( ! class_exists( 'Nyams_Ultimate_Button' ) ) {

	class Nyams_Ultimate_Button {

		private $settings = array(
				'text'				=> '&uarr;',
				'title'				=> 'Back to top',
				'enable'			=> true,
				'borderColor'		=> '#0089d3',
				'backgroundColor'	=> '#ffffff',
				'fontColor'			=> '#0089d3',
				'position'			=> 'right',
				'offset'			=> '7',
				),
				$regex = array(
						'color' => '^(#[A-Fa-f0-9]{6}|#[A-Fa-f0-9]{3}|transparent)$',
						);
		public static $option_name = 'nyams_ultimate_button';

		//! add the CSS to the top and the JS to the footer 
		public function add_ressources() {
			$options = get_option(self::$option_name);
			if ( ! $options['enable'] ) {
				return;	
			}
			wp_enqueue_script(
					'nyams-ultimate-button-js',
					plugin_dir_url( __FILE__ ) . self::$option_name . '.js',
					array('jquery'),
					'1.0',
					true
					);

			wp_enqueue_style(
					'nyams-ultimate-button-style',
					plugin_dir_url( __FILE__ ) . self::$option_name . '.css',
					false,
					'1.0',
					'all'
					);

			// Add some parameters for the JS.
			wp_localize_script(
					'nyams-ultimate-button-js',
					self::$option_name,
					array(
						'text'	=> $options['text'],
						'title'	=> $options['title'],
						)
					);
		}

		public function add_css() {
			$options = get_option(self::$option_name);
			if ( ! $options['enable'] ) {
				return;	
			}

			echo PHP_EOL, '<style> .top-link { ', $options['position'], 
				': ', $options['offset'], 'px; } .top-link a { border-color: ', 
				$options['borderColor'] , 
				'; color: ', 
				$options['fontColor'], 
				'; background-color: ', 
				$options['backgroundColor'], 
				'; }</style>', 
				PHP_EOL;
		}


		public function add_admin_ressources() {
			wp_enqueue_style('farbtastic');
			wp_enqueue_style(
					'nyams-ultimate-button-admin-style',
					plugin_dir_url( __FILE__ ) . 'admin.css',
					false,
					'1.0',
					'all'
					);
			wp_enqueue_style(
					'nyams-ultimate-button-style',
					plugin_dir_url( __FILE__ ) . self::$option_name . '.css',
					false,
					'1.0',
					'all'
					);

			wp_enqueue_script('farbtastic');
			wp_enqueue_script(
					'nyams-ultimate-button-admin-js',
					plugin_dir_url( __FILE__ ) . 'admin.js',
					array('jquery'),
					'1.0',
					true
					);
		}

		//!add HTML in the template footer
		public function add_html() {
			$options = get_option(self::$option_name);
			if ($options['enable']) {
				echo '<div id="top-link" class="top-link top-link-hide"><a href="#top" title="', $options['title'], '">', $options['text'],'</a></div>', PHP_EOL;
			}
		}

		//! load plugin l10n
		public function load_l10n() {
			load_plugin_textdomain('nyamsbutton', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
		}

		//! register plugin  option page link in the plugin page
		public function add_plugin_page_link($links, $file) {
			if ( $file == plugin_basename( __FILE__ ) ) {
				array_unshift($links , '<a href="'. get_admin_url() .'options-general.php?page='.self::$option_name.'">'. __('Settings') .'</a>');
			}
			return $links;
		}

		//! register plugin link in the admin menu
		public function add_admin_menu_link() {
			add_options_page('Ultimate Back To Top Page', 'Ultimate Back To Top', 'manage_options', self::$option_name, array($this, 'option_page_setup'));
		}

		//! create the actual option page
		public function option_page_setup () {
			$options = get_option(self::$option_name);
			?>
				<div class="wrap">
				<?php screen_icon(); ?>
				<h2><?php _e('Ultimate Back To Top Configuration Page', 'nyamsbutton'); ?></h2>
				<form action="options.php" method="post">
				<?php settings_fields('nyams_ultimate_button_options'); ?>
				<?php do_settings_sections('nyams_ultimate_button_content'); ?>
				<?php do_settings_sections('nyams_ultimate_button_layout'); ?>
				<div id="nub_preview">
					<h3 style="margin:.3em auto; text-align:center;"><?php _e('Preview your Ultimate Back To Top Button in this area', 'nyamsbutton'); ?></h3>
					<div id="top-link" class="top-link">
						<a href="#top" title="<?php echo $options['title']; ?>"><?php echo $options['text']; ?></a>
					</div>
				</div>
				<div style="margin-bottom:1em">
					<input class="button-primary" type="submit" name="submit" value="<?php esc_attr_e('Save Changes'); ?>">
					<button class="button-secondary" style="padding:4px" type="submit" name="<?php echo self::$option_name; ?>[reset]" value="on"><?php _e('Reset to Defaults', 'nyamsbutton'); ?></button>
				</div>
				</form>
				<div class="nyams_infobulles">
				<h3><span>About the Author</span></h3>
				<p><a href="http://www.nyamsprod.com/blog/2012/dynamic-top-link-revisited/" target="_blank">Ultimate Back To Top Button</a> was developped by <a href="https://www.twitter.com/nyamsprod" target="_blank" title="follow me on twitter">@nyamsprod</a>, who sometimes <a href="https://www.nyamsprod.com" target="_blank" title="..or just subscribe to my blog">blogs about random stuffs</a>.</p>
				</div>
				</div><!-- /wrap -->
				<?php
		}

		public function show_content_parameters() {
			echo '<p>', _e('To change the texts used in the plugin just replace the different strings below.','nyamsbutton'), '<p>', PHP_EOL;
		}

		public function show_css_parameters() {
			echo '<p>', _e('To adapt your button to your layout, change the parameters below.', 'nyamsbutton'), '<p>', PHP_EOL;
		}

		public function show_text_input() {
			$options = get_option( self::$option_name );
			echo '<input type="text" size="20" maxlength="20" id="nub_option_text" name="', self::$option_name, '[text]" value="', $options['text'], '" required>', PHP_EOL;
		}

		public function show_title_input() {
			$options = get_option( self::$option_name );
			echo '<input type="text" size="20" maxlength="20" id="nub_option_title" name="', self::$option_name, '[title]" value="', $options['title'], '" required>', PHP_EOL;
		}

		public function show_border_color_input() {
			$options = get_option( self::$option_name );
			echo '<input type="text" size="7" maxlength="7" id="nub_option_border_color" class="color" name="', self::$option_name, '[borderColor]" value="', ($options['borderColor']) ? $options['borderColor'] : $this->settings['borderColor'], '" required pattern="', $this->regex['color']  ,'">', PHP_EOL;
		}

		public function show_background_color_input() {
			$options = get_option( self::$option_name );
			echo '<input type="text" size="7" maxlength="7" id="nub_option_background_color" class="color" name="', self::$option_name, '[backgroundColor]" value="', ($options['backgroundColor']) ? $options['backgroundColor'] : $this->settings['backgroundColor'], '" required pattern="', $this->regex['color'] ,'">', PHP_EOL;
		}

		public function show_font_color_input() {
			$options = get_option( self::$option_name );
			echo '<input type="text" size="7" maxlength="7" id="nub_option_font_color" class="color" name="', self::$option_name, '[fontColor]" value="', ($options['fontColor']) ? $options['fontColor'] : $this->settings['fontColor'], '" required pattern="', $this->regex['color'] ,'">', PHP_EOL;
		}

		public function show_offset_input() {
			$options = get_option( self::$option_name );
			echo '<input type="number" style="width:60px" size="3" maxlength="3" id="nub_option_offset" name="', self::$option_name, '[offset]" value="', ($options['offset']) ? $options['offset'] : $this->settings['offset'], '" min="0" required> px', PHP_EOL;
		}

		public function show_enable_input() {
			$options = get_option( self::$option_name );
			?>
				<label><input type="radio" id="nub_option_enable" name="<?php echo self::$option_name; ?>[enable]" value="on"<?php if ($options['enable']): ?> checked<?php endif; ?>> <?php _e('Yes','nyamsbutton'); ?></label>
				<label><input type="radio" name="<?php echo self::$option_name; ?>[enable]" value="off"<?php if (!$options['enable']): ?> checked<?php endif; ?>> <?php _e('No','nyamsbutton'); ?></label>
				<?php		
		}

		public function show_position_input() {
			$options = get_option( self::$option_name );
			?>
				<label><input type="radio" name="<?php echo self::$option_name; ?>[position]" value="left"<?php if ('left' == $options['position']): ?> checked<?php endif; ?>> <?php _e('bottom left', 'nyamsbutton'); ?></label><br>
				<label><input type="radio" id="nub_option_position" name="<?php echo self::$option_name; ?>[position]" value="right"<?php if ('left' != $options['position']): ?> checked<?php endif; ?>> <?php _e('bottom right', 'nyamsbutton'); ?></label>
				<?php		
		}

		//! validate option modifications
		public function validate_options( $input ) {
			$options = get_option( self::$option_name );
			$new = filter_var_array( $input, 
					array(
						'text'				=> array( 'filter' => FILTER_CALLBACK, 'options' => wp_filter_nohtml_kses ),
						'title'				=> array( 'filter' => FILTER_CALLBACK, 'options' => wp_filter_nohtml_kses ),
						'enable'			=> array( 'filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE ),
						'reset'				=> array( 'filter' => FILTER_VALIDATE_BOOLEAN, 'flags' => FILTER_NULL_ON_FAILURE ),
						'position'			=> array( 'filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' =>'/^(left|right)$/i') ),
						'offset'			=> array( 'filter' => FILTER_VALIDATE_INT, 'options' => array('min_range' => 0) ),
						'borderColor'		=> array( 'filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/'.$this->regex['color'].'/')),
						'fontColor'			=> array( 'filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/'.$this->regex['color'].'/')),
						'backgroundColor'	=> array( 'filter' => FILTER_VALIDATE_REGEXP, 'options' => array('regexp' => '/'.$this->regex['color'].'/')),
						)
					);
			if ($new['reset']) {
				$new  = $this->settings;
			}  else {
				$new['position'] = strtolower($new['position']);
				foreach(array('text','title','borderColor', 'backgroundColor', 'fontColor', 'position', 'offset') as $f) {
					if ( empty( $new[$f] ) ) {
						$new[$f] = $options[$f];	
					}
				}
				if ( is_null( $new['enable'] ) ) {
					$new['enable'] = array_key_exists( 'enable', $options ) ? $options['enable'] : true;
				}
			}
			return $new;
		}

		//! check wordpress version
		private function check_wordpress_version() {
			global $wp_version;
			$plugin = plugin_basename( __FILE__ );
			$plugin_data = get_plugin_data( __FILE__ , false);
			if (version_compare( $wp_version, '3.3', '<' ) ) {
				if (is_plugin_active( $plugin ) ) {
					deactivate_plugins( $plugin );
					$msg = array();
					$msg[] = '<p><strong>' . $plugin_data['Name'] . '</strong>' . _e('requires WordPress 3.3 or higher, and has been deactivated!', 'nyamsbutton') .'</p>';
					$msg[] = '<p>' . _e('Please upgrade WordPress and try again.').'</p><p><a href="' .admin_url() . '">'. _e('Return to the WordPress Admin area', 'nyamsbutton') .'</a>.</p>';
					wp_die( implode( PHP_EOL, $msg ). PHP_EOL );
				}
			}
		}

		//! register Options setting for the admin page
		private function init_options() {
			register_setting( 'nyams_ultimate_button_options', self::$option_name, array( $this, 'validate_options' ) );		

			add_settings_section( 
					'nub_primary',
					__( 'Ultimate Back To Top Content Parameters', 'nyamsbutton' ), 
					array( $this, 'show_content_parameters' ), 
					'nyams_ultimate_button_content'
					);

			add_settings_field(
					'nub_option_enable',
					'<h4 style="margin:0;">'.__('Show the link:', 'nyamsbutton').'</h4>', 
					array( $this, 'show_enable_input' ),
					'nyams_ultimate_button_content',
					'nub_primary',
					array( 'label_for' => 'nub_option_enable' )
					);  

			add_settings_field(
					'nub_option_text', 
					'<h4 style="margin:0;">'.__( "Link's text:", 'nyamsbutton' ).'</h4>', 
					array( $this, 'show_text_input' ),
					'nyams_ultimate_button_content',
					'nub_primary', 
					array( 'label_for' => 'nub_option_text' )
					);  

			add_settings_field(
					'nub_option_title', 
					'<h4 style="margin:0;">'.__( "Link's title attribute:", 'nyamsbutton' ).'</h4>', 
					array( $this, 'show_title_input' ), 
					'nyams_ultimate_button_content', 
					'nub_primary', 
					array( 'label_for' => 'nub_option_title' )
					);  

			add_settings_section(
					'nub_secondary',
					__( 'Ultimate Back To Top Layout Parameters', 'nyamsbutton' ), 
					array( $this, 'show_css_parameters'),
					'nyams_ultimate_button_layout'
					);

			add_settings_field(
					'nub_option_position',
					'<h4 style="margin:0;">'.__('Button position', 'nyamsbutton').':</h4>', 
					array( $this, 'show_position_input' ),
					'nyams_ultimate_button_layout',
					'nub_secondary',
					array()
					);  

			add_settings_field(
					'nub_option_offset',
					'<h4 style="margin:0;">'.__('Button offset', 'nyamsbutton').':</h4>', 
					array( $this, 'show_offset_input' ),
					'nyams_ultimate_button_layout',
					'nub_secondary',
					array('label_for' => 'nub_option_offset')
					);  

			add_settings_field(
					'nub_option_border_color',
					'<h4 style="margin:0">'.__('Button border color', 'nyamsbutton').':</h4>',
					array( $this, 'show_border_color_input' ),
					'nyams_ultimate_button_layout',
					'nub_secondary',
					array( 'label_for' => 'nub_option_border_color' )
					);

			add_settings_field(
					'nub_option_background_color',
					'<h4 style="margin:0">'.__('Button background color', 'nyamsbutton').':</h4>',
					array( $this, 'show_background_color_input' ),
					'nyams_ultimate_button_layout',
					'nub_secondary',
					array( 'label_for' => 'nub_option_background_color' )
					);

			add_settings_field(
					'nub_option_font_color',
					'<h4 style="margin:0">'.__('Button text color', 'nyamsbutton').':</h4>',
					array( $this, 'show_font_color_input' ),
					'nyams_ultimate_button_layout',
					'nub_secondary',
					array( 'label_for' => 'nub_option_font_color' )
					);
		}

		//! define default settings
		public function init_plugin() {
			$options = get_option(self::$option_name);
			if(!is_array($options)) {
				update_option(self::$option_name, $this->settings);
			}
		}

		//! remove plugin settings after deletion
		public function destruct_plugin() {
			delete_option(self::$option_name);
		}
		//! Init everything
		public function init() {
			$this->check_wordpress_version();
			$this->init_options();
		}

		public function __construct() {
			register_activation_hook (__FILE__, array($this, 'init_plugin'));
			register_uninstall_hook (__FILE__, array($this, 'destruct_plugin'));
			add_action('template_redirect', array($this, 'add_ressources'));
			add_action('wp_head', array($this, 'add_css'));
			add_action('wp_footer', array($this, 'add_html'));
			add_action('init', array($this, 'load_l10n'));
			add_action('admin_init', array($this, 'init'));
			add_action('admin_head', array($this, 'add_admin_ressources'));
			add_action('admin_menu', array($this, 'add_admin_menu_link'));
			add_filter('plugin_action_links', array($this, 'add_plugin_page_link'), 10, 2);
		}
	}
}
$nyams_ultimate_button = new Nyams_Ultimate_Button;
