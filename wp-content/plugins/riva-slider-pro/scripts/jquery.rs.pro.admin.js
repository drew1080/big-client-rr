/*
 
 * This file contains the necessary Javascript & jQuery functionality for the Riva Slider's admin panel to function correctly.
 * Most of it is Ajax used with PHP contained in other files to send, receive and save slideshow and image data. Some of it also controls the aesthetics of the admin area.
 * The admin panel will cease to function if Javascript is disabled (as will the slideshow itself), so make sure it is not.
 *
 * Last modified 03/01/2012
 *
 *
*/

// Delete multiple images
jQuery.rivaDeleteImages = function() {
	
	var time = 0;
	
	var alert = confirm( delete_all_images );
	
	if(alert) {
	
		jQuery('.rs-image-holder').each(function() {
			time += 300;
			var self = this;
			setTimeout(function() {
				jQuery(self).fadeOut(300, function() {
					jQuery(self).remove();
					if(jQuery('.rs-image-holder').length == 0)
						jQuery('<h3 class="sub-title" id="no-images">'+ no_images +'</h3>').appendTo('#rs-left .rs-holder-wrap');
				});
			}, time);
			jQuery('input[name="image-index"]').val(1);
		});
		jQuery('#start_image').find('option').remove();
	
	}
	
}

// Delete single image
jQuery.rivaDeleteSingle = function(index) {
	
	var increment = 1;
	
	jQuery('.rs-image-holder').eq(index-1).fadeOut(300, function() {
		jQuery(this).remove();
		if(jQuery('.rs-image-holder').length > 0) {
			jQuery('.rs-image-holder').each(function() {
				jQuery('h3', this).text(image + increment);
				jQuery('.delete-image', this).attr('href', 'javascript: jQuery.rivaDeleteSingle('+ increment +');');
				increment++;	
			});
		} else {
			jQuery('<h3 class="sub-title" id="no-images">'+ no_images +'</h3>').appendTo('#rs-left .rs-holder-wrap');
		}
		
		jQuery('input[name="image-index"]', '#rs-left').val(increment);
	});
	jQuery('#start_image').find('option:last').remove();
	jQuery('#start_image').find('option:first').attr('selected', 'selected');
	
}

// Admin tabs
jQuery.rivaTab = function(selector) {
	jQuery('.content-holder').css({ 'display' : 'none' });
	jQuery('.rs-navigation li a').each(function() {
		if(jQuery(this).hasClass('current') == true)
			jQuery(this).removeClass('current');
	});
	jQuery('#'+ selector).css({ 'display' : 'block' });
	jQuery('.rs-navigation li a[id*='+ selector +']').addClass('current');
}

jQuery.rivaImageTab = function(selector) {
	
	jQuery('.rs-option-panel').each(function() {
		if(jQuery(this).hasClass('open')) {
			jQuery(this).removeClass('open').addClass('closed');
		}
	});
	jQuery('.'+ selector).removeClass('closed').addClass('open');
	
	jQuery('a', '.rs-image-navigation').each(function() {
		if(jQuery(this).hasClass('current'))
			jQuery(this).removeClass('current');
	});
	
	jQuery('a#'+ selector +'_tab', '.rs-image-navigation').addClass('current');
	
}

jQuery.rivaMetaTab = function(selector) {
	if ( jQuery('.rs-meta-box.open' ).length > 0 ) {
		var self = jQuery('.rs-meta-box.open');
		if ( self.attr('id') != selector ) {
			jQuery('#rs-meta-navigation li a[id*='+ selector +']').addClass('current');
			var id = jQuery('.rs-meta-box.open').attr('id');
			jQuery('#rs-meta-navigation li a[id*='+ id +']').removeClass('current');
			jQuery('.rs-meta-box.open').slideUp(300, function() {
				jQuery(this).removeClass('open');
				jQuery('#'+ selector).slideDown(300).addClass('open');
			});
		} else {
			jQuery('#rs-meta-navigation li a[id*='+ selector +']').addClass('current');
			var id = jQuery('.rs-meta-box.open').attr('id');
			jQuery('#rs-meta-navigation li a[id*='+ id +']').removeClass('current');
			jQuery('.rs-meta-box.open').slideUp(300, function() {
				jQuery(this).removeClass('open');
			});
		}
	} else {
		jQuery('#rs-meta-navigation li a[id*='+ selector +']').addClass('current');
		var id = jQuery('.rs-meta-box.open').attr('id');
		jQuery('#rs-meta-navigation li a[id*='+ id +']').removeClass('current');
		jQuery('#'+ selector).slideDown(300).addClass('open');
	}
}

jQuery.rivaEditor = function(selector) {
	var id = jQuery('#rs-select-slideshow').val();
	send_to_editor( '[rivasliderpro id="'+ id +'"]' );
}

