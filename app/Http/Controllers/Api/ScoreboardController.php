<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ScoreboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $scores = DB::table('catatan_sampahs as cs')
            ->select(
                'cs.pengguna_id',
                'p.nama',
                'p.avatar_profil_id',
                DB::raw('COUNT(*) as total_validasi'),
                DB::raw('MAX(ap.path) as avatar_path'),
            )
            ->join('penggunas as p', 'p.id', '=', 'cs.pengguna_id')
            ->leftJoin('avatar_profil as ap', 'ap.id', '=', 'p.avatar_profil_id')
            ->where('cs.is_divalidasi', true)
            ->groupBy('cs.pengguna_id', 'p.nama', 'p.avatar_profil_id')
            ->orderByDesc('total_validasi')
            ->orderBy('p.nama')
            ->get();

        $rankedScores = $this->attachDenseRank($scores);

        $topScores = $rankedScores->take(5)->values();

        $currentUserEntry = $rankedScores->firstWhere('user_id', $user->id);

        if (!$currentUserEntry) {
            $validatedCount = DB::table('catatan_sampahs')
                ->where('pengguna_id', $user->id)
                ->where('is_divalidasi', true)
                ->count();

            $rank = null;

            if ($validatedCount > 0) {
                $higherScores = $rankedScores
                    ->filter(fn (array $row) => $row['total_validasi'] > $validatedCount)
                    ->count();

                $rank = $higherScores + 1;
            }

            $currentUserEntry = [
                'user_id' => $user->id,
                'nama' => $user->nama,
                'avatar_profil_id' => $user->avatar_profil_id,
                'total_validasi' => (int) $validatedCount,
                'rank' => $rank,
                'avatar_url' => $this->formatAvatarPath(optional($user->avatarProfil)->path ?? null),
            ];
        }

        return response()->json([
            'top_scores' => $topScores,
            'current_user' => $currentUserEntry,
        ]);
    }

    private function attachDenseRank(Collection $scores): Collection
    {
        $currentRank = 0;
        $previousScore = null;

        return $scores->values()->map(function ($row) use (&$currentRank, &$previousScore) {
            if ($previousScore === null || $row->total_validasi !== $previousScore) {
                $currentRank++;
                $previousScore = $row->total_validasi;
            }

            return [
                'user_id' => (int) $row->pengguna_id,
                'nama' => $row->nama,
                'avatar_profil_id' => $row->avatar_profil_id,
                'total_validasi' => (int) $row->total_validasi,
                'rank' => $currentRank,
                'avatar_url' => $this->formatAvatarPath($row->avatar_path ?? null),
            ];
        });
    }

    private function formatAvatarPath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
