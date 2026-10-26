<?php
/**
 * CSRF Protection Helper
 * Native PHP implementation using session tokens and constant-time verification.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Returns the current CSRF token from the session, generating one if not present.
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validates a submitted token against the session token using constant-time comparison.
 */
function verify_csrf_token(?string $token = null): bool {
    $token = $token ?? $_POST['csrf_token'] ?? '';
    return !empty($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Enforces that the incoming request is POST and carries a valid CSRF token.
 * Halts execution with HTTP 403 Forbidden on failure.
 */
function require_csrf_token(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token()) {
        http_response_code(403);
        die("CSRF token validation failed. Request denied.");
    }
}
