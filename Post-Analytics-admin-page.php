<?php
// admin page
function Post_Analytics_admin() {   
   	add_options_page(__('Post-Analytics', 'Post-Analytics'), 'Post-Analytics', 'manage_options', 'Post_Analytics_admin', 'Post_Analytics_admin_options');
}  
add_action('admin_menu', 'Post_Analytics_admin');  

// add the admin settings and such
add_action('admin_init', 'Post_Analytics_admin_init');
function Post_Analytics_admin_init(){
	register_setting( 'Post_Analytics_settings', 'Post_Analytics_settings', 'Post_Analytics_settings_validate');
	add_settings_section('Post_Analytics_settings_main', 'Post SEOstats settings', 'Post_Analytics_settings_main_section', 'Post_Analytics');
	add_settings_field('Google_Account_Email_Address', __('Google Account Email Address','Post-Analytics'), 'Post_Analytics_settings_input_email', 'Post_Analytics', 'Post_Analytics_settings_main');
	add_settings_field('Google_Account_Password', __('Google Account Password','Post-Analytics'), 'Post_Analytics_settings_input_password', 'Post_Analytics', 'Post_Analytics_settings_main');
	add_settings_field('profile_id', __('Profile-Id Number','Post-Analytics'), 'Post_Analytics_settings_input_profile_id', 'Post_Analytics', 'Post_Analytics_settings_main');
}
function Post_Analytics_settings_main_section() {
	
	echo __(' ','Post-Analytics').'<br/>';
	
}
function Post_Analytics_settings_input_email() { 
	$options = get_option('Post_Analytics_settings');
	//echo __('Google Account Email Address','Post-Analytics').'<br/>';
	echo "<input id='Post_Analytics_settings_input_email' name='Post_Analytics_settings[email]' size='40' type='email' value='{$options['email']}' />";
}
function Post_Analytics_settings_input_password() {
	$options = get_option('Post_Analytics_settings');
	//echo __('Google Account Password','Post-Analytics').'<br/>';
	echo "<input id='Post_Analytics_settings_input_password' name='Post_Analytics_settings[password]' size='40' type='password' value='{$options['password']}' />";
}
function Post_Analytics_settings_input_profile_id() {
	$options = get_option('Post_Analytics_settings');
	
	//echo __('Profile-Id Number','Post-Analytics').'<br/>';
	echo "<input id='Post_Analytics_settings_input_profile_id' name='Post_Analytics_settings[profile_id]' size='40' type='text' value='{$options['profile_id']}' />";
	echo '<br/><br/><strong>'.__('How can I get my profile id number?','Post-Analytics').'</strong><br/><br/>';
	echo __('Enter your google analytics account and check your browser URL','Post-Analytics').'<br/>';
	echo  __('OLD VERSION analytics the ID=xxxxxxxx is the profile ID','Post-Analytics').'<br/>'
            .'https://www.google.com/analytics/reporting/?reset=1&id=XXXXXXXX&pdr=20110702-20110801<br/><br/>'.__('
			For the NEW VERSION analytic page it is the number at the end of the URL starting with p','Post-Analytics').'<br/> '
			.'https://www.google.com/analytics/web/#home/a11345062w43527078pXXXXXXXX/'.'<br/> ';
	
}
// validate   input
function Post_Analytics_settings_validate($input) {
	$options = get_option('Post_Analytics_settings');
	$options['email'] = wp_filter_nohtml_kses( trim( $input['email'] ) ) ;
	$options['password'] =  wp_filter_nohtml_kses( trim( $input['password']) ) ;
	$options['profile_id'] = intval( wp_filter_nohtml_kses( trim( $input['profile_id']) ) );
		

if   ( !is_email( $options['email']  ) ) { 
	$options['email'] = ' ';
	add_settings_error(
				'Post_Analytics_validate_fail',           // setting title
				'Post_Analytics_validate_fail',            // error ID
				__('Invalid email address','Post-Analytics'),   // error message
				'error'                        // type of message
			);		
}

if(   $options['password'] == '***********' )  { 
	$options['password'] = '***********';
	add_settings_error(
				'Post_Analytics_fail',           // setting title
				'Post_Analytics_fail',            // error ID
				__('Invalid password ','Post-Analytics'),   // error message
				'error'                        // type of message
			);		
}
if(  ( !is_numeric( $options['profile_id'] ) )  ||   ( $options['profile_id'] == '***********'  ) )  { 
	$options['profile_id'] = '***********';
	add_settings_error(
				'Post_Analytics_fail',           // setting title
				'Post_Analytics_fail',            // error ID
				__('Invalid password profile-id','Post-Analytics'),   // error message
				'error'                        // type of message
			);		
}

return $options;
}

function Post_Analytics_admin_options() {  // display the admin options page
?>
	<?php $options = get_option('Post_Analytics_settings');	?>
	<h2>Post-Analytics</h2>
	<?php
		echo '<br/><h2>' . __( '1$ is not much for this plugin pls make a donation','Post-Analytics' ) . '</h2><br/>';
		
	?>		
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="JUHAECJX5CEW8">
	<input type="image" src="https://www.paypalobjects.com/en_US/IL/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>

	
	<form action="options.php" method="post">
	<?php settings_fields( 'Post_Analytics_settings' ); ?>
	<?php do_settings_sections( 'Post_Analytics'  ); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
	<?php
		echo '<br/><div><h2>' . __( 'presented info in pages : ','Post-Analytics' ) . '</h2><br/>';
		
	?>	
	
	<ul>Search results for current page edited : </ul>
	<li>Organic Searches</li>
	<li>keyword</li>
	<li>visitis</li>
	<ul>post-analtyics for current page edited :</ul>
	<li>Pageviews</li>
	<li>Unique ulageviews</li>
	<li>Avg time on ulage</li>
	<li>EntranceRate rate</li>
	<li>Bounce Rate</li>
	<li>Exit rate</li>
	<li>ulageValue</li>
	<ul>Global Site analtyics data :</ul>
	<li>Site Pageviews</li>
	<li>Unique pageviews</li>
	<li>Total Entrances</li>
	<li>Avg timeonpage</li>
	<li>Exit rate</li>
	<li>Avg exitRate</li>
	<li>Avg TimeOnSite</li>
	<li>pageviewsPerVisit</li>
	<li>pageValue</li>
	<li>Site avg Bounce Rate</li>
	
	
	</div>
	
	
 
<?php  
}  
?>