<title>หนังสือส่งอนุมัติจังหวัด</title>
<?php
include 'head_admin.php';
include 'nav_admit.php';
include 'sidebar_admin.php';

if (isset($_REQUEST['uppv'])) {
    $approval_id = $_REQUEST['appr_id'];
    $approval_name = $_REQUEST['approval_name'];
     $approval_number = $_REQUEST['approval_number'];
     $approval_date = $_REQUEST['approval_date'];
     $approval_type = $_REQUEST['approval_type'];
     $approval_organ = $_REQUEST['approval_organ'];
     $approval_addp = $_REQUEST['approval_addp'];
     $approval_fdate = $_REQUEST['approval_fdate'];
     $approval_edate = $_REQUEST['approval_edate'];
     $approval_sum = $_REQUEST['approval_sum'];
     $approval_numof = $_REQUEST['approval_numof'];
     $approval_self = $_REQUEST['approval_self'];
     $approval_hsent = $_REQUEST['approval_hsent'];
     $approval_invite = $_REQUEST['approval_invite'];
     $approval_obj = $_REQUEST['approval_obj'];
    $approval_benf = $_REQUEST['approval_benf'];
    $approval_ex = $_REQUEST['approval_ex'];
   


    $sql = $db->prepare("UPDATE approval SET approval_name = :approval_name ,approval_number = :approval_number,approval_date = :approval_date,approval_type = :approval_type,
                            approval_organ = :approval_organ,approval_addp = :approval_addp,approval_fdate = :approval_fdate,
                            approval_edate = :approval_edate,approval_numof = :approval_numof,approval_self = :approval_self,
                            approval_hsent = :approval_hsent,approval_invite = :approval_invite,approval_obj = :approval_obj,
                            approval_benf = :approval_benf,approval_ex = :approval_ex,approval_sum = :approval_sum ,appr_status = 'W' WHERE approval_id = :approval_id");

    //  
    // approval_type = :approval_type,
    
    // 
    // 
    // 
    // 
    // 

    $sql->bindParam(":approval_id", $approval_id);
    $sql->bindParam(":approval_name", $approval_name);
    // $sql->bindParam(':uid', $uid);
     $sql->bindParam(':approval_number', $approval_number);
    $sql->bindParam(':approval_date', $approval_date);
     $sql->bindParam(':approval_type', $approval_type);
     $sql->bindParam(':approval_organ', $approval_organ);
     $sql->bindParam(':approval_addp', $approval_addp);
     $sql->bindParam(':approval_fdate', $approval_fdate);
     $sql->bindParam(':approval_edate', $approval_edate);
    $sql->bindParam(':approval_sum', $approval_sum);
     $sql->bindParam(':approval_numof', $approval_numof);
     $sql->bindParam(':approval_self', $approval_self);
     $sql->bindParam(':approval_hsent', $approval_hsent);
     $sql->bindParam(':approval_invite', $approval_invite);
     $sql->bindParam(':approval_obj', $approval_obj);
    $sql->bindParam(':approval_benf', $approval_benf);
    $sql->bindParam(':approval_ex', $approval_ex);
 
    $sql->execute();

    if ($sql) {
        $_SESSION['success'] = "Data has been updated successfully";
        header("location: index.php");
    } else {
        $_SESSION['error'] = "Data has not been updated successfully";
        header("location: index.php");
    }
}




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
    <h3><label class="form-text">อนุมัติหนังสือส่งจังหวัด</label></h3>
    </div>
    <br>

    <div class="input-group">


    </div>
    <!-- Modal -->
 

<div class="card">

              <div class="card-body">
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
      

        <form id="myform2" action="editappv.php" method="post">
            <?php 
                if (isset($_REQUEST['conpv'])); {

                    $pid = $_REQUEST['appr_id'];
                    $co = $db->prepare(" SELECT * from approval_prov pv
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
                    <!-- <input type="hidden" name="approval_id" value="<?= $row['approval_id'];?>"> -->
            <div class="row">
                <div class="col">
                    <label class="form-label" for="g_name">เรื่อง</label>
                    <input type="text" class="form-control"  name="g_name" readonly placeholder="ขออนุมัติเดินทางไปราชการ">
                </div>

                <div class="col">
                    <label class="form-label" for="name4">เรียน</label>
                    <input type="text" class="form-control" name="text1" readonly placeholder="นายแพทย์สาธารณสุขจังหวัดสงขลา" />
                    
                </div>
               
            </div>
           

            <div class="row">
                <div class="col">
                    <label class="form-label" for="appr_number">เลขที่</label>
                    <span >(เลขที่หนังสือ)</span>
                    <input type="text"  value="<?php echo $row['appr_number']; ?>" placeholder required class="form-control"  name="appr_number" >
                </div>

                    <?php
                    $date = date("Y-m-d");

                    ?>

            
                <div class="col">
                    <label class="form-label" for="appr_cdate">ลงวันที่</label>
                    <input type="date" value="<?php echo $date; ?>" class="form-control" name="appr_cdate" readonly placeholder="<?php echo $date; ?>">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col">
                    <label class="form-label" for="approval_name">ความหนังสือ</label>
                    <span>(เลขหนังสือเชิญ)</span>
                    <input type="text" id="name4" value="<?php echo $row['approval_number']; ?>" name="approval_number" class="form-control" readonly placeholder>
                </div>
                <br>
                <div class="col">
                    <label for="group_job" class="form-label" >ประเภท</label>
                    <div class="col-sm-12" >
                    <input type="text" id="name4" value="<?php echo $row['approval_type']; ?>" name="approval_type" class="form-control" readonly placeholder>
                     
                    </div>
                </div>
            </div>
            <br>
            <div class="form-outline mb-4">
                <label class="form-label" for="approval_organ">เรื่อง</label>
                <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" name="approval_name" class="form-control" required readonly placeholder>
            </div>
            <div class="form-outline mb-4">
                <label class="form-label" for="approval_organ">จัดโดย</label>
                <input type="text" id="name4" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" class="form-control" required readonly placeholder>
            </div>

            <div class="form-outline mb-4">
                <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                <span>(ไม่ต้องใส่ ณ )</span>
                <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" required readonly placeholder>

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
                <div class="col">
                    <label class="form-label" for="approval_numof">ครั้งที่</label>
                    <span>(ครั้งที่เข้าร่วม)</span>
                    <input type="text" class="form-control" value="<?php echo $row['approval_numof']; ?>" name="approval_numof" readonly placeholder />
                </div>
            </div>
            <hr>

         
      
            <br>

            <div class="form-group text-center">
                <div class="col-md-12 mt-3">
                <input type="hidden" name="appr_id" value="<?= $row['appr_id'];?>">
                    <input type="submit" name="uppvdate" class="btn btn-primary" value="บันทึก">
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