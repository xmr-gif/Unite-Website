<?php
class LoginUser {
    private $db;
    private $data;
    private $errors = array();
    private static $fields = ['AccountType', 'email', 'password'];

    public function __construct($post_data) {
        $this->data = $post_data;
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8";
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
        $table = $accountType . 's'; // Assumes table names are 'students' and 'professors'

        try {
            $query = "SELECT * FROM $table WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':email' => $this->data['email']]);
            $user = $stmt->fetch();

            if ($user && password_verify($this->data['password'], $user['password'])) {
                session_start();
                $_SESSION['user'] = $user;
                return true;
            } else {
                $this->addError('email', 'Invalid email or password');
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


session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config.php'; // Include your DB configuration

    $loginUser = new LoginUser($_POST);
    $errors = $loginUser->validateForm();

    if (empty($errors)) {
        if ($loginUser->authenticate()) {
            echo "sucess";
            // header('Location: dashboard.php'); // Redirect to dashboard
            // exit();
        }
        $errors = $loginUser->getErrors();
    }
}
?>
