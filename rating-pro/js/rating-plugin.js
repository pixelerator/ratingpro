$('#star').raty({
  score: function() {
    return $(this).attr('data-score');
  },
  path : 'wp-content/plugins/rating-pro/lib/img/',
  readOnly:true
});
//alert(document.getElementById('star').innerHTML);
//alert($('#star'));
$('#submit-comment-rating').raty({ scoreName: 'rater_rating',path : 'wp-content/plugins/rating-pro/lib/img/'});
$('.rating_ar').raty({ readOnly:true,path : 'wp-content/plugins/rating-pro/lib/img/',  score: function() { 
    return $(this).attr('data-score');
  }});
  $('.rating_nr').raty({
  readOnly  : true,
  noRatedMsg: "No rating available",
  path : 'wp-content/plugins/rating-pro/lib/img/'
});