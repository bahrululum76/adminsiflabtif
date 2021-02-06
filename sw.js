const staticCacheName = 'asiflabtif-static-v1';
const dynamicCacheName = 'asiflabtif-dynamic-v1';
const assets = [	
	'./offline.html',
	'/assets/images/bg-top.png',
	'/assets/dist/css/main.min.css',
	'/assets/dist/js/client.min.js',
	'/assets/library/jquery/jquery-3.5.1.min.js',
	'/assets/library/materialize/materialize.min.css',
	'/assets/library/materialize/materialize.min.js',
	'/assets/library/pace-loader/pace.min.css',
	'/assets/library/pace-loader/pace.min.js',
	'https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp',
];


// install service worker
self.addEventListener('install', evt => {
	console.log("service worker has been installed");
	// evt.waitUntil(
	// 	caches.open(staticCacheName).then(cache => {
	// 		console.log('caching shell asset');
	// 		cache.addAll(assets);
	// 	})
	// );
});

// activate service worker
self.addEventListener('activate', evt => {
	console.log("service worker has been activated");
	// evt.waitUntil(
	// 	caches.keys().then(keys => {
	// 		console.log(keys);
	// 		return Promise.all(keys
	// 			.filter(key => key !== staticCacheName && key !== dynamicCacheName)
	// 			.map(key => caches.delete(key))
	// 		)
	// 	})
	// );
});

// fetch event
self.addEventListener('fetch', evt => {
	// console.log('fetch event', evt);
	// evt.respondWith(
	// 	caches.match(evt.request).then(cacheRes => {
	// 		return cacheRes || fetch(evt.request)
	// 			.catch(() => caches.match('./offline.html'));
	// 	})		
	// )
});

self.addEventListener('push', function (event) {
	console.log('[Service Worker] Push Received.');
	
	let notif = event.data.json();
	const title = notif['judul'];
	const options = {
		body: notif['pesan'],
		icon: './assets/images/icons/icon-96x96.png',
		badge: './assets/images/icons/icon-96x96.png'
	};
	self.notificationURL = notif['url'];
	event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
	console.log('[Service Worker] Notification click Received.');
	event.notification.close();

	event.waitUntil(
		clients.openWindow(self.notificationURL)
	);
});
