// 快取名稱
const CACHE_NAME = 'my-site-cache-v1';
const urlsToCache = [
  '/',
  'index.html',
  'style.css',
  'script.js',
  // 其他需要快取的資源
];

// 安裝 Service Worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll([
        './',
        './index.php',
        './styles.css',
        './scripts.js',
        // 確保所有路徑都是正確的
      ])
      .catch(error => {
        console.error('快取添加失敗:', error);
        // 即使某些資源失敗，也可以繼續安裝 Service Worker
      });
    })
  );
});

// 更新 Service Worker
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// 處理網路請求
self.addEventListener('fetch', event => {
  // 只處理 HTTP/HTTPS 請求
  if (event.request.url.startsWith('http')) {
    event.respondWith(
      caches.match(event.request)
        .then(response => {
          if (response) {
            return response;
          }
          
          return fetch(event.request).then(
            response => {
              if (!response || response.status !== 200 || response.type !== 'basic') {
                return response;
              }

              const responseToCache = response.clone();

              caches.open(CACHE_NAME)
                .then(cache => {
                  // 只快取 HTTP/HTTPS 請求
                  if (event.request.url.startsWith('http')) {
                    cache.put(event.request, responseToCache);
                  }
                });

              return response;
            }
          );
        })
        .catch(() => {
          return caches.match('./offline.html');
        })
    );
  }
});
