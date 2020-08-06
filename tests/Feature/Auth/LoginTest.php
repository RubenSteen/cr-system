<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_guest_can_view_a_login_form()
    {
        $this->assertGuest();

        $this->get(route('login'))->assertStatus(200);
    }

    /** @test */
    public function test_user_cannot_view_a_login_form_when_authenticated()
    {
        $this->signIn();

        $this->get(route('login'))->assertRedirect('/');
    }

    /** @test */
    public function a_guest_can_authenticate_with_the_right_credentials()
    {
        $user = factory(User::class)->create(); // Create the user

        $this->assertGuest();

        $credentials = [
            'username' => $user->username,
            'password' => 'password',
        ];

        $this->post(route('login.go'), $credentials)->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_guest_cannot_authenticate_with_the_wrong_username()
    {
        factory(User::class)->create([
            'password' => Hash::make('supersecretpassword'),
        ]); // Create the user

        $this->assertGuest();

        $credentials = [
            'username' => 'somewrongusername',
            'password' => 'supersecretpassword',
        ];

        $this->post(route('login.go'), $credentials)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('username')[0], 'These credentials do not match our records.');

        $this->assertGuest($guard = null);
    }

    /** @test */
    public function a_guest_cannot_authenticate_with_the_wrong_password()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('supersecretpassword'),
        ]); // Create the user

        $this->assertGuest();

        $credentials = [
            'username' => $user->username,
            'password' => 'wrong password',
        ];

        $this->post(route('login.go'), $credentials)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('username')[0], 'These credentials do not match our records.'); // Actually throws the username error since you dont wanna show that the username is right

        $this->assertGuest($guard = null);
    }

    /** @test */
    public function login_will_throttle_after_5_attemps()
    {
        $user = factory(User::class)->create(); // Create the user

        $credentials = [
            'username' => $user->username,
            'password' => 'somewrongpassword',
        ];

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.go'), $credentials)->isSuccessful();
        }

        $this->post(route('login.go'), $credentials)->assertSessionHasErrors();

        $this->assertStringContainsString('Too many login attempts. Please try again in', session('errors')->get('username')[0]);
    }

    /** @test */
    public function a_guest_cannot_authenticate_with_a_inactive_user()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('supersecretpassword'),
            'active' => false,
        ]); // Create the user

        $credentials = [
            'username' => $user->username,
            'password' => 'supersecretpassword',
        ];

        $this->post(route('login.go'), $credentials)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('username')[0], 'These credentials do not match our records.');

        $this->assertGuest();
    }

    /** @test */
    public function a_inactive_logged_in_user_will_automatically_be_logged_out_due_the_middleware()
    {
        $this->signIn(['active' => false]);

        $this->get(route('home'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('warning', 'Your account has been set to inactive, you have been logged out.');
    }

    /** @test */
    public function the_username_is_required_while_logging_in()
    {
        $user = factory(User::class)->create(); // Create the user

        $credentials = [
            'username' => '',
        ];

        $this->post(route('login.go'), $credentials)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('username')[0], 'The username field is required.');
    }

    /** @test */
    public function the_password_is_required_while_logging_in()
    {
        $user = factory(User::class)->create(); // Create the user

        $credentials = [
            'password' => '',
        ];

        $this->post(route('login.go'), $credentials)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('password')[0], 'The password field is required.');
    }
}
