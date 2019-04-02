/*
  Determine if browser supports a Service Worker,
  if so, register it for the PWA
*/

if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
        // Registration was successful
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
      }).catch(function(err) {
        // registration failed :(
        //console.log('ServiceWorker registration failed: ', err);
      });
    });
  }