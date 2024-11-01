<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\Business;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BusinessTest extends TestCase
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

    private function createBusiness($numberOfData = 1)
    {
        $faker = Factory::create();
        $businesses = [];
    
        for ($ctr = 0; $ctr < $numberOfData; $ctr++) {
            $business = Business::create([
                'name' => $faker->word(),
                'opening_hours' => $faker->dateTimeBetween("2024-1-1", "2024-1-31")->format('Y-m-d H:i:s'),
                'status' => 'open',
            ]);
    
            $businesses[] = [
                'id' => $business->id,
                'name' => $business->name,
                'opening_hours' => $business->opening_hours,
                'status' => $business->status,
            ];
        }
    
        return $businesses;
    }
    
    public function test_business_getAllBusiness_expected()
    {
        $businesses = $this->createBusiness(3);

        $response = $this->call('GET', '/api/businesses');
        $response->assertJson([
            'success' => true,
            'message' => 'Businesses Retrieved Successfully',
            'code' => 200,
            'total_businesses' => count($businesses),
            'data' => [
                [
                    'id' => $businesses[0]['id'],
                    'name' => $businesses[0]['name'],
                    'opening_hours' => $businesses[0]['opening_hours'],
                    'status' => $businesses[0]['status']
                ],
                [
                    'id' => $businesses[1]['id'],
                    'name' => $businesses[1]['name'],
                    'opening_hours' => $businesses[1]['opening_hours'],
                    'status' => $businesses[1]['status']
                ],
                [
                    'id' => $businesses[2]['id'],
                    'name' => $businesses[2]['name'],
                    'opening_hours' => $businesses[2]['opening_hours'],
                    'status' => $businesses[2]['status']
                ]
            ]
        ]);
    }

    public function test_business_createBusiness_expected()
    {
        $request = [
            'name' => 'sample_name',
            'opening_hours' => '2024-01-29 14:43:22',
            'status' => 'open'
        ];

        $response = $this->call('POST', '/api/createBusiness', $request);
        $response->assertJson([
            'success' => true,
            'message' => 'Business Created Successfully',
            'code' => 200,
            'data' => [
                'id' => 1,
                'name' => $request['name'],
                'opening_hours' => $request['opening_hours'],
                'status' => $request['status']
            ]
        ]);
    }

    public function test_business_getBusinessById_expected()
    {
        $business = Business::first();

        if (!$business) {
            $business = Business::create([
                'name' => 'original_name',
                'opening_hours' => '2024-01-01 08:00:00',
                'status' => 'open',
            ]);
        }

        $response = $this->call('GET', '/api/getBusiness/'. $business->id, );
        $response->assertJson([
            'success' => true,
            'message' => 'Business Retrieved Successfully',
            'code' => 200,
            'data' => [
                'id' => $business->id,
                'name' => $business['name'],
                'opening_hours' => $business['opening_hours'],
                'status' => $business['status']
            ]
        ]);
    }

    public function test_business_updateBusiness_expected()
    {
        $business = Business::first();

        if (!$business) {
            $business = Business::create([
                'name' => 'original_name',
                'opening_hours' => '2024-01-01 08:00:00',
                'status' => 'open',
            ]);
        }

        $request = [
            'name' => 'updated_name',
            'opening_hours' => '2024-01-29 14:43:22',
            'status' => 'closed'
        ];

        $response = $this->call('PUT', '/api/updateBusiness/'. $business->id, $request);
        $response->assertJson([
            'success' => true,
            'message' => 'Business Updated Successfully',
            'code' => 200,
            'data' => [
                'id' => $business->id,
                'name' => $request['name'],
                'opening_hours' => $request['opening_hours'],
                'status' => $request['status']
            ]
        ]);
    }

    public function test_business_deleteBusiness_expected()
    {
        $business = Business::first();

        if (!$business) {
            $business = Business::create([
                'name' => 'delete_name',
                'opening_hours' => '2024-01-01 08:00:00',
                'status' => 'open',
            ]);
        }

        $response = $this->call('DELETE', '/api/deleteBusiness/'. $business->id);
        $response->assertJson([
            'success' => true,
            'message' => 'Business deleted successfully',
            'code' => 201,
        ]);
    }
}
