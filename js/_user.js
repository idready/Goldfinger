jQuery.noConflict();

(function( $ ) {
  "use strict";

  $(function() {

  	// retrieve users
    $.ajax({
      data: {single_user : user},
      dataType: 'json',
      url: './_ajax/_request_user.php',
      type: 'post',
      success : function(data){

      // TODO : add animation with the result when loading datas
      $('.single_user').html( Mustache.render($('.single_user').html(), {user : eval(data) }) );

      },

      error: function(data){
        console.log('Error : '+data);
      }

    });

  });

})(jQuery);