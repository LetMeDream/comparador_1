
//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

$(".next,.myButt,#righti").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	next_fs = $(this).parent().next();
	
	//activate next step on progressbar using the index of next_fs
	$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({
								'transform': 'scale('+scale+')',
								'position': 'absolute'
							});
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".previous,#lefti").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
	//de-activate current step on progressbar
	$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
	
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}, 
		//this comes from the custom easing plugin
		easing: 'easeInOutBack'
	});
});

$(".submit").click(function(){
	return true;
})


// Down here another thing.
$(document).ready(function () {
	$('video').height( $(window).height());
	$('video').width( $(window).width());
	$('.thisOne').height( $(window).height());
});
$(document).on('click','.btnn',function(){
	$('.c').fadeOut(500);
	$('#msform').fadeIn(1000);
})
/** dissapear form when esc */
$(document).keydown(function (e) { 
	if (e.key == 'Escape'){
		/** dissapear form when esc */
		$('.c').fadeIn(1000);
		$('#msform').fadeOut(500);	
	}
});
$(document).on('click','.downInicio',function(){
	/** dissapear to inicio */
	$('.c').fadeIn(1000);
	$('#msform').fadeOut(500);	
});
/** Botón responsive */
$(document).on('click','.volverInicio',function(){
	/** dissapear to inicio */
	$('.c').fadeIn(1000);
	$('#msform').fadeOut(500);	
})






/** Enable/disabling buttons */
$(document).ready(function(){
	$('#postalButt').attr('disabled',true);

	$('#subButton').attr('disabled', true);
	/** Postal */
    $('#postal').keyup(function(){
        $('#postalButt').prop('disabled', this.value == "" ? true : false); 
	})
	/** Email */
	$('#email').keyup(function(){
        $('#emailButt').prop('disabled', this.value == "" ? true : false); 
	})
	
	

	/** General info with several inputs */
	$('.lastStep').keyup(function() {

        var empty = false;
        $('.lastStep').each(function() {
            if ($(this).val().length == 0) {
                empty = true;
            }
        });

        if (empty) {
            $('#subButton').attr('disabled', 'disabled');
        } else {
            $('#subButton').removeAttr('disabled');
        }
    });

});