/* 
	Unmark Bookmarklet: Default
	This bookmarklet will open a window, allow the user to choose a label and add notes.
*/


// Full

javascript: (function () {
    l = 'http://unmark.it/mark/add?url=' + encodeURIComponent(window.location.href) + '&title=' + encodeURIComponent(document.title) + '&v=1&nowindow=yes&';
    var w = window.open(l + 'noui=1', 'Unmark', 'location=0,links=0,scrollbars=0,toolbar=0,width=594,height=585');
})();

// Minified

javascript:(function(){l="http://unmark.it/mark/add?url="+encodeURIComponent(window.location.href)+"&title="+encodeURIComponent(document.title)+"&v=1&nowindow=yes&";var e=window.open(l+"noui=1","Unmark","location=0,links=0,scrollbars=0,toolbar=0,width=594,height=485")})()

// URL Encoded in Link

<a href="javascript:(function()%7Bl%3D%22https://unmark.it/mark/add%3Furl%3D%22%2BencodeURIComponent(window.location.href)%2B%22%26title%3D%22%2BencodeURIComponent(document.title)%2B%22%26v%3D1%26nowindow%3Dyes%26%22%3Bvar%20e%3Dwindow.open(l%2B%22noui%3D1%22%2C%22Unmark%22%2C%22location%3D0%2Clinks%3D0%2Cscrollbars%3D0%2Ctoolbar%3D0%2Cwidth%3D594%2Cheight%3D485%22)%3B%7D)()" class="bookmarklet">Unmark +</a>