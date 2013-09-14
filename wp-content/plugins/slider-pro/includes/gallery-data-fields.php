<div class="dynamic-fields gallery-data-fields">
	
	<label title="dynamic_gallery_post"><?php _e('Post', 'slider_pro'); ?></label>
	<input name="slide[<?php echo $counter;?>][settings][dynamic_gallery_post]" type="text" 
		   value="<?php echo sliderpro_get_slide_setting($slide_settings, 'dynamic_gallery_post', 'dynamic'); ?>"/>
	
	
	<label title="dynamic_gallery_maximum"><?php _e('Maximum', 'slider_pro'); ?></label>
	<input name="slide[<?php echo $counter;?>][settings][dynamic_gallery_maximum]" type="text" 
		   value="<?php echo sliderpro_get_slide_setting($slide_settings, 'dynamic_gallery_maximum', 'dynamic'); ?>"/>
	
	
	<label title="dynamic_gallery_offset"><?php _e('Offset', 'slider_pro'); ?></label>
	<input name="slide[<?php echo $counter;?>][settings][dynamic_gallery_offset]" type="text" 
		   value="<?php echo sliderpro_get_slide_setting($slide_settings, 'dynamic_gallery_offset', 'dynamic'); ?>"/>


	<label title="dynamic_gallery_type"><?php _e('Type', 'slider_pro'); ?></label>
	<select name="slide[<?php echo $counter;?>][settings][dynamic_gallery_type]">
		<?php 
			$list = sliderpro_get_settings_list('dynamic_gallery_type');
			foreach ($list as $entry) {
				$selected = sliderpro_get_slide_setting($slide_settings, 'dynamic_gallery_type', 'dynamic') == $entry ? 'selected="selected"' : "";
				echo "<option value=\"$entry\" $selected>" . sliderpro_get_settings_pretty_name($entry) . "</option>";
			}
		?>
	</select>
	
</div>