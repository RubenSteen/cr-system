<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_view_the_change_password_form()
    {
        $this->assertGuest();

        $this->get(route('change-password'))->isForbidden();
    }

    /** @test */
    public function user_can_view_the_change_password_form()
    {
        $this->signIn();

        $this->get(route('change-password'))->assertStatus(200);
    }

    /** @test */
    public function user_can_change_their_password_and_get_redirected_with_a_flash_message()
    {
        $credentials['password'] = 'SuperStr0n9Passw0rd';

        $this->signIn(['password' => Hash::make($credentials['password'])]);

        $credentials['username'] = Auth::user()->username;

        $this->assertCredentials($credentials);

        $data['password'] = 'Chan9edSuperStr0n9Passw0rd!';
        $data['confirm_password'] = $data['password'];

        $this->patch(route('change-password.go'), $data)
            ->assertRedirect(route('home'))
            ->assertSessionHas('success', 'Your password has been changed!');

        $this->assertCredentials([
            'username' => Auth::user()->username,
            'password' => $data['password'],
        ]);
    }

    /** @test */
    public function the_password_is_required_while_changing_password()
    {
        $this->signIn();

        $this->patch(route('change-password.go'), [])->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('password')[0], 'The password field is required.');
    }

    /** @test */
    public function the_password_confirm_is_required_while_changing_password()
    {
        $this->signIn();

        $this->patch(route('change-password.go'), [])->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('confirm_password')[0], 'The confirm password field is required.');
    }

    /** @test */
    public function the_password_confirm_field_must_be_the_same_as_password_field_while_changing_password()
    {
        $this->signIn();

        $data = [
            'password' => 'PasswordWithoutTypo',
            'confirm_password' => 'PasswordWithTypo',
        ];

        $this->patch(route('change-password.go'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('confirm_password')[0], 'The confirm password and password must match.');
    }
    /** @test */
    public function the_password_should_be_a_minimum_of_6_characters_while_changing_password()
    {
        $this->signIn();

        $data = [
            'password' => '12345',
        ];

        $this->patch(route('change-password.go'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('password')[0], 'The password must be at least 6 characters.');
    }

}
