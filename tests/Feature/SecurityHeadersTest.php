<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_public_page_includes_baseline_security_headers(): void
    {
        $response = $this->get('/');

        $response
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), geolocation=(), microphone=()')
            ->assertHeaderContains('Content-Security-Policy', "base-uri 'self'")
            ->assertHeaderContains('Content-Security-Policy', "frame-ancestors 'self'");
    }
}
