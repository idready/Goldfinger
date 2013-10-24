jQuery.noConflict();

(function( $ ) {
  "use strict";

  $(function() {

  $(document).on('click', '#add_finger_count > a', function(evt) {

    evt.preventDefault();
    // do we want today fcounter returned too?
    var is_today_displayed = (!($('.days .day').data('data-today')))  ? true : false;

    // add lightbox
    // have some fun : http://www.netwaver.com/online-tools/12-ajax-loading-icon-generators/
    $.ajax({
      data: {user_id : user, ask_today : is_today_displayed },
      dataType: 'json',
      url: './_ajax/_update_user.php',
      type: 'post',
      success : function(data){

        // if today is not displayed yet
        if (( data.get_today !== undefined) && data.get_today.count_is_today == true) {

          var tpl_today = '{{#today}}'+
                            '<div class="day clearfix nice_blue" data-today="true">'+
                                '<span class="day_arrow">></span>'+
                                '<span class="date">{{count_day_jour}} {{count_day_day}} {{count_day_mois}}'+'{{count_day_year}}</span>'+
                                    '<span class="sum_golfingers">'+
                                        '<span>+{{count}}</span>'+
                                        '<span class="goldfinger_picto"></span>'+
                                    '</span>'+
                            '</div>'+
                            '{{/today}}';

          // remove description for polite people
          if ($('#days').children().get(0).nodeName == 'P') {
            $('#days').children('p').fadeOut('slow').remove();
          }

          $('#days').prepend( Mustache.render(tpl_today, {today : eval(data.get_today)}) ).hide().fadeIn('slow');
        }

        // update :  Today day counter
        if (data.get_today === undefined) {
       	  var _to_update_today = $('.days .day').first().find('.sum_golfingers').children('span :not(.goldfinger_picto)');
          _to_update_today.html( '+' + (parseInt(_to_update_today.html()) + 1) );
        }

        // update : Today
        $('#today_count').html((parseInt($('#today_count').html())+1));

        // update : User total
        var _to_update_total = $('.total_fingers');
        _to_update_total.html( (parseInt(_to_update_total.html().replace(' (total)', '')) +1) + ' (total)');

      },

      error: function(data){
        console.log('Error : '+data);
      },

      complete: function(){
      	// console.log('complete');
      }

    });

    // remove lightbox on request complete

  });

  });

})(jQuery);