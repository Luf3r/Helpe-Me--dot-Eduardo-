<?php
// app/Helpers/sanitize.php

class Sanitizer {
    /**
     * Sanitize string input
     * @param string $data
     * @return string
     */
    public static function sanitizeString($data) {
        $data = trim($data);
        $data = strip_tags($data);
        $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        return $data;
    }

    /**
     * Sanitize email
     * @param string $email
     * @return string
     */
    public static function sanitizeEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    /**
     * Sanitize URL
     * @param string $url
     * @return string
     */
    public static function sanitizeUrl($url) {
        return filter_var(trim($url), FILTER_SANITIZE_URL);
    }

    /**
     * Sanitize integer
     * @param mixed $value
     * @return int
     */
    public static function sanitizeInt($value) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Sanitize float
     * @param mixed $value
     * @return float
     */
    public static function sanitizeFloat($value) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    /**
     * Sanitize boolean
     * @param mixed $value
     * @return bool
     */
    public static function sanitizeBoolean($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Sanitize array elements
     * @param array $array
     * @return array
     */
    public static function sanitizeArray($array) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = self::sanitizeArray($value);
            } else {
                $array[$key] = self::sanitizeString($value);
            }
        }
        return $array;
    }

    /**
     * Sanitize SQL input (basic protection)
     * @param string $data
     * @return string
     */
    public static function escapeForSql($data) {
        // Note: This should be used in conjunction with prepared statements
        $data = self::sanitizeString($data);
        return addslashes($data);
    }

    /**
     * Sanitize HTML output
     * @param string $data
     * @return string
     */
    public static function output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
}

// Example usage:
// $clean_input = Sanitizer::sanitizeString($_POST['input']);
// $clean_email = Sanitizer::sanitizeEmail($_POST['email']);
?>