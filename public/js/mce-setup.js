$(function(){

    $('textarea.rich-text').tinymce({
        
        theme : 'advanced',
        theme_advanced_buttons1 : 'bold,italic,link,unlink,removeformat',    
        theme_advanced_buttons2 : '',
        theme_advanced_buttons3 : '',
        theme_advanced_toolbar_location : 'top',
        theme_advanced_statusbar_location : 'bottom',
        theme_advanced_resizing : true,
        theme_advanced_resize_horizontal : false,
        theme_advanced_path : false,
        relative_urls : false,
        inline_styles : false,
        valid_elements : 'a[href|target|title|class],b,strong,i,em,br,p'
        
    });
});