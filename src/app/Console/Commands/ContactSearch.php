<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contact;

class ContactSearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contact:search {--name=} {--phone=} {--email_domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search contacts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contactService = new \App\Services\ContactService();
        
        $nameParam = "";
        $phoneParam = "";
        $emailDomanParam = "";
        
        if ($name = $this->option('name')) {
            $nameParam =  $name;
        }

        if ($phone = $this->option('phone')) {
            $phoneParam = $phone;
        }

        if ($domain = $this->option('email_domain')) {
            $emailDomanParam = $domain;
        }

        $results = $contactService->search($nameParam, $phoneParam, $emailDomanParam);
        
        $this->info($results->isEmpty() ? 'No results found.' : $results->toJson(JSON_PRETTY_PRINT));
    }
}


/// the rest of the comands can be done by invouking ContactService() just like in API controller