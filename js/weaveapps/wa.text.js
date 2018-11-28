$wa( document ).ready(function() {
 	var x =  $wa('#banner_type').val();
 			if(x=='1'||x=='2'){
			$wa("#image").closest('tr').hide();
			$wa("#link").closest('tr').hide();
			$wa("#link_target").closest('tr').hide();
			$wa("#caption").closest('tr').hide();
			$wa("#caption_bg_colour").closest('tr').hide();
			$wa("#caption_font_colour").closest('tr').hide();
			$wa("#caption_position").closest('tr').hide();
			}else if(x=='0'){
			$wa("#video_height").closest('tr').hide();
			$wa("#video_id").closest('tr').hide();
			$wa("#auto_play").closest('tr').hide();
			$wa("#caption").closest('tr').show();
			$wa("#caption_bg_colour").closest('tr').show();
			$wa("#caption_font_colour").closest('tr').show();
			$wa("#caption_position").closest('tr').show();
			}
    $wa('#banner_type').change(function() {
			var x =  $wa(this).val();
			if(x=='1'||x=='2'){
			$wa("#image").closest('tr').hide();
			$wa("#link").closest('tr').hide();
			$wa("#link_target").closest('tr').hide();
			$wa("#caption").closest('tr').hide();
			$wa("#caption_bg_colour").closest('tr').hide();
			$wa("#caption_font_colour").closest('tr').hide();
			$wa("#caption_position").closest('tr').hide();
			$wa("#video_height").closest('tr').show();
			$wa("#video_id").closest('tr').show();
			$wa("#auto_play").closest('tr').show();
			}else if(x=='0'){
			$wa("#image").closest('tr').show();	
			$wa("#video_height").closest('tr').hide();
			$wa("#video_id").closest('tr').hide();
			$wa("#auto_play").closest('tr').hide();
			$wa("#caption").closest('tr').show();
			$wa("#caption_bg_colour").closest('tr').show();
			$wa("#caption_font_colour").closest('tr').show();
			$wa("#caption_position").closest('tr').show();
			$wa("#link").closest('tr').show();
			$wa("#link_target").closest('tr').show();
			}
    });
	$wa("#caption_bg_colour").spectrum({
		showAlpha:true,
		showInput: false,
		preferredFormat: "rgb",
		move: function(c) {
		$wa(this).val(c.toRgbString())
		}
	});
	$wa("#caption_font_colour").spectrum({
		showAlpha:true,
		showInput: false,
		preferredFormat: "rgb",
		move: function(c) {
		$wa(this).val(c.toRgbString())
		  }
	});
	$wa("#progress_bar_colour").spectrum({
		showAlpha:true,
		showInput: false,
		preferredFormat: "rgb",
		move: function(c) {
		$wa(this).val(c.toRgbString())
		  }
	});

	$wa('#sort_order').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
		$wa('#slider_width').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
			$wa('#slider_height').keyup(function () { 
    this.value = this.value.replace(/[^0-9\.]/g,'');
});
});