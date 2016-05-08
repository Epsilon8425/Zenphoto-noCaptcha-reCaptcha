<?php
/*
 * ------------------------------------------------------------------------------------------------
 * Google noCaptcha reCaptcha
 * ------------------------------------------------------------------------------------------------
 *
 * This plugin adds noCaptcha reCaptcha functionality to Zenphoto forms.
 *
 * @author Ben Feather (Epsilon)
 * @version 1.0.2
 * @package plugins
 * @subpackage spam
 *
 */
 
$plugin_is_filter = 5 | CLASS_PLUGIN;
$plugin_description = gettext("Add Google noCaptcha reCaptcha (checkbox reCaptcha) to Zenphoto forms.");
$plugin_author = gettext("Ben Feather (Epsilon)");
$plugin_version = '1.0.2';
$plugin_URL = 'https://github.com/Epsilon8425/Zenphoto-noCaptcha-reCaptcha';
$plugin_disable = ($_zp_captcha->name && $_zp_captcha->name != 'nocaptcha_recaptcha') ? sprintf(gettext('Only one Captcha handler plugin may be enabled. <a href="#%1$s"><code>%1$s</code></a> is already enabled.'), $_zp_captcha->name) : '';
$option_interface = 'noCaptcha_reCaptcha';

class nocaptcha_recaptcha extends _zp_captcha {
	
	var $name = 'nocaptcha_recaptcha';
	
	// Sets default values for dropdown options
	function __construct() {
		setOptionDefault('ncrc_theme', 'light');
		setOptionDefault('ncrc_type', 'image');
		setOptionDefault('ncrc_size', 'normal');
	}
	
	// Options for the plugin
	function getOptionsSupported() {
		
		$options = array(
			// Input for reCaptcha public key
			gettext('Public (Site) Key:') => array(
				'key'			=> 'ncrc_public_key',
				'type'			=> OPTION_TYPE_TEXTBOX,
				'order'			=> 1,
				'desc'			=> gettext('Enter your reCaptcha public key here. Visit https://www.google.com/recaptcha/intro/index.html to obtain your keys.')
			),
			// Input for reCaptcha private key
			gettext('Private (Secret) Key:')  => array(
				'key'			=> 'ncrc_private_key',
				'type'			=> OPTION_TYPE_TEXTBOX,
				'order'			=> 2,
				'desc'			=> gettext('Enter your reCaptcha private key here. Visit https://www.google.com/recaptcha/intro/index.html to obtain your keys.')
			),
			// Dropdown for reCaptcha theme
			gettext('Widget Theme:') => array(
				'key'			=> 'ncrc_theme',
				'type'			=> OPTION_TYPE_SELECTOR,
				'order'			=> 3,
				'selections'	=> array(
										gettext('Light')	=> 'light',
										gettext('Dark')	 	=> 'dark'
								   ),
				'desc'			=> gettext('Choose the theme for your reCaptcha.')
			),
			// Dropdown for reCaptcha type
			gettext('Widget Type:') => array(
				'key'			=> 'ncrc_type',
				'type'			=> OPTION_TYPE_SELECTOR,
				'order'			=> 4,
				'selections'	=> array(
										gettext('Audio')	=> 'audio',
										gettext('Image')	=> 'image'
								   ),
				'desc'			=> gettext('Choose the secondary verification method you would like to use.')
			),
			// Dropdown for reCaptcha size
			gettext('Widget Size:') => array(
				'key'			=> 'ncrc_size',
				'type'			=> OPTION_TYPE_SELECTOR,
				'order'			=> 5,
				'selections'	=> array(
										gettext('Normal')	=> 'normal',
										gettext('Compact')	=> 'compact'
								   ),
				'desc'			=> gettext('Choose the size of the reCaptcha widget.')
			)
		);
		
		return $options;
		
	}
	
	// Returns HTML for reCaptcha (including required reCaptcha script)
	function captchaHtml($publicKey, $theme, $type, $size){
		
		return '
				<span class="g-recaptcha" data-sitekey="'.$publicKey.'" data-theme="'.$theme.'" data-type="'.$type.'" data-size="'.$size.'"></span>
				<script src="https://www.google.com/recaptcha/api.js"></script>
		';
		
	}
	
	// Called by form (wherever reCaptcha is enabled) on submit to check whether or not the capture has succeeded. $s1, $s2 are required.
	function checkCaptcha($s1, $s2) {
		
		$secretKey = getOption('ncrc_private_key');
		
		$captcha = $_POST['g-recaptcha-response'];
		
		// verifies reCaptcha
		$response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$captcha.'&remoteip='.$_SERVER['REMOTE_ADDR']);
		
		// Changes response value into expected format (for return)
		if(strpos($response,'true') == true){
			$valid = true;
		}
		else {
			$valid = false;
		}
		
		return $valid;
		
	}
	
	// Called by form (wherever reCaptcha is enabled) to add reCaptcha widget
	function getCaptcha($prompt){
		
		$publicKey = getOption('ncrc_public_key');
		
		$theme = getOption('ncrc_theme');
		
		$type = getOption('ncrc_type');
		
		$size = getOption('ncrc_size');
		
		// Check for proper configuration of options
		if (!getOption('ncrc_public_key') || !getOption('ncrc_private_key')) {
			
			return array('input' => '', 'html' => '<div class="errorbox"><p>' . gettext('reCAPTCHA keys are not configured properly. Visit <a href="https://www.google.com/recaptcha/intro/index.html">this link</a> to retrieve your reCaptcha keys then enter them in noCapture reCaptcha\'s options (found in Zenphoto Control Panel > Options > Plugins).') . '</p></div>', 'hidden' => '');
			
		} 
		else {
		
			$html = $this->captchaHtml($publicKey, $theme, $type, $size);
			
			return array('html'	 => '<label class="captcha-label">' . $prompt . '</label>', 'input' => $html);
		
		}
		
	}

}

// Required for script to be considered a reCaptcha handler
if ($plugin_disable) {
	enableExtension('nocaptcha_recaptcha', 0);
} else {
	$_zp_captcha = new nocaptcha_recaptcha(getOption('ncrc_private_key'));
}

?>
