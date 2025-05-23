<x-layout>
    <div class="w-full p-4 sm:p-6 bg-gray-50 min-h-screen">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6">Data Tiket</h1>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-center pb-4">
            <div>
                <button data-modal-target="modal_create_ticket" data-modal-toggle="modal_create_ticket"
                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
                    + Tambah Tiket
                </button>
            </div>

            <div>
                <form method="GET" action="{{ route('ticket.index') }}" class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama tiket..."
                        class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500 w-full sm:w-auto">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition w-full sm:w-auto">Filter</button>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto bg-white rounded-lg shadow w-full">
            <table class="min-w-[400px] w-full text-left text-sm">
                <thead class="bg-blue-100 text-gray-700">
                    <tr>
                        <th class="py-3 px-4 sm:px-6">#</th>
                        <th class="py-3 px-4 sm:px-6">Nama Tiket</th>
                        <th class="py-3 px-4 sm:px-6">Harga</th>
                        <th class="py-3 px-4 sm:px-6">Peraturan</th>
                        <th class="py-3 px-4 sm:px-6">Destinasi</th>
                        <th class="py-3 px-4 sm:px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($tickets as $index => $ticket)
                        <tr class="hover:bg-gray-100">
                            <td class="py-3 px-4 sm:px-6">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 sm:px-6">{{ $ticket->name_ticket }}</td>
                            <td class="py-3 px-4 sm:px-6">Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 sm:px-6">{{ $ticket->rules }}</td>
                            <td class="py-3 px-4 sm:px-6">{{ $ticket->destination->name }}</td>
                            <td class="py-3 px-4 sm:px-6 text-center space-x-2">
                                <button data-modal-target="modal_edit_ticket_{{ $ticket->id }}" data-modal-toggle="modal_edit_ticket_{{ $ticket->id }}"
                                    class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs transition">
                                    Edit
                                </button>
                                <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus tiket ini?')"
                                        class="bg-red-400 hover:bg-red-500 text-white px-3 py-1 rounded text-xs transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit Tiket --}}
                        <div id="modal_edit_ticket_{{ $ticket->id }}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[1000] justify-center items-center w-full h-full bg-gray-500 bg-opacity-75">
                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <div class="relative bg-white rounded-lg shadow-sm">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                                        <h3 class="text-xl font-semibold text-gray-900">
                                            Edit Tiket
                                        </h3>
                                        <button type="button" data-modal-hide="modal_edit_ticket_{{ $ticket->id }}"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                                            <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                            </svg>
                                            <span class="sr-only">Close modal</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('ticket.update', $ticket->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="p-4 md:p-5 space-y-4">
                                            <div>
                                                <label for="name_ticket_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Nama Tiket</label>
                                                <input type="text" name="name_ticket" id="name_ticket_{{ $ticket->id }}" value="{{ $ticket->name }}" required
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label for="price_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Harga</label>
                                                <input type="number" name="price" id="price_{{ $ticket->id }}" value="{{ $ticket->price }}" required min="0"
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label for="open_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Jam Buka</label>
                                                <input type="time" name="open" id="open_{{ $ticket->id }}" value="{{ $ticket->open }}" required
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>


                                            <div>
                                                <label for="close_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Jam Tutup</label>
                                                <input type="time" name="close" id="close_{{ $ticket->id }}" value="{{ $ticket->close }}" required
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            </div>
                                            <div>
                                                <label for="status_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Status</label>
                                                <select name="status" id="status_{{ $ticket->id }}" required
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option value="Active">Active</option>
                                                    <option value="Expired">Expired</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label for="type_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Tipe Tiket</label>
                                                <select name="type" id="type_{{ $ticket->id }}" required
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option value="Regular">Regular</option>
                                                    <option value="Special">Special</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="destination_id_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Destinasi</label>
                                                <select name="destination_id" id="destination_id_{{ $ticket->id }}" required
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    @foreach ($destination as $item)
                                                        <option value="{{ $item->id }}" {{ $ticket->destination_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label for="rules_{{ $ticket->id }}" class="block text-sm font-medium text-gray-700">Peraturan</label>
                                                <textarea name="rules" id="rules_{{ $ticket->id }}" rows="3" required
                                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $ticket->rules }}</textarea>
                                            </div>
                                        </div>

                                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                            <button type="submit"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Perubahan</button>
                                            <button type="button" data-modal-hide="modal_edit_ticket_{{ $ticket->id }}"
                                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Tidak ada tiket ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Tambah Tiket --}}
    <div id="modal_create_ticket" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[1000] justify-center items-center w-full h-full bg-gray-500 bg-opacity-75">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Tambah Tiket
                    </h3>
                    <button type="button" data-modal-hide="modal_create_ticket"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <form action="{{ route('ticket.store') }}" method="POST">
                    @csrf
                    <div class="p-4 md:p-5 space-y-4">
                        <div>
                            <label for="name_ticket" class="block text-sm font-medium text-gray-700">Nama Tiket</label>
                            <input type="text" name="name_ticket" id="name_ticket" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700">Harga</label>
                            <input type="number" name="price" id="price" required min="0"
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="open" class="block text-sm font-medium text-gray-700">Jam Buka</label>
                            <input type="time" name="open" id="open" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="close" class="block text-sm font-medium text-gray-700">Jam Tutup</label>
                            <input type="time" name="close" id="close" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipe Tiket</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="Regular">Regular</option>
                                <option value="Special">Special</option>
                            </select>
                        </div>
                        <div>
                            <label for="destination_id" class="block text-sm font-medium text-gray-700">Destinasi</label>
                            <select name="destination_id" id="destination_id" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled selected>Pilih Destinasi</option>
                                @foreach ($destination as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="rules" class="block text-sm font-medium text-gray-700">Peraturan</label>
                            <textarea name="rules" id="rules" rows="3" required
                                class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
                        <button type="button" data-modal-hide="modal_create_ticket"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
