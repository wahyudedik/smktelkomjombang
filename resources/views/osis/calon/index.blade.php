<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ __('common.data_calon_osis') }}</h1>
                <p class="text-slate-600 mt-1">{{ __('common.manage_calon_description') }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Import/Export Dropdown -->
                <div class="relative inline-block">
                    <button type="button" onclick="toggleDropdown('importExportDropdown')"
                        class="btn btn-secondary flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        {{ __('common.import_export') }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="importExportDropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 z-10">
                        <a href="{{ route('admin.osis.calon.import') }}"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-t-lg">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            {{ __('common.import_data') }}
                        </a>
                        <a href="{{ route('admin.osis.calon.export', request()->query()) }}"
                            class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-b-lg">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ __('common.export_data') }}
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.osis.calon.create') }}" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __('common.tambah_calon') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filters -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 mb-6">
            <form method="GET" action="{{ route('admin.osis.calon.index') }}" id="filterForm"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input"
                        onchange="document.getElementById('filterForm').submit();">
                        <option value="">{{ __('common.all_status') }}</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('common.active') }}</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('common.inactive') }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Jenis Pencalonan</label>
                    <select name="jenis_pencalonan" class="form-input"
                        onchange="document.getElementById('filterForm').submit();">
                        <option value="">{{ __('common.all_types') }}</option>
                        <option value="ketua" {{ request('jenis_pencalonan') === 'ketua' ? 'selected' : '' }}>{{ __('common.ketua') }}
                        </option>
                        <option value="wakil" {{ request('jenis_pencalonan') === 'wakil' ? 'selected' : '' }}>{{ __('common.wakil') }}
                        </option>
                        <option value="pasangan" {{ request('jenis_pencalonan') === 'pasangan' ? 'selected' : '' }}>
                            {{ __('common.pasangan') }}</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('common.search_placeholder_calon') }}"
                        class="form-input">
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        {{ __('common.search') }}
                    </button>
                    <a href="{{ route('admin.osis.calon.index') }}" class="btn btn-secondary">{{ __('common.reset') }}</a>
                </div>
            </form>
        </div>

        <!-- Calon List -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">{{ __('common.candidate_list') }} ({{ $calons->total() }})</h3>
            </div>

            <div class="divide-y divide-slate-200">
                @forelse($calons as $calon)
                    <div class="p-6 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <span
                                        class="text-xl font-bold text-orange-600">#{{ $loop->iteration + ($calons->currentPage() - 1) * $calons->perPage() }}</span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-slate-900">{{ $calon->full_candidate_name }}
                                    </h4>
                                    <p class="text-sm text-slate-600">{{ $calon->pencalonan_type_display }}</p>
                                    <div class="flex items-center space-x-4 mt-2">
                                        <span
                                            class="badge {{ $calon->is_active ? 'badge-success' : 'badge-warning' }}">
                                            {{ $calon->is_active ? __('common.status_active') : __('common.status_inactive') }}
                                        </span>
                                        <span class="text-sm text-slate-500">{{ $calon->votings_count }} {{ __('common.votes') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.osis.calon.show', $calon) }}"
                                    class="btn btn-secondary text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Lihat
                                </a>
                                <a href="{{ route('admin.osis.calon.edit', $calon) }}"
                                    class="btn btn-secondary text-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.osis.calon.destroy', $calon) }}"
                                    class="inline" data-confirm="Apakah Anda yakin ingin menghapus calon ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 mb-2">Belum ada calon</h3>
                        <p class="text-slate-600 mb-4">Mulai dengan menambahkan calon OSIS pertama</p>
                        <a href="{{ route('admin.osis.calon.create') }}" class="btn btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Calon Pertama
                        </a>
                    </div>
                @endforelse
            </div>

            @if ($calons->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $calons->links() }}
                </div>
            @endif
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successKey = 'calon_alert_' + '{{ md5(session('success') . time()) }}';
                if (!sessionStorage.getItem(successKey) && typeof showSuccess !== 'undefined') {
                    showSuccess('{{ session('success') }}');
                    sessionStorage.setItem(successKey, 'shown');
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorKey = 'calon_alert_' + '{{ md5(session('error') . time()) }}';
                if (!sessionStorage.getItem(errorKey) && typeof showError !== 'undefined') {
                    showError('{{ session('error') }}');
                    sessionStorage.setItem(errorKey, 'shown');
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorsKey = 'calon_errors_' + '{{ md5(json_encode($errors->all()) . time()) }}';
                if (!sessionStorage.getItem(errorsKey) && typeof showError !== 'undefined') {
                    showError('{{ $errors->first() }}');
                    sessionStorage.setItem(errorsKey, 'shown');
                }
            });
        </script>
    @endif

    <script>
        // Dropdown toggle
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = ['importExportDropdown'];
            dropdowns.forEach(id => {
                const dropdown = document.getElementById(id);
                const button = event.target.closest('button');
                if (dropdown && !dropdown.contains(event.target) && (!button || button.getAttribute(
                            'onclick') !==
                        `toggleDropdown('${id}')`)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
