<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bootstrap.min.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/font-awesome.css?fin20" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/theme.css" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// Theme JS files.
	echo '
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/bootstrap.min.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/scrolling-nav.js"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/jquery.easing.min.js"></script>';
	// Here comes the JavaScript bits!
	echo'
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';
	
	echo '
	<style type="text/css">
	@media (min-width: 768px) 
	{
		.container {
			width: ' . $settings['forum_width'] . ';
		}
	}
	</style>
	
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

		echo '
    <div id="intro" class="intro-section">
		<header class="head-er" id="top">
			<div class="top-header-b">
				<a class="logow pull-left" href="', $scripturl, '">
					', empty($context['header_logo_url_html_safe']) ? '<img  src="' . $settings['images_url'] . '/logo.png" alt="' . $context['forum_name'] . '" />' : '<img class="logo" src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '
				</a>
				<ul class="btn-nav btn-group pull-right">
					<li><a href="#forum" class="btn btn-white-lg page-scroll"><i class="fa fa-home fa-fw"></i><span class="hidden-xs">',$txt['forum'],'</span></a></li>'; 

			if(!empty($context['user']['is_logged']))
				echo'
					<li><a href="', $scripturl, '?action=logout;sesc=', $context['session_id'], '" class="btn btn-green-crt"><i class="fa fa-logout fa-fw"></i><span class="hidden-xs">', $txt['logout'], '</span></a></li>';
			else
				echo'
					<li><a href="', $scripturl, '?action=login#forum" class="btn btn-green-crt"><i class="fa fa-login fa-fw"></i><span class="hidden-xs">',$txt['login'],'</span></a></li>';

			echo'
				</ul>
			</div>
			<div class="container">
				<div class="profile-area">
					<div class="profile-image">
						<img class="profile-pic" src="', !empty($context['user']['avatar']['href']) ? $context['user']['avatar']['href'] : $settings['images_url']. '/theme/noavatar.png' ,'" alt="', $context['user']['name'],'" />
					</div>
					<div class="profile-menu va">
						<ul class="menu val">
							<li class="dropdown">
								<a class="btn btn-green-crt" data-toggle="dropdown" href="#">
									', $context['user']['name'], '
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu" role="menu">';

							if(!empty($context['user']['is_logged']))
							{
								echo'
									<li><a href="', $scripturl, '?action=profile#forum"><i class="fa fa-user"></i> ', $txt['profile'], '</a></li>
									<li><a href="', $scripturl, '?action=profile;area=forumprofile#forum"><i class="fa fa-edit"></i> ', $txt['forumprofile'], '</a></li>
									<li><a href="', $scripturl, '?action=profile;area=account#forum"><i class="fa fa-cog"></i> ', $txt['account'], '</a></li>
									<li><a href="', $scripturl, '?action=logout;sesc=', $context['session_id'], '"><i class="fa fa-logout"></i> ', $txt['logout'], '</a></li>';	
							}
							else
							{
								echo'
									<li><a href="', $scripturl, '?action=login#forum"><i class="fa fa-login fa-fw"></i> ',$txt['login'],'</a></li>
									<li><a href="',$scripturl,'?action=register#forum"><i class="fa fa-register fa-fw"></i> ',$txt['register'],'</a></li>';
							}
							echo'
								</ul>
							</li>';

					if(!empty($context['user']['is_logged']))
					{
						echo'
							<li class="text"><a href="', $scripturl, '?action=unread#forum">', $txt['unread_since_visit'], '</a></li>
							<li class="text"><a href="', $scripturl, '?action=unreadreplies#forum">', $txt['show_unread_replies'], '</a></li>';
					}
					else
						echo'
							<li class="welcome">', sprintf($txt['welcome_guest'], $txt['guest_title']), '</li>';

					echo'
						</ul>
					</div>
				</div>
			</div>				
		</header>
    </div>

    <div id="forum" class="forum-section">	
		<nav class="navbar navbar-default">
			<div class="container">	
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-user">
						<i class="fa fa-profile"></i>
					</button>
					<a class="logow nav pull-left" href="', $scripturl, '">
						', empty($context['header_logo_url_html_safe']) ? '<img  src="' . $settings['images_url'] . '/logo.png" alt="' . $context['forum_name'] . '" />' : '<img class="logo" src="' . $context['header_logo_url_html_safe'] . '" alt="' . $context['forum_name'] . '" />', '
					</a>
				</div>
				<div class="pull-right menu">
					<div class="navbar-collapse collapse" id="menu">';

						// Show the menu here, according to the menu sub template.
						template_menu();

				echo'
					</div>';

		if(!empty($context['user']['is_logged']))
		{
			echo'
					<div class="navbar-collapse-user collapse">
						<ul class="nav navbar-nav">
							<li><a href="', $scripturl, '?action=profile"><i class="fa fa-user"></i> ', $txt['profile'], '</a></li>
							<li><a href="', $scripturl, '?action=profile;area=forumprofile"><i class="fa fa-edit"></i> ', $txt['forumprofile'], '</a></li>
							<li><a href="', $scripturl, '?action=profile;area=account"><i class="fa fa-cog"></i> ', $txt['account'], '</a></li>
							<li><a href="', $scripturl, '?action=logout;sesc=', $context['session_id'], '"><i class="fa fa-logout"></i> ', $txt['logout'], '</a></li>
						</ul>
					</div>';
		}
		else
		{
		echo'
					<div class="navbar-collapse-user collapse">
						<ul class="nav navbar-nav">
							<li><a href="', $scripturl, '?action=login"><i class="fa fa-login"></i> ', $txt['login'], '</a></li>
							<li><a href="', $scripturl, '?action=register"><i class="fa fa-register"></i> ', $txt['register'], '</a></li>
						</ul>
					</div>';
		}
		echo'
				</div>	
			</div>
		</nav>
		<div class="wh-bar-u">
			<div class="container">
				<div class="pull-left">
					<form id="custom-search-form" action="', $scripturl, '?action=search2#forum" method="post" accept-charset="', $context['character_set'], '" class="form-search form-horizontal pull-right">
						<input type="text" class="search-query" name="search" placeholder="Search">
						<button type="submit" name="submit" class="btn"><i class="fa fa-search"></i></button>
					</form>
				</div>
				<div class="dropdown pull-right dr-menu">
					<a class="dr-wh-baru" data-toggle="dropdown" href="#">
						', $context['user']['name'], '
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu" role="menu">';

			if (!empty($context['user']['is_logged']))
			{
				echo'
						<li><a href="', $scripturl, '?action=profile"><i class="fa fa-user"></i> ', $txt['profile'], '</a></li>
						<li><a href="', $scripturl, '?action=profile;area=forumprofile"><i class="fa fa-edit"></i> ', $txt['forumprofile'], '</a></li>
						<li><a href="', $scripturl, '?action=profile;area=account"><i class="fa fa-cog"></i> ', $txt['account'], '</a></li>
						<li><a href="', $scripturl, '?action=logout;sesc=', $context['session_id'], '"><i class="fa fa-logout"></i> ', $txt['logout'], '</a></li>';
			}
			else
			{
				echo'
						<li><a href="', $scripturl, '?action=login#forum"><i class="fa fa-login fa-fw"></i> ',$txt['login'],'</a></li>
						<li><a href="',$scripturl,'?action=register"><i class="fa fa-register fa-fw"></i> ',$txt['register'],'</a></li>';
			}		
		echo'
					</ul>
				</div>
			</div>
		</div>
		<div class="container">';

	// Custom banners and shoutboxes should be placed here, before the linktree.

	// Show the navigation tree.
	theme_linktree();
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
			<div id="footer_section">
				<div class="frame">
					<div class="row">
						<div class="col-md-9">
							<ul class="reset">
								<li class="copyright">', theme_copyright(), '</li>
								<li><a id="button_xhtml" href="http://validator.w3.org/check?uri=referer" target="_blank" class="new_win" title="', $txt['valid_xhtml'], '"><span>', $txt['xhtml'], '</span></a></li>
								', !empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']) ? '<li><a id="button_rss" href="' . $scripturl . '?action=.xml;type=rss" class="new_win"><span>' . $txt['rss'] . '</span></a></li>' : '', '
								<li class="last"><a id="button_wap2" href="', $scripturl , '?wap2" class="new_win"><span>', $txt['wap2'], '</span></a></li>
							</ul>
						</div>
						<div class="col-md-3">
							<ul class="reset2">
								',copyright(),'
							</ul>
						</div>
					</div>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
					<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '
				</div>
			</div>
		</div>
	</div>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
		<ol class="st-bread breadcrumb">';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		echo '
			</li>';
	}
	echo '
		</ol>';

	$shown_linktree = true;
}
// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;
	echo'
	 <ul class="nav navbar-nav">';
	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '" class="', $button['sub_buttons'] ? 'dropdown ' : '', '', $button['active_button'] ? 'active ' : '', '">
					<a ', $button['sub_buttons'] ? 'class="dropdown-toggle" ' : '', 'href="', $button['sub_buttons'] ? '#' : $button['href'] . '#forum', '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '', $button['sub_buttons'] ? ' data-toggle="dropdown"' : '', '>
						', $button['title'], '
						', $button['sub_buttons'] ? '<span class="caret"></span>' : '' ,'
					</a>';
		if (!empty($button['sub_buttons']))
		{
			echo '
					<ul class="dropdown-menu" role="menu">
						<li>
							<a href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>', $button['title'], '</a>
						</li>';
					
			if ($act == 'admin')
					echo '
						<li>
							<a href="', $scripturl, '?action=admin;area=theme;sa=settings;th=', $settings['theme_id'], '">', $txt['current_theme'], '</a>
						</li>';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
								', $childbutton['title'] , '
							</a>
						</li>';
			}
				echo '
					</ul>';
		}
		echo '
				</li>';
				
	}
	echo'
	</ul>';
}
// COPYRIGHT NO REMOVE!
function copyright()
{
	$copy = '<li class="copyright2" style="display: block;">Theme by <a href="https://smftricks.com" title="SMFTricks">SMF Tricks</a></li>';
	return $copy;
}
// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<a class="btn btn-green-crt" ' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><i class="fa fa-'.$value['text'].'"></i> <span class="hidden-xs">' . $txt[$value['text']] . '</span></a>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<div class="btn-group">',
				implode('', $buttons), '
			</div>
		</div>';
}

?>