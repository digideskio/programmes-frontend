<?php
declare(strict_types = 1);

namespace App\Controller;

use GuzzleHttp\Client;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

class FacebookController extends BaseController
{
    const ACCESS_TOKEN = '';


    public function show(Request $request)
    {
        // Your verify token. Should be a random string.
        $verifyToken = '';

        // Parse the query params
        $mode = $request->query->get('hub_mode');
        $token = $request->query->get('hub_verify_token');
        $challenge = $request->query->get('hub_challenge');

        // Checks if a token and mode is in the query string of the request
        if ($mode && $token) {

            // Checks the mode and token sent is correct
            if ($mode === 'subscribe' && $token === $verifyToken) {

                // Responds with the challenge token from the request
                $this->response()->setContent($challenge); //200OK

            } else {
                // Responds with '403 Forbidden' if verify tokens do not match
                $this->response()->setStatusCode(403);
            }
        }

        return $this->response();
    }

    public function edit(Request $request)
    {
        $body = json_decode($request->getContent());
        // Checks this is an event from a page subscription
        if ($body->object === 'page') {

            // Iterates over each entry - there may be multiple if batched
            foreach ($body->entry as $entry) {
                // Gets the message. entry.messaging is an array, but
                // will only ever contain one message, so we get index 0
                $webhookEvent = $entry->messaging[0];
                $senderPsid = $webhookEvent->sender->id;
                if ($webhookEvent->message) {
                    $this->handleMessage($senderPsid, $webhookEvent->message);
                } elseif ($webhookEvent->postback) {
                    $this->handlePostback($senderPsid, $webhookEvent->postback);
                }
            }

            // Returns a '200 OK' response to all requests
            $this->response()->setContent('EVENT_RECEIVED'); //200OK
        } else {
            // Returns a '404 Not Found' if event is not from a page subscription
            $this->response()->setStatusCode(404);
        }

        return $this->response();
    }

    private function send($id, $response)
    {
        $message = ['recipient' => ['id' => $id], 'message' => $response];
        $client = new Client();
        $client->request(
            'POST',
            'https://graph.facebook.com/v2.6/me/messages?access_token=' . self::ACCESS_TOKEN,
            [
                'json' => $message,
            ]
        );
    }


    private function handleMessage(string $senderPsid, stdClass $message)
    {
        if ($message->text === 'Hey') {
            $response = ['text' => 'Hey. Would you like to know when something is next on?'];
        } else {
            $response = [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text' => 'Which programme would you like to know the next on time for?',
                        'buttons' => [
                            [
                                'type' => 'postback',
                                'title' => 'Eastenders',
                                'payload' => 'b006m86d',
                            ],[
                                'type' => 'postback',
                                'title' => 'Dr Who',
                                'payload' => 'b006q2x0',
                            ],[
                                'type' => 'postback',
                                'title' => 'torchwood',
                                'payload' => 'b006m8ln',
                            ],
                        ]
                    ],
                ]
            ];
        }
        $this->send($senderPsid, $response);
    }

    private function handlePostback(string $senderPsid, string $postback)
    {
    }
}
