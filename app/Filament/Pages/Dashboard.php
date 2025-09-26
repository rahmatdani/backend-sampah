<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\Admin\AdminOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\TextEntry;
use Filament\Support\Enums\MaxWidth;

class Dashboard extends BaseDashboard
{
    public function getColumns(): array | int
    {
        return [
            'sm' => 1,
            'lg' => 3,
        ];
    }

    public function getHeaderWidgets(): array
    {
        // Menghapus semua widget dari header untuk mencegah duplikasi
        return [];
    }

    public function getFooterWidgets(): array
    {
        // Menghapus semua widget dari footer untuk mencegah duplikasi
        return [];
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Selamat Datang di EcoSort - Admin Panel')
                    ->description('Sistem Manajemen Pemilah Sampah Modern')
                    ->schema([
                        Placeholder::make('welcome')
                            ->content('EcoSort adalah sistem inovatif yang membantu masyarakat dalam mengelola sampah rumah tangga dengan lebih efektif. Dengan menggunakan teknologi Machine Learning, aplikasi ini dapat mendeteksi jenis sampah secara otomatis berdasarkan gambar yang diunggah pengguna.')
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Statistik Overview')
                    ->schema([
                        // Menampilkan widget AdminOverview sebagai komponen dalam schema
                        // untuk mencegah duplikasi dengan header widgets
                        Placeholder::make('stats')
                            ->content(function () {
                                // Membuat instance widget dan mendapatkan stats-nya
                                $widget = new AdminOverview();
                                $stats = $widget->getStats();
                                
                                // Membuat HTML untuk menampilkan stats dengan tata letak yang rapi
                                $html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">';
                                foreach ($stats as $stat) {
                                    $html .= '<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">';
                                    $html .= '<div class="text-2xl font-bold">' . $stat->getValue() . '</div>';
                                    $html .= '<div class="text-gray-500 dark:text-gray-400 mt-1">' . $stat->getName() . '</div>';
                                    if ($stat->getDescription()) {
                                        $html .= '<div class="text-sm text-gray-400 dark:text-gray-500 mt-2">' . $stat->getDescription() . '</div>';
                                    }
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                
                                return $html;
                            })
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Fitur Utama')
                    ->schema([
                        Placeholder::make('features')
                            ->content(
                                <<<HTML
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-emerald-100">
                                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">Deteksi Otomatis</h3>
                                            <p class="mt-1 text-sm text-gray-500">Deteksi jenis sampah menggunakan Machine Learning berdasarkan gambar.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-100">
                                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">Gamifikasi</h3>
                                            <p class="mt-1 text-sm text-gray-500">Sistem poin dan streak harian untuk mendorong konsistensi pengguna.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">Analitik</h3>
                                            <p class="mt-1 text-sm text-gray-500">Dashboard analitik untuk memantau tren pengelolaan sampah.</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">Dataset Training</h3>
                                            <p class="mt-1 text-sm text-gray-500">Dataset yang terus diperbarui untuk meningkatkan akurasi deteksi.</p>
                                        </div>
                                    </div>
                                </div>
                                HTML
                            )
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
