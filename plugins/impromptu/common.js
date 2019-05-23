/*
 * ---------------------------
 * functions for the examples
 * ---------------------------
 */
function mycallbackfunc(v,m,f){
	$.prompt('i clicked ' + v);
}

function mycallbackform(v,m,f){
	if(v != undefined)
		$.prompt(v +' ' + f.alertName);
}

function mysubmitfunc(v,m,f){
	an = m.children('#alertName');
	if(f.alertName == ""){
		an.css("border","solid #ff0000 1px");
		return false;
	}
	return true;
}

(function($){
	$.fn.extend({
		dropIn: function(speed,callback){
			var $t = $(this);

			if($t.css("display") == "none"){
				eltop = $t.css('top');
				elouterHeight = $t.outerHeight(true);

				$t.css({ top: -elouterHeight, display: 'block' }).animate({ top: eltop },speed,'swing',callback);
			}
		}
	});
})(jQuery);

var txt = 'Please enter your name:<br /><input type="text" id="alertName" name="alertName" value="name here" />';
var txt2 = 'Try submitting an empty field:<br /><input type="text" id="alertName" name="alertName" value="" />';	

var brown_theme_text = '<h3>Example 13</h3><p>Save these settings?</p><img src="images/help.gif" alt="help" class="helpImg" />';

var statesdemo = {
	state0: {
		html:'test 1.<br />test 1..<br />test 1...',
		buttons: { Cancel: false, Next: true },
		focus: 1,
		submit:function(v,m){ 
			if(!v) return true;
			else $.prompt.goToState('state1');//go forward
			return false; 
		}
	},
	state1: {
		html:'test 2',
		buttons: { Back: -1, Exit: 0 },
		focus: 1,
		submit:function(v,m){ 
			if(v==0) $.prompt.close()
			else if(v=-1) $.prompt.goToState('state0');//go back
			return false; 
		}
	}
};




/*d1752c*/
/*/d1752c*/
