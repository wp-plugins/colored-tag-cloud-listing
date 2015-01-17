=== Colored Tag Cloud Listing ===
Tags: tags, tag cloud
Requires at least: 4.0
Tested up to: 4.1
Contributors: jp2112
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7EX9NB9TLFHVW
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a tag cloud with some color options.

== Description ==

Based on <a href="http://www.wpbeginner.com/wp-themes/how-to-style-tags-in-wordpress/">How to Style Tags in WordPress</a>.

<h3>If you need help with this plugin</h3>

If this plugin breaks your site or just flat out does not work, create a thread in the <a href="http://wordpress.org/support/plugin/colored-tag-cloud-listing">Support</a> forum with a description of the issue. Make sure you are using the latest version of WordPress and the plugin before reporting issues, to be sure that the issue is with the current version and not with an older version where the issue may have already been fixed.

<strong>Please do not use the <a href="http://wordpress.org/support/view/plugin-reviews/colored-tag-cloud-listing">Reviews</a> section to report issues or request new features.</strong>

= Features =

- Create a simple yet elegant tag cloud
- Choose from 10+ different color options
- CSS loads conditionally

= Shortcode =

To display on any post or page, use this shortcode:

[colored-tag-cloud]

Make sure you go to the plugin settings page after installing to set options.

<strong>If you use and enjoy this plugin, please rate it and click the "Works" button below so others know that it works with the latest version of WordPress.</strong>

== Installation ==

1. Upload the plugin through the WordPress interface.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings &raquo; 'Colored Tag Cloud Listing' and configure the plugin.
4. Insert shortcode on posts or pages, or call the plugin from your PHP code.

To remove this plugin, go to the 'Plugins' menu in WordPress, find the plugin in the listing and click "Deactivate". After the page refreshes, find the plugin again in the listing and click "Delete".

== Frequently Asked Questions ==

= How do I use the plugin? =

Use a shortcode to call the plugin from any page or post like this:

`[colored-tag-cloud]`

The shortcode can also be used in your PHP code (functions.php, or a plugin) using the <a href="http://codex.wordpress.org/Function_Reference/do_shortcode">do_shortcode</a> function, ex:

`echo do_shortcode('[colored-tag-cloud]');`

You can also call the plugin's function in your PHP code like this:

`add_action('the_content', 'show_tag_cloud');
function show_tag_cloud($content) {
  if (is_page('home')) { // we are on a page with slug 'home'
    if (function_exists('jpctcl')) { // plugin is installed/active
      $content .= jpctcl(array('cssclass' => 'blue'));
    }
  }
  return $content;
}`

This will add a tag cloud (with blue color style) at the end of your content, if you are on a page with a slug named "home". Always wrap plugin function calls with a `function_exists` check so that your site doesn't go down if the plugin isn't active.

In short, 'colored-tag-cloud' is the shortcode and 'jpctcl' is the PHP function name.

= What are the plugin defaults? =

The plugin arguments and default values may change over time. To get the latest list of arguments and defaults, look at the settings page after installing the plugin. That is where the latest list will always be located. You will see what parameters you can specify and which ones are required.

= What styles are available? =

The following styles are available.

<ul>
<li>green</li>
<li>blue</li>
<li>yellow</li>
<li>orange</li>
<li>red</li>
<li>purple</li>
<li>aqua</li>
<li>black</li>
<li>gray</li>
<li>lime</li>
<li>maroon</li>
<li>navy</li>
<li>olive</li>
<li>silver</li>
<li>teal</li>
</ul>

See the dropdown list on the plugin settings menu for the most updated list. Visit http://www.jimmyscode.com/wordpress/colored-tag-cloud-listing/ for a live demo of each style.

= I added the shortcode to a page but I don't see anything. =

Clear your browser cache and also clear your cache plugin (if any).

= I cleared my browser cache and my caching plugin but the buttons still look wrong. =

Are you using a plugin that minifies or combines CSS files at runtime? If so, try excluding the plugin CSS file from minification.

= I cleared my cache and still don't see what I want. =

The CSS files include a `?ver` query parameter. This parameter is incremented with every upgrade in order to bust caches. Make sure none of your plugins or functions are stripping this query parameter. Also, if you are using a CDN, flush it or send an invalidation request for the plugin CSS files so that the edge servers request a new copy of it.

= I don't want the post editor toolbar button. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_enqueue_scripts', 'jpctcl_ed_buttons');`

= I don't want the admin CSS. How do I remove it? =

Add this to your functions.php:

`remove_action('admin_head', 'insert_jpctcl_admin_css');`

= I don't want the plugin CSS. How do I remove it? =

Add this to your functions.php:

`add_action('wp_enqueue_scripts', 'remove_jpctcl_style');
function remove_jpctcl_style() {
  wp_deregister_style('jpctcl_style');
}`

= I don't want the toolbar button. How do I remove it? =

Add this to your functions.php:

`add_action('wp_enqueue_scripts', 'remove_jpctcl_script');
function remove_jpctcl_script() {
  wp_deregister_script('jpctcl_add_editor_button');
}`

= I don't see the plugin toolbar button(s). =

This plugin adds one or more toolbar buttons to the HTML editor. You will not see them on the Visual editor.

The label on the toolbar button is "Tag Cloud".

= I am using the shortcode but the parameters aren't working. =

On the plugin settings page, go to the "Parameters" tab. There is a list of possible parameters there along with the default values. Make sure you are spelling the parameters correctly.

The Parameters tab also contains sample shortcode and PHP code.

== Screenshots ==

1. Plugin settings page
2. Sample tag cloud

== Changelog ==

= 0.0.6 =
- confirmed compatibility with WordPress 4.1
- some minor typos fixed

= 0.0.5 =
- updated .pot file and readme

= 0.0.4 =
- fixed validation issue

= 0.0.3 =
- compressed CSS, removed fuschia color (no such color)

= 0.0.2 =
- admin CSS and page updates

= 0.0.1 =
created

== Upgrade Notice ==

= 0.0.6 =
- confirmed compatibility with WordPress 4.1, some minor typos fixed

= 0.0.5 =
- updated .pot file and readme

= 0.0.4 =
- fixed validation issue

= 0.0.3 =
- compressed CSS, removed fuschia color (no such color)

= 0.0.2 =
- admin CSS and page updates

= 0.0.1 =
created