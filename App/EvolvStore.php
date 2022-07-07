<?php

namespace App\EvolvStore;

use App\EvolvContext\Context;
use  App\EvolvPredicate\Predicate;
use HttpClient;

require_once __DIR__ . '/EvolvOptions.php';
require_once __DIR__ . '/EvolvContext.php';
require_once __DIR__ . '/EvolvPredicate.php';

class Store
{
    private $httpClient;
    private bool $initialized = false;
    private string $environment;
    private string $endpoint;
    private $context;
    public $genomeKeyStates = [];
    public $configKeyStates = [];
    public $activeEids = [];

    public function __construct(string $environment, string $endpoint)
    {
        $this->environment = $environment;
        $this->endpoint = $endpoint;

        $this->httpClient = new HttpClient();
    }

    public function initialize($context) {
        if ($this->initialized) {
            echo 'Evolv: The store has already been initialized.';
        }

        $this->context = $context;
        $this->initialized = true;

        $this->pull();

        // TODO: waitFor CONTEXT_CHANGED event
    }

    private function pull()
    {
        $allocationUrl = $this->endpoint . 'v1/' . $this->environment . '/' . $this->context->uid . '/allocations';
        $configUrl = $this->endpoint . 'v1/' . $this->environment . '/' . $this->context->uid . '/configuration.json';

        $arr_location = $this->httpClient->request($allocationUrl);
        $arr_config = $this->httpClient->request($configUrl);

        $arr_config = json_decode($arr_config, true);

        $this->genomeKeyStates = [
            'needed' => [],
            'requested' => [],
            'experiments' => [],
        ];

        $this->configKeyStates = [
            'needed' => [],
            'requested' => [],
            'experiments' => [],
        ];

        array_push($this->genomeKeyStates['experiments'], $arr_config);

        foreach ($arr_config['_experiments'] as $key => $v) {
            array_push($this->configKeyStates['experiments'], $v);
        }
    }

    public function getActiveKeys(string $prefix = '')
    {
        $predicate = new Predicate();

        $configKeyStates = $this->configKeyStates;

        $context = $this->context->getRemoteContext();

        $keys = $predicate->evaluate($context, $configKeyStates);

        return $keys;
    }

    public function activeEntryPoints()
    {
        return [];
    }
}
