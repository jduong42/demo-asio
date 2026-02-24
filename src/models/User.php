<?php
/**
 * User Model
 * Handles all database operations for the users, private_profiles
 * and company_profiles tables. No business logic here — only SQL.
 */
class User {

    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /**
     * Find a user by email address.
     * Used to check for duplicates before registration.
     */
    public function findByEmail(string $email): ?array {
        $stmt = $this->conn->prepare(
            "SELECT id, email FROM users WHERE email = ? LIMIT 1"
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    /**
     * Insert a private person.
     * Runs two inserts inside a transaction:
     *   1. users  (base row)
     *   2. private_profiles (profile row)
     * Rolls back both if either fails.
     */
    public function createPrivateUser(array $data): bool {
        $this->conn->begin_transaction();

        try {
            // 1. Insert base user row
            $stmt = $this->conn->prepare(
                "INSERT INTO users (user_type, email, password, privacy_consent)
                 VALUES ('private', ?, ?, 1)"
            );
            $stmt->bind_param('ss', $data['email'], $data['password_hash']);
            $stmt->execute();
            $userId = $this->conn->insert_id;
            $stmt->close();

            // 2. Insert private profile
            $stmt = $this->conn->prepare(
                "INSERT INTO private_profiles (user_id, first_name, last_name, phone)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param(
                'isss',
                $userId,
                $data['first_name'],
                $data['last_name'],
                $data['phone']
            );
            $stmt->execute();
            $stmt->close();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    /**
     * Insert a company user.
     * Same transaction approach as createPrivateUser.
     */
    public function createCompanyUser(array $data): bool {
        $this->conn->begin_transaction();

        try {
            // 1. Insert base user row
            $stmt = $this->conn->prepare(
                "INSERT INTO users (user_type, email, password, privacy_consent)
                 VALUES ('company', ?, ?, 1)"
            );
            $stmt->bind_param('ss', $data['email'], $data['password_hash']);
            $stmt->execute();
            $userId = $this->conn->insert_id;
            $stmt->close();

            // 2. Insert company profile
            $stmt = $this->conn->prepare(
                "INSERT INTO company_profiles
                    (user_id, company_name, business_id, contact_person, billing_address, phone)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param(
                'isssss',
                $userId,
                $data['company_name'],
                $data['business_id'],
                $data['contact_person'],
                $data['billing_address'],
                $data['phone']
            );
            $stmt->execute();
            $stmt->close();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