jQuery(document).ready(function() {
	
	// Show loading icon
	jQuery('.submitdelete, .submitduplicate, #doaction', '#riva_slider_pro_manage_slideshows').live('click', function() {
		jQuery('#riva_slider_pro_manage_slideshows #loading').css({ 'visibility' : 'visible' });	
	});
	jQuery('input[name="save"]', '#riva_slider_pro_edit_options').live('click', function() {
		jQuery('#riva_slider_pro_edit_options #loading').css({ 'visibility' : 'visible' });	
	});
	jQuery('input[name="save"]', '#riva_slider_pro_global_settings').live('click', function() {
		jQuery('#riva_slider_pro_global_settings #loading').css({ 'visibility' : 'visible' });	
	});
	
	// Reset Settings confirm message
	jQuery('#reset-settings', '#riva_slider_pro_global_settings').live('click', function() {
		var alert = confirm( confirm_reset_settings );
		if ( alert )
			return true;
		else
			return false;
	});
	
	// Success & error messages
	if ( jQuery('.rs-message').length >= 1 ) {
		jQuery('.rs-message').each(function() {
			var self = this;
			if ( jQuery(self).hasClass('dont-hide') == false ) {
				var t = '';
				t = setTimeout(function() {
					jQuery(self).slideUp(300, function() {
						jQuery(self).remove();	
					});
				}, 5000);
			}
		});
	}
	
	// Save slideshow settings checks
	jQuery('#riva_slider_pro_edit_options input[name="save"]').live('click', function() {
		
		// No errors until one is found
		var error = false;
		var type = '';
		var name = jQuery('input[name="name"]');
		// Check if any values are invalid
		jQuery('.int, #rs-max-images').each(function() {
			var number = jQuery(this).val();
			if(isNaN(number) || number == '') {
				jQuery(this).addClass('error');
				error = true;
				type = not_valid;
			}
		});
		jQuery('.hex').each(function() {
			var hex = jQuery(this).val();
			if(hex.length < 3 || hex.length > 6 || hex == '') {
				jQuery(this).addClass('error');
				error = true;
				type = not_valid;
			}
		});
		if ( name.val() == '' ) {
			name.addClass('error');
			error = true;
			type = no_name;
		}
		if ( error == true ) {
			if ( jQuery('.rs-message').length <= 1 ) {
				var t = '';
				jQuery('<div id="message" class="rs-message error"><p>'+ type +'</p></div>').insertAfter('#rs-title').hide().slideDown(300);
				t = setTimeout(function() {
					jQuery('.rs-message').slideUp(300, function() {
						jQuery(this).remove();
					})
				}, 5000);
			}
			return false;
		}
		return true;
	
	});
	
	// Make image containers sortable
	jQuery('.rs-holder-wrap:first').sortable({
		opacity: 0.5,
		handle: '.rs-image-container',
		containment: '.rs-holder-wrap',
		axis: 'y',
		tolerance: 'pointer',
		update: function() {
			var index = 0;
			jQuery('.rs-image-title', '.rs-image-holder').each(function() {
				index++;
				jQuery(this).find('h3').text(image + index);
				jQuery(this).find('a').attr('href', 'javascript: jQuery.rivaDeleteSingle('+ index +');');
			});
		}
	});
	
	// Edit image options container
	jQuery('.rs-image-container').live('mouseenter', function() {
		if (jQuery(this).parent().hasClass('active')) {
			jQuery('h3', this).append('<span class="sub-text"> &raquo; '+ close_image +'</span>');
		}
		else {
			jQuery('h3', this).append('<span class="sub-text"> &raquo; '+ edit_image +'</span>');
		}
	});
		
	jQuery('.rs-image-container').live('mouseleave', function() {
		jQuery('h3 span', this).remove();
	});
		
	jQuery('.rs-image-container').live('click', function() {
		var parent = jQuery(this).parent();
		
		if(jQuery('div.open').length >= 1 && jQuery('.rs-image-options', parent).hasClass('closed')) {
			jQuery('.active').removeClass('active');
			jQuery('.rs-image-options.open').slideUp(400, function() {
				jQuery(this).removeClass('open').addClass('closed');
			});
			jQuery(parent).addClass('active');
			jQuery('.rs-image-options', parent).slideDown(400, function() {
				jQuery(this).removeClass('closed').addClass('open');
			});
		}
		
		else if(jQuery('.rs-image-options', parent).hasClass('closed')) {
			jQuery(parent).addClass('active');
			jQuery('.rs-image-options', parent).slideDown(400, function() {
				jQuery(this).removeClass('closed').addClass('open');
			});
		}
		
		else if(jQuery('.rs-image-options', parent).hasClass('open')) {
			jQuery('.active').removeClass('active');
			jQuery('.rs-image-options', parent).slideUp(400, function() {
				jQuery(this).removeClass('open').addClass('closed');
			});			
		}
	});
	
	
	// Serial code check
	var serial = jQuery('#serial_code');
	if ( serial.val() == '' )
		serial.addClass('error');
	
	// Quick change slideshow
	jQuery('#quick_change').live('change', function() {
		var id = jQuery(this).val();
		if (id != 0) {
			window.location = slideshows_url +'&id='+ id;
		}
	});
	
	
	// Preloading icons selection box
	jQuery('.radio-icons').live('click', function() {
		jQuery('.radio-icons').each(function() {
			if(jQuery(this).hasClass('selected'))
				jQuery(this).removeClass('selected').animate({ 'opacity' : '0.3' }, 200);
		});
		jQuery(this).addClass('selected');
		var img = jQuery('img', this).attr('src');
		jQuery('#preload-current-icon').attr('src', img);
	});
	
	jQuery('.radio-icons').live('mouseenter', function() {
		if (jQuery(this).hasClass('selected') == false) {
			jQuery(this).animate({ 'opacity' : '1' }, 200);
		}
	});
		
	jQuery('.radio-icons').live('mouseleave', function() {
		if (jQuery(this).hasClass('selected') == false) {
			jQuery(this).animate({ 'opacity' : '0.3' }, 200);
		}			
	});
	
	
	// Hides and disables specific options depending on other options current values, etc.
	jQuery('#show-preload-icons').live('click', function() {
		jQuery('#radio-icons-holder').slideToggle(200);
	});
	
	if(jQuery('input[name="transparent_preloader"]').is(':checked')) {
		jQuery('input[name="preload_colour"]').attr('disabled', 'disabled').addClass('disabled');
	}
	
	jQuery('input[name="transparent_preloader"]').live('click', function() {
		if(jQuery(this).is(':checked')) {
			jQuery('input[name="preload_colour"]').attr('disabled', 'disabled').addClass('disabled');
		} else {
			jQuery('input[name="preload_colour"]').removeAttr('disabled').removeClass('disabled');
		}
	});
	
	if(jQuery('#auto_play_false').is(':checked')) {
		jQuery('input[name="play_once"]').attr('disabled', 'disabled').addClass('disabled');
	}
	
	jQuery('input[name="auto_play"]').live('click', function() {
		if(jQuery(this).val() == 'false') {
			jQuery('input[name="play_once"]').attr('disabled', 'disabled').addClass('disabled');
		} else {
			jQuery('input[name="play_once"]').removeAttr('disabled').removeClass('disabled');
		}
	});
	
	if(jQuery('#play_once_false').is(':checked')) {
		jQuery('input[name="buttons_play_once"]').attr('disabled', 'disabled').addClass('disabled');
	}
	
	jQuery('input[name="play_once"]').live('click', function() {
		if(jQuery(this).val() == 'false') {
			jQuery('input[name="buttons_play_once"]').attr('disabled', 'disabled').addClass('disabled');
		} else {
			jQuery('input[name="buttons_play_once"]').removeAttr('disabled').removeClass('disabled');
		}
	});
	
	if(jQuery('#direction_nav_disable').is(':checked')) {
		jQuery('#direction_nav_pos').attr('disabled', 'disabled').addClass('disabled');
	}
	
	jQuery('input[name="direction_nav"]').live('click', function() {
		if(jQuery(this).val() == 'disable') {
			jQuery('#direction_nav_pos').attr('disabled', 'disabled').addClass('disabled');
		} else {
			jQuery('#direction_nav_pos').removeAttr('disabled').removeClass('disabled');
		}
	});
	
	if(jQuery('#control_nav_disable').is(':checked')) {
		jQuery('#control_nav_index').attr('disabled', 'disabled').addClass('disabled');
		jQuery('#control_nav_pos').attr('disabled', 'disabled').addClass('disabled');
	}
	
	jQuery('input[name="control_nav"]').live('click', function() {
		if(jQuery(this).val() == 'disable') {
			jQuery('#control_nav_index').attr('disabled', 'disabled').addClass('disabled');
			jQuery('#control_nav_pos').attr('disabled', 'disabled').addClass('disabled');
		} else {
			jQuery('#control_nav_index').removeAttr('disabled').removeClass('disabled');
			jQuery('#control_nav_pos').removeAttr('disabled').removeClass('disabled');
		}
	});
	
	if(jQuery('#control_nav_index_thumbnails').is(':checked')) {
		jQuery('#thumb_height').parents('tr').show();
		jQuery('#thumb_width').parents('tr').show();
	} else {
		jQuery('#thumb_height').parents('tr').hide();
		jQuery('#thumb_width').parents('tr').hide();
	}
	
	jQuery('label[for*="control_nav_index_"]').live('click', function() {
		if(jQuery(this).find('input').val() == 'thumbnails') {
			jQuery('#thumb_height').parents('tr').fadeIn(400);
			jQuery('#thumb_width').parents('tr').fadeIn(400);
		} else {
			jQuery('#thumb_height').parents('tr').fadeOut(400);
			jQuery('#thumb_width').parents('tr').fadeOut(400);
		}
	});
	
	var pause_pos = jQuery('#pause_button_pos').val();
	jQuery('select[name*="play-button-"]').each(function() {
			
		if ( jQuery(this).val() == pause_pos ) {
			var next = jQuery('option:selected', this).next().val();
			jQuery(this).val(next);
		}
		
		jQuery('option', this).each(function() {
			var text = jQuery(this).text();
			if ( jQuery(this).val() == pause_pos )
				jQuery(this).attr('disabled', 'disabled').text( text + pause_pos_text );
		});
		
	});
	jQuery('#pause_button_enable').live('click', function() {		
		jQuery('select[name*="play-button-"]').each(function() {
				
			if ( jQuery(this).val() == pause_pos ) {
				var next = jQuery('option:selected', this).next().val();
				jQuery(this).val(next);
			}
			
			jQuery('option', this).each(function() {
				var text = jQuery(this).text();
				if ( jQuery(this).val() == pause_pos )
					jQuery(this).attr('disabled', 'disabled').text( text + pause_pos_text );
			});
			
		});	
	});
	jQuery('#pause_button_pos').live('change', function() {
		
		var pause_pos_new = jQuery(this).val();
		
		jQuery('select[name*="play-button-"]').each(function() {
			
			if ( jQuery(this).val() == pause_pos_new ) {
				var next = jQuery('option:selected', this).next().val();
				jQuery(this).val(next);
			}
			
			jQuery('option', this).each(function() {
				jQuery(this).removeAttr('disabled');
				if ( jQuery(this).val() == pause_pos_new ) {
					var text = jQuery(this).text();
					jQuery(this).attr('disabled', 'disabled').text( text + pause_pos_text );
				} else {
					var text = jQuery(this).text();
					if ( text.indexOf( ' -' ) != -1 ) {
						text = text.split(' -');
						jQuery(this).text( text[ 0 ] );
					}
				}
			});
			
		});
		
	});
	jQuery('#pause_button_disable').live('click', function() {
		var play = jQuery('select[name*="play-button-"]');
		jQuery('option', play).each(function() {
			jQuery(this).removeAttr('disabled');
			var text = jQuery(this).text();
			if ( text.indexOf(' -' ) != -1 ) {
				text = text.split(' -');
				jQuery(this).text( text[ 0 ] );
			}
		});
	});
	
	// Meta options
	jQuery('.rs-image-link').find('input').each(function() {
		if ( jQuery(this).is(':checked') ) {
			if ( jQuery(this).val() == 'webpage' ) {
				// Disable video fields
				jQuery('.rs-video-url').find('input').addClass('disabled').parent().hide();
				jQuery('.rs-play-button').hide();
				jQuery('.rs-autoplay-video').hide();
				jQuery('.rs-related-videos').hide();
				// Enable webpage fields
				jQuery('.rs-webpage-url').find('input').removeClass('disabled').parent().show();
				
			}
			else if ( jQuery(this).val() == 'video' ) {
				// Enable video fields
				jQuery('.rs-video-url').find('input').removeClass('disabled').parent().show();
				jQuery('.rs-play-button').show();
				jQuery('.rs-autoplay-video').show();
				jQuery('.rs-related-videos').show();
				// Disable webpage fields
				jQuery('.rs-webpage-url').find('input').addClass('disabled').parent().hide();
			}
		}
	});
	
	jQuery('.rs-image-link').find('input').live('click', function() {
		if ( jQuery(this).val() == 'webpage' ) {
			// Disable video fields
			jQuery('.rs-video-url').find('input').addClass('disabled').parent().slideUp('300');
			jQuery('.rs-play-button').slideUp('300');
			jQuery('.rs-autoplay-video').slideUp('300');
			jQuery('.rs-related-videos').slideUp('300');
			// Enable webpage fields
			setTimeout( function() { jQuery('.rs-webpage-url').find('input').removeClass('disabled').parent().slideDown('300'); }, 400 );	
		}
		else if ( jQuery(this).val() == 'video' ) {
			// Enable video fields
			setTimeout( function() {
				jQuery('.rs-video-url').find('input').removeClass('disabled').parent().slideDown('300');
				jQuery('.rs-play-button').slideDown('300');
				jQuery('.rs-autoplay-video').slideDown('300');
				jQuery('.rs-related-videos').slideDown('300');
			}, 400 );
			// Disable webpage fields
			jQuery('.rs-webpage-url').find('input').addClass('disabled').parent().slideUp('300');
		}
	});
	
	jQuery('input[name*="image-link-"]').each(function() {
		if ( jQuery(this).is(':checked') )
			if ( jQuery(this).val() == 'webpage' ) {
				var id = jQuery(this).attr('name');
				id = id.replace('image-link-', '');
				// Disable video fields
				jQuery('#video-url-'+ parseFloat(id)).addClass('disabled').parent().parent().hide();
				jQuery('#play-button-'+ parseFloat(id)).parent().parent().hide();
				jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]').parent().parent().parent().hide();
				jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]');
				jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]').parent().parent().parent().hide();
				jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]');
				jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]').parent().parent().parent().hide();
				jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]');
				// Enable webpage fields
				jQuery('#webpage-url-'+ parseFloat(id)).removeClass('disabled').parent().parent().show();
				
			}
			else if ( jQuery(this).val() == 'video' ) {
				var id = jQuery(this).attr('name');
				id = id.replace('image-link-', '');
				// Enable video fields
				jQuery('#video-url-'+ parseFloat(id)).removeClass('disabled').parent().parent().show();
				jQuery('#play-button-'+ parseFloat(id)).parent().parent().show();
				jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]').parent().parent().parent().show();
				jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]');
				jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]').parent().parent().parent().show();
				jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]');
				jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]').parent().parent().parent().show();
				jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]');
				// Disable webpage fields
				jQuery('#webpage-url-'+ parseFloat(id)).addClass('disabled').parent().parent().hide();
			}
	});
	
	jQuery('input[name*="image-link-"]').live('click', function() {
		if ( jQuery(this).val() == 'webpage' ) {
			var id = jQuery(this).attr('name');
			id = id.replace('image-link-', '');
			// Disable video 
			jQuery('#video-url-'+ parseFloat(id)).addClass('disabled').parent().parent().slideUp(200);
			jQuery('#play-button-'+ parseFloat(id)).parent().parent().slideUp(200);
			jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]').parent().parent().parent().slideUp(200);
			jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]');
			jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]').parent().parent().parent().slideUp(200);
			jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]');
			jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]').parent().parent().parent().slideUp(200);
			jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]');
			// Enable webpage fields
			setTimeout(function() { jQuery('#webpage-url-'+ parseFloat(id)).removeClass('disabled').parent().parent().slideDown(200); }, 200);
		}
		else if ( jQuery(this).val() == 'video' ) {
			var id = jQuery(this).attr('name');
			id = id.replace('image-link-', '');
			// Enable video fields
			setTimeout(function() {
				jQuery('#video-url-'+ parseFloat(id)).removeClass('disabled').parent().parent().slideDown(200);
				jQuery('#play-button-'+ parseFloat(id)).parent().parent().slideDown(200);
				jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]').parent().parent().parent().slideDown(200);
				jQuery('input[name*="autoplay-video-'+ parseFloat(id) +'"]');
				jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]').parent().parent().parent().slideDown(200);
				jQuery('input[name*="related-videos-'+ parseFloat(id) +'"]');
				jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]').parent().parent().parent().slideDown(200);
				jQuery('input[name*="video-quality-'+ parseFloat(id) +'"]');
			}, 200);
			// Disable webpage fields
			jQuery('#webpage-url-'+ parseFloat(id)).addClass('disabled').parent().parent().slideUp(200);
		}
	});
	
	if(jQuery('#pause_button_disable').is(':checked')) {
		jQuery('#pause_button_pos').attr('disabled', 'disabled').addClass('disabled');
	}
	
	jQuery('input[name="pause_button"]').live('click', function() {
		if(jQuery(this).val() == 'enable') {
			jQuery('#pause_button_pos').removeAttr('disabled').removeClass('disabled');
		}
		else {
			jQuery('#pause_button_pos').attr('disabled', 'disabled').addClass('disabled');
		}
	});
	
	if(jQuery('#edit_section').val() != 'select_a_section') {
		var edit_section = jQuery('#edit_section').val();
		jQuery('#'+ edit_section +'_styling').show();
	}
	
	jQuery('#edit_section').live('change', function() {
		if(jQuery(this).val() != 'select_a_section') {
			jQuery('option', this).each(function() {
				var hide = jQuery(this).val();
				jQuery('#'+ hide +'_styling').fadeOut(400);
			});
			var edit_section = jQuery('#edit_section').val();
			setTimeout(function() { jQuery('#'+ edit_section +'_styling').fadeIn(400); }, 400);
		} else {
			jQuery('option', this).each(function() {
				var hide = jQuery(this).val();
				jQuery('#'+ hide +'_styling').fadeOut(400);
			});
		}
	});
	
	if(jQuery('#skin').val() == 'custom') {
		jQuery('#custom_styling').show();
	}
	
	jQuery('#skin').live('change', function() {
		if(jQuery(this).val() == 'custom') {
			jQuery('#custom_styling').fadeIn(400);
			jQuery('#edit_section').val( jQuery('option', '#edit_section').eq(0).val() );
		} else {
			jQuery('#custom_styling').fadeOut(400);
			jQuery('option', '#edit_section').each(function() {
				var hide = jQuery(this).val();
				jQuery('#'+ hide +'_styling').fadeOut(400);
			});
		}
	});
	
	if(jQuery('#control_nav_index').val() == 'thumbnails') {
		jQuery('#edit_section').find('option').each(function() {
			if(jQuery(this).val() == 'control_navigation') {
				var value = jQuery(this).text();
				jQuery(this).attr('disabled', 'disabled').text( value +' - Styling disabled. Slideshow is set to use thumbnails' );
			}
		});
	}
	
	jQuery('#control_nav_index').live('change', function() {
	
		if(jQuery(this).val() == 'thumbnails') {
			jQuery('#edit_section').find('option').each(function() {
				if(jQuery(this).val() == 'control_navigation') {
					var value = jQuery(this).text();
					jQuery(this).attr('disabled', 'disabled').text( value + control_nav_disabled );
					jQuery('#control_navigation_styling').hide();
					jQuery('#edit_section').val( jQuery('#edit_section').find('option:first').val() );
				}
			});
		} else {
			jQuery('#edit_section').find('option').each(function() {
				if(jQuery(this).val() == 'control_navigation') {
					var value = jQuery(this).text();
					value = value.split(' -');
					jQuery(this).removeAttr('disabled').text( value[ 0 ] );
				}
			});
		}
		
	});
	
	
	// Box pop-up if no slideshow name has been entered for some time
	if(jQuery('input[name="name"]').val() == '') {
		jQuery('<div id="enter-name" class="rs-message-name updated"><span class="ie7-fix">'+ enter_name +': </span><input type="text" value="" /><span class="ie7-fix"><a href="javascript: return false;" class="button-secondary">'+ close_message +'</a></span></div>')
			.insertAfter('#rs-title').hide().delay(500).slideDown(300);
			
		var entername = jQuery('#enter-name');
		
		jQuery('input', entername).keyup(function() {
			var self = this;
			setTimeout(function() {
				var value = jQuery(self).val();
				jQuery('input[name="name"]').val(value);
			}, 200);
		});
		
		jQuery('a', entername).live('click', function() {
			jQuery(entername).slideUp(300, function() {
				jQuery(this).remove();	
			});
		});
	}
	
	// jQuery colour picker
	jQuery.rivaColorPicker = function(selector) {
		jQuery(selector).each(function() {
			var self = this;
			var input = jQuery(self).parent().find('input[type="text"]');
			var color = input.val();
			jQuery(self).css({ 'background-color' : '#'+ color });
			jQuery(self).ColorPicker({
				color: color,
				onChange: function(hsb, hex, rgb) {
					jQuery(self).css({ 'background-color' : '#'+ hex });
					input.val(hex);
				}
			});
		});
	};
		
	jQuery.rivaColorPicker('.rs-color-picker');
	
	// Image source
	var value = jQuery('#image-source select[name="rs-image-source"]').val();
	if ( value == 'images_from_posts' ) {
		jQuery('select[name="rs-image-category"]').fadeIn(300);
		jQuery('#manage-images').parent().slideUp(300);
		if ( jQuery('.rs-image-holder').length >= 1 ) {
			jQuery('.rs-image-holder').slideUp(300, function() {
				if ( jQuery('#alt-source').length < 1 )
					jQuery('<h3 class="sub-title" id="alt-source" />').text(alt_source).hide().appendTo('#rs-left .rs-holder-wrap').fadeIn(200);
			});
		} else
			if ( jQuery('#no-images').length >= 1 )
				jQuery('#no-images').fadeOut(300, function() {
					jQuery('<h3 class="sub-title" id="alt-source" />').text(alt_source).hide().appendTo('#rs-left .rs-holder-wrap').fadeIn(200);
				});
	} else if ( value == 'this_panel' ) {
		jQuery('select[name="rs-image-category"]').fadeOut(300);
		if ( jQuery('#alt-source').length >= 1 )
			jQuery('#alt-source').fadeOut(300, function() {
				jQuery(this).remove();
				jQuery('#manage-images').parent().slideDown(300);
				if ( jQuery('.rs-image-holder').length >= 1 )
					jQuery('.rs-image-holder').slideDown(300);
				else
					jQuery('<h3 class="sub-title" id="no-images">'+ no_images +'</h3>').appendTo('#rs-left .rs-holder-wrap');
			});
	}
		
	jQuery('#image-source select[name="rs-image-source"]').live('change', function() {
		var value = jQuery(this).val();
		if ( value == 'images_from_posts' ) {
			jQuery('select[name="rs-image-category"]').fadeIn(300);
			jQuery('div#max-images').slideDown(300);
			jQuery('#manage-images').parent().slideUp(300);
			if ( jQuery('.rs-image-holder').length >= 1 ) {
				jQuery('.rs-image-holder').each(function() {
					jQuery(this).fadeOut(300, function() {
						if ( jQuery('#alt-source').length < 1 )
							jQuery('<h3 class="sub-title" id="alt-source" />').text(alt_source).hide().appendTo('#rs-left .rs-holder-wrap').fadeIn(200);
					});
				});
			}
			else {
				jQuery('#no-images').fadeOut(300, function() {
					jQuery('<h3 class="sub-title" id="alt-source" />').text(alt_source).hide().appendTo('#rs-left .rs-holder-wrap').fadeIn(200);
					jQuery(this).remove();
				});
			}
			jQuery('#start_image').find('option').remove();
			var value = jQuery('#image-source select[name="rs-image-category"]').val();
			value = value.split( ', ' );
			value = parseFloat( value[ 1 ] );
			for ( i = 0; i < value; i++ ) {
				jQuery('<option value="'+ ( i + 1 ) +'">'+ ( i + 1 ) +'</option>').appendTo('#start_image');
				jQuery('#start_image').find('option:first').attr('selected', 'selected');
			}
		} else if ( value == 'this_panel' ) {
			jQuery('select[name="rs-image-category"]').fadeOut(300);
			jQuery('div#max-images').slideUp(300);
			jQuery('#manage-images').parent().slideDown(300);
			if ( jQuery('.rs-image-holder').length >= 1 ) {
				jQuery('#alt-source').fadeOut(300, function() {
					jQuery('.rs-image-holder').fadeIn(200);
					jQuery(this).remove();
				});
			}
			else {
				jQuery('#alt-source').fadeOut(300, function() {
					jQuery('<h3 class="sub-title" id="no-images" />').text(no_images).hide().appendTo('#rs-left .rs-holder-wrap').fadeIn(200);
					jQuery(this).remove();
				});
			}
			jQuery('#start_image').find('option').remove();
			var value = jQuery('.rs-image-holder').length;
			for ( i = 0; i < value; i++ ) {
				jQuery('<option value="'+ ( i + 1 ) +'">'+ ( i + 1 ) +'</option>').appendTo('#start_image');
				jQuery('#start_image').find('option:first').attr('selected', 'selected');
			}
		}
	});
	
	jQuery('#image-source select[name="rs-image-category"]').live('change', function() {
		var value = jQuery(this).val();
		value = value.split( ', ' );
		value = parseFloat( value[ 1 ] );
		jQuery('#start_image').find('option').remove();
		for ( i = 0; i < value; i++ ) {
			jQuery('<option value="'+ ( i + 1 ) +'">'+ ( i + 1 ) +'</option>').appendTo('#start_image');
			jQuery('#start_image').find('option:first').attr('selected', 'selected');
		}
	});
	
	jQuery('.radio[for*="control_nav_index_"]').live('click', function() {
		if ( jQuery(this).find('input').is(':checked') )
			var checked = jQuery(this).find('input').val();
			
		jQuery('.radio[for*="control_nav_index_"]').removeClass('control-current');
			
		if ( jQuery(this).find('input').val() == checked )
			jQuery(this).addClass('control-current');
	});
	
	jQuery('.radio[for*="control_nav_index_"]').each(function() {
		if ( jQuery(this).find('input').is(':checked') )
			jQuery(this).addClass('control-current');
	});
	
	jQuery('#riva-slider-meta .rs-get_post_info label').live('click', function() {
		if ( jQuery(this).find('input').is(':checked') ) {
			jQuery('.rs-image-link').find('input').each(function() {
				if ( jQuery(this).is(':checked' ) ) {
					if ( jQuery(this).val() == 'webpage' ) {
						jQuery('.rs-webpage-url').find('input').addClass('disabled').parent().slideUp(300);
					}
					else if ( jQuery(this).val() == 'video' ) {
						jQuery('.rs-video-url').find('input').addClass('disabled').parent().slideUp(300);
						jQuery('.rs-play-button').slideUp(300);
						jQuery('.rs-autoplay-video').slideUp(300);
						jQuery('.rs-related-videos').slideUp(300);
					}
				}
			});
			jQuery('#content-title').attr('disabled', 'disabled').addClass('disabled');
			jQuery('#content-text').attr('disabled', 'disabled').addClass('disabled');
			jQuery('.rs-image-link').find('input').attr('disabled', 'disabled').addClass('disabled');
		}
		else {
			jQuery('.rs-image-link').find('input').each(function() {
				if ( jQuery(this).is(':checked' ) ) {
					if ( jQuery(this).val() == 'webpage' ) {
						jQuery('.rs-webpage-url').find('input').removeClass('disabled').parent().slideDown(300);
					}
					else if ( jQuery(this).val() == 'video' ) {
						jQuery('.rs-video-url').find('input').removeClass('disabled').parent().slideDown(300);
						jQuery('.rs-play-button').slideDown(300);
						jQuery('.rs-autoplay-video').slideDown(300);
						jQuery('.rs-related-videos').slideDown(300);
					}
				}
			});
			jQuery('#content-title').removeAttr('disabled').removeClass('disabled');
			jQuery('#content-text').removeAttr('disabled').removeClass('disabled');
			jQuery('.rs-image-link').find('input').removeAttr('disabled').removeClass('disabled');
		}
	});
	
	if ( jQuery('#riva-slider-meta .rs-get_post_info label').find('input').is(':checked') ) {
		jQuery('.rs-image-link').find('input').each(function() {
			if ( jQuery(this).is(':checked' ) ) {
				if ( jQuery(this).val() == 'webpage' ) {
					jQuery('.rs-webpage-url').find('input').addClass('disabled').parent().hide();
				}
				else if ( jQuery(this).val() == 'video' ) {
					jQuery('.rs-video-url').find('input').addClass('disabled').parent().hide();
					jQuery('.rs-play-button').hide();
					jQuery('.rs-autoplay-video').hide();
					jQuery('.rs-related-videos').hide();
				}
			}
		});
		jQuery('#content-title').attr('disabled', 'disabled').addClass('disabled');
		jQuery('#content-text').attr('disabled', 'disabled').addClass('disabled');
		jQuery('.rs-image-link').find('input').attr('disabled', 'disabled').addClass('disabled');
	}
	else {
		jQuery('.rs-image-link').find('input').each(function() {
			if ( jQuery(this).is(':checked' ) ) {
				if ( jQuery(this).val() == 'webpage' ) {
					jQuery('.rs-webpage-url').find('input').removeClass('disabled').parent().show();
				}
				else if ( jQuery(this).val() == 'video' ) {
					jQuery('.rs-video-url').find('input').removeClass('disabled').parent().show();
					jQuery('.rs-play-button').show();
					jQuery('.rs-autoplay-video').show();
					jQuery('.rs-related-videos').show();
				}
			}
		});
		jQuery('#content-title').removeAttr('disabled').removeClass('disabled');
		jQuery('#content-text').removeAttr('disabled').removeClass('disabled');
		jQuery('.rs-image-link').find('input').removeAttr('disabled').removeClass('disabled');
	}
	
});

