<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;


class ApiService
{  
    private $clientapi;
    
    public function __construct(HttpClientInterface $clientapi)
    {
       $this->clientapi = $clientapi; 
    }
   
    public function postLenbox($nomEntreprise,$email,$telMobile,$uniqid): array
    {
        
        $response = $this->clientapi->request(
            'POST',
            'https://app.finnocar.com/version-test/api/1.1/wf/getagency',[
            'headers' => [
                    'content-type' => 'application/json'    
            ],
            'json' => [
                'authkey' => '1622543601638x611830994992322700',
                'nomEntreprise'=> $nomEntreprise,
                'email'=> $email,
                'telMobile'=> $telMobile,
                'uniqueId'=> $uniqid
             ],
        ]);
     return $response->toArray();
    }  
}