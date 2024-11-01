<?php

namespace Tests\Feature\Feature;

use Exception;
use Carbon\Carbon;
use Faker\Factory;
use Tests\TestCase;
use App\Models\User;
use App\Helpers\MoneyFormatter;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReportTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        DB::statement('TRUNCATE TABLE testing.reports');
    }

    private function createReport($numberOfData = 1, $hasPromo = 0, $promoCode = null)
    {
        $faker = Factory::create();
        $paymentMethods = ['Cash', 'Credit Card', 'Debit Card', 'Bank Transfer'];
        $amount = $faker->randomFloat(2, 301, 1000);
        $discount = $hasPromo? $amount * 0.1 : 0;

        $updatedAt = $faker->dateTimeBetween("2024-1-1", "2024-1-31");

        for ($ctr = 0; $ctr < $numberOfData; $ctr++) {
            $report[] = [
                'book_id' => $faker->unique()->randomNumber(),
                'name' => $faker->name(),
                'service_name' => 'Hotel Booking Services',
                'amount' => $amount,
                'duration' => $updatedAt,
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'payment_status' => 'paid',
                'status' => 'Completed',
                'note' => $faker->sentence(),
                'created_at' => $updatedAt->format('Y-m-d H:i:s'),
                'updated_at' => $updatedAt->format('Y-m-d H:i:s'),
                'promo' => $hasPromo,
                'promo_code' => $promoCode,
                'discount' => MoneyFormatter::format($discount),
                'total_amount' => MoneyFormatter::format($amount - $discount)
            ];

            $updatedAt = Carbon::parse($updatedAt)->addSecond();
        }

        DB::table('testing.reports')->insert($report);

        return $report;
    }

    public function test_reports_validData_expected()
    {
        $report = $this->createReport(1, 0, '');

        $request = [
            'from_date' => Carbon::parse($report[0]['created_at'])->format('Y-m-d H:i:s'),
            'to_date' => Carbon::parse($report[0]['created_at'])->format('Y-m-d H:i:s'),
            'bookId' => 'sample_book_id',
            'serviceName' => 'sample_service_name',
        ];

        $response = $this->call('POST', '/api/report', $request);
        $response->assertJson([
            'response_code' => '200',
            'from_date' => $request['from_date'],
            'to_date' => $request['to_date'],
            'total_reports' => 1,
            'data' => [
                [
                    'book_id' => $report[0]['book_id'],
                    'clientName' => $report[0]['name'],
                    'serviceName' => $report[0]['service_name'],
                    'amount' => MoneyFormatter::format($report[0]['amount']),
                    'payment_method' => $report[0]['payment_method'],
                    'payment_status' => $report[0]['payment_status'],
                    'bookStatus' => $report[0]['status'],
                    'note' => $report[0]['note'],
                    'booked_at' => Carbon::parse($report[0]['created_at'])->format('Y-m-d\TH:i:s.u\Z'),
                ]
            ]
        ]);
    }

    public function test_reports_validDataWithPromoCode_expected()
    {
        $report = $this->createReport(1, 1, "PROMO10%Off");

        $request = [
            'from_date' => Carbon::parse($report[0]['created_at'])->format('Y-m-d H:i:s'),
            'to_date' => Carbon::parse($report[0]['created_at'])->format('Y-m-d H:i:s'),
            'bookId' => 'sample_book_id',
            'serviceName' => 'sample_service_name',
        ];

        $response = $this->call('POST', '/api/report', $request);
        // dd($response);
        $response->assertJson([
            'response_code' => '200',
            'from_date' => $request['from_date'],
            'to_date' => $request['to_date'],
            'total_reports' => 1,
            'data' => [
                [
                    'book_id' => $report[0]['book_id'],
                    'clientName' => $report[0]['name'],
                    'serviceName' => $report[0]['service_name'],
                    'amount' => MoneyFormatter::format($report[0]['amount']),
                    'payment_method' => $report[0]['payment_method'],
                    'payment_status' => $report[0]['payment_status'],
                    'bookStatus' => $report[0]['status'],
                    'note' => $report[0]['note'],
                    'booked_at' => Carbon::parse($report[0]['created_at'])->format('Y-m-d\TH:i:s.u\Z'),
                    'hasPromo' => 1,
                    'promoDetails' => [
                        'promo_code' => 'PROMO10%Off',
                        'discount' => MoneyFormatter::format($report[0]['discount']),
                        'total_amount' => MoneyFormatter::format($report[0]['total_amount']),
                    ]  
                ]
            ]
        ]);
    }

    public function test_reports_multipleReports_expected()
    {
        $reports = $this->createReport(2, 0, '');

        $request = [
            'from_date' => Carbon::parse($reports[0]['created_at'])->format('Y-m-d H:i:s'),
            'to_date' => Carbon::parse($reports[0]['created_at'])->format('Y-m-d H:i:s'),
            'bookId' => 'sample_book_id',
            'serviceName' => 'sample_service_name',
        ];

        $response = $this->call('POST', '/api/report', $request);
        $response->assertJson([
            'response_code' => '200',
            'from_date' => $request['from_date'],
            'to_date' => $request['to_date'],
            'total_reports' => count($reports),
            'data' => [
                [
                    'book_id' => $reports[0]['book_id'],
                    'clientName' => $reports[0]['name'],
                    'serviceName' => $reports[0]['service_name'],
                    'amount' => MoneyFormatter::format($reports[0]['amount']),
                    'payment_method' => $reports[0]['payment_method'],
                    'payment_status' => $reports[0]['payment_status'],
                    'bookStatus' => $reports[0]['status'],
                    'note' => $reports[0]['note'],
                    'booked_at' => Carbon::parse($reports[0]['created_at'])->format('Y-m-d\TH:i:s.u\Z'),
                    'hasPromo' => 0,  
                ],
                [
                    'book_id' => $reports[1]['book_id'],
                    'clientName' => $reports[1]['name'],
                    'serviceName' => $reports[1]['service_name'],
                    'amount' => MoneyFormatter::format($reports[1]['amount']),
                    'payment_method' => $reports[1]['payment_method'],
                    'payment_status' => $reports[1]['payment_status'],
                    'bookStatus' => $reports[1]['status'],
                    'note' => $reports[1]['note'],
                    'booked_at' => Carbon::parse($reports[1]['created_at'])->format('Y-m-d\TH:i:s.u\Z'),
                    'hasPromo' => 0,  
                ],
            ]
        ]);
    }

    public function test_reports_multipleReportsWithPromoCode_expected()
    {
        $reports = $this->createReport(3, 1, 'PROMO10%Off');

        $request = [
            'from_date' => Carbon::parse($reports[0]['created_at'])->format('Y-m-d H:i:s'),
            'to_date' => Carbon::parse($reports[0]['created_at'])->format('Y-m-d H:i:s'),
            'bookId' => 'sample_book_id',
            'serviceName' => 'sample_service_name',
        ];

        $response = $this->call('POST', '/api/report', $request);
        $response->assertJson([
            'response_code' => '200',
            'from_date' => $request['from_date'],
            'to_date' => $request['to_date'],
            'total_reports' => count($reports),
            'data' => [
                [
                    'book_id' => $reports[0]['book_id'],
                    'clientName' => $reports[0]['name'],
                    'serviceName' => $reports[0]['service_name'],
                    'amount' => MoneyFormatter::format($reports[0]['amount']),
                    'payment_method' => $reports[0]['payment_method'],
                    'payment_status' => $reports[0]['payment_status'],
                    'bookStatus' => $reports[0]['status'],
                    'note' => $reports[0]['note'],
                    'booked_at' => Carbon::parse($reports[0]['created_at'])->format('Y-m-d\TH:i:s.u\Z'),
                    'hasPromo' => 1,
                    'promoDetails' => [
                        'promo_code' => 'PROMO10%Off',
                        'discount' => MoneyFormatter::format($reports[0]['discount']),
                        'total_amount' => MoneyFormatter::format($reports[0]['total_amount']),
                    ]  
                ],
                [
                    'book_id' => $reports[1]['book_id'],
                    'clientName' => $reports[1]['name'],
                    'serviceName' => $reports[1]['service_name'],
                    'amount' => MoneyFormatter::format($reports[1]['amount']),
                    'payment_method' => $reports[1]['payment_method'],
                    'payment_status' => $reports[1]['payment_status'],
                    'bookStatus' => $reports[1]['status'],
                    'note' => $reports[1]['note'],
                    'booked_at' => Carbon::parse($reports[1]['created_at'])->format('Y-m-d\TH:i:s.u\Z'),
                    'hasPromo' => 1,
                    'promoDetails' => [
                        'promo_code' => 'PROMO10%Off',
                        'discount' => MoneyFormatter::format($reports[1]['discount']),
                        'total_amount' => MoneyFormatter::format($reports[1]['total_amount']),
                    ]  
                ],
                [
                    'book_id' => $reports[2]['book_id'],
                    'clientName' => $reports[2]['name'],
                    'serviceName' => $reports[2]['service_name'],
                    'amount' => MoneyFormatter::format($reports[2]['amount']),
                    'payment_method' => $reports[2]['payment_method'],
                    'payment_status' => $reports[2]['payment_status'],
                    'bookStatus' => $reports[2]['status'],
                    'note' => $reports[2]['note'],
                    'booked_at' => Carbon::parse($reports[2]['created_at'])->format('Y-m-d\TH:i:s.u\Z'),
                    'hasPromo' => 1,
                    'promoDetails' => [
                        'promo_code' => 'PROMO10%Off',
                        'discount' => MoneyFormatter::format($reports[2]['discount']),
                        'total_amount' => MoneyFormatter::format($reports[2]['total_amount']),
                    ]  
                ],
            ]
        ]);
    }

    public function test_reports_validRequestNoDataFound_expected()
    {
        $reports = $this->createReport(1, 0, '');

        $request = [
            'from_date' => Carbon::parse($reports[0]['created_at'])->addDay()->format('Y-m-d H:i:s'),
            'to_date' => Carbon::parse($reports[0]['created_at'])->addDay()->format('Y-m-d H:i:s'),
            'bookId' => 'sample_book_id',
            'serviceName' => 'sample_service_name',
        ];

        $response = $this->call('POST', '/api/report', $request);
        $response->assertJson([
            'response_code' => '200',
            'from_date' => $request['from_date'],
            'to_date' => $request['to_date'],
            'total_reports' => 0,
            'data' => []
        ]);
    }

    #[DataProvider('reportRequestParams')]
    public function test_reports_incompleteRequest_expected($requestParams)
    {
        $request = [
            'from_date' => '2024-01-31 23:59:59',
            'to_date' => '2024-02-01 00:59:59',
            'bookId' => 'sample_book_id',
            'serviceName' => 'sample_service_name',
        ];

        unset($request[$requestParams]);

        $response = $this->call('POST', '/api/report', $request);
        $response->assertJson([
            'response_code' => '400',
        ]);

    }

    public static function reportRequestParams()
    {
        return [
            ['from_date'],
            ['to_date'],
        ];
    }

    public function test_reports_invalidDateFormat_expected()
    {
        $request = [
            'from_date' => 'invalid_date_format',
            'to_date' => '2024-02-01 00:59:59',
            'bookId' => 'sample_book_id',
            'serviceName' => 'sample_service_name',
        ];

        $response = $this->call('POST', '/api/report', $request);
        $response->assertJson([
            'response_code' => '401',
            'message' => 'Invalid date format.'
        ]);
    }
}
