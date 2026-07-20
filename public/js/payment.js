// Payment Midtrans Integration
(function() {
    'use strict';

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        if (!payButton) return;

        payButton.addEventListener('click', handlePayment);
    });

    function handlePayment() {
        const payButton = document.getElementById('pay-button');
        const loadingDiv = document.getElementById('payment-loading');

        if (!payButton || !loadingDiv) return;

        // Disable button and show loading
        payButton.disabled = true;
        loadingDiv.classList.remove('d-none');

        // Check if we have snap token from server
        const snapTokenElement = document.querySelector('meta[name="snap-token"]');
        const snapToken = snapTokenElement ? snapTokenElement.getAttribute('content') : null;

        if (snapToken) {
            // Use existing snap token
            payWithSnap(snapToken);
        } else {
            // Request snap token from server
            requestSnapToken();
        }
    }

    function payWithSnap(snapToken) {
        const payButton = document.getElementById('pay-button');
        const loadingDiv = document.getElementById('payment-loading');

        if (typeof snap === 'undefined') {
            alert('Midtrans Snap belum dimuat. Silakan refresh halaman.');
            resetPaymentButton(payButton, loadingDiv);
            return;
        }

        // Fix scroll: pastikan body tidak terkunci oleh Midtrans
        document.documentElement.classList.add('midtrans-open');
        document.body.classList.add('midtrans-open');

        // Monitor dan override style overflow yang di-set Midtrans
        const scrollFix = setInterval(function() {
            if (document.body.style.overflow === 'hidden') {
                document.body.style.overflow = '';
                document.documentElement.style.overflow = '';
            }
        }, 100);

        snap.pay(snapToken, {
            onSuccess: function(result) {
                clearInterval(scrollFix);
                window.location.href = getFinishUrl('success', result.order_id);
            },
            onPending: function(result) {
                clearInterval(scrollFix);
                window.location.href = getFinishUrl('pending', result.order_id);
            },
            onError: function(result) {
                clearInterval(scrollFix);
                window.location.href = getFinishUrl('error', result.order_id);
            },
            onClose: function() {
                clearInterval(scrollFix);
                document.documentElement.classList.remove('midtrans-open');
                document.body.classList.remove('midtrans-open');
                resetPaymentButton(payButton, loadingDiv);
            }
        });
    }

    function requestSnapToken() {
        const payButton = document.getElementById('pay-button');
        const loadingDiv = document.getElementById('payment-loading');
        const reservasiId = document.querySelector('meta[name="reservasi-id"]')?.getAttribute('content');

        if (!reservasiId) {
            alert('Data reservasi tidak ditemukan.');
            resetPaymentButton(payButton, loadingDiv);
            return;
        }

        fetch(`/customer/payment/${reservasiId}/process`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.snap_token) {
                payWithSnap(data.snap_token);
            } else {
                alert('Gagal memproses pembayaran: ' + (data.error || 'Unknown error'));
                resetPaymentButton(payButton, loadingDiv);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
            resetPaymentButton(payButton, loadingDiv);
        });
    }

    function getFinishUrl(status, orderId) {
        const baseUrl = window.location.origin;
        switch(status) {
            case 'success':
                return `${baseUrl}/customer/payment/finish?order_id=${orderId}`;
            case 'pending':
                return `${baseUrl}/customer/payment/unfinish?order_id=${orderId}`;
            case 'error':
                return `${baseUrl}/customer/payment/error?order_id=${orderId}`;
            default:
                return `${baseUrl}/customer/riwayat`;
        }
    }

    function resetPaymentButton(button, loadingDiv) {
        if (button) button.disabled = false;
        if (loadingDiv) loadingDiv.classList.add('d-none');
    }
})();