<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;

class MainControllerTest extends BaseTestCase
{
    /** @test */
    public function is_should_return_main_page()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);
    }
}
