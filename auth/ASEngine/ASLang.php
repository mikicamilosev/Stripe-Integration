<?php

class ASLang
{
    /**
     * Get whole language file with all terms.
     *
     * @param bool $jsonEncode Determine should data be encoded in json or not
     * @return mixed|string Array or JSON that contains whole language file of current language.
     * @throws \Exception
     */
    public static function all(bool $jsonEncode = true)
    {
        // determine language
        $language = self::getLanguage();

        // get translation for determined language
        $trans = self::getTrans($language);
        
        if ($jsonEncode) {
            return json_encode($trans);
        }

        return $trans;
    }

    /**
     * Get translation for specific term represented by $key param
     *
     * @param $key string Term
     * @param array $bindings If term value contains some variables (:name, :username or similar)
     * this array should contain values that those variables should be replaced with.
     * @return mixed|string
     * @throws \Exception
     */
    public static function get(string $key, array $bindings = [])
    {
        // determine language
        $language = self::getLanguage();

        // get translation array
        $trans = self::getTrans($language);

        // if term (key) doesn't exist, return empty string
        if (! isset($trans[$key])) {
            return '';
        }

        // term exist, get the value
        $value = $trans[$key];

        // replace variables with provided bindings
        if (! empty($bindings)) {
            foreach ($bindings as $key => $val) {
                $value = str_replace('{'.$key.'}', $val, $value);
            }
        }

        return $value;
    }

    /**
     * Set the application language
     *
     * @param $language string Language that should be set
     * @return bool
     */
    public static function setLanguage(string $language): bool
    {
        // check if language is valid
        if (! self::isValidLanguage($language)) {
            return false;
        }

        //set language cookie to 1 year
        setcookie('as_lang', $language, time() + 60 * 60 * 24 * 365, '/');

        // update session
        ASSession::set('as_lang', $language);

        unset($_GET['lang']);

        $queryString = http_build_query($_GET);

        $redirect = $_SERVER['PHP_SELF'];

        if ($queryString !== '' && $queryString !== '0') {
            $redirect .= "?{$queryString}";
        }

        redirect($redirect);
    }

    /**
     * Get current language
     * @return mixed String abbreviation of current language
     */
    public static function getLanguage(): string
    {
        // Get language from cookie if there is valid lang cookie
        if (isset($_COOKIE['as_lang']) && self::isValidLanguage($_COOKIE['as_lang'])) {
            return $_COOKIE['as_lang'];
        }

        return ASSession::get('as_lang', DEFAULT_LANGUAGE);
    }

    /**
     * Get translation array for provided language
     *
     * @param $language string Language to get translation array for
     * @return mixed Translation array.
     * @throws Exception
     */
    private static function getTrans(string $language): array
    {
        $file = self::getFilePath($language);

        if (self::isValidLanguage($language)) {
            return include $file;
        }

        throw new Exception('Language file does not exist!');
    }

    /**
     * Get language file path from lang directory
     *
     * @param $language string
     * @return string
     */
    private static function getFilePath(string $language): string
    {
        return dirname(__FILE__, 2) . '/Lang/' . $language . '.php';
    }

    /**
     * Check if language is valid (if file for given language exist in Lang folder)
     *
     * @param $lang string Language to validate
     * @return bool TRUE if language file exist, FALSE otherwise
     */
    private static function isValidLanguage(string $lang): bool
    {
        $file = self::getFilePath($lang);

        return file_exists($file);
    }

    /**
     * Generate a valid URL for switching to a specific language.
     *
     * @param $lang
     * @return string
     */
    public static function langUrl($lang): string
    {
        $params = array_merge($_GET, ['lang' => $lang]);

        $queryString = http_build_query($params);

        return sprintf(
            "%s/%s?%s",
            rtrim(SCRIPT_URL, "/"),
            ltrim($_SERVER['PHP_SELF'], "/"),
            $queryString
        );
    }
}
