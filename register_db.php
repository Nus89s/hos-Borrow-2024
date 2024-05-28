<?php 

require_once "conn.php";


   session_start();

    if (isset($_POST['btn_register'])) {
       
        $username = $_POST['txt_username'];
        $cid = $_POST['txt_cid'];
        $fname = $_POST['txt_fname'];
        $lname = $_POST['txt_lname'];
        $tel = $_POST['txt_tel'];
        $job = $_POST['txt_job'];
        $group_job = $_POST['txt_group_job'];
        $position = $_POST['position'];
        $ms_status = $_POST['txt_ms_status'];
        $rg = $_POST['txt_rg'];
        $f_date = $_POST['f_date'];
        $birthday = $_POST['birthday'];
        $addr = $_POST['addr'];
        $password = $_POST['txt_password'];

        if (empty($username)) {
            echo  "กรุณาป้อนชื่อผู้ใช้";
        } else if (empty($cid)) {
            $errorMsg[] = "กรุณาป้อนเลขบัตรประชาชน";
        } else if (empty($fname)) {
            $errorMsg[] = "กรุณาป้อนชื่อจริง";
        } else if (empty($lname)) {
            $errorMsg[] = "กรุณาป้อนนามสกุล";
        } else if (empty($job)) {
            $errorMsg[] = "กรุณาป้อนตำแหน่งงาน/สายงาน";
        } else if (empty($group_job)) {
            $errorMsg[] = "กรุณาป้อนสังกัดกลุ่มงาน/ฝ่ายงาน ";
        } else if (empty($ms_status)) {
            $errorMsg[] = "กรุณาป้อนสถานภาพ";
        } else if (empty($rg)) {
            $errorMsg[] = "กรุณาป้อนศาสนา";
        } else if (empty($f_date)) {
            $errorMsg[] = "กรุณาป้อนวันที่เริ่มทำงาน";
        } else if (empty($birthday)) {
            $errorMsg[] = "กรุณาป้อนวันเกิด";
        } else if (empty($addr)) {
            $errorMsg[] = "กรุณาป้อนที่อยู่";
        } else if (empty($password)) {
            $errorMsg[] = "กรุณาป้อนรหัสผ่าน";
        }


        else  
        {
            
                    $insert_stmt = $db->prepare("INSERT INTO user(user_name, cid, f_name, l_name, tel, job_id, g_id, pos_id, ms_id, re_id, f_job_date, birthday, addpt, password,status) 
                                                 VALUES (:username, :cid, :fname, :lname, :tel, :job, :group_job, :position, :ms_status, :rg, :f_date, :birthday,:addr,:password,'user')");
                    $insert_stmt->bindParam(":username", $username);
                    $insert_stmt->bindParam(":cid", $cid);
                    $insert_stmt->bindParam(":fname", $fname);
                    $insert_stmt->bindParam(":lname", $lname);   
                    $insert_stmt->bindParam(":tel", $tel);
                    $insert_stmt->bindParam(":job", $job);
                    $insert_stmt->bindParam(":group_job", $group_job); 
                    $insert_stmt->bindParam(":position",  $position);
                    $insert_stmt->bindParam(":ms_status", $ms_status);
                    $insert_stmt->bindParam(":rg", $rg);
                    $insert_stmt->bindParam(":f_date", $f_date);
                    $insert_stmt->bindParam(":birthday", $birthday);
                    $insert_stmt->bindParam(":addr", $addr);
                    $insert_stmt->bindParam(":password", $password);
                    // header('Location:login.php');
    if ($insert_stmt->execute()) {
      header("refresh:2;login.php");
    }
                
            
        }
    
    }
