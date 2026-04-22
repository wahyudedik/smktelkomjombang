<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Buat Surat Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Form Surat Keluar</h2>
                        <a href="{{ route('admin.letters.out.index') }}" class="text-slate-500 hover:text-slate-700">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>

                    @if ($errors->has('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Gagal!</strong>
                            <span class="block sm:inline">{{ $errors->first('error') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('admin.letters.out.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Format Surat</label>
                                    <select name="letter_format_id"
                                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="">Pilih Format...</option>
                                        @foreach ($formats as $format)
                                            <option value="{{ $format->id }}">{{ $format->name }}
                                                ({{ $format->format_template }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Surat</label>
                                    <input type="date" name="letter_date" value="{{ date('Y-m-d') }}"
                                        class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tujuan / Kepada</label>
                                <input type="text" name="recipient"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Kepala Dinas Pendidikan" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Perihal</label>
                                <input type="text" name="subject"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Contoh: Undangan Rapat Koordinasi" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Isi Ringkas / Keterangan
                                    (Opsional)</label>
                                <textarea name="description" rows="3"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-paper-plane mr-2"></i> Generate Nomor & Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
