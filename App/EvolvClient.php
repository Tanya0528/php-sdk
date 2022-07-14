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
    public bool $initialized = false;
    public $context;
    private $store;
    private bool $autoconfirm;

    public function __construct(string $environment, string $endpoint = 'https://participants.evolv.ai/', bool $autoconfirm = true)
    {
        $this->store = new Store($environment, $endpoint);
        $this->context = new Context();

        $this->autoconfirm = $autoconfirm;
    }

    /**
     * Initializes the client with required context information.
     *
     * @param {String} uid A globally unique identifier for the current participant.
     * @param {String} sid A globally unique session identifier for the current participant.
     * @param {Object} remoteContext A map of data used for evaluating context predicates and analytics.
     * @param {Object} localContext A map of data used only for evaluating context predicates.
     */

    public function initialize(string $uid, array $remoteContext = [], array $localContext = [])
    {
        if ($this->initialized) {
            throw new \Exception('Evolv: Client is already initialized');
        }

        if (!$uid) {
            throw new \Exception('Evolv: "uid" must be specified');
        }

        $this->context->initialize($uid, $remoteContext, $localContext);
        $this->store->initialize($this->context);

        if ($this->autoconfirm) {
            $this->confirm();
        }

        // TODO: emit EvolvClient.INITIALIZED event
    }

    /**
     * Check all active keys that start with the specified prefix.
     *
     * @param {String} prefix The prefix of the keys to check.
     * @returns {SubscribablePromise.<Object|Error>} A SubscribablePromise that resolves to object
     * describing the state of active keys.
     * @method
     */
    public function getActiveKeys(string $prefix = '')
    {
        return $this->store->getActiveKeys($prefix);
    }

    /**
     * Confirm that the consumer has successfully received and applied values, making them eligible for inclusion in
     * optimization statistics.
     */
    public function confirm()
    {
        $allocations = $this->context->get('experiments.allocations');
        if (!isset($allocations) || !count($allocations)) {
            return;
        }

        $entryPointEids = $this->store->activeEntryPoints();
        if (!count($entryPointEids)) {
            return;
        }
    
        $confirmations = $this->context->get('experiments.confirmations');
        $confirmedCids = array_map(function($item) { return $item['cid']; }, $confirmations);
    
        $contaminations = $this->context->get('experiments.contaminations');
        $contaminatedCids = array_map(function($item) { return $item['cid']; }, $contaminations);

        $confirmableAllocations = array_filter($allocations, function($alloc) use ($confirmedCids, $contaminatedCids, $entryPointEids) {
            return !array_search($alloc['cid'], $confirmedCids) &&
                !array_search($alloc['cid'], $contaminatedCids) &&
                array_search($alloc['eid'], $this->store->activeEids) &&
                array_search($alloc['eid'], $entryPointEids);
        });
        if (!count($confirmableAllocations)) {
            return;
        }

        $timestamp = time();

        $contextConfirmations = array_map(function($alloc) use ($timestamp) {
            return ['cid' => $alloc['cid'], 'timestamp' => $timestamp];
        }, $confirmableAllocations);

        $newConfirmations = array_merge($contextConfirmations, $confirmations);
        $this->context->update(['experiments' => ['confirmations' => $newConfirmations]]);

        foreach ($confirmableAllocations as $alloc) {
            // TODO: emit confirmation event
            // eventBeacon.emit('confirmation', assign({
            //     uid: alloc.uid,
            //     sid: alloc.sid,
            //     eid: alloc.eid,
            //     cid: alloc.cid
            //   }, context.remoteContext));
        };
    
        // TODO: emit EvolvClient.CONFIRMED event
    }

    /**
     * Marks a consumer as unsuccessfully retrieving and / or applying requested values, making them ineligible for
     * inclusion in optimization statistics.
     *
     * @param details {Object} Optional. Information on the reason for contamination. If provided, the object should
     * contain a reason. Optionally, a 'details' value should be included for extra debugging info
     * @param {boolean} allExperiments If true, the user will be excluded from all optimizations, including optimization
     * not applicable to this page
     */
    public function contaminate($details, bool $allExperiments = false)
    {
        $allocations = $this->context->get('experiments.allocations');
        if (!isset($allocations) || !count($allocations)) {
          return;
        }

        if (!isset($details['reason'])) {
            throw new \Exception('Evolv: contamination details must include a reason');
        }

        $contaminations = $this->context->get('experiments.contaminations');
        $contaminatedCids = array_map(function($item) { return $item['cid']; }, $contaminations);

        $contaminatableAllocations = array_filter($allocations, function($alloc) use ($contaminatedCids, $allExperiments) {
            return !array_search($alloc['cid'], $contaminatedCids) &&
                ($allExperiments || array_search($alloc['eid'], $this->store->activeEids));
        });

        if (!count($contaminatableAllocations)) {
            return;
        }

        $timestamp = time();

        $contextContaminations = array_map(function($alloc) use ($timestamp) {
            return ['cid' => $alloc['cid'], 'timestamp' => $timestamp];
        }, $contaminatableAllocations);

        $newContaminations = array_merge($contextContaminations, $contaminations);
        $this->context->update(['experiments' => ['contaminations' => $newContaminations]]);

        foreach ($contaminatableAllocations as $alloc) {
            // TODO: emit contamination events
            // eventBeacon.emit('contamination', assign({
            //     uid: alloc.uid,
            //     sid: alloc.sid,
            //     eid: alloc.eid,
            //     cid: alloc.cid,
            //     contaminationReason: details
            //   }, context.remoteContext));
        };
    
        // TODO: emit EvolvClient.CONTAMINATED event
    }
}







