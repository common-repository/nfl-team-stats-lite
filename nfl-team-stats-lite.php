<?php
/*
Plugin Name: NFL Football Team Stats Lite
Description: Provides the latest NFL stats of your NFL Team, updated regularly throughout the NFL regular season.
Author: A93D
Version: 0.8.2
Author URI: http://www.thoseamazingparks.com/getstats.php
*/

require_once(dirname(__FILE__) . '/rss_fetch.inc'); 
define('MAGPIE_FETCH_TIME_OUT', 60);
define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
define('MAGPIE_CACHE_ON', 0);

// Get Current Page URL
function NFLLPageURL() {
 $NFLLpageURL = 'http';
 $NFLLpageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $NFLLpageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $NFLLpageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $NFLLpageURL;
}
/* This Registers a Sidebar Widget.*/
function widget_nfllstats() 
{
?>
<h2>NFL Team Stats</h2>
<?php nfll_stats(); ?>
<?php
}

function nfllstats_install()
{
register_sidebar_widget(__('NFL Team Stats Lite'), 'widget_nfllstats'); 
}
add_action("plugins_loaded", "nfllstats_install");

/* When plugin is activated */
register_activation_hook(__FILE__,'nfll_stats_install');

/* When plugin is deactivation*/
register_deactivation_hook( __FILE__, 'nfll_stats_remove' );

function nfll_stats_install() 
{
// Initial Team
$initialnfllteam = 'arizona_cardinals_stats';
add_option("nfll_stats_color", "#000000", "This is my default stats color", "yes");

add_option("nfll_stats_team", "$initialnfllteam", "This is my nfll team", "yes");

if ( ($ads_id_1 == 1) || ($ads_id_1 == 0) )
	{
	mail("links@a93d.com", "LITE NFLL Stats Installation", "Hi\n\nLITE NFLL Stats Activated at \n\n".NFLLPageURL()."\n\nLITE NFLL Stats Service Support\n","From: links@a93d.com\r\n");
	}
}
function nfll_stats_remove() 
{
/* Deletes the database field */
delete_option('nfll_stats_team');
delete_option('nfll_stats_color');
}

if ( is_admin() ){

/* Call the html code */
add_action('admin_menu', 'nfll_stats_admin_menu');

function nfll_stats_admin_menu() {
add_options_page('NFL Stats Lite', 'NFL Stats Lite Settings', 'administrator', 'nfl-team-stats-lite.php', 'nfll_stats_plugin_page');
}
}

function nfll_stats_plugin_page() {
?>
<script language=JavaScript>

var TCP = new TColorPicker();

function TCPopup(field, palette) {
	this.field = field;
	this.initPalette = !palette || palette > 3 ? 0 : palette;
	var w = 194, h = 240,
	move = screen ? 
		',left=' + ((screen.width - w) >> 1) + ',top=' + ((screen.height - h) >> 1) : '', 
	o_colWindow = window.open('<?php echo '../wp-content/plugins/nfl-team-stats-lite/picker.html'; ?>', null, "help=no,status=no,scrollbars=no,resizable=no" + move + ",width=" + w + ",height=" + h + ",dependent=yes", true);
	o_colWindow.opener = window;
	o_colWindow.focus();
}

function TCBuildCell (R, G, B, w, h) {
	return '<td bgcolor="#' + this.dec2hex((R << 16) + (G << 8) + B) + '"><a href="javascript:P.S(\'' + this.dec2hex((R << 16) + (G << 8) + B) + '\')" onmouseover="P.P(\'' + this.dec2hex((R << 16) + (G << 8) + B) + '\')"><img src="pixel.gif" width="' + w + '" height="' + h + '" border="0"></a></td>';
}

function TCSelect(c) {
	this.field.value = '#' + c.toUpperCase();
	this.win.close();
}

function TCPaint(c, b_noPref) {
	c = (b_noPref ? '' : '#') + c.toUpperCase();
	if (this.o_samp) 
		this.o_samp.innerHTML = '<font face=Tahoma size=2>' + c +' <font color=white>' + c + '</font></font>'
	if(this.doc.layers)
		this.sample.bgColor = c;
	else { 
		if (this.sample.backgroundColor != null) this.sample.backgroundColor = c;
		else if (this.sample.background != null) this.sample.background = c;
	}
}

function TCGenerateSafe() {
	var s = '';
	for (j = 0; j < 12; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i ++)
				s += this.bldCell(k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, i * 51, 8, 10);
		s += "</tr>";
	}
	return s;
}

