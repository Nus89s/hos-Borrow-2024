<?php
session_start();
if (!isset($_SESSION['user_id'])) { // ถ้าไม่ได้เข้าระบบอยู่
  header("location: login.php"); // redirect ไปยังหน้า login.php
  exit;
}
// ('login_action.php');

require_once('conn.php');
if (isset($_REQUEST['delete_id'])) {
  $id = $_REQUEST['delete_id'];

  $select_stmt = $db->prepare('SELECT * FROM approval WHERE approval_id = :id');
  $select_stmt->bindParam(':id', $id);
  $select_stmt->execute();
  $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
  unlink("docs/approval_in/" . $row['doc_in']); // unlin functoin permanently remove your file

  $delete_stmt = $db->prepare('DELETE FROM approval WHERE approval_id = :id');
  $delete_stmt->bindParam(':id', $id);
  $delete_stmt->execute();

  $delete_pr = $db->prepare('DELETE FROM proj_head WHERE approval_id = :id'); 
  $delete_pr->bindParam(':id', $id);
  $delete_pr->execute();
  $deleteMsg = "ลบข้อมูลสำเร็จ";
  
    header("refresh:5;index.php");

} //END delete

if (isset($_REQUEST['btn_cancel'])) {

  header("index.php");
}

function sendLineNotify($message)
{
  $token = "UZN9BjByH3jW049daiKbVWfFONhXURcwi5XyQI39PCj";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "message=" . $message);
  $headers = array('Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $token . '',);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($ch);
  if (curl_error($ch)) {
    echo 'error:' . curl_error($ch);
  } else {
    $res = json_decode($result, true);
    //  echo "status : " . $res['status'];
    // echo "message : " . $res['message'];
  }
  curl_close($ch);
} //END-Api_line


