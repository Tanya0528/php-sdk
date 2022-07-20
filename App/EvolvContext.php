<?php

declare(strict_types=1);

namespace App\EvolvContext;

use function Utils\getValueForKey;
use function Utils\setKeyToValue;
use function Utils\removeValueForKey;
use function Utils\emit;
require_once __DIR__ . '/../Utils/getValueForKey.php';
require_once __DIR__ . '/../Utils/setKeyToValue.php';
require_once __DIR__ . '/../Utils/removeValueForKey.php';
require_once __DIR__ . '/../Utils/waitForIt.php';


const CONTEXT_CHANGED = 'context.changed';
const CONTEXT_INITIALIZED = 'context.initialized';
const CONTEXT_VALUE_REMOVED = 'context.value.removed';
const CONTEXT_VALUE_ADDED = 'context.value.added';
const CONTEXT_VALUE_CHANGED = 'context.value.changed';
const CONTEXT_DESTROYED = 'context.destroyed';

class Context
{
    public string $uid;
    public array $remoteContext = [];
    public array $localContext = [];
    private bool $initialized = false;

    private function ensureInitialized(): void
    {
        if (!$this->initialized) {
            throw new \Exception('Evolv: The context is not initialized');
        }
    }

    private function resolve()
    {
        return array_merge_recursive($this->remoteContext, $this->localContext);
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

        emit(CONTEXT_INITIALIZED, $this->resolve());
    }

    public function __destruct()
    {
        emit(CONTEXT_DESTROYED);
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

        $before = null;
        if ($local) {
            $before = getValueForKey($key, $this->localContext);
        } else {
            $before = getValueForKey($key, $this->remoteContext);
        }

        if ($local) {
            setKeyToValue($key, $value, $this->localContext);
        } else {
            setKeyToValue($key, $value, $this->remoteContext);
        }

        if (is_null($before)) {
            emit(CONTEXT_VALUE_ADDED, $key, $value, $local);
        } else {
            emit(CONTEXT_VALUE_CHANGED, $key, $value, $local);
        }
        emit(CONTEXT_CHANGED, $this->resolve());
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
    public function remove(string $key)
    {
        $this->ensureInitialized();
        $local = removeValueForKey($key, $this->localContext);
        $remote = removeValueForKey($key, $this->remoteContext);
        $removed = $local || $remote;
    
        if ($removed) {
            $updated = $this->resolve();
            emit(CONTEXT_VALUE_REMOVED, $key, !$remote, $updated);
            emit(CONTEXT_CHANGED, $updated);
        }
    
        return $removed;
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