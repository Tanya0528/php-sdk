<?php

use PHPUnit\Framework\TestCase;

use App\EvolvClient\EvolvClient;
require_once __DIR__ . '/../App/EvolvClient.php';


class EvolvClientTest extends TestCase {

    public function testInitializeMakesTwoRequests() {
/*        $environment = '758012fca1';
        $endpoint = 'https://participants.evolv.ai/v1';
        $uid = 'uid';

        $client = new EvolvClient($environment, $uid, $endpoint);
        $client->initialize($environment, $uid, $endpoint, [], []);*/

        // TODO: verify two requests are made
    }

}
