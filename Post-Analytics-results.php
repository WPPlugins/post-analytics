<?php

// get data from DB
$options = get_option('Post_Analytics_settings');

// get the gapi class
require 'gapi-1.3/gapi.class.php';

/* Define variables */
$ga_email = $options['email'];
$ga_password = $options['password'];
$ga_profile_id = $options['profile_id'];

// get post slug
global $post;
$ga_url = urldecode($post->post_name); 


// Create a new Google Analytics request to get pagestats

$ga = new gapi($ga_email,$ga_password);

// get results for totals calculations

 $dimensions = array('pagePath'); 
 $metrics = array(
					'pageviews','entrances',
					'uniquePageviews','avgTimeOnPage','visitBounceRate', 'exitRate','pageValue',
				);
 $sortMetric=null;
 $filter= null;//'pagePath=@'.$ga_url; 
 $start_date=null;
 $end_date=null;
 $start_index=null;
 $max_results=null;


$ga->requestReportData($ga_profile_id, $dimensions, $metrics, $sortMetric, $filter, $start_date, $end_date, $start_index, $max_results);


$results = $ga->getResults();

// second request get domain stats

 $dimensions = array('hostname'); 
 $metrics = array(					
					'avgTimeOnSite','pageviewsPerVisit','pageValue','visitBounceRate'
				);
 $sortMetric=null;
 $filter= null;//'pagePath=@'.$ga_url; 
 $start_date=null;
 $end_date=null;
 $start_index=null;
 $max_results=null;


$ga->requestReportData($ga_profile_id, $dimensions, $metrics, $sortMetric, $filter, $start_date, $end_date, $start_index, $max_results);


$resultsTotal = $ga->getResults();

// third request get keyword for specific page

 $dimensions = array('pagePath','keyword'); 
 $metrics = array(					
					'organicSearches','visits'
				);
 $sortMetric=null;
 $filter='pagePath=@/'.$ga_url.'/'; 
 $start_date=null;
 $end_date=null;
 $start_index=null;
 $max_results=null;


$ga->requestReportData($ga_profile_id, $dimensions, $metrics, $sortMetric, $filter, $start_date, $end_date, $start_index, $max_results);


$resultskeyword = $ga->getResults();

// get results for specific post
	
	
	
	$dimensions = array('pagePath'); 
	$metrics = array(
						'pageviews','entrances',
						'uniquePageviews','avgTimeOnPage','visitBounceRate', 'exitRate','pageValue',
					);
	$sortMetric=null;
	$filter='pagePath=@/'.$ga_url.'/'; 
	$start_date=null;
	$end_date=null;
	$start_index=null;
	$max_results=null;


$ga->requestReportData($ga_profile_id, $dimensions, $metrics, $sortMetric, $filter, $start_date, $end_date, $start_index, $max_results);


$post_results = $ga->getResults();

//convert seconds into minutes. This will be useful for displaying the average time on page, which by default returns the result in seconds.
 
function secondMinute($seconds) {
  $minResult = floor($seconds/60);
  if($minResult < 10){$minResult = 0 . $minResult;}
  $secResult = ($seconds/60 - $minResult)*60;
  if($secResult < 10){$secResult = 0 . round($secResult);}
  else { $secResult = round($secResult); }
  return $minResult.":".$secResult;
}

// loop to get totals
$i = 0;	
foreach($results as $result) {

  $i++;
	
  $TotalPageviews = $result->getPageviews() + $TotalPageviews;
  $TotalUniquepageviews = $result->getUniquepageviews() + $TotalUniquepageviews;
  $TotalAvgtimeonpage = $result->getAvgtimeonpage() + $TotalAvgtimeonpage;
  $Totalentrances = $result->getentrances() + $Totalentrances;
  $TotalexitRate = $result->getexitRate() + $TotalexitRate;
  
}
?>