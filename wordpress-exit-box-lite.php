<?php

/**
 * @package Wordpress Exit Box Lite
 * @author Bouzid Nazim Zitouni
 * @version 1.14
 */
/*
Plugin Name: Wordpress Exit Box Lite
Plugin URI: https://dev.angrybyte.com/wordpress-exit-box-external-link-jquery-pop-up-plugin/
Description: Wordpress exit box is used design and display your exit box, a ThickBox page that will be shown to your users when they click a external link.
Author: Bouzid Nazim Zitouni
Version: 1.14
Author URI: http://angrybyte.com
*/

if(!function_exists('add_action')){
    echo ""; // someone is trying to run the plugin directly, added to avoid full path disclosure.
    die;
}
register_activation_hook( plugin_basename(__FILE__), 'exitboxlite_install' );
register_uninstall_hook( plugin_basename(__FILE__),  'exitboxlite_uninstall' );


function exitboxlite_install(){
add_option("exitboxlite_contents",
    '<h2 style="text-align: center;">It was nice having you! We hope that you enjoyed your stay.</h2>',
    'Contents of the Exit page', 'yes');
add_option("exitboxlite_delay", '10', 'yes');
add_option("exitboxlite_autoredirect", '0', 'yes');

add_option("exitboxlite_width", '630', 'yes'); 
add_option("exitboxlite_height", '440', 'yes');
}
function exitboxlite_uninstall(){
    delete_option("exitboxlite_contents");
     delete_option("exitboxlite_delay");
   delete_option("exitboxlite_autoredirect");
     delete_option("exitboxlite_width");
        delete_option("exitboxlite_height");
   
}
 

add_filter('the_content', 'widgetreplacelinks');
add_action('wp_head', 'exitboxlite_autoredirect');
add_action('admin_menu', 'exitboxadmin');
add_filter('query_vars', 'xtbx_queryvars');

function exitboxes($what)
{

    $mypath = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname
        (__file__));
    if ($what == 'list')
    {
        return array('thickbox');
    }
    if ($what == 'init')
    {
        if (!is_admin())
        {

            wp_enqueue_script('jquery');
            wp_enqueue_script('thickbox', null, array('jquery'));
            wp_enqueue_style('thickbox.css', '/' . WPINC . '/js/thickbox/thickbox.css', null,
                '1.0');
            return 1;


        }
    }
    if ($what == 'link')
    {
        $exitboxlite_width = get_option("exitboxlite_width");
        $exitboxlite_height = get_option("exitboxlite_height");

        return array("href" => "&keepThis=true&width=$exitboxlite_width&height=$exitboxlite_height",
            "tag" => 'class="thickbox"');

    }
    if ($what == 'close')
    {

        return "tb_remove()";
    }

}
function add_thickscript()
{
    exitboxes('init');
}
add_action('init', 'add_thickscript');
function xtbx_queryvars($qvars)
{
    $qvars[] = 'xb';

    return $qvars;
}

