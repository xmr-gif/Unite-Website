<?php

    class User{
        private $db ;
        private $data ;
        private $errors = array();
        private static $fields = ['Prenom','Nom','Email','Mdp'] ;

        public function __construct($post_data){
            $this->data = $post_data;
            try {
            $dsn = "mysql:host=" . DB_HOST . ";port=3307;dbname=" . DB_NAME . ";charset=utf8";
            $this->db = new PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
        }

        public function validateForm(){
            foreach (self::$fields as $field){
                if(!array_key_exists($field, $this->data)){
                    trigger_error("$field is not present in data");
                    return ;
                }
            }
            $this->validatePrenom() ;
            $this->validateNom() ;
            $this->validateEmail();
            $this->validateMdp();
            return $this->errors ;
        }

        private function validatePrenom(){
            $val = $this->data['Prenom'];
            if(empty($val)){$this->addError('Prenom','First Name cannot be empty');
            }else{
                if(!preg_match('/^[A-Za-z]{2,20}$/',$val)){
                    $this->addError('Prenom','First Name must be 2-20 chars & alphabetic') ;
                }
            }

        }

        private function validateNom(){
            $val = $this->data['Nom'];
            if(empty($val)){$this->addError('Nom','Last Name cannot be empty');
            }else{
                if(!preg_match('/^[A-Za-z]{2,20}$/',$val)){
                    $this->addError('Nom','Last Name must be 2-20 chars & alphabetic') ;
                }
            }

        }
        private function validateEmail() {
            $val = trim($this->data['Email']);
            if(empty($val)) {
                $this->addError('Email', 'Email cannot be empty');
            } else {
                if(!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $this->addError('Email', 'Email must be a valid email address');
                }
            }
        }

        private function validateMdp(){
            $val = $this->data['Mdp'];
            if(empty($val)){$this->addError('Mdp','Password cannot be empty');
            }
            // You might want to add more password validation rules here
        }

        public function save($table) {
            try {
                if ($table === 'Professeur') {
                    $query = "INSERT INTO Professeur
                                    (Nom, Prenom, Email, Mdp, DateRegistration)
                                    VALUES (:Nom, :Prenom, :Email, :Mdp, NOW())";
                } elseif ($table === 'Etudiant') {
                    $query = "INSERT INTO Etudiant
                                    (Nom, Prenom, Email, Mdp, DateRegistration)
                                    VALUES (:Nom, :Prenom, :Email, :Mdp, NOW())";
                } else {
                    // Handle invalid table name
                    return false;
                }

                $stmt = $this->db->prepare($query);

                // Hash password
                $hashedPassword = password_hash($this->data['Mdp'], PASSWORD_DEFAULT);

                $stmt->execute([
                    ':Prenom' => $this->data['Prenom'],
                    ':Nom' => $this->data['Nom'],
                    ':Email' => $this->data['Email'],
                    ':Mdp' => $hashedPassword
                ]);

                // return true;
                return $this->db->lastInsertId();

            } catch(PDOException $e) {
                $this->addError('database', 'Database error: ' . $e->getMessage());
                return false;
            }
        }

        public function addError($key,$value){
            $this->errors[$key] = $value ;
        }
    }


?>
