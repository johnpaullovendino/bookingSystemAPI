<?php

namespace Tests\Feature;

use Faker\Factory;
use Tests\TestCase;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Business;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class BookingTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('services')->truncate();
        DB::table('business')->truncate();
        DB::table('bookings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function createBook($numberOfData = 1, $hasPromo = 0, $promoCode = null)
    {
        $faker = Factory::create();
        $bookings = [];

        $business = Business::first() ?: Business::create([
            'name' => $faker->word(),
            'opening_hours' => $faker->dateTimeBetween("2024-1-1", "2024-1-31")->format('Y-m-d H:i:s'),
            'status' => 'open',
        ]);
    
        $service = Service::first() ?: Service::create([
            'name' => 'sample_service_name',
            'business_id' => $business->id,
            'description' => 'sample description',
            'price' => 300, 
        ]);

        $discount = $hasPromo? $service->price * 0.1 : 0;

        for ($ctr = 0; $ctr < $numberOfData; $ctr++) {
            $bookings[] = [
                'book_id' => $faker->unique()->randomNumber(),
                'service_id' => $service->id,
                'duration' => $faker->dateTimeBetween("2024-1-1", "2024-1-31")->format('Y-m-d H:i:s'),
                'name' => $faker->name(),
                'phoneNumber' => $faker->phoneNumber(),
                'email' => $faker->email(),
                'amount' => $service->price,
                'promo' => $hasPromo,
                'promo_code' => $promoCode,
                'discount' => $discount,
                'total_amount' =>  $service->price - $discount,                
            ];
        }

        DB::table('bookings')->insert($bookings);

        return $bookings;
    }

    public function test_booking_getAllBookings_expected()
    {
        $bookings = $this->createBook(3,0,'');

        $response = $this->json('GET', '/api/bookings');
        $response->assertJson([
            'success' => true,
            'message' => 'Bookings Retrieved Successfully',
            'code' => 200,
            'total_bookings' => count($bookings),
            'data' => [
                [
                    'book_id' => $bookings[0]['book_id'],
                    'service_id' => $bookings[0]['service_id'],
                    'duration' => $bookings[0]['duration'],
                    'name' => $bookings[0]['name'],
                    'phoneNumber' => $bookings[0]['phoneNumber'],
                    'email' => $bookings[0]['email'],
                    'amount' => $bookings[0]['amount'],
                    'promo' => $bookings[0]['promo'],
                    'promoDetails' => [
                            'promo_code' => '',
                            'discount' => 0,
                            'total_amount' => $bookings[0]['amount'],
                    ]
                ],
                [
                    'book_id' => $bookings[1]['book_id'],
                    'service_id' => $bookings[1]['service_id'],
                    'duration' => $bookings[1]['duration'],
                    'name' => $bookings[1]['name'],
                    'phoneNumber' => $bookings[1]['phoneNumber'],
                    'email' => $bookings[1]['email'],
                    'amount' => $bookings[1]['amount'],
                    'promo' => $bookings[1]['promo'],
                    'promoDetails' => [
                            'promo_code' => '',
                            'discount' => 0,
                            'total_amount' => $bookings[1]['amount'],
                    ]
                ],
                [
                    'book_id' => $bookings[2]['book_id'],
                    'service_id' => $bookings[2]['service_id'],
                    'duration' => $bookings[2]['duration'],
                    'name' => $bookings[2]['name'],
                    'phoneNumber' => $bookings[2]['phoneNumber'],
                    'email' => $bookings[2]['email'],
                    'amount' => $bookings[2]['amount'],
                    'promo' => $bookings[2]['promo'],
                    'promoDetails' => [
                            'promo_code' => '',
                            'discount' => 0,
                            'total_amount' => $bookings[2]['amount'],
                    ]
                ]
            ]
        ]);
    }

    public function test_booking_getAllBookingsWithPromoCode_expected()
    {
        $bookings = $this->createBook(3,1,'PROMO10%Off');

        $response = $this->json('GET', '/api/bookings');
        $response->assertJson([
            'success' => true,
            'message' => 'Bookings Retrieved Successfully',
            'code' => 200,
            'total_bookings' => count($bookings),
            'data' => [
                [
                    'book_id' => $bookings[0]['book_id'],
                    'service_id' => $bookings[0]['service_id'],
                    'duration' => $bookings[0]['duration'],
                    'name' => $bookings[0]['name'],
                    'phoneNumber' => $bookings[0]['phoneNumber'],
                    'email' => $bookings[0]['email'],
                    'amount' => $bookings[0]['amount'],
                    'promo' => $bookings[0]['promo'],
                    'promoDetails' => [
                        'promo_code' => $bookings[1]['promo_code'],
                        'discount' => $bookings[1]['discount'],
                        'total_amount' => $bookings[1]['total_amount'],
                    ]
                ],
                [
                    'book_id' => $bookings[1]['book_id'],
                    'service_id' => $bookings[1]['service_id'],
                    'duration' => $bookings[1]['duration'],
                    'name' => $bookings[1]['name'],
                    'phoneNumber' => $bookings[1]['phoneNumber'],
                    'email' => $bookings[1]['email'],
                    'amount' => $bookings[1]['amount'],
                    'promo' => $bookings[1]['promo'],
                    'promoDetails' => [
                            'promo_code' => $bookings[1]['promo_code'],
                            'discount' => $bookings[1]['discount'],
                            'total_amount' => $bookings[1]['total_amount'],
                    ]
                    ],
                [
                    'book_id' => $bookings[2]['book_id'],
                    'service_id' => $bookings[2]['service_id'],
                    'duration' => $bookings[2]['duration'],
                    'name' => $bookings[2]['name'],
                    'phoneNumber' => $bookings[2]['phoneNumber'],
                    'email' => $bookings[2]['email'],
                    'amount' => $bookings[2]['amount'],
                    'promo' => $bookings[2]['promo'],
                    'promoDetails' => [
                            'promo_code' => $bookings[2]['promo_code'],
                            'discount' => $bookings[2]['discount'],
                            'total_amount' => $bookings[2]['total_amount'],
                    ]
                ]
            ]
        ]);
    }


    public function test_booking_createBooking_expected()
    {
        $business = Business::first() ?: Business::create([
            'name' => 'Business Name',
            'opening_hours' => '2024-01-29 12:00:00',
            'status' => 'open',
        ]);
    
        Service::first() ?: Service::create([
            'name' => 'sample_service_name',
            'business_id' => $business->id,
            'description' => 'sample description',
            'price' => 500, 
        ]);

        $request = [
            'service_id' => 1,
            'duration' => '2024-01-30 12:00:00',
            'name'  =>  'John Doe',
            'phoneNumber'  =>  '09123456789',
            'email'  =>  'johndoe@example.com',
            'promo' => 0,
            'promo_code'  => '',
            'amount' => 500
        ];

        $response = $this->post('/api/createBooking', $request);
        $response->assertJson([
            'success' => true,
            'message' => 'Booking created successfully',
            'code' => 201,
            'data' => [
                'service_id' => $request['service_id'],
                'duration' => $request['duration'],
                'name' => $request['name'],
                'phoneNumber' => $request['phoneNumber'],
                'email' => $request['email'],
                'amount' => $request['amount'],
                'promo' => $request['promo'],
                'promo_code' => $request['promo_code'],
                'discount' => 0,
                'total_amount' => $request['amount']
            ]
        ]);
    }


    public function test_booking_createBookingWithPromoCode_expected()
    {
        $business = Business::first() ?: Business::create([
            'name' => 'Business Name',
            'opening_hours' => '2024-01-29 12:00:00',
            'status' => 'open',
        ]);
    
        Service::first() ?: Service::create([
            'name' => 'sample_service_name',
            'business_id' => $business->id,
            'description' => 'sample description',
            'price' => 500, 
        ]);

        $request =  [
            'service_id' => 1,
            'duration' => '2024-01-30 12:00:00',
            'name'  =>  'John Doe',
            'phoneNumber'  =>  '09123456789',
            'email'  =>  'johndoe@example.com',
            'promo' => 0,
            'promo_code'  => 'PROMO10%Off',
            'amount' => 500
        ]; 

        $response = $this->post('/api/createBooking', $request);
        $response->assertJson([
            'success' => true,
            'message' => 'Booking created successfully',
            'code' => 201,
            'data' => [
                'service_id' => $request['service_id'],
                'duration' => $request['duration'],
                'name' => $request['name'],
                'phoneNumber' => $request['phoneNumber'],
                'email' => $request['email'],
                'amount' => $request['amount'],
                'promo' => 1,
                'promo_code' => $request['promo_code'],
                'discount' => 50,
                'total_amount' => 450
            ]
        ]);
    }

    public function test_booking_getBooking_expected()
    {
        $booking = Booking::first();

        if (!$booking) {
            $this->createBook(1,0, '');
            $booking = Booking::first();
        }

        $id = $booking->id;

        $response = $this->get('/api/getBooking/'. $id);
        $response->assertJson([
            'success' => true,
            'message' => 'Booking retrieved successfully',
            'code' => 200,
            'data' => [
                'book_id' => $booking->book_id,
                'service_id' => $booking->service_id,
                'duration' => $booking->duration,
                'name' => $booking->name,
                'phoneNumber' => $booking->phoneNumber,
                'email' => $booking->email,
                'amount' => $booking->amount,
                'promo' => $booking->promo,
                'promoDetails' => [
                    'promo_code' => '',
                    'discount' => 0,
                    'total_amount' => $booking->amount
                    ]
                ]
            ]);
    }

    public function test_booking_getBookingWithPromo_expected()
    {
        $booking = Booking::first();

        if (!$booking) {
            $this->createBook(1,1, 'PROMO10%Off');
            $booking = Booking::first();
        }

        $id = $booking->id;

        $response = $this->get('/api/getBooking/'. $id);
        $response->assertJson([
            'success' => true,
            'message' => 'Booking retrieved successfully',
            'code' => 200,
            'data' => [
                'book_id' => $booking->book_id,
                'service_id' => $booking->service_id,
                'duration' => $booking->duration,
                'name' => $booking->name,
                'phoneNumber' => $booking->phoneNumber,
                'email' => $booking->email,
                'amount' => $booking->amount,
                'promo' => $booking->promo,
                'promoDetails' => [
                    'promo_code' => $booking->promo_code,
                    'discount' => $booking->discount,
                    'total_amount' => $booking->total_amount,
                ]
            ]
        ]);
    }

    public function test_booking_deleteBooking_expected()
    {
        $booking = Booking::first();

        if (!$booking) {
            $this->createBook(1,0,null);
            $booking = Booking::first();
        }

        $id = $booking->id;

        $response = $this->delete('/api/deleteBooking/'. $id);
        $response->assertJson([
            'success' => true,
            'message' => 'Booking Deleted Successfully',
            'code' => 200,
        ]);
    }

    #[DataProvider('createBookingRequestParams')]
    public function test_booking_incompleteRequestData_expected($requestParams)
    {
        $request = [
            'service_id' => 1,
            'duration' => 'sample_duration',
            'name'  =>  'sampleName',
            'phoneNumber'  =>  'samplePhoneNumber',
            'email'  =>  'sample@email.com',
            'amount' => 100,
            'promo' => 0,
        ];

        unset($request[$requestParams]);

        $response = $this->post('/api/createBooking', $request);
        $response->assertJson([
            'response_code' => 400,
        ]);
    }

    public static function createBookingRequestParams()
    {
        return [
            ['service_id'],
            ['duration'],
            ['name'],
            ['phoneNumber'],
            ['email'],
            ['amount'],
            ['promo']
        ];
    }

    #[DataProvider('createBookingInvalidParams')]
    public function test_booking_invalidRequestData_expected($key, $value)
    {
        $request = [
            'service_id' => 1,
            'duration' => '2024-01-30 12:00:00',
            'name'  =>  'John Doe',
            'phoneNumber'  =>  '09123456789',
            'email'  =>  'johndoe@example.com',
            'amount' => 500,
            'promo' => 0,
        ];

        
    }

    public static function createBookingInvalidParams()
    {
        return [
            ['service_id' => 'invalid_service_id'],
            ['duration' => 123],
            ['name' => 123],
            ['phoneNumber' => 09123456789],
            ['email' => 123],
        ];
    }
}
