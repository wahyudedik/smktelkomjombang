<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Catat Surat Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Form Surat Masuk</h2>
                        <a href="{{ route('admin.letters.in.index') }}" class="text-slate-500 hover:text-slate-700">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>

                    <form action="{{ route('admin.letters.in.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Surat (Dari
                                        Pengirim)</label>
                                    <input type="text" name="reference_number"
                                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Surat</label>
                                    <input type="date" name="letter_date" value="{{ date('Y-m-d') }}"
                                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Pengirim
                                    (Instansi/Perorangan)</label>
                                <input type="text" name="sender"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Dinas Pendidikan Provinsi" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Perihal</label>
                                <input type="text" name="subject"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Pemberitahuan Lomba" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Upload Scan Surat
                                    (PDF/Gambar)</label>
                                <input type="file" name="file"
                                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Ringkasan Isi
                                    (Opsional)</label>
                                <textarea name="description" rows="3"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-save mr-2"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
