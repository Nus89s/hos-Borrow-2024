<?php


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

  $insert_stmt = $db->prepare("INSERT INTO approval(user_id,approval_number,approval_in_name,approval_in_date, approval_date,approval_name,approval_type,approval_organ,approval_addp,approval_fdate,approval_edate,approval_sum,approval_numof,approval_self,approval_hsent,approval_invite,approval_obj,approval_benf,approval_ex) 
                               VALUES (:uid ,:approval_number,:approval_in_name,:approval_in_date, :approval_date, :approval_name, :approval_type, :approval_organ , :approval_addp,:approval_fdate, :approval_edate,:approval_sum,:approval_numof,:approval_self, :approval_hsent,:approval_invite,:approval_obj,:approval_benf,:approval_ex)");

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
  if ($insert_stmt->execute()) {

    $lastID = $db->lastInsertId();

    //echo $lastID;

    $stmt2 = $db->prepare("INSERT INTO proj_head (prodate, user_id ,approval_id,approval_st)VALUES (:approval_date,$uid,$lastID, 'N')");
    //bindParam data type
    $stmt2->bindParam(':approval_date', $approval_date);
    $result2 = $stmt2->execute();


    header("refresh:2;index.php");
  }
  sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่เริ่ม: " . $approval_fdate . '  ' . "วันที่สิ้นสุด: " . $approval_edate );
} //END ----insert 




if (isset($_REQUEST['upscbor'])){

  $borrow_staff = $_SESSION['user_id'];
  $approval_id = $_REQUEST['approval_id'];
  $doc_bor = $_FILES['scanbr']['name'];
    $temp = $_FILES['scanbr']['tmp_name'];
    $typefile = strrchr($_FILES['scanbr']['name'], "."); //เอานามสกุลไฟล์
    $date1 = date("Ymd_His");
    $numrand = (mt_rand());

    $path = "docs/bor_doc/" . $doc_bor; // set upload folder path
    //$image_file = 'upload/'.$numrand.$date1.$typefile;
    $newname = 'scanbr' . $numrand . $date1 . $typefile;
    $path_copy = $path . $newname;

    if (empty($doc_bor)) {
        $errorMsg = "กรุณา อัพโหลดไฟล์!!!!";
    } else if ($typefile == ".pdf") {
        if (!file_exists($path)) { // check file not exist in your upload folder path
            move_uploaded_file($temp, '../docs/bor_doc/' . $newname); // move upload file temperory directory to your upload folder
        } else {
            $errorMsg = "File already exists... Check upload filder"; // error message file not exists your upload folder path
        }
    } else {
        $errorMsg = "กรุณาอัพโหลดไฟล์ที่มีนามสกุล.pdf";
        header("refresh:2;borrow.php");
    }



    if (!isset($errorMsg)) {
      $sql = $db->prepare("UPDATE borrow SET borrow_staff = :borrow_staff , borrow_doc = :borrow_doc WHERE approval_id = :approval_id");

      $sql->bindParam(":approval_id", $approval_id);
      $sql->bindParam(':borrow_staff', $borrow_staff);
      $sql->bindParam(':borrow_doc', $newname);
      $sql->execute();

      $conscanMsg = "บันทึกเรียบร้อย";

    }

} //up scan PDF 

// if($date == $date ){
//   $date = date("Y-m-d H:i:s".strtotime("today 13:44"));
//   $select_stmt = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//   $select_stmt->execute();
//   while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//   sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

//    } }


if (isset($_REQUEST['adduserline'])){
  $uid = $_REQUEST['user_id'];
  $lineuser = $_REQUEST['lineuser'];

  $adline = $db->prepare("UPDATE `user` SET `line_user_id` = :lineuser  WHERE `user_id` = :uid;");
  $adline->bindParam(':uid',$uid);
  $adline->bindParam(':lineuser',$lineuser);
  
  if ($line = $adline->execute()){

    $lineMsg = "บันทึกเรียบร้อย";
  }

}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

    <div class="container text-center">
  
    <h3><label class="form-text">ยืนยันการยืมเงิน</label></h3>
    </div>
    <br>

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

<div class="card">
              <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">
              <?php
    if (isset($lineMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $lineMsg; ?>",
            type: "success"
          }, function() {
            window.location = "borrow.php"; 
          });
        }, 1000);
      </script>

    <?php
    }     
 
    if(isset($conscanMsg)){ ?>
   <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $conscanMsg; ?>",
            type: "success"
          }, function() {
            window.location = "borrow.php"; 
          });
        }, 1000);
      </script>

