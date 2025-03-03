<?php

/**
 * SessionManager Class
 *
 * This class handles session operations including:
 * - Creating a session (if not already started)
 * - Setting session variables
 * - Retrieving session variables
 * - Deleting a specific session variable
 * - Destroying the session
 */
class SessionManager
{

    /**
     * Constructor
     *
     * Starts the session if it hasn't been started already.
     */
    public function __construct()
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
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session variable.
     *
     * @param string $key The key for the session variable.
     * @return mixed|null Returns the session variable value if set, otherwise null.
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Delete a specific session variable.
     *
     * @param string $key The key for the session variable to be deleted.
     */
    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy the current session.
     *
     * This method clears all session variables, removes the session cookie,
     * and destroys the session.
     */
    public function destroy()
    {
        // Unset all session variables.
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
     * Store an object in the session using `serialize()`.
     *
     * @param string $key    The key to use for the session variable.
     * @param object $object The object to be stored.
     */
    public function storeObject($key, $object)
    {
        $_SESSION[$key] = serialize($object);
    }

    /**
     * Retrieve and deserialize an object from the session.
     *
     * @param string $key The key for the session variable.
     * @return object|null The deserialized object if it exists, otherwise null.
     */
    public function getObject($key)
    {
        return isset($_SESSION[$key]) ? unserialize($_SESSION[$key]) : null;
    }

    /**
     * Copy matching properties from one object to another.
     *
     * @param object $sourceObj      The source object to copy properties from.
     * @param object $destinationObj The destination object to copy properties into.
     */
    public static function copyProperties(object $sourceObj, object $destinationObj)
    {
        foreach (get_object_vars($sourceObj) as $key => $value) {
            if (property_exists($destinationObj, $key) && $key !== 'conn') {
                // Only copy if the property exists in the destination object
                $destinationObj->$key = $value;
            }
        }
    }

    /**
     * Retrieve an object from the session by key, copy its properties into a new temporary instance,
     * and return the new instance.
     *
     * @param string $key The session key used to store the object.
     * @return object|null The new object with copied properties, or null if the object doesn't exist.
     */
    public function retrieveAndCopyObject($key)
    {
        // Retrieve the object from the session using getObject()
        $sourceObj = $this->getObject($key);
        if ($sourceObj === null) {
            return null; // No object found for the provided key
        }

        // Create a new temporary object of the same class as the retrieved object
        $className = get_class($sourceObj);
        $tempObj = new $className();

        // Copy properties from the source object to the temporary object
        self::copyProperties($sourceObj, $tempObj);

        // Return the temporary object with all the properties copied over
        return $tempObj;
    }
}
?>

<!-- end -->