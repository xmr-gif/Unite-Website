<?php

    class User{

        private $data ;
        private $errors = array();
        private static $fields = ['firstname','lastname','email','password'] ;

        public function __construct($post_data){
            $this->data = $post_data;
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

        private function validateEmail(){

            $val = trim($this->data['email']);
            if(empty($val)){$this->addError('email','Email cannot be empty');
            }else{
                if(filter_var($val, FILTER_VALIDATE_EMAIL)){
                    $this->addError('email','Email must be a valid email') ;
                }
            }

        }

        private function validatePassword(){

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
