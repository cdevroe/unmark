/* 
	Unmark Bookmarklet: Default
	This bookmarklet will open a window, allow the user to choose a label and add notes.
*/


// Full

javascript: (function () {
    l = 'http://unmark.it/mark/add?url=' + encodeURIComponent(window.location.href) + '&title=' + encodeURIComponent(document.title) + '&v=1&nowindow=yes&';
    var w = window.open(l + 'noui=1', 'Unmark', 'location=0,links=0,scrollbars=0,toolbar=0,width=594,height=585');
    return false;
})();

// Minified

javascript:(function(){l="http://unmark.it/mark/add?url="+encodeURIComponent(window.location.href)+"&title="+encodeURIComponent(document.title)+"&v=1&nowindow=yes&";var e=window.open(l+"noui=1","Unmark","location=0,links=0,scrollbars=0,toolbar=0,width=594,height=485");return false})()