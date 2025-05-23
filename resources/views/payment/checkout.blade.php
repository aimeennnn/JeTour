<x-layout>
    <div class="w-full p-4 sm:p-6 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">Checkout Tiket</h1>

            <div id="error-message" class="hidden bg-red-100 text-red-700 p-3 rounded mb-4"></div>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Tiket</h2>

                    <div class="bg-blue-50 text-blue-800 p-4 rounded mb-6">
                        <p><strong>Destinasi:</strong> {{ $ticket->destination->name }}</p>
                        <p><strong>Tiket:</strong> {{ $ticket->name_ticket }}</p>
                        <p><strong>Harga per Tiket:</strong> Rp {{ number_format($ticket->price, 0, ',', '.') }}</p>
                    </div>

                    <form id="payment-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" required
                                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                    title="Masukkan alamat email yang valid"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                                <input type="text" name="phone" id="phone" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
                            </div>
                            <div>
                                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tiket</label>
                                <input type="number" name="quantity" id="quantity" min="1" value="1" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600">Harga per tiket:</span>
                                <span class="text-gray-800">Rp <span id="price-per-ticket">{{ number_format($ticket->price, 0, ',', '.') }}</span></span>
                            </div>
                            <div class="flex justify-between font-semibold">
                                <span class="text-gray-800">Total:</span>
                                <span class="text-gray-800">Rp <span id="total-price">{{ number_format($ticket->price, 0, ',', '.') }}</span></span>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" id="pay-button"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded transition flex items-center justify-center">
                                <span>Bayar Sekarang</span>
                                <span id="spinner" class="hidden animate-spin ml-3 w-5 h-5 border-2 border-white border-t-transparent rounded-full"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans JS -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

    <script>
        const quantityInput = document.getElementById('quantity');
        const pricePerTicket = {{ $ticket->price }};
        const totalPriceElement = document.getElementById('total-price');
        const paymentForm = document.getElementById('payment-form');
        const payButton = document.getElementById('pay-button');
        const spinner = document.getElementById('spinner');
        const errorMessage = document.getElementById('error-message');

        quantityInput.addEventListener('change', function () {
            let quantity = parseInt(this.value);
            if (quantity < 1 || isNaN(quantity)) {
                quantity = 1;
                this.value = 1;
            }
            const total = pricePerTicket * quantity;
            totalPriceElement.textContent = new Intl.NumberFormat('id-ID').format(total);
        });

        paymentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            errorMessage.classList.add('hidden');
            payButton.disabled = true;
            spinner.classList.remove('hidden');
            payButton.querySelector('span').textContent = 'Memproses...';

            const formData = new FormData(paymentForm);

            fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ticket_id: formData.get('ticket_id'),
                    quantity: formData.get('quantity'),
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone')
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        snap.pay(data.snap_token, {
                            onSuccess: function (result) {
                                window.location.href = data.redirect_url;
                            },
                            onPending: function (result) {
                                window.location.href = data.redirect_url;
                            },
                            onError: function (result) {
                                resetButton();
                                errorMessage.textContent = 'Pembayaran gagal: ' + result.status_message;
                                errorMessage.classList.remove('hidden');
                            },
                            onClose: function () {
                                resetButton();
                            }
                        });
                    } else {
                        resetButton();
                        errorMessage.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                        errorMessage.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    resetButton();
                    console.error('Error:', error);
                    errorMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                    errorMessage.classList.remove('hidden');
                });
        });

        function resetButton() {
            payButton.disabled = false;
            spinner.classList.add('hidden');
            payButton.querySelector('span').textContent = 'Bayar Sekarang';
        }
    </script>
</x-layout>
