var CACHE_NAME = 'unmark-version-1.8';
var urlsToCache = [
  '/assets/css/unmark.css',
  '/custom/assets/js/production/unmark.loggedin.js'
];

self.addEventListener('install', function(event) {
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
      caches.match(event.request)
        .then(function(response) {
          // Cache hit - return response
          if (response) {
            return response;
          }
          return fetch(event.request);
        }
      )
    );
  });

  // self.addEventListener("OpenGraphData", function(e) {
  //   var shareData = JSON.parse(e.detail);
  //   $("#shared").text(shareData.url);
  // })