// service-worker.js

// Cache static assets
const staticCacheName = 'your-app-static-v1';
const staticAssets = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/path/to/other-assets',
];

self.addEventListener('install', event => {
    event.waitUntil(
      caches.open(staticCacheName)
        .then(cache => {
          return Promise.all(
            staticAssets.map(url => {
              return fetch(url)
                .then(response => cache.put(url, response));
            })
          );
        })
    );
  });
  

  self.addEventListener('fetch', event => {
    // Exclude requests with 'chrome-extension' scheme
    if (event.request.url.startsWith('chrome-extension://')) {
      return;
    }
  
    event.respondWith(
      caches.match(event.request)
        .then(response => {
          if (response) {
            return response;
          }
          return fetch(event.request)
            .then(fetchResponse => {
              if (!fetchResponse || fetchResponse.status !== 200 || fetchResponse.type !== 'basic') {
                console.log('Fetch request failed:', event.request.url);
                return fetchResponse;
              }
              const responseClone = fetchResponse.clone();
              caches.open(staticCacheName)
                .then(cache => {
                  cache.put(event.request, responseClone);
                });
              return fetchResponse;
            });
        })
    );
  });
  
  
