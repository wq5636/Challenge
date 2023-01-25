<?php

namespace Tests\Unit;
use Tests\Traits\TestHelpers;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Tests\TestCase;
class AuthTest extends TestCase
{
    use TestHelpers;

    public function testRegister() {
        $email = $this->getRandomEmail();
        $data = [
            'name' => 'John ',
            'email' => $email,
            'password' => 'test',
            'password_confirmation' => 'test',
        ];

        $controller = new AuthController();
        $response = $controller->register(new Request($data));

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('name', $data);

        $this->delete($email);
    }


    public function testRegisterWithExistingEmail() {
        $existingEmail = $this->createRandomUser();

        // the email will exist as a new user with this email has just been created
        $data = [
            'name' => 'John Doe',
            'email' => $existingEmail,
            'password' => 'test',
            'password_confirmation' => 'test',
        ];

        $controller = new AuthController();

        try {
            $controller->register(new Request($data));
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertEquals($e->validator->errors()->first('email'),
                'The email has already been taken.');
            $this->delete($existingEmail);
        }
    }


    public function testLogin() {
        $email = $this->getRandomEmail();
        $password = $this->getRandomPassword();

        $this->createUser([
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $controller = new AuthController();
        $response = $controller->login(new Request([
            'email' => $email,
            'password' => $password,
        ]));

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('name', $data);

        $this->delete($email);
    }


    public function testLogout() {
        $email = $this->signIn();
        $controller = new AuthController();
        $response = $controller->logout();
        $this->assertEquals(200, $response->getStatusCode());

        $this->delete($email);
    }
}
