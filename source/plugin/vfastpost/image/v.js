function _showVFlink() {
	if($('ft')){
		var viewPortHeight = parseInt(document.documentElement.clientHeight);
		var scrollHeight = parseInt(document.body.getBoundingClientRect().top);
		var basew = parseInt($('ft').clientWidth);
		var sw = $('vfpop_menu').clientWidth;
		if (basew < 1000) {
			var left = parseInt(fetchOffset($('ft'))['left']);
			left = left < sw ? left * 2 - sw : left;
			$('vfpop_menu').style.left = ( basew + left ) + 'px';
		} else {
			$('vfpop_menu').style.left = 'auto';
			$('vfpop_menu').style.right = 0;
		}

		if (BROWSER.ie && BROWSER.ie < 7) {
			$('vfpop_menu').style.top = viewPortHeight - scrollHeight - 233 + 'px';
		}
		if (scrollHeight < -100) {
			$('vfpop_menu').style.visibility = 'visible';
		} else {
			$('vfpop_menu').style.visibility = 'hidden';
		}
	}
}
_attachEvent(window, 'scroll', function(){_showVFlink();});
