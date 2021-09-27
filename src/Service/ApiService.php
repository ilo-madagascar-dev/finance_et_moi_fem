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
   
    public function postLenbox($path,$authkey,$nomEntreprise,$email,$telMobile,$uniqid,$mjour=false): array
    {
        
        $response = $this->clientapi->request(
            'POST',
             $path,[
            'headers' => [
                    'content-type' => 'application/json'    
            ],
            'json' => [
                'authkey' => $authkey,
                'nomEntreprise'=> $nomEntreprise,
                'email'=> $email,
                'telMobile'=> $telMobile,
                'uniqueId'=> $uniqid,
                'update'=>$mjour
             ],
        ]);
     return $response->toArray();
    }
    
    public function postsousCompte($path,$authkey,$vd,$email,$telMobile,$nom,$prenom,$mjour=false): array
    {
        
        $response = $this->clientapi->request(
            'POST',
            $path,[
            'headers' => [
                    'content-type' => 'application/json'    
            ],
            'json' => [
                'authkey' => $authkey,
                'vd'=>$vd,
                'email'=> $email,
                'telMobile'=> $telMobile,
                'nom'=> $nom,
                'prenom'=>$prenom,
                'admin'=> false,
                'update'=>$mjour
             ],
        ]);
        
        return $response->toArray();
    }  
}