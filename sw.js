
self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open('beltranmalinao').then(function(cache) {
      return cache.addAll([
        '/css/app.min.css',
        '/css/login.min.css',
        '/images/bmalinaomedclinic.png',
        '/images/logo.png',
        '/js/app.js',
        'https://unpkg.com/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
        'https://unpkg.com/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js',
        'https://unpkg.com/feather-icons@4.29.0/dist/feather.min.js',
        'https://unpkg.com/vue@3.2.47/dist/vue.global.prod.js'
      ])
    })
  )
})

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request).then(function(response) {
      return response || fetch(event.request)
    })
  )
})