<?php 
    }
   
        $stmt = $db->prepare("SELECT fiscalYear FROM approval  GROUP by fiscalYear");
        $stmt->execute();
        $rs = $stmt->fetchAll();

        
        $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
        $stmtMax->execute();
        $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
        $sM = $sMax['mx'];

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
          <th scope="col">ลำดับ</th>
          <th scope="col">สถานะ</th>
          <th scope="col">ชื่อโครงการ</th>
          <th scope="col">ชื่อผู้ขอ</th>
          <th scope="col">วันที่เริ่ม</th>
          <th scope="col">วันที่สิ้นสุด</th>
          <th scope="col">ค่าลงทะเบียน</th>
          <th scope="col">สถานที่</th>
          <th scope="col">พิมพ์</th>
          <th scope="col">ตัวเลือก</th>
        </tr>
      </thead>
      <tbody id="myTable">
        <?php

        if (isset($_POST['filter'])) {

          $D = $_POST['filter'];
          //echo  $D;
          $uid1 = $_SESSION['user_id'];

          $select_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* ,br.* from  borrow br
          left join approval ap on ap.approval_id = br.approval_id
          left join proj_head pr on pr.approval_id = ap.approval_id 
          left join user u on u.user_id = ap.user_id where  pr.borrow_st in ('W','Y') and ap.fiscalYear in ('$D') order by ap.approval_id DESC");
          $select_stmt->bindParam(':uid1', $uid1);
          $select_stmt->execute();
        } else {


          $uid1 = $_SESSION['user_id'];

          $select_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* ,br.* from  borrow br
                                        left join approval ap on ap.approval_id = br.approval_id
                                        left join proj_head pr on pr.approval_id = ap.approval_id 
                                        left join user u on u.user_id = ap.user_id where  pr.borrow_st in ('W','Y') and ap.fiscalYear in ('$sM') order by ap.approval_id DESC");
          $select_stmt->bindParam(':uid1', $uid1);
          $select_stmt->execute();
        }

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;


          // $ST = $row["approval_st"];

          if ($row["borrow_st"] == 'W') {
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
            <td><?php echo $row["f_name"];
                echo "&nbsp;" . $row["l_name"]; ?></td>
            <td><?php echo $row["approval_fdate"]; ?></td>
            <td><?php echo $row["approval_edate"]; ?></td>
            <td><?php echo $row["approval_sum"]; ?> บาท</td>
            <td><?php echo $row["approval_addp"]; ?></td>
            <?php if ($row["line_user_id"] != "")   { ?>
            <td> 
              <!-- <a target="_blank" href="../print_borrow.php?borid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red"></a> -->
              <?php if ($row['borrow_doc'] !='' ){?><a href="../docs/bor_doc/<?php echo $row['borrow_doc'];?>" target="_blank"  class="bi bi-eye-fill"><?php }?>
              <a target="_blank" href="../print_borrow1.php?borid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red"></a></td>
            <td class = "text-center" ><?php if ($str_sy == 'N') { ?><a class="btn btn-warning btn-sm "  href="conborrow.php?approval_id=<?php echo $row['approval_id']; ?>" name = "conborrow">ตรวจสอบ
                <?php } else {  ?> <a class="bi bi-pencil-fill icon-pen" data-bs-toggle='modal' data-bs-target='#scanbr<?php echo $row['approval_id']; ?>' name="#" >
                   <?php  } ?> <i class="bi bi-lock-fill icon-lock "></td>

            <!-- <td><a href="?delete_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger">ลบ</a></td> -->
          </tr>

          <?php 
               include 'modal.php';
               ?>

        <?php  } else { ?> 
          <td> <a class="bi bi-bag-plus-fill icon-green" data-bs-toggle='modal' data-bs-target='#edit_<?php echo $row['user_id']; ?>'> </td>
          <div class="modal fade" id="edit_<?php echo $row['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">เพิ่ม lineuser</h5>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                               <form enctype="multipart/form-data" method="POST" action="?adduserline=<?php echo $row["user_id"]; ?>">
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ชื่อ-สกุล</span>
                                                        <input type="text" class="form-control" name="user_name" required readonly value=<?php echo $row["f_name"];
                                                                                                                                            echo "&nbsp;" . $row["l_name"]; ?>>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>เบอร์โทรศัพท์</span>
                                                        <input type="text" class="form-control" name="tel" required readonly value=<?php echo $row["tel"];  ?>>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>lineuser</span>
                                                        <input type="text" class="form-control" name="lineuser" required value=<?php echo $row["line_user_id"];  ?>>

                                                    </div>
                                                </div>                       
                                                <br>
                                                <div class="modal-footer">
                                                  
                                                    <input type="hidden" name="user_id" value="<?= $row['user_id']; ?>">
                                                    <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
                                                    <input type="submit" name="adduserline" class="btn btn-primary btn-sm" value="บันทึก">
                                                    <!-- <a type="submit" name="adduserline" class="btn btn-danger  btn-sm">ตกลง</a> -->
                                                </div>
                                                <!-- href="?adduserline=<?php echo $row["user_id"]; ?>" -->
                                              </form>
                                            </div>
                                        </div>
                                    </div>
          <td>  </td> <?php }?>
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
      .icon-pen{
        
        font-size: 20px;
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
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


</html>