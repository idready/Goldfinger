jQuery.noConflict();

(function( $ ) {
  "use strict";

  $(function() {

    $(document).on('submit', 'form#create_user', function(evt) {

      if(evt.preventDefault) { evt.preventDefault() }else { evt.returnValue = false; }

      // check if an image is include in url
      var image_url = $('#user_avatar').val().replace('//', '/');
      var image_url_temp = image_url.split('/');
      var image_url_temp_file = image_url_temp[image_url_temp.length - 1];
      var image_file_temp = image_url_temp_file.split('.');
      var image_file_temp_ext = image_file_temp[image_file_temp.length - 1];

      if ( image_file_temp_ext == 'jpg' || image_file_temp_ext == 'png' || image_file_temp_ext == 'jpeg') {

        $.ajax({
          data: {user_name : $('#user_name').val(), user_first_name : $('#user_first_name').val(),
          user_avatar: $('#user_avatar').val()},
          url: './_ajax/_create_user.php',
          dataType: 'json',
          type: 'post',
          success : function(data){
            var results = eval(data.results);

            // success
            if (results.result) {

              $('#create_user').parent().append('<p class="resp_notice nice_green">Utilisateur crée avec succès.</p>')
                                        .find('.resp_notice').slideDown().fadeIn('slow').delay(3000)
                                        .slideUp().fadeOut('slow', function(){
                                          // on animation complete
                                          $(this).remove();
                                          window.location.href = 'http://' + base_url + '/user.php?'+'user=' + results.redirect_user;
                                        });
            // error user already exists
            } else if (!results.result && (results.message.length > 1)) {

              $('#create_user').parent().append('<p class="resp_notice error">'+results.message+'</p>')
                                        .find('.resp_notice').slideDown().fadeIn('slow').delay(3000)
                                        .slideUp().fadeOut('slow', function(){
                                          // on animation complete
                                          $(this).remove();
                                        });
            // others errors
            } else if (!results.result) {

              $('#create_user').parent().append('<p class="resp_notice error">Erreur création utilisateur.</p>')
                                        .find('.resp_notice').slideDown().fadeIn('slow').delay(3000)
                                        .slideUp().fadeOut('slow', function(){
                                          // on animation complete
                                          $(this).remove();
                                        });
            }

          },

          error: function(data){
            console.log('Error : '+data);
          }

        });

      } else {
        alert('Merci de selectionner une image au format suivant: jpg // jpeg // png');
      }

    });

    return false;

  });

})(jQuery);