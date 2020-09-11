<?php

namespace Tests\Feature\SetupTests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class SetupTest extends TestCase
{
    use RefreshDatabase;

    private $namedRoute = "setup.go";

    private function userData($overrides = [], $password = 'SomeStrongPassword123')
    {
        $data = factory(User::class)->raw($overrides);

        $data = Arr::only($data, ['username', 'first_name', 'last_name', 'email']);

        $data['password'] = $password;
        $data['confirm_password'] = $password;

        return $data;
    }

    /** @test */
    public function cannot_show_the_setup_methods_when_a_user_already_exists_in_the_database()
    {
        $this->createUser();

        $this->get(route('setup'))->assertStatus(500);
    }

    /** @test */
    public function can_show_the_setup_methods_when_no_users_exist_in_the_database()
    {
        $this->get(route('setup'))->assertStatus(200);
    }

    /** @test */
    public function the_method_renders_the_given_component_for_inertia()
    {
        $response = $this->get(route($this->namedRoute));

        $response->assertInertia('Setup/Show');
    }

    /** @test */
    public function a_user_gets_created_when_persisting_the_setup()
    {
        $data = $this->userData();

        $this->post(route($this->namedRoute), $data)
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas((new User)->getTable(), Arr::except($data, ['password', 'confirm_password']));
    }

    /** @test */
    public function the_created_user_is_a_admin_persisting_the_setup()
    {
        $data = $this->userData();

        $this->post(route($this->namedRoute), $data);

        $this->assertDatabaseHas((new User)->getTable(), ['username' => $data['username'], 'admin' => 1]);
    }

    /** @test */
    public function the_created_user_is_active_persisting_the_setup()
    {
        $data = $this->userData();

        $this->post(route($this->namedRoute), $data);

        $this->assertDatabaseHas((new User)->getTable(), ['username' => $data['username'], 'active' => 1]);
    }
}
