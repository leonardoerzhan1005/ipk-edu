<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the application can set locale from route parameter
     */
    public function test_can_set_locale_from_route(): void
    {
        $response = $this->get('/ru/');
        
        $response->assertStatus(200);
        $this->assertEquals('ru', app()->getLocale());
    }

    /**
     * Test that the application can set locale from segment
     */
    public function test_can_set_locale_from_segment(): void
    {
        $response = $this->get('/kk/');
        
        $response->assertStatus(200);
        $this->assertEquals('kk', app()->getLocale());
    }

    /**
     * Test that the application falls back to default locale for invalid locale
     */
    public function test_falls_back_to_default_for_invalid_locale(): void
    {
        $response = $this->get('/invalid/');
        
        $response->assertStatus(200);
        $this->assertEquals('ru', app()->getLocale()); // default locale
    }

    /**
     * Test that the application redirects root to default locale
     */
    public function test_redirects_root_to_default_locale(): void
    {
        $response = $this->get('/');
        
        $response->assertRedirect('/ru/');
    }

    /**
     * Test that locale is stored in session
     */
    public function test_locale_is_stored_in_session(): void
    {
        $response = $this->get('/en/');
        
        $response->assertStatus(200);
        $this->assertEquals('en', session('locale'));
    }

    /**
     * Test that Carbon locale is set correctly
     */
    public function test_carbon_locale_is_set(): void
    {
        $this->get('/ru/');
        
        $this->assertEquals('ru', \Carbon\Carbon::getLocale());
    }

    /**
     * Test that supported locales are configured correctly
     */
    public function test_supported_locales_configuration(): void
    {
        $supported = config('locales.supported');
        
        $this->assertIsArray($supported);
        $this->assertContains('en', $supported);
        $this->assertContains('ru', $supported);
        $this->assertContains('kk', $supported);
    }

    /**
     * Test that default locale is configured correctly
     */
    public function test_default_locale_configuration(): void
    {
        $default = config('locales.default');
        
        $this->assertEquals('ru', $default);
    }

    /**
     * Test that fallback locale is configured correctly
     */
    public function test_fallback_locale_configuration(): void
    {
        $fallback = config('locales.fallback');
        
        $this->assertEquals('en', $fallback);
    }

    /**
     * Test localizedRoute helper function with string parameter
     */
    public function test_localized_route_with_string_parameter(): void
    {
        $this->get('/en/');
        
        $url = localizedRoute('courses.show', 'test-slug');
        
        $this->assertStringContainsString('/en/courses/test-slug', $url);
    }

    /**
     * Test localizedRoute helper function with array parameter
     */
    public function test_localized_route_with_array_parameter(): void
    {
        $this->get('/ru/');
        
        $url = localizedRoute('courses.show', ['slug' => 'test-slug', 'category' => 'test']);
        
        $this->assertStringContainsString('/ru/courses/test-slug', $url);
        $this->assertStringContainsString('category=test', $url);
    }
}
