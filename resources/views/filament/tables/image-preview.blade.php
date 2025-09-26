@props(['path'])

@if($path)
    <div class="max-w-xs">
        @if(str_starts_with($path, 'storage/'))
            <img src="{{ asset($path) }}" 
                 alt="Foto Sampah" 
                 class="max-w-full max-h-20 rounded object-cover">
        @else
            <img src="{{ asset('storage/' . $path) }}" 
                 alt="Foto Sampah" 
                 class="max-w-full max-h-20 rounded object-cover">
        @endif
    </div>
@else
    <span class="text-gray-500">Tidak ada foto</span>
@endif