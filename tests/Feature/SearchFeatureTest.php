<?php

namespace Tests\Feature;

use Tests\TestCase;

class SearchFeatureTest extends TestCase
{

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_the_application_returns_a_400_response_for_searching_without_request_data(): void
    {
        $response = $this->get('/api/book/search');
        $response->assertStatus(400);
    }
    public function test_the_application_returns_a_200_response_for_search(): void
    {
        $response = $this->get('/api/book/search?q=book');
        $response->assertStatus(200);
    }
    public function test_the_application_returns_a_200_response_for_search_with_page(): void
    {
        $response = $this->get('/api/book/search?q=book&page=2');
        $response->assertStatus(200);
    }
    public function test_the_application_returns_a_400_response_for_search_with_page_not_a_number(): void
    {
        $response = $this->get('/api/book/search?q=book&page=abc');
        $response->assertStatus(400);
    }
    public function test_the_application_returns_a_data_when_search_mastering(): void
    {
        $response = $this->get('/api/book/search?q=mastering');
        $response->assertJsonFragment(['title' => 'Mastering Something']);
    }
    public function test_request_api_return_429_when_request_limit_exceeded(): void
    {
        for ($i = 0; $i <= 21; $i++) {
            $response = $this->get('/api/book/search?q=book');
        }
        $response->assertStatus(429);
    }

}
