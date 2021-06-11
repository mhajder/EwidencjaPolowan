<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteTest extends TestCase
{
    /**
     * Test GET slash route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_slash_route_check_if_status_302()
    {
        $response = $this->get('/');
        $response->assertStatus(302);
    }

    /**
     * Test GET home route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_home_route_check_if_status_302()
    {
        $response = $this->get('/home');
        $response->assertStatus(302);
    }

    /**
     * Test GET login route. Correct if response status is 200.
     *
     * @return void
     */
    public function test_get_login_route_check_if_status_200()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test GET register route. Correct if response status is 404.
     *
     * @return void
     */
    public function test_get_register_route_check_if_status_404()
    {
        $response = $this->get('/register');
        $response->assertStatus(404);
    }

    /**
     * Test GET profile route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_profile_route_check_if_status_302()
    {
        $response = $this->get('/profile');
        $response->assertStatus(302);
    }

    /**
     * Test GET district.change route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_district_change_route_check_if_status_302()
    {
        $response = $this->get('/district/change/1');
        $response->assertStatus(302);
    }

    /**
     * Test GET authorization.index route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_authorization_index_route_check_if_status_302()
    {
        $response = $this->get('/authorizations');
        $response->assertStatus(302);
    }

    /**
     * Test GET authorization.create route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_authorization_create_route_check_if_status_302()
    {
        $response = $this->get('/authorization/create');
        $response->assertStatus(302);
    }

    /**
     * Test GET hunting.index route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_hunting_index_route_check_if_status_302()
    {
        $response = $this->get('/hunting-book');
        $response->assertStatus(302);
    }

    /**
     * Test GET hunting.create route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_hunting_create_route_check_if_status_302()
    {
        $response = $this->get('/hunting/create');
        $response->assertStatus(302);
    }

    /**
     * Test GET hunting.edit route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_hunting_edit_route_check_if_status_302()
    {
        $response = $this->get('/hunting/edit/1');
        $response->assertStatus(302);
    }

    /**
     * Test GET user.index route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_user_index_route_check_if_status_302()
    {
        $response = $this->get('/admin/users');
        $response->assertStatus(302);
    }

    /**
     * Test GET user.create route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_user_create_route_check_if_status_302()
    {
        $response = $this->get('/admin/user/create');
        $response->assertStatus(302);
    }

    /**
     * Test GET user.edit route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_user_edit_route_check_if_status_302()
    {
        $response = $this->get('/admin/user/edit/1');
        $response->assertStatus(302);
    }

    /**
     * Test GET district.index route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_district_index_route_check_if_status_302()
    {
        $response = $this->get('/admin/districts');
        $response->assertStatus(302);
    }

    /**
     * Test GET district.create route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_district_create_route_check_if_status_302()
    {
        $response = $this->get('/admin/district/create');
        $response->assertStatus(302);
    }

    /**
     * Test GET district.edit route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_district_edit_route_check_if_status_302()
    {
        $response = $this->get('/admin/district/edit/1');
        $response->assertStatus(302);
    }

    /**
     * Test GET hunting-ground.index route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_hunting_ground_index_route_check_if_status_302()
    {
        $response = $this->get('/admin/district/1/hunting-grounds');
        $response->assertStatus(302);
    }

    /**
     * Test GET hunting-ground.create route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_hunting_ground_create_route_check_if_status_302()
    {
        $response = $this->get('/admin/district/1/hunting-ground/create');
        $response->assertStatus(302);
    }

    /**
     * Test GET hunting-ground.edit route. Correct if response status is 302.
     *
     * @return void
     */
    public function test_get_hunting_ground_edit_route_check_if_status_302()
    {
        $response = $this->get('/admin/district/1/hunting-ground/edit/1');
        $response->assertStatus(302);
    }
}
