<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Rules\E164Phone;
use Symfony\Component\HttpFoundation\JsonResponse;

class ContactController extends Controller
{
    
    /**
     * GET: http://localhost:8000/api/contacts/search
     * GET: http://localhost:8000/api/contacts/search?name=*
     * GET: http://localhost:8000/api/contacts/search?phone=*
     * GET: http://localhost:8000/api/contacts/search?email_domain=@example.com
     */
    public function search(Request $request) {
        
        $contactService = new \App\Services\ContactService();

        $name = "";
        $phone = "";
        $emailDoman = "";
        
        if ($request->filled('name')) {
            $name = $request->name;
        }

        if ($request->filled('phone')) {
            $phone = $request->phone;
        }

        if ($request->filled('email_domain')) {
            $emailDoman = $request->email_domain;
        }
        
        $contacts = $contactService->search($name, $phone, $emailDoman);
        
        return response()->json([
            'message' => 'Contacts retrieved successfully',
            'data' => $contacts->toArray()
        ], 200);
    }

    /**
     * GET: http://localhost:8000/api/contacts/{id}
     */
    public function show(int $id) {
        
        if (!$id) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        
        $contactService = new \App\Services\ContactService();
        
        $contact = $contactService->findOne($id);
        if (!$contact instanceof Contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        return response()->json([
            'message' => 'Contacts retrieved successfully',
            'data' => $contact
        ], 200);   
    }

    public function upsert(Request $request) {
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', new E164Phone()],
        ]);
        
        $contactService = new \App\Services\ContactService();
        $contact = $contactService->findOneByEmail( $data['email']);
        
        if ($contact instanceof Contact) {
            $contactService->update($contact, $data);

            return response()->json([
                'message' => 'Contact updated',
                'contact' => $contact,
            ], 200);
        }
        
        // create new
        $contact = $contactService->insert($data);

        return response()->json([
            'message' => 'Contact created',
            'contact' => $contact,
        ], 201);
    }

    /**
     * DELETE: http://localhost:8000/api/contacts/{id}
     */
    public function destroy(int $id): JsonResponse {
        
        if (!$id) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        
        $contactService = new \App\Services\ContactService();
        $contact = $contactService->findOne($id);
        if (!$contact instanceof Contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }

        $contactService->destroy($id);

        return response()->json(['message' => 'Contact deleted successfully'], 200);
    }
    
    /**
     * Mock Call function
     * GET: http://localhost:8000/api/contacts/{id}
     */
    public function call(Contact $contact) {
        // $this->access->denyAccessUnlessGranted('call', ContactResource::class);
        return response()->json([
            'status' => 'success',
            'message' => "Mock call placed to {$contact->phone}"
        ]);
    }
}