function exitboxadmin()
{

    add_options_page('Wordpress Exit Box Lite', 'Wordpress Exit Box Lite', 8,
        __file__, 'exit_box_admin');
}
function exit_box_admin()
{
     if (($_POST["xx"])&& (is_admin())&& check_admin_referer( 'exit_box_save', 'exit_box_nonce' ))
    {

        if (is_numeric($_POST['exitboxlite_width']) || $_POST['exitboxlite_width'] == "auto")
            update_option('exitboxlite_width', $_POST['exitboxlite_width']);
        if (is_numeric($_POST['exitboxlite_height']) || $_POST['exitboxlite_height'] == "auto")
            update_option('exitboxlite_height', $_POST['exitboxlite_height']);

        update_option('exitboxlite_contents', stripslashes($_POST['xx']));


    }
    $oldtemp = stripcslashes(get_option("exitboxlite_contents"));
    $exitboxlite_width = get_option("exitboxlite_width");
    $exitboxlite_height = get_option("exitboxlite_height");

    echo <<< EOFT
    <h1> Wordpress Exit Box Lite</h1>
    <table ><tr><td >
    <div class="metabox-holder" />
    <div  class="postbox gdrgrid frontleft">
<h3 class="hndle">
<span>Exit Box Options</span>
</h3>
<div class="inside">
<div class="table">
<table>
<tbody>
<tr class="first">
<td class="first b"><strong>Exit Box Design</strong></td>
<td class="t options"><Form method ='post' action='' ><input type='hidden' value='fit' name='fit' id='fit' />
EOFT;
    if (function_exists(wp_editor))
    {
        wp_editor($oldtemp, "xx", array('wpautop' => false, 'tabindex' => 1));

    } else
    {
        echo "<textarea name='xx' cols='150' rows='20'>$oldtemp</textarea>";
    }
    wp_nonce_field( 'exit_box_save','exit_box_nonce' ); 
    echo <<< EOFT

  <br/>
  <br/>
  <table><tbody>
  <tr><td><strong>Shortcode</strong></td><td><strong>Used to insert:</strong></td></tr>
  <tr><td><b>%n% </b></td><td>The Redirection Delay.</td></tr>
  <tr><td><b>%link% </b></td><td>The Redirection URL.</td></tr>
  <tr><td><strong><strike>%count%</strike> </strong></td><td>     <font color="#BFBDBD">Redirection Count Down.(Not included in Lite version)</font></td></tr>
<tr><td><strong><strike>%site+1%</strike> </strong></td><td>  <font color="#BFBDBD"> Google +1 Button For Your Site. (Not included in Lite version)</font></td></tr>
<tr><td><strong><strike>%post+1% </strike></strong></td><td>  <font color="#BFBDBD"> Google +1 Button  For Your Post.(Not included in Lite version)</font></td></tr>
<tr><td><strong><strike>%sitelike%</strike> </strong></td><td> <font color="#BFBDBD">Facebook Like Button for your site.(Not included in Lite version)</font></td></tr>
<tr><td><strong><strike>%postlike%</strike> </strong></td><td> <font color="#BFBDBD">Facebook Like Button for your post.(Not included in Lite version)</font></td></tr>
<tr><td><strong><strike>%sitetweet%</strike></strong></td><td> <font color="#BFBDBD">Twitter Button For Your Site.(Not included in Lite version)</font></td></tr>
<tr><td><strong><strike>%posttweet%</strike></strong></td><td> <font color="#BFBDBD">Twitter Button For Your Post.(Not included in Lite version)</font></td></tr>
<tr><td><strong><strike>%follow%</strike></strong></td><td>    <font color="#BFBDBD">Twitter Follow Button.(Not included in Lite version)</font></td></tr>
</tbody></table>
  

<br />HTML and javascript are allowed<br />
 
</tr>
<tr>
<td class="first b"><strong>Redirection delay </strong></td><td class="t options"><input type="text" name='delay' disabled="disabled" value='15' />in seconds (not editable in Lite)</td></tr><tr>
<td> <strong> Auto Redirection.</strong></td><td class="t options"><input type='checkbox' disabled="disabled" value='1' $chkd name='exitboxlite_autoredirect' /> Enable auto redirection after the end of the delay (Not optional in Lite version)</td></tr><tr>
<tr><td>
<strong>Exit Box Type.</strong></td><td class="t options"><select name='exitboxtype' size="1" style="width:120px">
    <option value='thickbox' >thickbox</option>(ColorBox, FancyBox, and Thickbox in full version)
</select><br /> The width of your Exit Box, configure this based on the width of your exit box design. Default:630</td></tr>
<tr><td>
<strong>Exit Box Width.</strong></td><td class="t options"><input type="text" size='100' name='exitboxlite_width' value='$exitboxlite_width' /><br /> The width of your Exit Box, configure this based on the width of your exit box design. Default:630 (Only  ThickBox)</td></tr>
<tr><td>

<strong>Exit Box Height.</strong></td><td class="t options"><input type="text" size='100' name='exitboxlite_height' value='$exitboxlite_height' /><br /> The height of your Exit Box, configure this based on the height of your exit box design. Default:440 (Only needed when using ThickBox)</td></tr><tr><td>
<strong>Open links in a new window</strong></td><td class="t options"><input type='checkbox' disabled="disabled" value='0'  name='exitboxnewwindow' /> Clicking the external link inside the exit box will open the link in a new page. (Not available in Lite)</td></tr><tr><td>
 <strong>Process entire pages</strong></td><td class="t options"><input type='checkbox' disabled="disabled" value='0'  name='boxprocesswholepages' /> redirect links in the entire page including widgets and footer, or just the post contents. (Not available in Lite)</td></tr>
<tr><td>
<strong>End of count Message.</strong></td><td class="t options"><input type="text" size='100' disabled="disabled" name='eoc' value='' /><br /> Display this message when the %count% counter runs out (HTML allowed , can include %link%) Generally &#60;a href="%link%"&#62;Click here to continue&#60;/a&#62; (Not available in Lite)</td></tr><tr><td>
<strong>Excluded pages.</strong></td><td class="t options"><input type="text" size='100' disabled="disabled" name='excl' value='' /><br /> enter to post IDs to be excluded from your exit page, useful for your sales or affiliate pages. Separate by commas ex: 3,55,153 (Not available in Lite)</td></tr>
<tr><td>
<strong>Twitter account.</strong></td><td class="t options">@<input type="text" size='100' disabled="disabled" name='twitterboxname' value='' /><br /> This will be used with the Twitter follow button shortcode %follow% </td></tr>
<tr><td>
<strong>Facebook App Id.</strong></td><td class="t options"><input type="text" size='100' disabled="disabled" name='facebookappid' value='' /><br /> If you dont have one for your website, <a href="https://developers.facebook.com/apps/">get one here</a>. If you keep this blank, your facebook like buttons will fallback to iframe mode, which is not compatible with the Share&Pass feature (Not available in Lite)</td></tr>
<tr><td>
<strong>Enable Share&Pass.</strong></td><td class="t options"><input type='checkbox' disabled="disabled" value='1'  name='socialpass' /> Skip the waiting timer when the user clicks the social sharing buttons (Not available in Lite)</td></tr>
<tr><td>
<strong>Excluded Links</strong>.</td><td class="t options"><textarea name='exitboxexcludedlinks' disabled="disabled" cols='100' rows='20'></textarea><br /> Enter the links you wish to exclude from your exit strategy. each link is a separate line. Excluding a website will exclude all pages within it, and excluding a page will not exclude the rest of the website. (Not available in Lite)</td></tr>
<tr><td>
<input type='submit' value='save' /></form></td></tr>
</tbody>
</table>
</div></div>
</div></td><td VALIGN="TOP">
 <div class="metabox-holder" />
    <div  class="postbox gdrgrid frontleft" >
<h3 class="hndle" ><span>WordPress Exit Box</span></h3>
<div class="inside">
<div class="table">
<table><tr class="first" ><td>
<h4>Love Exit Box Lite? wait until you see the full version. it is now available with all these cool features:</h4>
<ul style="color: #444444;
font: normal normal 400 13px/19px Tahoma;

list-style: square;
margin: 0px 0px 2px;

padding: 0px 0px 0px 8px;
text-align: left; ">
    <li>Design your Exit Box, or paste  HTML  from any source.</li>
    <li>Pick your redirection method. Auto, count down timer, or a clickable static link.</li>
    <li>Select your Box, use Wordpress&#8217;s bundled ThickBox, or go for a FancyBox or a ColorBox.</li>
    <li>Customizable redirection delay</li>
    <li>Exclude selected posts or pages, useful for sales pages.</li>
    <li>Exclude any website or link, enter a list of URLs that will not be pass through your Exit Box.</li>
    <li>Shortcodes for Facebook , Google+,  Tweet, and Follow buttons, for your post or entire site.</li>
    <li>Share&#38;Pass! with this new feature, your visitors can skip the waiting time by clicking one of the social buttons, +1, Like, Tweet, or Follow</li>
    <li>Only process links in post contents, or process every external link in WordPress.</li>
    <li>Falls back to a standalone Exit Page if your visitor opens the external link in a new page.</li>
</ul>
<div style="text-align:center;font: normal normal 400 15px/19px Tahoma"><a  href="http://codecanyon.net/item/wordpress-exit-box/2291033?ref=angrybyte">Interested? learn more about WordPress Exit Box here!<br /><img title="Check some of the many cool thing you can do with Exit Box" style="width:100%;align:center" src="https://dl.dropbox.com/u/7048593/exitbox.png" /></a></div>
</td></tr></table></div></div>
</tr></table>
EOFT;
}
function exitboxlite_autoredirect()
{
    global $post;
    global $wp_query;

    if (isset($wp_query->query_vars['xb']))
    {
        $d = get_option("exitboxlite_contents");
        $d = stripslashes($d);
        $d = replacelinks($d);

        $ender = exitboxes("close");
        $d = str_replace("<a ", '<a rel="nofollow" onclick="cleanupbox()" ', $d);


        echo "</head><body   >
<div align='center' style='vertical-align: middle;'>
<table  cellspacing='0' cellpadding='0 ' style='vertical-align: middle;width:auto;'>
<tbody>
<tr style='vertical-align: middle;'>
<td valign='top' align='left' style='vertical-align: middle;'>$d</td>
</tr>
</tbody>
</table>
</div><div style='text-align:center'><a href='http://codecanyon.net/item/wordpress-exit-box/2291033?ref=angrybyte'>Get WordPress Exit Box Plugin for your website!</a></div></body></html>";

        die(); //mama I just shot a man down
    }


}

