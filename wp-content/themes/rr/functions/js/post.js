/**
 * Created by JetBrains PhpStorm.
 * User: sQrt121
 * Date: 2/1/12
 * Time: 11:05 AM
 * To change this template use File | Settings | File Templates.
 */


jQuery(document).ready(function($) {
    // hide all custom metaboxes
    $('div[id^="meta-box-"]').hide();


    // show metabox on demand
    $('#post-formats-select input').change(function(e) {
        $('div[id^="meta-box-"]').hide();
        var t = $(this).val();
        $('#meta-box-' + t).show();
    })

    // show the selected metabox
    var _s = $('#post-formats-select input:checked').val()
    $('#meta-box-' + _s).show();
});