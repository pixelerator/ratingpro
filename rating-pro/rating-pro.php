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
	wp_register_script('rating_plugin_scripts', plugin_dir_url(__FILE__) . 'js/rating-plugin.js');
	wp_enqueue_script('rating_plugin_scripts');
}

add_action('wp_enqueue_scripts', 'rating_plugin_scripts');

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
	if(is_single()){
		return $content."<div style='border:1px solid red;clear:both;'>I am after Post</div>";
	}
	return $content;
}

add_filter('the_content', 'rate_post_content');
//Adding new option in the fields
add_filter( 'comment_form_default_fields', 'my_comment_form_default_fields' );

function my_comment_form_default_fields( $fields ) {

	$fields['twitter'] = '<p class="comment-form-twitter"><label for="twitter">' . __( 'Twitter (@username)' ) . '</label><input type="text" id="twitter" name="twitter" value="" size="30" /></p>';

	return $fields;
}
//end of adding new fields
add_filter( 'comment_form_field_comment', 'my_comment_form_field_comment' );

function my_comment_form_field_comment( $comment_field ) {

	$comment_field = '<div style="border 1px solid green;" class="myclass">fkjsdhfjkhsdkjfhj' . $comment_field . '</div>';

	return $comment_field;
}
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

function additional_fields ($below) {
  global $post;
  
  return $below."<div>Rating</div>".json_encode( wp_get_post_categories( $post->ID));
}
add_filter('comment_text','additional_fields');
?>