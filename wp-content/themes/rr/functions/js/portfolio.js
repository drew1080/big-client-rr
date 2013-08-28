jQuery(document).ready(function($) {

    /*
     *   THUMBNAIL
     *
     * */
    $('#portfolioThumb input.browse-image-button').click(imageButtonClick);


    /*
     *   CONTENT ITEMS
     *
     * */

    // IMPORTANT DE MODIFICAT DACA SE MODIFICA STRUCTURA !!!!!!
    // add content items section
    // show only the first content item type
    $('#contentItemData').children('div').hide();
    $('#contentItemData div.image').show();

    $("#selectedContentType").change(function () {
        var _type = $('#selectedContentType option:selected').attr('value');

        $('#contentItemData').children('div').hide();
        $('#contentItemData div.' + _type).show();
    })


    // store content items last id
    var nContentItems;
    if ($('#contentItems .content-item').length != 0) {
        var nContentItems = parseInt($('#contentItems .content-item:last').attr('class')) + 1;
    }
    else {
        nContentItems = 0;
    }


    // add colors
    $('#contentItems').children('div:nth-child(2n)').css({"backgroundColor":"#eee"})

    // add content button
    $('#addContentButton').click(function(e) {
        var _postID = $('#post_ID').attr('value');
        var _contentType = $('#selectedContentType').val();
        var _contentText = $("#selectedContentType option[value='" + _contentType + "']").text();


        // create new html elements
        var main = $('#contentItems');
        var newdiv = $('<div />', {
            class:nContentItems + " content-item " + _contentType,
            style:"margin-top: 20px;"
        });
        newdiv.hide();

        switch (_contentType) {
            case "image":
                var _url = $('#contentItemData div.image input:first').val();
                newdiv.html("<label style='position: relative; float: left; width: 150px;'>" +
                    "<strong>Image</strong><span style='display:block;color: #999'>edit image url</span></label>" +
                    "<input type='text' style='width: 50%; margin-left: 10px;margin-top: 4px; float: left;' value='" + _url + "'/>" +
                    "<input style='margin-left: 25px;margin-top: 5px;' class='update-item-button button' type='button'  value='Update'/>" +
                    "<input style='margin-left: 10px;margin-top: 5px;' class='remove-item-button button' type='button'  value='Remove Item'/>" +
                    "<div style='clear: both;'></div>");
                main.append(newdiv);
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = _contentText;
                meta_data.url = _url;
                break;
            case "youtube":
                var _url = $('#contentItemData div.youtube textarea:first').val();
                newdiv.html("<label style='position: relative; float: left; width: 150px;'><strong>Youtube</strong>" +
                    "<span style='display:block;color: #999'>edit embed code</span></label>" +
                    "<textarea style='width: 50%; margin-left: 10px; height: 60px;outline: 0; float: left;'>" + _url + "</textarea>" +
                    "<input style='margin-left: 25px;margin-top: 22px;float: left;' class='update-item-button button' type='button' value='Update'/>" +
                    "<input style='margin-left: 10px;margin-top: 22px;' class='remove-item-button button' type='button' value='Remove Item'/>" +
                    "<div style='clear: both;'></div>")
                main.append(newdiv);
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = _contentText;
                meta_data.url = _url;
                break;
            case "vimeo":
                var _url = $('#contentItemData div.vimeo textarea:first').val();
                newdiv.html("<label style='position: relative; float: left; width: 150px;'><strong>Vimeo</strong>" +
                    "<span style='display:block;color: #999'>edit embed code</span></label>" +
                    "<textarea style='width: 50%; margin-left: 10px; height: 60px;outline: 0; float: left;'>" + _url + "</textarea>" +
                    "<input style='margin-left: 25px;margin-top: 22px;float: left;' class='update-item-button button' type='button' value='Update'/>" +
                    "<input style='margin-left: 10px;margin-top: 22px;' class='remove-item-button button' type='button' value='Remove Item'/>" +
                    "<div style='clear: both;'></div>")
                main.append(newdiv);
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = _contentText;
                meta_data.url = _url;
                break;
            case "video":
                var _tmb = $('#contentItemData div.video input:eq(0)').val();
                var _w = $('#contentItemData div.video input:eq(1)').val();
                var _h = $('#contentItemData div.video input:eq(2)').val();
                var _m4v = $('#contentItemData div.video input:eq(3)').val();
                var _ogv = $('#contentItemData div.video input:eq(4)').val();
                newdiv.html("<div style='width:150px;float:left;'>" +
                    "<label style=' float: left;'><strong>Video</strong></label>" +
                    "<span style='clear:both;float: left;color: #999'>edit poster url</span>" +
                    "<span style='margin-top: 9px;clear:both;float: left;color: #999'>edit video width</span>" +
                    "<span style='margin-top: 9px;clear:both;float: left;color: #999'>edit video height</span>" +
                    "<span style='margin-top: 9px;clear:both;float: left;color: #999'>edit video m4v src</span>" +
                    "<span style='margin-top: 9px;clear:both;float: left;color: #999'>edit video ogv src</span></div>" +
                    "<div style='margin-left: 10px;margin-top: 10px;width: 50%;float: left;'>" +
                    "<input style='width: 100%;' type='text' value='" + _tmb + "'/>" +
                    "<input style='width: 100%;' type='text' value='" + _w + "'/>" +
                    "<input style='width: 100%;' type='text' value='" + _h + "'/>" +
                    "<input style='width: 100%;' type='text' value='" + _m4v + "'/>" +
                    "<input style='width: 100%;' type='text' value='" + _ogv + "'/></div>" +
                    "<div style='margin-top: 50px;float: left;'>" +
                    "<input style='margin-left: 25px;float: left;' class='update-item-button button' type='button' value='Update'/>" +
                    "<input style='margin-left: 10px;' class='remove-item-button button' type='button' value='Remove Item'/></div>" +
                    "<div style='clear: both;'></div>")
                main.append(newdiv);
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = _contentText;
                meta_data.tmb = _tmb;
                meta_data.width = _w;
                meta_data.height = _h;
                meta_data.m4v = _m4v;
                meta_data.ogv = _ogv;
                break;
        }
        $('#contentItems input.update-item-button:last').click(updateButtonClick);
        $('#contentItems input.remove-item-button:last').click(removeButtonClick);

        // prepare data to send to the database
        var meta_key = "content-item-" + nContentItems;
        var value = JSON.stringify(meta_data);
        var data = {
            action: 'update_post_meta',
            post_id: _postID,
            meta_key: meta_key,
            meta_value: value
        };
        // send
        $.post(ajaxurl, data, function(response) {
            newdiv.fadeIn();
            // reset fields
            $('#contentItemData div.image input:first').val("");
            $('#contentItemData div.youtube textarea:first').val("");
            $('#contentItemData div.vimeo textarea:first').val("");
            $('#contentItemData div.video input').val("");
        });

        nContentItems++;
    });


    // image browse button
    $('#addContentItem input.browse-image-button').click(imageButtonClick);
    // remove item button
    $('#contentItems input.remove-item-button').click(removeButtonClick);
    // update item button
    $('#contentItems input.update-item-button').click(updateButtonClick);


    // functions
    function imageButtonClick(e) {
        var the_input = $(e.target).parents('div:first').find('input:first');
        window.send_to_editor = function(html) {
            imgurl = $('img', html).attr('src');
            the_input.val(imgurl);
            tb_remove();
        }
        tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
        return false;
    }

    function updateButtonClick(e) {
        var _div = $(e.target).parents('div.content-item');
        var _contentType = _div.attr('class').split(' ')[2];
        // prepare data to send to the database
        var _postID = $('#post_ID').attr('value');
        var _itemNr = parseInt(_div.attr('class'));
        var _metaKey = 'content-item-' + _itemNr;
        var meta_data;


        switch (_contentType) {
            case "image":
                var _url = _div.find('input:first').val();
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = "Image";
                meta_data.url = _url;
                break;
            case "youtube":
                var _url = _div.find('textarea:first').val();
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = "Youtube";
                meta_data.url = _url;
                break;
            case "vimeo":
                var _url = _div.find('textarea:first').val();
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = "Vimeo";
                meta_data.url = _url;
                break;
            case "video":
                var _tmb = _div.find('input:eq(0)').val();
                var _w = _div.find('input:eq(1)').val();
                var _h = _div.find('input:eq(2)').val();
                var _m4v = _div.find('input:eq(3)').val();
                var _ogv = _div.find('input:eq(4)').val();
                var meta_data = {};
                meta_data.type = _contentType;
                meta_data.name = "Video";
                meta_data.tmb = _tmb;
                meta_data.width = _w;
                meta_data.height = _h;
                meta_data.m4v = _m4v;
                meta_data.ogv = _ogv;
                break;
        }

        // prepare data to send to the database
        var value = JSON.stringify(meta_data);
        var data = {
            action: 'update_post_meta',
            post_id: _postID,
            meta_key: _metaKey,
            meta_value: value
        };

        // change button text
        $(e.target).val("Updating")
        // send
        $.post(ajaxurl, data, function(response) {
            $(e.target).val("Update")
        });


    }

    function removeButtonClick(e) {
        // prepare data to send to the database
        var _postID = $('#post_ID').attr('value');
        var _itemNr = parseInt($(e.target).parents('div.content-item').attr('class'));
        var _metaKey = 'content-item-' + _itemNr;


        // send data to db
        var data = {
            action: 'delete_post_meta',
            post_id: _postID,
            meta_key:  _metaKey
        };
        $.post(ajaxurl, data, function(response) {
            $('#contentItems div.' + _itemNr).hide();
        });
    }


});

