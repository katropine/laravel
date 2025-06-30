<?php
namespace App\Services;

use App\Models\Contact;
/**
 * Description of ContactService
 *
 */
class ContactService {
    
    public function findOneByEmail(string $email) {
        return Contact::where('email', $email)->first();
    }
   
    
    public function insert(array $data = []) {
        return Contact::create($data);
    }
    
    public function update(Contact $contact, array $data = []) {
        return $contact->update($data);
    }
    
    public function destroy(int $id) {
        $contact = Contact::find($id);
        
        try {
            $contact->delete();
            
            return true;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    public function findOne(int $id): Contact {
        return Contact::find($id);
    } 
    
    public function search(string $name = "", string $phone = "", string $emailDoman = "") {
        $query = Contact::query();

        if (!empty($name)) {
            $query->where('name', 'like', "%{$name}%");
        }

        if (!empty($phone)) {
            $query->where('phone', $phone);
        }

        if (!empty($emailDoman)) {
            $query->where('email', 'like', "%@{$emailDoman}");
        }

        return $query->get();
    }
}
