jQuery.noConflict();

(function( $ ) {
  "use strict";

  $(function() {

    $(document).on('submit', 'form#log_user', function(evt) {

      if(evt.preventDefault) { evt.preventDefault() }else { evt.returnValue = false; }

    	// create user
      $.ajax({
        data: {user_login : $('#user_login').val(), user_pwd : $('#user_pwd').val()},
        url: './_ajax/_log_user.php',
        dataType: 'json',
        type: 'post',
        success : function(data){
          var results = eval(data.results);
          // success
          if (results.result) {
            // redirect to users edit
            window.location.href = base_url;
          }

        },

        error: function(data){
          console.log('Error : '+data);
        }

      });

    });


    return false;

  });

})(jQuery);