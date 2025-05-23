<x-layout>
    <div class="w-full p-4 sm:p-6 bg-gray-50 min-h-screen">
        <div class="max-w-lg mx-auto bg-white rounded-lg shadow p-6">
            <div class="text-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h2 class="text-2xl font-bold text-gray-800 mt-4">Terima Kasih!</h2>
                <p class="text-gray-600 mt-2">Pembayaran Anda sedang diproses.</p>
            </div>

            <div class="bg-gray-100 p-4 rounded-lg mb-6">
                <p class="text-gray-700 text-center">
                    Status pembayaran akan diperbarui secara otomatis. Anda juga akan menerima email konfirmasi setelah pembayaran berhasil.
                </p>
            </div>

            <div class="flex justify-center">
                <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded transition">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</x-layout>
