<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Upload Scan Surat Keluar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-slate-800">Upload File Scan</h2>
                        <a href="{{ route('admin.letters.out.index') }}" class="text-slate-500 hover:text-slate-700">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>

                    <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-slate-500">Nomor Surat</span>
                                <span class="font-semibold text-slate-800">{{ $letter->letter_number }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500">Tanggal</span>
                                <span
                                    class="font-semibold text-slate-800">{{ $letter->letter_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-slate-500">Perihal</span>
                                <span class="font-semibold text-slate-800">{{ $letter->subject }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.letters.out.upload.process', $letter->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">File Scan
                                    (PDF/Gambar)</label>
                                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                                    class="w-full rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                    required>
                                <p class="text-xs text-slate-500 mt-1">Maksimal ukuran file: 5MB.</p>
                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-upload mr-2"></i> Upload & Selesaikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
