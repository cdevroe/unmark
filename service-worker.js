var CACHE_NAME = 'unmark-version-2.0';
var urlsToCache = [
  '/assets/css/unmark.css',
  '/assets/libraries/jquery/jquery-2.1.0.min.js',
  '/custom/assets/js/production/unmark.loggedin.js',
  '/assets/js/plugins/modernizr-2.7.1.min.js',
  '/assets/js/production/unmark.plugins.js'
];

self.addEventListener('install', function(event) {
  self.skipWaiting();
  console.log('Service Worker: Installed');
  // Perform install steps
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Service Worker: Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Call Activate Event
self.addEventListener('activate', e => {
  console.log('Service Worker: Activated');
  // Remove unwanted caches
  e.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cache => {
          if (cache !== CACHE_NAME) {
            console.log('Service Worker: Clearing Old Cache ' + cache);
            return caches.delete(cache);
          }
        })
      );
    })
  );
});

// Call Fetch Event
self.addEventListener('fetch', e => {
  console.log('Service Worker: Fetching ' + e.request);
  e.respondWith(fetch(e.request).catch(() => caches.match(e.request)));
});