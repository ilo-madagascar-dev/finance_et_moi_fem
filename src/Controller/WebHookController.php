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
        // webhook.php
        //
        // Use this sample code to handle webhook events in your integration.
        //
        // 1) Paste this code into a new file (webhook.php)
        //
        // 2) Install dependencies
        //   composer require stripe/stripe-php
        //
        // 3) Run the server on http://localhost:4242
        //   php -S localhost:4242

        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = 'whsec_MPdI2xpNr94ge3iFN4CAcGf0KJPd27jQ';

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
        $event = \Stripe\Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
        } catch(\UnexpectedValueException $e) {
        // Invalid payload
        http_response_code(400);
        exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
        // Invalid signature
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

        return new Response(json_decode($invoice));

        //http_response_code(200);

        /*return $this->render('web_hook/index.html.twig', [
            'controller_name' => 'WebHookController',
        ]);*/
    }
}
