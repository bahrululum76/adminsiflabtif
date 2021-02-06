'use strict';

const applicationServerPublicKey = 'BPDJRLtSfOnHnYhuaI_qROVZR9c_YTCTJ3Yp6G3hNiTfG5GG9zU-bMs-NxJPGlmzMA8IDeT52a92tJRcL-qTYLg';
const pushButton = document.querySelector(".btn-notif");
// const subscriptionJson = document.querySelector('.js-subscription-json');

let isSubscribed = false;
let swRegistration = null;

function urlB64ToUint8Array(base64String) {
	const padding = '='.repeat((4 - base64String.length % 4) % 4);
	const base64 = (base64String + padding)
		.replace(/\-/g, '+')
		.replace(/_/g, '/');

	const rawData = window.atob(base64);
	const outputArray = new Uint8Array(rawData.length);

	for (let i = 0; i < rawData.length; ++i) {
		outputArray[i] = rawData.charCodeAt(i);
	}
	return outputArray;
}

function updateSubscriptionOnServer(subscription) {
	// TODO: Send subscription to application server
	if (subscription) {
		$.ajax({
			type: 'POST',
			url: "../auth/subscribe",
			data: {
				token: JSON.stringify(subscription)
			},
			success: function () {
				$('#modalPermission').modal('close');
				console.log("Success send subscription");
			}
		});
	} else {
		console.log("Failed send subscription");
	}
}

function subscribeUser() {
	const applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
	swRegistration.pushManager.subscribe({
			userVisibleOnly: true,
			applicationServerKey: applicationServerKey
		})
		.then(function (subscription) {
			// console.log('User is subscribed');
			updateSubscriptionOnServer(subscription);
			isSubscribed = true;
			// updateBtn();
		})
		.catch(function (err) {
			console.log('Failed to subscribe the user: ', err);
			// updateBtn();
		});
}

function initializeUI() {
	pushButton.addEventListener('click', function () {
		if (isSubscribed) {
			// TODO: Unsubscribe user
			isSubscribed = false;
		} else {
			subscribeUser();
		}
	});

	// Set the initial subscription value
	swRegistration.pushManager.getSubscription()
		.then(function (subscription) {
			isSubscribed = !(subscription === null);
			updateSubscriptionOnServer(subscription);
			if (isSubscribed) {
				console.log('User IS subscribed.');
			} else {
				console.log('User is NOT subscribed.');
			}
		});
}

if ('serviceWorker' in navigator && 'PushManager' in window) {
	console.log('Service Worker and Push is supported');

	navigator.serviceWorker.register('../adminsilabtif/sw.js')
		.then(function (swReg) {
			console.log('Service Worker is registered');
			swRegistration = swReg;
			initializeUI();
		})
		.catch(function (error) {
			// console.log('Service Worker Error', error);
		});
} else {
	console.warn('Push messaging is not supported');
}

if (Notification.permission === "granted") {
	console.log('permission: granted');
} else if (Notification.permission === "denied") {
	console.log('permission: denied');
} else {
	console.log('permission: default');
}
