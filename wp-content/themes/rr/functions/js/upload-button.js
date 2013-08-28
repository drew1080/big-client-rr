jQuery(document).ready(function () {

    // bg
    jQuery('#coll_background_image_button').click(function () {

        window.send_to_editor = function (html) {
            imgurl = jQuery('img', html).attr('src');
            jQuery('#coll_background_image').val(imgurl);
            tb_remove();
        }


        tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
        return false;

    });

    // the rest
    jQuery('input.browse.img.button').click(function () {
        var _input = jQuery(this).parent().children('input[type=text]');
        window.send_to_editor = function (html) {
            imgurl = jQuery('img', html).attr('src');
            _input.val(imgurl);
            tb_remove();
        }


        tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
        return false;

    });

});
