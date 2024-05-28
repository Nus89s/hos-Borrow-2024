<title>หนังสืออนุมัติจังหวัด</title>
<?php
include 'head_admin.php';
include 'nav_admit.php';
include 'sidebar_admin.php';


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
    $insertMsg = "บันทึกเรียบร้อย";
     $apprid = $_REQUEST['scanpv_id'];
     include 'nofyconapp.php';

  }




// if($date == $date ){
//   $date = date("Y-m-d H:i:s".strtotime("today 13:44"));
//   $select_stmt = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//   $select_stmt->execute();
//   while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//   sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

//    } }

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
    <!-- <h4>อนุมัติหนังสือส่งจังหวัด</h4> -->
    <h3><label class="form-text">ลงหนังสืออนุมัติจังหวัด</label></h3>
    </div>
    <br>

    <div class="input-group">


    </div>
    <!-- Modal -->
 

<div class="card">

              <div class="card-body">
              <?php
    if (isset($insertMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $insertMsg; ?>",
            type: "success"
          }, function() {
            window.location = "apppv.php"; 
          });
        }, 1000);
      </script>

    <?php
    } ?>
 
      

        <form id="myform3" action="scanpv.php" method="post"  enctype="multipart/form-data">
        
        <?php 
            if (isset($_REQUEST['scanpv'])); {

                $pid = $_REQUEST['scanpv_id'];
                $co = $db->prepare("SELECT * from approval_prov pv
                left join approval ap on ap.approval_id = pv.approval_id
                left join proj_head pr on pr.approval_id = ap.approval_id
                left join user u on u.user_id = pv.user_id where  pv.appr_id = :pid");
                $co->bindParam(':pid',$pid);
                $co->execute();
                $row = $co->fetch();
            }
        
        ?>


    <div class="container">
     

            <!-- Name input -->
            <div class="row">
                <div class="col">
                    <label class="form-label" for="g_name">เรื่อง</label>
                    <input type="text" class="form-control"  name="g_name" readonly placeholder="ขออนุมัติเดินทางไปราชการ">
                </div>

                <!-- <div class="col">
                    <label class="form-label" for="name4">เรียน</label>
                    <input type="text" class="form-control" name="text1" readonly placeholder="นายแพทย์สาธารณสุขจังหวัดสงขลา" />
                    
                </div> -->
               
           
                <div class="col">
                    <label class="form-label" for="appr_number">เลขที่</label>
                    <span>(เลขที่หนังสือ)</span>
                    <input type="text"  value="<?php echo $row['appr_number']; ?>" placeholder readonly class="form-control"  name="appr_number" >
                </div>

                    <?php
                    $date = date("Y-m-d");

                    ?>

            
                <div class="col">
                    <label class="form-label" for="appr_cdate">ลงวันที่</label>
                    <input type="date" value="<?php echo $date; ?>" class="form-control" name="appr_cdate" readonly placeholder="<?php echo $date; ?>">
                </div>
                <div class="col">
                    <label class="form-label" for="approval_name">ความหนังสือ</label>
                    <span>(เลขหนังสือเชิญ)</span>
                    <input type="text" id="name4" value="<?php echo $row['approval_number']; ?>" name="approval_number" class="form-control" readonly placeholder>
                </div>
            </div>
           
            
            <div class="row">
                <div class="col">
                    <label class="form-label" for="approval_organ">เรื่อง</label>
                    <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" name="approval_name" class="form-control" required readonly placeholder>
                </div>
                <div class="col">
                    <label class="form-label" for="approval_organ">จัดโดย</label>
                    <input type="text" id="name4" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" class="form-control" required readonly placeholder>
                </div>
            </div>
            <div class="row mt-2">

              <br>
              <div class="col">
                  <label for="group_job" class="form-label" >ประเภท</label>
                  <div class="col-sm-12" >
                  <input type="text" id="name4" value="<?php echo $row['approval_type']; ?>" name="approval_type" class="form-control" readonly placeholder>
                  </div>
              </div>
              <div class="col">
                  <label class="form-label" for="approval_numof">ครั้งที่</label>
                  <span>(ครั้งที่เข้าร่วม)</span>
                  <input type="text" class="form-control" value="<?php echo $row['approval_numof']; ?>" name="approval_numof" readonly placeholder />
              </div>
              <div class="col">
                    <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                    <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" required readonly placeholder>
    
                </div>
          </div>
            <div class="row mt-2">
                <div class="col">
                    <label for="firstname" class="form-label" for="approval_fdate">ระหว่างวันที่</label>
                    <span>(วันที่ดำเนินการ)</span>
                    <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required readonly placeholder>
                </div>
                <div class="col">
                    <?php
                    $date = date("Y-m-d");

                    ?>
                    <label class="form-label" for="approval_edate">ถึงวันที่</label>
                    <span>(วันที่ดำเนินการ)</span>
                    <input class="form-control" id="date" value="<?php echo $row['approval_edate']; ?>" name="approval_edate" placeholder="MM/DD/YYY" type="date" required readonly placeholder>
                </div>
            
                <div class="col">
                    <label class="form-label" for="approval_sum">ค่าลงทะเบียน</label>
                    <span>(บาท)</span>
                    <input type="text" class="form-control" value="<?php echo $row['approval_sum']; ?>" name="approval_sum" readonly placeholder>
                </div>
                
            </div>
            <br>
                <label class =  "form-label">อัพโหลดไฟล์หนังสือยืนยันจากจังหวัด</label><br>
                         <font class="form-text" color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น </font>
                        <input type="file" name="scanpv" required   class="form-control" accept="application/pdf"> <br>

            <br>

            <div class="form-group text-center">
                <div class="col-md-12 mt-3">
                <input type="hidden" name="scanpv_id" value="<?= $row['appr_id'];?>">
                    <input type="submit" name="upscanpv" class="btn btn-primary" value="บันทึก">
                    <a href="apppv.php" class="btn btn-danger">ยกเลิก</a>
                </div>
            </div>
        </form>
 
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
  <!-- /.content-wrapper -->
  
</html>
<?php 
include 'footer.php'
?>