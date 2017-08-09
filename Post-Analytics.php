<?php
/*
Plugin Name: Post-Analytics
Plugin URI: www.dossihost.net
Description: Add SEO stats meta box to every page/post.
Version: 1.01
Author: DrMosko
Author URI: www.dossihost.net

Text Domain: Post-Analytics
Domain Path: /lang
*/
/* This script uses https://code.google.com/p/gapi-google-analytics-php-interface/ */

// include the admin page
include( plugin_dir_path( __FILE__ ) . 'Post-Analytics-admin-page.php');

// load language translation

function Post_Analytics_lng()  
{  
    // Localization  
    load_plugin_textdomain('Post-Analytics', false, dirname(plugin_basename(__FILE__)) . '/lang');  
}  
  
// Add actions  
add_action('init', 'Post_Analytics_lng');  


// add the WP_SEO_Post_stats to edit post/page

function Post_Analytics_after_title() {
	$scr = get_current_screen();
	if ( ($scr->base !== 'post' && $scr->base !== 'page') || $scr->action === 'add' )
    return;
	
	// include results from gapi
	include( plugin_dir_path( __FILE__ ) . 'Post-Analytics-results.php');
	
	// start content	
	echo '<div id="main-analtyics">';
	
	// print serach results
	if (count($resultskeyword) != 0 ) { // check if there r values 
		echo '<div id="post-analtyics-search">';
		echo '<div class="post-analtyics-title">Search results for this page</div>';
		echo '<div class="cell"><span>Organic Searches</span><br /><strong>'.$resultskeyword[0]->getorganicSearches().'</strong></div>';
		echo '<div class="cell"><span>keyword</span><br /><strong>'. $resultskeyword[0]->getkeyword() .'</strong></div>';
		echo '<div class="cell"><span>visitis</span><br /><strong>'.$resultskeyword[0]->getvisits().'</strong></div>';
		echo '</div>';
	}else {
	echo '<div id="post-analtyics"><div class="post-analtyics-title">There arent values for keywords</div></div>';
	}
	
	// print post analtyics
	if (count($post_results) != 0 ) { // check if there r values for this post and print them
	echo '<div id="post-analtyics">';
	echo '<div class="post-analtyics-title">post-analtyics</div>';
	echo '<div class="cell"><span>Pageviews</span><br /><strong>'.number_format($post_results[0]->getPageviews()).'</strong></div>';
	echo '<div class="cell"><span>Unique pageviews</span><br /><strong>'.number_format($post_results[0]->getUniquepageviews()).'</strong></div>';
	echo '<div class="cell"><span>Avg time on page</span><br /><strong>'.secondMinute($post_results[0]->getAvgtimeonpage()).'</strong></div>';
	echo '<div class="cell"><span>EntranceRate rate</span><br /><strong>'.number_format($post_results[0]->getentrances()).'</strong></div>';
	echo '<div class="cell"><span>Bounce Rate</span><br /><strong>'.round($post_results[0]->getvisitBounceRate(), 4).'%</strong></div>';
	echo '<div class="cell"><span>Exit rate</span><br /><strong>'.round($post_results[0]->getexitRate(), 4).'%</strong></div>';
	echo '<div class="cell"><span>pageValue</span><br /><strong>'.number_format($post_results[0]->getpageValue()).'</strong></div>';
	echo '</div>';
	}else {
	echo '<div id="post-analtyics"><div class="post-analtyics-title">There arent values for this post</div></div>';
	}
	
	// site global analtyics	
	echo '<div id="Global-Site-analtyics">';
	echo '<div class="post-analtyics-title">Global Site Stats</div>';
	echo '<div class="cell"><span>Site Pageviews</span><br /><strong>'.$TotalPageviews.'</strong></div>';
	echo '<div class="cell"><span>Unique pageviews</span><br /><strong>'.$TotalUniquepageviews.'</strong></div>';
	echo '<div class="cell"><span>Total Entrances</span><br /><strong>'.$Totalentrances.'</strong></div>';
	echo '<div class="cell"><span>Avg timeonpage</span><br /><strong>'.(secondMinute(($TotalAvgtimeonpage/$i))).'</strong></div>';
	if (count($post_results) != 0 ) { // check if there r values for this post and print them
	echo '<div class="cell"><span>Exit rate</span><br /><strong>'.round($post_results[0]->getexitRate(), 4).'%</strong></div>';
	}
	echo '<div class="cell"><span>Avg exitRate</span><br /><strong>'.round(($TotalexitRate/$i), 4).'%</strong></div>';
	echo '<div class="cell"><span>Avg TimeOnSite</span><br /><strong>'.secondMinute($resultsTotal[0]->getavgTimeOnSite()).'</strong></div>';
	echo '<div class="cell"><span>pageviewsPerVisit</span><br /><strong>'.number_format($resultsTotal[0]->getpageviewsPerVisit()).'</strong></div>';
	echo '<div class="cell"><span>pageValue</span><br /><strong>'.number_format($resultsTotal[0]->getpageValue()).'</strong></div>';
	echo '<div class="cell"><span>Site avg Bounce Rate</span><br /><strong>'.round($resultsTotal[0]->getvisitBounceRate(), 4).'%</strong></div>';
	echo '</div>';
	
	
	echo '</div>';
	
}

add_action('edit_form_after_title','Post_Analytics_after_title');


// add css to edit posts
add_action('admin_print_styles-post.php', 'Post_Analytics_css');
function Post_Analytics_css() {
  wp_enqueue_style( 'Post_Analytics_style', plugins_url('/css/stylesheet.css', __FILE__) );
}

// create default values when user activate plugin
function Post_Analytics_activate() {
	
		delete_option('Post_Analytics_settings');				
			$settings = array(
			'email' => ' ',
			'password' => '***********',
			'profile_id' => '***********',
			
			);
	
			add_option('Post_Analytics_settings', $settings);
	
}
register_activation_hook( __FILE__, 'Post_Analytics_activate' );

// delete default values when user deactivate plugin
function Post_Analytics_deactivate() {
		
	delete_option('Post_Analytics_settings');
	
	
}
register_deactivation_hook( __FILE__, 'Post_Analytics_deactivate' );



?>