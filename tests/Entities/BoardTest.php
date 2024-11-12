<?php

namespace WalkerChiu\MorphBoard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use WalkerChiu\MorphBoard\Models\Entities\Board;

class BoardTest extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');
    }

    /**
     * To load your package service provider, override the getPackageProviders.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return Array
     */
    protected function getPackageProviders($app)
    {
        return [\WalkerChiu\Core\CoreServiceProvider::class,
                \WalkerChiu\MorphBoard\MorphBoardServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    /**
     * A basic functional test on Board.
     *
     * For WalkerChiu\MorphBoard\Models\Entities\Board
     * 
     * @return void
     */
    public function testMorphBoard()
    {
        // Config
        Config::set('wk-core.onoff.core-lang_core', 0);
        Config::set('wk-morph-board.onoff.core-lang_core', 0);
        Config::set('wk-core.lang_log', 1);
        Config::set('wk-morph-board.lang_log', 1);
        Config::set('wk-core.soft_delete', 1);
        Config::set('wk-morph-board.soft_delete', 1);

        $faker = \Faker\Factory::create();

        $user_id = $faker->uuid();
        DB::table(config('wk-core.table.user'))->insert([
            'id'       => $user_id,
            'name'     => $faker->username,
            'email'    => $faker->email,
            'password' => $faker->password
        ]);

        // Give
        $db_morph_1 = factory(Board::class)->create(['user_id' => $user_id]);
        $db_morph_2 = factory(Board::class)->create(['user_id' => $user_id]);

        // Get records after creation
            // When
            $records = Board::all();
            // Then
            $this->assertCount(2, $records);

        // Delete someone
            // When
            $db_morph_2->delete();
            $records = Board::all();
            // Then
            $this->assertCount(1, $records);

        // Resotre someone
            // When
            Board::withTrashed()
                 ->find($db_morph_2->id)
                 ->restore();
            $record_2 = Board::find($db_morph_2->id);
            $records = Board::all();
            // Then
            $this->assertNotNull($record_2);
            $this->assertCount(2, $records);
    }
}
