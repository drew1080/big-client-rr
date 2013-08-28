function riva_change_image(html, id) {
	
	var content = jQuery('#rs-left');
	var location = window.location.href;
	
	if(content.length > 0 && location.indexOf( 'rs_manage_slideshows' ) != -1) {
		
                // Hidden image increment input and other variables
		var image_url = jQuery('img', html).attr('src');
                var image_resized = jQuery(html).attr('href');
		
		jQuery('#rs-image-'+ id).find('.rs-image-box img').attr('src', image_resized);
		jQuery('#rs-image-'+ id).find('#image-resized-'+ id).val(image_resized);
		jQuery('#rs-image-'+ id).find('#image-url-'+ id).val(image_url);
		
	}
	
	tb_remove();
	
}

function riva_send_image_to_plugin(html) {
	
	var content = jQuery('#rs-left');
	var location = window.location.href;
	
	if(content.length > 0 && ( ( location.indexOf( 'rs_manage_slideshows' ) != -1 ) || ( location.indexOf( 'rs_new_slideshow' ) != -1 ) ) ) {
		
                // Hidden image increment input and other variables
		var input = jQuery('input[name="image-increment"]', '#rs-left');
                var index = jQuery('input[name="image-index"]', '#rs-left');
		var increment = parseFloat( input.val() );
		var holder = jQuery('.rs-holder-wrap', '#rs-left');
		
                // Clone the first image content as a template for the new image       
    		jQuery('.rs-image-template').clone().appendTo(holder);
		var new_content = jQuery('.rs-image-template').last();
                
                // Edit the parent class
                jQuery(new_content).removeClass('rs-image-template').addClass('rs-image-holder').attr('id', 'rs-image-'+ parseFloat(increment));
		
		// Temporary colour picker class
		jQuery('#rs-image-'+ parseFloat(increment)).addClass('rs-color-picker-temp-'+ parseFloat(increment));
		
		// Color picker function
		jQuery.rivaColorPicker('.rs-color-picker-temp-'+ parseFloat(increment) +' .rs-color-picker');
		
                // Clear the old image and add the new one
                jQuery('img', new_content).remove();
                jQuery('div.rs-image-box', new_content).append('<img src="'+ image_none +'" title="" class="rs-image" />');
		
                // Get some variables
		var image_url = jQuery('img', html).attr('src');
                var image_title = jQuery('img', html).attr('title');
                var image_alt = jQuery('img', html).attr('alt');
                var image_resized = jQuery(html).attr('href');
                
                // Modify cloned content
		jQuery('img', new_content).attr('src', image_resized).attr('title', image_title);
		jQuery('img', new_content).parent().attr('id', 'rs-image-'+ increment);
		
		jQuery('input, textarea, select', new_content).each(function() {
			
			if ( jQuery(this).hasClass('checked') ) {
				jQuery(this).removeAttr('checked').removeClass('checked');
				jQuery(this).attr('checked', 'checked');
			}
			
			// Replace all input names
			var name = jQuery(this).attr('name');
			jQuery(this).attr('name', name.replace('-0', '-'+ increment));
			
			// Replace all input id's
			var id = jQuery(this).attr('id');
			jQuery(this).attr('id', id.replace('-0', '-'+ increment));
		});
		
		var change = jQuery('.change-image', new_content).attr('href');
		change = change.replace('riva_id=0', 'riva_id='+ parseFloat(increment));
		jQuery('.change-image', new_content).attr('href', change);
		
		jQuery('label', new_content).each(function() {
			
			// Replace all labels
			var label = jQuery(this).attr('for');
			jQuery(this).attr('for', label.replace('-0', '-'+ increment));
			
		});
		
                jQuery('input[name*="image-url-"]', new_content).val(image_url);
                jQuery('input[name*="image-title-"]', new_content).val(image_title);
                jQuery('input[name*="image-alt-"]', new_content).val(image_alt);
                jQuery('input[name*="image-resized-"]', new_content).val(image_resized);
		jQuery('input[name*="image-id"]', new_content).val(increment).attr('name', 'image-id[]');
                
                // Edit the heading & delete link
                jQuery('h3', new_content).text(image + index.val());
                jQuery('.delete-image', new_content).attr('href', 'javascript: jQuery.rivaDeleteSingle('+ index.val() +');');
		
                // Modify the hidden image increment & image index inputs
		jQuery(input).val( increment+1 );
                jQuery(index).val( parseFloat( index.val() ) + 1 );
		
		// If no images text is showing remove it
		if(jQuery('#no-images').length >= 1)
			jQuery('#no-images').remove();
			
		// Add value to start image option
		var startImage = jQuery('#start_image option').length;
		jQuery('<option value="'+ parseFloat( startImage+1 ) +'">'+ parseFloat( startImage+1 ) +'</option>').appendTo('#start_image');
		
	} else {
		
		if ( jQuery('#rs-choose-image').length >= 1 )
			jQuery('#rs-choose-image').parent().remove();
		
                // Get some variables
		var image_url = jQuery('img', html).attr('src');
		var image_resized = jQuery(html).attr('href');
		var image_title = jQuery('img', html).attr('title');
		
		// Add/replace image
		jQuery('.rs-image-meta').html('<img src="'+ image_resized +'" width="258" height="86" style="margin-bottom: 5px;" />');
		jQuery('input[name="image-url"]').val(image_url);
		jQuery('input[name="image-resized"]').val(image_resized);
		
		// Edit values
		jQuery('input[name="image-title"]').val(image_title);
		
	}
	
	tb_remove();
	
}