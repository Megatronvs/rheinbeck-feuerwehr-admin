<?php
/**
 * Haupt-Konfigurationsdatei
 * Alle wichtigen Einstellungen für die Anwendung
 */

// ============================================================================
// 1. DATENBANK-KONFIGURATION
// ============================================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');              // XAMPP: normalerweise leer
define('DB_NAME', 'rheinbeck');
define('DB_CHARSET', 'utf8mb4');

// ============================================================================
// 2. APPLICATION-EINSTELLUNGEN
// ============================================================================

// Base URL der Anwendung
define('BASE_URL', 'http://localhost/rheinbeck/');

// Anwendungstitel
define('APP_NAME', 'Brand- und Katastrophenschutzamt Rheinbeck');
define('APP_SHORT', 'BKA Rheinbeck');

// Zeitzone
define('TIMEZONE', 'Europe/Berlin');
date_default_timezone_set(TIMEZONE);

// ============================================================================
// 3. SICHERHEIT
// ============================================================================

// Session-Konfiguration
define('SESSION_NAME', 'rheinbeck_session');
define('SESSION_TIMEOUT', 3600);           // 1 Stunde in Sekunden
define('SESSION_SECURE', false);           // true = nur HTTPS (lokal: false)
define('SESSION_HTTPONLY', true);          // Verhindert JS-Zugriff auf Cookie

// Passwort-Hashing
define('PASSWORD_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_COST', 10);               // 10 = gut für Local, 12+ für Production

// CSRF-Token
define('CSRF_TOKEN_NAME', '_csrf_token');
define('CSRF_TOKEN_LENGTH', 32);

// ============================================================================
// 4. DISCORD INTEGRATION (optional)
// ============================================================================

define('DISCORD_ENABLED', false);          // Auf true setzen wenn aktiviert
define('DISCORD_CLIENT_ID', 'YOUR_CLIENT_ID');
define('DISCORD_CLIENT_SECRET', 'YOUR_CLIENT_SECRET');
define('DISCORD_REDIRECT_URI', BASE_URL . 'auth/discord_callback.php');
define('DISCORD_SERVER_ID', '1428814662455136470');

// ============================================================================
// 5. DATEIEN & UPLOADS
// ============================================================================

define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('UPLOAD_URL', BASE_URL . 'assets/uploads/');
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024);  // 10 MB
define('ALLOWED_FILE_TYPES', ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx']);

// ============================================================================
// 6. LOGGING & DEBUG
// ============================================================================

define('DEBUG_MODE', true);                 // true = mehr Fehlermeldungen
define('LOG_DIR', __DIR__ . '/../logs/');
define('LOG_ERRORS', true);
define('LOG_QUERIES', false);               // true = alle SQL Queries loggen (Performance!)

// ============================================================================
// 7. PAGINATION
// ============================================================================

define('ITEMS_PER_PAGE', 20);
define('ITEMS_PER_PAGE_ADMIN', 50);

// ============================================================================
// 8. SYSTEM-KONSTANTEN
// ============================================================================

// Benutzer-Status
define('USER_STATUS_ACTIVE', 'active');
define('USER_STATUS_INACTIVE', 'inactive');
define('USER_STATUS_ON_LEAVE', 'on_leave');
define('USER_STATUS_RETIRED', 'retired');

// Kurse-Status
define('COURSE_STATUS_DRAFT', 'draft');
define('COURSE_STATUS_PUBLISHED', 'published');
define('COURSE_STATUS_ACTIVE', 'active');
define('COURSE_STATUS_COMPLETED', 'completed');
define('COURSE_STATUS_CANCELED', 'canceled');

// Fahrzeug-Status
define('VEHICLE_STATUS_AVAILABLE', 'available');
define('VEHICLE_STATUS_IN_SERVICE', 'in_service');
define('VEHICLE_STATUS_MAINTENANCE', 'maintenance');
define('VEHICLE_STATUS_OUT_OF_SERVICE', 'out_of_service');

// ============================================================================
// 9. FEHLERBEHANDLUNG
// ============================================================================

error_reporting(E_ALL);
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

// Custom Error Handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $error_types = [
        E_ERROR => 'ERROR',
        E_WARNING => 'WARNING',
        E_PARSE => 'PARSE ERROR',
        E_NOTICE => 'NOTICE',
        E_CORE_ERROR => 'CORE ERROR',
        E_CORE_WARNING => 'CORE WARNING',
        E_COMPILE_ERROR => 'COMPILE ERROR',
        E_COMPILE_WARNING => 'COMPILE WARNING',
        E_USER_ERROR => 'USER ERROR',
        E_USER_WARNING => 'USER WARNING',
        E_USER_NOTICE => 'USER NOTICE',
    ];
    
    $type = $error_types[$errno] ?? 'UNKNOWN';
    $message = date('[Y-m-d H:i:s]') . " [$type] $errstr in $errfile on line $errline\n";
    
    if (LOG_ERRORS && is_dir(LOG_DIR)) {
        file_put_contents(LOG_DIR . 'php_errors.log', $message, FILE_APPEND);
    }
    
    if (DEBUG_MODE) {
        echo "<pre>$message</pre>";
    }
    
    return true;
});

// ============================================================================
// 10. ZEICHENSATZ
// ============================================================================

header('Content-Type: text/html; charset=utf-8');
