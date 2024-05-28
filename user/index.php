<title>บันทึกโครงการ</title>
<?php

include 'header.php';
include 'nav.php';
include 'sidebar.php';
if (isset($_REQUEST['delete_id'])) {
  $id = $_REQUEST['delete_id'];

  $select_stmt = $db->prepare('SELECT * FROM approval WHERE approval_id = :id');
  $select_stmt->bindParam(':id', $id);
  $select_stmt->execute();
  $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
  unlink("../docs/approval_in/" . $row['doc_in']); // unlin functoin permanently remove your file

  $delete_stmt = $db->prepare('DELETE FROM approval WHERE approval_id = :id');
  $delete_stmt->bindParam(':id', $id);
  $delete_stmt->execute();

  $delete_pr = $db->prepare('DELETE FROM proj_head WHERE approval_id = :id');
  $delete_pr->bindParam(':id', $id);
  $delete_pr->execute();
  $deleteMsg = "ลบข้อมูลสำเร็จ";

  //header("refresh:5;index.php");
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

function fiscalYear($date) {
  // วันที่ที่ต้องการตรวจสอบ
  list($year, $month, $day) = explode("-", $date);
  // วันที่ที่ส่งมา (mktime)
  $cday = mktime(0, 0, 0, $month, $day, $year);
  // ปีงบประมาณตามค่าที่ส่งมา (mktime)
  $d1 = mktime(0, 0, 0, 10, 1, $year );
  // ปีใหม่
  $d2 = mktime(0, 0, 0, 9, 30, $year + 1);
  if ($cday >= $d1 && $cday < $d2) {
    // 1 ตค. -  ธค.
$year++;
  }
  $year+=543;
  //echo "$date = $year <br>";
  echo $year;
 //return $year;
}


if (isset($_REQUEST['btn_insert'])) {

  //รับค่าจากฟอร์ม
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
  //$approval_hsent = $_REQUEST['approval_hsent'];
  //$approval_invite = $_REQUEST['approval_invite'];
  $approval_obj = $_REQUEST['approval_obj'];
  $approval_benf = $_REQUEST['approval_benf'];
  $approval_ex = $_REQUEST['approval_ex'];
  $approval_veh = $_REQUEST['approval_veh'];
   //$fiscalYear = fiscalYear($appoval_fdate); 
   
  //$fiscalYear =  fiscalYear($approval_fdate);
  list($year, $month, $day) = explode("-", $approval_fdate);
  // วันที่ที่ส่งมา (mktime)
  $cday = mktime(0, 0, 0, $month, $day, $year);
  // ปีงบประมาณตามค่าที่ส่งมา (mktime)
  $d1 = mktime(0, 0, 0, 10, 1, $year );
  // ปีใหม่
  $d2 = mktime(0, 0, 0, 9, 30, $year + 1);
  if ($cday >= $d1 && $cday < $d2) {
    // 1 ตค. -  ธค.
$year++;
  }
  $year+=543;
  //echo "$date = $year <br>";
  $fiscalYear = $year;
  //$r = $this->fiscalYear($approval_fdate);
  

  

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
      move_uploaded_file($temp, '../docs/approval_in/' . $newname); // move upload file temperory directory to your upload folder
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
    $insert_stmt = $db->prepare("INSERT INTO approval(user_id,approval_number,approval_in_name,approval_in_date, approval_date,approval_name,approval_type,approval_organ,approval_addp,approval_fdate,approval_edate,approval_sum,approval_numof,approval_self,approval_obj,approval_benf,approval_ex,approval_veh,	doc_in,fiscalYear) 
                                   VALUES (:uid ,:approval_number,:approval_in_name,:approval_in_date, :approval_date, :approval_name, :approval_type, :approval_organ , :approval_addp,:approval_fdate, :approval_edate,:approval_sum,:approval_numof,:approval_self,:approval_obj,:approval_benf,:approval_ex,:approval_veh,:doc_file,:fiscalYear)");

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
    // $insert_stmt->bindParam(':approval_hsent', $approval_hsent);
    // $insert_stmt->bindParam(':approval_invite', $approval_invite);
    $insert_stmt->bindParam(':approval_obj', $approval_obj);
    $insert_stmt->bindParam(':approval_benf', $approval_benf);
    $insert_stmt->bindParam(':approval_ex', $approval_ex);
    $insert_stmt->bindParam(':approval_veh', $approval_veh);
    $insert_stmt->bindParam(':doc_file', $newname);
    $insert_stmt->bindParam(':fiscalYear', $fiscalYear);

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
        //header('refresh:2;index.php');
      }
    }

    // header("refresh:2;index.php");
  }



  //header("refresh:2;index.php");
  // sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่เริ่ม: " . $approval_fdate . '  ' . "วันที่สิ้นสุด: " . $approval_edate);
} //END ----insert 





