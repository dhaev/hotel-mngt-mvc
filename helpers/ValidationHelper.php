<?php

class ValidationHelper {
    public static function signupEmpty($fname, $lname, $email, $phone, $pwd, $rpwd) {
        return empty($fname) || empty($lname) || empty($email) || empty($phone) || empty($pwd) || empty($rpwd);
    }

    public static function invalidEmail($email) {
        return !filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function pwdMatch($pwd, $rpwd) {
        return $pwd !== $rpwd;
    }

    public static function validUsername($name) {
        return !preg_match('/^[a-zA-Z0-9]*$/', $name);
    }

    public static function sanitizeInput($input) {
        return is_null($input) ? '' : htmlspecialchars(strip_tags(trim($input)));
    }

    public static function enforcePasswordPolicy($pwd) {
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/', $pwd);
    }
}
