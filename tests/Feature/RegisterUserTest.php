<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    /** @var array */
    private $data;

    /**
     * RegisterUserTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        // test variables
        $this->data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@mail.com',
            'password' => '!p4ssW0rd',
        ];
        $this->createApplication();
    }

    /**
     * Missing Parameter(s).
     */
    public function testRegisterMissingParam()
    {
        $data = $this->data;
        unset($data['password']);

        $response = $this->json('POST', '/' . config('app.api_version') . '/register', $data);

        $result = $response->getData();

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertTrue(
            in_array('The password field is required.', $result->error->password)
        );
    }

    /**
     * Testing the Email format validation
     */
    public function testRegisterInvalidEmail()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'notAnEmail@',
            'password' => '!p4ssW0rd',
        ]);

        $result = $response->getData();

        $this->assertEquals(422, $response->getStatusCode());

        $this->assertTrue(in_array('Invalid email address.', $result->error->email));
    }

    public function testRegisterInvalidPasswordFormat()
    {
        $data = $this->data;
        $data['password'] = 'notvalidpassword';
        $response = $this->json('POST', '/' . config('app.api_version') . '/register', $data);
        $response->assertStatus(422);
        $result = json_decode((string)$response->getContent());
        $this->assertTrue(in_array(
            'Password must contain the following: 1 uppercase, 1 special character and a minimum of 8 characters.',
            $result->error->password
        ));
    }

    /**
     * A successful account creation
     */
    public function testRegister()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/register', $this->data);
        $result = $response->getData();

        $user = $result->data;

        foreach ($this->data as $key => $value) {
            if ($key === 'password') {
                continue;
            }

            $this->assertEquals($value, $user->{$key});
        }
    }

    /**
     * Testing the event of registering existing user
     */
    public function testRegisterExistingUser()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/register', $this->data);
        $result = $response->getData();
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertTrue(in_array('The email has already been taken.', $result->error->email));
    }

    /**
     * Will Check if there is a token generated.
     */
    public function testEmailNotification()
    {
        $user = User::where('email', $this->data['email'])->first();
        // check if there is a token generated
        $this->assertDatabaseHas('activation_tokens', ['user_id' => $user->id]);

        // remove test data
        User::find($user->id)->delete();
    }
}
