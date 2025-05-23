<x-layout>
    <div class="w-full p-4 sm:p-6 bg-gray-50 min-h-screen">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6">Data Transaksi</h1>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 pb-4">
            <div>
                <a href="#">
                    <button
                        class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                        + Tambah Wisata
                    </button>
                </a>
            </div>
            <div class="w-full sm:w-auto">
                <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                    <input type="text" name="search" id="search" placeholder="Cari transaksi..."
                        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500 w-full sm:w-auto">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition w-full sm:w-auto">Filter</button>
                </form>
            </div>
        </div>
        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto bg-white rounded-lg shadow w-full">
            <table class="min-w-[600px] w-full text-left text-sm">
                <thead class="bg-blue-100 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 sm:px-6">#</th>
                        <th class="py-3 px-4 sm:px-6">Order ID</th>
                        <th class="py-3 px-4 sm:px-6">Nama</th>
                        <th class="py-3 px-4 sm:px-6">Tiket</th>
                        <th class="py-3 px-4 sm:px-6">Jumlah</th>
                        <th class="py-3 px-4 sm:px-6">Total</th>
                        <th class="py-3 px-4 sm:px-6">Metode Pembayaran</th>
                        <th class="py-3 px-4 sm:px-6">Status</th>
                        <th class="py-3 px-4 sm:px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $index => $transaction)
                    <tr class="hover:bg-gray-100">
                        <td class="py-3 px-4 sm:px-6">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $transaction->order_id ?? '-' }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $transaction->name }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $transaction->ticket->name_ticket ?? '-' }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $transaction->quantity ?? '-' }}</td>
                        <td class="py-3 px-4 sm:px-6">Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 sm:px-6">{{ $transaction->payment_type ?? '-' }}</td>
                        <td class="py-3 px-4 sm:px-6">
                            @if($transaction->status == 'SUCCESS')
                                <span class="inline-block px-2 py-1 text-xs text-white bg-green-500 rounded">SUKSES</span>
                            @elseif($transaction->status == 'PENDING')
                                <span class="inline-block px-2 py-1 text-xs text-white bg-yellow-500 rounded">PENDING</span>
                            @elseif($transaction->status == 'FAILED')
                                <span class="inline-block px-2 py-1 text-xs text-white bg-red-500 rounded">GAGAL</span>
                            @elseif($transaction->status == 'EXPIRED')
                                <span class="inline-block px-2 py-1 text-xs text-white bg-gray-500 rounded">KADALUARSA</span>
                            @elseif($transaction->status == 'CANCELLED')
                                <span class="inline-block px-2 py-1 text-xs text-white bg-red-500 rounded">DIBATALKAN</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs text-white bg-gray-500 rounded">{{ $transaction->status }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 sm:px-6 text-center space-x-2">
                            <a href="{{ route('transactions.status', $transaction->order_id) }}" class="inline-block mb-1 sm:mb-0">
                                <button class="bg-blue-400 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs transition">Cek Status</button>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-6 text-center text-gray-500">Tidak ada data transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!-- Pagination -->
            <div class="py-4 px-4 border text-center text-sm text-gray-500">
                Menampilkan {{ $transactions->count() }} dari {{ $transactions->total() ?? $transactions->count() }} data
            </div>
        </div>
    </div>
</x-layout>
