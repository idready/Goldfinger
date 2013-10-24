jQuery.noConflict();

(function( $ ) {
  "use strict";

  $(function() {

  	// retrieve users
  	if ($('#users').exists()) {

  		$.get('./_ajax/_request_users.php', function(data) {
  			$('#users').html( Mustache.render($('#users').html(), {user : data}) );
		}, 'json');
  	}

  });

})(jQuery);