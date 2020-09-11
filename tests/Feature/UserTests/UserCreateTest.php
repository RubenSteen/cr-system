<?php

namespace Tests\Feature\UserTests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCreateTest extends TestCase
{
    use RefreshDatabase;

    private $namedRoute = 'admin.user.create';

    /** @test */
    public function a_authenticated_user_cannot_view_the_create_page()
    {
        $this->signIn(['admin' => false]);

        $this->get(route($this->namedRoute))->assertForbidden();
    }

    /** @test */
    public function a_superadmin_can_view_the_create_page()
    {
        $this->signIn(['admin' => true]);

        $this->get(route($this->namedRoute))->assertStatus(200);
    }

    /** @test */
    public function the_method_renders_the_given_component_for_inertia()
    {
        $this->signIn(['admin' => true]);

        $response = $this->get(route($this->namedRoute));

        $response->assertInertia('User/Admin/Create');
    }
}
