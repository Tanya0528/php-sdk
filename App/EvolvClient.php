<?php

declare(strict_types=1);

namespace App\EvolvClient;

use  App\EvolvStore\Store;
use  App\EvolvContext\Context;

require 'vendor/autoload.php';
require_once __DIR__ . '/EvolvStore.php';

ini_set('display_errors', 'on');

class EvolvClient
{
    public $initialized = false;
    public $context;
    private $store;

    public function __construct($environment, $endpoint = 'https://participants.evolv.ai/')
    {
        $this->store = new Store($environment, $endpoint);
        $this->context = new Context();
    }

    /**
     * Initializes the client with required context information.
     *
     * @param {String} uid A globally unique identifier for the current participant.
     * @param {String} sid A globally unique session identifier for the current participant.
     * @param {Object} remoteContext A map of data used for evaluating context predicates and analytics.
     * @param {Object} localContext A map of data used only for evaluating context predicates.
     */

    public function initialize($uid, $remoteContext = [], $localContext = [])
    {
        if ($this->initialized) {
            echo 'Evolv: Client is already initialized';
        }

        if (!$uid) {
            echo 'Evolv: "uid" must be specified';
        }

        $this->context->initialize($uid, $remoteContext, $localContext);
        $this->store->initialize($this->context);

        // TODO: emit EvolvClient.INITIALIZED event
    }

    public function getActiveKeys()
    {
        return $this->store->getActiveKeys();
    }

    public function confirm()
    {
        
    }

    public function contaminate()
    {

    }
}







