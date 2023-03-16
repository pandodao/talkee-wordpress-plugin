<?php

namespace Pando\Talkee;

/*
 * Retrieve this value with:
 * $talkee_options = get_option( 'talkee_options' ); // Array of All Options
 * $post_enabled = $talkee_options['post_enabled']; // Post
*/

class Admin {
	private $talkee_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'talkee_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'talkee_page_init' ) );
	}

	/**
	 * Init a new Settings Page at Menu Bottom
	 */
	public function talkee_add_plugin_page() {
		add_menu_page(
			__('Talkee','talkee'), // page_title
			__('Talkee','talkee'), // menu_title
			'manage_options', // capability
			'talkee', // menu_slug
			array( $this, 'talkee_create_admin_page' ), // function
			'dashicons-admin-comments', // icon_url
			99 // position
		);
	}

	/**
	 * Build the simple Page
	 */
	public function talkee_create_admin_page() {
		$this->talkee_options = get_option( 'talkee_options' ); ?>

		<div class="wrap">
			<h2><?php _e('Talkee','talkee');?></h2>
			<?php	echo sprintf( __( 'If you need help, you can visit the <a href="%s" target="_blank">documentation</a> for more assistance.', 'talkee' ), "https://developers.pando.im/guide/talkee.html" ,'talkee');?>
			
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'talkee_option_group' );
					do_settings_sections( 'talkee-admin' );
					submit_button();
				?>
			</form>
		</div>
	<?php }

	/**
	 * Init & Regiser Settings
     * then add Settings Setion & Settings Field
	 */
	public function talkee_page_init() {
		register_setting(
			'talkee_option_group', // option_group
			'talkee_options', // option_name
			array( $this, 'talkee_sanitize' ) // sanitize_callback
		);

		add_settings_section(
			'post_type_section', // id
			__('Post Type Settings','talkee'), // title
			array( $this, 'post_type_section_info' ), // callback
			'talkee-admin' // page
		);

		add_settings_field(
			'post_enabled', // id
			__('Post'), // title
			array( $this, 'post_enabled_callback' ), // callback
			'talkee-admin', // page
			'post_type_section' // section
		);

		add_settings_field(
			'page_enabled', // id
			__('Page'), // title
			array( $this, 'page_enabled_callback' ), // callback
			'talkee-admin', // page
			'post_type_section' // section
		);

		add_settings_section(
			'site_section', // id
			__('Site Settings','talkee'), // title
			array( $this, 'site_settings_section_info' ), // callback
			'talkee-admin' // page
		);

		add_settings_field(
			'site_id', // id
			__('Site ID','talkee'), // title
			array( $this, 'site_id_callback' ), // callback
			'talkee-admin', // page
			'site_section' // section
		);

		add_settings_field(
			'chain_id', // id
			__('Chain ID','talkee'), // title
			array( $this, 'chain_id_callback' ), // callback
			'talkee-admin', // page
			'site_section' // section
		);

		add_settings_field(
			'show_ar_hash', // id
			__('Show AR Hash','talkee'), // title
			array( $this, 'show_ar_hash_callback' ), // callback
			'talkee-admin', // page
			'site_section' // section
		);

		add_settings_field(
			'auth_methods', // id
			__('Auth Methods','talkee'), // title
			array( $this, 'auth_methods_callback' ), // callback
			'talkee-admin', // page
			'site_section' // section
		);

		add_settings_field(
			'language', // id
			__('Language'), // title
			array( $this, 'language_callback' ), // callback
			'talkee-admin', // page
			'site_section' // section
		);

		add_settings_field(
			'container_selector', // id
			__('Container Selector','talkee'), // title
			array( $this, 'container_selector_callback' ), // callback
			'talkee-admin', // page
			'site_section' // section
		);

		add_settings_field(
			'css_overide', // id
			__('CSS Overide','talkee'), // title
			array( $this, 'css_overide_callback' ), // callback
			'talkee-admin', // page
			'site_section' // section
		);
	}

	/**
	 * Clean user input and return it
	 */
	public function talkee_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['post_enabled'] ) ) {
			$sanitary_values['post_enabled'] = $input['post_enabled'];
		}

		if ( isset( $input['page_enabled'] ) ) {
			$sanitary_values['page_enabled'] = $input['page_enabled'];
		}

		if ( isset( $input['site_id'] ) ) {
			$sanitary_values['site_id'] = sanitize_text_field( $input['site_id'] );
		}

		if ( isset( $input['chain_id'] ) ) {
			$sanitary_values['chain_id'] = sanitize_text_field( $input['chain_id'] );
		}

		if ( isset( $input['show_ar_hash'] ) ) {
			$sanitary_values['show_ar_hash'] = $input['show_ar_hash'];
		}

		if ( isset( $input['auth_methods'] ) ) {
			$sanitary_values['auth_methods'] = $input['auth_methods'];
		}

		if ( isset( $input['language'] ) ) {
			$sanitary_values['language'] = $input['language'];
		}

		if ( isset( $input['container_selector'] ) ) {
			$sanitary_values['container_selector'] = sanitize_text_field( $input['container_selector'] );
		}

		if ( isset( $input['css_overide'] ) ) {
			$sanitary_values['css_overide'] = esc_textarea( $input['css_overide'] );
		}

		return $sanitary_values;
	}


    // Code below use for build a better form and better description.

	public function post_type_section_info() {
			_e('Post Type Setting can be used to enable the Talkee Comments according to different article types.','talkee');
	}

	public function site_settings_section_info(){
		_e('Site settings allow you to configure the behavior of Talkee on your site, including the specific pages where comments are displayed and whether to show AR hash.','talkee');
	}

	public function post_enabled_callback() {
		printf(
			'<input type="checkbox" name="talkee_options[post_enabled]" id="post_enabled" value="post_enabled" %s> <label for="post_enabled">%s</label>',
			( isset( $this->talkee_options['post_enabled'] ) && $this->talkee_options['post_enabled'] === 'post_enabled' ) ? 'checked' : '',__('Enable Talkee comments at the bottom of the post','talkee')
		);
	}

	public function page_enabled_callback() {
		printf(
			'<input type="checkbox" name="talkee_options[page_enabled]" id="page_enabled" value="page_enabled" %s> <label for="page_enabled">%s</label>',
			( isset( $this->talkee_options['page_enabled'] ) && $this->talkee_options['page_enabled'] === 'page_enabled' ) ? 'checked' : '',__('Enable Talkee comments at the bottom of the page','talkee')
		);
	}

	public function site_id_callback() {
		printf(
			'<input class="regular-text" type="number" required name="talkee_options[site_id]" id="site_id" value="%s">',
			isset( $this->talkee_options['site_id'] ) ? esc_attr( $this->talkee_options['site_id']) : ''
		);
		echo(sprintf(__('<p class="description">The site ID for <a href="%s">Talkee</a> can be found on the site management page that you registered with <a href="%s">Talkee</a>. If you don’t have a site ID, you can visit the Talkee console to register a new site.</p>','talkee'),'https://developers.pando.im/console/talkee/','https://developers.pando.im/console/talkee/'));

	}

	public function chain_id_callback() {
		printf(
			'<input class="regular-text" type="number" name="talkee_options[chain_id]" id="chain_id" value="%s">',
			isset( $this->talkee_options['chain_id'] ) ? esc_attr( $this->talkee_options['chain_id']) : 1
		);
		echo(sprintf(__('<p class="description">The Chain ID for <a href="%s">Talkee</a>. Default is  1</p>','talkee'),'https://developers.pando.im/console/talkee/'));
	}

	public function show_ar_hash_callback() {
		printf(
			'<input type="checkbox" name="talkee_options[show_ar_hash]" id="show_ar_hash" value="true" %s> <label for="show_ar_hash">%s</label>',
			( isset( $this->talkee_options['show_ar_hash'] ) && $this->talkee_options['show_ar_hash'] === 'true' ) ? 'checked' : '',__('Whether to show the on-chain tx link to the comment page','talkee')
		);
		_e('<p class="description">Show the link to the Arweave transaction page if possible</p>','talkee');
	}

	public function auth_methods_callback() {
		?> <select name="talkee_options[auth_methods][]" id="auth_methods" multiple>
			<?php $selected = (isset( $this->talkee_options['auth_methods'] ) && in_array('metamask',$this->talkee_options['auth_methods'])) ? 'selected' : '' ; ?>
			<option value="metamask" <?php echo $selected; ?>>MetaMask</option>
			<?php $selected = (isset( $this->talkee_options['auth_methods'] ) && in_array('walletconnect',$this->talkee_options['auth_methods'])) ? 'selected' : '' ; ?>
			<option value="walletconnect" <?php echo $selected; ?>>Wallet Connect</option>
			<?php $selected = (isset( $this->talkee_options['auth_methods'] ) && in_array('mixin',$this->talkee_options['auth_methods'])) ? 'selected' : '' ; ?>
			<option value="mixin" <?php echo $selected; ?>>Mixin Messenger</option>
			<?php $selected = (isset( $this->talkee_options['auth_methods'] ) && in_array('fennec',$this->talkee_options['auth_methods'])) ? 'selected' : '' ; ?>
			<option value="fennec" <?php echo $selected; ?>>Fennec</option>
			<?php $selected = (isset( $this->talkee_options['auth_methods'] ) && in_array('onekey',$this->talkee_options['auth_methods'])) ? 'selected' : '' ; ?>
			<option value="onekey" <?php echo $selected; ?>>One Key</option>
			
		</select> <?php
		_e('<p class="description">Please select the payment wallet(s) you wish to use (multiple choices available)</p>','talkee');
	}

	public function language_callback() {
		?> <select name="talkee_options[language]" id="language">
			<?php $selected = (isset( $this->talkee_options['language'] ) && $this->talkee_options['language'] === 'zh-CN') ? 'selected' : '' ; ?>
			<option value="zh-CN" <?php echo $selected; ?>><?php _e('Simplified Chinese','talkee');?></option>
			<?php $selected = (isset( $this->talkee_options['language'] ) && $this->talkee_options['language'] === 'zh-TW') ? 'selected' : '' ; ?>
			<option value="zh-TW" <?php echo $selected; ?>><?php _e('Traditional Chinese','talkee');?></option>
			<?php $selected = (isset( $this->talkee_options['language'] ) && $this->talkee_options['language'] === 'ja-JP') ? 'selected' : '' ; ?>
			<option value="ja-JP" <?php echo $selected; ?>><?php _e('Janpanese','talkee');?></option>
			<?php $selected = (isset( $this->talkee_options['language'] ) && $this->talkee_options['language'] === 'en-US') ? 'selected' : '' ; ?>
			<option value="en-US" <?php echo $selected; ?>><?php _e('English','talkee');?></option>
		</select> <?php
		_e('<p class="description">Please select the default language.</p>','talkee');
	}

	public function container_selector_callback() {
		printf(
			'<input class="regular-text" type="text" name="talkee_options[container_selector]" id="container_selector" value="%s">',
			isset( $this->talkee_options['container_selector'] ) ? esc_attr( $this->talkee_options['container_selector']) : '#comments'
		);
		_e('<p class="description">Comment Container Selector.</p>','talkee');
	}

	public function css_overide_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="talkee_options[css_overide]" id="css_overide">%s</textarea>',
			isset( $this->talkee_options['css_overide'] ) ? esc_attr( $this->talkee_options['css_overide']) : ''
		);
		_e('<p class="description">CSS Override. you no need have a <code>style</code> tag outter.</p>','talkee');
	}

}

