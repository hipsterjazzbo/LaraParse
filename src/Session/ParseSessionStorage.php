<?php namespace LaraParse\Session;

use Illuminate\Session\SessionManager;
use LaraParse\Traits\CastsParseProperties;
use Parse\ParseStorageInterface;

class ParseSessionStorage implements ParseStorageInterface
{
    use CastsParseProperties;
    /**
     * @var \Illuminate\Session\SessionManager
     */
    private $session;

    /**
     * @param \Illuminate\Session\SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Sets a key-value pair in storage.
     *
     * @param string $key   The key to set
     * @param mixed  $value The value to set
     *
     * @return null
     */
    public function set($key, $value)
    {
        $this->session->put($key, $value);

        return null;
    }

    /**
     * Remove a key from storage.
     *
     * @param string $key The key to remove.
     *
     * @return null
     */
    public function remove($key)
    {
        $this->session->forget($key);

        return null;
    }

    /**
     * Gets the value for a key from storage.
     *
     * @param string $key The key to get the value for
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->session->get($key);
    }

    /**
     * Clear all the values in storage.
     *
     * @return null
     */
    public function clear()
    {
        $this->session->clear();
    }

    /**
     * Save the data, if necessary. This would be a no-op when using the
     * $_SESSION implementation, but could be used for saving to file or
     * database as an action instead of on every set.
     *
     * @return null
     */
    public function save()
    {
        return null;
    }

    /**
     * Get all keys in storage.
     *
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->session->all());
    }

    /**
     * Get all key-value pairs from storage.
     *
     * @return array
     */
    public function getAll()
    {
        return $this->session->all();
    }
}
