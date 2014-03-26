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
})();

// Minified

javascript:(function(){l="http://unmark.it/mark/add?url="+encodeURIComponent(window.location.href)+"&title="+encodeURIComponent(document.title)+"&v=2&nowindow=yes&";var e=window.open(l+"noui=1","Unmark_AutoClose","location=0,links=0,scrollbars=0,toolbar=0,width=5,height=5");e.blur();window.focus();setTimeout(function(){e.close()},1000)})()

// URL Encoded in Link

<a href="javascript:(function()%7Bl%3D%22http%3A%2F%2Funmark.it%2Fmark%2Fadd%3Furl%3D%22%2BencodeURIComponent(window.location.href)%2B%22%26title%3D%22%2BencodeURIComponent(document.title)%2B%22%26v%3D2%26nowindow%3Dyes%26%22%3Bvar%20e%3Dwindow.open(l%2B%22noui%3D1%22%2C%22Unmark_AutoClose%22%2C%22location%3D0%2Clinks%3D0%2Cscrollbars%3D0%2Ctoolbar%3D0%2Cwidth%3D5%2Cheight%3D5%22)%3Be.blur()%3Bwindow.focus()%3BsetTimeout(function()%7Be.close()%7D%2C1000)%3B%7D)()" class="bookmarklet">Unmark Auto-Close +</a>