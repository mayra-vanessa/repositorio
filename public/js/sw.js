// sw.js
const staticAssets = [
    '/',
    '../img/doc1.jpg',
    '../css/estilos.css',  // Ruta del archivo CSS
    '../img/hospital1.jpg',
    '../img/hospital2.jpg',
    '../img/cookie.png',
    '../img/logo.png',
    '../img/diabetes.jpg',
    '../img/epilepsia.jpg',
    '../img/alzheimer.jpg'
    // Agrega más recursos estáticos aquí
];


// sw.js
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open('my-cache').then((cache) => {
      return cache.addAll([
        '/',
        '/css/app.css',
        '/js/app.js',
        // Agrega aquí todos los archivos que deseas cachear
      ]);
    })
  );
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    caches.match(event.request).then((response) => {
      return response || fetch(event.request);
    })
  );
});


// Eliminar caches antiguas al activar la nueva
self.addEventListener('activate', function(event) {
    event.waitUntil(
      caches.keys().then(function(cacheNames) {
        return Promise.all(
          cacheNames.filter(function(cacheName) {
            return cacheName !== 'cache-rvec';
          }).map(function(cacheName) {
            return caches.delete(cacheName);
          })
        );
      })
    );
  });