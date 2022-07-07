<?php

declare(strict_types=1);

namespace App\EvolvContext;

class Context
{
    public $current = [];
    public $set = [];
    public $value;
    public $local;
    public $context;
    public $result;

    
    private string $uid;
    private array $remoteContext = [];
    private array $localContext = [];
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
       return $this->remoteContext; 
    }

    private function ensureInitialized(): void
    {
        if (!$this->initialized) {
            throw new \Exception('Evolv: The context is not initialized');
        }
    }

    public function initialize($uid, $remoteContext, $localContext)
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
    public function set(string $key, mixed $value, bool $local = false): void
    {
        $this->ensureInitialized();

        $context = $local ? $this->localContext : $this->remoteContext;

        $key;
        $value;

        switch ($local) {
            case true:
                $this->localContext[] = $this->setKeyToValue($key, $value, $local);
                break;
            case false:
                $this->remoteContext[] = $this->setKeyToValue($key, $value, $local);
                break;
        }
    }

    private function setKeyToValue($key, $value)
    {
        $key;
        $value;

        $array = explode(".", $key);

        $array = array_reverse($array);

        foreach ($array as $key => $val) {

            $setval = ($key === 0) ? $value : self::$result;

            self::$result = [

                $val => $setval

            ];

        }

        return self::$result;

    }

    private function arraysEqual($a, $b)
    {
        if (!is_array($a) || !is_array($b)) return false;

        if ($a === $b) return true;

        if (count($a) !== count($b)) return false;

        for ($i = 0; $i < count($a); ++$i) {

            if ($a[$i] !== $b[$i]) return false;

        }
        return true;
    }
}