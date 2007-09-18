<?php
/*
Plugin Name: Better "Alexa Redirect"
Plugin URI: http://kaloyan.info/blog/better-alexa-redirect-plugin/
Description: This plugin is designed to improve your Alexa Rating by tunneling all your blog's links with an <em>Alexa Redirect Link</em> (<code>http://redirect.alexa.com/redirect?</code>an_url_from_your_site). The benefit of this particular plugin over the rest <em>sibling</em> plugins that state to do the same, is that uses JavaScript to apply the redirects and it does not mess woth your actual HTML code, so using it will not affect your Google or other search engines ratings.
Author: Kaloyan K. Tsvetkov
Version: 0.2
Author URI: http://kaloyan.info/
*/

/////////////////////////////////////////////////////////////////////////////

/**
* @internal prevent from direct calls
*/
if (!defined('ABSPATH')) {
	return ;
	}

/**
* @internal prevent from second inclusion
*/
if (!class_exists('wp_alexa_redirect')) {

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
	* Whether the output buffering has been started by {@link wp_alexa_redirect}
	* @var boolean
	*/
	var $_ob_started = false;

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

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
		
		add_action(
			'wp_footer',
			array(&$this, 'cook_links')
			);

		// attach to admin menu
		//
		if (is_admin()) {
			add_action('admin_menu',
				array(&$this, '_menu')
				);
			}
		
		// attach to plugin installation
		//
		add_action(
			'activate_' . str_replace(
				DIRECTORY_SEPARATOR, '/',
				str_replace(
					realpath(ABSPATH . PLUGINDIR) . DIRECTORY_SEPARATOR,
						'', __FILE__
					)
				),
			array(&$this, 'install')
			);
		}
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	/**
	* Performs the routines required at plugin installation: 
	* in general introducing the whitelist setting
	*/	
	function install() {
		add_option(
			'wp_alexa_redirect_whitelist', ''
			);
		}
	
	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 
	
	/**
	* Attach the menu page to the `Options` tab
	*/
	function _menu() {
		add_submenu_page('options-general.php',
			 'Better "Alexa Redirect"',
			 'Better "Alexa Redirect"', 8,
			 __FILE__,
			 array($this, 'menu')
			);
		}
		
	/**
	* Handles and renders the menu page
	*/
	function menu() {
		
		// sanitize referrer
		//
		$_SERVER['HTTP_REFERER'] = preg_replace('~&saved=.*$~Uis','', $_SERVER['HTTP_REFERER']);
		
		// information updated ?
		//
		if ($_POST['submit']) {
			
			// sanitize the whitelist
			//
			$wl = preg_split(
				"~[\r\n]+~",
				$_POST['wp_alexa_redirect_whitelist']
				);
			$wl = preg_replace(
				"~[^a-z0-9_\.\-\r\n]+~", '-',
				join("\r\n", $wl)
				);
			
			update_option(
				'wp_alexa_redirect_whitelist',
				$wl
				);
			die("<script>document.location.href = '{$_SERVER['HTTP_REFERER']}&saved=whitelist:" . time() . "';</script>");
			}

		// operation report detected
		//
		if (@$_GET['saved']) {
			
			list($saved, $ts) = explode(':', $_GET['saved']);
			if (time() - $ts < 30) {
				echo '<div class="updated"><p>';
	
				switch ($saved) {
					case 'whitelist' :
						echo 'Whitelist saved.';
						break;
					}
	
				echo '</p></div>';
				}
			}

?>
<div class="wrap">
	<h2>Better "Alexa Redirect"</h2>
	<p>For more information please visit the <a href="http://kaloyan.info/blog/better-alexa-redirect-plugin/">Better &quot;Alexa Redirect&quot;</a> homepage.</p>
	<form method="post">
	<fieldset class="options">
		<legend>Whitelist</legend>
		<div>This plugin will prefix all your blog's links with <code><b>http://redirect.alexa.com/redirect?</b></code> in attemp to boost your <a href="http://www.alexa.com/data/details/traffic_details?url=<?php echo get_option('siteurl');?>" target="_blank">Alexa Ranking</a>. If you want to apply this operation to other links that appear on your blog (no matter if they are inside posts, blogroll, or hardcoded), you have to put their domains in the &quot;whitelist&quot; below, putting one domain per line.</div>
		<br/>
		<div style="width:45%; float: right;">
			<b>Examples:</b><br/>
			<ul>
			<li> yourdomain.com<br/><small>whitelists all the subdomains including: <b>yourdomain.com</b>, <b>www.yourdomain.com</b>, <b>blog.yourdomain.com</b>, <b>forum.yourdomain.com</b>, etc.</small>
			<li> www.yourdomain.com<br/><small>whitelists only the <b>www.yourdomain.com</b> subdomain; this will <u>not</u> work for the main <b>yourdomain.com</b> domain and other subdomains like <b>blog.yourdomain.com</b>, <b>forum.yourdomain.com</b>, etc.</small>
			</ul>
		</div>
		<textarea style="width:50%; height:230px;" name="wp_alexa_redirect_whitelist"><?php echo get_option('wp_alexa_redirect_whitelist');?></textarea>
		<p class="submit" style="text-align:left;"><input type="submit" name="submit" value="Update &raquo;" /></p>
	</fieldset>
	</form>
</div>
<?php
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

		if (ob_get_level() === 0) {
			$this->_ob_started = true;
			ob_start(
				array(&$this, 'cook_links')
				);
			}
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
		
		// prepare the whitelist
		//
		$wl = preg_replace(
			"~[\r\n]+~",
			"','",
			addSlashes(
				get_option('wp_alexa_redirect_whitelist')
				)
			);
		
		// this is the JS snippet
		//
		$js = <<<ALEXA_REDIRECT_JS
		
<script type="text/javascript">
<!--//
window.old_onload = window.onload || function(){};
window.onload = function() {
	var wl=['{$wl}']; wl.push(document.location.host);
	for(var i=0; i<document.links.length; i++) {

		if (navigator.userAgent.indexOf('MSIE 7.0; Windows NT 6.') > -1) {
			break;
			}

		var detected = false;
		for(var j=0; j<wl.length; j++) {
			if (wl[j] && document.links[i].href.indexOf(wl[j]) > -1) {
				detected = true;
				break;
				}
			}
		if (!detected) {
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

		if ($this->_ob_started) {

			// injecting the snippet
			// at the end of the page
			//
			return preg_replace(
				'~(</body>\s*</html>)~Uis', $js . '$1', $content
				);
			} else {
			
			// output the snippet in the footer
			//
			echo $js;
			}
		}
	
	//--end-of-class
	}

}

/////////////////////////////////////////////////////////////////////////////

/**
* Initiating the plugin...
* @see wp_alexa_redirect
*/
new wp_alexa_redirect;

?>