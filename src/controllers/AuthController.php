<?php
/**
 * AuthController
 * Handles registration logic: validation, password hashing,
 * duplicate checking, and delegating DB writes to User model.
 * Never outputs HTML — always returns a result array.
 */

require_once(__DIR__ . '/../models/User.php');
require_once(__DIR__ . '/../config/db.php');

class AuthController {

    private User $userModel;

    public function __construct() {
        global $conn;
        $this->userModel = new User($conn);
    }

    /**
     * Entry point called from register.php on POST.
     * Returns: ['success' => bool, 'message' => string]
     */
    public function register(array $post): array {
        $type = trim($post['user_type'] ?? '');

        // ── 1. Shared field validation ──────────────────────────────
        $email            = trim($post['email'] ?? '');
        $password         = $post['password'] ?? '';
        $passwordConfirm  = $post['password_confirm'] ?? '';
        $privacyConsent   = $post['privacy_consent'] ?? '';

        if (empty($email) || empty($password) || empty($passwordConfirm)) {
            return $this->error('Täytä kaikki pakolliset kentät.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->error('Sähköpostiosoite ei ole kelvollinen.');
        }

        if (strlen($password) < 8) {
            return $this->error('Salasanan on oltava vähintään 8 merkkiä pitkä.');
        }

        if ($password !== $passwordConfirm) {
            return $this->error('Salasanat eivät täsmää.');
        }

        if ($privacyConsent !== '1') {
            return $this->error('Sinun on hyväksyttävä tietosuojaseloste.');
        }

        // ── 2. Type-specific field validation ───────────────────────
        if ($type === 'private') {
            $firstName = trim($post['first_name'] ?? '');
            $lastName  = trim($post['last_name']  ?? '');

            if (empty($firstName) || empty($lastName)) {
                return $this->error('Etunimi ja sukunimi ovat pakollisia.');
            }

        } elseif ($type === 'company') {
            $companyName    = trim($post['company_name']    ?? '');
            $businessId     = trim($post['business_id']     ?? '');
            $contactPerson  = trim($post['contact_person']  ?? '');
            $billingAddress = trim($post['billing_address'] ?? '');

            if (empty($companyName) || empty($businessId) ||
                empty($contactPerson) || empty($billingAddress)) {
                return $this->error('Täytä kaikki yrityksen pakolliset kentät.');
            }

        } else {
            return $this->error('Valitse rekisteröitymistyyppi.');
        }

        // ── 3. Check for duplicate email ────────────────────────────
        if ($this->userModel->findByEmail($email)) {
            return $this->error('Sähköpostiosoite on jo käytössä.');
        }

        // ── 4. Hash the password ─────────────────────────────────────
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // ── 5. Insert into DB via User model ─────────────────────────
        $phone = trim($post['phone'] ?? '');

        if ($type === 'private') {
            $success = $this->userModel->createPrivateUser([
                'email'         => $email,
                'password_hash' => $passwordHash,
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'phone'         => $phone,
            ]);
        } else {
            $success = $this->userModel->createCompanyUser([
                'email'           => $email,
                'password_hash'   => $passwordHash,
                'company_name'    => $companyName,
                'business_id'     => $businessId,
                'contact_person'  => $contactPerson,
                'billing_address' => $billingAddress,
                'phone'           => $phone,
            ]);
        }

        // ── 6. Return result ─────────────────────────────────────────
        if ($success) {
            return [
                'success' => true,
                'message' => 'Rekisteröityminen onnistui! Voit nyt kirjautua sisään.',
            ];
        }

        return $this->error('Rekisteröityminen epäonnistui. Yritä uudelleen.');
    }

    // ── Private helper ───────────────────────────────────────────────
    private function error(string $message): array {
        return ['success' => false, 'message' => $message];
    }
}
