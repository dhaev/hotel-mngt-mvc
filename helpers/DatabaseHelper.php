<?php

class DatabaseHelper {
    public static function executeQuery($conn, $sql, $types = null, ...$params) {
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
        }

        if ($types && $params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Failed to execute statement: ' . mysqli_error($conn));
        }

        return $stmt;
    }
}
?>
