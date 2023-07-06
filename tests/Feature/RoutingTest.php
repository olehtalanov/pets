<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutingTest extends TestCase
{
    public function test_the_main_page_correctly_redirect_to_dashboard(): void
    {
        $response = $this->get('/');

        $response
            ->assertStatus(302)
            ->assertRedirect('/dashboard');
    }
}
