{{-- @if (session('alert'))
    <div id="toastContainer" class="fixed inset-x-0 z-50 flex justify-start ml-4 pointer-events-none"
        style="bottom: calc(env(safe-area-inset-bottom, 1rem) + 4rem);">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertSession = @json(session('alert'));
            const toastContainer = document.getElementById('toastContainer');

            const bgColors = {
                success: 'bg-slate-800',
                error: 'bg-slate-600',
                warning: 'bg-slate-600',
                info: 'bg-slate-800'
            };
            const textColors = {
                success: 'text-white',
                error: 'text-white',
                warning: 'text-white',
                info: 'text-white'
            };

            function showToast() {
                let bg = bgColors[alertSession.type] || bgColors.info;
                let text = textColors[alertSession.type] || 'text-white';
                if (text.includes('text-gray') || text.includes('text-black')) {
                    bg = 'bg-white';
                }

                const toast = document.createElement('div');
                toast.className = `
                    px-4 py-2 rounded text-sm shadow mb-2 pointer-events-auto
                    ${bg} ${text} animate-fade-in
                `;
                toast.textContent = alertSession.message;
                toastContainer.appendChild(toast);

                setTimeout(() => {
                    toast.classList.replace('animate-fade-in', 'animate-fade-out');
                    toast.addEventListener('animationend', () => toast.remove());
                }, 3000);
            }

            function showSweetAlert() {
                Swal.fire({
                    icon: alertSession.type,
                    title: alertSession.title || alertSession.type.charAt(0).toUpperCase() + alertSession.type.slice(1),
                    text: alertSession.message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    width: '400px',
                    customClass: {
                        popup: 'rounded-xl',
                        title: 'text-lg font-semibold',
                        content: 'text-sm text-gray-700'
                    }
                });
            }

            function handleAlert() {
                const isMobile = window.innerWidth < 768;

                // Hapus toast lama sebelum menampilkan yang baru
                toastContainer.innerHTML = '';

                if (isMobile) {
                    showToast();
                } else {
                    showSweetAlert();
                }
            }

            // Tampilkan alert pertama kali
            handleAlert();

            // Jika user resize, otomatis adaptif
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(handleAlert, 300);
            });
        });
    </script>

    <style>
        @keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
        @keyframes fade-out { 0% { opacity: 1; } 100% { opacity: 0; } }

        .animate-fade-in { animation: fade-in 0.3s ease forwards; }
        .animate-fade-out { animation: fade-out 0.3s ease forwards; }
    </style>
@endif --}}

@if (session('alert'))
<script>
    window.__pendingAlert = @json(session('alert'));
</script>
@endif

<div id="toastContainer"
     class="fixed inset-x-0 z-[9999] flex flex-col items-start w-full px-4 space-y-2 pointer-events-none"
     style="bottom: 1.5rem;"></div>

<script>
    document.addEventListener('turbo:load', handleAlert);
    document.addEventListener('turbo:frame-load', handleAlert);

    function handleAlert() {
        if (!window.__pendingAlert) return;

        const alertId = window.__pendingAlert.id;
        const lastId = sessionStorage.getItem('lastAlertId');

        if (alertId !== lastId) {
            showAlert(window.__pendingAlert);
            sessionStorage.setItem('lastAlertId', alertId);
        }

        window.__pendingAlert = null; // reset supaya tidak muncul ulang
    }

    function showAlert(alertData) {
        const toastContainer = document.getElementById('toastContainer');
        const isMobile = window.matchMedia('(max-width: 768px)').matches;

        const bgColors = {
            success: 'bg-slate-800',
            error: 'bg-red-600',
            warning: 'bg-yellow-600',
            info: 'bg-blue-700'
        };

        if (isMobile) {
            const toast = document.createElement('div');
            toast.className = `px-4 py-2 rounded w-full text-sm shadow-lg mb-2 pointer-events-auto text-white animate-fade-in ${bgColors[alertData.type] || 'bg-slate-800'}`;
            toast.textContent = alertData.message;
            toast.style.opacity = '0';
            toastContainer.appendChild(toast);

            // Force reflow biar animasi jalan
            void toast.offsetWidth;

            toast.classList.add('animate-fade-in');

            setTimeout(() => {
                toast.classList.replace('animate-fade-in', 'animate-fade-out');
                toast.addEventListener('animationend', () => {
                    if (toast && toast.parentNode) toast.remove();
                });
            }, 3000);
        } else {
            Swal.fire({
                icon: alertData.type,
                title: alertData.title || alertData.type.charAt(0).toUpperCase() + alertData.type.slice(1),
                text: alertData.message,
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6',
                width: '400px'
            });
        }
    }

    // Bersihkan sebelum Turbo cache
    document.addEventListener('turbo:before-cache', () => {
        window.__pendingAlert = null;
        const toastContainer = document.getElementById('toastContainer');
        if (toastContainer) toastContainer.innerHTML = '';
    });
</script>

<style>
    @keyframes fade-in { 0% { opacity: 0; } 100% { opacity: 1; } }
    @keyframes fade-out { 0% { opacity: 1; } 100% { opacity: 0; } }
    .animate-fade-in { animation: fade-in 0.3s ease forwards; }
    .animate-fade-out { animation: fade-out 0.3s ease forwards; }
</style>
