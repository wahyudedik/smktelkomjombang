<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Maintenance Details</h1>
                <p class="text-slate-600 mt-1">View maintenance record information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.sarpras.maintenance.edit', $maintenance) }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.sarpras.maintenance.index') }}" class="btn btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">Maintenance Information</h3>
                    </div>
                    <div class="card-body">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Maintenance ID</dt>
                                <dd class="mt-1 text-sm text-slate-900">#{{ $maintenance->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Status</dt>
                                <dd class="mt-1">
                                    <span class="badge badge-{{ $maintenance->status_badge_color }}">
                                        {{ $maintenance->status_display }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Item Type</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $maintenance->item_type_display }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Item Name</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $maintenance->item_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Maintenance Type</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ ucfirst($maintenance->jenis_maintenance) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Technician</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $maintenance->teknisi ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Cost</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $maintenance->formatted_biaya }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Maintenance Date</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $maintenance->tanggal_maintenance->format('d M Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Completion Date</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $maintenance->tanggal_selesai ? $maintenance->tanggal_selesai->format('d M Y') : 'Not completed' }}
                                </dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Problem Description</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $maintenance->deskripsi_masalah ?? 'No description provided' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Repair Action</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $maintenance->tindakan_perbaikan ?? 'No repair action provided' }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-slate-500">Notes</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $maintenance->catatan ?? 'No notes provided' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Photos -->
                @if ($maintenance->photos && count($maintenance->photos) > 0)
                    <div class="card mt-6">
                        <div class="card-header">
                            <h3 class="text-lg font-semibold text-slate-900">Photos</h3>
                        </div>
                        <div class="card-body">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($maintenance->photos as $photo)
                                    <div class="relative group">
                                        <img src="{{ $maintenance->getPhotoUrl($photo) }}" alt="Maintenance photo"
                                            class="w-full h-32 object-cover rounded-lg">
                                        <div
                                            class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-opacity rounded-lg flex items-center justify-center">
                                            <button class="opacity-0 group-hover:opacity-100 text-white">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Maintenance Details -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">Maintenance Details</h3>
                    </div>
                    <div class="card-body">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Created By</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $maintenance->user->name ?? 'Unknown' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Created At</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $maintenance->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-slate-500">Updated At</dt>
                                <dd class="mt-1 text-sm text-slate-900">
                                    {{ $maintenance->updated_at->format('M d, Y H:i') }}</dd>
                            </div>
                            @if ($maintenance->scheduled_date)
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Scheduled Date</dt>
                                    <dd class="mt-1 text-sm text-slate-900">
                                        {{ $maintenance->scheduled_date->format('M d, Y') }}</dd>
                                </div>
                            @endif
                            @if ($maintenance->completed_date)
                                <div>
                                    <dt class="text-sm font-medium text-slate-500">Completed Date</dt>
                                    <dd class="mt-1 text-sm text-slate-900">
                                        {{ $maintenance->completed_date->format('M d, Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-lg font-semibold text-slate-900">Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="space-y-3">
                            <a href="{{ route('admin.sarpras.maintenance.edit', $maintenance) }}"
                                class="w-full btn btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Maintenance
                            </a>
                            <form method="POST"
                                action="{{ route('admin.sarpras.maintenance.destroy', $maintenance) }}"
                                data-confirm="Apakah Anda yakin ingin menghapus maintenance {{ $maintenance->jenis_maintenance }}?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full btn btn-danger">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Maintenance
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
