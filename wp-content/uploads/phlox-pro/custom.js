/*
===============================================================
 #CUSTOM JavaScript
- Please do not edit this file. This file is generated from admin area.
- Every changes here will be overwritten by theme
===============================================================*/
    /* theme_options_custom 
=========================*/
(function($){
    if ($('body').hasClass('single-portfolio') ) {
        var changeNavPosition = function() {
            if ( $(window).width() > 1024 ) {
                var sidebarHeight = $('.single-portfolio .aux-primary .entry-side').height();
                var imageHeight = $('.single-portfolio .aux-primary .entry-main').height();
                if ( sidebarHeight > imageHeight && $('.aux-next-prev-posts').length ) {
                    $('.aux-next-prev-posts').css('bottom', (sidebarHeight - imageHeight) + 'px');
                }   
            }
        }
        
        changeNavPosition();
        $(window).on('resize', function(){
           changeNavPosition(); 
        });
    }
         // Thêm đoạn mã để xử lý nội dung wpcf7-list-item-label
        $('.wpcf7-list-item-label').each(function() {
            console.log('est');
            var text = $(this).text().trim();
            var [prefix, price] = text.split('Chỉ từ ');
            var formattedHTML = `${prefix}<span class="pricetable">Chỉ từ <span class="price">${price}</span></span>`;
            $(this).html(formattedHTML);
        });    
})(jQuery)  
