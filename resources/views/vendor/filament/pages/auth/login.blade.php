<x-filament-panels::page.simple>
    <div class="fi-auth-card-header">
        <h2 class="fi-brand-name mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl text-center">
            EcoSort
        </h2>
    </div>

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

    <x-filament-panels::form id="form" wire:submit="authenticate" class="mt-5">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
</x-filament-panels::page.simple>