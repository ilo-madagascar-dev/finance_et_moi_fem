<?php

namespace App\Controller;

use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexApiController extends AbstractController
{
    /**
     * @Route("/index/api", name="index_api")
     */
    public function index(): Response
    {
        $client = new Google_Client();
        $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . "/indexing_api_credentials/femcreditconso-40812-a164a2e7824f.json");
        $client->addScope('https://www.googleapis.com/auth/indexing');
        
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

        $content = '{
            "url": "https://femcreditconso.fr",
            "type": "URL_UPDATED"
          }';
          
        $response = $httpClient->request('POST', $endpoint, [ 'body' => $content ]);

        $status_code = $response->getStatusCode();
        
        return $this->render('index_api/index.html.twig', [
            'controller_name' => 'IndexApiController',
            'status_code' => $status_code,
            'response' => $response
        ]);
    }

    /**
     * @Route("/indexation/get-infos", name="index_api_get_infos")
     */
    public function getIndexationInfos()
    {
        $client = new Google_Client();
        $client->setAuthConfig($_SERVER['DOCUMENT_ROOT'] . "/indexing_api_credentials/femcreditconso-40812-a164a2e7824f.json");
        $client->addScope('https://www.googleapis.com/auth/indexing');
        
        $httpClient = $client->authorize();
        $endpoint = 'https://indexing.googleapis.com/v3/urlNotifications/metadata?url=https%3A%2F%2Ffemcreditconso.fr';

        $response = $httpClient->request('GET', $endpoint);
        $status_code = $response->getStatusCode();
        $contents = $response->getBody()->getContents();
        
        $decodedContent = json_decode($contents);

        return $this->render('index_api/indexation_infos.html.twig', [
            'controller_name' => 'IndexApiController',
            'status_code' => $status_code,
            'content' => $decodedContent
        ]);
    }
}
