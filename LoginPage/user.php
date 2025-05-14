<?php
session_start();
class LoginUser {
    private $db;
    private $data;
    private $errors = array();
    private static $fields = ['AccountType', 'email', 'password'];

    public function __construct($post_data) {
        $this->data = $post_data;
        try {
            $dsn = "mysql:host=" . DB_HOST . ";port=3306;dbname=" . DB_NAME . ";charset=utf8";
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function validateForm() {
        foreach (self::$fields as $field) {
            if (!array_key_exists($field, $this->data)) {
                trigger_error("$field is not present in data");
                return;
            }
        }

        $this->validateAccountType();
        $this->validateEmail();
        $this->validatePassword();
        return $this->errors;
    }

    private function validateAccountType() {
        $val = $this->data['AccountType'];
        if (!in_array($val, ['student', 'professor'])) {
            $this->addError('AccountType', 'Invalid account type');
        }
    }

    private function validateEmail() {
        $val = trim($this->data['email']);
        if (empty($val)) {
            $this->addError('email', 'Email cannot be empty');
        } else {
            if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                $this->addError('email', 'Email must be a valid email address');
            }
        }
    }

    private function validatePassword() {
        $val = $this->data['password'];
        if (empty($val)) {
            $this->addError('password', 'Password cannot be empty');
        }
    }

    public function authenticate() {
        $accountType = $this->data['AccountType'];
        $table = ($accountType === 'professor') ? 'Professeur' : 'Etudiant';
        $idField = ($accountType === 'professor') ? 'ID_Professeur' : 'ID_Etudiant';

        try {
            $query = "SELECT $idField, Nom, Prenom, Email, Mdp FROM $table WHERE Email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $this->data['email']]);
            $user = $stmt->fetch();

            if ($user && password_verify($this->data['password'], $user['Mdp'])) {
                $_SESSION['account_type'] = ($accountType === 'professor') ? 'Professeur' : 'Etudiant';
                $_SESSION['user_id'] = $user[$idField];
                $_SESSION['Prenom'] = $user['Prenom'];
                $_SESSION['Nom'] = $user['Nom'];
                $_SESSION['Email'] = $user['Email'];

                //doubt
                if ($_SESSION['account_type'] === 'Etudiant') {
                    $_SESSION['Etudiant_id'] = $user['ID_Etudiant'];
                }
                return true;
            } else {
                $this->addError('email', 'Invalid email or password for the selected account type');
                return false;
            }
        } catch(PDOException $e) {
            $this->addError('database', 'Database error: ' . $e->getMessage());
            return false;
        }
    }

    private function addError($key, $value) {
        $this->errors[$key] = $value;
    }

    public function getErrors() {
        return $this->errors;
    }
}
?>