/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 * 
 * Dual licensed under the MIT and GPL licenses
 * 
 */

(function($) {
	var ColorPicker = function () {
		var
			ids = {},
			inAction,
			charMin = 65,
			visible,
			tpl = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color"></div><div class="colorpicker_hex"><input type="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_submit"></div></div>',
			defaults = {
				eventName: 'click',
				onShow: function () {},
				onBeforeShow: function(){},
				onHide: function () {},
				onChange: function () {},
				onSubmit: function () {},
				color: 'ff0000',
				livePreview: true,
				flat: false
			},
			fillRGBFields = function  (hsb, cal) {
				var rgb = HSBToRGB(hsb);
				$(cal).data('colorpicker').fields
					.eq(1).val(rgb.r).end()
					.eq(2).val(rgb.g).end()
					.eq(3).val(rgb.b).end();
			},
			fillHSBFields = function  (hsb, cal) {
				$(cal).data('colorpicker').fields
					.eq(4).val(hsb.h).end()
					.eq(5).val(hsb.s).end()
					.eq(6).val(hsb.b).end();
			},
			fillHexFields = function (hsb, cal) {
				$(cal).data('colorpicker').fields
					.eq(0).val(HSBToHex(hsb)).end();
			},
			setSelector = function (hsb, cal) {
				$(cal).data('colorpicker').selector.css('backgroundColor', '#' + HSBToHex({h: hsb.h, s: 100, b: 100}));
				$(cal).data('colorpicker').selectorIndic.css({
					left: parseInt(150 * hsb.s/100, 10),
					top: parseInt(150 * (100-hsb.b)/100, 10)
				});
			},
			setHue = function (hsb, cal) {
				$(cal).data('colorpicker').hue.css('top', parseInt(150 - 150 * hsb.h/360, 10));
			},
			setCurrentColor = function (hsb, cal) {
				$(cal).data('colorpicker').currentColor.css('backgroundColor', '#' + HSBToHex(hsb));
			},
			setNewColor = function (hsb, cal) {
				$(cal).data('colorpicker').newColor.css('backgroundColor', '#' + HSBToHex(hsb));
			},
			keyDown = function (ev) {
				var pressedKey = ev.charCode || ev.keyCode || -1;
				if ((pressedKey > charMin && pressedKey <= 90) || pressedKey == 32) {
					return false;
				}
				var cal = $(this).parent().parent();
				if (cal.data('colorpicker').livePreview === true) {
					change.apply(this);
				}
			},
			change = function (ev) {
				var cal = $(this).parent().parent(), col;
				if (this.parentNode.className.indexOf('_hex') > 0) {
					cal.data('colorpicker').color = col = HexToHSB(fixHex(this.value));
				} else if (this.parentNode.className.indexOf('_hsb') > 0) {
					cal.data('colorpicker').color = col = fixHSB({
						h: parseInt(cal.data('colorpicker').fields.eq(4).val(), 10),
						s: parseInt(cal.data('colorpicker').fields.eq(5).val(), 10),
						b: parseInt(cal.data('colorpicker').fields.eq(6).val(), 10)
					});
				} else {
					cal.data('colorpicker').color = col = RGBToHSB(fixRGB({
						r: parseInt(cal.data('colorpicker').fields.eq(1).val(), 10),
						g: parseInt(cal.data('colorpicker').fields.eq(2).val(), 10),
						b: parseInt(cal.data('colorpicker').fields.eq(3).val(), 10)
					}));
				}
				if (ev) {
					fillRGBFields(col, cal.get(0));
					fillHexFields(col, cal.get(0));
					fillHSBFields(col, cal.get(0));
				}
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
				cal.data('colorpicker').onChange.apply(cal, [col, HSBToHex(col), HSBToRGB(col)]);
			},
			blur = function (ev) {
				var cal = $(this).parent().parent();
				cal.data('colorpicker').fields.parent().removeClass('colorpicker_focus');
			},
			focus = function () {
				charMin = this.parentNode.className.indexOf('_hex') > 0 ? 70 : 65;
				$(this).parent().parent().data('colorpicker').fields.parent().removeClass('colorpicker_focus');
				$(this).parent().addClass('colorpicker_focus');
			},
			downIncrement = function (ev) {
				var field = $(this).parent().find('input').focus();
				var current = {
					el: $(this).parent().addClass('colorpicker_slider'),
					max: this.parentNode.className.indexOf('_hsb_h') > 0 ? 360 : (this.parentNode.className.indexOf('_hsb') > 0 ? 100 : 255),
					y: ev.pageY,
					field: field,
					val: parseInt(field.val(), 10),
					preview: $(this).parent().parent().data('colorpicker').livePreview					
				};
				$(document).bind('mouseup', current, upIncrement);
				$(document).bind('mousemove', current, moveIncrement);
			},
			moveIncrement = function (ev) {
				ev.data.field.val(Math.max(0, Math.min(ev.data.max, parseInt(ev.data.val + ev.pageY - ev.data.y, 10))));
				if (ev.data.preview) {
					change.apply(ev.data.field.get(0), [true]);
				}
				return false;
			},
			upIncrement = function (ev) {
				change.apply(ev.data.field.get(0), [true]);
				ev.data.el.removeClass('colorpicker_slider').find('input').focus();
				$(document).unbind('mouseup', upIncrement);
				$(document).unbind('mousemove', moveIncrement);
				return false;
			},
			downHue = function (ev) {
				var current = {
					cal: $(this).parent(),
					y: $(this).offset().top
				};
				current.preview = current.cal.data('colorpicker').livePreview;
				$(document).bind('mouseup', current, upHue);
				$(document).bind('mousemove', current, moveHue);
			},
			moveHue = function (ev) {
				change.apply(
					ev.data.cal.data('colorpicker')
						.fields
						.eq(4)
						.val(parseInt(360*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.y))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upHue = function (ev) {
				fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				$(document).unbind('mouseup', upHue);
				$(document).unbind('mousemove', moveHue);
				return false;
			},
			downSelector = function (ev) {
				var current = {
					cal: $(this).parent(),
					pos: $(this).offset()
				};
				current.preview = current.cal.data('colorpicker').livePreview;
				$(document).bind('mouseup', current, upSelector);
				$(document).bind('mousemove', current, moveSelector);
			},
			moveSelector = function (ev) {
				change.apply(
					ev.data.cal.data('colorpicker')
						.fields
						.eq(6)
						.val(parseInt(100*(150 - Math.max(0,Math.min(150,(ev.pageY - ev.data.pos.top))))/150, 10))
						.end()
						.eq(5)
						.val(parseInt(100*(Math.max(0,Math.min(150,(ev.pageX - ev.data.pos.left))))/150, 10))
						.get(0),
					[ev.data.preview]
				);
				return false;
			},
			upSelector = function (ev) {
				fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
				$(document).unbind('mouseup', upSelector);
				$(document).unbind('mousemove', moveSelector);
				return false;
			},
			enterSubmit = function (ev) {
				$(this).addClass('colorpicker_focus');
			},
			leaveSubmit = function (ev) {
				$(this).removeClass('colorpicker_focus');
			},
			clickSubmit = function (ev) {
				var cal = $(this).parent();
				var col = cal.data('colorpicker').color;
				cal.data('colorpicker').origColor = col;
				setCurrentColor(col, cal.get(0));
				cal.data('colorpicker').onSubmit(col, HSBToHex(col), HSBToRGB(col), cal.data('colorpicker').el);
			},
			show = function (ev) {
				var cal = $('#' + $(this).data('colorpickerId'));
				cal.data('colorpicker').onBeforeShow.apply(this, [cal.get(0)]);
				var pos = $(this).offset();
				var viewPort = getViewport();
				var top = pos.top + this.offsetHeight;
				var left = pos.left;
				if (top + 176 > viewPort.t + viewPort.h) {
					top -= this.offsetHeight + 176;
				}
				if (left + 356 > viewPort.l + viewPort.w) {
					left -= 356;
				}
				cal.css({left: left + 'px', top: top + 'px'});
				if (cal.data('colorpicker').onShow.apply(this, [cal.get(0)]) != false) {
					cal.show();
				}
				$(document).bind('mousedown', {cal: cal}, hide);
				return false;
			},
			hide = function (ev) {
				if (!isChildOf(ev.data.cal.get(0), ev.target, ev.data.cal.get(0))) {
					if (ev.data.cal.data('colorpicker').onHide.apply(this, [ev.data.cal.get(0)]) != false) {
						ev.data.cal.hide();
					}
					$(document).unbind('mousedown', hide);
				}
			},
			isChildOf = function(parentEl, el, container) {
				if (parentEl == el) {
					return true;
				}
				if (parentEl.contains) {
					return parentEl.contains(el);
				}
				if ( parentEl.compareDocumentPosition ) {
					return !!(parentEl.compareDocumentPosition(el) & 16);
				}
				var prEl = el.parentNode;
				while(prEl && prEl != container) {
					if (prEl == parentEl)
						return true;
					prEl = prEl.parentNode;
				}
				return false;
			},
			getViewport = function () {
				var m = document.compatMode == 'CSS1Compat';
				return {
					l : window.pageXOffset || (m ? document.documentElement.scrollLeft : document.body.scrollLeft),
					t : window.pageYOffset || (m ? document.documentElement.scrollTop : document.body.scrollTop),
					w : window.innerWidth || (m ? document.documentElement.clientWidth : document.body.clientWidth),
					h : window.innerHeight || (m ? document.documentElement.clientHeight : document.body.clientHeight)
				};
			},
			fixHSB = function (hsb) {
				return {
					h: Math.min(360, Math.max(0, hsb.h)),
					s: Math.min(100, Math.max(0, hsb.s)),
					b: Math.min(100, Math.max(0, hsb.b))
				};
			}, 
			fixRGB = function (rgb) {
				return {
					r: Math.min(255, Math.max(0, rgb.r)),
					g: Math.min(255, Math.max(0, rgb.g)),
					b: Math.min(255, Math.max(0, rgb.b))
				};
			},
			fixHex = function (hex) {
				var len = 6 - hex.length;
				if (len > 0) {
					var o = [];
					for (var i=0; i<len; i++) {
						o.push('0');
					}
					o.push(hex);
					hex = o.join('');
				}
				return hex;
			}, 
			HexToRGB = function (hex) {
				var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
				return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
			},
			HexToHSB = function (hex) {
				return RGBToHSB(HexToRGB(hex));
			},
			RGBToHSB = function (rgb) {
				var hsb = {
					h: 0,
					s: 0,
					b: 0
				};
				var min = Math.min(rgb.r, rgb.g, rgb.b);
				var max = Math.max(rgb.r, rgb.g, rgb.b);
				var delta = max - min;
				hsb.b = max;
				if (max != 0) {
					
				}
				hsb.s = max != 0 ? 255 * delta / max : 0;
				if (hsb.s != 0) {
					if (rgb.r == max) {
						hsb.h = (rgb.g - rgb.b) / delta;
					} else if (rgb.g == max) {
						hsb.h = 2 + (rgb.b - rgb.r) / delta;
					} else {
						hsb.h = 4 + (rgb.r - rgb.g) / delta;
					}
				} else {
					hsb.h = -1;
				}
				hsb.h *= 60;
				if (hsb.h < 0) {
					hsb.h += 360;
				}
				hsb.s *= 100/255;
				hsb.b *= 100/255;
				return hsb;
			},
			HSBToRGB = function (hsb) {
				var rgb = {};
				var h = Math.round(hsb.h);
				var s = Math.round(hsb.s*255/100);
				var v = Math.round(hsb.b*255/100);
				if(s == 0) {
					rgb.r = rgb.g = rgb.b = v;
				} else {
					var t1 = v;
					var t2 = (255-s)*v/255;
					var t3 = (t1-t2)*(h%60)/60;
					if(h==360) h = 0;
					if(h<60) {rgb.r=t1;	rgb.b=t2; rgb.g=t2+t3}
					else if(h<120) {rgb.g=t1; rgb.b=t2;	rgb.r=t1-t3}
					else if(h<180) {rgb.g=t1; rgb.r=t2;	rgb.b=t2+t3}
					else if(h<240) {rgb.b=t1; rgb.r=t2;	rgb.g=t1-t3}
					else if(h<300) {rgb.b=t1; rgb.g=t2;	rgb.r=t2+t3}
					else if(h<360) {rgb.r=t1; rgb.g=t2;	rgb.b=t1-t3}
					else {rgb.r=0; rgb.g=0;	rgb.b=0}
				}
				return {r:Math.round(rgb.r), g:Math.round(rgb.g), b:Math.round(rgb.b)};
			},
			RGBToHex = function (rgb) {
				var hex = [
					rgb.r.toString(16),
					rgb.g.toString(16),
					rgb.b.toString(16)
				];
				$.each(hex, function (nr, val) {
					if (val.length == 1) {
						hex[nr] = '0' + val;
					}
				});
				return hex.join('');
			},
			HSBToHex = function (hsb) {
				return RGBToHex(HSBToRGB(hsb));
			},
			restoreOriginal = function () {
				var cal = $(this).parent();
				var col = cal.data('colorpicker').origColor;
				cal.data('colorpicker').color = col;
				fillRGBFields(col, cal.get(0));
				fillHexFields(col, cal.get(0));
				fillHSBFields(col, cal.get(0));
				setSelector(col, cal.get(0));
				setHue(col, cal.get(0));
				setNewColor(col, cal.get(0));
			};
		return {
			init: function (opt) {
				opt = $.extend({}, defaults, opt||{});
				if (typeof opt.color == 'string') {
					opt.color = HexToHSB(opt.color);
				} else if (opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined) {
					opt.color = RGBToHSB(opt.color);
				} else if (opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined) {
					opt.color = fixHSB(opt.color);
				} else {
					return this;
				}
				return this.each(function () {
					if (!$(this).data('colorpickerId')) {
						var options = $.extend({}, opt);
						options.origColor = opt.color;
						var id = 'collorpicker_' + parseInt(Math.random() * 1000);
						$(this).data('colorpickerId', id);
						var cal = $(tpl).attr('id', id);
						if (options.flat) {
							cal.appendTo(this).show();
						} else {
							cal.appendTo(document.body);
						}
						options.fields = cal
											.find('input')
												.bind('keyup', keyDown)
												.bind('change', change)
												.bind('blur', blur)
												.bind('focus', focus);
						cal
							.find('span').bind('mousedown', downIncrement).end()
							.find('>div.colorpicker_current_color').bind('click', restoreOriginal);
						options.selector = cal.find('div.colorpicker_color').bind('mousedown', downSelector);
						options.selectorIndic = options.selector.find('div div');
						options.el = this;
						options.hue = cal.find('div.colorpicker_hue div');
						cal.find('div.colorpicker_hue').bind('mousedown', downHue);
						options.newColor = cal.find('div.colorpicker_new_color');
						options.currentColor = cal.find('div.colorpicker_current_color');
						cal.data('colorpicker', options);
						cal.find('div.colorpicker_submit')
							.bind('mouseenter', enterSubmit)
							.bind('mouseleave', leaveSubmit)
							.bind('click', clickSubmit);
						fillRGBFields(options.color, cal.get(0));
						fillHSBFields(options.color, cal.get(0));
						fillHexFields(options.color, cal.get(0));
						setHue(options.color, cal.get(0));
						setSelector(options.color, cal.get(0));
						setCurrentColor(options.color, cal.get(0));
						setNewColor(options.color, cal.get(0));
						if (options.flat) {
							cal.css({
								position: 'relative',
								display: 'block'
							});
						} else {
							$(this).bind(options.eventName, show);
						}
					}
				});
			},
			showPicker: function() {
				return this.each( function () {
					if ($(this).data('colorpickerId')) {
						show.apply(this);
					}
				});
			},
			hidePicker: function() {
				return this.each( function () {
					if ($(this).data('colorpickerId')) {
						$('#' + $(this).data('colorpickerId')).hide();
					}
				});
			},
			setColor: function(col) {
				if (typeof col == 'string') {
					col = HexToHSB(col);
				} else if (col.r != undefined && col.g != undefined && col.b != undefined) {
					col = RGBToHSB(col);
				} else if (col.h != undefined && col.s != undefined && col.b != undefined) {
					col = fixHSB(col);
				} else {
					return this;
				}
				return this.each(function(){
					if ($(this).data('colorpickerId')) {
						var cal = $('#' + $(this).data('colorpickerId'));
						cal.data('colorpicker').color = col;
						cal.data('colorpicker').origColor = col;
						fillRGBFields(col, cal.get(0));
						fillHSBFields(col, cal.get(0));
						fillHexFields(col, cal.get(0));
						setHue(col, cal.get(0));
						setSelector(col, cal.get(0));
						setCurrentColor(col, cal.get(0));
						setNewColor(col, cal.get(0));
					}
				});
			}
		};
	}();
	$.fn.extend({
		ColorPicker: ColorPicker.init,
		ColorPickerHide: ColorPicker.hidePicker,
		ColorPickerShow: ColorPicker.showPicker,
		ColorPickerSetColor: ColorPicker.setColor
	});
})(jQuery);