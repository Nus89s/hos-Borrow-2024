<?php 
session_start();
if (!isset($_SESSION['user_id'])) { // ถ้าไม่ได้เข้าระบบอยู่
  header("location: login.php"); // redirect ไปยังหน้า login.php
  exit;
}

?>