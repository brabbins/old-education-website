jQuery(document).ready(function($) {  
    $('#upload_banner_button').click(function() {  
        tb_show('Upload a logo', 'media-upload.php?&type=image&TB_iframe=true&post_id=0', false);  
        return false;  
    });  
window.send_to_editor = function(html) {  
    var image_url = $('img',html).attr('src');  
    $('#foe-banner-image').val(image_url);  
    tb_remove();  
} 
    
	$('#upload_regular_button').click(function() {  
        tb_show('Upload a logo', 'media-upload.php?&type=image&TB_iframe=true&post_id=0', false);  
        return false;  
    });  
window.send_to_editor = function(html) {  
    var image_url = $('img',html).attr('src');  
    $('#foe-chevron-image-regular').val(image_ur);  
    tb_remove();  
} 


    $('#upload_retina_button').click(function() {  
        tb_show('Upload a logo', 'media-upload.php?&type=image&TB_iframe=true&post_id=0', false);  
        return false;  
    });  
window.send_to_editor = function(html) {  
    var image_url = $('img',html).attr('src');  
    $('#foe-chevron-image-retina').val(image_url);  
    tb_remove();  
} 
});