function replacelinks($content)
{
    global $post;
    global $wp_query;
    $pageurl = get_permalink($post->ID);

    if (isset($wp_query->query_vars['xb']))
    {

        $pageurl = get_permalink($post->ID);

        $myurl = get_option('siteurl');

        $url = $wp_query->query_vars['xb'];
      //  $url = urldecode($url);
          $url = "http" . urldecode($url); //to bypass http forbidden rule on some servers
        //block possible code injection attempts
        $m2 = strpos(" " . $url, "<");
        $m3 = strpos(" " . $url, "$");
        $m4 = strpos(" " . $url, "'");
        $m5 = strpos(" " . $url, '"');

        if ($m2 || $m3 || $m4 || $m5)
        {
            return $content;
            //someone is trying to inject a code, return contents without modifications
        }
        $url = sanitize_text_field($url); //added security
        $d = get_option("exitboxlite_contents");
        $c = 15;
        $cc = $c * 1000;
        $autoredirfun = "var t=setTimeout(\"parent.location ='$url';\",$cc);";
        $reditrectit = 'boxskipit = -1;';
        $stopredirect = 'boxskipit = -1;';
        if (get_option('exitboxlite_autoredirect'))
        {
            $reditrectit = "window.open('$url');";
            $stopredirect = "clearTimeout(t);";
        }
        $mess = addslashes(get_option("boxcountmessage"));
        $mypath = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname
            (__file__));


        $ender = exitboxes("close");

        $counter = <<< EOTX
     <script language="JavaScript">
gsecs = $c;
CountActive = true;
CountStepper = -1;
LeadingZero = true;
DisplayFormat = "%%S%%";
FinishMessage = '$mess';
$autoredirfun
jQuery(document).ready(function(){
    
    jQuery(window).bind('tb_unload',function() {    cleanupbox();})
});
function cleanupbox(){
    $stopredirect
    $ender;
}
function plusClick(data){
    $reditrectit
}
</script>
  
EOTX;

        $d = $counter . str_ireplace("%n%", $c, $d);

        $d = str_ireplace("%link%", $url, $d);

        $content = stripcslashes($d);

        return $content;


    }
    $content = widgetreplacelinks($content);

    return $content;
}