?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">

    </div>
  </div>

  <section class="content">

    <div class="container text-center">
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop4">
        เพิ่มโครงการ
      </button>
    </div>
    <br>

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
     $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
     $stmtMax->execute();
     $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
     $sM = $sMax['mx'];

    $uid1 = $_SESSION['user_id'];
    $select = $db->prepare("SELECT u.*, g.g_name FROM `user` u left join group_job g on g.g_id = u.g_id WHERE user_id = :uid1  ");
    $select->bindParam(':uid1', $uid1);
    $select->execute();

    $row = $select->fetch(PDO::FETCH_ASSOC);

    $sel = $db->prepare("select COUNT(approval_id) c from approval where user_id = :uid1 and fiscalYear in ('$sM') ");
    $sel->bindParam(':uid1', $uid1);
    $sel->execute();

    $row1 = $sel->fetch(PDO::FETCH_ASSOC);

    ?>

    <div class="modal fade " id="staticBackdrop4" tabindex="-1" aria-labelledby="exampleModalLabel4" aria-hidden="true">
      <div class="modal-dialog modal-xl ">
        <div class="modal-content " style="background-color:antiquewhite ">
          <div class="modal-header" style="background-color:burlywood ">
            <h5 class="modal-title">ขออนุมัติการเข้าฝึกอบรม</h5>
            <button type="button" class="btn-close" name="addpro" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body ">
            <form id="myform1" method="post" enctype="multipart/form-data" class="was-validated">
              <!-- Name input -->
              <div class="row">
                <div class="col">
                  <label class="form-label" for="name4">ความหนังสือ</label>
                  <span>(เลขหนังสือเชิญ)</span>
                  <input type="text" class="form-control" name="approval_number" required>
                </div>


                <div class="col">
                  <?php
                  $date = date("Y-m-d");

                  ?>
                  <label class="form-label" class="form-label" for="date">ลงวันที่</label>
                  <span>(วันที่หนังสือเชิญ)</span>
                  <input class="form-control" id="date" name="approval_in_date" placeholder="MM/DD/YYY" type="date" required>
                </div>
                <!-- <div class="col">
                  <label for="firstname" class="form-label" for="date">ระหว่างวันที่</label>
                  <label class="form-text">(วันที่ดำเนินการ)</label>
                  <input class="form-control" id="date" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required>
                </div> -->
              </div>
              <br>
              <div class="form-outline mb-4">
                <label class="form-label" for="name4">เรื่อง</label>
                <span>(จากหนังสือเชิญ)</span>
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
                  <span>(ขอเข้าร่วม)</span>
                  <div class="col-sm-12">
                    <select name="approval_type" class="form-control" required>
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
                <!-- <font class="form-text" color="red">* </font> -->
                <input type="text" id="name4" name="approval_name" class="form-control is-invalid" placeholder="ขออนุมัติอบรม" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" required>
                <div id="validationServerUsernameFeedback" class="invalid-feedback">

                </div>
              </div>



              <!-- <div class="row">
                <div class="col-md-4">
                    <label for="validationCustom01" class="form-label">ชื่อ</label>
                    <input type="text" class="form-control" id="validationCustom01" required minlength="3" placeholder="ชื่อ">
                    <div class="invalid-feedback">
                        ห้ามว่าง และขั้นต่ำ 3 ตัวอักษร
                    </div>
                </div>
            </div> -->



              <div class="form-outline mb-4">
                <label class="form-label" for="name4">จัดโดย</label>
                <input type="text" id="name4" name="approval_organ" class="form-control" required>
              </div>
              <div class="form-outline mb-4">
                <label class="form-label" for="name4">สถานที่ดำเนินการ</label>
                <span>(ไม่ต้องใส่ ณ )</span>
                <input type="text" id="name4" name="approval_addp" class="form-control" required>

              </div>
              <div class="row mt-2">
                <div class="col">
                  <label for="firstname" class="form-label" for="date">ระหว่างวันที่</label>
                  <span>(วันที่ดำเนินการ)</span>
                  <input class="form-control" id="date" name="approval_fdate" placeholder="MM/DD/YYY" type="date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="col">
                  <?php
                  $date = date("Y-m-d");

                  ?>
                  <label class="form-label" for="name4">ถึงวันที่</label>
                  <span>(วันที่ดำเนินการ)</span>
                  <input class="form-control" id="date" name="approval_edate" placeholder="MM/DD/YYY" type="date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
              </div>
              <hr>

              <div class="row">
                <div class="col">
                  <label class="form-label" for="name4">ค่าลงทะเบียน</label>
                  <span>(บาท) </span>
                  
                  <input type="text" class="form-control" name="approval_sum"   placeholder="ไม่มีให้ใส่ 0" required>
                </div>
                <div class="col">
                  <label class="form-label" for="name4">ครั้งที่</label>
                  <span>(ครั้งที่เข้าร่วมต่อปีงบประมาณ)</span>
                  <input type="text" class="form-control" name="approval_numof" readonly placeholder="<?php echo (($row1['c']) + 1); ?>" value="<?php echo (($row1['c']) + 1); ?>" required>
                </div>
                <div class="col">
                  <label for="approval_veh" class="form-label">เดินทางโดย</label>

                  <select name="approval_veh" class="form-control" required>
                    <option value="" selected="selected">- กรุณาเลือก -</option>
                    <option value="เครื่องบิน">เครื่องบิน</option>
                    <option value="ยานพาหนะส่วนตัว">ยานพาหนะส่วนตัว</option>
                    <option value="รถจากหน่วยงานภายนอก">รถจากหน่วยงานภายนอก</option>
                    <option value="รถโรงพยาบาล">รถจากโรงพยาบาล</option>
                  </select>
                </div>
              </div>
              <br>
              <hr>


              <!-- <input id='approval_selftHidden' type='hidden' value='N' name='approval_hsent'>
                    <select name="approval_self" class="form-control mb-4"  >
                      <option value="N" selected="selected">- กรุณาเลือก -</option>
                      <option value="V">เชิงวิชาชีพ</option>
                      <option value="P">งานที่ต้องรับผิดชอบเพิ่มเติม</option>
                    </select> -->



              <div class="row ">
                <div class="form-check">
                  <div class="form-outline mb-4">



                    <label for="group_job" class="col control-label">ข้าพเจ้าขออนุมัติการเข้าเข้าร่วมเองโดยเป็นการเข้าร่วม <span class="required" color="red">*</span></label>




                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <!-- <input id='approval_selftHidden' type='hidden' value='N' name='approval_self'> -->
                    <input class="form-check-input" type="radio" value="V" id="radioCheck" name="approval_self" required>
                    <label class="form-check-label" for="radioCheck">

                    </label>

                    เชิงวิชาชีพ
                    <br>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <!-- <input id='approval_selfHidden' type='hidden' value='N' name='approval_self'> -->
                    <input class="form-check-input" type="radio" value="P" id="radioCheck" name="approval_self" required>
                    <label class="form-check-label" for="radioCheck">

                    </label>
                    งานที่ต้องรับผิดชอบเพิ่มเติม
                  </div>
                </div>

                <div class="col px-md-2">
                  <br>
                  <div class="form-check">
                    <!-- <input id='approval_hsentHidden' type='hidden' value='N' name='approval_hsent'> -->
                    <input class="form-check-input" type="radio" value="H" id="radioCheck" name="approval_self" required>
                    <label class="form-check-label" for="radioCheck">

                    </label>
                    <label class="form-label">
                      โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม
                    </label>

                  </div>
                  <div class="form-check">
                    <!-- <input id='approval_inviteHidden' type='hidden' value='N' name='approval_invite'> -->
                    <input class="form-check-input" type="radio" value="B" id="radioCheck" name="approval_self" required>
                    <label class="form-check-label" for="radioCheck">
                    </label>
                    <label class="form_label">
                      ผู้จัดการอบรมมีหนังสือเชิญโดยระบุชื่อหรือตำแหน่งผู้เข้ารับการอบรมชัดเจน
                    </label>


                    <!-- <input type="radio" class="form-check-input" id="validationFormCheck2" name="radio-stacked" required>
                    <label class="form-check-label" for="validationFormCheck2"></label>
                    เชิงวิชาชีพ
                  </div>

                  <div class="form-check">
                    <input type="radio" class="form-check-input" id="validationFormCheck2" name="radio-stacked" required>
                    <label class="form-check-label" for="validationFormCheck2"></label>
                    งานที่ต้องรับผิดชอบเพิ่มเติม

                  
                    <div class="col px-md-2">
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="validationFormCheck2" name="radio-stacked" required>
                        <label class="form-label" for="validationFormCheck2">โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม</label>
                      </div>
                      <div class="form-check">
                        <input type="radio" class="form-check-input" id="validationFormCheck2" name="radio-stacked" required>
                        <label class="form-label" for="validationFormCheck2">ผู้จัดการอบรมมีหนังสือเชิญโดยระบุชื่อหรือตำแหน่งผู้เข้ารับการอบรมชัดเจน</label>
                      </div>
                    </div> -->

                  </div>
                </div>

              </div>


              <hr>
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
              <label class="form-label">อัพโหลดไฟล์หนังสือเชิญ</label>
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
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">บันทึกโครงการ</h3>
      </div>
      <!-- /.card-header -->
      
      <div class="card-body">
        <?php 
        $stmt = $db->prepare("SELECT fiscalYear FROM approval  GROUP by fiscalYear");
        $stmt->execute();
        $rs = $stmt->fetchAll();
        ?>
        
        <form class="form-inline" action="" method="post">

          
            <div class="float-right">
              <select class="form-control mr-sm-2" name="filter" required>
                <option value=""> เลือกปีงบประมาณ</option>
                <?php foreach ($rs as $row) { ?>
                  <option value="<?= $row['fiscalYear']; ?>"><?= $row['fiscalYear']; ?></option>
                <?php } ?>
                <!-- <option value=""></option> -->
              </select>
            
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-secondary">เลือก</button>
          </div>
          <!-- <div class="d-grid">
                        <?php if (isset($_POST['filter'])) { ?>
                            <h5>ตรวจสอบมี input อะไรบ้าง และส่งอะไรมาบ้าง</h5>
                            <?= print_r($_POST); ?>
                        <?php } ?>
                    </div> -->

      
        </form>

        <table id="Table" class="table table-striped thead-dark" name="from1">
          <thead class="table-info">
            <tr>
              <th scope="col" width="5%">ลำดับ</th>
              <th scope="col" width="5%">สถานะ</th>
              <th scope="col" width="40%">ชื่อโครงการ</th>
              <th scope="col" width="10%">วันที่เริ่ม</th>
              <!-- <th scope="col"width="10%">วันที่สิ้นสุด</th> -->
              <th scope="col" width="10%">ค่าลงทะเบียน</th>
              <th scope="col" width="20%">สถานที่</th>
              <th scope="col" width="5%">พิมพ์</th>
              <th scope="col" width="5%">action</th>
            </tr>
          </thead>
          <tbody id="myTable">
            <?php

              if (isset($_POST['filter'])) {

                $D = $_POST['filter']; 
                //echo  $D;
                $uid1 = $_SESSION['user_id'];

                $select_stmt = $db->prepare("SELECT ap.*, pr.* from approval ap left join proj_head pr on pr.approval_id = ap.approval_id where ap.user_id = :uid1  
                                and ap.fiscalYear in ('$D') order by ap.approval_id DESC");
                $select_stmt->bindParam(':uid1', $uid1);
                $select_stmt->execute();
              } else {

              $uid1 = $_SESSION['user_id'];
              $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
              $stmtMax->execute();
              $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
              $sM = $sMax['mx'];
    
                $select_stmt = $db->prepare("SELECT ap.*, pr.* from approval ap left join proj_head pr on pr.approval_id = ap.approval_id where ap.user_id = :uid1 and ap.fiscalYear in ('$sM') order by ap.approval_id DESC");
                $select_stmt->bindParam(':uid1', $uid1);
                $select_stmt->execute();
              }

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
                <td><?php echo $row["approval_fdate"]; ?> </td>
                <!-- <?php echo $row["approval_edate"]; ?> -->

                <td><?php echo $row["approval_sum"]; ?> บาท</td>
                <td><?php echo $row["approval_addp"]; ?></td>
                <td><?php if ($str_sy == 'Y') { ?><a target="_blank" href="../print_approval.php?appid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red"></a>
                  <?php } else { ?> 
                    <!-- <a href="?delete_id=<?php echo $row['approval_id']; ?>" class="bi bi-trash3-fill"></a>  -->
                    <a class="bi bi-trash3-fill" data-bs-toggle='modal' data-bs-target='#delete_<?php echo $row['approval_id']; ?>' >
                    <?php } ?>
                </td>
                <div class="modal fade" id="delete_<?php echo $row['approval_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-ml" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <label class="modal-title" id="exampleModalLabel">คุณต้องการอกกลบรายการ ?</label>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div class="row-">
                          <div class="col">
                          <label class="form-label" for="name4">ชื่อโครงการ</label>
                            <!-- <span>(เลขหนังสือเชิญ)</span> -->
                            <input type="text" value="<?php echo $row['approval_name']; ?>" class="form-control" placeholder="" name="approval_name" readonly>
                          </div>
                        </div>
                        <br>
                        <div class="row px-md-2">
                          <div class="col">
                          <label class="form-label" for="name4">วันที่เริ่ม</label>
                            <!-- <span>(เลขหนังสือเชิญ)</span> -->
                            <input type="text" value="<?php echo $row['approval_fdate']; ?>" class="form-control" placeholder="" name="approval_fdate" readonly>
                          </div>
                        
                          <div class="col">
                          <label class="form-label" for="name4">ค่าลงทะเบียน</label>
                            <!-- <span>(เลขหนังสือเชิญ)</span> -->
                            <input type="text" value="<?php echo $row['approval_sum']; ?>" class="form-control" placeholder="" name="approval_sum" readonly>
                          </div>
                          </div>
                        
                        
                      </div>
                      <div class="modal-footer">

                        <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
                        <a type="button" href="?delete_id=<?php echo $row['approval_id']; ?>" class="btn btn-danger  btn-sm">ลบ</a>
                      </div>
                    </div>
                  </div>
                </div>

                <td><?php if ($str_sy == 'N') { ?><a class="btn btn-warning btn-sm " href="editapp.php?approval_id=<?php echo $row['approval_id']; ?>" name="upd">แก้ไข
                    <?php } else {  ?> <i class="bi bi-lock-fill icon-lock "><?php  } ?> </td>

                <!-- <td><a href="?delete_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger">ลบ</a></td> -->
              </tr>

            <?php  }  ?>

          </tbody>

        </table>
      </div>
    </div>

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

  </section>

</div>


</html>
<script>
  // Example starter JavaScript for disabling form submissions if there are invalid fields
  (() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })
  })()
</script>

<script type="text/javascript">
  $(document).ready(function() {
    var minDate = new Date();
    $("#s_date").datepicker({
      showAnim: 'drop',
      numberOfMonth: 1,
      minDate: minDate,
      dateFormat: 'yy-mm-dd',
      onClose: function(selectedDate) {
        $('#e_date').datepicker("option", "minDate", selectedDate);
      }
    });
    $(".e_date").datepicker({
      showAnim: 'drop',
      numberOfMonth: 1,
      minDate: minDate,
      dateFormat: 'yy-mm-dd',
    });
  });
</script>
<?php
include 'footer.php'
?>