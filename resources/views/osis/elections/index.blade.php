<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Kelola Pemilihan</h1>
                <p class="text-slate-600 mt-1">Buat, aktifkan, tutup, dan reset election OSIS</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @can('osis.create')
                    <a href="{{ route('admin.osis.elections.create') }}" class="btn btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Buat Pemilihan
                    </a>
                @endcan
                <a href="{{ route('admin.osis.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h3 class="text-lg font-semibold text-slate-900">Daftar Pemilihan</h3>
                <p class="text-sm text-slate-600 mt-1">Hanya boleh ada satu pemilihan aktif pada satu waktu.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Votes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Hasil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse ($elections as $election)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900">{{ $election->title }}</div>
                                    @if ($election->description)
                                        <div class="text-xs text-slate-500 mt-0.5 max-w-xs truncate">
                                            {{ $election->description }}</div>
                                    @endif
                                    <div class="text-xs text-slate-400 mt-0.5">
                                        Maks. {{ $election->max_votes_per_student }} suara/siswa
                                        @if ($election->allowed_classes)
                                            · Kelas: {{ implode(', ', $election->allowed_classes) }}
                                        @else
                                            · Semua kelas
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 whitespace-nowrap">
                                    {{ $election->start_date?->format('d M Y H:i') }}<br>
                                    <span class="text-slate-400">s/d</span><br>
                                    {{ $election->end_date?->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $election->status_badge_color }}">
                                        {{ $election->status_label_text }}
                                    </span>
                                    @if ($election->isCurrentlyActive())
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                            Sedang Berlangsung
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-slate-900">
                                        {{ $election->votes_count ?? 0 }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($election->show_results)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Ditampilkan
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800"
                                            title="Perolehan per calon disembunyikan selama pemilihan berjalan">
                                            Tersembunyi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @can('osis.edit')
                                            <a href="{{ route('admin.osis.elections.edit', $election) }}"
                                                class="text-amber-600 hover:text-amber-700" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>

                                            @if (!$election->is_active && !$election->is_locked && !$election->hasEnded())
                                                <form method="POST"
                                                    action="{{ route('admin.osis.elections.activate', $election) }}"
                                                    class="inline"
                                                    data-confirm="Jadikan "{{ $election->title }}" sebagai pemilihan aktif? Election lain akan dinonaktifkan.">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-700"
                                                        title="Aktifkan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif

                                            @if (!$election->is_locked)
                                                <form method="POST"
                                                    action="{{ route('admin.osis.elections.close', $election) }}"
                                                    class="inline"
                                                    data-confirm="Tutup dan kunci pemilihan "{{ $election->title }}"? Voting akan berhenti menerima suara baru.">
                                                    @csrf
                                                    <button type="submit" class="text-slate-600 hover:text-slate-800"
                                                        title="Tutup & Kunci">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('osis.edit')
                                            @if (($election->votes_count ?? 0) > 0 && ($election->is_locked || $election->hasEnded()))
                                                {{-- Reset votes: konfirmasi ketat — wajib ketik judul election --}}
                                                <form method="POST"
                                                    action="{{ route('admin.osis.elections.reset', $election) }}"
                                                    class="inline reset-votes-form"
                                                    data-election-title="{{ $election->title }}">
                                                    @csrf
                                                    <input type="hidden" name="confirm_title" value="">
                                                    <button type="submit" class="text-orange-600 hover:text-orange-700"
                                                        title="Reset Semua Votes">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('osis.delete')
                                            @if (($election->votes_count ?? 0) === 0)
                                                <form method="POST"
                                                    action="{{ route('admin.osis.elections.destroy', $election) }}"
                                                    class="inline"
                                                    data-confirm="Hapus pemilihan "{{ $election->title }}" secara permanen?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-700"
                                                        title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8">
                                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-slate-500">Belum ada pemilihan. Buat pemilihan pertama atau
                                        gunakan CLI <code class="text-sm">php artisan osis:manage create</code>.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($elections->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $elections->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Konfirmasi SweetAlert2 KETAT untuk reset votes: user harus mengetik
            // judul election dengan tepat sebelum form di-submit.
            document.querySelectorAll('form.reset-votes-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    if (form.dataset.processed === '1') {
                        return; // Sudah terkonfirmasi — lanjut submit asli
                    }
                    e.preventDefault();

                    const title = form.dataset.electionTitle;

                    Swal.fire({
                        title: 'Reset Semua Votes?',
                        html: 'Semua vote di pemilihan ini akan <strong>DIHAPUS PERMANEN</strong> dan status pemilih dikembalikan ke "belum memilih".<br><br>Ketik judul pemilihan <strong>' +
                            title + '</strong> untuk melanjutkan:',
                        input: 'text',
                        inputPlaceholder: title,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Reset Votes',
                        confirmButtonColor: '#dc2626',
                        cancelButtonText: 'Batal',
                        showLoaderOnConfirm: true,
                        preConfirm: function(value) {
                            if (value !== title) {
                                Swal.showValidationMessage('Judul pemilihan tidak sesuai!');
                            }
                            return value;
                        }
                    }).then(function(result) {
                        if (result.isConfirmed && result.value === title) {
                            form.querySelector('input[name="confirm_title"]').value = result.value;
                            form.dataset.processed = '1';
                            form.submit();
                        }
                    });
                });
            });

            // Flash messages
            @if (session('success'))
                if (typeof showSuccess !== 'undefined') {
                    showSuccess(@json(session('success')));
                }
            @endif
            @if (session('error'))
                if (typeof showError !== 'undefined') {
                    showError(@json(session('error')));
                }
            @endif
        });
    </script>
</x-app-layout>
