<?php

namespace WalkerChiu\MorphBoard;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use WalkerChiu\MorphBoard\Models\Entities\Board;
use WalkerChiu\MorphBoard\Models\Entities\BoardLang;

class BoardLangTest extends \Orchestra\Testbench\TestCase
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
     * A basic functional test on BoardLang.
     *
     * For WalkerChiu\Core\Models\Entities\Lang
     *     WalkerChiu\MorphBoard\Models\Entities\MorphBoardLang
     *
     * @return void
     */
    public function testMorphBoardLang()
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
        $db_lang_1 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'name', 'value' => 'Hello']);
        $db_lang_2 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'description']);
        $db_lang_3 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'zh_tw', 'key' => 'description']);
        $db_lang_4 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_5 = factory(BoardLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_6 = factory(BoardLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Board::class, 'code' => 'zh_tw', 'key' => 'description']);

        // Get records after creation
            // When
            $records = BoardLang::all();
            // Then
            $this->assertCount(6, $records);

        // Get record's morph
            // When
            $record = BoardLang::find($db_lang_1->id);
            // Then
            $this->assertNotNull($record);
            $this->assertInstanceOf(Board::class, $record->morph);

        // Scope query on whereCode
            // When
            $records = BoardLang::ofCode('en_us')
                                ->get();
            // Then
            $this->assertCount(4, $records);

        // Scope query on whereKey
            // When
            $records = BoardLang::ofKey('name')
                                ->get();
            // Then
            $this->assertCount(3, $records);

        // Scope query on whereCodeAndKey
            // When
            $records = BoardLang::ofCodeAndKey('en_us', 'name')
                                ->get();
            // Then
            $this->assertCount(3, $records);

        // Scope query on whereMatch
            // When
            $records = BoardLang::ofMatch('en_us', 'name', 'Hello')
                                ->get();
            // Then
            $this->assertCount(1, $records);
            $this->assertTrue($records->contains('id', $db_lang_1->id));
    }

    /**
     * A basic functional test on BoardLang.
     *
     * For WalkerChiu\Core\Models\Entities\LangTrait
     *     WalkerChiu\MorphBoard\Models\Entities\MorphBoardLang
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
        $db_lang_1 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'name', 'value' => 'Hello']);
        $db_lang_2 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'board_line1']);
        $db_lang_3 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'zh_tw', 'key' => 'board_line1']);
        $db_lang_4 = factory(BoardLang::class)->create(['morph_id' => $db_morph_1->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_5 = factory(BoardLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Board::class, 'code' => 'en_us', 'key' => 'name']);
        $db_lang_6 = factory(BoardLang::class)->create(['morph_id' => $db_morph_2->id, 'morph_type' => Board::class, 'code' => 'zh_tw', 'key' => 'board_line1']);

        // Get lang of record
            // When
            $record_1 = Board::find($db_morph_1->id);
            $lang_1   = BoardLang::find($db_lang_1->id);
            $lang_4   = BoardLang::find($db_lang_4->id);
            // Then
            $this->assertNotNull($record_1);
            $this->assertTrue(!$lang_1->is_current);
            $this->assertTrue($lang_4->is_current);
            $this->assertCount(4, $record_1->langs);
            $this->assertInstanceOf(BoardLang::class, $record_1->findLang('en_us', 'name', 'entire'));
            $this->assertEquals($db_lang_4->id, $record_1->findLang('en_us', 'name', 'entire')->id);
            $this->assertEquals($db_lang_4->id, $record_1->findLangByKey('name', 'entire')->id);
            $this->assertEquals($db_lang_2->id, $record_1->findLangByKey('board_line1', 'entire')->id);

        // Get lang's histories of record
            // When
            $histories_1 = $record_1->getHistories('en_us', 'name');
            $record_2 = Board::find($db_morph_2->id);
            $histories_2 = $record_2->getHistories('en_us', 'name');
            // Then
            $this->assertCount(1, $histories_1);
            $this->assertCount(0, $histories_2);
    }
}
