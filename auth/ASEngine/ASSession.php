<?php

class ASSession
{
    /**
     * Start session.
     */
    public static function startSession(): void
    {
        ini_set('session.use_only_cookies', SESSION_USE_ONLY_COOKIES);
        
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params(
            $cookieParams["lifetime"],
            $cookieParams["path"],
            $cookieParams["domain"],
            SESSION_SECURE,
            SESSION_HTTP_ONLY
        );

        session_start();
    }

    /**
     * Destroys session.
     *
     */
    public static function destroySession(): void
    {
        $_SESSION = [];

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

        session_destroy();
    }

    /**
     * Regenerates session id.
     *
     * @param bool $deleteOldSession
     * @return bool
     */
    public static function regenerate(bool $deleteOldSession = true): bool
    {
        return session_regenerate_id($deleteOldSession);
    }
    
    /**
     * Set session data.
     *
     * @param mixed $key Key that will be used to store value.
     * @param mixed $value Value that will be stored.
     */
    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset session data with provided key.
     * @param $key
     */
    public static function destroy($key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Get data from $_SESSION variable.
     * @param mixed $key Key used to get data from session.
     * @param mixed $default This will be returned if there is no record inside
     * session for given key.
     * @return mixed Session value for given key.
     */
    public static function get($key, $default = null)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }

        return $default;
    }
}
