<?php
/**
 * A Zenphoto plugin for jQuery UI
 * Experimental plugin, requires Zenphoto 1.4.5!** 
 *
 * The plugin does two things:
 * - Load jQuery on the front end (This does not load the Zenphoto core one but its own newer 1.10.x version!)
 * - Provides content macros to insert an accordion or tabs within the normal description or content fields
 *
 * @license GPL v3 
 * @author Malte Müller (acrylian)
 *
 * @package plugins
 * @subpackage misc
 */

$plugin_is_filter = 9|THEME_PLUGIN|ADMIN_PLUGIN;
$plugin_description = gettext('A plugin to either load jQuery UI and also enable macros for accordion and tabs.');
$plugin_author = 'Malte Müller (acrylian)';
$plugin_version = '1.4.5';
$option_interface = 'jqueryui';

global $_zp_gallery, $_zp_gallery_page;
if (getOption('jqueryui_'.$_zp_gallery->getCurrentTheme().'_'.stripSuffix($_zp_gallery_page))) {
	zp_register_filter('theme_head','jqueryui::jqueryuiJS');
}
zp_register_filter('content_macro','jqueryui::macros');

class jqueryui {

	/**
	 * class instantiation function
	 */
	function __construct() {
		setOptionDefault('jqueryui_theme', 'ui-lightness');
	}
	
	function getOptionsSupported() {
		$themes = $this->getUIThemes();
		/* 
			The option definitions are stored in a multidimensional array. There are several predefine option types.
			Options types are the same for plugins and themes.
		*/
		$options = array(
			 gettext('jQuery UI theme') => array('key' => 'jqueryui_theme', 'type' => OPTION_TYPE_SELECTOR,
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
					'desc' => gettext('The scripts for which jQuery UI should be loaded.')
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
	$default_skins_dir = SERVERPATH.'/'.USER_PLUGIN_FOLDER.'/jqueryui/themes';
	$user_skins_dir = SERVERPATH.'/'.USER_PLUGIN_FOLDER.'/jqueryui/themes';
	$filestoignore = array( '.', '..','.DS_Store','Thumbs.db','.htaccess','.svn');
	$skins = array_diff(scandir($default_skins_dir),array_merge($filestoignore));
	$default_skins = $this->getUIThemeCSS($skins,$default_skins_dir);
	$skins2 = @array_diff(scandir($user_skins_dir),array_merge($filestoignore));
	if(is_array($skins2)) {
		$user_skins = $this->getUIThemeCSS($skins2,$user_skins_dir);
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
	$skin = getOption('jqueryui_theme');
	if(file_exists($skin)) {
		$skin = str_replace(SERVERPATH,WEBPATH,$skin); //replace SERVERPATH as that does not work as a CSS link
	} else {
		$skin = WEBPATH.'/'.USER_PLUGIN_FOLDER.'/jqueryui/themes/ui-lightness/jquery-ui.min.css';
	}
	?>
	<script type="text/javascript" src="<?php echo FULLWEBPATH.'/'.USER_PLUGIN_FOLDER; ?>/jqueryui/jquery-ui.min.js"></script>
  <link href="<?php echo $skin; ?>" rel="stylesheet" type="text/css" />
  <script>
 		$(function() {
    	$(".ui-accordion").accordion();
    	$(".ui-tabs").tabs();
  	});
  </script>
  <?php	
}

static function macros($macros) {
			/* accordion macros */
			$macros['UIACC'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'<span class="ui-accordion">',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the opening div element for a jQuery UI accordion wrapper. Pass a class name as %1 or just empty.')
				);
				
			$macros['UIACC-END'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'</span>',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the closing div element for a jQuery UI accordion wrapper.')
			);
				
			$macros['UIACC-HL'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'<h3>',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the opening h3 element for a jQuery UI accordion element header.')
				);
			$macros['UIACC-HL-END'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'</h3>',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the closing h3 element for a jQuery UI accordion element header.')
				);
			$macros['UIACC-EL'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'<span>',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the opening div element for a jQuery UI accordion element content.')
				);
			$macros['UIACC-EL-END'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'</span>',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the opening div element for a jQuery UI accordion element content.')
				); 
				
				/* tabs macros */
				$macros['UITABS'] = array(
				'class'=>'function',
				'params'=> array('string','array'),
				'value'=>'jqueryui::getUITabsStart',
				'owner'=>'jqueryui',
				'desc'=>gettext('Something')
				);
				
				$macros['UITAB'] = array(
				'class'=>'function',
				'params'=> array('string'),
				'value'=>'jqueryui::getUITabStart',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the opening wrapper for the tab content. Pass the tab number as %1')
				); 
				
				$macros['UITAB-END'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'</span>',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the opening wrapper for the tab content. The number of these must match those set with the main wrapper.')
				); 
	
				$macros['UITABS-END'] = array(
				'class'=>'constant',
				'params'=> array(),
				'value'=>'</div>',
				'owner'=>'jqueryui',
				'desc'=>gettext('Provides the opening div element for a jQuery UI accordion element content.')
				); 
	
		return $macros;
	}

	static function getUITabsStart($id,$tabs) {
		global $tabid;		
		$tabid = $id;
		//print_r($tabs);
		$content = '<div style="display:block" id="'.$id.'" class="ui-tabs">';
		$count = '';
		$content .= '<ul>';
		 foreach($tabs as $key=>$tab) {
			$count++;
			$content .= '<li><a href="#'.$id.'-'.$count.'">tab</a></li>';
		}
		$content .= '</ul>';
		return $content; 
	}

	
	static function getUITabStart($number) {
		global $tabid;
		$content = '<span id="'.$tabid.'-'.$number.'">';
		return $content;
	}
	

	
} // class end
?>