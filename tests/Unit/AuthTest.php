<?php

namespace Tests\Unit;
use Tests\Traits\TestHelpers;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

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

        $response = $this->json('POST', '/api/register', $data);

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

        $response = $this->json('POST', '/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('name', $data);

        $this->delete($email);
    }


    public function testLogout() {
        $email = $this->signIn();
        $token = auth()->user()->tokens();
        $data = [
            'token' => $token,
        ];
        $response = $this->json('POST', '/api/logout', $data);
        $this->assertEquals(200, $response->getStatusCode());
        $this->delete($email);
    }


    /** @test */
    public function testLogoutDeleteTokens()
    {
        $email = $this->signIn();
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->createToken('test')->plainTextToken,
        ];
        $this->assertCount(1, auth()->user()->tokens);
        $response = $this->json('POST', '/api/logout', $headers);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Successfully logged out']);
        $this->assertCount(0, auth()->user()->fresh()->tokens);
        $this->delete($email);
    }
}
