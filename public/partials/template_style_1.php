<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_Airbnb_Review
 * @subpackage WP_Airbnb_Review/public/partials
 */
 //html code for the template style
$plugin_dir = WP_PLUGIN_DIR;
$imgs_url = esc_url( plugins_url( 'imgs/', __FILE__ ) );

//loop if more than one row
for ($x = 0; $x < count($rowarray); $x++) {
	if(	$currentform[0]->template_type=="widget"){
		?>
		<div class="wpairbnb_t1_outer_div_widget w3_wprs-row-padding-small">
		<?php
		} else {
		?>
		<div class="wpairbnb_t1_outer_div w3_wprs-row-padding">
		<?php
	}
	//loop 
	foreach ( $rowarray[$x] as $review ) 
	{
		if($review->type=="Facebook"){
			$userpic = 'https://graph.facebook.com/'.$review->reviewer_id.'/picture?width=60&height=60 ';
		} else {
			$userpic = $review->userpic;
		}
		if($userpic==""){
			$userpichtml = "";
		} else {
			$userpichtml ='<img src="'.$userpic.'" alt="thumb" class="wpairbnb_t1_IMG_4" />';
		}
		
		//star number 
		if($review->type=="Airbnb"){
			//find business url
			$options = get_option('wpairbnb_airbnb_settings');
			$burl = $options['airbnb_business_url'];
			if($burl==""){
				$burl="https://www.airbnb.com";
			}
			$starfile = "stars_".$review->rating."_yellow.png";
			$logo = '<a href="'.$burl.'" target="_blank" rel="nofollow"><img src="'.$imgs_url.'airbnb_outline.png" alt="" class="wpairbnb_t1_airbnb_logo"></a>';
		} 
		
		$reviewtext = "";
		if($review->review_text !=""){
			$reviewtext = nl2br($review->review_text);
			//add airbnb fix for ..More replace with link to pageid
			$morelink = '<a href="'.$burl.'" class="ta_morelink" target="_blank" rel="nofollow">..More</a>';
			$reviewtext = str_replace("..More",$morelink,$reviewtext);

			
		}
		//if read more is turned on then divide then add read more span links
		if(	$currentform[0]->read_more=="yes"){
			$readmorenum = intval($currentform[0]->read_more_num);
			$countwords = str_word_count($reviewtext);
			$readmoretext = $currentform[0]->read_more_text;
			if($readmoretext==""){
				$readmoretext ="read more";
			}
			if($countwords>$readmorenum){
				//split in to array
				$pieces = explode(" ", $reviewtext);
				//slice the array in to two
				$part1 = array_slice($pieces, 0, $readmorenum);
				$part2 = array_slice($pieces, $readmorenum);
				$reviewtext = implode(" ",$part1).'<a href="'.$burl.'" class="ta_morelink" target="_blank" rel="nofollow">... '.$readmoretext.'</a>';
			}
		}

		//per a row
		if($currentform[0]->display_num>0){
			$perrow = 12/$currentform[0]->display_num;
		} else {
			$perrow = 4;
		}
		
		//date
		if($review->created_time_stamp=='' || $review->created_time_stamp<1){
			$temptime = $review->created_time;
			$review->created_time_stamp = strtotime($temptime );
		} 
		$tempdate = date("n/d/Y",$review->created_time_stamp);
	?>
		<div class="wpairbnb_t1_DIV_1<?php if(	$currentform[0]->template_type=="widget"){echo ' marginb10';}?> w3_wprs-col l<?php echo $perrow; ?>">
			<div class="wpairbnb_t1_DIV_2 wprev_preview_bg1_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?> wprev_preview_bradius_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>">
				<p class="wpairbnb_t1_P_3 wprev_preview_tcolor1_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>">
					<span class="wpairbnb_star_imgs_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><img src="<?php echo $imgs_url."".$starfile; ?>" alt="" class="wpairbnb_t1_star_img_file">&nbsp;&nbsp;</span><?php echo stripslashes($reviewtext); ?>
				</p>
				<?php echo $logo; ?>
			</div><span class="wpairbnb_t1_A_8"><?php echo $userpichtml; ?></span> <span class="wpairbnb_t1_SPAN_5 wprev_preview_tcolor2_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo stripslashes($review->reviewer_name); ?><br/><span class="wprev_showdate_T<?php echo $currentform[0]->style; ?><?php if($iswidget){echo "_widget";} ?>"><?php echo $tempdate; ?></span> </span>
		</div>
	<?php
	}
	//end loop
	?>
	</div>
<?php
}
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
