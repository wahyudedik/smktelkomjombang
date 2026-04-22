<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Audit Log Details') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Viewing audit log #{{ $auditLog->id }}</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Action</h3>
                            <p class="mt-1">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($auditLog->action == 'create') bg-green-100 text-green-800
                                    @elseif($auditLog->action == 'update') bg-blue-100 text-blue-800
                                    @elseif($auditLog->action == 'delete') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($auditLog->action) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Time</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $auditLog->created_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">User</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $auditLog->user?->name ?? 'System' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">IP Address</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $auditLog->ip_address }}</p>
                        </div>
                        @if ($auditLog->model_type)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Model Type</h3>
                                <p class="mt-1 text-sm text-gray-900">{{ class_basename($auditLog->model_type) }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Model ID</h3>
                                <p class="mt-1 text-sm text-gray-900">#{{ $auditLog->model_id }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- User Agent -->
                    @if ($auditLog->user_agent)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">User Agent</h3>
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded">{{ $auditLog->user_agent }}</p>
                        </div>
                    @endif

                    <!-- Old Values -->
                    @if ($auditLog->old_values)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Old Values</h3>
                            <div class="bg-red-50 p-4 rounded">
                                <pre class="text-sm text-gray-700">{{ json_encode($auditLog->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- New Values -->
                    @if ($auditLog->new_values)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">New Values</h3>
                            <div class="bg-green-50 p-4 rounded">
                                <pre class="text-sm text-gray-700">{{ json_encode($auditLog->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
