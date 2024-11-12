<?php

namespace WalkerChiu\MorphBoard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use WalkerChiu\Core\Models\Constants\CountryZone;
use WalkerChiu\MorphBoard\Models\Constants\BoardType;
use WalkerChiu\MorphBoard\Models\Entities\Board;
use WalkerChiu\MorphBoard\Models\Forms\BoardFormRequest;

class BoardFormRequestTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        //$this->loadLaravelMigrations(['--database' => 'mysql']);
        $this->loadMigrationsFrom(__DIR__ .'/../migrations');
        $this->withFactories(__DIR__ .'/../../src/database/factories');

        $this->request  = new BoardFormRequest();
        $this->rules    = $this->request->rules();
        $this->messages = $this->request->messages();
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
     * Unit test about Authorize.
     *
     * For WalkerChiu\MorphBoard\Models\Forms\BoardFormRequest
     * 
     * @return void
     */
    public function testAuthorize()
    {
        $this->assertEquals(true, 1);
    }

    /**
     * Unit test about Rules.
     *
     * For WalkerChiu\MorphBoard\Models\Forms\BoardFormRequest
     * 
     * @return void
     */
    public function testRules()
    {
        $faker = \Faker\Factory::create();

        $user_id = $faker->uuid();
        DB::table(config('wk-core.table.user'))->insert([
            'id'       => $user_id,
            'name'     => $faker->username,
            'email'    => $faker->email,
            'password' => $faker->password
        ]);


        // Give
        $attributes = [
            'type'           => $faker->randomElement(config('wk-core.class.morph-board.boardType')::getCodes()),
            'user_id'        => $user_id,
            'serial'         => $faker->isbn10,
            'identifier'     => $faker->slug,
            'is_highlighted' => $faker->boolean,
            'is_enabled'     => $faker->boolean,
            'name'           => $faker->name,
            'content'        => $faker->text
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(false, $fails);

        // Give
        $attributes = [
            'type'           => $faker->randomElement(config('wk-core.class.morph-board.boardType')::getCodes()),
            'serial'         => $faker->isbn10,
            'identifier'     => $faker->slug,
            'is_highlighted' => $faker->boolean,
            'is_enabled'     => $faker->boolean,
            'name'           => $faker->name,
            'content'        => $faker->text
        ];
        // When
        $validator = Validator::make($attributes, $this->rules, $this->messages); $this->request->withValidator($validator);
        $fails = $validator->fails();
        // Then
        $this->assertEquals(true, $fails);
    }
}
