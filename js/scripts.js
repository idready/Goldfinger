
jQuery.noConflict();

(function( $ ) {
  "use strict";

  // check if element exists
  jQuery.fn.exists = function(){return this.length>0;}


  $(function() {

    // handle some rude code here

    // fittext for big headlines
    // $('header h1').fitText();

    // $(window).resize(function() {
    //     console.log( 'resize' );
    //     console.log( $('body').css("width") );
    // });

    // retrieve user days
    if ($('#days').exists()) {

        $.ajax({
            data: {user_front : user, rows : 0},
            dataType: 'json',
            url: './_ajax/_request_days_front.php',
            type: 'post',
            success : function(data){

                // more days to load?
                (data.last_days) ? $('.more_fingers > a').fadeOut('slow').remove() : '' ;

                // TODO : this case will be removed once user can be added in BO
                // fresh user?
                if (data.User_Counter < 1) {
                  $('#count .sub_title').remove();
                  $('.more_fingers').remove();
                  $('#total').remove();
                }

                // TODO : add animation with the result when loading datas
                $('#days').html( Mustache.render($('#days').html(), {days : eval(data.User_Days) }));
                // display user total fingers showed
                $('#total > span').html( Mustache.render($('#total > span').html(), {total_finger : eval(data.User_Counter)}) );
                // display days added today
                if ($('#today_count')[0]) {
                    $('#today_count').html( Mustache.render($('#today_count').html(), {today_count : eval(data.today_count)}) );
                }
            },

            error: function(data){
                console.log('Error : '+data);
            }

        });
    }

    // loads more days
    $(document).on('click', '.more_fingers > a', function(evt) {

        evt.preventDefault();

        $.ajax({
            data: {user_front : user, rows : $('#days > div.day').size() },
            dataType: 'json',
            url: './_ajax/_request_days_front.php',
            type: 'post',
            success : function(data){

                // more days to load?
                (data.last_days) ? $('.more_fingers > a').fadeOut('slow').remove() : '' ;

                var tpl_days = '{{#more_days}}'+
                                '<div class="day clearfix">'+
                                    '<span class="day_arrow">></span>'+
                                    '<span class="date">{{count_day_jour}} {{count_day_day}} {{count_day_mois}}'+'{{count_day_year}}</span>'+
                                        '<span class="sum_golfingers">'+
                                            '<span>+{{count}}</span>'+
                                            '<span class="goldfinger_picto"></span>'+
                                        '</span>'+
                                '</div>'+
                                '{{/more_days}}';
                $('#days').append( Mustache.render(tpl_days, {more_days : eval(data.User_Days)}) );
                // TODO : add animation with the result when loading datas
                // var result = Mustache.render(tpl_days, {more_days : eval(data.User_Days)});

                // TODO counter after scroll up
                // update totals
                // display user total fingers showed
                if ($('#total > span').exists()) {
                    var total = parseInt( ($('#total > span').html()).replace('+','') ) + eval(data.User_Counter);
                    $('#total > span').html('+' + total);
                }
            },

            error: function(data){
                console.log('Error : '+data);
            }

        });

    });

  });

})(jQuery);