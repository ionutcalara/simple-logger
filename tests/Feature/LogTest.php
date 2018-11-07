<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogTest extends TestCase
{

	use RefreshDatabase;

	/**
	 *
	 */
	public function setUp() {
		parent::setUp();
		Artisan::call( 'db:seed', [ '--class' => 'TestUsersTableSeeder' ] );
	}

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNoContent()
    {
        $this->cleanup();
        $response = $this->get('/api/v1/log/read?tag=test');
        $response->assertStatus(422);
    }

    public function testAddAndRead()
    {
        $this->cleanup();
        $response = $this->get('/api/v1/log?api_token=' . env('API_TOKEN') . '&tag=test&key=test&data=1&test=2');
        $response->assertStatus(200);
        $read_page = $this->get('/api/v1/log/read?tag=test');
        $data = json_decode($read_page->getContent(), true);
        $this->assertArrayHasKey('test', $data);
    }

    private function cleanup()
    {
        Storage::disk('local')->delete('test.json');
    }
}
