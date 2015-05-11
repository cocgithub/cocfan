_attachEvent(window, 'scroll', function(){showGuessLike();});

ajaxget('forum.php?guessulike_ajax=1', 'guessLike');
function showGuessLike() {
	var ft = $('ft');
	if(ft && !getcookie('guessulikenotice')){
		var guessLike = $('guessLike');
		var viewPortHeight = parseInt(document.documentElement.clientHeight);
		var scrollHeight = parseInt(document.body.getBoundingClientRect().top);
		guessLike.style.left = 10+'px';
		guessLike.style.right = 0;

		if (BROWSER.ie && BROWSER.ie < 7) {
			guessLike.style.top = viewPortHeight - scrollHeight - 150 + 'px';
		}
		if (scrollHeight < -100) {
			guessLike.style.visibility = 'visible';
		} else {
			guessLike.style.visibility = 'hidden';
		}
	}
}