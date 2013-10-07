$('#star').raty({
  score: function() {
    return $(this).attr('data-score');
  },
  path : 'wp-content/plugins/rating-pro/lib/img/',
  readOnly:true
});
//alert(document.getElementById('star').innerHTML);
//alert($('#star'));
