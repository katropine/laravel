<?php

/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_upsert_create_new_contact()
    {
        $data = [
            "name"  => "New Contact",
            "email" => "new@example.com",
            "phone" => "+61412345678",
        ];

        $response = $this->postJson("/api/contacts/upsert", $data);

        $response->assertStatus(201)
            ->assertJsonFragment(["email" => "new@example.com"]);

        $this->assertDatabaseHas("contacts", ["email" => "new@example.com"]);
    }

    /** @test */
    public function can_upsert_update_existing_contact()
    {
        $contact =  Contact::create([
            "email" => "existing@example.com",
            "name" => "Kriss Kross",
            "phone" => "+61400000000",
        ]);
        
        $this->assertDatabaseHas("contacts", [
            "email" => "existing@example.com"
        ]);

        $data = [
            "email" => "existing@example.com",
            "name" => "Updated Name",
            "phone" => "+61400000001",
        ];

        $response = $this->postJson("/api/contacts/upsert", $data);

        $response->assertStatus(200)
            ->assertJsonFragment(["name" => "Updated Name"]);

        $this->assertDatabaseHas("contacts", [
            "email" => "existing@example.com",
            "name" => "Updated Name",
            "phone" => "+61400000001",
        ]);
    }

    /** @test */
    public function can_delete_contact()
    {
         $contact = Contact::create([
            "name" => "Test User",
            "email" => "deleteme@example.com",
            "phone" => "+61412345678"
        ]);
         
        $this->assertDatabaseHas("contacts", [
            "email" => "deleteme@example.com"
        ]); 
         
        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
                 ->assertJson(["message" => "Contact deleted successfully"]);

        $this->assertDatabaseMissing("contacts", ["id" => $contact->id]);
    }

    /** @test */
    public function can_search_contacts_by_name_phone_email_domain()
    {
        Contact::create([
            "name" => "Kriss Kross",
            "email" => "kriss@kross.com",
            "phone" => "+61411111111",
        ]);
        
        $this->assertDatabaseHas("contacts", [
            "email" => "kriss@kross.com"
        ]);

        Contact::create([
            "name" => "Alexander the Great",
            "email" => "alex@thegreat.co.nz",
            "phone" => "+64422222222",
        ]);
        
        $this->assertDatabaseHas("contacts", [
            "email" => "alex@thegreat.co.nz"
        ]);

        // Search by name
        $response = $this->getJson("/api/contacts/search?name=Kriss");
        $response->assertStatus(200)
                ->assertJsonFragment([
                    "name" => "Kriss Kross"
                ]);

        // Search by phone
        $response = $this->getJson("/api/contacts/search?phone=%2B64422222222");
        $response->assertStatus(200)
                ->assertJsonFragment([
                    "name" => "Alexander the Great"
                ]);

        // Search by email domain
        $response = $this->getJson("/api/contacts/search?email_domain=thegreat.co.nz");
        $response->assertStatus(200)
                 ->assertJsonFragment(["name" => "Alexander the Great"]);
    }

    /** @test */
    public function can_read_single_contact()
    {
        $contact = Contact::create([
            "name" => "Kriss Kross",
            "email" => "kriss@kross.com",
            "phone" => "+61411111111",
        ]);
        
        $this->assertDatabaseHas("contacts", [
            "email" => "kriss@kross.com"
        ]);

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(["email" => $contact->email]);
    }

    /** @test */
    public function can_call_contact_and_get_mocked_response()
    {
        $contact = Contact::create([
            "name" => "Kriss Kross",
            "email" => "kriss@kross.com",
            "phone" => "+61411111111",
        ]);
        
        $this->assertDatabaseHas("contacts", [
            "email" => "kriss@kross.com"
        ]);

        $response = $this->postJson("/api/contacts/{$contact->id}/call");

        $response->assertStatus(200)
                 ->assertJsonStructure(["status", "message"]);
    }
}