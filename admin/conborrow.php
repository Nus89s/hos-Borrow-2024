<?php
include 'head_admin.php';
include 'nav_admit.php';
include 'sidebar_admin.php';

if (isset($_REQUEST['btn_cancel'])) {

    header("index.php");
}


function sendLineNotify($message){
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

function DateThai($strDate){
    $strYear = date("Y", strtotime($strDate)) + 543;
    $strMonth = date("n", strtotime($strDate));
    $strDay = date("j", strtotime($strDate));
    // $strHour= date("H",strtotime($strDate));
    // $strMinute= date("i",strtotime($strDate));
    // $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai = $strMonthCut[$strMonth];
    return "&nbsp;$strDay&nbsp;เดือน&nbsp;$strMonthThai&nbsp;พ.ศ.&nbsp;$strYear";
}


if (isset($_REQUEST['conbo'])) {

    $borrow_staff = $_SESSION['user_id'];
    $approval_id = $_REQUEST['approval_id'];
    $borrow_date = $_REQUEST['borrow_date'];
    $borrow_number = $_REQUEST['borrow_number'];
    $borrow_edate = $_REQUEST['borrow_edate'];
    $borrow_turn = $_REQUEST['borrow_turn'];
    $borrow_ofdate = $_REQUEST['borrow_ofdate'];
    $borrow_apdate = $_REQUEST['borrow_apdate'];
    $borrow_accmoney = $_REQUEST['borrow_accmoney'];
    $borrow_sum = $_REQUEST['borrow_sum'];
    $borrow_allw = $_REQUEST['borrow_allw'];
    $borrow_accom = $_REQUEST['borrow_accom'];
    $borrow_veh = $_REQUEST['borrow_veh'];
    $borrow_regis = $_REQUEST['borrow_regis'];
    $borrow_reward = $_REQUEST['borrow_reward'];
    $borrow_com = $_REQUEST['borrow_com'];
    $borrow_turn_haft = $_REQUEST['borrow_turn_haft'];
    $borrow_turn_month = $_REQUEST['borrow_turn_month'];

    // $doc_bor = $_FILES['doc_bor']['name'];
    // $temp = $_FILES['doc_bor']['tmp_name'];
    // $typefile = strrchr($_FILES['doc_bor']['name'], "."); //เอานามสกุลไฟล์
    // $date1 = date("Ymd_His");
    // $numrand = (mt_rand());

    // $path = "docs/bor_doc/" . $doc_bor; // set upload folder path
    // //$image_file = 'upload/'.$numrand.$date1.$typefile;
    // $newname = 'doc_bor' . $numrand . $date1 . $typefile;
    // $path_copy = $path . $newname;

    // if (empty($doc_bor)) {
    //     $errorMsg = "กรุณา อัพโหลดไฟล์!!!!";
    // } else if ($typefile == ".pdf") {
    //     if (!file_exists($path)) { // check file not exist in your upload folder path
    //         move_uploaded_file($temp, '../docs/bor_doc/' . $newname); // move upload file temperory directory to your upload folder
    //     } else {
    //         $errorMsg = "File already exists... Check upload filder"; // error message file not exists your upload folder path
    //     }
    // } else {
    //     $errorMsg = "กรุณาอัพโหลดไฟล์ที่มีนามสกุล.pdf";
    //     header("refresh:2;index.php");
    // }



    if (!isset($errorMsg)) {
        $sql = $db->prepare("UPDATE borrow SET borrow_date = :borrow_date,borrow_number = :borrow_number ,borrow_edate = :borrow_edate ,borrow_turn = :borrow_turn ,
                            borrow_ofdate = :borrow_ofdate ,borrow_apdate = :borrow_apdate ,borrow_accmoney = :borrow_accmoney ,borrow_sum = :borrow_sum ,
                            borrow_allw = :borrow_allw,borrow_accom = :borrow_accom,borrow_veh = :borrow_veh,
                            borrow_regis = :borrow_regis,borrow_reward = :borrow_reward,borrow_com = :borrow_com,
                            borrow_turn_haft = :borrow_turn_haft,borrow_turn_month = :borrow_turn_month, borrow_staff = :borrow_staff WHERE approval_id = :approval_id");


        $sql->bindParam(":approval_id", $approval_id);
        $sql->bindParam(":borrow_date", $borrow_date);
        $sql->bindParam(":borrow_sum", $borrow_sum);
        $sql->bindParam(":borrow_allw", $borrow_allw);
        $sql->bindParam(':borrow_accom', $borrow_accom);
        $sql->bindParam(':borrow_veh', $borrow_veh);
        $sql->bindParam(':borrow_regis', $borrow_regis);
        $sql->bindParam(':borrow_reward', $borrow_reward);
        $sql->bindParam(':borrow_com', $borrow_com);
        $sql->bindParam(':borrow_turn_haft', $borrow_turn_haft);
        $sql->bindParam(':borrow_turn_month', $borrow_turn_month);
        $sql->bindParam(':borrow_number', $borrow_number);
        $sql->bindParam(':borrow_edate', $borrow_edate);
        $sql->bindParam(':borrow_turn', $borrow_turn);
        $sql->bindParam(':borrow_ofdate', $borrow_ofdate);
        $sql->bindParam(':borrow_apdate', $borrow_apdate);
        $sql->bindParam(':borrow_accmoney', $borrow_accmoney);
        $sql->bindParam(':borrow_staff', $borrow_staff);
        //$sql->bindParam(':borrow_doc', $newname);

        $sql->execute();

        $approval_id = $_REQUEST['approval_id'];
        // $upbor = $db->prepare("SELECT * from borrow where approval_id = :approval_id ");
        $upbor = $db->prepare("SELECT ap.* , b.* from approval ap left join borrow b on b.approval_id = ap.approval_id WHERE ap.approval_id = :approval_id ");
        $upbor->bindParam(":approval_id", $approval_id);
        $upbor->execute();
        $upbor_id = $upbor->fetch(PDO::FETCH_ASSOC);
        $strDate = $upbor_id['borrow_edate'];
        $approval_name = $upbor_id['approval_name'];


        sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่ครบสัญญา: " . $strDate);

        //$approval_id = $_REQUEST['approval_id'];
        $conapp = $_REQUEST['approval_id'];
        $conap = $db->prepare("UPDATE `proj_head` SET `borrow_st` = 'Y' ,pjs_st = 'N' WHERE `approval_id` = :appid;");

        $conap->bindParam(':appid', $conapp);
        // $conap->execute();
        if ($conap->execute()) {

            $select = $db->prepare("select * from approval_prov where `approval_id` = $conapp");
            $select->execute();
            $row1 = $select->fetch(PDO::FETCH_ASSOC);

            $uid = $row1['user_id'];
            $conapp = $_REQUEST['approval_id'];
            $appr_number = $row1['appr_number'];

            $stmt2 = $db->prepare("INSERT INTO proj_summary (user_id ,approval_id,appr_number) VALUES (:user_id,:appid,:appr_number)");
            $stmt2->bindParam(':user_id', $uid);
            $stmt2->bindParam(':appid', $conapp);
            $stmt2->bindParam(':user_id', $uid);
            $stmt2->bindParam(':appr_number', $appr_number);

            if ($result2 = $stmt2->execute()) {
                $lastID = $db->lastInsertId();

                //echo $lastID;

                $uapid = $db->prepare("UPDATE proj_head SET  pjs_id = $lastID WHERE approval_id = $conapp");
                //bindParam data type
                $uapid->execute();


                $insertMsg = "บันทึกเรียบร้อย";
                $approval_id = $_REQUEST['approval_id'];
                include 'nofyconapp.php';

               
            }
        }
    }






    // $confpv = $db->prepare("UPDATE `proj_head` SET `borrow_st` = 'Y' ,pjs_st = 'N' WHERE `appr_id` = :apprid;");
    // $confpv->bindParam(':apprid', $approval_id);
    // $confpv->execute();

    // $approval_id = $_REQUEST['approval_id'];
    // $constatuspv = $db->prepare("SELECT ap.* , av.* from approval ap
    //   left join approval_prov av on av.approval_id = ap.approval_id
    //   WHERE ap.approval_id = :apprid;");
    // $constatuspv->bindParam(':apprid', $approval_id);
    // $constatuspv->execute();
    // $str_pv = $constatuspv->fetch(PDO::FETCH_ASSOC);
    // $uid_pv = $str_pv["user_id"];
    // $pvnumber = $str_pv["appr_number"];
    // $app = $str_pv["approval_id"];

    // $stmt2 = $db->prepare("INSERT INTO proj_summary (user_id ,approval_id,appr_number) VALUES (:user_id,:appid,:appr_number)");
    // //bindParam data type
    // //$stmt2->bindParam(':approval_date',$approval_date);
    // $stmt2->bindParam(':appid', $conapp);
    // $stmt2->bindParam(':user_id', $uid_pv);
    // $stmt2->bindParam(':approval_number', $pvnumber);





    //header("location: borrow.php");
    //header('refresh:2;list_borrow.php');

}


function Convert($amount_number){
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

function ReadNumber($number){
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





// if($date == $date ){
//   $date = date("Y-m-d H:i:s".strtotime("today 13:44"));
//   $select_stmt = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//   $select_stmt->execute();
//   while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//   sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

//    } }


?>


<title>ยืนยันการยืมเงิน</title>

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
                    <h3><label class="form-text">ยืนยันการยืมเงิน</label></h3>
                </div>
                <br>

                <div class="input-group">


                </div>
                <!-- Modal -->
                <div class="card-body">
                    <?php
                    if (isset($errorMsg)) {
                    ?>
                        <script>
                            setTimeout(function() {
                                swal({
                                    title: "<?php echo $errorMsg; ?>",
                                    type: "error"
                                }, function() {
                                    window.location = "borrow.php";
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
                                    window.location = "borrow.php";
                                });
                            }, 1000);
                        </script>

                    <?php
                    } ?>



                    <form id="myform2" action="conborrow.php" method="post" enctype="multipart/form-data">
                        <?php
                        if (isset($_REQUEST['conborrow'])); {

                            $ar_id = $_REQUEST['approval_id'];
                            $co = $db->prepare("SELECT ap.*,g.g_name,br.* from  borrow br
                left join approval ap on ap.approval_id = br.approval_id
                left join user u on u.user_id = ap.user_id
                left join group_job g on g.g_id = u.user_id where br.approval_id = :pid ");

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
                                    <label class="form-label" for="approval_number">ความหนังสือ</label>
                                    <span>(เลขหนังสือเชิญ)</span>
                                    <input type="text" value="<?php echo $row['approval_number']; ?>" required readonly class="form-control" name="approval_number">
                                </div>
                                <div class="col">
                                    <label class="form-label" for="borrow_date">ลงวันที่</label>
                                    <input type="date" value="<?php echo $row['borrow_date']; ?>" class="form-control" name="borrow_date" readonly placeholder="<?php echo $row['borrow_date']; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <br>
                                    <label class="form-label" for="borrow_name">ตามที่</label>
                                    <span>(หน่วยงาน)</span>
                                    <input type="text" id="name4" name="borrow_name" value="<?php echo $row['approval_organ']; ?>" class="form-control" readonly placeholder="">
                                    <br>
                                    <div class="row">
                                        <div class="col">
                                            <label class="form-label" for="borrow_name">เรื่อง</label>
                                            <input type="text" id="name4" name="borrow_name" value="<?php echo $row['approval_name']; ?>" class="form-control" readonly placeholder="">

                                            <br>
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
                                            </div>


                                            <div class="col">
                                                <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                                                <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" readonly required>
                                            </div>

                                            <br>
                                            <div class="row">
                                                <div class="col">
                                                    <label class="form-label" for="borrow_sum">ขออนุมัติเบิกค่าใช้จ่าย</label>
                                                    <span>(บาท)</span>
                                                    <input type="text" class="form-control" value="<?php echo $row['borrow_sum']; ?>" name="borrow_sum">
                                                </div>
                                                <div class="col">
                                                    <label class="form-label" for="borrow_sum">ตัวอักษร</label>
                                                    <span>(ยอดร่วมบาท)</span>
                                                    <input type="text" class="form-control" value="<?php echo Convert($row['borrow_sum']) ?>" name=""  readonly>
                                                </div>
                                            </div>

                                            <br>


                                            <div class="row">
                                                <div class="col">
                                                    <input id='borrow_allwHidden' type='hidden' value='0' name='borrow_allw'>
                                                    <label class="form-label" for="borrow_allw">ค่าเบี้ยเลี้ยง</label>
                                                    <!-- <span>(บาท)</span> -->
                                                    <input type="text" value="<?php echo $row['borrow_allw']; ?>" required class="form-control" name="borrow_allw">
                                                </div>
                                                <br>
                                                <div class="col">
                                                    <input id='borrow_accomHidden' type='hidden' value='0' name='borrow_accom'>
                                                    <label class="form-label" for="borrow_accom">ค่าเช่าที่พัก</label>
                                                    <!-- <span>(บาท)</span> -->
                                                    <input type="text" class="form-control" value="<?php echo $row['borrow_accom']; ?>" name="borrow_accom" />
                                                </div>
                                                <div class="col">
                                                    <input id='borrow_vehHidden' type='hidden' value='0' name='borrow_veh'>
                                                    <label class="form-label" for="borrow_veh">ค่าพาหนะ</label>
                                                    <!-- <span>(บาท)</span> -->
                                                    <input type="text" class="form-control" value="<?php echo $row['borrow_veh']; ?>" name="borrow_veh" />
                                                </div>
                                                <div class="col">
                                                    <input id='borrow_vehHidden' type='hidden' value='0' name='borrow_regisf'>
                                                    <label class="form-label" for="borrow_regis">ค่าลงทะเบียน</label>
                                                    <!-- <span>(บาท)</span> -->
                                                    <input type="text" class="form-control" value="<?php echo $row['borrow_regis']; ?>" name="borrow_regis" />
                                                </div>
                                                <div class="col">
                                                    <input id='borrow_rewardHidden' type='hidden' value='0' name='borrow_reward'>
                                                    <label class="form-label" for="borrow_reward">ค่าสมนาคุณ</label>
                                                    <!-- <span>(บาท)</span> -->
                                                    <input type="text" class="form-control" value="<?php echo $row['borrow_reward']; ?>" name="borrow_reward" />
                                                </div>
                                                <div class="col">
                                                    <input id='borrow_anotherHidden' type='hidden' value='0' name='borrow_another'>
                                                    <label class="form-label" for="borrow_another">อื่นๆ</label>
                                                    <!-- <span>(บาท)</span> -->
                                                    <input type="text" class="form-control" value="<?php echo $row['borrow_another']; ?>" name="borrow_another" />
                                                </div>
                                            </div>

                                            <div class="form-outline mb-4">
                                                <input id='borrowcomHidden' type='hidden' value='-' name='borrow_com'>
                                                <label class="form-label" for="borrow_com">หมายเหตุ</label>
                                                <textarea id="textarea3" rows="2" name="borrow_com" class="form-control"><?php echo $row['borrow_com']; ?></textarea>
                                            </div>
                                            <hr>
                                            <div class="row ml-2">
                                                <div class="col ">
                                                    <label class="form-label" for="borrow_number">เลขที่</label>
                                                    <span>เลที่สัญญา</span>
                                                    <input type="text" class="form-control" name="borrow_number" required>
                                                </div>
                                                <div class="col ">
                                                    <label class="form-label" for="borrow_edate">วันครบกำหนด</label>
                                                    <span>(วันครบกำหนดสัญญา)</span>
                                                    <input type="date" class="form-control" name="borrow_edate" placeholder="MM/DD/YYY" required />
                                                </div>


                                            </div>
                                            <hr>

                                            <div class="row">
                                                <span class="form-label ">*ข้าพเจ้าสัญญาว่าจะปฏิบัติตามระเบียบของทางราชการทุกประการ และจะนำใบสำคัญคู่จ่ายที่ถูกต้อง พร้อมทั้งเงินเหลือจ่าย
                                                    (ถ้ามี) ส่งใช้ภายในกำหนดไว้ในระเบียบการเบิกจ่ายเงินจากคลัง คือ </span>
                                                <div class="row ml-2 ">
                                                    <div class="col px-md-5 center">
                                                        <div class="form-check ">

                                                            <input id='bthHidden' type='hidden' value='N' name='borrow_turn_haft'>
                                                            <input class="form-check-input" type="checkbox" value="Y" id="flexCheckChecked" name="borrow_turn_haft">
                                                            <label class="form-check-label" for="flexCheckDefault">
                                                                ภายใน 15 วันนับตั้งแต่วันที่เดินทางกลับจากราชการ
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input id='btmHidden' type='hidden' value='N' name='borrow_turn_month'>
                                                            <input class="form-check-input" type="checkbox" value="Y" id="flexCheckDefault" name="borrow_turn_month">
                                                            <span>
                                                                ภายใน 30 วันนับตั้งแต่วันที่ได้รับเงินยืมนี้ (โครงการ) ถ้าข้าพเจ้าไม่ส่งตามกำหนด ข้าพเจ้ายินยอมให้หักเงินเดือน
                                                                ค่าจ้าง เบี้ยหวัด บำเหน็จ บำนาญ หรือเงินอื่นใดที่ข้าพเจ้าพึงได้รับจากทางราชการ ชดใช้จำนวนเงินที่ยืมไปจนครบถ้วนได้ทันที
                                                            </span>

                                                            <label class="form-text">ลงวันที่</label>
                                                            <input type="date" class="form-control" name="borrow_turn" placeholder="MM/DD/YYY" required>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row ">
                                                <div class="col ">
                                                    <div class="form-check ">
                                                        <span><b>เสนอ</b> ผู้อำนวยการโรงพยาบาลเทพา
                                                            ได้ตรวจสอบแล้ว เห็นควรอนุมัติให้ยืมตามใบยืมฉบับนี้ได้จำนวน <b><?php echo $row['borrow_sum']; ?> บาท (<?php echo Convert($row['borrow_sum']) ?>)</b>
                                                        <label class="form-text ">&nbsp;&nbsp;&nbsp;ลงวันที่</label>
                                                        <input type="date" class="form-control" name="borrow_ofdate" placeholder="MM/DD/YYY" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row ">
                                                <div class="col ">
                                                    <div class="form-check ">
                                                        <span><b>คำอนุมัติ </b> อนุมัติให้ยืมตามเงื่อนไขข้างต้นได้ <b><?php echo $row['borrow_sum']; ?> บาท (<?php echo Convert($row['borrow_sum']) ?>)</b>

                                                        <label class="form-text ">&nbsp;&nbsp;&nbsp;ลงวันที่</label>
                                                        <input type="date" class="form-control" name="borrow_apdate" placeholder="MM/DD/YYY" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row "> 
                                                <div class="col ">
                                                    <div class="form-check ">
                                                        <span><b>ใบรับเงิน </b> ได้รับเงินยืมจำนวน <b><?php echo $row['borrow_sum']; ?> บาท (<?php echo Convert($row['borrow_sum']) ?>)</b>
                                                        <label class="form-text ">&nbsp;&nbsp;&nbsp;ลงวันที่</label>
                                                        <input type="date" class="form-control" name="borrow_accmoney" placeholder="MM/DD/YYY" required>
                                                    </div>
                                                </div>
                                            </div>                                           
                                            <hr>
                                            <!-- <div class="row">
                                                <div class="col">
                                                    <label class="form-label"><b>อัพโหลดไฟล์สัญญายืมเงิน</b></label>
                                                    <font class="form-text" color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น </font>
                                                    <input type="file" name="doc_bor" required class="form-control" accept="application/pdf">
                                                </div>
                                            </div> -->
                                            
                                        </div>

                                        <hr>

                                    </div>
                                   
                                    <div class="row center">
                                                <div class="form-group center">
                                                    <div class="col-md-12 mt-3">
                                                        <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
                                                        <input type="submit" name="conbo" class="btn btn-primary" value="ยืนยัน">
                                                        <a href="borrow.php" class="btn btn-danger">ยกเลิก</a>
                                                    </div>
                                                </div>
                                            </div>
                    </form>

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
  
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

 



</html>
<?php 
include 'footer.php';
?>