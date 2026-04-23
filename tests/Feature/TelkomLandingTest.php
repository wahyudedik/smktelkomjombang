<?php

namespace Tests\Feature;

use Tests\TestCase;

class TelkomLandingTest extends TestCase
{
    /**
     * Test that the telkom landing page loads successfully
     */
    public function test_telkom_landing_page_loads()
    {
        $response = $this->get('/telkom');
        
        $response->assertStatus(200);
        $response->assertViewIs('telkom');
    }

    /**
     * Test that the telkom landing page has required data
     */
    public function test_telkom_landing_page_has_required_data()
    {
        $response = $this->get('/telkom');
        
        $response->assertStatus(200);
        $response->assertViewHas('siswaCount');
        $response->assertViewHas('kelulusanPercentage');
        $response->assertViewHas('testimonials');
        $response->assertViewHas('blogs');
        $response->assertViewHas('partners');
        $response->assertViewHas('events');
    }

    /**
     * Test that the telkom route is named correctly
     */
    public function test_telkom_route_name()
    {
        $response = $this->get(route('landing.telkom'));
        
        $response->assertStatus(200);
    }
}
