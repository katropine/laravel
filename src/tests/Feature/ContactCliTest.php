<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactCliTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_contact_by_name()
    {
        Contact::create([
            "name" => "Kriss Kross",
            "email" => "kriss@example.com",
            "phone" => "+61412345678"
        ]);

        $this->artisan("contact:search --name=Kriss")
             ->expectsOutputToContain("Kriss Kross")
             ->assertExitCode(0);
    }

    public function test_search_contact_by_email_domain()
    {
        Contact::create([
            "name" => "Jason",
            "email" => "jas@example.net",
            "phone" => "+61498765432"
        ]);

        $this->artisan("contact:search --email_domain=example.net")
             ->expectsOutputToContain("jas@example.net")
             ->assertExitCode(0);
    }

    public function test_no_results_found()
    {
        $this->artisan("contact:search --name=DoesNotExist")
             ->expectsOutput("No results found.")
             ->assertExitCode(0);
    }
}