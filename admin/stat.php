<title>สรุปสถิติ</title>
<?php

// ('login_action.php');
include 'head_admin.php';
  include 'nav_admit.php';
  include 'sidebar_admin.php';

if (isset($_REQUEST['upd'])); {

    $pid = $_REQUEST['approval_id'];
    $co = $db->prepare("SELECT ap.*,g.g_name,pv.*,ps.*,br.* from approval ap 
    left join user u on u.user_id = ap.user_id
    left join approval_prov pv on pv.approval_id = ap.approval_id
    left join proj_summary ps on ps.approval_id = ap.approval_id
    left join borrow br on br.approval_id = ap.approval_id
    left join group_job g on g.g_id = u.user_id where ap.approval_id = :pid");

    $co->bindParam(':pid', $pid);
    $co->execute();
    $row = $co->fetch();
}

if (isset($_REQUEST['update'])) {


            $approval_id = $_REQUEST['approval_id'];
            $approval_in_name = $_REQUEST['approval_in_name'];
            $approval_in_date = $_REQUEST['approval_in_date'];
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
            $approval_veh = $_REQUEST['approval_veh'];
            
            $doc_file = $_FILES['doc_file']['name'];
            $typefile = strrchr($_FILES['doc_file']['name'],".");
            $temp = $_FILES['doc_file']['tmp_name'];
            //$typefile = strrchr($_FILES['txt_file']['name'],"."); //เอานามสกุลไฟล์

            $path = "docs/approval_in/".$doc_file;
            $directory = "docs/approval_in/"; // set uplaod folder path for upadte time previos file remove and new file upload for next use

            if ($doc_file) {
                if ($typefile == ".pdf") {
                    if (!file_exists($path)) { // check file not exist in your upload folder path
                            unlink($directory.$row['doc_in']); // unlink functoin remove previos file
                            move_uploaded_file($temp,'docs/approval_in/'.$doc_file); // move upload file temperory directory to your upload folder
                    } else {
                        $errorMsg = "File already exists... Check upload folder";
                    }
                } else {
                    $errorMsg = "Upload JPG, JPEG, PNG & GIF formats...";
                }
            } else {
                $doc_file = $row['doc_in']; // if you not select new image than previos image same it is it.
            }   



            $sql = $db->prepare("UPDATE approval SET approval_in_name = :approval_in_name,approval_in_date = :approval_in_date ,approval_name = :approval_name ,approval_number = :approval_number,approval_date = :approval_date,approval_type = :approval_type,
                            approval_organ = :approval_organ,approval_addp = :approval_addp,approval_fdate = :approval_fdate,
                            approval_edate = :approval_edate,approval_numof = :approval_numof,approval_self = :approval_self,
                            approval_hsent = :approval_hsent,approval_invite = :approval_invite,approval_obj = :approval_obj,
                            approval_benf = :approval_benf,approval_ex = :approval_ex,approval_sum = :approval_sum,approval_veh = :approval_veh,doc_in = :doc_file  WHERE approval_id = :approval_id");

            //  
            // approval_type = :approval_type,

            // 
            // 
            // 
            // 
            // 

            $sql->bindParam(":approval_id", $approval_id);
            $sql->bindParam(":approval_name", $approval_name);
            $sql->bindParam(":approval_in_name", $approval_in_name);
            $sql->bindParam(":approval_in_date", $approval_in_date);
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
            $sql->bindParam(':approval_veh', $approval_veh);
            $sql->bindParam(':doc_file', $doc_file);

           if($result2 = $sql->execute()) {
            $updateapptMsg = "บันทึกเรียบร้อย";
                //header('refresh:2;index.php');
           }
        

            //header("refresh:2;index.php");
      

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
    <!-- <h4>อนุมัติหนังสือส่งจังหวัด</h4> -->
    <h3><label class="form-text">ตรวจสอบการอบรม</label></h3>
    </div>
    <br>

    <?php 
            if(isset($updateapptMsg)) {
        ?>
            <script>
                     setTimeout(function() {
                      swal({
                          title: "<?php echo $updateapptMsg; ?>",
                          type: "success"
                      }, function() {
                          window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 1000);
                </script>
           
        <?php } ?>


    <div class="input-group">


    </div>
    <!-- Modal -->


    <div class="card">
              <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body" style="background-color:#f2ece3 ">
              <form id="myform2" action="editapp.php" method="post"  enctype="multipart/form-data">
            <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
           
            <div class="container">


                <!-- Name input -->
                <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
                <div class="row ">
                    <div class="col">
                        <label class="form-label" for="approval_name">เรื่อง</label>
                        <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" name="approval_name" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="approval_addp">ณ สถานที่</label>
                        <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" readonly>
                    </div>
                    <br>
                </div>
                <hr>
                <h4><label class="form-label mt-3">ยืมเงิน</label></h4>
                <div class="row mt-2">
                    <div class="col">
                        <label class="form-label" for="borrow_number">เลขที่สัญญา</label>
                        <input type="text" id="borrow_number" value="<?php echo $row['borrow_number']; ?>" name="borrow_number" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="borrow_edate">วันที่ครบสัญญา</label>
                        <input type="date" id="borrow_edate" value="<?php echo $row['borrow_edate']; ?>" name="borrow_edate" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="borrow_sum">ยืมเงิน-จำนวน</label>
                        <input type="text" id="borrow_sum" value="<?php echo $row['borrow_sum']; ?>" name="borrow_sum" class="form-control" readonly>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label class="form-label" for="borrow_allw">ค่าเบี้ยเลี้ยง</label>
                        <input type="text" id="name4" value="<?php echo $row['borrow_allw']; ?>" name="borrow_allw" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="borrow_accom">ค่าที่พัก</label>
                        <input type="text" id="name4" value="<?php echo $row['borrow_accom']; ?>" name="borrow_accom" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="borrow_veh">ค่าพาหนะ</label>
                        <input type="text" id="name4" value="<?php echo $row['borrow_veh']; ?>" name="borrow_veh" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="borrow_regis">ค่าลงทะเบียน</label>
                        <input type="text" id="name4" value="<?php echo $row['borrow_regis']; ?>" name="borrow_regis" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="borrow_reward">ค่าสัมนาคุณ</label>
                        <input type="text" id="name4" value="<?php echo $row['borrow_reward']; ?>" name="borrow_reward" class="form-control" readonly>
                    </div>
                    <div class="col">
                        <label class="form-label" for="borrow_another">อื่นๆ</label>
                        <input type="text" id="name4" value="<?php echo $row['borrow_another']; ?>" name="borrow_another" class="form-control" readonly>
                    </div>
                    <br>
                </div>
                <hr>
                <h4><label class="form-label mt-3">หนังสือขออนุญาต</label></h4>

                <div class="row text-center mt-5">
                    
                    <div class="col">
                        <label>หนังสือเชิญ</label>
                        <br>
                    <iframe src="../docs/approval_in/<?php echo $row['doc_in']; ?>"  height="300px" width = "500px" ></iframe>
                    <div class = "col sm-1">
                    
                       <font class="form-text" color="red">ไฟล์หนังสือเชิญ</font>                
                   <a href="../docs/approval_in/<?php echo $row['doc_in'];?>" target="_blank" class="btn btn-info btn-sm"> เปิดดู </a>
                      
                   </div>   
                    </div>
                    
                  
                    <div class="col">
                    <label>หนังสืออนุมัติจังหวัด</label>
                    <br>
                    <iframe src="../docs/app_prov/con_pv/<?php echo $row['doc_pv']; ?>"  height="300px" width = "500px" ></iframe>
                    <div class = "col sm-1">
                    
                       <font class="form-text" color="red">ไฟล์หนังสืออนุมัติจังหวัด</font>                
                   <a href="../docs/app_prov/con_pv/<?php echo $row['doc_pv'];?>" target="_blank" class="btn btn-info btn-sm"> เปิดดู </a>
                      
                   </div>   
                    </div>
                    </div>
                    
                
                <div class="row text-center mt-5">
                    <div class="col">
                    <label>หนังสือสัญญายืมเงิน</label>
                    <br>
                    <iframe src="../docs/bor_doc/<?php echo $row['borrow_doc']; ?>"  height="300px" width = "500px" ></iframe>
                    <div class = "col sm-1">
                    
                    <font class="form-text" color="red">ไฟล์หนังสือสัญญายืมเงิน</font>                
                <a href="../docs/bor_doc/<?php echo $row['borrow_doc']; ?>" target="_blank" class="btn btn-info btn-sm"> เปิดดู </a>
                   
                </div>
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