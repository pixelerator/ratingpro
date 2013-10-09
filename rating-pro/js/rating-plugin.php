<?php
	header('Content-Type: application/javascript');
	//$baseUrl = get_bloginfo('url');
	//$fullPath = $baseUrl."/wp-content/plugins/my-plugin...";
	  
 ?>
$('#star').raty({
  score: function() {
    return $(this).attr('data-score');
  },
  path : object_name.path_image+'lib/img/',
  readOnly:true
});
$('#submit-comment-rating').raty({ scoreName: 'rater_rating',path : object_name.path_image+'lib/img/'});
$('.rating_ar').raty({ readOnly:true,path : object_name.path_image+'lib/img/',  score: function() { 
    return $(this).attr('data-score');
  }});
  $('.rating_nr').raty({
  readOnly  : true,
  noRatedMsg: "No rating available",
  path : object_name.path_image+'lib/img/'
});

<?php 
	//global $post;
	//json_encode($post);
?>