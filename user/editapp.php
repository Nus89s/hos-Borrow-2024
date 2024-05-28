<title>แก้ไขการขออบรม</title>
<?php

// ('login_action.php');
include 'header.php';
include 'nav.php';
include 'sidebar.php';

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
  }

if (isset($_REQUEST['upd'])); {

    $pid = $_REQUEST['approval_id'];
    $co = $db->prepare("SELECT ap.*,g.g_name from approval ap 
left join user u on u.user_id = ap.user_id
left join group_job g on g.g_id = u.user_id where approval_id = :pid");

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
    // $approval_numof = $_REQUEST['approval_numof'];
    $approval_self = $_REQUEST['approval_self'];
   // $approval_hsent = $_REQUEST['approval_hsent'];
 //   $approval_invite = $_REQUEST['approval_invite'];
    $approval_obj = $_REQUEST['approval_obj'];
    $approval_benf = $_REQUEST['approval_benf'];
    $approval_ex = $_REQUEST['approval_ex'];
    $approval_veh = $_REQUEST['approval_veh'];

    $doc_file = $_FILES['doc_file']['name'];
    $typefile = strrchr($_FILES['doc_file']['name'], ".");
    $temp = $_FILES['doc_file']['tmp_name'];
    //$typefile = strrchr($_FILES['txt_file']['name'],"."); //เอานามสกุลไฟล์

    

    $path = "docs/approval_in/" . $doc_file;
    $directory = "docs/approval_in/"; // set uplaod folder path for upadte time previos file remove and new file upload for next use

    if ($doc_file) {
        if ($typefile == ".pdf") {
            if (!file_exists($path)) { // check file not exist in your upload folder path
                unlink($directory . $row['doc_in']); // unlink functoin remove previos file
                move_uploaded_file($temp, 'docs/approval_in/' . $doc_file); // move upload file temperory directory to your upload folder
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
                            approval_edate = :approval_edate,approval_self = :approval_self,approval_obj = :approval_obj,
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
    // $sql->bindParam(':approval_numof', $approval_numof);
    $sql->bindParam(':approval_self', $approval_self);
   // $sql->bindParam(':approval_hsent', $approval_hsent);
 //   $sql->bindParam(':approval_invite', $approval_invite);
    $sql->bindParam(':approval_obj', $approval_obj);
    $sql->bindParam(':approval_benf', $approval_benf);
    $sql->bindParam(':approval_ex', $approval_ex);
    $sql->bindParam(':approval_veh', $approval_veh);
    $sql->bindParam(':doc_file', $doc_file);

    if ($result2 = $sql->execute()) {
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
            <h3><label class="form-text">แก้ไขการขออบรม</label></h3>
        </div>
        <br>

        <?php
        if (isset($updateapptMsg)) {
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
                <form id="myform2" action="editapp.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">

                    <div class="container">
                    


                        <!-- Name input -->
                        <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="name4">ความหนังสือ</label>
                                <span>(เลขหนังสือเชิญ)</span>
                                <input type="text" value="<?php echo $row['approval_number']; ?>" class="form-control" placeholder="" name="approval_number">
                            </div>


                            <div class="col">
                                <?php
                                $date = date("Y-m-d");

                                ?>
                                <label class="form-label" class="form-label" for="date">ลงวันที่</label>
                                <lspan>(วันที่หนังสือเชิญ)</lspan>
                                <input class="form-control" value="<?php echo $row['approval_in_date']; ?>" id="date" name="approval_in_date" placeholder="MM/DD/YYY" type="date" required>
                            </div>
                        </div>
                        <!-- <div class="col">
                  <label for="firstname" class="form-label" for="date">ระหว่างวันที่</label>
                  <label class="form-text">(วันที่ดำเนินการ)</label>
                  <input class="form-control" id="date" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required>
                </div> -->
                        <br>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="name4">เรื่อง</label>
                            <span>(จากหนังสือเชิญ)</span>
                            <input type="text" id="name4" value="<?php echo $row['approval_in_name']; ?>" name="approval_in_name" class="form-control" required>

                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="g_name">เขียนที่</label>
                                <input type="text" class="form-control" name="g_name" readonly placeholder=<?php echo $row["g_name"]; ?>>
                            </div>

                            <div class="col">
                                <?php
                                $date = date("Y-m-d");

                                ?>
                                <label class="form-label" for="name4">เรียน</label>
                                <input type="text" class="form-control" name="text1" readonly placeholder="ผู้อำนวยการโรงพยาบาลเทพา" />

                            </div>

                        </div>


                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="approval_date">ลงวันที่</label>
                                <input type="date" value="<?php echo $date; ?>" class="form-control" name="approval_date" readonly placeholder="<?php echo $date; ?>">
                            </div>
                            <div class="col">
                                <label for="group_job" class="form-label">ประเภท</label>
                                <div class="col">
                                    <select name="approval_type" class="form-control">
                                        <option value="<?php echo $row['approval_type']; ?>"><?php echo $row['approval_type']; ?> </option>
                                        <option value="ประชุม">ประชุม</option>
                                        <option value="อบรม">อบรม</option>
                                        <option value="สัมมนา">สัมมนาทางวิชาการ</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label class="form-label" for="approval_name">เรื่อง</label>
                                <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" name="approval_name" class="form-control">
                            </div>
                            <br>
                        </div>

                        <div class="form-outline mb-4">
                            <label class="form-label" for="approval_organ">จัดโดย</label>
                            <input type="text" id="name4" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" class="form-control" required>
                        </div>

                        <div class="form-outline mb-4">
                            <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                            <span>(ไม่ต้องใส่ ณ )</span>
                            <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" required>

                        </div>
                        <div class="row mt-2">
                            <div class="col">
                            <?php 
                        
                        // $t = 'fiscalYear';
                        // $t();
                          //fiscalYear($row['approval_in_date']);
                         
                        
                        ?>
                                <label for="firstname" class="form-label" for="approval_fdate">ระหว่างวันที่</label>
                                <span>(วันที่ดำเนินการ)</span>
                                <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required>
                            </div>
                            <div class="col">
                                <?php
                                $date = date("Y-m-d");

                                ?>
                                <label class="form-label" for="approval_edate">ถึงวันที่</label>
                                <span>(วันที่ดำเนินการ)</span>
                                <input class="form-control" id="date" value="<?php echo $row['approval_edate']; ?>" name="approval_edate" placeholder="MM/DD/YYY" type="date" required>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="approval_sum">ค่าลงทะเบียน</label>
                                <span>(บาท)</span>
                                <input type="text" class="form-control" value="<?php echo $row['approval_sum']; ?>" name="approval_sum">
                            </div>
                            <div class="col">
                                <label class="form-label" for="approval_numof">ครั้งที่</label>
                                <span>(ครั้งที่เข้าร่วม)</span>
                                <input type="text" class="form-control" value="<?php echo $row['approval_numof']; ?>" readonly name="approval_numof" />
                            </div>
                            <div class="col">
                                <label for="approval_veh" class="form-label">เดินทางโดย</label>
                                <select name="approval_veh" class="form-control">
                                    <option value="<?php echo $row['approval_veh']; ?>" selected="selected"> <?php if ($row["approval_veh"] == 'เครื่องบินโดยสาร') {
                                                                                                                    echo  "เครื่องบินโดยสาร";
                                                                                                                } else if ($row["approval_veh"] == 'ยานพาหนะส่วนตัว') {
                                                                                                                    echo "ยานพาหนะส่วนตัว";
                                                                                                                } else if ($row["approval_veh"] == 'รถจากหน่วยงานภายนอก') {
                                                                                                                    echo "รถจากหน่วยงานภายนอก";
                                                                                                                } else if ($row["approval_veh"] == 'รถโรงพยาบาล') {
                                                                                                                    echo "รถโรงพยาบาล";
                                                                                                                } ?></option>
                                    <option value="เครื่องบินโดยสาร">เครื่องบินโดยสาร</option>
                                    <option value="ยานพาหนะส่วนตัว">ยานพาหนะส่วนตัว</option>
                                    <option value="รถจากหน่วยงานภายนอก">รถจากหน่วยงานภายนอก</option>
                                    <option value="รถโรงพยาบาล">รถจากโรงพยาบาล</option>
                                </select>
                            </div>
                        </div>

                        <br>
                        <div class="row ">
                            <div class="form-check">
                                <div class="form-outline mb-4">



                                    <label for="group_job" class="col control-label">ข้าพเจ้าขออนุมัติการเข้าเข้าร่วมเองโดยเป็นการเข้าร่วม <span class="required" color="red">*</span></label>




                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <!-- <input id='approval_selftHidden' type='hidden' value='N' name='approval_self'> -->
                                    <input class="form-check-input" type="radio" value="V" id="radioCheck" name="approval_self" required <?php if ($row['approval_self'] == "V") {
                                                                                                                                            ?> checked="" <?php
                                                                                                                                                } ?>>
                                    <label class="form-check-label" for="radioCheck">

                                    </label>

                                    เชิงวิชาชีพ
                                    <br>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <!-- <input id='approval_selfHidden' type='hidden' value='N' name='approval_self'> -->
                                    <input class="form-check-input" type="radio" value="P" id="radioCheck" name="approval_self" required <?php if ($row['approval_self'] == "P") {
                                                                                                                                            ?> checked="" <?php
                                                                                                                                                } ?>>
                                    <label class="form-check-label" for="radioCheck">

                                    </label>
                                    งานที่ต้องรับผิดชอบเพิ่มเติม
                                </div>
                            </div>

                            <div class="col px-md-2">
                                <br>
                                <div class="form-check">
                                    <!-- <input id='approval_hsentHidden' type='hidden' value='N' name='approval_hsent'> -->
                                    <input class="form-check-input" type="radio" value="H" id="radioCheck" name="approval_self" required <?php if ($row['approval_self'] == "H") {
                                                                                                                                            ?> checked="" <?php
                                                                                                                                                } ?>>
                                    <label class="form-check-label" for="radioCheck">

                                    </label>
                                    <label class="form-label">
                                        โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม
                                    </label>

                                </div>
                                <div class="form-check">
                                    <!-- <input id='approval_inviteHidden' type='hidden' value='N' name='approval_invite'> -->
                                    <input class="form-check-input" type="radio" value="B" id="radioCheck" name="approval_self" required <?php if ($row['approval_self'] == "B") {
                                                                                                                                            ?> checked="" <?php
                                                                                                                                                } ?>>
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




                        <br>
                        <label class="form-label" for="textarea4">วัตถุประสงค์การเข้าร่วม</label>
                        <textarea id="textarea3" rows="4" name="approval_obj" class="form-control" required><?php echo $row['approval_obj']; ?> </textarea>

                        <br>


                        <label class="form-label" for="textarea4">ประโยชน์ที่คาดว่าจะได้รับ</label>
                        <textarea id="textarea3" rows="4" name="approval_benf" class="form-control" required><?php echo $row['approval_benf']; ?></textarea>
                        <br>

                        <label class="form-label" for="textarea4">กิจกรรมที่คาดว่าจะสามารถดำเดินการได้ภายหลังเข้าร่วม</label>
                        <textarea id="textarea3" rows="4" name="approval_ex" class="form-control" required><?php echo $row['approval_ex']; ?></textarea>
                        <br>
                        <div class="row">
                            <div class="col">
                                <div class="form-labl"></div>
                                <div class="ExternalFiles text-center">
                                    <iframe src="../docs/approval_in/<?php echo $row['doc_in']; ?>" height="300px" width="500px">

                                    </iframe>
                                    <br>
                                    <a href="../docs/approval_in/<?php echo $row['doc_in']; ?>" target="_blank" class="btn btn-info btn-sm"> เปิดดู </a>
                                    <span>อัพโหลดไฟล์หนังสือเชิญ</span>

                                </div>
                            </div>
                        </div>

                        <div class="form-label"></div>
                        <div class="row">
                            <br>
                            <div class="col">
                                <font class="form-text" color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น </font>
                                <input type="file" name="doc_file" class="form-control" value="<?php echo $row['doc_in']; ?>" accept="application/pdf"> <br>

                            </div>
                        </div>

                        <div class="form-group text-center">
                            <div class="col-md-12 mt-3">
                                <!-- <input type="hidden" name="id" value="<?= $row['approval_id']; ?>"> -->
                                <input type="submit" name="update" class="btn btn-primary" value="บันทึก">
                                <a href="index.php" class="btn btn-danger">ยกเลิก</a>
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