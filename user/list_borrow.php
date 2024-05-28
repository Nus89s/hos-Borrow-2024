<title>บันทึกขออนุมัติยืมเงิน</title>
<?php

include 'header.php';
include 'nav.php';
include 'sidebar.php';

if (isset($_REQUEST['delete_id'])) {
  $id = $_REQUEST['delete_id'];
  $delete_stmt = $db->prepare('DELETE FROM proj WHERE id = :id');
  $delete_stmt->bindParam(':id', $id);
  $delete_stmt->execute();

  header('Location:index.php');
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
  sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่เริ่ม: " . $approval_fdate . '  ' . "วันที่สิ้นสุด: " . $approval_edate);
} //END ----insert 

if (isset($_REQUEST['upbo'])) {
  $approval_id = $_REQUEST['approval_id'];
  $borrow_date = $_REQUEST['borrow_date'];
  // $borrow_sum = $_REQUEST['borrow_sum'];
  $borrow_allw = $_REQUEST['borrow_allw'];
  $borrow_accom = $_REQUEST['borrow_accom'];
  $borrow_veh = $_REQUEST['borrow_veh'];
  $borrow_regis = $_REQUEST['borrow_regis'];
  $borrow_reward = $_REQUEST['borrow_reward'];
  $borrow_com = $_REQUEST['borrow_com'];
  $borrow_another = $_REQUEST['borrow_another'];
  // $borrow_turn_haft = $_REQUEST['borrow_turn_haft'];
  // $borrow_turn_month = $_REQUEST['borrow_turn_month'];

  $sum = $borrow_allw + $borrow_accom + $borrow_veh + $borrow_regis + $borrow_reward + $borrow_another;


  $sql = $db->prepare("UPDATE borrow SET borrow_date = :borrow_date,borrow_date = :borrow_date ,borrow_sum = :sum ,
                            borrow_allw = :borrow_allw,borrow_accom = :borrow_accom,borrow_veh = :borrow_veh,
                            borrow_regis = :borrow_regis,borrow_reward = :borrow_reward,borrow_com = :borrow_com,borrow_another = :borrow_another
                            WHERE approval_id = :approval_id");


  $sql->bindParam(":approval_id", $approval_id);
  $sql->bindParam(":borrow_date", $borrow_date);
  // $sql->bindParam(":borrow_sum", $borrow_sum);
  $sql->bindParam(":borrow_allw", $borrow_allw);
  $sql->bindParam(':borrow_accom', $borrow_accom);
  $sql->bindParam(':borrow_veh', $borrow_veh);
  $sql->bindParam(':borrow_regis', $borrow_regis);
  $sql->bindParam(':borrow_reward', $borrow_reward);
  $sql->bindParam(':borrow_com', $borrow_com);
  $sql->bindParam(':borrow_another', $borrow_another);
  $sql->bindParam(':sum', $sum);

  // $sql->bindParam(':borrow_turn_haft', $borrow_turn_haft);
  // $sql->bindParam(':borrow_turn_month', $borrow_turn_month);
  if ($sql->execute()) {


    $approval_id = $_REQUEST['approval_id'];
    $upbor = $db->prepare("SELECT * from borrow where approval_id = :approval_id ");
    $upbor->bindParam(":approval_id", $approval_id);
    $upbor->execute();
    $upbor_id = $upbor->fetch(PDO::FETCH_ASSOC);
    $borrow_id = $upbor_id['borrow_id'];
    $approval_id = $upbor_id['approval_id'];

    $con_b = $db->prepare("UPDATE `proj_head` SET `borrow_st` = 'W'   WHERE `approval_id` = :apprid;");
    $con_b->bindParam(':apprid', $approval_id);
    $con_b->execute();
    $bormesg = "บันทึกข้อมูลสำเร็จ";
    //header("refresh:5;list_borrow.php");
  }



  // $upborow = $db->prepare("UPDATE `proj_head` SET `borrow_id` = ':borrow_id'  WHERE `approval_id` = :approval_id;");
  // $upborow->bindParam(":borrow_id", $borrow_id);
  // $upborow->bindParam(":approval_id", $approval_id);
  // $upbor->execute();


  // if ($sql) {
  //     $_SESSION['success'] = "Data has been updated successfully";
  //     header("location: list_borrow.php");
  // } else {
  //     $_SESSION['error'] = "Data has not been updated successfully";
  //header("location: list_borrow.php");
  // }
}



// if($date == $date ){
//   $date = date("Y-m-d H:i:s".strtotime("today 13:44"));
//   $select_stmt = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//   $select_stmt->execute();
//   while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//   sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

//    } }
?>



  <div class="content-wrapper">

    <div class="content-header">
      <div class="container-fluid">
        
      </div>
    </div>

    <section class="content">

    <div class="container text-center">
    <h4><label class="form-text">บันทึกขออนุมัติยืมเงิน</label></h4>
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
    if (isset($bormesg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $bormesg; ?>",
            type: "success"
          }, function() {
            window.location = "list_borrow.php"; 
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

<div class="card">
              <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
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
<table id="Table" class="table table-striped " name="from1">
      <thead class="table-info">
        <tr>
          <th scope="col">ลำดับ</th>
          <th scope="col">สถานะ</th>
          <th scope="col">ชื่อโครงการ</th>
          <!-- <th scope="col">วันที่เริ่ม</th>
          <th scope="col">วันที่สิ้นสุด</th> -->
          <th scope="col">จำนวนเงินยืม</th>
          <th scope="col">สถานที่</th>
          <th scope="col">พิมพ์</th>
          <th scope="col">action</th>
        </tr>
      </thead>
      <tbody id="myTable">
        <?php
            if (isset($_POST['filter'])) {

              $D = $_POST['filter']; 
              //echo  $D;
              $uid1 = $_SESSION['user_id'];

              $select_stmt = $db->prepare("SELECT ap.*, pr.*, b.* ,(b.borrow_allw + b.borrow_accom + b.borrow_veh + b.borrow_regis + b.borrow_reward + b.borrow_another ) as s
              from approval ap 
              left join proj_head pr on pr.approval_id = ap.approval_id 
              left join borrow b on b.approval_id = ap.approval_id 
              where ap.user_id = :uid1 and pr.appr_st = 'Y' and pr.borrow_st in ('N','Y','W') and ap.fiscalYear in ('$D') order by ap.approval_id DESC");
              $select_stmt->bindParam(':uid1', $uid1);
              $select_stmt->execute();
            } else {



              $uid1 = $_SESSION['user_id'];
              
                    $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
                    $stmtMax->execute();
                    $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
                    $sM = $sMax['mx'];
      
              $select_stmt = $db->prepare("SELECT ap.*, pr.*, b.* ,(b.borrow_allw + b.borrow_accom + b.borrow_veh + b.borrow_regis + b.borrow_reward + b.borrow_another ) as s
              from approval ap 
              left join proj_head pr on pr.approval_id = ap.approval_id 
              left join borrow b on b.approval_id = ap.approval_id 
              where ap.user_id = :uid1 and pr.appr_st = 'Y' and pr.borrow_st in ('N','Y','W') and ap.fiscalYear in ('$sM') order by ap.approval_id DESC");
              $select_stmt->bindParam(':uid1', $uid1);
              $select_stmt->execute();
            }

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;

          if ($row["borrow_st"] == 'N') {
            $str_sy = 'N';
          } else if ($row["borrow_st"] == 'W'){
            $str_sy = 'W';
          }else  if ($row["borrow_st"] == 'Y'){
            $str_sy = 'Y';

          }

          if ($row["borrow_edate"] == ''){
            $bow_sum = 'Y';
          }else{
            $bow_sum = 'N';
          }
          

        ?>
          <tr>
            <td><?php echo $x ?></td>
            <td><?php if ($str_sy == 'N'){
              
              ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-red"></i><?php } else if ($row["borrow_st"] =='W') {?> <i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else {?>
              <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?>
             
             </td>
            <td><?php echo $row["approval_name"]; ?></td>
            <!-- <td><?php echo $row["approval_fdate"]; ?></td>
            <td><?php echo $row["approval_edate"]; ?></td> -->
            <td><?php if ($str_sy != 'N'){  echo $row["s"]; ?> บาท<?php } ?></td>
            <td><?php echo $row["approval_addp"]; ?></td>
            <td> <?php if ($str_sy == 'Y' ){
              ?> <a href="../docs/bor_doc/<?php echo $row['borrow_doc'];?>" target="_blank"  class="bi bi-eye-fill"></a>
            <?php }?>
          </td>
              <!-- <a target="_blank" href="print_conborrow.php?borid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red"></a> -->
            <td><?php if ($str_sy == 'N' ) { ?><a class="btn btn-info btn-sm " href="add_borrow.php?approval_id=<?php echo $row['approval_id']; ?>" name = "addborrow">ขอยืมเงิน
                <?php } else if ($row["borrow_st"] =='W') { ?>
                  <!-- <a class="btn btn-info btn-sm " href="add_borrow.php?approval_id=<?php echo $row['approval_id']; ?>" name = "addborrow">แก้ไข -->
                  <a class="btn btn-warning btn-sm " data-bs-toggle='modal' data-bs-target='#edbr_<?php echo $row['approval_id']; ?>' name="#">แก้ไข

               <?php }else{  ?> <i class="bi bi-lock-fill icon-lock "><?php  } ?>

            </td>
            <?php include '../admin/modal.php'; ?>

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
    <!-- /.content -->
  </div>
 
</html>
<?php 
include 'footer.php'
?>