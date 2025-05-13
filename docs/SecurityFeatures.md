# Security Features Documentation

This document outlines the security features implemented in the application.

## 1. Input Sanitization
- All user inputs are sanitized using `ValidationHelper::sanitizeInput` to prevent SQL injection and XSS attacks.

## 2. Password Hashing
- Passwords are hashed using `password_hash` with `PASSWORD_DEFAULT`.
- Passwords are verified using `password_verify`.

## 3. CSRF Protection
- CSRF tokens are generated during session initialization and validated in forms to prevent CSRF attacks.

## 4. Session Security
- Secure session cookies are configured with the following attributes:
  - `secure`: Ensures cookies are sent over HTTPS only.
  - `httponly`: Prevents JavaScript access to cookies.
  - `samesite`: Restricts cross-site cookie usage.

## 5. XSS Prevention
- All user-generated content displayed in the browser is escaped using `htmlspecialchars`.

## 6. Error Handling
- Errors are logged using `error_log` instead of being displayed to users.

## 7. File Upload Security
- Uploaded files are validated for type and size.
- Files are stored outside the web root to prevent direct access.

## 8. HTTPS
- The application is configured to run over HTTPS to encrypt data in transit.

## 9. Access Control
- Sensitive pages are restricted based on user roles.

## 10. Content Security Policy (CSP)
- A CSP header is added to restrict the sources of scripts and other resources.

## 11. Rate Limiting
- Rate limiting is implemented to prevent brute force attacks on login forms.

## 12. Regular Security Audits
- The application undergoes periodic security audits to identify and fix vulnerabilities.
