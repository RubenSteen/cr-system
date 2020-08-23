<?php

namespace Tests\Feature\QuestionPictureTests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_authenticated_user_cannot_view_the_index_page()
    {
        $this->signIn(['admin' => false]);

        $this->get(route('admin.user.index'))->assertForbidden();
    }

    /** @test */
    public function a_superadmin_can_view_the_index_page()
    {
        $this->signIn(['admin' => true]);

        $this->get(route('admin.user.index'))->assertStatus(200);
    }
}
