<?php

namespace Tests\Feature\QuestionPictureTests;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_authenticated_user_cannot_store_a_user()
    {
        $this->signIn(['admin' => false]);

        $data = factory(User::class)->raw();

        $this->post(route('admin.user.store'), $data)->assertForbidden();
    }

    /** @test */
    public function a_superadmin_can_store_a_user()
    {
        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw();

        $this->post(route('admin.user.store'), $data)
            ->assertRedirect(route('admin.user.index'));

        $this->assertDatabaseHas((new User)->getTable(), ['username' => $data['username']]);
    }


    public function requiredFields()
    {
        return [
            ['field' => 'username'],
            ['field' => 'first_name', 'prettyFieldName' => 'first name'],
            ['field' => 'last_name', 'prettyFieldName' => 'last name'],
            ['field' => 'email'],
            ['field' => 'admin'],
            ['field' => 'active'],
        ];
    }

    /**
     * @test
     * @dataProvider requiredFields
     */
    public function field_x_is_required_while_storing_a_user($field, $prettyFieldName = null)
    {
        $error = "The $field field is required.";

        if ($prettyFieldName !== null) {
            $error = "The $prettyFieldName field is required.";
        }

        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw([$field => '']);

        $this->post(route('admin.user.store'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get($field)[0], $error);
    }

    public function mustBeAString()
    {
        return [
            ['field' => 'username'],
            ['field' => 'first_name', 'prettyFieldName' => 'first name'],
            ['field' => 'last_name', 'prettyFieldName' => 'last name'],
            ['field' => 'street_name' , 'prettyFieldName' => 'street name'],
            ['field' => 'house_number' , 'prettyFieldName' => 'house number'],
            ['field' => 'zip_code' , 'prettyFieldName' => 'zip code'],
            ['field' => 'city'],
            ['field' => 'province'],
            ['field' => 'phone_number' , 'prettyFieldName' => 'phone number'],
            ['field' => 'mobile_number' , 'prettyFieldName' => 'mobile number'],
            ['field' => 'comment'],
        ];
    }

    /**
     * @test
     * @dataProvider mustBeAString
     */
    public function field_x_must_be_a_type_of_string_while_storing_a_user($field, $prettyFieldName = null)
    {
        $error = "The $field must be a string.";

        if ($prettyFieldName !== null) {
            $error = "The $prettyFieldName must be a string.";
        }

        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw([$field => true]);

        $this->post(route('admin.user.store'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get($field)[0], $error);
    }

    public function mustBeABoolean()
    {
        return [
            ['field' => 'admin'],
            ['field' => 'active'],
        ];
    }

    /**
     * @test
     * @dataProvider mustBeABoolean
     */
    public function field_x_must_be_a_type_of_boolean_while_storing_a_user($field, $prettyFieldName = null)
    {
        $error = "The $field field must be true or false.";

        if ($prettyFieldName !== null) {
            $error = "The $prettyFieldName field must be true or false.";
        }

        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw([$field => 'not a boolean']);

        $this->post(route('admin.user.store'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get($field)[0], $error);
    }

    public function canBeNull()
    {
        return [
            ['field' => 'date_of_birth' , 'prettyFieldName' => 'date of birth'],
            ['field' => 'street_name' , 'prettyFieldName' => 'street name'],
            ['field' => 'house_number' , 'prettyFieldName' => 'house number'],
            ['field' => 'zip_code' , 'prettyFieldName' => 'zip code'],
            ['field' => 'city'],
            ['field' => 'province'],
            ['field' => 'phone_number' , 'prettyFieldName' => 'phone number'],
            ['field' => 'mobile_number' , 'prettyFieldName' => 'mobile number'],
            ['field' => 'comment'],
        ];
    }

    /**
     * @test
     * @dataProvider canBeNull
     */
    public function field_x_can_be_null_while_storing_a_user($field, $prettyFieldName = null)
    {
        $error = "The $field must be a string.";

        if ($prettyFieldName !== null) {
            $error = "The $prettyFieldName must be a string.";
        }

        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw([$field => null]);

        $this->post(route('admin.user.store'), $data);

        $this->assertDatabaseHas((new User)->getTable(), ['username' => $data['username']]);
    }

    /**
     * @test
     */
    public function field_email_must_be_a_type_of_email_while_storing_a_user()
    {
        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw(['email' => 'not a email']);

        $this->post(route('admin.user.store'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('email')[0], "The email must be a valid email address.");
    }

    /**
     * @test
     */
    public function field_username_must_be_unique_while_storing_a_user()
    {
        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw(['username' => 'xX360SniperNoScopeXx']);

        $this->post(route('admin.user.store'), $data)->isSuccessful();

        $this->post(route('admin.user.store'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('username')[0], "The username has already been taken.");
    }

    /**
     * @test
     */
    public function field_date_of_birth_must_be_a_type_of_date_while_storing_a_user()
    {
        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw(['date_of_birth' => 'not a date value']);

        $this->post(route('admin.user.store'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('date_of_birth')[0], "The date of birth is not a valid date.");
    }

    /**
     * @test
     */
    public function field_comment_max_value_is_200_while_storing_a_user()
    {
        $this->signIn(['admin' => true]);

        $data = factory(User::class)->raw(['comment' => \Str::random(201)]);

        $this->post(route('admin.user.store'), $data)->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('comment')[0], "The comment may not be greater than 200 characters.");
    }
}
