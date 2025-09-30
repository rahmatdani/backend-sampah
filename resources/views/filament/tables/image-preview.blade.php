@props(['path'])

@if($path)
    <div class="max-w-xs">
        @php
            $normalizedPath = Str::startsWith($path, ['http://', 'https://'])
                ? $path
                : (Str::startsWith($path, 'storage/')
                    ? asset($path)
                    : asset('storage/' . ltrim($path, '/')));
        @endphp
        <img src="{{ $normalizedPath }}"
             alt="Foto"
             class="h-12 w-12 object-cover rounded shadow">
    </div>
@else
    <span class="text-gray-500">Tidak ada foto</span>
@endif