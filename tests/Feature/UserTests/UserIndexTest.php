<?php

namespace Tests\Feature\UserTests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    private $namedRoute = 'admin.user.index';

    /** @test */
    public function a_authenticated_user_cannot_view_the_index_page()
    {
        $this->signIn(['admin' => false]);

        $this->get(route($this->namedRoute))->assertForbidden();
    }

    /** @test */
    public function a_superadmin_can_view_the_index_page()
    {
        $this->signIn(['admin' => true]);

        $this->get(route($this->namedRoute))->assertStatus(200);
    }

    /** @test */
    public function the_method_renders_the_given_component_for_inertia()
    {
        $this->signIn(['admin' => true]);

        $response = $this->get(route($this->namedRoute));

        $response->assertInertia('User/Admin/Index');
    }

    /** @test */
    public function assert_if_the_component_only_holds_the_given_prop_data_for_prop_users()
    {
        $this->signIn(['admin' => true]);

        $response = $this->get(route($this->namedRoute));

        $user = Auth::user();

        // Checks the array against the logged in user that automatically gets created in the signIn method
        $response->assertInertiaHas('users.0', [
            'id' => $user->id,
            'fullName' => $user->fullName(),
            'active' => $user->active,
            'admin' => $user->admin,
            'created_at' => readable_date_time_string($user->created_at),
        ]);
    }

    /** @test */
    public function page_is_paginated_and_only_shows_a_x_amount_of_users($amount = 15)
    {
        $this->signIn(['admin' => true]);

        for ($i = 1; $i <= 100; $i++) {
            $this->createUser([
                'admin' => rand(0, 1) == true, // Random true/false value
                'active' => rand(0, 1) == true, // Random true/false value
            ]);
        }

        $response = $this->get(route($this->namedRoute));

        $response->assertInertiaHas('users', function ($value) use ($amount) {
            return count($value) === $amount; // Checks if the prop only holds a x amount of users
        });
    }
}
