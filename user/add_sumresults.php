<title>บันทึกสรุปอบรม</title>
<?php
include 'header.php';
include 'nav.php';
include 'sidebar.php';
if (isset($_REQUEST['uppjs'])) {
    $approval_id = $_REQUEST['approval_id'];
    $pjs_detail = $_REQUEST['pjs_detail'];
    $pjs_benf = $_REQUEST['pjs_benf'];
    $pjs_ex = $_REQUEST['pjs_ex'];
    $pjs_abt = $_REQUEST['pjs_abt'];
    //$pjs_sumprice = $_REQUEST['pjs_sumprice'];
    $pjs_allw = $_REQUEST['pjs_allw'];
    $pjs_accom = $_REQUEST['pjs_accom'];
    $pjs_veh = $_REQUEST['pjs_veh'];
    $pjs_regis = $_REQUEST['pjs_regis'];
    $pjs_other = $_REQUEST['pjs_other'];
    $pjs_comment = $_REQUEST['pjs_comment'];


    $sum = $pjs_allw + $pjs_accom + $pjs_veh + $pjs_regis + $pjs_other;

    $sql = $db->prepare("UPDATE proj_summary SET pjs_detail = :pjs_detail,pjs_benf = :pjs_benf ,pjs_ex = :pjs_ex,
                            pjs_abt = :pjs_abt,pjs_sumprice = :pjs_sumprice,pjs_allw = :pjs_allw,pjs_accom = :pjs_accom , pjs_veh = :pjs_veh,
                            pjs_regis = :pjs_regis ,pjs_other = :pjs_other ,pjs_comment = :pjs_comment
                            WHERE approval_id = :approval_id");


    $sql->bindParam(":approval_id", $approval_id);
    $sql->bindParam(":pjs_detail", $pjs_detail);
    $sql->bindParam(":pjs_benf", $pjs_benf);
    $sql->bindParam(":pjs_ex", $pjs_ex);
    $sql->bindParam(':pjs_abt', $pjs_abt);
    $sql->bindParam(':pjs_sumprice', $sum);
    $sql->bindParam(':pjs_allw', $pjs_allw);
    $sql->bindParam(':pjs_accom', $pjs_accom);
    $sql->bindParam(':pjs_veh', $pjs_veh);
    $sql->bindParam(':pjs_regis', $pjs_regis);
    $sql->bindParam(':pjs_other', $pjs_other);
    $sql->bindParam(':pjs_comment', $pjs_comment);

    $sql->execute();

    $approval_id = $_REQUEST['approval_id'];
    $upbor = $db->prepare("SELECT ap.* , b.* from approval ap left join proj_summary b on b.approval_id = ap.approval_id WHERE ap.approval_id = :approval_id ");
    $upbor->bindParam(":approval_id", $approval_id);
    $upbor->execute();
    $upbor_id = $upbor->fetch(PDO::FETCH_ASSOC);
    $approval_id = $upbor_id['approval_id'];
    $approval_name = $upbor_id['approval_name'];


    // sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่ครบสัญญา: " .$strDate );

    //$approval_id = $_REQUEST['approval_id'];
    $conapp = $_REQUEST['approval_id'];
    $conap = $db->prepare("UPDATE `proj_head` SET pjs_st = 'W' WHERE `approval_id` = :appid;");
    $conap->bindParam(':appid', $conapp);
    $conap->execute();
    $mesgconre = 'บันทึกข้อมูลสำเร็จ!!!';

    //header("location: list_sumresults.php");
}



function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".", "");
    $pt = strpos($amount_number, ".");
    $number = $fraction = "";
    if ($pt === false)
        $number = $amount_number;
    else {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }

    $ret = "";
    $baht = ReadNumber($number);
    if ($baht != "")
        $ret .= $baht . "บาท";

    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else
        $ret .= "ถ้วน";
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000) {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }

    $divider = 100000;
    $pos = 0;
    while ($number > 0) {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : ((($divider == 10) && ($d == 1)) ? "" : ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}
## วิธีใช้งาน
// $num1 = '3500.01'; 
// $num2 = '120000.50'; 
// echo  $num1  . "&nbsp;=&nbsp;" .Convert($num1),"<br>"; 
// echo  $num2  . "&nbsp;=&nbsp;" .Convert($num2),"<br>"; 


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

        <?php
                if (isset($mesgconre)) {
                ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $mesgconre; ?>",
            type: "success"
          }, function() {
            window.location = "list_sumresults.php"; 
          });
        }, 350);
      </script>

    <?php
                } ?>


        <div class="container text-center">
            <!-- <h4>อนุมัติหนังสือส่งจังหวัด</h4> -->
            <h3><label class="form-text">บันทึกสรุปอบรม</label></h3>
        </div>
        <br>

        <div class="input-group">


        </div>
        <!-- Modal -->
        <div class="card-body">

            <form id="myform2" action="add_sumresults.php" method="post">
                <?php
                if (isset($_REQUEST['addsum'])); {

                    $ar_id = $_REQUEST['approval_id'];
                    $co = $db->prepare("SELECT ap.* ,ps.* ,g.g_name from proj_summary ps 
                left join approval ap on ap.approval_id = ps.approval_id 
                left join user u on u.user_id = ap.user_id
                left join group_job g on g.g_id = u.user_id where ps.approval_id = :pid");

                    $co->bindParam(':pid', $ar_id);
                    $co->execute();
                    $row = $co->fetch();
                }
                ?>
                <div class="container">


                    <!-- Name input -->
                    <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">

                    <div class="row">
                        <div class="col">
                            <?php
                            $date = date("Y-m-d");

                            ?>
                            <label class="form-label" for="name4">เรียน</label>
                            <input type="text" class="form-control" name="text1" readonly placeholder="ผู้อำนวยการโรงพยาบาลเทพา" />

                        </div>
                        <div class="col">
                            <label class="form-label" for="approval_number">อ้างถึงหนังสือ</label>
                            <span>(หนังสือเชิญ)</span>
                            <input type="text" value="<?php echo $row['approval_number']; ?>" required readonly class="form-control" name="approval_number">
                        </div>
                        <div class="col">
                            <label class="form-label" for="borrow_date">ลงวันที่</label>
                            <input type="date" value="<?php echo $date; ?>" class="form-control" name="borrow_date" readonly placeholder="<?php echo $date; ?>">
                        </div>
                    </div>
                    <br>
                    <div class="row ">
                        <div class="col">
                            <label for="firstname" class="form-label" for="approval_organ">ตามที่</label>
                            <span>(หน่วยงาน)</span>
                            <input class="form-control" id="date" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" readonly type="text" required>
                        </div>
                        <div class="col">
                            <?php
                            $date = date("Y-m-d");

                            ?>
                            <label class="form-label" for="approval_name">เรื่อง</label>
                            <input class="form-control" id="date" value="<?php echo $row['approval_name']; ?>" name="approval_name" readonly type="text" required>
                            <br>
                        </div>
                    </div>
                    <div class="row ">

                        <div class="col">
                            <label for="firstname" class="form-label" for="approval_fdate">ระหว่างวันที่</label>
                            <span>(วันที่ดำเนินการ)</span>
                            <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" readonly placeholder="MM/DD/YYY" type="date" required>
                        </div>
                        <div class="col">
                            <?php
                            $date = date("Y-m-d");

                            ?>
                            <label class="form-label" for="approval_edate">ถึงวันที่</label>
                            <span>(วันที่ดำเนินการ)</span>
                            <input class="form-control" id="date" value="<?php echo $row['approval_edate']; ?>" name="approval_edate" readonly placeholder="MM/DD/YYY" type="date" required>
                            <br>
                        </div>
                        <div class="col">
                            <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                            <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" readonly required>
                        </div>
                    </div>

                    
                    <div class="form-outline mb-4">
                        <input id='pjs_detailHidden' type='hidden' value='-' name='pjs_detail'>
                        <label class="form-label" for="pjs_detail">สรุปเนื้อหาการฝึกอบรม</label>
                        <textarea id="textarea3" rows="2" name="pjs_detail" class="form-control"></textarea>
                    </div>
                    <div class="form-outline mb-4">
                        <input id='pjs_benfHidden' type='hidden' value='-' name='pjs_benf'>
                        <label class="form-label" for="pjs_benf">ประโยชน์ที่ได้รับจากการฝึก</label>
                        <textarea id="textarea3" rows="2" name="pjs_benf" class="form-control"></textarea>
                    </div>
                    <div class="form-outline mb-4">
                        <input id='pjs_exHidden' type='hidden' value='-' name='pjs_ex'>
                        <label class="form-label" for="pjs_ex">กิจกรรมที่คาดว่าจะดำเนินการได้ภายหลังการฝึกอบรม (ระบุระยะเวลาไม่เกิน 6 เดือน )</label>
                        <textarea id="textarea3" rows="2" name="pjs_ex" class="form-control"></textarea>
                    </div>

                    <div class="row">

                        <div class="col text-center">
                            <br>
                            <label class="form_label"> วิทยากรในการฝึกอบรมมีความรู้/ความสามารถ </label>
                            <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pjs_abt" id="pjs_abt" value="high">
                                <label class="form-check-label" for="inlineRadio1">มาก</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pjs_abt" id="pjs_abt" value="mid">
                                <label class="form-check-label" for="inlineRadio2">ปานกลาง</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="pjs_abt" id="pjs_abt" value="low">
                                <label class="form-check-label" for="inlineRadio3">น้อย</label>
                            </div>
                            <br>


                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <!-- <div class="col">
                            <label class="form-label" for="pjs_sumprice">ค่าใช้จ่ายในการฝึกอบรม</label>
                            <span>(บาท)</span>
                            <input type="text" class="form-control" name="pjs_sumprice">
                        </div> -->

                        <div class="row">
                            <div class="col">
                                <br>
                                <input id='pjs_allwHidden' type='hidden' value='0' name='pjs_allw'>
                                <label class="form-label" for="pjs_allw">ค่าเบี้ยเลี้ยงเดินทาง</label>
                                <span>(บาท)</span>
                                <input type="text" required class="form-control" name="pjs_allw">
                            </div>
                            <br>
                            <div class="col">
                                <br>
                                <input id='pjs_accomHidden' type='hidden' value='0' name='pjs_accom'>
                                <label class="form-label" for="pjs_accom">ค่าเช่าที่พัก</label>
                                <span>(บาท)</span>
                                <input type="text" class="form-control" name="pjs_accom" />
                            </div>
                            <div class="col">
                                <br>
                                <input id='pjs_vehHidden' type='hidden' value='0' name='pjs_veh'>
                                <label class="form-label" for="	pjs_veh">ค่าพาหนะ</label>
                                <span>(บาท)</span>
                                <input type="text" class="form-control" name="pjs_veh" />
                            </div>
                            <div class="col">
                                <br>
                                <input id='pjs_regis' type='hidden' value='0' name='pjs_regis'>
                                <label class="form-label" for="pjs_regis">ค่าลงทะเบียน</label>
                                <span>(บาท)</span>
                                <!-- <input type="text" class="form-control" name="borrow_regis" /> -->
                                <input type="text" value="<?php echo $row['approval_sum']; ?>" required readonly class="form-control" name="borrow_regis">
                            </div>

                            <div class="col">
                                <br>
                                <input id='pjs_otherHidden' type='hidden' value='0' name='pjs_other'>
                                <label class="form-label" for="pjs_other">อื่นๆ</label>
                                <span>(บาท)</span>
                                <input type="text" class="form-control" name="pjs_other" />
                            </div>

                        </div>
                    </div>
                    <br>
                    <div class="row ">
                        <div class="col ">
                            <div class="form-outline ">
                                <input id='borrowcomHidden' type='hidden' value='-' name='pjs_comment'>
                                <label class="form-label" for="pjs_comment">ความคิดเห็น/ข้อเสนอแนะ</label>
                                <textarea id="textarea3" rows="2" name="pjs_comment" class="form-control"></textarea>

                            </div>
                        </div>
                    </div>


                    <div class="form-group text-center">
                        <div class="col-md-12 mt-3">
                            <input type="hidden" name="id" value="<?= $row['approval_id']; ?>">
                            <input type="submit" name="uppjs" class="btn btn-primary" value="บันทึก">
                            <a href="list_sumresults.php" class="btn btn-danger">ยกเลิก</a>
                        </div>
                    </div>
                    <br>

            </div>            
        </form>   
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


</html>
<?php
include 'footer.php'
?>