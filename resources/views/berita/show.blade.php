<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Detail Berita</h1>
                <p class="text-slate-600 mt-1">{{ Str::limit($berita->title, 60) }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @can('berita.edit')
                    <a href="{{ route('admin.berita.edit', $berita) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">← Kembali</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="card">
            <div class="card-body">

                @if ($berita->featured_image)
                    <img src="{{ Storage::url($berita->featured_image) }}" alt="{{ $berita->title }}"
                        class="w-full h-64 object-cover rounded-xl mb-6 border border-slate-200">
                @endif

                <!-- Meta -->
                <div class="flex flex-wrap gap-3 mb-4">
                    @if ($berita->status === 'published')
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Publish</span>
                    @elseif ($berita->status === 'draft')
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">Draft</span>
                    @else
                        <span class="px-2 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">Arsip</span>
                    @endif
                    @if ($berita->is_featured)
                        <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full">
                            <i class="fas fa-star mr-1"></i>Unggulan
                        </span>
                    @endif
                    @if ($berita->published_at)
                        <span class="text-xs text-slate-500 flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $berita->published_at->format('d M Y H:i') }}
                        </span>
                    @endif
                    <span class="text-xs text-slate-500 flex items-center">
                        <i class="fas fa-user mr-1"></i>{{ $berita->user->name ?? '-' }}
                    </span>
                </div>

                <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ $berita->title }}</h2>

                @if ($berita->excerpt)
                    <p class="text-slate-500 italic border-l-4 border-blue-400 pl-4 mb-6">{{ $berita->excerpt }}</p>
                @endif

                <div class="prose max-w-none text-slate-700 leading-relaxed">
                    {!! $berita->content !!}
                </div>

                <div class="border-t border-slate-200 pt-4 mt-6 flex items-center justify-between text-xs text-slate-400">
                    <span>Dibuat: {{ $berita->created_at->format('d M Y H:i') }}</span>
                    <span>Diperbarui: {{ $berita->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">← Kembali ke Daftar</a>
            <div class="flex space-x-3">
                @can('berita.edit')
                    <a href="{{ route('admin.berita.edit', $berita) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit Berita
                    </a>
                @endcan
                @can('berita.delete')
                    <form action="{{ route('admin.berita.destroy', $berita) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash mr-2"></i>Hapus
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>
