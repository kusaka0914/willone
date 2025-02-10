<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class JobNoteApiService
{
    private $jobnoteUrl;

    public function __construct()
    {
        $this->jobnoteUrl = env('JOBNOTE_URL', 'https://job-note.jp');
    }

    /**
     * jobnote EntryAPIコール
     * @param $woaCustomer Collection 会員情報
     */
    public function executeEntryApi(Collection $woaCustomer): ?\Psr\Http\Message\ResponseInterface
    {
        $body = [
            'woa_id' => $woaCustomer['woa_id'],
            'license' => $woaCustomer['license'],
            'graduation_year' => $woaCustomer['graduation_year'],
            'req_emp_type' => $woaCustomer['req_emp_type'],
            'req_date' => $woaCustomer['req_date'],
            'addr1' => $woaCustomer['addr1'],
            'addr2' => $woaCustomer['addr2'],
            'name_kan' => $woaCustomer['name_kan'],
            'name_cana' => $woaCustomer['name_cana'],
            'birth_year' => $woaCustomer['birth_year'],
            'retirement_intention' => $woaCustomer['retirement_intention'],
            'mail' => $woaCustomer['mail'],
            'mob_phone' => $woaCustomer['mob_phone'],
        ];

        $client = new Client();

        try {
            return $client->request(
                'POST',
                $this->jobnoteUrl . '/api/from-woa',
                [
                    'form_params' => $body
                ]
            );
        } catch (GuzzleException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}