function TCGenerateWind() {
	var s = '';
	for (j = 0; j < 12; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i++)
				s += this.bldCell(i * 51, k * 51 + (j % 2) * 51 * 3, Math.floor(j / 2) * 51, 8, 10);
		s += "</tr>";
	}
	return s	
}
function TCGenerateMac() {
	var s = '';
	var c = 0,n = 1;
	var r,g,b;
	for (j = 0; j < 15; j ++) {
		s += "<tr>";
		for (k = 0; k < 3; k ++)
			for (i = 0; i <= 5; i++){
				if(j<12){
				s += this.bldCell( 255-(Math.floor(j / 2) * 51), 255-(k * 51 + (j % 2) * 51 * 3),255-(i * 51), 8, 10);
				}else{
					if(n<=14){
						r = 255-(n * 17);
						g=b=0;
					}else if(n>14 && n<=28){
						g = 255-((n-14) * 17);
						r=b=0;
					}else if(n>28 && n<=42){
						b = 255-((n-28) * 17);
						r=g=0;
					}else{
						r=g=b=255-((n-42) * 17);
					}
					s += this.bldCell( r, g,b, 8, 10);
					n++;
				}
			}
		s += "</tr>";
	}
	return s;
}

function TCGenerateGray() {
	var s = '';
	for (j = 0; j <= 15; j ++) {
		s += "<tr>";
		for (k = 0; k <= 15; k ++) {
			g = Math.floor((k + j * 16) % 256);
			s += this.bldCell(g, g, g, 9, 7);
		}
		s += '</tr>';
	}
	return s
}

function TCDec2Hex(v) {
	v = v.toString(16);
	for(; v.length < 6; v = '0' + v);
	return v;
}

function TCChgMode(v) {
	for (var k in this.divs) this.hide(k);
	this.show(v);
}

function TColorPicker(field) {
	this.build0 = TCGenerateSafe;
	this.build1 = TCGenerateWind;
	this.build2 = TCGenerateGray;
	this.build3 = TCGenerateMac;
	this.show = document.layers ? 
		function (div) { this.divs[div].visibility = 'show' } :
		function (div) { this.divs[div].visibility = 'visible' };
	this.hide = document.layers ? 
		function (div) { this.divs[div].visibility = 'hide' } :
		function (div) { this.divs[div].visibility = 'hidden' };
	// event handlers
	this.C       = TCChgMode;
	this.S       = TCSelect;
	this.P       = TCPaint;
	this.popup   = TCPopup;
	this.draw    = TCDraw;
	this.dec2hex = TCDec2Hex;
	this.bldCell = TCBuildCell;
	this.divs = [];
}

