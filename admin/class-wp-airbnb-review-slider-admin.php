<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Airbnb_Review
 * @subpackage WP_Airbnb_Review/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Airbnb_Review
 * @subpackage WP_Airbnb_Review/admin
 * @author     Your Name <email@example.com>
 */
class WP_Airbnb_Review_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugintoken    The ID of this plugin.
	 */
	private $plugintoken;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugintoken       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugintoken, $version ) {

		$this->_token = $plugintoken;
		//$this->version = $version;
		//for testing==============
		$this->version = time();
		//===================
				

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Airbnb_Review_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Airbnb_Review_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		//only load for this plugin wp_airbnb-settings-pricing
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_airbnb-reviews" || $_GET['page']=="wp_airbnb-templates_posts" || $_GET['page']=="wp_airbnb-get_airbnb" || $_GET['page']=="wp_airbnb-get_pro"){
			wp_enqueue_style( $this->_token, plugin_dir_url( __FILE__ ) . 'css/wpairbnb_admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->_token."_wpairbnb_w3", plugin_dir_url( __FILE__ ) . 'css/wpairbnb_w3.css', array(), $this->version, 'all' );
			}
			//load template styles for wp_airbnb-templates_posts page
			if($_GET['page']=="wp_airbnb-templates_posts"|| $_GET['page']=="wp_airbnb-get_pro"){
				//enque template styles for preview
				wp_enqueue_style( $this->_token."_style1", plugin_dir_url(dirname(__FILE__)) . 'public/css/wprev-public_template1.css', array(), $this->version, 'all' );
			}
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Airbnb_Review_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Airbnb_Review_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		

		//scripts for all pages in this plugin
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_airbnb-reviews" || $_GET['page']=="wp_airbnb-templates_posts" || $_GET['page']=="wp_airbnb-get_airbnb" || $_GET['page']=="wp_airbnb-get_pro"){
				//pop-up script
				wp_register_script( 'simple-popup-js',  plugin_dir_url( __FILE__ ) . 'js/wpairbnb_simple-popup.min.js' , '', $this->version, false );
				wp_enqueue_script( 'simple-popup-js' );
				
			}
		}
		
	
		//scripts for review list page
		if(isset($_GET['page'])){
			if($_GET['page']=="wp_airbnb-reviews"){
				//admin js
				wp_enqueue_script('wpairbnb_review_list_page-js', plugin_dir_url( __FILE__ ) . 'js/wpairbnb_review_list_page.js', array( 'jquery','media-upload','thickbox' ), $this->version, false );
				//used for ajax
				wp_localize_script('wpairbnb_review_list_page-js', 'adminjs_script_vars', 
					array(
					'wpairbnb_nonce'=> wp_create_nonce('randomnoncestring')
					)
				);
				
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
		 
				wp_enqueue_script('media-upload');
				wp_enqueue_script('wptuts-upload');

			}
			
			//scripts for templates posts page
			if($_GET['page']=="wp_airbnb-templates_posts"){
			
				//admin js
				wp_enqueue_script('wpairbnb_templates_posts_page-js', plugin_dir_url( __FILE__ ) . 'js/wpairbnb_templates_posts_page.js', array( 'jquery' ), $this->version, false );
				//used for ajax
				wp_localize_script('wpairbnb_templates_posts_page-js', 'adminjs_script_vars', 
					array(
					'wpairbnb_nonce'=> wp_create_nonce('randomnoncestring'),
					'pluginsUrl' => wprev_airbnb_plugin_url
					)
				);
 				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
				
				//add color picker here
				wp_enqueue_style( 'wp-color-picker' );
				//enque alpha color add-on wpairbnb-wp-color-picker-alpha.js
				wp_enqueue_script( 'wp-color-picker-alpha', plugin_dir_url( __FILE__ ) . 'js/wpairbnb-wp-color-picker-alpha.js', array( 'wp-color-picker' ), '2.1.2', false );

			}
		}
		
	}
	
	public function add_menu_pages() {

		/**
		 * adds the menu pages to wordpress
		 */

		$page_title = 'WP Airbnb Reviews : Reviews List';
		$menu_title = 'WP Airbnb';
		$capability = 'manage_options';
		$menu_slug = 'wp_airbnb-reviews';
		
		// Now add the submenu page for the actual reviews list
		$submenu_page_title = 'WP Reviews Pro : Reviews List';
		$submenu_title = 'Reviews List';
		$submenu_slug = 'wp_airbnb-reviews';
		
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this,'wp_airbnb_reviews'),'dashicons-star-half');
		
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_airbnb_reviews'));
		
		
		//add_menu_page($page_title, $menu_title, $capability, $menu_slug, array($this,'wp_airbnb_settings'),'dashicons-star-half');
		
		// We add this submenu page with the same slug as the parent to ensure we don't get duplicates
		//$sub_menu_title = 'Get FB Reviews';
		//add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, array($this,'wp_airbnb_settings'));
		
		// Now add the submenu page for airbnb
		$submenu_page_title = 'WP Reviews Pro : Airbnb';
		$submenu_title = 'Get Airbnb Reviews';
		$submenu_slug = 'wp_airbnb-get_airbnb';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_airbnb_getairbnb'));
		

		
		// Now add the submenu page for the reviews templates
		$submenu_page_title = 'WP Reviews Pro : Templates';
		$submenu_title = 'Templates';
		$submenu_slug = 'wp_airbnb-templates_posts';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_airbnb_templates_posts'));
		
		// Now add the submenu page for the reviews templates
		$submenu_page_title = 'WP FB Reviews : Upgrade';
		$submenu_title = 'Get Pro';
		$submenu_slug = 'wp_airbnb-get_pro';
		add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, array($this,'wp_fb_getpro'));
	

	}
	
	public function wp_airbnb_reviews() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/review_list.php';
	}
	
	public function wp_airbnb_templates_posts() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/templates_posts.php';
	}
	public function wp_airbnb_getairbnb() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/get_airbnb.php';
	}
	public function wp_fb_getpro() {
		require_once plugin_dir_path( __FILE__ ) . '/partials/get_pro.php';
	}

	/**
	 * custom option and settings on airbnb page
	 */
	 //===========start airbnb page settings===========================================================
	public function wpairbnb_airbnb_settings_init()
	{
	
		// register a new setting for "wp_airbnb-get_airbnb" page
		register_setting('wp_airbnb-get_airbnb', 'wpairbnb_airbnb_settings');
		
		// register a new section in the "wp_airbnb-get_airbnb" page
		add_settings_section(
			'wpairbnb_airbnb_section_developers',
			'',
			array($this,'wpairbnb_airbnb_section_developers_cb'),
			'wp_airbnb-get_airbnb'
		);
		
		//register airbnb business url input field
		add_settings_field(
			'airbnb_business_url', // as of WP 4.6 this value is used only internally
			'Airbnb Listing URL',
			array($this,'wpairbnb_field_airbnb_business_id_cb'),
			'wp_airbnb-get_airbnb',
			'wpairbnb_airbnb_section_developers',
			[
				'label_for'         => 'airbnb_business_url',
				'class'             => 'wpairbnb_row',
				'wpairbnb_custom_data' => 'custom',
			]
		);

		//Turn on Airbnb Reviews Downloader
		add_settings_field("airbnb_radio", "Turn On Airbnb Reviews", array($this,'airbnb_radio_display'), "wp_airbnb-get_airbnb", "wpairbnb_airbnb_section_developers",
			[
				'label_for'         => 'airbnb_radio',
				'class'             => 'wpairbnb_row',
				'wpairbnb_custom_data' => 'custom',
			]); 
	
	}
	//==== developers section cb ====
	public function wpairbnb_airbnb_section_developers_cb($args)
	{
		//echos out at top of section
		echo "<p>Use this page to download your newest Airbnb business reviews and save them in your Wordpress database. They will show up on the Review List page once downloaded. Any new reviews you get are downloaded once a day. The Pro version will allow you to download your latest 20 reviews and get reviews from up to 15 listings.</p>";
	}
	
	//==== field cb =====
	public function wpairbnb_field_airbnb_business_id_cb($args)
	{
		// get the value of the setting we've registered with register_setting()
		$options = get_option('wpairbnb_airbnb_settings');

		// output the field
		?>
		<input id="<?= esc_attr($args['label_for']); ?>" data-custom="<?= esc_attr($args['wpairbnb_custom_data']); ?>" type="text" name="wpairbnb_airbnb_settings[<?= esc_attr($args['label_for']); ?>]" placeholder="" value="<?php echo $options[$args['label_for']]; ?>">
		
		<p class="description">
			<?= esc_html__('Copy and paste the Airbnb URL for your location and click Save Settings. Example:', 'wp_airbnb-settings'); ?>
			</br>
			<?= esc_html__('https://www.airbnb.com/rooms/12351', 'wp_airbnb-settings'); ?>
			</br>
		</p>
		<?php
	}
	public function airbnb_radio_display($args)
		{
		$options = get_option('wpairbnb_airbnb_settings');
		
		   ?>
				<input type="radio" name="wpairbnb_airbnb_settings[<?= esc_attr($args['label_for']); ?>]" value="yes" <?php checked('yes', $options[$args['label_for']], true); ?>>Yes&nbsp;&nbsp;&nbsp;
				<input type="radio" name="wpairbnb_airbnb_settings[<?= esc_attr($args['label_for']); ?>]" value="no" <?php checked('no', $options[$args['label_for']], true); ?>>No
		   <?php
		}
	//=======end airbnb page settings========================================================

	
	/**
	 * Store reviews in table, called from javascript file admin.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpairbnb_process_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpairbnb_nonce');
		
		$postreviewarray = $_POST['postreviewarray'];
		
		//var_dump($postreviewarray);

		//loop through each one and insert in to db
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpairbnb_reviews';
		
		$stats = array();
		
		foreach($postreviewarray as $item) { //foreach element in $arr
			$pageid = $item['pageid'];
			$pagename = $item['pagename'];
			$created_time = $item['created_time'];
			$created_time_stamp = strtotime($created_time);
			$reviewer_name = $item['reviewer_name'];
			$reviewer_id = $item['reviewer_id'];
			$rating = $item['rating'];
			$review_text = $item['review_text'];
			$review_length = str_word_count($review_text);
			$rtype = $item['type'];
			
			//check to see if row is in db already
			$checkrow = $wpdb->get_row( "SELECT id FROM ".$table_name." WHERE created_time = '$created_time'" );
			if ( null === $checkrow ) {
				$stats[] =array( 
						'pageid' => $pageid, 
						'pagename' => $pagename, 
						'created_time' => $created_time,
						'created_time_stamp' => strtotime($created_time),
						'reviewer_name' => $reviewer_name,
						'reviewer_id' => $reviewer_id,
						'rating' => $rating,
						'review_text' => $review_text,
						'hide' => '',
						'review_length' => $review_length,
						'type' => $rtype
					);
			}
		}
		$i = 0;
		$insertnum = 0;
		foreach ( $stats as $stat ){
			$insertnum = $wpdb->insert( $table_name, $stat );
			$i=$i + 1;
		}
	
		$insertid = $wpdb->insert_id;

		//header('Content-Type: application/json');
		echo $insertnum."-".$insertid."-".$i;

		die();
	}

	/**
	 * Hides or deletes reviews in table, called from javascript file wpairbnb_review_list_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpairbnb_hidereview_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpairbnb_nonce');
		
		$rid = intval($_POST['reviewid']);
		$myaction = $_POST['myaction'];

		//loop through each one and insert in to db
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpairbnb_reviews';
		
		//check to see if we are deleting or just hiding or showing
		if($myaction=="hideshow"){
			//grab review and see if it is hidden or not
			$myreview = $wpdb->get_row( "SELECT * FROM $table_name WHERE id = $rid" );
			
			//pull array from options table of airbnb hidden
			$airbnbhidden = get_option( 'wpairbnb_hidden_reviews' );
			if(!$airbnbhidden){
				$airbnbhiddenarray = array('');
			} else {
				$airbnbhiddenarray = json_decode($airbnbhidden,true);
			}
			if(!is_array($airbnbhiddenarray)){
				$airbnbhiddenarray = array('');
			}
			$this_airbnb_val = $myreview->reviewer_name."-".$myreview->created_time_stamp."-".$myreview->review_length."-".$myreview->type."-".$myreview->rating;

			if($myreview->hide=="yes"){
				//already hidden need to show
				$newvalue = "";
				
				//remove from $airbnbhidden
				if(($key = array_search($this_airbnb_val, $airbnbhiddenarray)) !== false) {
					unset($airbnbhiddenarray[$key]);
				}
				
			} else {
				//shown, need to hide
				$newvalue = "yes";
				
				//need to update Airbnb hidden ids in options table here array of name,time,count,type
				 array_push($airbnbhiddenarray,$this_airbnb_val);
			}
			//update hidden airbnb reviews option, use this when downloading airbnb reviews so we can re-hide them each download
			$airbnbhiddenjson=json_encode($airbnbhiddenarray);
			update_option( 'wpairbnb_hidden_reviews', $airbnbhiddenjson );
			
			//update database review table to hide this one
			$data = array( 
				'hide' => "$newvalue"
				);
			$format = array( 
					'%s'
				); 
			$updatetempquery = $wpdb->update($table_name, $data, array( 'id' => $rid ), $format, array( '%d' ));
			if($updatetempquery>0){
				echo $rid."-".$myaction."-".$newvalue;
			} else {
				echo $rid."-".$myaction."-fail";
			}

		}
		if($myaction=="deleterev"){
			$deletereview = $wpdb->delete( $table_name, array( 'id' => $rid ), array( '%d' ) );
			if($deletereview>0){
				echo $rid."-".$myaction."-success";
			} else {
				echo $rid."-".$myaction."-fail";
			}
		
		}

		die();
	}
	
	/**
	 * Ajax, retrieves reviews from table, called from javascript file wpairbnb_templates_posts_page.js
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function wpairbnb_getreviews_ajax(){
	//ini_set('display_errors',1);  
	//error_reporting(E_ALL);
		
		check_ajax_referer('randomnoncestring', 'wpairbnb_nonce');
		$filtertext = htmlentities($_POST['filtertext']);
		$filterrating = htmlentities($_POST['filterrating']);
		$filterrating = intval($filterrating);
		$curselrevs = $_POST['curselrevs'];
		
		//perform db search and return results
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpairbnb_reviews';
		$rowsperpage = 20;
		
		//pagenumber
		if(isset($_POST['pnum'])){
		$temppagenum = $_POST['pnum'];
		} else {
		$temppagenum ="";
		}
		if ( $temppagenum=="") {
			$pagenum = 1;
		} else if(is_numeric($temppagenum)){
			$pagenum = intval($temppagenum);
		}
		
		//sort direction
		if($_POST['sortdir']=="ASC" || $_POST['sortdir']=="DESC"){
			$sortdir = $_POST['sortdir'];
		} else {
			$sortdir = "DESC";
		}

		//make sure sortby is valid
		if(!isset($_POST['sortby'])){
			$_POST['sortby'] = "";
		}
		$allowed_keys = ['created_time_stamp', 'reviewer_name', 'rating', 'review_length', 'pagename', 'type' , 'hide'];
		$checkorderby = sanitize_key($_POST['sortby']);
	
		if(in_array($checkorderby, $allowed_keys, true) && $_POST['sortby']!=""){
			$sorttable = $_POST['sortby']. " ";
		} else {
			$sorttable = "created_time_stamp ";
		}
		if($_POST['sortdir']=="ASC" || $_POST['sortdir']=="DESC"){
			$sortdir = $_POST['sortdir'];
		} else {
			$sortdir = "DESC";
		}
		
		//get reviews from db
		$lowlimit = ($pagenum - 1) * $rowsperpage;
		$tablelimit = $lowlimit.",".$rowsperpage;
		
		if($filterrating>0){
			$filterratingtext = "rating = ".$filterrating;
		} else {
			$filterratingtext = "rating > 0";
		}
			
		//check to see if looking for previously selected only
		if (is_array($curselrevs)){
			$query = "SELECT * FROM ".$table_name." WHERE id IN (";
			//loop array and add to query
			$n=1;
			foreach ($curselrevs as $value) {
				if($value!=""){
					if(count($curselrevs)==$n){
						$query = $query." $value";
					} else {
						$query = $query." $value,";
					}
				}
				$n++;
			}
			$query = $query.")";
			//echo $query ;

			$reviewsrows = $wpdb->get_results($query);
			$hidepagination = true;
			$hidesearch = true;
		} else {
		

			//if filtertext set then use different query
			if($filtertext!=""){
				$reviewsrows = $wpdb->get_results("SELECT * FROM ".$table_name."
					WHERE (reviewer_name LIKE '%".$filtertext."%' or review_text LIKE '%".$filtertext."%') AND ".$filterratingtext."
					ORDER BY ".$sorttable." ".$sortdir." 
					LIMIT ".$tablelimit." "
				);
				$hidepagination = true;
			} else {
				$reviewsrows = $wpdb->get_results(
					$wpdb->prepare("SELECT * FROM ".$table_name."
					WHERE id>%d AND ".$filterratingtext."
					ORDER BY ".$sorttable." ".$sortdir." 
					LIMIT ".$tablelimit." ", "0")
				);
			}
		}
		
		//total number of rows
		$reviewtotalcount = $wpdb->get_var( "SELECT COUNT(*) FROM ".$table_name." WHERE id>1 AND ".$filterratingtext );
		//total pages
		$totalpages = ceil($reviewtotalcount/$rowsperpage);
		
		$reviewsrows['reviewtotalcount']=$reviewtotalcount;
		$reviewsrows['totalpages']=$totalpages;
		$reviewsrows['pagenum']=$pagenum;
		if($hidepagination){
			$reviewsrows['reviewtotalcount']=0;
			//$reviewsrows['totalpages']=0;
			//$reviewsrows['pagenum']=0;
		}
		if($hidesearch){
			//$reviewsrows['reviewtotalcount']=0;
			$reviewsrows['totalpages']=0;
			//$reviewsrows['pagenum']=0;
		}
		
		$results = json_encode($reviewsrows);
		echo $results;

		die();
	}
	
	
	
	/**
	 * replaces insert into post text on media uploader when uploading reviewer avatar
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wpairbnb_media_text() {
		global $pagenow;
		if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			// Now we'll replace the 'Insert into Post Button' inside Thickbox
			add_filter( 'gettext', array($this,'replace_thickbox_text') , 1, 3 );
		}
	}
	 
	public function replace_thickbox_text($translated_text, $text, $domain) {
		if ('Insert into Post' == $text) {
			$referer = strpos( wp_get_referer(), 'wp_airbnb-reviews' );
			if ( $referer != '' ) {
				return __('Use as Reviewer Avatar', 'wp-airbnb-review-slider' );
			}
		}
		return $translated_text;
	}
	

	/**
	 * download csv file of reviews
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wpairbnb_download_csv() {
      global $pagenow;
      if ($pagenow=='admin.php' && current_user_can('export') && isset($_GET['taction']) && $_GET['taction']=='downloadallrevs' && $_GET['page']=='wp_airbnb-reviews') {
        header("Content-type: application/x-msdownload");
        header("Content-Disposition: attachment; filename=reviewdata.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

		global $wpdb;
		$table_name = $wpdb->prefix . 'wpairbnb_reviews';		
		$downloadreviewsrows = $wpdb->get_results(
				$wpdb->prepare("SELECT * FROM ".$table_name."
				WHERE id>%d ", "0"),'ARRAY_A'
			);
		$file = fopen('php://output', 'w');
		$delimiter=";";
		
		foreach ($downloadreviewsrows as $line) {
		    fputcsv($file, $line, $delimiter);
		}

        exit();
      }
    }	
	
	/**
	 * adds drop down menu of templates on post edit screen
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	//add_action('media_buttons','add_sc_select',11);
	public function add_sc_select(){
		//get id's and names of templates that are post type 
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpairbnb_post_templates';
		$currentforms = $wpdb->get_results("SELECT id, title, template_type FROM $table_name WHERE template_type = 'post'");
		if(count($currentforms)>0){
		echo '&nbsp;<select id="wprs_sc_select"><option value="select">Review Template</option>';
		foreach ( $currentforms as $currentform ){
			$shortcodes_list .= '<option value="[wpairbnb_usetemplate tid=\''.$currentform->id.'\']">'.$currentform->title.'</option>';
		}
		 echo $shortcodes_list;
		 echo '</select>';
		}
	}
	//add_action('admin_head', 'button_js');
	public function button_js() {
			echo '<script type="text/javascript">
			jQuery(document).ready(function(){
			   jQuery("#wprs_sc_select").change(function() {
							if(jQuery("#wprs_sc_select :selected").val()!="select"){
							  send_to_editor(jQuery("#wprs_sc_select :selected").val());
							}
							  return false;
					});
			});
			</script>';
	}
	

	/**
	 * download airbnb reviews when clicking the save button on Airbnb page
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wpairbnb_download_airbnb() {
      global $pagenow;
      if (isset($_GET['settings-updated']) && $pagenow=='admin.php' && current_user_can('export') && $_GET['page']=='wp_airbnb-get_airbnb') {
		$this->wpairbnb_download_airbnb_master();
      }
    }
	
	
	
	//for using curl instead of fopen
	private function file_get_contents_curl($url) {
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}
	//fix stringtotime for other languages
	private function myStrtotime($date_string) { 
		$monthnamearray = array(
		'janvier'=>'jan',
		'février'=>'feb',
		'mars'=>'march',
		'avril'=>'apr',
		'mai'=>'may',
		'juin'=>'jun',
		'juillet'=>'jul',
		'août'=>'aug',
		'septembre'=>'sep',
		'octobre'=>'oct',
		'novembre'=>'nov',
		'décembre'=>'dec',
		'gennaio'=>'jan',
		'febbraio'=>'feb',
		'marzo'=>'march',
		'aprile'=>'apr',
		'maggio'=>'may',
		'giugno'=>'jun',
		'luglio'=>'jul',
		'agosto'=>'aug',
		'settembre'=>'sep',
		'ottobre'=>'oct',
		'novembre'=>'nov',
		'dicembre'=>'dec',
		'janeiro'=>'jan',
		'fevereiro'=>'feb',
		'março'=>'march',
		'abril'=>'apr',
		'maio'=>'may',
		'junho'=>'jun',
		'julho'=>'jul',
		'agosto'=>'aug',
		'setembro'=>'sep',
		'outubro'=>'oct',
		'novembro'=>'nov',
		'dezembro'=>'dec',
		'enero'=>'jan',
		'febrero'=>'feb',
		'marzo'=>'march',
		'abril'=>'apr',
		'mayo'=>'may',
		'junio'=>'jun',
		'julio'=>'jul',
		'agosto'=>'aug',
		'septiembre'=>'sep',
		'octubre'=>'oct',
		'noviembre'=>'nov',
		'diciembre'=>'dec',
		'januari'=>'jan',
		'februari'=>'feb',
		'maart'=>'march',
		'april'=>'apr',
		'mei'=>'may',
		'juni'=>'jun',
		'juli'=>'jul',
		'augustus'=>'aug',
		'september'=>'sep',
		'oktober'=>'oct',
		'november'=>'nov',
		'december'=>'dec',
		' de '=>''
		);
		return strtotime(strtr(strtolower($date_string), $monthnamearray)); 
	}
	
	private function getreviewurlfrommain($urlvalue, $listing_id, $limit=10, $offset=0){
							$response = wp_remote_get( $urlvalue );
					if ( is_array( $response ) ) {
					  $header = $response['headers']; // array of http header lines
					  $fileurlcontents = $response['body']; // use the content
					} else {
						echo "Error finding reviews. Please contact plugin support.";
						die();
					}
					
					//going to try to pull the API key
					$dom  = new DOMDocument();
					libxml_use_internal_errors( 1 );
					$dom->loadHTML( $fileurlcontents );

					$xpath = new DOMXpath( $dom );

					$titleNode = $xpath->query('//title');
					$temptitle = $titleNode->item(0)->nodeValue;
					$pieces = explode("-", $temptitle);
					
					$reviewurl['pagetitle']=$pieces[0];

					$items = $xpath->query( '//meta/@content' );
					//$items = $dom->getElementsByTagName("meta");
					$key='';
					$findme='"api_config":{';
					if( $items->length < 1 )
					{
						die( "Error 1: No key found." );
					} else {
						//print_r($items);
						foreach ($items as $item) {
							if(strpos($item->nodeValue,$findme)){
								$nodearray = json_decode( $item->nodeValue, true );
								$key = $nodearray['api_config']['key'];
								$locale = $nodearray['locale'];
								//echo $key;
								//end the loop early
								break;
							}
						}
					}

					if($key==""){
						die( "Error 2: No key found." );
					}
					//print_r($nodearray);
					//die();
					
					//use the key and the listing id to find review data					
					$rurl = "https://www.airbnb.com/api/v2/reviews?key=".$key."&currency=USD&locale=".$locale."&listing_id=".$listing_id."&role=guest&_format=for_p3&_limit=".$limit."&_offset=".$offset."&_order=language_country";

					$reviewurl['url'] = esc_url_raw($rurl);
					
					return $reviewurl;
		
	}
		
	/**
	 * download airbnb reviews
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */	
	public function wpairbnb_download_airbnb_master() {
		//make sure file get contents is turned on for this host
		$errormsg ='';
		$ShowUserReviews = false;
					
			global $wpdb;
			$table_name = $wpdb->prefix . 'wpairbnb_reviews';
			$options = get_option('wpairbnb_airbnb_settings');
			
			//make sure you have valid url, if not display message
			if (filter_var($options['airbnb_business_url'], FILTER_VALIDATE_URL)) {
			  // you're good
			  //echo "valid url";
			  if($options['airbnb_radio']=='yes'){
				//echo "passed both tests";
				$stripvariableurl = strtok($options['airbnb_business_url'], '?');
				//find the listing_id
				$listing_id = (int) filter_var($stripvariableurl, FILTER_SANITIZE_NUMBER_INT);
				
				//find the reviewurl for this URL
				$urldetails = $this->getreviewurlfrommain($stripvariableurl, $listing_id, $limit=15, $offset=0);
				
				$airbnburl[1] =$urldetails['url'];
				
				//include_once('simple_html_dom.php');
				//loop to grab pages
				$reviews = [];
				$n=1;
				foreach ($airbnburl as $urlvalue) {
					

					$data = wp_remote_get( $urlvalue );
					if ( is_wp_error( $data ) ) 
					{
						$response['error_message'] 	= $data->get_error_message();
						$reponse['status'] 		= $data->get_error_code();
						print_r($response);
						die();
					}
					$pagedata = json_decode( $data['body'], true );

					//find airbnb business name and add to db under pagename
					$pagename ='';
					if($urldetails['pagetitle']!=""){
						$pagename =$urldetails['pagetitle'];
					}

					// Find 20 reviews
					$reviewsarray = $pagedata['reviews'];

					foreach ($reviewsarray as $review) {

							$user_name='';
							$userimage='';
							$rating='';
							$datesubmitted='';
							$rtext='';
							// Find user_name
							if($review['reviewer']['first_name']){
								$user_name = $review['reviewer']['first_name'];
							}
							
							// Find userimage ui_avatar
							if($review['reviewer']['picture_url']){
								$userimage = $review['reviewer']['picture_url'];
							}

							// find rating
							if($review['rating']){
								$rating = $review['rating'];
							}

							// find date created_at
							if($review['created_at']){
								$datesubmitted = $review['created_at'];
							}
							
							// find text
							if($review['comments']){
								$rtext = $review['comments'];
							}
							
							if($rating>0){
								$review_length = str_word_count($rtext);
								$timestamp = $this->myStrtotime($datesubmitted);
								$unixtimestamp = $timestamp;
								$timestamp = date("Y-m-d H:i:s", $timestamp);
								//check option to see if this one has been hidden
								//pull array from options table of airbnb hidden
								$airbnbhidden = get_option( 'wpairbnb_hidden_reviews' );
								if(!$airbnbhidden){
									$airbnbhiddenarray = array('');
								} else {
									$airbnbhiddenarray = json_decode($airbnbhidden,true);
								}
								$this_airbnb_val = trim($user_name)."-".strtotime($datesubmitted)."-".$review_length."-Airbnb-".$rating;
								if (in_array($this_airbnb_val, $airbnbhiddenarray)){
									$hideme = 'yes';
								} else {
									$hideme = 'no';
								}
								
								//add check to see if already in db, skip if it is and end loop
								$reviewindb = 'no';
								$checkrow = $wpdb->get_var( "SELECT id FROM ".$table_name." WHERE created_time_stamp = '".$unixtimestamp."' AND reviewer_name = '".trim($user_name)."' " );
								if( empty( $checkrow ) ){
										$reviewindb = 'no';
								} else {
										$reviewindb = 'yes';
								}
								if( $reviewindb == 'no' )
								{
								$reviews[] = [
										'reviewer_name' => trim($user_name),
										'pagename' => trim($pagename),
										'userpic' => $userimage,
										'rating' => $rating,
										'created_time' => $timestamp,
										'created_time_stamp' => $unixtimestamp,
										'review_text' => trim($rtext),
										'hide' => $hideme,
										'review_length' => $review_length,
										'type' => 'Airbnb'
								];
								}
								$review_length ='';
							}
					}

					//sleep for random 2 seconds
					sleep(rand(0,2));
					$n++;
					
					// clean up memory
					if (!empty($html)) {
						$html->clear();
						unset($html);
					}
				}
				 

					// clean up memory
					if (!empty($html)) {
						$html->clear();
						unset($html);
					}
				
				
				//add all new airbnb reviews to db
				$insertnum=0;
				foreach ( $reviews as $stat ){
					$insertnum = $wpdb->insert( $table_name, $stat );
				}
				//reviews added to db
				if($insertnum>0){
					$errormsg = $errormsg . ' Airbnb reviews downloaded.';
					$this->errormsg = $errormsg;
				} else {
					$errormsg = $errormsg . ' Unable to find any new reviews.';
					$this->errormsg = $errormsg;
				}
				
			  }
			} else {
				$errormsg = $errormsg . ' Please enter a valid URL.';
				$this->errormsg = $errormsg;
			}
			
			if($options['airbnb_radio']=='no'){
				$wpdb->delete( $table_name, array( 'type' => 'Airbnb' ) );
				//cancel wp cron job
			}
			

		if($errormsg !=''){
			//echo $errormsg;
		}
	}

    

}
