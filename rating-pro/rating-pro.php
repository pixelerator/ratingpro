<?php
/*
 Plugin Name: Rater-Pro
 Plugin URI: http://www.mypixel.co.in
 Description: Rating Plugin .
 Version: 1.0
 Author: Mohammed Intekhab khan
 Author URI: http://www.mypixel.co.in
 License: GPL2
 */
?>
<?php
function rating_plugin_scripts() {
	//wp_register_script('rating_plugin_scripts', plugin_dir_url(__FILE__) . 'lib/jquery.raty.min.js');
	wp_enqueue_script('rating_plugin_main', plugin_dir_url(__FILE__) . 'lib/jquery-1.10.2.min.js', FALSE, FALSE, TRUE);
	wp_enqueue_script('rating_plugin_scripts', plugin_dir_url(__FILE__) . 'lib/jquery.raty.js', FALSE, FALSE, TRUE);
	//wp_register_script('rating_plugin_custom', plugin_dir_url(__FILE__) . 'js/rating-plugin.js');
	wp_enqueue_script('rating_plugin_custom', plugin_dir_url(__FILE__) . 'js/rating-plugin.js', FALSE, FALSE, TRUE);
	
}

function rating_admin_plugin_scripts() {

	wp_enqueue_script('rating_plugin_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', FALSE, FALSE, TRUE);
	wp_register_style('rater-boot-style', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
	wp_enqueue_style('rater-boot-style');
	wp_register_style('rater-boot-style-responsive', plugin_dir_url(__FILE__) . 'css/bootstrap-responsive.min.css');
	wp_enqueue_style('rater-boot-style-responsive');

}

add_action('wp_enqueue_scripts', 'rating_plugin_scripts');
add_action('admin_enqueue_scripts', 'rating_admin_plugin_scripts');

//1
function rating_plugin_menu() {
	add_options_page('Rating Plugin Options', 'Rating Plugin', 'manage_options', 'rating-plugin-menu', 'rating_plugin_options');
}

//2
add_action('admin_menu', 'rating_plugin_menu');

//3
function rating_plugin_options() {
	include ('admin/rating-plugin-admin.php');
}

function rate_post_content($content) {

	if (is_single()) {

		global $post;
		$current_cat = get_the_category($post -> ID);
		$selected_cat_from_admin = get_option("rater");
		if (is_array($selected_cat_from_admin)) {
			$do = 0;
			foreach ($selected_cat_from_admin as $skey => $svalue) {
				foreach ($current_cat as $ckey => $cvalue) {
					if ($cvalue -> cat_ID == $svalue) {
						$do = 1;
					}
				}
			}
			if ($do == 1) {
				$rating_value = get_post_meta($post -> ID, "rater_rate", FALSE);
				if (empty($rating_value)) {
					add_post_meta($post -> ID, "rater_rate", 0);
				}
				$rating_value = get_post_meta($post -> ID, "rater_rate", TRUE);
				return $content . "<div style='border:1px solid red;clear:both;'><div id='star' data-score='" . $rating_value . "'></div></div>";
			}
		}else{
			return $content;
		}
	}
	return $content;
}

add_filter('the_content', 'rate_post_content');
//Adding new option in the fields
//add_filter('comment_form_default_fields', 'my_comment_form_default_fields');
add_action('comment_form_logged_in_after', 'my_comment_form_default_fields_logged');
add_action('comment_form_after_fields', 'my_comment_form_default_fields_logged');
function my_comment_form_default_fields_logged($fields) {

	echo '<p class="comment-form-rating">'.
	'<label for="rating">'. __('Rating') . '<span class="required">*</span></label><div id="submit-comment-rating"></div><span class="comment-rating-box"></span></p>';

	//return $fields;
}

function my_comment_form_default_fields($fields) {

	$fields['rating-at'] = '<p class="comment-form-rating"><label for="rating">' . __('Rating ') . '</label><input type="text" id="rater_rating" name="rater_twitter" value="" size="30" /></p>';

	return $fields;
}

//end of adding new fields
/*add_filter('comment_form_field_comment', 'my_comment_form_field_comment');

function my_comment_form_field_comment($comment_field) {

	$comment_field = '<div style="border 1px solid green;" class="myclass">fkjsdhfjkhsdkjfhj' . $comment_field . '</div>';

	return $comment_field;
}*/

// function additional_fields ($below) {
// global $comment;
// $ratsec = '<form action="'.get_permalink().'" method="get" class="comment-form-rating">';
// $ratsec .= '<input type="hidden" name="p" value="'.get_the_ID().'"';
// $ratsec .= '<label for="rating">'. __('Rating') . '<span class="required">*</span></label>';
// $ratsec .= '<span class="commentratingbox">';
//
// //Current rating scale is 1 to 5. If you want the scale to be 1 to 10, then set the value of $i to 10.
// for( $i=1; $i <= 5; $i++ ) {
// $ratsec .= '<span class="commentrating"><input type="radio" name="rating['.$comment->comment_ID.']" id="rating" value="'. $i .'"/>'. $i .'</span>';
// }
//
// $ratsec .= '<input type="submit" name="rate" value="Rate" />';
// $ratsec .= '</span>';
// $ratsec .= '</form>';
// $below = $below .$ratsec;
// return $below;
// }
// add_filter( 'comment_text', 'additional_fields' );

function additional_fields($below) {
	global $post;

	return $below . '<div>rating</div>' . json_encode($post);
}

add_filter('comment_text', 'additional_fields');

add_option("rater", "none", null, 'yes');
//update_option("rater",array("Intekhab"));
add_filter('preprocess_comment', 'verify_comment_meta_data');
function verify_comment_meta_data($commentdata) {
	if (!isset($_POST['rater_rating']))
		wp_die(__('Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.'));
	return $commentdata;
}

/*add_action( 'comment_post', 'save_comment_meta_data' );
 function save_comment_meta_data( $comment_id ) {
 if ( ( isset( $_POST['phone'] ) ) && ( $_POST['phone'] != '') )
 $phone = wp_filter_nohtml_kses($_POST['phone']);
 add_comment_meta( $comment_id, 'phone', $phone );

 if ( ( isset( $_POST['title'] ) ) && ( $_POST['title'] != '') )
 $title = wp_filter_nohtml_kses($_POST['title']);
 add_comment_meta( $comment_id, 'title', $title );

 if ( ( isset( $_POST['rating'] ) ) && ( $_POST['rating'] != '') )
 $rating = wp_filter_nohtml_kses($_POST['rating']);
 add_comment_meta( $comment_id, 'rating', $rating );
 }*/
?>