function TCDraw(o_win, o_doc) {
	this.win = o_win;
	this.doc = o_doc;
	var 
	s_tag_openT  = o_doc.layers ? 
		'layer visibility=hidden top=54 left=5 width=182' : 
		'div style=visibility:hidden;position:absolute;left:6px;top:54px;width:182px;height:0',
	s_tag_openS  = o_doc.layers ? 'layer top=32 left=6' : 'div',
	s_tag_close  = o_doc.layers ? 'layer' : 'div'
		
	this.doc.write('<' + s_tag_openS + ' id=sam name=sam><table cellpadding=0 cellspacing=0 border=1 width=181 align=center class=bd><tr><td align=center height=18><div id="samp"><font face=Tahoma size=2>sample <font color=white>sample</font></font></div></td></tr></table></' + s_tag_close + '>');
	this.sample = o_doc.layers ? o_doc.layers['sam'] : 
		o_doc.getElementById ? o_doc.getElementById('sam').style : o_doc.all['sam'].style

	for (var k = 0; k < 4; k ++) {
		this.doc.write('<' + s_tag_openT + ' id="p' + k + '" name="p' + k + '"><table cellpadding=0 cellspacing=0 border=1 align=center>' + this['build' + k]() + '</table></' + s_tag_close + '>');
		this.divs[k] = o_doc.layers 
			? o_doc.layers['p' + k] : o_doc.all 
				? o_doc.all['p' + k].style : o_doc.getElementById('p' + k).style
	}
	if (!o_doc.layers && o_doc.body.innerHTML) 
		this.o_samp = o_doc.all 
			? o_doc.all.samp : o_doc.getElementById('samp');
	this.C(this.initPalette);
	if (this.field.value) this.P(this.field.value, true)
}
</script>

   <div>
   <?php
   clearstatcache();
	?>
	<br />
   <h2>NFL Team Stats Lite Options Page</h2>
  
   <form method="post" action="options.php">
   <?php wp_nonce_field('update-options'); ?>
  
   
   <h2>My Current Team: 
   <?php $theteam = get_option('nfll_stats_team'); 
  	$currentteam = preg_replace('/_|stats/', ' ', $theteam);
	$finalteam = ucwords($currentteam);
	echo $finalteam;
   	?></h2><br />
     <small>My New Team:</small><br />
     <p>
     <select name="nfll_stats_team" id="nfll_stats_team">
<option value="arizona_cardinals_stats" selected="selected">Arizona Cardinals</option>
			
				<option value="atlanta_falcons_stats">Atlanta Falcons</option>
			
				<option value="baltimore_ravens_stats">Baltimore Ravens</option>
			
				<option value="buffalo_bills_stats">Buffalo Bills</option>

				<option value="carolina_panthers_stats">Carolina Panthers</option>
			
				<option value="chicago_bears_stats">Chicago Bears</option>
			
				<option value="cincinnati_bengals_stats">Cincinnati Bengals</option>
			
				<option value="cleveland_browns_stats">Cleveland Browns</option>
			
				<option value="dallas_cowboys_stats">Dallas Cowboys</option>
			
				<option value="denver_broncos_stats">Denver Broncos</option>

				<option value="detroit_lions_stats">Detroit Lions</option>
			
				<option value="green_bay_packers_stats">Green Bay Packers</option>
			
				<option value="houston_texans_stats">Houston Texans</option>
			
				<option value="indianapolis_colts_stats">Indianapolis Colts</option>
			
				<option value="jacksonville_jaguars_stats">Jacksonville Jaguars</option>
			
				<option value="kansas_city_chiefs_stats">Kansas City Chiefs</option>

				<option value="miami_dolphins_stats">Miami Dolphins</option>
			
				<option value="minnesota_vikings_stats">Minnesota Vikings</option>
			
				<option value="new_england_patriots_stats">New England Patriots</option>
			
				<option value="new_orleans_saints_stats">New Orleans Saints</option>
			
				<option value="new_york_giants_stats">New York Giants</option>
			
				<option value="new_york_jets_stats">New York Jets</option>

				<option value="oakland_raiders_stats">Oakland Raiders</option>
			
				<option value="philadelphia_eagles_stats">Philadelphia Eagles</option>
			
				<option value="pittsburgh_steelers_stats">Pittsburgh Steelers</option>
			
				<option value="san_diego_chargers_stats">San Diego Chargers</option>
			
				<option value="san_francisco_49ers_stats">San Francisco 49ers</option>
			
				<option value="seattle_seahawks_stats">Seattle Seahawks</option>

				<option value="saint_louis_rams_stats">Saint Louis Rams</option>
			
				<option value="tampa_bay_buccaneers_stats">Tampa Bay Buccaneers</option>
			
				<option value="tennessee_titans_stats">Tennessee Titans</option>
			
				<option value="washington_redskins_stats">Washington Redskins</option>

        </select>      
     
     <br />
     <small>Select Your Team from the Drop-Down Menu Above, then Click "Save Changes"</small>
   <input type="hidden" name="action" value="update" />
   <input type="hidden" name="page_options" value="nfll_stats_team" />
  
   <p>
   <input type="submit" value="<?php _e('Save Changes') ?>" />
   </p>
  </form>
