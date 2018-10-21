<?php


if ( ! defined('BASEPATH')) exit('No direct script access allowed');  // prevent direct access to this file

class User {
    
   private $id;
   private $name;
   private $surname;
   private $gender;
   private $email;
   private $cell;
   private $password;
   private $avatar;
   private $status;
   private $dateCreated;
   
   function getId() {
       return $this->id;
   }

   function getName() {
       return $this->name;
   }

   function getSurname() {
       return $this->surname;
   }

   function getGender() {
       return $this->gender;
   }

   function getEmail() {
       return $this->email;
   }

   function getCell() {
       return $this->cell;
   }

   function getPassword() {
       return $this->password;
   }

   function getAvatar() {
       return $this->avatar;
   }

   function getStatus() {
       return $this->status;
   }

   function getDateCreated() {
       return $this->dateCreated;
   }

   function setId($id) {
       $this->id = $id;
   }

   function setName($name) {
       $this->name = $name;
   }

   function setSurname($surname) {
       $this->surname = $surname;
   }

   function setGender($gender) {
       $this->gender = $gender;
   }

   function setEmail($email) {
       $this->email = $email;
   }

   function setCell($cell) {
       $this->cell = $cell;
   }

   function setPassword($password) {
       $this->password = $password;
   }

   function setAvatar($avatar) {
       $this->avatar = $avatar;
   }

   function setStatus($status) {
       $this->status = $status;
   }

   function setDateCreated($dateCreated) {
       $this->dateCreated = $dateCreated;
   }


}

