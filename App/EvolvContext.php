<?php

declare(strict_types=1);

namespace App\EvolvContext;

use function Utils\setKeyToValue;
use function Utils\getValueForKey;
require_once __DIR__ . '/../Utils/setKeyToValue.php';
require_once __DIR__ . '/../Utils/getValueForKey.php';

class Context
{
    public $current = [];
    public $set = [];
    public $value;
    public $local;
    public $context;
    public $result;

    
    public string $uid;
    public array $remoteContext = [];
    public array $localContext = [];
    private bool $initialized = false;

    /**
     * A unique identifier for the participant.
     *
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * The context information for evaluation of predicates and analytics.
     *
     * @return array
     */
    public function getRemoteContext(): array
    {
        // TODO: return cloned copy of remoteContext
        return $this->remoteContext;
    }

    /**
     * The context information for evaluation of predicates only, and not used for analytics.
     *
     * @return array
     */
    public function getLocalContext(): array
    {
        // TODO: return cloned copy of localContext
       return $this->localContext; 
    }

    private function ensureInitialized(): void
    {
        if (!$this->initialized) {
            throw new \Exception('Evolv: The context is not initialized');
        }
    }

    public function initialize($uid, $remoteContext = [], $localContext = [])
    {
        if ($this->initialized) {
            throw new \Exception('Evolv: The context is already initialized');
        }

        $this->uid = $uid;

        // TODO: clone the remoteContext passed from args
        $this->remoteContext = $remoteContext;

        // TODO: clone the localContext passed from args
        $this->localContext = $localContext;

        $this->initialized = true;

        // TODO: emit CONTEXT_INITIALIZED event
    }

    public function __destruct()
    {
        // TODO: emit CONTEXT_DESTROYED event
    }

    /**
     * Sets a value in the current context.
     *
     * Note: This will cause the effective genome to be recomputed.
     * 
     * @param string $key The key to associate the value to.
     * @param mixed $value The value to associate with the key.
     * @param bool $local If true, the value will only be added to the localContext.
     * @return bool True if context value has been changes, otherwise false.
     */ 
    public function set(string $key, $value, bool $local = false): void
    {
        $this->ensureInitialized();

        if ($local) {
            setKeyToValue($key, $value, $this->localContext);
        } else {
            setKeyToValue($key, $value, $this->remoteContext);
        }
    }

    /**
     * Retrieve a value from the context.
     *
     * @param {String} key The kay associated with the value to retrieve.
     * @returns {*} The value associated with the specified key.
     */
    public function get(string $key)
    {
        $this->ensureInitialized();

        $value = getValueForKey($key, $this->remoteContext);
        if (!$value) {
            $value = getValueForKey($key, $this->localContext);
        }

        return $value;
    }

    /**
     * Remove a specified key from the context.
     *
     * Note: This will cause the effective genome to be recomputed.
     *
     * @param key {String} The key to remove from the context.
     */
    public function remote(string $key)
    {
        $this->ensureInitialized();
    }

    /**
     * Merge the specified object into the current context.
     *
     * Note: This will cause the effective genome to be recomputed.
     *
     * @param update {Object} The values to update the context with.
     * @param local {Boolean} If true, the values will only be added to the localContext.
     */
    public function update($update, $local = false) {
        $this->ensureInitialized();
    }
}