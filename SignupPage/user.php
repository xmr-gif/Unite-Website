<?php

    class User{
        private $db ; 
        private $data ;
        private $errors = array();
        private static $fields = ['firstname','lastname','email','password'] ;

        public function __construct($post_data){
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

        public function validateForm(){
            foreach (self::$fields as $field){
                if(!array_key_exists($field, $this->data)){
                    trigger_error("$field is not present in data");
                    return ;
                }
            }
            $this->validateFirstName() ;
            $this->validateLastName() ;
            $this->validateEmail();
            $this->validatePassword();
            return $this->errors ;
        }

        private function validateFirstName(){
            $val = $this->data['firstname'];
            if(empty($val)){$this->addError('firstname','First Name cannot be empty');
            }else{
                if(!preg_match('/^[A-Za-z]{2,20}$/',$val)){
                    $this->addError('firstname','First Name must be 2-20 chars & alphabetic') ;
                }
            }

        }

        private function validateLastName(){
            $val = $this->data['lastname'];
            if(empty($val)){$this->addError('lastname','Last Name cannot be empty');
            }else{
                if(!preg_match('/^[A-Za-z]{2,20}$/',$val)){
                    $this->addError('lastname','Last Name must be 2-20 chars & alphabetic') ;
                }
            }

        }
        private function validateEmail() {
            $val = trim($this->data['email']);
            if(empty($val)) {
                $this->addError('email', 'Email cannot be empty');
            } else {
                // Fix: Add negation (!) before filter_var
                if(!filter_var($val, FILTER_VALIDATE_EMAIL)) {
                    $this->addError('email', 'Email must be a valid email address');
                }
            }
        }
        // private function validateEmail(){

        //     $val = trim($this->data['email']);
        //     if(empty($val)){$this->addError('email','Email cannot be empty');
        //     }else{
        //         if(filter_var($val, FILTER_VALIDATE_EMAIL)){
        //             $this->addError('email','Email must be a valid email') ;
        //         }
        //     }

        // }

        private function validatePassword(){

        }

        public function save($table) {
            try {
                $query = "INSERT INTO $table
                          (firstname, lastname, email, password)
                          VALUES (:firstname, :lastname, :email, :password)";

                $stmt = $this->db->prepare($query);

                // Hash password
                $hashedPassword = password_hash($this->data['password'], PASSWORD_DEFAULT);

                $stmt->execute([
                    ':firstname' => $this->data['firstname'],
                    ':lastname' => $this->data['lastname'],
                    ':email' => $this->data['email'],
                    ':password' => $hashedPassword
                ]);

                return true;

            } catch(PDOException $e) {
                $this->addError('database', 'Database error: ' . $e->getMessage());
                return false;
            }
        }

        private function addError($key,$value){
            $this->errors[$key] = $value ;
        }

        // public function signup($POST){
        //     foreach ($POST as $key => $value) {
        //         # code...
        //         if($key == "firstname"){
        //             if(trim($value) == ""){
        //                 $this->errors[] = "please enter a valid Name" ;
        //             }
        //         }
        //     }

        //     if(count($this->errors) == 0){

        //         // save on db

        //     }
        //     return $this->errors ;



        //}
    }


?>
