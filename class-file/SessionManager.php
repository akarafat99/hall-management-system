<?php

/**
 * SessionStatic class (pure static version)
 * 
 * This class handles session operations entirely statically. You can call its methods directly
 * without instantiating the class.
 */
class SessionStatic
{
    /**
     * Ensure the session is started.
     *
     * Checks if a session is already running, and if not, starts a session.
     *
     * @return void
     */
    public static function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session variable.
     *
     * @param string $key   The key to use for the session variable.
     * @param mixed  $value The value to store.
     * @return void
     */
    public static function set(string $key, $value): void
    {
        self::ensureSessionStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key The key for the session variable.
     * @return mixed|null Returns the session variable value if set, otherwise null.
     */
    public static function get(string $key)
    {
        self::ensureSessionStarted();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Delete a specific session variable.
     *
     * @param string $key The key for the session variable to be deleted.
     * @return void
     */
    public static function delete(string $key): void
    {
        self::ensureSessionStarted();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the current session.
     *
     * Clears all session variables, removes the session cookie,
     * and destroys the session.
     *
     * @return void
     */
    public static function destroy(): void
    {
        self::ensureSessionStarted();
        // Clear all session variables.
        $_SESSION = [];

        // Delete the session cookie if one exists.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        // Destroy the session.
        session_destroy();
    }

    /**
     * Store an object in the session using serialize().
     *
     * @param string $key    The key to use for the session variable.
     * @param mixed  $object The object to store.
     * @return void
     */
    public static function storeObject(string $key, $object): void
    {
        self::ensureSessionStarted();
        $_SESSION[$key] = serialize($object);
    }

    /**
     * Retrieve and deserialize an object from the session.
     *
     * @param string $key The key for the session variable.
     * @return object|null Returns the deserialized object if it exists, otherwise null.
     */
    public static function getObject(string $key)
    {
        self::ensureSessionStarted();
        return isset($_SESSION[$key]) ? unserialize($_SESSION[$key]) : null;
    }

    /**
     * Copy matching properties from a source object to a destination object.
     *
     * @param object|null $sourceObj      The object to copy from (can be null).
     * @param object      $destinationObj The object to copy to.
     * @throws InvalidArgumentException if the source object is null.
     * @return void
     */
    public static function copyProperties(?object $sourceObj, object $destinationObj): void
    {
        if ($sourceObj === null) {
            throw new InvalidArgumentException("Source object is null.");
        }
        foreach (get_object_vars($sourceObj) as $key => $value) {
            if (property_exists($destinationObj, $key) && $key !== 'conn') {
                $destinationObj->$key = $value;
            }
        }
    }

    /**
     * Retrieve an object from the session by key, copy its properties into a new temporary instance,
     * and return the temporary instance.
     *
     * @param string $key The session key used to store the object.
     * @return object|null The new object with copied properties if found, otherwise null.
     */
    public static function retrieveAndCopyObject(string $key)
    {
        self::ensureSessionStarted();
        $sourceObj = self::getObject($key);
        if ($sourceObj === null) {
            return null;
        }
        $className = get_class($sourceObj);
        $tempObj = new $className();
        self::copyProperties($sourceObj, $tempObj);
        return $tempObj;
    }
}

/**
 * Session class (object version)
 * 
 * This class handles session operations using instance methods.
 * You need to create an object of this class to access its methods.
 */
class Session
{
    /**
     * Ensure the session is started.
     *
     * Checks if a session is already running, and if not, starts a session.
     *
     * @return void
     */
    public function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session variable.
     *
     * @param string $key   The key to use for the session variable.
     * @param mixed  $value The value to store.
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->ensureSessionStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key The key for the session variable.
     * @return mixed|null Returns the session variable value if set, otherwise null.
     */
    public function get(string $key)
    {
        $this->ensureSessionStarted();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Delete a specific session variable.
     *
     * @param string $key The key for the session variable to be deleted.
     * @return void
     */
    public function delete(string $key): void
    {
        $this->ensureSessionStarted();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the current session.
     *
     * Clears all session variables, removes the session cookie, and destroys the session.
     *
     * @return void
     */
    public function destroy(): void
    {
        $this->ensureSessionStarted();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Store an object in the session using serialize().
     *
     * @param string $key    The key to use for the session variable.
     * @param mixed  $object The object to store.
     * @return void
     */
    public function storeObject(string $key, $object): void
    {
        $this->ensureSessionStarted();
        $_SESSION[$key] = serialize($object);
    }

    /**
     * Retrieve and deserialize an object from the session.
     *
     * @param string $key The key for the session variable.
     * @return object|null Returns the deserialized object if it exists, otherwise null.
     */
    public function getObject(string $key)
    {
        $this->ensureSessionStarted();
        return isset($_SESSION[$key]) ? unserialize($_SESSION[$key]) : null;
    }

    /**
     * Copy matching properties from a source object to a destination object.
     *
     * @param object|null $sourceObj      The object to copy from (can be null).
     * @param object      $destinationObj The object to copy to.
     * @throws InvalidArgumentException if the source object is null.
     * @return void
     */
    public function copyProperties(?object $sourceObj, object $destinationObj): void
    {
        if ($sourceObj === null) {
            throw new InvalidArgumentException("Source object is null.");
        }
        foreach (get_object_vars($sourceObj) as $key => $value) {
            if (property_exists($destinationObj, $key) && $key !== 'conn') {
                $destinationObj->$key = $value;
            }
        }
    }

    /**
     * Retrieve an object from the session by key, copy its properties into a new temporary instance,
     * and return the temporary instance.
     *
     * @param string $key The session key used to store the object.
     * @return object|null The new object with copied properties if found, otherwise null.
     */
    public function retrieveAndCopyObject(string $key)
    {
        $this->ensureSessionStarted();
        $sourceObj = $this->getObject($key);
        if ($sourceObj === null) {
            return null;
        }
        $className = get_class($sourceObj);
        $tempObj = new $className();
        $this->copyProperties($sourceObj, $tempObj);
        return $tempObj;
    }
}

?>

<!-- end -->