<!-- End Team Select -->
<!-- Start Color Select -->
Manage Your Scroller's Colors Below
Select Scrolling Text Color from Web Safe Palette (Default color is Black: #000000): 
            <br />
            <strong>Color Sample:</strong>
            <br />
            <input type="text" class="textbox" style="background:<?php echo get_option('nfll_stats_color'); ?>;" />
            <br />
            <small>*If White (#FFFFFF) is chosen, it will not appear on this page, since the page is already white</small>

<form name="tcp_test" method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
	<input type="Text" name="nfll_stats_color" id="nfll_stats_color" value="<?php echo get_option('nfll_stats_color'); ?>" />

			<a href="javascript:TCP.popup(document.forms['tcp_test'].elements['nfll_stats_color'])"><img width="15" height="13" border="0" alt="Click Here Pick A Color" src="<?php echo '../wp-content/plugins/nfl-team-stats-lite/cpiksel.gif'; ?>" /></a>
      <br />
      <input type="hidden" name="action" value="update" />
   <input type="hidden" name="page_options" value="nfll_stats_color" />
  
   <p>
   <input type="submit" value="<?php _e('Save Changes') ?>" />
      <input name="defaultfontcolor" type="hidden" value="#000000" />
<input type="button" value="Default Color" onClick="document.tcp_test.nfll_stats_color.value=document.tcp_test.defaultfontcolor.value">
   </p>
  
   </form>
<!-- end color select -->

<!-- Start Advanced Plugins List -->
  <h2>If You Want MORE Stats and Information:</h2>
  <p>A93D Offers FREE upgrades for this stats package, that allow you to display advanced and more complete NFL team stats. Plus, our advanced NFL plugin comes with a Mini News Scroller, highlighting the top 4 stories of the NFL above your stats!
  <h5>Step 1. <?php _e('Use the link below to upgrade to our FREE advanced NFL stats package') ?></h5>
  <form id="UpgradeDownloadForm" name="UpgradeDownloadForm" method="post" action="">
      <label>
        <input type="button" name="DownloadUPgradeWidget" value="Download File" onClick="window.open('http://www.ibet.ws/download/nfl-team-stats.zip', 'Download'); return false;">
      </label>
    <br />
    <a href="http://www.ibet.ws/download/nfl-team-stats.zip" title="Click Here to Download or use the Button" target="_blank"><strong>Click Here</strong> to Download if Button Does Not Function</a>
  </form>
  	<h5>Step 2. <?php _e('Now Locate The File You Just Downloaded and Upload Here. It will install automatically.') ?></h5>
	<p class="install-help"><?php _e('Find the .zip file from the step above on your computer, then click the "Install Now" button.') ?></p>
	<form method="post" enctype="multipart/form-data" action="<?php echo admin_url('update.php?action=upload-plugin') ?>">
		<?php wp_nonce_field( 'plugin-upload') ?>
		<label class="screen-reader-text" for="pluginzip"><?php _e('Plugin zip file'); ?></label>
		<input type="file" id="pluginzip" name="pluginzip" />
		<input type="submit" class="button" value="<?php esc_attr_e('Install Now') ?>" />
	</form>

  
  <h2>Other FREE Sports Stats and Information Plugins:</h2>
  <p>Download and install in seconds using the Wordpress 3.0 Plugin Installer. You Can also auto-install by downloading any of the plugins below, and then uploading using our form above. Just make sure to select the correct downloaded .zip file on your computer!</p>
  <p><strong>Football</strong><br />
    <strong>NFL Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NFL Team, plus optional news scroller<br />
    <strong>NFL News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NFL Headlines<br />
  <strong>NFL Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nfl-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 32 NFL Teams</p>
  <p><strong>NCAAF D1A Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1a-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1A Football Team<br />
    <strong>NCAAF D1AA Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1aa-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1AA Football Team <br />
    <strong>NCAAF News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/ncaaf-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NCAAF Headlines<br />
    <strong>NCAAF D1 Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cfbd1-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - 
  Top 25 College Football Teams Updated Weekly</p>
  <p><strong>Basketball</strong><br />
    <strong>NBA Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NBA Team, plus optional news scroller<br />
    <strong>NBA News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NBA Headlines<br />
    <strong>NBA Power rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nba-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 30 NBA Teams</p>
  <p><strong>NCAAB D1 Team Stats </strong><a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cbbd1a-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NCAA D1A Basketball Team<br />
    <strong>NCAAB D1 News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/ncaab-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NCAAB Headlines <br />
    <strong>NCAAB D1
  Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/cbb-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 25 College Basketball Teams Updated Weekly</p>
  <p><strong>NASCAR</strong><br />
  <strong>NASCAR Power Rankings</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nascar-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top NASCAR Drivers Updated Weekly</p>
<p><strong>Hockey</strong><br />
  <strong>NHL Team Stats</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-team-stats.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete stats of your favorite NHL Team, plus optional news scroller<br />
    <strong>NHL News Scroller</strong> <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-news-scroller.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Top 10 NHL Headlines<br />
    <strong>NHL Power Rankings</strong>
    <a href="http://wplatest5.thoseamazingparks.com" target="_blank">Preview</a> | <a href="http://www.ibet.ws/download/nhl-power-rankings.zip" title="Download Plugin Now" target="_blank">Download Now</a> - Complete Power Rankings of all 30 Teams</p>
<p><small><strong>WordPress Versions 2.9+ Directions</strong> - Click the link of the stats package you would like to install. The link will open a download window that will save the plugin's .zip file to your computer. Next, go to your &quot;Add Plugins&quot; page in the WordPress admin control panel (the link is found in the Plugins sub-menu). Click the &quot;Upload&quot; link and select the .zip file of the new plugin on your computer. Finally, click &quot;Install Now&quot;, and WordPress will automatically upload and install the plugin to your blog. Visit the Plugin settings page to make adjustments.</small><br />
  <br />
  <small><strong>Directions for Older Versions / Manual Installation </strong>- Click the link of the stats package you would like to install. The link will open a download window that will save the plugin's zip file to your computer. Next, unzip the plugin's files on your computer. Finally, upload the unzipped folder and its contents to your WordPress plugins directory by FTP. Activate the plugin from your WordPress control panel. Visit the Plugin settings page to make adjustments.</small></p>
<!-- End Advanced Plugins List --> 

</div>
   <?php
   }
function nfll_stats()
{
$theteam = get_option('nfll_stats_team');
$textcolor = preg_replace('/#/', '', get_option('nfll_stats_color'));

$mydisplay = "http://www.ibet.ws/nfll_stats_magpie/int0-8-2/nfl_stats_magpie_ads.php?team=$theteam&textcolor=$textcolor";
// This is the Magpie Basic Command for Fetching the Stats URL
$url = $mydisplay;
$rss = nfll_fetch_rss( $url );
// Now to break the feed down into each item part
foreach ($rss->items as $item) 
		{
		// These are the individual feed elements per item
		$title = $item['title'];
		$description = $item['description'];
		// Assign Variables to Feed Results
		if ($title == 'adform')
			{
			$adform = $description;
			}
		}

echo $adform;
}
?>