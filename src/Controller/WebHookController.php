<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebHookController extends AbstractController
{
    /**
     * @Route("/webhook", name="web_hook")
     */
    public function index(Request $request): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51JAyRkDd9O5GRESHwySMe7BscZHT8npvPTAnFRUUFzrUtxKsytTSetDABLsB74Np0ODjjhY26VpkZIJXiwvkxB7a00G4pDH3n1');
        //$payload = @file_get_contents('php://input');
        $endpoint_secret = 'whsec_xayjuDO33MODkpYjQhw53ntA4OggyslR';

        $payload = $request->getContent();
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true),  $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        $invoice = [];
        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        return $response;
        /* return $this->render('web_hook/index.html.twig', [
            'controller_name' => 'WebHookController',
        ]); */
    }

    /**
     * @Route("/webhook/test/deux", name="web_hook_test_deux")
     */
    public function webhookTestDeux(Request $request): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_VePHdqKTYQjKNInc7u56JBrQ');
        //$payload = @file_get_contents('php://input');
        $payload = $request->getContent();
        
        $event = null;
        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        return $response;
        /* return $this->render('web_hook/index.html.twig', [
            'controller_name' => 'WebHookController',
        ]); */
    }

    /**
     * @Route("/henintsoa/account/webhook", name="henintsoa_account_web_hook", methods={"POST"})
     */
    public function henintsoaAccountWebHook(Request $request): Response
    {
        \Stripe\Stripe::setApiKey('sk_test_51JSbdPBW8SyIFHAgGLf2rFeDFKCcS0UfKFRuGifDaCKnQg9t1m6PSK1NxwSuf23JcmY5HK8ZTcV0Pvaex4E2RaIt00fbf8PcYC');
        //$payload = @file_get_contents('php://input');
        $payload = $request->getContent();
        
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
            json_decode($payload, true)
        );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
        case 'invoice.created':
            $invoice = $event->data->object;
        case 'invoice.deleted':
            $invoice = $event->data->object;
        case 'invoice.finalization_failed':
            $invoice = $event->data->object;
        case 'invoice.finalized':
            $invoice = $event->data->object;
        case 'invoice.marked_uncollectible':
            $invoice = $event->data->object;
        case 'invoice.paid':
            $invoice = $event->data->object;
        case 'invoice.payment_action_required':
            $invoice = $event->data->object;
        case 'invoice.payment_failed':
            $invoice = $event->data->object;
        case 'invoice.payment_succeeded':
            $invoice = $event->data->object;
        case 'invoice.sent':
            $invoice = $event->data->object;
        case 'invoice.upcoming':
            $invoice = $event->data->object;
        case 'invoice.updated':
            $invoice = $event->data->object;
        case 'invoice.voided':
            $invoice = $event->data->object;
        // ... handle other event types
        default:
            echo 'Received unknown event type ' . $event->type;
        }
        
        http_response_code(200);
        
        $data =  ['invoice' => $invoice];
        $response = $this->json($data, 200, [], ['groups'=>'conversation:read']);

        return $response;
        /* return $this->render('web_hook/index.html.twig', [
            'controller_name' => 'WebHookController',
        ]); */
    }
}
