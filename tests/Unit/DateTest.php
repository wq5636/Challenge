<?php

namespace Tests\Unit;
use Tests\Traits\TestHelpers;
use Tests\TestCase;
class DateTest extends TestCase
{
    use TestHelpers;

    public function testDateEntryOne() {
        $email = $this->signIn();
        $data = [
            'start_date' => '2022-22-2222',
            'end_date' => '2022-12-23',
        ];
        $response = $this->json('POST', '/api/days', $data);
        $this->delete($email);

        $response->assertStatus(422)->assertJsonValidationErrors(['start_date']);
    }


    public function testDateEntryTwo() {
        $email = $this->signIn();
        $data = [
            'start_date' => '2022-12-22',
            'end_date' => '22/12/2022', // m/d/y
        ];
        $response = $this->json('POST', '/api/days', $data);
        $this->delete($email);

        $response->assertStatus(422)->assertJsonValidationErrors(['end_date']);
    }

    public function testDateEntryThree() {
        $email = $this->signIn();
        $data = [
            'start_date' => '2022-22-22',
            'end_date' => '22/12/2022', // m/d/y
        ];
        $response = $this->json('POST', '/api/days', $data);
        $this->delete($email);

        $response->assertStatus(422)->assertJsonValidationErrors(['start_date', 'end_date']);
    }


    public function testDateEntryFour() {
        $email = $this->signIn();
        $data = [
            'start_date' => '2022-12-10 24:10',
            'end_date' => '12/12/2022', // m/d/y
            'start_timezone' => 'Australia/Canberra', // Canberra not available
            'end_timezone' => 'UTC',

        ];
        $response = $this->json('POST', '/api/days', $data);
        $this->delete($email);

        $response->assertStatus(422)->assertJsonValidationErrors(['start_timezone']);
    }


    public function testDateEntryFive() {
        $email = $this->signIn();
        $data = [
            'start_date' => '2022-12-10 24:10',
            'end_date' => '12/12/2022', // m/d/y
            'start_timezone' => 'Australia/Sydney',
            'end_timezone' => '', // cannot pass empty timezone

        ];
        $response = $this->json('POST', '/api/days', $data);
        $this->delete($email);

        $response->assertStatus(422)->assertJsonValidationErrors(['end_timezone']);
    }
}
