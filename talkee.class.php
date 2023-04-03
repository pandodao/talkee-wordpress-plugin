<?php

namespace Pando\Talkee;

class Talkee {
	function __construct() {
		$this->frontendHooks();
	}
	// add action for hook default comment form
	function frontendHooks() {
		add_action( 'comment_form_before', [$this,'_commentHeader'] );
		add_action( 'comment_form_after', [$this,'_commentFooter']);
		add_action( 'wp_body_open',[$this,'_headCSS'] , 1 );
	}
	/**
	 * check should show talkee code in current page
	 *
	 * Two case need return true
	 * 1. enable Post & current page is Post(by is_signle())
	 * 2. enable Page & current page is Page(by is_page())
	 **/
	function _should_show_talkee(){
			$talkee_options = get_option( 'talkee_options' );
			if($talkee_options['post_enabled'] && is_single()){
 				return true;
			}
			if($talkee_options['page_enabled'] && is_page()){
 				return true;
			}
			return false;
	}
	/**
	 * Conver PHP Lang Code to Talkee Lang Code
	 **/
	function _converLangCode($lang){
		switch ($lang) {
			case 'zh-CN':
					return 'zh';
				break;
			case 'zh-TW':
				return 'zh';
				break;
			case 'ja-JP':
				return 'ja';
				break;
			case 'en-US':
				return 'en';
				break;
			default:
				return 'en';
				break;
		}
	}
	/**
	 * hide default form while current page need show talkee
	 **/
	function _commentHeader(){
		  if($this->_should_show_talkee()){
		  	echo '<div style="display:none;">';
		  }
	}
	/**
	 * get default options and build js for page.
	 **/
	function _commentFooter(){

		// get talkee options and remove unused props
		$talkee_options = get_option( 'talkee_options' );
		unset($talkee_options['css_overide']);
		unset($talkee_options['post_enabled']);
		unset($talkee_options['page_enabled']);

		// conver WordPress Lang Code to Talkee Lang Code
		$talkee_options["language"] = $this->_converLangCode($talkee_options["language"]);

		// cover PHP Array to JSON & then we can insert it into heredoc
		$json = json_encode($talkee_options);

		$ad_code = <<<EOF
 	<div id="comments"></div>
 	<script src="https://cdn.jsdelivr.net/npm/@foxone/talkee-install-js@latest/dist/ti.min.js"></script>
 <script>
   var config = $json;
   var res ={
     siteId: parseInt(config.site_id),
     chainId: parseInt(config.chain_id),
     authMethods: config.auth_methods,
     showLink: config.show_ar_hash == 'true' ? true :false,
     container: config.container_selector?config.container_selector:"#comments",
     locale: config.language? config.language:'en',
   };
   window.tijs(res);
 </script>
EOF;
		if($this->_should_show_talkee()){
			echo '</div>';
			echo $ad_code;
		}
	}
	public function _headCSS(){

		if($this->_should_show_talkee()){
			$talkee_options = get_option( 'talkee_options' );
			printf("<style>%s</style>",$talkee_options['css_overide']);
		}
	}
}