<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="website icon" type="png" href="bor.png">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style type="text/css">
		body{
			font-family: 'Prompt', sans-serif;
		}
		title{
			font-family: 'Prompt', sans-serif;
		}
	</style>
  <title>Login</title>
</head>

<body class="body">
  
  <br>
  
  <div class="container mt-15">

    <div class="row">
      <div class="col-md-3"></div>
      <div class="col-md-8"> <br>
        <h4>เข้าสู่ระบบ</h4>
        <hr>
        <form action="" method="post">
          <div class="mb-2">
            <div class="col-sm-9">
              <input type="text" name="user_name" class="form-control" required minlength="3" placeholder="username">
            </div>
          </div>
          <div class="mb-3">
            <div class="col-sm-9">
              <input type="password" name="password" class="form-control" required minlength="3" placeholder="password">
            </div>

          </div>
          <!-- <div class="mb-3">
            <div class="col-sm-9">
              <select name="role" class="form-control">
                <option value="" selected="selected">-เลือกกลุ่มใช้งาน-</option>
                <option value="user">ผู้ใช้งานระบบ</option>
                <option value="admin">ผู้ดูแลระบบ</option>
              </select>
            </div>

          </div> -->
          <div class="d-grid gap-2 col-sm-9 mb-3">
            <button type="submit" class="btn btn-primary">Login</button>
            <div class="form-group text-center">
              <div class="col-sm-12 mt-3">
                <h8>คุณไม่มีบัญชีใช่ไหม?ลงทะเบียนที่นี่</h8>
                <p><a href="register.php">ลงทะเบียน</a></p>
              </div>
            </div>


          </div>
        </form>
      </div>
    </div>


  
  </div>
  </div>
</body>

</html>


<?php

//print_r($_POST); //ตรวจสอบมี input อะไรบ้าง และส่งอะไรมาบ้าง 
//ถ้ามีค่าส่งมาจากฟอร์ม
if (isset($_POST['user_name']) && isset($_POST['password']) ) {
  // sweet alert 
  echo '
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">';

  //ไฟล์เชื่อมต่อฐานข้อมูล
  require_once 'conn.php';
  //ประกาศตัวแปรรับค่าจากฟอร์ม
  $username = $_POST['user_name'];
  $password = $_POST['password'];
  //$role = $_POST['role'];

  //check username  & password
  $stmt = $db->prepare("SELECT * FROM user WHERE user_name = :user_name AND password = :password and status = 'user'");
  //$stmt->bindparam(':role', $role, PDO::PARAM_STR);
  $stmt->bindParam(':user_name', $username, PDO::PARAM_STR);
  $stmt->bindParam(':password', $password, PDO::PARAM_STR);
  $stmt->execute();


  if($stmt->rowCount() == 1){
    //fetch เพื่อเรียกคอลัมภ์ที่ต้องการไปสร้างตัวแปร session
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    //สร้างตัวแปร session
     $_SESSION['user_id'] = $row['user_id'];
     $_SESSION['f_name'] = $row['f_name'];
     $_SESSION['l_name'] = $row['l_name'];

    //เช็คว่ามีตัวแปร session อะไรบ้าง
    //print_r($_SESSION);

   // exit();

      header('Location: user/index.php'); 

    } else {
      $stmt = $db->prepare("SELECT * FROM user WHERE user_name = :user_name AND password = :password and status = 'admin'");
      //$stmt->bindparam(':role', $role, PDO::PARAM_STR);
      $stmt->bindParam(':user_name', $username, PDO::PARAM_STR);
      $stmt->bindParam(':password', $password, PDO::PARAM_STR);
      $stmt->execute();
    
    
      if($stmt->rowCount() == 1){
        //fetch เพื่อเรียกคอลัมภ์ที่ต้องการไปสร้างตัวแปร session
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //สร้างตัวแปร session
         $_SESSION['user_id'] = $row['user_id'];
         $_SESSION['f_name'] = $row['f_name'];
         $_SESSION['l_name'] = $row['l_name'];



         header('Location: admin/dash.php'); 

        }

      $_SESSION['error'] = "Wrong user or password or role";
  ?>
                    <script>
                      setTimeout(function() {
                        swal({
                          title: "Username หรือ Password ไม่ถูกต้อง ลองใหม่อีกครั้ง",
                          type: "warning",
                          timer: 2000,
                        }, function() {
                          window.location = "login.php"; 
                        });
                      }, 1000);
                    </script>
              
                  <?php
                 
      //header("location: login.php");
    }
   // print_r($_SESSION);
  }
  


?>
<style>
  .body {
    min-width: 100%;
    min-height: 100vh;
    background-color: #FF3CAC;
    background-image: linear-gradient(200deg, #FF3CAC 0%, #784BA0 50%, #2B86C5 100%);
    background-repeat: no-repeat;
    background-repeat: no-repeat;
    background-size: cover;

  }

  .container {
    position: absolute;
    top: 40%;
    left: 20%;
    margin-top: -100px;
    margin-left: -100px;
  }
</style>