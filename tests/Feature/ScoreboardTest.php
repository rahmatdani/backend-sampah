<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ScoreboardController;
use App\Models\Pengguna;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;

class ScoreboardTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_it_returns_top_five_scores_and_current_user_rank_without_database(): void
    {
        config()->set('session.driver', 'array');
        config()->set('app.url', 'http://localhost');

        $controller = app(ScoreboardController::class);

        $user = new Pengguna();
        $user->id = 6;
        $user->nama = 'User 6';
        $user->avatar_profil_id = null;

        $request = Request::create('/api/scoreboard', 'GET');
        $request->setUserResolver(fn () => $user);

        $builder = Mockery::mock();

        DB::shouldReceive('raw')
            ->andReturnUsing(fn (string $value) => new Expression($value));

        DB::shouldReceive('table')
            ->once()
            ->with('catatan_sampahs as cs')
            ->andReturn($builder);

        $builder->shouldReceive('select')
            ->once()
            ->with(
                'cs.pengguna_id',
                'p.nama',
                'p.avatar_profil_id',
                Mockery::type(Expression::class),
                Mockery::type(Expression::class),
            )
            ->andReturnSelf();

        $builder->shouldReceive('join')
            ->once()
            ->with('penggunas as p', 'p.id', '=', 'cs.pengguna_id')
            ->andReturnSelf();

        $builder->shouldReceive('leftJoin')
            ->once()
            ->with('avatar_profil as ap', 'ap.id', '=', 'p.avatar_profil_id')
            ->andReturnSelf();

        $builder->shouldReceive('where')
            ->once()
            ->with('cs.is_divalidasi', true)
            ->andReturnSelf();

        $builder->shouldReceive('groupBy')
            ->once()
            ->with('cs.pengguna_id', 'p.nama', 'p.avatar_profil_id')
            ->andReturnSelf();

        $builder->shouldReceive('orderByDesc')
            ->once()
            ->with('total_validasi')
            ->andReturnSelf();

        $builder->shouldReceive('orderBy')
            ->once()
            ->with('p.nama')
            ->andReturnSelf();

        $builder->shouldReceive('get')
            ->once()
            ->andReturn(collect([
                (object) ['pengguna_id' => 1, 'nama' => 'User 1', 'avatar_profil_id' => null, 'total_validasi' => 6, 'avatar_path' => 'avatars/top.png'],
                (object) ['pengguna_id' => 2, 'nama' => 'User 2', 'avatar_profil_id' => null, 'total_validasi' => 4, 'avatar_path' => null],
                (object) ['pengguna_id' => 3, 'nama' => 'User 3', 'avatar_profil_id' => null, 'total_validasi' => 4, 'avatar_path' => null],
                (object) ['pengguna_id' => 4, 'nama' => 'User 4', 'avatar_profil_id' => null, 'total_validasi' => 3],
                (object) ['pengguna_id' => 5, 'nama' => 'User 5', 'avatar_profil_id' => null, 'total_validasi' => 2],
                (object) ['pengguna_id' => 6, 'nama' => 'User 6', 'avatar_profil_id' => null, 'total_validasi' => 1, 'avatar_path' => 'https://example.com/avatar.png'],
            ]));

        $response = $controller->index($request);

        $data = $response->getData(true);

        $this->assertIsArray($data['top_scores']);
        $this->assertCount(5, $data['top_scores']);

        $this->assertSame(6, $data['top_scores'][0]['total_validasi']);
        $this->assertSame(1, $data['top_scores'][0]['rank']);
        $this->assertSame(asset('storage/avatars/top.png'), $data['top_scores'][0]['avatar_url']);

        $this->assertSame($user->id, $data['current_user']['user_id']);
        $this->assertSame(1, $data['current_user']['total_validasi']);
        $this->assertSame(5, $data['current_user']['rank']);
        $this->assertSame('https://example.com/avatar.png', $data['current_user']['avatar_url']);
    }
}
