self.addEventListener("install", event => {
    event.waitUntil(
      caches.open("pwa-cache-v1").then(cache => {
        return cache.addAll([
          "/",
          "/index.php",
          "/css/styles.css",
          "/img/logo/logo.png",
          "/img/logo/logo.png"
        ]);
      })
    );
  });
  
  self.addEventListener("fetch", event => {
    event.respondWith(
      caches.match(event.request).then(response => {
        return response || fetch(event.request);
      })
    );
  });
  