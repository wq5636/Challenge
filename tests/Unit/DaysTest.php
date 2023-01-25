<?php

namespace Tests\Unit;
use Tests\Traits\TestHelpers;
use Tests\TestCase;
class DaysTest extends TestCase
{
    use TestHelpers;

    /**
     * Basic test for days() without timezone (Default: UTC)
     * @return void
     */
    public function testDaysWithoutTimezoneOne() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-15',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(14, $data['days']);
    }


    /**
     * Adding specific time for days() without timezone (Default: UTC)
     * @return void
     */
    public function testDaysWithoutTimezoneTwo() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01 11:00',
            'end_date' => '2022-01-15 12:00',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(14, $data['days']);
    }


    /**
     * Use different time format for days() without timezone (Default: UTC)
     * @return void
     */
    public function testDaysWithoutTimezoneThree() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01 1:00pm',
            'end_date' => '2022-01-15 12:00',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(13, $data['days']);
    }


    /**
     * Use different time format for days() without timezone (Default: UTC)
     * @return void
     */
    public function testDaysWithoutTimezoneFour() {
        $response = $this->daysEntry([
            'start_date' => '01-01-2023 1:00pm',
            'end_date' => '15-01-2023 9:00am',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(13, $data['days']);
    }


    /**
     * With timezone (UTC - Sydney) - days()
     * @return void
     */
    public function testDaysWithTimezoneOne() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-16',
            'end_timezone' => 'Australia/Sydney',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(14, $data['days']);
    }


    /**
     * With timezone (UTC - Sydney) - days()
     * @return void
     */
    public function testDaysWithTimezoneTwo() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-15 10:59',
            'end_timezone' => 'Australia/Sydney',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(13, $data['days']);
    }


    /**
     * With timezone (Sydney - UTC) - days()
     * @return void
     */
    public function testDaysWithTimezoneThree() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01 08:00',
            'end_date' => '2022-01-15',
            'start_timezone' => 'Asia/Shanghai',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(14, $data['days']);
    }


    /**
     * With timezone (Sydney - UTC) - days()
     * @return void
     */
    public function testDaysWithTimezoneFour() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01 08:01',
            'end_date' => '2022-01-15 00:00',
            'start_timezone' => 'Asia/Shanghai',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(13, $data['days']);
    }


    /**
     * With timezone (Shanghai - Sydney) - days()
     * @return void
     */
    public function testDaysWithTimezoneFive() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01',
            'end_date' => '2022-01-15 03:00',
            'start_timezone' => 'Asia/Shanghai',
            'end_timezone' => 'Australia/Sydney',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(14, $data['days']);
    }


    /**
     * With timezone (Shanghai - Sydney) - days()
     * @return void
     */
    public function testDaysWithTimezoneSix() {
        $response = $this->daysEntry([
            'start_date' => '2022-01-01 00:01',
            'end_date' => '2022-01-15 03:00',
            'start_timezone' => 'Asia/Shanghai',
            'end_timezone' => 'Australia/Sydney',
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(13, $data['days']);
    }
}
