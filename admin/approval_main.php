  <?php 
  require_once('../conn.php');
  if (isset($_REQUEST['uppvdate'])) {

    $apprid = $_REQUEST['appr_id'];
    //$conapp = $_REQUEST['appid'];
     $appr_number = $_REQUEST['appr_number'];
     $appr_cdate = $_REQUEST["appr_cdate"];
    $u_apprv = $db->prepare("UPDATE `proj_head` SET `appr_st` = 'W'  WHERE `appr_id` = :apprid;");
    $u_apprv->bindParam(':apprid',$apprid);
    //$u_apprv->bindParam(':appid',$conapp);
    
    $upapprv = $u_apprv->execute();
      $apprid = $_REQUEST['appr_id'];
      $appr_number = $_REQUEST['appr_number'];
      $appr_cdate = $_REQUEST["appr_cdate"];
      $stm_pv = $db->prepare("UPDATE  approval_prov SET appr_number = :appr_number ,appr_cdate = :appr_cdate , appr_status = 'W' WHERE `appr_id` = :apprid;");
      $stm_pv->bindParam(':apprid',$apprid);
      $stm_pv->bindParam(':appr_number',$appr_number);
      $stm_pv->bindParam(':appr_cdate',$appr_cdate);

    if ($restm_pv = $stm_pv->execute()); {
        

        $apprid = $_REQUEST['appr_id'];
        $appr_number = $_REQUEST['appr_number'];
        $appr_cdate = $_REQUEST["appr_cdate"];
        $stm_pv = $db->prepare("UPDATE  approval_prov SET appr_number = :appr_number ,appr_cdate = :appr_cdate WHERE `appr_id` = :apprid;");
        $stm_pv->bindParam(':apprid', $apprid);
        $stm_pv->bindParam(':appr_number', $appr_number);
        $stm_pv->bindParam(':appr_cdate', $appr_cdate);
        $restm_pv = $stm_pv->execute();


        $selectbo = $db->prepare("select * from proj_head where `appr_id` = :apprid");
        $selectbo->bindParam(':apprid', $apprid);
       // $selectbo->execute();
        //$rowbo = $selectbo->fetch(PDO::FETCH_ASSOC);
        $selectbo->execute();
        $row = $selectbo->fetch(PDO::FETCH_ASSOC);
        $uid = $row['user_id'];
        $conapp = $row['approval_id'];
        // echo $uid;
        // echo 1;
        $inbo = $db->prepare("INSERT INTO borrow (user_id ,approval_id) VALUES (:user_id,:appid)");
        $inbo->bindParam(':user_id', $uid);
        $inbo->bindParam(':appid', $conapp);
        // $inbo->execute();

        if ($result2 = $inbo->execute()){
            $lastID = $db->lastInsertId();
    
          //echo $lastID;
      
          $uapid = $db->prepare("UPDATE proj_head SET  borrow_id = $lastID WHERE approval_id = $conapp");
          //bindParam data type
          if($uapid->execute()){
            //$conpvMsg = "บันทึกข้อมูลสำเร็จ";
            //header('refresh:2;approval_ prov.php');
          }
         
         }


        
    }
    
  $apprid = $_REQUEST['appr_id'];
  $conpvMsg = "บันทึกข้อมูลสำเร็จ";
  include 'nofyconapp.php';
  //header('refresh:2;apppv.php');

  }

  if (isset($_REQUEST['upscanpv'])) {

    $apprid = $_REQUEST['scanpv_id'];
    //$conapp = $_REQUEST['appid'];
    // $appr_number = $_REQUEST['appr_number'];
    // $appr_cdate = $_REQUEST["appr_cdate"];
    $u_apprv = $db->prepare("UPDATE `proj_head` SET `appr_st` = 'Y' ,	borrow_st = 'N' WHERE `appr_id` = :apprid;");
    $u_apprv->bindParam(':apprid',$apprid);
    //$u_apprv->bindParam(':appid',$conapp);
    $upapprv = $u_apprv->execute();

    $scanpv_file = $_FILES['scanpv']['name'];
    $temp = $_FILES['scanpv']['tmp_name'];
    $typefile = strrchr($_FILES['scanpv']['name'],"."); //เอานามสกุลไฟล์
    $date1 = date("Ymd_His");
    $numrand = (mt_rand());

    $path = "docs/app_prov/con_pv/" . $scanpv_file; // set upload folder path
    //$image_file = 'upload/'.$numrand.$date1.$typefile;
    $newname = 'doc_prov' . $numrand . $date1 . $typefile;
    $path_copy = $path . $newname;

   if (empty($scanpv_file)) {
        $errorMsg = "กรุณา อัพโหลดไฟล์!!!!";
    } else if ($typefile == ".pdf") {
        if (!file_exists($path)) { // check file not exist in your upload folder path
                move_uploaded_file($temp, '../docs/app_prov/con_pv/'.$newname); // move upload file temperory directory to your upload folder
        } else {
            $errorMsg = "File already exists... Check upload filder"; // error message file not exists your upload folder path
        }
    } else {
        $errorMsg = "กรุณาอัพโหลดไฟล์ที่มีนามสกุล.pdf";
        header("refresh:2;index.php"); 
    }

    if (!isset($errorMsg)) {
      $apprid = $_REQUEST['scanpv_id'];
      $stm_pv = $db->prepare("UPDATE  approval_prov SET doc_pv = :scanpv_file ,appr_status = 'Y' WHERE `appr_id` = :apprid;");
      $stm_pv->bindParam(':apprid',$apprid);
      $stm_pv->bindParam(':scanpv_file',$newname);

     $restm_pv = $stm_pv->execute();

     
     //header('refresh:2;approval_ prov.php');

        // $selectbo = $db->prepare("select * from proj_head where `appr_id` = $apprid");
        // $selectbo->execute();
        // $rowbo = $selectbo->fetch(PDO::FETCH_ASSOC);
  
        // $uid = $rowbo['user_id'];
        // $conapp = $rowbo['approval_id'];
        // $inbo = $db->prepare("INSERT INTO borrow (user_id ,approval_id) VALUES (:user_id,:appid)");
        // $inbo->bindParam(':user_id', $uid);
        // $inbo->bindParam(':appid', $conapp);
        // $sebo = $inbo->execute();


        //header('Location: approval_ prov.php');
     
    }
    $apprid = $_REQUEST['scanpv_id'];
    include 'nofyconapp.php';
    $insertMsg = "บันทึกเรียบร้อย";

  }

  ?>
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
  
    <h3><label class="form-text">อนุมัติหนังสือส่งจังหวัด</label></h3>
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
              <?php 
            if(isset($conpvMsg)) {
        ?>
            <script>
                     setTimeout(function() {
                      swal({
                          title: "<?php echo $conpvMsg; ?>",
                          type: "success"
                      }, function() {
                          window.location = "apppv.php"; 
                      });
                    }, 1000);
                </script>
           
        <?php } ?>
    <table id="Table" class="table table-striped " name="from1">
      <thead class="table-info">
        <tr class="text-center">
          <th scope="col">#</th>
          <th scope="col">สถานะ</th>
          <th scope="col">ชื่อโครงการ</th>
          <th scope="col">ชื่อผู้ขอ</th>
          <th scope="col">เลขที่ขออนุมัติ</th>
          <th scope="col">เลขที่หนังสือจังหวัด</th>
          <!-- <th scope="col">สถานที่</th> -->
          <th scope="col">พิมพ์</th>
          <th scope="col">action</th>
          <th scope="col">แก้ไข</th>

        </tr>
      </thead>
      <tbody id="myTable">
        <?php
            //print_r($_POST);
            if (isset($_POST['filter'])) {

              $D = $_POST['filter']; 
              //echo  $D;
              $uid1 = $_SESSION['user_id'];

              $select_stmt = $db->prepare("SELECT * from approval_prov pv
              left join approval ap on ap.approval_id = pv.approval_id
              left join proj_head pr on pr.approval_id = ap.approval_id
              left join user u on u.user_id = pv.user_id where ap.fiscalYear in ('$D') ORDER by pv.appr_id DESC");

              $select_stmt->execute();
            } else { 
              $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
              $stmtMax->execute();
              $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
              $sM = $sMax['mx'];


              $uid1 = $_SESSION['user_id'];
      
              $select_stmt = $db->prepare("SELECT * from approval_prov pv
              left join approval ap on ap.approval_id = pv.approval_id
              left join proj_head pr on pr.approval_id = ap.approval_id
              left join user u on u.user_id = pv.user_id where ap.fiscalYear in ('$sM') ORDER by pv.appr_id DESC");
      
              $select_stmt->execute();
            }

     


        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;
        ?>
          <tr >
          <?php
          // $ST = $row["approval_st"];

          if ($row["appr_status"] == 'N') {
            $str_sy = 'N';
          } else if ($row["appr_status"] == 'W') {
            $str_sy = 'W';
          } else if ($row["appr_status"] == 'Y'){
            $str_sy = 'Y';
          }
          ?>
          <style>
            .icon-green {
                color: green;
                font-size: 25px;
              }
              .icon-yellow{
                color: darkorange;
                font-size: 25px;
              }
              .icon-red{
                color: darkred;
                font-size: 20px;
              }
          </style>
            <td><?php echo $x ?></td>
            <td><?php if ($str_sy == 'N'){
              
             ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-red"></i><?php } else if ($str_sy == 'W') {?> <i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else {?>
             <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?>
            
            </td>
            <td><?php echo $row["approval_name"]; ?></td>
            <td><?php echo $row["f_name"];
                echo "&nbsp;" . $row["l_name"]; ?></td>
            <td><?php echo $row["approval_number"]; ?></td>
            <td><?php echo $row["appr_number"]; ?></td>
            <!-- <td><?php echo $row["approval_addp"]; ?></td> -->
            <!-- <td><a href="print_approval.php" >พิมพ์ PDF </a></td> -->
            <td><?php if ($str_sy == 'Y' ){ ?>
              <a target="_blank" href="../docs/app_prov/con_pv/<?php echo $row['doc_pv'];?>" target="_blank"  class="bi bi-eye-fill"></a>
           <?php } else if ($str_sy == 'N' || $str_sy == 'W'){ ?>    
            <?php }?> <a target="_blank" href="../print_apppv.php?appr_id=<?php echo $row["appr_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red" name = "printpv"></a> </td>
          
            <td> 
            <?php if ($str_sy == 'N'){
              ?>
               <a class="btn btn-warning btn-sm " data-bs-toggle='modal' data-bs-target='#conpv<?php echo $row['appr_id']; ?>' name="#">บันทึก
              <!-- <a type="submit" href = "editappv.php?appr_id=<?php echo $row["appr_id"]; ?>" class="btn btn-success btn-sm" name = "conpv" >ยืนยัน</button> -->
              <?php } else if (($str_sy == 'W')){?> 
              <!-- <a type="submit"  href = "scanpv.php?scanpv_id=<?php echo $row["appr_id"]; ?>"class="btn btn-warning btn-sm" name = "scanpv">บันทึก -->
              <a class="btn btn-warning btn-sm " data-bs-toggle='modal' data-bs-target='#scanpv<?php echo $row['appr_id']; ?>' name="#">ยืนยัน
              <?php 
              } else {?><button type="button" class="btn btn-info btn-sm">ยืนยันแล้ว</button> <?php }?>
             
                </td>
                <?php 
               include 'modal.php';
               ?>
                <td>
                <?php if ($str_sy == 'Y' ){ ?>  <a   class="bi bi-pencil-fill"href="scanpv.php?scanpv_id=<?php echo $row["appr_id"]; ?>" name = "uppv" >
                  </a>   
            <?php }?>
             
                
                
                </td>
            

            <!-- <td><a href="?delete_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger">ลบ</a></td> -->
          </tr>

        <?php  }  
             
        ?>
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