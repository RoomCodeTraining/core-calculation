<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VehicleCharacteristic;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VehicleCharacteristicTest extends TestCase
{
    use  RefreshDatabase;

    protected string $endpoint = '/api/vehicleCharacteristics';
    protected string $tableName = 'vehicleCharacteristics';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCreateVehicleCharacteristic(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        $payload = VehicleCharacteristic::factory()->make([])->toArray();

        $this->json('POST', $this->endpoint, $payload)
             ->assertStatus(201)
             ->assertSee($payload['name']);

        $this->assertDatabaseHas($this->tableName, ['id' => 1]);
    }

    public function testViewAllVehicleCharacteristicsSuccessfully(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        VehicleCharacteristic::factory(5)->create();

        $this->json('GET', $this->endpoint)
             ->assertStatus(200)
             ->assertJsonCount(5, 'data')
             ->assertSee(VehicleCharacteristic::find(rand(1, 5))->name);
    }

    public function testViewAllVehicleCharacteristicsByFooFilter(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        VehicleCharacteristic::factory(5)->create();

        $this->json('GET', $this->endpoint.'?foo=1')
             ->assertStatus(200)
             ->assertSee('foo')
             ->assertDontSee('foo');
    }

    public function testsCreateVehicleCharacteristicValidation(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        $data = [
        ];

        $this->json('post', $this->endpoint, $data)
             ->assertStatus(422);
    }

    public function testViewVehicleCharacteristicData(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        VehicleCharacteristic::factory()->create();

        $this->json('GET', $this->endpoint.'/1')
             ->assertSee(VehicleCharacteristic::first()->name)
             ->assertStatus(200);
    }

    public function testUpdateVehicleCharacteristic(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        VehicleCharacteristic::factory()->create();

        $payload = [
            'name' => 'Random'
        ];

        $this->json('PUT', $this->endpoint.'/1', $payload)
             ->assertStatus(200)
             ->assertSee($payload['name']);
    }

    public function testDeleteVehicleCharacteristic(): void
    {
        $this->markTestIncomplete('This test case needs review.');

        $this->actingAs(User::factory()->create());

        VehicleCharacteristic::factory()->create();

        $this->json('DELETE', $this->endpoint.'/1')
             ->assertStatus(204);

        $this->assertEquals(0, VehicleCharacteristic::count());
    }
    
}
