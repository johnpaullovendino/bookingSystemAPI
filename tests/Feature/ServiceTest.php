<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\Business;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServiceTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('services')->truncate();
        DB::table('business')->truncate(); 
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    
    private function createService($numberOfData  = 1)
    {
        $faker = Factory::create();

        $business = Business::first();
        if (!$business) {
            $business = Business::create([
                'name' => $faker->word(),
                'opening_hours' => $faker->dateTimeBetween("2024-1-1", "2024-1-31")->format('Y-m-d H:i:s'),
                'status' => 'open',
            ]);
        }

        $services = []; 

        for ($ctr = 0; $ctr < $numberOfData; $ctr++) {
            $services[] = [
                'business_id' => $business->id,
                'name' => $faker->sentence(5),
                'price' => $faker->randomFloat(2, 10, 1000),
                'created_at' => $faker->dateTimeBetween("2024-1-1", "2024-1-31")
            ];
        }

        DB::table('services')->insert($services);

        return $services;
    }

    public function test_service_getAllServices_expected()
    {
        $services = $this->createService(3);

        $response = $this->call('GET', '/api/services');
        $response->assertJson([
            'success' => true,
            'message' => 'Service Retrieved Successfully',
            'code' => 200,
            'total_services' => count($services),
            'data' => [
                [
                    'business_id' => $services[0]['business_id'],
                    'name' => $services[0]['name'],
                    'price' => $services[0]['price'],
                ],
                [
                    'business_id' => $services[1]['business_id'],
                    'name' => $services[1]['name'],
                    'price' => $services[1]['price'],
                ],
                [
                    'business_id' => $services[2]['business_id'],
                    'name' => $services[2]['name'],
                    'price' => $services[2]['price'],
                ],
            ]
        ]);
    }

    public function test_service_createService_expected()
    {   
        $business = Business::first();

        if(!$business) {
            Business::create([
                'name' => 'sample_business',
                'opening_hours' =>'2024-01-29 12:00:00',
                'status' => 'open',
            ]);
        }

        $request  = [
            'business_id' => 1,
            'name' => 'sample_service_name',
            'description' => 'sample description',
            'price' => 500.00
        ];

        $response = $this->call('POST', 'api/createService', $request);
        $response->assertJson([
            'success' => true,
            'message' => 'Service Created Successfully',
            'code' => 200,
            'data' => [
                'business_id' => $request['business_id'],
                'name' => $request['name'],
                'description' => $request['description'],
                'price' => $request['price'],
            ]
        ]);
    }

    public function test_service_getService_expected()
    {
        $business = Business::first();

        if (!$business) {
            $business = Business::create([
                'name' => 'sample_business',
                'opening_hours' => '2024-01-29 12:00:00',
                'status' => 'open',
            ]);
        }
        $service = Service::first();

        if (!$service) {
            $service = Service::create([
                'name' => 'original_service_name',
                'business_id' => $business->id,
                'description' => 'original description',
                'price' => 500
            ]);
        }

        $response = $this->call('GET', '/api/getService/'. $service->id);
        $response->assertJson([
            'success' => true,
            'message' => 'Service Retrieved Successfully',
            'code' => 200,
            'data' => [
                'business_id' => $service['business_id'],
                'name' => $service['name'],
                'description' => $service['description'],
                'price' => $service['price'],
            ]
        ]);
    }

    public function test_service_updateService_expected()
    {
        $business = Business::first();

        if (!$business) {
            $business = Business::create([
                'name' => 'sample_business',
                'opening_hours' => '2024-01-29 12:00:00',
                'status' => 'open',
            ]);
        }

        $service = Service::first();
        if (!$service) {
            $service = Service::create([
                'name' => 'original_service_name',
                'business_id' => $business->id, 
                'description' => 'original description',
                'price' => 500
            ]);
        }

        $request = [
            'name' => 'updated_service_name',
            'business_id' => 1, 
            'description' => 'updated description',
            'price' => 600
        ];

        $response = $this->call('PUT', '/api/updateService/' . $service->id, $request);
        $response->assertJson([
            'success' => true,
            'message' => 'Service Updated Successfully',
            'code' => 200,
            'data' => [
                'name' => $request['name'],
                'description' => $request['description'],
                'price' => $request['price'],
                'business_id' => $request['business_id']
            ]
        ]);
    }

    public function test_service_deleteBusiness_expected()
    {
        $business = Business::first();

        if (!$business) {
            $business = Business::create([
                'name' => 'sample_business',
                'opening_hours' => '2024-01-29 12:00:00',
                'status' => 'open',
            ]);
        }
        $services = Service::first();

        if (!$services) {
            $services = Service::create([
                'name' => 'original_service_name',
                'business_id' => $business->id,
                'description' => 'original description',
                'price' => 500
            ]);
        }

        $response = $this->call('DELETE', '/api/deleteService/'. $services->id);
        $response->assertJson([
            'success' => true,
            'message' => 'Service Deleted Successfully',
            'code' => 200,
        ]);
    }
}