if (isset($_REQUEST['btn_insert'])) {

  //ประกาศตัวแปรรับค่าจากฟอร์ม
  $uid = $_SESSION['user_id'];
  $approval_in_name = $_REQUEST['approval_in_name'];
  $approval_in_date = $_REQUEST['approval_in_date'];
  $approval_number = $_REQUEST['approval_number'];
  $approval_date = $_REQUEST['approval_date'];
  $approval_name = $_REQUEST['approval_name'];
  $approval_type = $_REQUEST['approval_type'];
  $approval_organ = $_REQUEST['approval_organ'];
  $approval_addp = $_REQUEST['approval_addp'];
  $approval_fdate = $_REQUEST['approval_fdate'];
  $approval_edate = $_REQUEST['approval_edate'];
  $approval_sum = $_REQUEST['approval_sum'];
  $approval_numof = $_REQUEST['approval_numof'];
  $approval_self = $_REQUEST['approval_self'];
  // $approval_hsent = $_REQUEST['approval_hsent'];
  $approval_hsent = $_REQUEST['approval_hsent'];
  $approval_invite = $_REQUEST['approval_invite'];
  $approval_obj = $_REQUEST['approval_obj'];
  $approval_benf = $_REQUEST['approval_benf'];
  $approval_ex = $_REQUEST['approval_ex'];
  $approval_veh = $_REQUEST['approval_veh'];

  $doc_file = $_FILES['doc_file']['name'];
  $temp = $_FILES['doc_file']['tmp_name'];
  $date1 = date("Ymd_His");
  $numrand = (mt_rand());
  $typefile = strrchr($_FILES['doc_file']['name'], "."); //เอานามสกุลไฟล์

  $path = "docs/approval_in/" . $doc_file; // set upload folder path
  //$image_file = 'upload/'.$numrand.$date1.$typefile;
  $newname = 'doc_app' . $numrand . $date1 . $typefile;
  $path_copy = $path . $newname;

  if (empty($doc_file)) {
    $errorMsg = "กรุณา อัพโหลดไฟล์!!!!";
  } else if ($typefile == ".pdf") {
    if (!file_exists($path)) { // check file not exist in your upload folder path
      move_uploaded_file($temp, 'docs/approval_in/' . $newname); // move upload file temperory directory to your upload folder
    } else {
      $errorMsg = "File already exists... Check upload filder"; // error message file not exists your upload folder path
    }
  } else {
    $errorMsg = "กรุณาอัพโหลดไฟล์ที่มีนามสกุล.pdf";
    header("refresh:7;index.php");
  }


  //sql insert
  // $stmt = $db->prepare("INSERT INTO tbl_pdf (doc_name, doc_file)
  // VALUES (:doc_name, '$newname')");
  // $stmt->bindParam(':doc_name', $doc_name, PDO::PARAM_STR);.
  if (!isset($errorMsg)) {
    $insert_stmt = $db->prepare("INSERT INTO approval(user_id,approval_number,approval_in_name,approval_in_date, approval_date,approval_name,approval_type,approval_organ,approval_addp,approval_fdate,approval_edate,approval_sum,approval_numof,approval_self,approval_hsent,approval_invite,approval_obj,approval_benf,approval_ex,approval_veh,	doc_in) 
                               VALUES (:uid ,:approval_number,:approval_in_name,:approval_in_date, :approval_date, :approval_name, :approval_type, :approval_organ , :approval_addp,:approval_fdate, :approval_edate,:approval_sum,:approval_numof,:approval_self, :approval_hsent,:approval_invite,:approval_obj,:approval_benf,:approval_ex,:approval_veh,:doc_file)");

    $insert_stmt->bindParam(':uid', $uid);
    $insert_stmt->bindParam(':approval_number', $approval_number);
    $insert_stmt->bindParam(':approval_in_name', $approval_in_name);
    $insert_stmt->bindParam(':approval_in_date', $approval_in_date);
    $insert_stmt->bindParam(':approval_date', $approval_date);
    $insert_stmt->bindParam(':approval_name', $approval_name);
    $insert_stmt->bindParam(':approval_type', $approval_type);
    $insert_stmt->bindParam(':approval_organ', $approval_organ);
    $insert_stmt->bindParam(':approval_addp', $approval_addp);
    $insert_stmt->bindParam(':approval_fdate', $approval_fdate);
    $insert_stmt->bindParam(':approval_edate', $approval_edate);
    $insert_stmt->bindParam(':approval_sum', $approval_sum);
    $insert_stmt->bindParam(':approval_numof', $approval_numof);
    $insert_stmt->bindParam(':approval_self', $approval_self);
    $insert_stmt->bindParam(':approval_hsent', $approval_hsent);
    $insert_stmt->bindParam(':approval_invite', $approval_invite);
    $insert_stmt->bindParam(':approval_obj', $approval_obj);
    $insert_stmt->bindParam(':approval_benf', $approval_benf);
    $insert_stmt->bindParam(':approval_ex', $approval_ex);
    $insert_stmt->bindParam(':approval_veh', $approval_veh);
    $insert_stmt->bindParam(':doc_file', $newname);

    //$result = $stmt->execute();
    // $conn = null; //close connect db
    //เงื่อนไขตรวจสอบการเพิ่มข้อมูล
    if ($insert_stmt->execute()) {

      $lastID = $db->lastInsertId();

      //echo $lastID;

      $stmt2 = $db->prepare("INSERT INTO proj_head (prodate, user_id ,approval_id,approval_st)VALUES (:approval_date,$uid,$lastID, 'N')");
      //bindParam data type
      $stmt2->bindParam(':approval_date', $approval_date);
      if ($stmt2->execute()) {
        $insertMsg = "บันทึกเรียบร้อย";
        header('refresh:2;index.php');
      }
    }

    // header("refresh:2;index.php");
  }



  //header("refresh:2;index.php");
  // sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่เริ่ม: " . $approval_fdate . '  ' . "วันที่สิ้นสุด: " . $approval_edate);
} //END ----insert 




// if($date == $date ){
//   $date = date("Y-m-d H:i:s".strtotime("today 13:44"));
//   $select_stmt = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//   $select_stmt->execute();
//   while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//   sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

//    } }
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>ขออนุมัติโครงการ</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css");
  </style>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>


<body style="background-color: #FAFFFC;  ">


  <div class="w3-sidebar w3-bar-block w3-card w3-animate-left text-center" style="display:none" id="leftMenu">
    <button onclick="closeLeftMenu()" class="w3-bar-item w3-button w3-large">Close &times;</button>
    <hr>
    <a class="navbar-brand " href="index.php">HOME</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!--       
  <a href="#" class="w3-bar-item w3-button">Link 1</a>
  <a href="#" class="w3-bar-item w3-button">Link 2</a>
  <a href="#" class="w3-bar-item w3-button">Link 3</a> -->
    <ul class="nav nav-pills flex-column mb-0 align-items-center align-items-sm-start" id="menu">
      <!-- <li class="nav-item">
        <a href="#" class="nav-link align-middle px-0 text-white">
          <i class="bi bi-send-arrow-up-fill icon-side"></i> <span class="ms-1 d-none d-sm-inline">หนังสือส่งจังหวัด</span>
        </a>
      </li> -->
      <li class="nav-item ">
        <a href="list_borrow.php" class="nav-link align-middle px-0 text-white">
          <i class="bi bi-coin icon-side"></i> <span class="ms-1 d-none d-sm-inline">ยืมเงิน</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="list_sumresults.php" class="nav-link align-middle px-0 text-white">
          <i class="bi bi-card-list icon-side"></i> <span class="ms-1 d-none d-sm-inline">สรุปการอบรม</span>
        </a>
      </li>

      <!-- <li>
        <a href="#" class="nav-link px-0 align-middle">
          <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Orders</span></a>
      </li> -->
      <!-- <li class="nav-item ">
            <a class="nav-link px-0 align-middle " href="logout.php" tabindex="-1" aria-disabled="true" onclick="return confirm('ยืนยันการออกจากระบบ');">
            <i class="bi bi-box-arrow-left icon-side"></i>ออกจากระบบ</a>
          </li> -->
      <li class="nav-item">
        <a href="logout.php" aria-disabled="true" onclick="return confirm('ยืนยันการออกจากระบบ');" class="nav-link align-middle px-0 text-white">
          <i class="bi bi-box-arrow-left icon-side"></i> <span class="ms-1 d-none d-sm-inline">ออกจากระบบ</span>
        </a>
      </li>


    </ul>

    <script>
      function openLeftMenu() {
        document.getElementById("leftMenu").style.display = "block";
      }

      function closeLeftMenu() {
        document.getElementById("leftMenu").style.display = "none";
      }
    </script>

    <!-- <?php print_r($_SESSION); ?> -->

  </div>

  <button class="w3-button  w3-xlarge w3-left " style="background-color: #52baff; " onclick="openLeftMenu()">&#9776;</button>
  <div class=" p-3 text-center" style="background-color: #52baff; ">
    <h3>บันทึกขออนุมัติเข้ารับการฝึกอบรม</h3>
    <h5> สวัสดี<?= $_SESSION['f_name'] . ' ' . $_SESSION['l_name'];  ?></h5>




  </div>
  <!-- <h5>สวัสดี</h5>
<div class="dropdown">
   -->


  <!-- <input type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-search" type="button">
          <i class="fa fa-search "></i> Search</button>
        </span> -->

  <div class="container  ">
    <div class="row text-center">
      <div class="col">

        <h2></h2>
      </div>

    </div>
    <!-- Button trigger modal -->
    <div class="container text-center">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop4">
        เพิ่มโครงการ
      </button>

      <br>
      <br>
      <input id="myInput" type="text" placeholder="Search..">
      <hr>
      <!-- <div class="bg-light p-3 rounded mt-3">
        <h5 class="text-center">สวัสดีคุณ <?= $_SESSION['p_name'] . ' ' . $_SESSION['l_name']; ?></h5>
        <a href="logout.php" class="list-group-item list-group-item-danger" onclick="return confirm('ยืนยันการออกจากระบบ');">ออกจากระบบ</a>
      </div> -->



    </div>

    <?php
    if (isset($errorMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $errorMsg; ?>",
            type: "error"
          }, function() {
            window.location = "index.php"; 
          });
        }, 1000);
      </script>

    <?php
    } ?>

    <?php
    if (isset($insertMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $insertMsg; ?>",
            type: "success"
          }, function() {
            window.location = "index.php"; 
          });
        }, 1000);
      </script>

    <?php
    } ?>
    
    <?php
    if (isset($deleteMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $deleteMsg; ?>",
            type: "success"
          }, function() {
            window.location = "index.php"; 
          });
        }, 1000);
      </script>

    <?php
    } ?>


    <div class="input-group">


    </div>
    <!-- Modal -->
    <?php

    $uid1 = $_SESSION['user_id'];
    $select = $db->prepare("SELECT u.*, g.g_name FROM `user` u left join group_job g on g.g_id = u.g_id WHERE user_id = :uid1 ");
    $select->bindParam(':uid1', $uid1);
    $select->execute();

    $row = $select->fetch(PDO::FETCH_ASSOC)

    ?>

    <div class="modal fade " id="staticBackdrop4" tabindex="-1" aria-labelledby="exampleModalLabel4" aria-hidden="true">
      <div class="modal-dialog modal-xl ">
        <div class="modal-content " style="background-color:antiquewhite ">
          <div class="modal-header" style="background-color:burlywood ">
            <h5 class="modal-title">ขออนุมัติการเข้าฝึกอบรม</h5>
            <button type="button" class="btn-close" name="addpro" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body ">
            <form id="myform1" method="post" enctype="multipart/form-data">
              <!-- Name input -->
              <div class="row">
                <div class="col">
                  <label class="form-label" for="name4">ความหนังสือ</label>
                  <label class="form-text">(เลขหนังสือเชิญ)</label>
                  <input type="text" class="form-control" placeholder="" name="approval_number">
                </div>


                <div class="col">
                  <?php
                  $date = date("Y-m-d");

                  ?>
                  <label class="form-label" class="form-label" for="date">ลงวันที่</label>
                  <label class="form-text">(วันที่หนังสือเชิญ)</label>
                  <input class="form-control" id="date" name="approval_in_date" placeholder="MM/DD/YYY" type="date" required>
                </div>
                <!-- <div class="col">
                  <label for="firstname" class="form-label" for="date">ระหว่างวันที่</label>
                  <label class="form-text">(วันที่ดำเนินการ)</label>
                  <input class="form-control" id="date" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required>
                </div> -->
              </div>
              <div class="form-outline mb-4">
                <label class="form-label" for="name4">เรื่อง</label>
                <label class="form-text">(จากหนังสือเชิญ)</label>
                <input type="text" id="name4" name="approval_in_name" class="form-control" required>
              </div>
              <br>
              <h5 class="modal-title">ขออนุมัติการเข้าฝึกอบรม</h5>
              <hr>

              <div class="row">
                <div class="col">
                  <label class="form-label" for="name4">เขียนที่</label>
                  <input type="text" class="form-control" name="text1" readonly placeholder=<?php echo $row["g_name"]; ?>>
                </div>
                <div class="col">

                  <label class="form-label" for="name4">เรียน</label>
                  <input type="text" class="form-control" name="text1" readonly placeholder="ผู้อำนวยการโรงพยาบาลเทพา">
                </div>
              </div>
              <div class="row mt-2">
                <div class="col">
                  <label class="form-label" for="name4">ลงวันที่</label>
                  <input type="text" class="form-control" name="approval_date" value=<?php echo $date; ?> readonly placeholder="<?php echo $date; ?>">
                </div>
                <br>
                <div class="col">
                  <label for="group_job" class="control-label">ประเภท</label>
                  <label class="form-text">(ขอเข้าร่วม)</label>
                  <div class="col-sm-12">
                    <select name="approval_type" class="form-control">
                      <option value="" selected="selected">- กรุณาเลือกประเภท -</option>
                      <option value="ประชุม">ประชุม</option>
                      <option value="อบรม">อบรม</option>
                      <option value="สัมมนา">สัมมนาทางวิชาการ</option>
                    </select>
                  </div>
                </div>
              </div>
              <br>
              <div class="form-outline mb-4">
                <label class="form-label" for="name4">เรื่อง</label>
                <input type="text" id="name4" name="approval_name" class="form-control" required>
              </div>
              <div class="form-outline mb-4">
                <label class="form-label" for="name4">จัดโดย</label>
                <input type="text" id="name4" name="approval_organ" class="form-control" required>
              </div>
              <div class="form-outline mb-4">
                <label class="form-label" for="name4">สถานที่ดำเนินการ</label>
                <label class="form-text">(ไม่ต้องใส่ ณ )</label>
                <input type="text" id="name4" name="approval_addp" class="form-control" required>

              </div>
              <div class="row mt-2">
                <div class="col">
                  <label for="firstname" class="form-label" for="date">ระหว่างวันที่</label>
                  <label class="form-text">(วันที่ดำเนินการ)</label>
                  <input class="form-control" id="date" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required>
                </div>
                <div class="col">
                  <?php
                  $date = date("Y-m-d");

                  ?>
                  <label class="form-label" for="name4">ถึงวันที่</label>
                  <label class="form-text">(วันที่ดำเนินการ)</label>
                  <input class="form-control" id="date" name="approval_edate" placeholder="MM/DD/YYY" type="date" required>
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col">
                  <label class="form-label" for="name4">ค่าลงทะเบียน</label>
                  <label class="form-text">(บาท)</label>
                  <input type="text" class="form-control" name="approval_sum">
                </div>
                <div class="col">
                  <label class="form-label" for="name4">ครั้งที่</label>
                  <label class="form-text">(ครั้งที่เข้าร่วม)</label>
                  <input type="text" class="form-control" name="approval_numof" />
                </div>
                <div class="col">
                  <label for="approval_veh" class="form-label">เดินทางโดย</label>
                  <select name="approval_veh" class="form-control">
                    <option value="N" selected="selected">- กรุณาเลือก -</option>
                    <option value="เครื่องบินโดยสาร">เครื่องบินโดยสาร</option>
                    <option value="ยานพาหนะส่วนตัว">ยานพาหนะส่วนตัว</option>
                    <option value="รถจากหน่วยงานภายนอก">รถจากหน่วยงานภายนอก</option>
                    <option value="รถโรงพยาบาล">รถจากโรงพยาบาล</option>
                  </select>
                </div>
              </div>
              <div class="row mt-2">
                <div class="form-check">
                  <div class="col">
                    <label for="group_job" class="col control-label">ข้าพเจ้าขออนุมัติการเข้าเข้าร่วมเองโดยเป็นการเข้าร่วม</label>
                    <div class="col-sm-12">
                      <select name="approval_self" class="form-control">
                        <option value="N" selected="selected">- กรุณาเลือก -</option>
                        <option value="V">เชิงวิชาชีพ</option>
                        <option value="P">งานที่ต้องรับผิดชอบเพิ่มเติม</option>
                      </select>
                    </div>
                  </div>

                  <div class="col px-md-5">
                    <br>
                    <div class="form-check">
                      <input id='approval_hsentHidden' type='hidden' value='N' name='approval_hsent'>
                      <input class="form-check-input" type="checkbox" value="Y" id="flexCheckChecked" name="approval_hsent">
                      <label class="form-check-label" for="flexCheckChecked">
                        โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม
                      </label>
                    </div>
                    <div class="form-check">
                      <input id='approval_inviteHidden' type='hidden' value='N' name='approval_invite'>
                      <input class="form-check-input" type="checkbox" value="Y" id="flexCheckChecked" name="approval_invite">
                      <label class="form-check-label" for="flexCheckChecked">
                        ผู้จัดการอบรมมีหนังสือเชิญโดยระบุชื่อหรือตำแหน่งผู้เข้ารับการอบรมชัดเจน
                      </label>
                    </div>
                  </div>
                </div>
              </div>

              <br>
              <label class="form-label" for="textarea4">วัตถุประสงค์การเข้าร่วม</label>
              <textarea id="textarea3" rows="3" name="approval_obj" class="form-control" required></textarea>

              <br>
              <label class="form-label" for="textarea4">ประโยชน์ที่คาดว่าจะได้รับ</label>
              <textarea id="textarea3" rows="3" name="approval_benf" class="form-control" required></textarea>
              <br>

              <label class="form-label" for="textarea4">กิจกรรมที่คาดว่าจะสามารถดำเดินการได้ภายหลังเข้าร่วม</label>
              <textarea id="textarea3" rows="3" name="approval_ex" class="form-control" required></textarea>

              <br>
              <label class="form-label">อัพโหลดไฟล์หนังสือเชิญ</label><br>
              <font class="form-text" color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น </font>
              <input type="file" name="doc_file" required class="form-control" accept="application/pdf"> <br>


              <!-- Submit button -->

              <div class="form-group text-center">
                <div class="col-md-12 mt-3">
                  <input type="submit" name="btn_insert" class="btn btn-success" value="เพิ่ม">
                  <a href="index.php" class="btn btn-danger">ยกเลิก</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> <!-- END Modal -->



    <!--
        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal1">
        เพิ่มโครงการ
        </button>
        -->

    <table class="table table-striped thead-dark" name="from1">
      <thead class="thead-dark">
        <tr>
          <th scope="col">ลำดับ</th>
          <th scope="col">สถานะ</th>
          <th scope="col">ชื่อโครงการ</th>
          <th scope="col">วันที่เริ่ม</th>
          <th scope="col">วันที่สิ้นสุด</th>
          <th scope="col">ค่าลงทะเบียน</th>
          <th scope="col">สถานที่</th>
          <th scope="col">พิมพ์</th>
          <th scope="col">action</th>
        </tr>
      </thead>
      <tbody id="myTable">
        <?php
        $uid1 = $_SESSION['user_id'];

        $select_stmt = $db->prepare("SELECT ap.*, pr.* from approval ap left join proj_head pr on pr.approval_id = ap.approval_id where ap.user_id = :uid1");
        $select_stmt->bindParam(':uid1', $uid1);
        $select_stmt->execute();

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;


          // $ST = $row["approval_st"];

          if ($row["approval_st"] == 'N') {
            $str_sy = 'N';
          } else {
            $str_sy = 'Y';
          }

        ?>
          <tr>
            <td><?php echo $x ?></td>
            <td><?php if ($str_sy == 'N') {

                ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else { ?> <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?></td>
            <td><?php echo $row["approval_name"]; ?></td>
            <td><?php echo $row["approval_fdate"]; ?></td>
            <td><?php echo $row["approval_edate"]; ?></td>
            <td><?php echo $row["approval_sum"]; ?> บาท</td>
            <td><?php echo $row["approval_addp"]; ?></td>
            <td><?php if ($str_sy == 'Y') { ?><a target="_blank" href="print_approval.php?appid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red"></a>
              <?php } else { ?> <a href="?delete_id=<?php echo $row['approval_id']; ?>" class="bi bi-trash3-fill"></a> <?php } ?>
            </td>
            <td><?php if ($str_sy == 'N') { ?><a class="btn btn-warning btn-sm " href="editapp.php?approval_id=<?php echo $row['approval_id']; ?>" name="upd">แก้ไข
                <?php } else {  ?> <i class="bi bi-lock-fill icon-lock "><?php  } ?> </td>

            <!-- <td><a href="?delete_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger">ลบ</a></td> -->
          </tr>

        <?php  }  ?>
      </tbody>
    </table>
    <style>
      .icon-red {
        color: darkred;
        font-size: 20px;
      }

      .icon-green {
        color: green;
        font-size: 25px;
      }

      .icon-yellow {
        color: darkorange;
        font-size: 25px;
      }

      .icon-lock {
        color: red;
        font-size: 25px;
      }

      .w3-sidebar {
        position: fixed;
        top: 0px;
        bottom: 0;
        left: 0;
        z-index: 1000;
        padding: 20px;
        background-color: #6bc4ff;
        width: 220px;

      }
    </style>
    <script src="js/slim.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript">
      $(function() {
        $("#myform1").on("submit", function() {
          var form = $(this)[0];
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        });
      });

      $(document).ready(function() {
        $('#dtBasicExample').DataTable();
        $('.dataTables_length').addClass('bs-select');
      });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
      $(document).ready(function() {
        $("#myInput").on("keyup", function() {
          var value = $(this).val().toLowerCase();
          $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
          });
        });
      });
    </script>


</body>

</html>