function widgetreplacelinks($content)
{
    global $wp_query;
    preg_match_all('/<a\s[^>]*href\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>(.*)(?!<\/a>)/siU',
        $content, $matches);
    $matches = $matches[0];
    global $post;
    $pageurl = get_permalink($post->ID);
    $myurl = get_option('siteurl');
    $serv = $_SERVER['PHP_SELF'];
    foreach ($matches as $match)
    {
        $qref = strpos($match, $myurl);
        $qref2 = strpos($match, "http");


        if (($qref == '') && ($qref2 != ''))
        {
            preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $match,
                $xurl);
            $xurl = $xurl[0][0];
            $exitboxvals = exitboxes("link");
            if (strpos($myurl, "?"))
            {

                $xurl2 = $myurl . "&xb=" . urlencode($xurl) . $exitboxvals['href'];

            } else
            {
                $xurl2 = $myurl . "?xb=" . urlencode($xurl) . $exitboxvals['href'];

            }
            $newlink = str_replace($xurl, $xurl2, $match);
            $newlink = str_replace("xb=http","xb=", $newlink); //to bypass HTTP forbidden issue on some servers
            $newlink = str_replace("<a ", "<a {$exitboxvals['tag']} ", $newlink);
            if (!strpos($newlink, "nofollow"))
            {
                $newlink = str_replace("<a ", '<a rel="nofollow" ', $newlink);
            }
            $content = str_ireplace($match, $newlink, $content);

        }
    }

    return $content;
}

?>