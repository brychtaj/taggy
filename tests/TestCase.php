<?php 

use Brychtaj\Taggy\TaggyServiceProvider;
use Illuminate\Database\Eloquent\Model;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [TaggyServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();

        // Mass asignment will be ignored
        Model::unguard();

        $this->artisan("migrate", [
            "--database" => "testbench",
            "--realpath" => realpath(__DIR__ . "/../migrations"),
        ]);
    }

    protected function tearDown()
    {
        \Schema::drop("lessons");
    }

    protected function getEnvironmentSetup($app)
    {
        $app["config"]->set("database.default", "testbench");

        $app["config"]->set("database.connections.testbench", [
            "driver" => "sqlite",
            "database" => ":memory:",
            "prefix" => ""
        ]);

        \Schema::create("lessons", function($table) {
            $table->increments('id');
            $table->string("title");
            $table->timestamps();
        });
    }

}
