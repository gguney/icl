<?php

namespace Tests\Feature;

class MainControllerTest extends BaseTestCase
{
    /** @test */
    public function is_should_return_main_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
