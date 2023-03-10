<?php

namespace Tests\Traits;

use App\Models\User;
use Illuminate\Testing\TestResponse;

trait TestHelpers
{
    /**
     * Create a fake user with defined attributes
     *
     * @param array $attributes
     * @return null
     */
    public function createUser(array $attributes = []) {
        User::factory(1)->create($attributes);
    }


    /**
     * @param $email
     * @return object|null
     */
    public function findUserByEmail($email) {
        $user = User::where('email', $email)->first();
        return $user ?? null;
    }

    /**
     * Generate a random email that does not exist for tests
     *
     * @return string
     */
    public function getRandomEmail() {
        while (true) {
            $email = '';
            for ($i = 0; $i < 20; $i++) {
                $email .= rand(0, 10);
            }
            $email .= '@gmail.com';
            if ($this->findUserByEmail($email) === null) {
                return $email;
            }
        }

    }

    /**
     * Generate a random password
     *
     * @return string
     */
    public function getRandomPassword() {
        $password = '';
        for ($i = 0; $i < 20; $i++) {
            $password .= rand(0, 10);
        }
        return $password;
    }


    /**
     * Create a fake random user,
     * return the generated random email.
     *
     * @return string email
     */
    public function createRandomUser() {
        $email = $this->getRandomEmail();
        $user = $this->findUserByEmail($email);
        if ($user === null) {
            $this->createUser(['email' => $email]);
            return $email;
        }
    }


    /**
     * Remove the test user after tests
     *
     * @param $email
     * @return false|int
     */
    public function delete($email) {
        $user = User::where('email', $email)->first();
        return $user ? User::destroy($user->id) : false;
    }


    /**
     * Return the email of the sign-in user
     * @return string email
     */
    public function signIn() {
        $user = User::factory(1)->create()->first();
        $this->actingAs($user);
        return $user->email;
    }


    /**
     * Enter attributes of start date (timezone), end date (timezone),
     * return the result of calculating the day gap.
     *
     * @param array $attribute
     * @return TestResponse
     */
    public function daysEntry(array $attribute) {
        $email = $this->signIn();
        $response = $this->json('POST', '/api/days', $attribute);
        $this->delete($email);
        return $response;
    }


    /**
     * Enter attributes of start date (timezone), end date (timezone),
     * return the result of calculating the week gap.
     *
     * @param array $attribute
     * @return TestResponse
     */
    public function weeksEntry(array $attribute) {
        $email = $this->signIn();
        $response = $this->json('POST', '/api/weeks', $attribute);
        $this->delete($email);
        return $response;
    }
}
