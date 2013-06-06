<?php
/**
 *
 * @license GPL v3 
 * @author Malte Müller (acrylian)
 *
 * @package plugins
 */

$plugin_is_filter = 9|THEME_PLUGIN;
$plugin_description = gettext('A plugin to either load jQuery UI and also enable macros for accordion and tabs.');
$plugin_author = 'Malte Müller (acrylian)';
$plugin_version = '1.4.5';
$option_interface = 'jqueryui_options';
//zp_register_filter('theme_head','socialshareprivacyJS');

class queryui_options {

	/**
	 * class instantiation function
	 */
	function __construct() {
	
	}
	
	function getOptionsSupported() {
		$themes = $this->getUIThemes();
		/* 
			The option definitions are stored in a multidimensional array. There are several predefine option types.
			Options types are the same for plugins and themes.
		*/
		$options = array(
			 gettext('jQuery UI theme') => array('key' => 'queryui_theme', 'type' => OPTION_TYPE_SELECTOR,
	 				'selections' => $themes,
	 				'desc' => gettext("Select the theme to use. Place custom skin within the root plugins folder. See plugin documentation for more info."))
		);
		
		foreach (getThemeFiles(array('404.php','themeoptions.php','theme_description.php')) as $theme=>$scripts) {
			$list = array();
			foreach ($scripts as $script) {
				$list[$script] = 'jqueryui_'.$theme.'_'.stripSuffix($script);
			}
			$opts[$theme] = array('key' => 'jqueryui_'.$theme.'_scripts', 'type' => OPTION_TYPE_CHECKBOX_ARRAY,
					'checkboxes' => $list,
					'desc' => gettext('The scripts for which jquery ui should be loaded.')
			);
		}
		$options = array_merge($options, $opts);
		
	return $options;
	}
	
	/**
 * Gets the skin names and css files
 *
 */
function getUIThemes() {
	$all_skins = array();
	$default_skins_dir = SERVERPATH.'/'.ZENFOLDER.'/'.PLUGIN_FOLDER.'/jqueryui/skin/';
	$user_skins_dir = SERVERPATH.'/'.USER_PLUGIN_FOLDER.'/jqueryui/skin/';
	$filestoignore = array( '.', '..','.DS_Store','Thumbs.db','.htaccess','.svn');
	$skins = array_diff(scandir($default_skins_dir),array_merge($filestoignore));
	$default_skins = getUIThemeCSS($skins,$default_skins_dir);
	//echo "<pre>";print_r($default_skins);echo "</pre>";
	$skins2 = @array_diff(scandir($user_skins_dir),array_merge($filestoignore));
	if(is_array($skins2)) {
		$user_skins = getUIThemeCSS($skins2,$user_skins_dir);
		//echo "<pre>";print_r($user_skins);echo "</pre>";
		$default_skins = array_merge($default_skins,$user_skins);
	}
	return $default_skins;
}
	
	/**
 * Gets the css files for a skin. Helper function for getUIThemes().
 *
 */
function getUIThemeCSS($skins,$dir) {
	$skin_css = array();
	foreach($skins as $skin) {
		$css = safe_glob($dir.'/'.$skin.'/jquery-ui.min.css');
		if($css) {
			$skin_css = array_merge($skin_css,array($skin => $css[0]));	// a skin should only have one css file so we just use the first found
		}
	}
	return $skin_css;
}

/**
* Adds the jQuery calls to the theme head via filter
*/
static function jqueryuiJS() {
	?>
	<script type="text/javascript" src="<?php echo FULLWEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/jqueryui/jquery-ui.min.js"></script>
  <?php	
}
	
} // class end
?>