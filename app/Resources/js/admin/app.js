
var APP = function() {

    // PATHS
    // ======================
    //this.ASSETS_PATH = '../../assets/';
    this.ASSETS_PATH = './assets/';
    this.SERVER_PATH = this.ASSETS_PATH + 'demo/server/';

    // GLOBAL HELPERS
    // ======================
    this.is_touch_device = function() {
        return !!('ontouchstart' in window) || !!('onmsgesturechange' in window);
    };
};

var APP = new APP();

// APP UI SETTINGS
// ======================

APP.UI = {
    scrollTop: 0, // Minimal scrolling to show scrollTop button
};


// PAGE PRELOADING ANIMATION
$(window).on('load', function() {
    setTimeout(function() {
        $('.preloader-backdrop').fadeOut(200);
        $('body').addClass('has-animation');
    },0);
});

// Hide sidebar on small screen
$(window).on('load resize scroll', function () {
    if ($(this).width() < 992) {
        $('body').addClass('sidebar-mini');
    }
});


$(function () {

    // SIDEBAR ACTIVATE METISMENU
    $(".metismenu").metisMenu();

    // Activate Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Activate Popovers
    $('[data-toggle="popover"]').popover();

    // Activate slimscroll
    $('.scroller').each(function(){
        $(this).slimScroll({
            height: $(this).attr('data-height'),
            color: $(this).attr('data-color'),
            railOpacity: '0.9',
        });
    });


    // TOGGLE THEME-CONFIG BOX    
    $('.theme-config-toggle').on('click', function() {
        $(this).parents('.theme-config').toggleClass('opened');
    });

    // LAYOUT SETTINGS
    // ======================

    // SIDEBAR TOGGLE ACTION
    $('.js-sidebar-toggler').click(function() {
        $('body').toggleClass('sidebar-mini');
    });
    
    // fixed layout
    $('#_fixedlayout').change(function(){
        if( $(this).is(':checked') ) {
           $('body').addClass('fixed-layout');
            $('#sidebar-collapse').slimScroll({
                height: '100%',
                railOpacity: '0.9',
            });
        } else {
            $('#sidebar-collapse').slimScroll({destroy: true}).css({overflow: 'visible', height: 'auto'});
            $('body').removeClass('fixed-layout');
        }
    });

    // fixed navbar
    $('#_fixedNavbar').change(function() {
        if($(this).is(':checked')) $('body').addClass('fixed-navbar');
        else $('body').removeClass('fixed-navbar');
    });
    
    // Boxed layout
    $("[name='layout-style']").change(function(){
        if(+$(this).val()) $('body').addClass('boxed-layout');
        else $('body').removeClass('boxed-layout');
    });

    // THEMES CHANGE
    // ======================
    $('.color-skin-box input:radio').change(function() {
        var val = $(this).val();
        if(val != 'default') {
            if(! $('#theme-style').length ) {
                $('head').append( "<link href='assets/css/themes/"+val+".css' rel='stylesheet' id='theme-style' >" );
            } else $('#theme-style').attr('href', 'assets/css/themes/'+val+'.css');
        } else $('#theme-style').remove();
    });

    // BACK TO TOP
    $(window).scroll(function() {
        if($(this).scrollTop() > APP.UI.scrollTop) $('.to-top').fadeIn();
        else $('.to-top').fadeOut();
    });
    $('.to-top').click(function(e) {
        $("html, body").animate({scrollTop:0},500);
    });

    // PANEL ACTIONS
    // ======================
    $('.ibox-collapse').click(function(){
        var ibox = $(this).closest('div.ibox');
        ibox.toggleClass('collapsed-mode').children('.ibox-body').slideToggle(200);
    });
    $('.ibox-remove').click(function(){
        $(this).closest('div.ibox').remove();
    });
    $('.fullscreen-link').click(function(){
        if($('body').hasClass('fullscreen-mode')) {
            $('body').removeClass('fullscreen-mode');
            $(this).closest('div.ibox').removeClass('ibox-fullscreen');
            $(window).off('keydown',toggleFullscreen);
        } else {
            $('body').addClass('fullscreen-mode');
            $(this).closest('div.ibox').addClass('ibox-fullscreen');
            $(window).on('keydown', toggleFullscreen);
        }
    });
    function toggleFullscreen(e) {
        // pressing the ESC key - KEY_ESC = 27 
        if(e.which == 27) {
            $('body').removeClass('fullscreen-mode');
            $('.ibox-fullscreen').removeClass('ibox-fullscreen');
            $(window).off('keydown',toggleFullscreen);
        }
    }



    //MINICOLORS
    // ======================
    $('.minicolors').each(function(){
        $(this).minicolors({
            theme:"bootstrap",
            control:$(this).attr("data-control")||"hue",
            format: $(this).attr('data-format') || 'hex',
            opacity:$(this).attr("data-opacity"),
            swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            position: $(this).attr('data-position') || 'bottom left',
        });
    });

    // SORTORDER JS
    // ======================
    if (typeof Sortable == 'function') {
        var el = document.getElementById('items');
        Sortable.create(el, {
            animation: 150,
            store: {
                get: function(sortable) {
                    var order = localStorage.getItem(sortable.options.group.name);
                    return order ? order.split('|') : [];
                },
                set: function(sortable) {
                    var order = sortable.toArray();
                    // Update sort order
                    $.ajax({
                        method: "POST",
                        url: "/admin/projects/sort",
                        data: {
                            positions: order,
                        },
                    });
                }
            }
        });
    }

    // REMOVE PROJECT IMAGES
    // ======================
    $( ".btn-remove" ).click(function() {

        if(confirmDelete()) {
            // Loading
            $('.preloader-backdrop').fadeIn();

            // Image type
            var id = $(this).attr('data-id');
            var img = $(this).attr('data-img');

            $.ajax({
                method: "POST",
                url: "/admin/projects/"+id+"/remove",
                data: { "img":img },
                success: function(){
                    if(img == 'logo') {
                        $(".img-logo").remove();
                    }
                    if(img == 'image') {
                        $(".img-image").remove();
                    }
                    $('.preloader-backdrop').fadeOut();
                },
                error: function () {
                    $('.preloader-backdrop').fadeOut();
                    alert('Access denied');
                }
            });
        }

    });


});


/**
 * Confirm delete notification
 */
function confirmDelete() {
    var agree=confirm("Are you sure you wish to proceed?");
    if (agree) {
        return true;
    }
    else {
        return false;
    }
}
