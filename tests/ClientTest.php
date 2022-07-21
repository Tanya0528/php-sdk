<?php

use PHPUnit\Framework\TestCase;

use App\EvolvClient;
require_once __DIR__ . '/../App/EvolvClient.php';


class ClientTest extends TestCase {

    public function testInitializeMakesTwoRequests() {
        $environment = '758012fca1';
        $endpoint = 'https://participants.evolv.ai/v1';
        $uid = 'uid';

        $client = new EvolvClient($environment, $uid, $endpoint);
        $client->initialize($uid);

        // TODO: verify two requests are made
    }

}
