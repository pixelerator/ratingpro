<?php
/*
 Plugin Name: Rater-Pro
 Plugin URI: http://www.mypixel.co.in
 Description: Plugin Use to initiate the Ratings on indiviudal post and the user can rate it .
 Version: 1.0
 Author: Mohammed Intekhab khan
 Author URI: http://www.mypixel.co.in
 License: GPL3
 */
?>
<?php
function rating_plugin_scripts() {
	//wp_register_script('rating_plugin_scripts', plugin_dir_url(__FILE__) . 'lib/jquery.raty.min.js');
	if (is_single()) {
		wp_enqueue_script('rating_plugin_main', plugin_dir_url(__FILE__) . 'lib/jquery-1.10.2.min.js', FALSE, FALSE, TRUE);
		wp_enqueue_script('rating_plugin_scripts', plugin_dir_url(__FILE__) . 'lib/jquery.raty.min.js', FALSE, FALSE, TRUE);
		//wp_register_script('rating_plugin_custom', plugin_dir_url(__FILE__) . 'js/rating-plugin.js');
		wp_enqueue_script('rating_plugin_custom', plugin_dir_url(__FILE__) . 'js/rating-plugin.php', FALSE, FALSE, TRUE);
		$translation_array = array('path_image' => plugin_dir_url(__FILE__), 'a_value' => '10');
		wp_localize_script('rating_plugin_custom', 'object_name', $translation_array);
	}
}

function rating_admin_plugin_scripts() {

	if ($_GET['page'] == "rating-plugin-menu") {

		wp_enqueue_script('rating_plugin_bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', FALSE, FALSE, TRUE);
		wp_register_style('rater-boot-style', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
		wp_enqueue_style('rater-boot-style');
		wp_register_style('rater-boot-style-responsive', plugin_dir_url(__FILE__) . 'css/bootstrap-responsive.min.css');
		wp_enqueue_style('rater-boot-style-responsive');
	}
}

add_action('wp_enqueue_scripts', 'rating_plugin_scripts');
add_action('admin_enqueue_scripts', 'rating_admin_plugin_scripts');

//1
function rating_plugin_menu() {
	add_options_page('Rater Pro Option', 'Rating Pro', 'manage_options', 'rating-plugin-menu', 'rating_plugin_options');
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
				if (!empty($rating_value)) {
					$rvalue = get_comment_aggregate();
					if ($rvalue == "N") {
						update_post_meta($post -> ID, "rater_rate", 0);
					} else {
						update_post_meta($post -> ID, "rater_rate", $rvalue);
					}
				}
				//get_comment_aggregate()
				$rating_value = get_post_meta($post -> ID, "rater_rate", TRUE);
				return $content . "<div style='clear:both;'><div id='star' data-score='" . $rating_value . "'></div></div>";
			}
		} else {
			return $content;
		}
	}
		if(is_category()){
			return $content."Hello i am extra field";
		}
	return $content;
}

add_filter('the_content', 'rate_post_content');
add_action('comment_form_logged_in_after', 'my_comment_form_default_fields_logged');
add_action('comment_form_after_fields', 'my_comment_form_default_fields_logged');
function my_comment_form_default_fields_logged($fields) {
	if (check_is_single_available()) {

		echo '<p class="comment-form-rating">' . '<label for="rating">' . __('Rating') . '<span class="required">*</span></label><div id="submit-comment-rating"></div><span class="comment-rating-box"></span></p>';
	}

}

function check_is_single_available() {
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
				return 1;
			} else {
				return 0;
			}
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}

function additional_fields($below) {
	if (check_is_single_available()) {
		$comment_id = get_comment_ID();
		$meta = get_comment_meta($comment_id, 'rating', true);
		if (empty($meta) || $meta == "N") {
			return $below . '<div class="rating_nr"></div>';
		} else {
			return $below . '<div class="rating_ar" data-score=' . $meta . '></div>';
		}

	} else {
		return $below;
	}

}

add_filter('comment_text', 'additional_fields');

add_option("rater", "none", null, 'yes');
add_filter('preprocess_comment', 'verify_comment_meta_data');
function verify_comment_meta_data($commentdata) {
	if (check_is_single_available()) {
		if (!empty($_POST['rater_rating'])) {

			if (!is_numeric($_POST['rater_rating']) || $_POST['rater_rating'] > 5 || $_POST['rater_rating'] < 0) {
				wp_die(__('Error: Invalid Rating. Hit the Back button on your Web browser and resubmit your comment with a rating.'));
			}
		}
	}
	return $commentdata;
}

add_action('comment_post', 'save_comment_meta_data');
function save_comment_meta_data($comment_id) {

	if (( isset($_POST['rater_rating'])) && (!empty($_POST['rater_rating']))) {
		add_comment_meta($comment_id, 'rating', $_POST['rater_rating']);
	} else {
		add_comment_meta($comment_id, 'rating', "N");
	}

}

function get_comment_aggregate() {
	global $post, $wpdb;
	$query = "SELECT w.comment_ID,w.comment_post_ID,m.meta_value FROM " . $wpdb -> prefix . "comments w inner join " . $wpdb -> prefix . "commentmeta m on m.comment_id=w.comment_ID where w.comment_post_ID=" . $post -> ID . " and w.comment_approved=1";
	$data = $wpdb -> get_results($query);
	if (empty($data)) {
		return "N";
	} else {
		$count = 0;
		$i = 0;
		foreach ($data as $key => $value) {
			if ($value -> meta_value != "" && !empty($value -> meta_value) && $value -> meta_value != "N" && is_numeric($value -> meta_value)) {
				$i++;
				$count = $count + $value -> meta_value;
			}
		}
		$rating = $count / $i;
		return $rating;
	}

}
function my_fields() {
 echo '<p>iiiii/p>';

}
add_action('comment_form_before','my_fields');

?>