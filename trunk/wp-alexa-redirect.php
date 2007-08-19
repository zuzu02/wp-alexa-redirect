<?php
/*
Plugin Name: Better "Alexa Redirect"
Plugin URI: http://kaloyan.info/blog/better-alexa-redirect-plugin/
Description: This plugin is designed to improve your Alexa Rating by tunneling all your blog's links with an <em>Alexa Redirect Link</em> (<code>http://redirect.alexa.com/redirect?</code>an_url_from_your_site). The benefit of this particular plugin over the rest <em>sibling</em> plugins that state to do the same, is that uses JavaScript to apply the redirects and it does not mess woth your actual HTML code, so using it will not affect your Google or other search engines ratings.
Author: Kaloyan K. Tsvetkov
Version: 0.1
Author URI: http://kaloyan.info/
*/

/////////////////////////////////////////////////////////////////////////////

/**
* @internal prevent from direct calls
*/
if (!defined('ABSPATH')) {
	return ;
	}

/////////////////////////////////////////////////////////////////////////////

/**
* Better "Alexa Redirect" Plugin
*
* This is the `Better "Alexa Redirect" Plugin` class
*
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/
Class wp_alexa_redirect {

	/**
	* Constructor
	*
	* This constructor attaches the plugin hook
	* callback to the `template_redirect` action
	*/
	function wp_alexa_redirect() {
		add_action(
			'template_redirect',
			array(&$this, '_cook_links')
			);
		}
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
	
	/**
	* Start the output buffering
	*
	* This method is called by the `template_redirect` 
	* action, and it starts the output buffering by
	* attaching a callback which is going to be
	* activated when the buffering is over.
	*/
	function _cook_links() {
		ob_start(
			array(&$this, 'cook_links')
			);
		}

	/**
	* Adds the JavaScript snippet
	*
	* This method is called when the output 
	* buffering is over, and it attaches a small 
	* JavaScript snippet to the end of the page, which 
	* does its voodoo when the page is loaded.
	*
	* @param string $content
	* @return string
	*/
	function cook_links($content) {
		
		// this is the JS snippet
		//
		$js = <<<ALEXA_REDIRECT_JS
<script type="text/javascript">
<!--//
window.old_onload = window.onload || function(){};
window.onload = function() {
	for(var i=0; i<document.links.length; i++) {
		if (document.links[i].href.indexOf(document.location.host) < 0) {
			continue;
			}
		document.links[i].onmousedown = function(){
			if (this.href.indexOf('http://redirect.alexa.com/redirect?') < 0) {
				this.href = 'http://redirect.alexa.com/redirect?' + escape(this.href);
				}
			}
		}	
	window.old_onload();
	}
//-->
</script>
ALEXA_REDIRECT_JS;
				
		// injecting the snippet
		// at the end of the page
		//
		return preg_replace('~(</body>\s*</html>)~Uis', $js . '$1', $content);
		}
	
	//--end-of-class
	}

/////////////////////////////////////////////////////////////////////////////

/**
* Initiating the plugin...
* @see wp_alexa_redirect
*/
new wp_alexa_redirect;

?>