/* 
	Unmark Bookmarklet: Auto Close
	This bookmarklet will open a small window, save the current page to Unmark, then close the window in one second.
*/


// Full

javascript: (function () {
    l = 'http://unmark.it/mark/add?url=' + encodeURIComponent(window.location.href) + '&title=' + encodeURIComponent(document.title) + '&v=2&nowindow=yes&';
    var w = window.open(l + 'noui=1', 'Unmark_AutoClose', 'location=0,links=0,scrollbars=0,toolbar=0,width=5,height=5');
    w.blur(); window.focus();
    setTimeout(function () {
    	w.close();
    }, 1000);
    return false;
})();

// Minified

javascript:(function(){l="http://unmark.it/mark/add?url="+encodeURIComponent(window.location.href)+"&title="+encodeURIComponent(document.title)+"&v=2&nowindow=yes&";var e=window.open(l+"noui=1","Unmark_AutoClose","location=0,links=0,scrollbars=0,toolbar=0,width=5,height=5");e.blur();window.focus();setTimeout(function(){e.close()},1000);return false})()