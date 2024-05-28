<?php
session_start();
if (!isset($_SESSION['user_id'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.php"); // redirect ไปยังหน้า login.php
    exit;
}
// ('login_action.php');

require_once('conn.php');

if (isset($_REQUEST['uppjs'])) {
    $approval_id = $_REQUEST['approval_id'];
    $pjs_detail = $_REQUEST['pjs_detail'];
    $pjs_benf = $_REQUEST['pjs_benf'];
    $pjs_ex = $_REQUEST['pjs_ex'];
    $pjs_abt = $_REQUEST['pjs_abt'];
    $pjs_sumprice = $_REQUEST['pjs_sumprice'];
    $pjs_allw = $_REQUEST['pjs_allw'];
    $pjs_accom = $_REQUEST['pjs_accom'];
    $pjs_veh = $_REQUEST['pjs_veh'];
    $pjs_regis = $_REQUEST['pjs_regis'];
    $pjs_other = $_REQUEST['pjs_other'];
    $pjs_comment = $_REQUEST['pjs_comment'];




    $sql = $db->prepare("UPDATE proj_summary SET pjs_detail = :pjs_detail,pjs_benf = :pjs_benf ,pjs_ex = :pjs_ex,
                            pjs_abt = :pjs_abt,pjs_sumprice = :pjs_sumprice,pjs_allw = :pjs_allw,pjs_accom = :pjs_accom , pjs_veh = :pjs_veh,
                            pjs_regis = :pjs_regis ,pjs_other = :pjs_other ,pjs_comment = :pjs_comment
                            WHERE approval_id = :approval_id");


    $sql->bindParam(":approval_id", $approval_id);
    $sql->bindParam(":pjs_detail", $pjs_detail);
    $sql->bindParam(":pjs_benf", $pjs_benf);
    $sql->bindParam(":pjs_ex", $pjs_ex);
    $sql->bindParam(':pjs_abt', $pjs_abt);
    $sql->bindParam(':pjs_sumprice', $pjs_sumprice);
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
    $conap->bindParam(':appid',$conapp);
    $conap->execute();

        header("location: list_sumresults.php");

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


<!DOCTYPE html>
<html lang="en">

<head>
    <title>บันทึกสรุปอบรม</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css");
    </style>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">


</head>


<body style="background-color: #FAFFFC; ">

    <div class="w3-sidebar w3-bar-block w3-card w3-animate-left text-center" style="display:none" id="leftMenu">
        <button onclick="closeLeftMenu()" class="w3-bar-item w3-button w3-large">Close &times;</button>
        <hr>
        <a class="navbar-brand " href="index.php">HOME</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!--       
  <a href="#" class="w3-bar-item w3-button">Link 1</a>
  <a href="#" class="w3-bar-item w3-button">Link 2</a>
  <a href="#" class="w3-bar-item w3-button">Link 3</a> -->
        <ul class="nav nav-pills flex-column mb-0 align-items-center align-items-sm-start" id="menu">
            <!-- <li class="nav-item">
                <a href="#" class="nav-link align-middle px-0 text-white">
                    <i class="bi bi-send-arrow-up-fill icon-side"></i> <span class="ms-1 d-none d-sm-inline">หนังสือส่งจังหวัด</span>
                </a>
            </li> -->
            <li class="nav-item ">
                <a href="list_borrow.php" class="nav-link align-middle px-0 text-white">
                    <i class="bi bi-coin icon-side"></i> <span class="ms-1 d-none d-sm-inline">การยืมเงิน</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="list_sumresults.php" class="nav-link align-middle px-0 text-white">
                    <i class="bi bi-card-list icon-side"></i> <span class="ms-1 d-none d-sm-inline">สรุปการอบรม</span>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">ยืนยันการยืมเงิน</span> </a>
            </li>
            <li>
                <a href="#" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Orders</span></a>
            </li> -->
            <!-- <li class="nav-item ">
            <a class="nav-link px-0 align-middle " href="logout.php" tabindex="-1" aria-disabled="true" onclick="return confirm('ยืนยันการออกจากระบบ');">
            <i class="bi bi-box-arrow-left icon-side"></i>ออกจากระบบ</a>
          </li> -->
            <li class="nav-item">
                <a href="logout.php" aria-disabled="true" onclick="return confirm('ยืนยันการออกจากระบบ');" class="nav-link align-middle px-0 text-white">
                    <i class="bi bi-box-arrow-left icon-side"></i> <span class="ms-1 d-none d-sm-inline">ออกจากระบบ</span>
                </a>
            </li>


        </ul>

        <script>
            function openLeftMenu() {
                document.getElementById("leftMenu").style.display = "block";
            }

            function closeLeftMenu() {
                document.getElementById("leftMenu").style.display = "none";
            }
        </script>


    </div>



    <button class="w3-button  w3-xlarge w3-left " style="background-color: #52baff; " onclick="openLeftMenu()">&#9776;</button>
    <div class=" p-3 " style="background-color: #52baff; ">
        <h3 class="text-center">บันทึกสรุปอบรม</h3>
    </div>
    <br>
    <h5 class="text-center">สวัสดี <?= $_SESSION['f_name'] . ' ' . $_SESSION['l_name'];  ?></h5>

    <style>
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

        .icon-side {
            color: #FAFFFC;
            font-size: 20px;

        }
    </style>


    </div>


    <div class="container  ">

        <!-- Button trigger modal -->
        <div class="container text-center">

            <hr>




        </div>

        <!-- <?php print_r($_SESSION); ?> -->


        <!-- Modal -->



        <form id="myform2" action="add_sumresults.php" method="post">
            <?php
            if (isset($_REQUEST['addsum'])); {

                $ar_id = $_REQUEST['ar_id'];
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
                        <label class="form-text">(หนังสือเชิญ)</label>
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
                        <label class="form-text">(หน่วยงาน)</label>
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
                        <label class="form-text">(วันที่ดำเนินการ)</label>
                        <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" readonly placeholder="MM/DD/YYY" type="date" required>
                    </div>
                    <div class="col">
                        <?php
                        $date = date("Y-m-d");

                        ?>
                        <label class="form-label" for="approval_edate">ถึงวันที่</label>
                        <label class="form-text">(วันที่ดำเนินการ)</label>
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
                        <label class ="form_label"> วิทยากรในการฝึกอบรมมีความรู้/ความสามารถ    </label>
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
                            <input class="form-check-input" type="radio" name="pjs_abt" id="pjs_abt" value="low" >
                            <label class="form-check-label" for="inlineRadio3">น้อย</label>
                        </div>
                        <br>


                    </div>
                    
                </div>
                <br>
                <div class="row">
                <div class="col">
                        <label class="form-label" for="pjs_sumprice">ค่าใช้จ่ายในการฝึกอบรม</label>
                        <label class="form-text">(บาท)</label>
                        <input type="text" class="form-control" name="pjs_sumprice">
                    </div>
                   
                    <label class = "form-label"></label>


                    <br>


                    <div class="row">
                        <div class="col">
                            <input id='pjs_allwHidden' type='hidden' value='0' name='pjs_allw'>
                            <label class="form-label" for="pjs_allw">ค่าเบี้ยเลี้ยง</label>
                            <label class="form-text">(บาท)</label>
                            <input type="text" required class="form-control" name="pjs_allw">
                        </div>
                        <br>
                        <div class="col">
                            <input id='pjs_accomHidden' type='hidden' value='0' name='pjs_accom'>
                            <label class="form-label" for="pjs_accom">ค่าเช่าที่พัก</label>
                            <label class="form-text">(บาท)</label>
                            <input type="text" class="form-control" name="pjs_accom" />
                        </div>
                        <div class="col">
                            <input id='pjs_vehHidden' type='hidden' value='0' name='pjs_veh'>
                            <label class="form-label" for="	pjs_veh">ค่าพาหนะ</label>
                            <label class="form-text">(บาท)</label>
                            <input type="text" class="form-control" name="pjs_veh" />
                        </div>
                        <div class="col">
                            <input id='pjs_regis' type='hidden' value='0' name='pjs_regis'>
                            <label class="form-label" for="pjs_regis">ค่าลงทะเบียน</label>
                            <label class="form-text">(บาท)</label>
                            <!-- <input type="text" class="form-control" name="borrow_regis" /> -->
                            <input type="text" value="<?php echo $row['approval_sum']; ?>" required readonly class="form-control" name="borrow_regis">
                        </div>
                        <!-- <div class="col">
                            <input id='borrow_rewardHidden' type='hidden' value='0' name='borrow_reward'>
                            <label class="form-label" for="borrow_reward">ค่าสมนาคุณ</label>
                            <label class="form-text">(บาท)</label>
                            <input type="text" class="form-control" name="borrow_reward" />
                        </div> -->
                        <div class="col">
                            <input id='pjs_otherHidden' type='hidden' value='0' name='pjs_other'>
                            <label class="form-label" for="pjs_other">อื่นๆ</label>
                            <label class="form-text">(บาท)</label>
                            <input type="text" class="form-control" name="pjs_other" />
                        </div>
                        <label class="form-label"></label>
                        <div class="form-outline mb-4">
                            <input id='borrowcomHidden' type='hidden' value='-' name='pjs_comment'>
                            <label class="form-label" for="pjs_comment">ความคิดเห็น/ข้อเสนอแนะ</label>
                            <textarea id="textarea3" rows="2" name="pjs_comment" class="form-control"></textarea>
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
                    <div class="form-label"></div>
        </form>
        <br>


        <script src="js/slim.js"></script>
        <script src="js/popper.js"></script>
        <script src="js/bootstrap.js"></script>



</body>

</html>







<div class="container">
        <h1 class="mt-3">ลงทะเบียนใช้งานระบบ</h1>
        <hr>

        <form action="register_db.php" method="post" class="form-horizontal my-5">

            <div class="form-group">
                <label for="username" class="col-sm-3 control-label">ชื่อผู้ใช้งานระบบ</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_username" class="form-control" required placeholder="ชื่อผู้ใช้งานระบบ ภาษาอังกฤษ">
                </div>
            </div>

            <div class="form-group">
                <label for="cid" class="col-sm-3 control-label">เลขบัตรประชาชน</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_cid" class="form-control" required placeholder="เลขบัตรประชาชน">
                </div>
            </div>
            <div class="form-group">
                <label for="fname" class="col-sm-3 control-label">ชื่อ </label>
                <div class="col-sm-12">
                    <input type="text" name="txt_fname" class="form-control" required placeholder="ชื่อ ระบุคำหน้าด้วย">
                </div>
            </div>
            <div class="form-group">
                <label for="lname" class="col-sm-3 control-label">สกุล</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_lname" class="form-control" required placeholder="สกุล">
                </div>
            </div>
            <div class="form-group">
                <label for="tel" class="col-sm-3 control-label">เบอร์โทรศัพท์</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_tel" class="form-control" required placeholder="เบอร์โทร">
                </div>
            </div>
            <div class="form-group">
                <label for="job" class="col-sm-3 control-label">ตำแหน่งงาน/สายงาน</label>
                <div class="col-sm-12">
                    <select name="txt_job" class="form-control">
                        <option value="" selected="selected">- ตำแหน่งงาน/สายงาน -</option>
                        <option value="1">จพ.การเงินและบัญชี</option>
                        <option value="2">จพ.ทันตสาธรณสุข</option>
                        <option value="3">จพ.ธุรการ</option>
                        <option value="4">จพ.พัสดุ</option>
                        <option value="5">จพ.พัสดุ</option>
                        <option value="6">จพ.เวชสถิติ</option>
                        <option value="7">จพ.สาธารณสุข</option>
                        <option value="8">เจ้าพนักงานเวชกิจฉุกเฉิน</option>
                        <option value="9">จพ.เวชกรรมฟื้นฟู</option>
                        <option value="10">ช่างปูน</option>
                        <option value="11">ทันตแพทย์</option>
                        <option value="12">นักกายภาพบำบัด</option>
                        <option value="13">นักจัดการงานทั่วไป</option>
                        <option value="14">นักจิตวิทยา</option>
                        <option value="15">นักเทคนิคการแพทย์</option>
                        <option value="16">นักประชาสัมพันธ์</option>
                        <option value="17">นักโภชนาการ</option>
                        <option value="18">นักรังสีการแพทย์</option>
                        <option value="19">นักวิชาการคอมพิวเตอร์</option>
                        <option value="20">นักวิชาการเงินและบัญชี</option>
                        <option value="21">นักวิชาการสถิติ</option>
                        <option value="22">นักวิชาการสาธารณสุข</option>
                        <option value="23">นักวิชาการสาธารณสุข(เวชสถิติ)</option>
                        <option value="24">นายช่างเทคนิค</option>
                        <option value="25">นายแพทย์</option>
                        <option value="26">ผู้ช่วยเจ้าหน้าที่สาธารณสุข</option>
                        <option value="27">ผู้ช่วยทันตแพทย์</option>
                        <option value="28">ผู้ช่วยพยาบาล</option>
                        <option value="29">ผู้ช่วยแพทย์แผนไทย</option>
                        <option value="30">พนักงานแพทย์และรังสีเทคนิค</option>
                        <option value="31">พนักงานเกษตรพื้นฐาน</option>
                        <option value="32">พนักงานขับรถยนต์</option>
                        <option value="33">พนักงานช่วยเหลือคนไข้</option>
                        <option value="34">พนักงานธุรการ</option>
                        <option value="35">พนักงานบริการ</option>
                        <option value="36">พนักงานประกอบอาหาร</option>
                        <option value="37">พนักงานประจำห้องยา</option>
                        <option value="38">พนักงานแปล</option>
                        <option value="39">พนักงานพัสดุ</option>
                        <option value="40">พนักงานพิมพ์</option>
                        <option value="41">พนักงานโสตทัศนศึกษา</option>
                        <option value="42">พยาบาลวิชาชีพ</option>
                        <option value="43">แพทย์แผนไทย</option>
                        <option value="44">เภสัชกร</option>
                        <option value="45">หัวหน้าพยาบาล พยาบาลวิชาชีพ</option>
                        <option value="46">พนักงานทั่วไป</option>
                        <option value="47">จพ.เครื่องคอมพิวเตอร์</option>
                        <option value="48">พนักงานช่วยเหลือคนไข้(ผู้ช่วยแพทย์แผนไทย)</option>
                        <option value="49">พนักงานซักฟอก</option>
                        <option value="50">ผู้ช่วยวิจัย</option>
                        <option value="51">นักวิชาการโสตทัศนศึกษา</option>
                        <option value="52">นักวิชาการพัสดุ</option>
                        <option value="53">เจ้าพนักงานรังสีการแพทย์</option>
                        <option value="54">ผู้ช่วยหนักกายภาพบำบัด</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="group_job" class="col-sm-3 control-label">สังกัดกลุ่มงาน/ฝ่ายงาน </label>
                <div class="col-sm-12">
                    <select name="txt_group_job" class="form-control">
                        <option value="" selected="selected">- สังกัดกลุ่มงาน/ฝ่ายงาน -</option>
                        <option value="1">กลุ่มงานบริหารงานทั่วไป</option>
                        <option value="2">กลุ่มงานเทคนิตการแพทย์</option>
                        <option value="3">กลุ่มงานทันตกรรม</option>
                        <option value="4">กลุ่มงานเภสัชและคุ้มครองผู้บริโภค</option>
                        <option value="5">กลุ่มงานทางการแพทย์</option>
                        <option value="6">กลุ่มงานโภชนศาสตร์</option>
                        <option value="7">กลุ่มงานรังสีวิทยา</option>
                        <option value="8">กลุ่มงานเวชศาสตร์ฟื้นฟู</option>
                        <option value="9">กลุ่มงานประกันสุขภาพ ยุทธศาสตร์และสารสนเทศทางการแพทย์</option>
                        <option value="10">กลุ่มงานบริการด้านปฐมภูมิและองค์รวม</option>
                        <option value="11">กลุ่มงานพยาบาล</option>
                        <option value="12">กลุ่มงานการแพทย์แผนไทยและการแพทย์ทางเลือก</option>
                        <option value="13">กลุ่มงานจิตเวชและยาเสพติด</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="position" class="col-sm-3 control-label">ตำแหน่ง</label>
                <div class="col-sm-12">
                    <select name="position" class="form-control">
                        <option value="" selected="selected">- ตำแหน่ง -</option>
                        <option value="1">ข้าราชการพลเรือนสามัญ</option>
                        <option value="2">ลูกจ้างประจำ</option>
                        <option value="3">พนักงานราชการ</option>
                        <option value="4">พนักงานกระทรวงสาธารณสุข</option>
                        <option value="5">ลูกจ้างชั่วคราว</option>
                        <option value="6">ลูกจ้างเหมา</option>
                        <option value="7">ลูกจ้างแพทย์แผนไทย</option>
                        <option value="8">ลูกจ้างชั่วคราว(รายวัน)</option>
                        <option value="9">ลูกจ้างเหมาโครงการ</option>
                        <option value="10">แพทย์หมุนเวียน</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="ms_status" class="col-sm-3 control-label">สถานภาพ</label>
                <div class="col-sm-12">
                    <select name="txt_ms_status" class="form-control">
                        <option value="" selected="selected">- สถานภาพ -</option>
                        <option value="1">โสด</option>
                        <option value="2">สมรส</option>
                        <option value="3">หม้าย</option>
                        <option value="4">หย่า</option>
                        <option value="5">แยกกันอยู่</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="rg" class="col-sm-3 control-label">ศาสนา</label>
                <div class="col-sm-12">
                    <select name="txt_rg" class="form-control">
                        <option value="" selected="selected">- ศาสนา -</option>
                        <option value="1">พุทธ</option>
                        <option value="2">อิสลาม</option>
                        <option value="3">อื่นๆ</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="f_date" class="form-label" for="date">วันที่เริ่มทำงาน</label>
                    <input class="form-control" id="date" name="f_date" placeholder="MM/DD/YYY" type="date" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="birthday" class="form-label" for="date">วันที่เกิด</label>
                    <input class="form-control" id="date" name="birthday" placeholder="MM/DD/YYY" type="date" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="addr" class="form-label">ที่อยู่</label>
                    <textarea class="form-control" name="addr" rows="3"></textarea>
                </div>
            </div>
            <label for="password" class="col-sm-3 control-label">รหัสผ่าน</label>
            <div class="col-sm-12">
                <input type="password" name="txt_password" class="form-control" required placeholder="ป้อนรหัสผ่าน">
            </div>
            <div class="form-group">
                <div class="col-sm-12 mt-3">
                    <input type="submit" name="btn_register" class="btn btn-primary" style="width: 100%;" value="ลงทะเบียน">
                </div>
            </div>

            <div class="form-group text-center">
                <div class="col-sm-12 mt-3">
                    <h8> มีบัญชีอยู่แล้ว ? </h8>
                    <p><a href="login.php">เข้าสู่ระบบ</a></p>
                </div>
            </div>

        </form>
    </div>





    ////////////
    <div class="row">
                    <div class="col">
                        <label class="form-label" for="name4">ความหนังสือ</label>
                        <span >(เลขหนังสือเชิญ)</span>
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
                        <input type="text" class="form-control" value="<?php echo $row['approval_numof']; ?>" name="approval_numof" />
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
                <div class="row mt-2">
                    <div class="col">
                        <label for="approval_self" class="col control-label">ข้าพเจ้าขออนุมัติการเข้าเข้าร่วมเองโดยเป็นการเข้าร่วม</label>
                        <div class="col-sm-12">
                            <select name="approval_self" class="form-control">
                                <option value="<?php echo $row['approval_self']; ?>" selected="selected"> <?php if ($row["approval_self"] == 'V') {
                                                                                                                echo  "เชิงวิชาชีพ";
                                                                                                            } else if ($row["approval_self"] == 'P') {
                                                                                                                echo "งานที่ต้องรับผิดชอบเพิ่มเติม";
                                                                                                            } ?> </option>
                                <option value="V">เชิงวิชาชีพ</option>
                                <option value="P">งานที่ต้องรับผิดชอบเพิ่มเติม</option>
                                <option value="N">-</option>
                            </select>
                        </div>
                    </div>

                    <div class="col">
                        <br>
                        <div class="form-check">
                            <input id='testNameHidden' type='hidden' value='N' name='approval_hsent'>
                            <input class="form-check-input" type="checkbox" value="Y" id="flexCheckChecked" name="approval_hsent" <?php if ($row['approval_hsent'] == "Y") {
                                                                                                                                    ?> checked="" <?php
                                                                                                                                            } ?>>
                            <label class="form-check-label" for="flexCheckDefault">
                                โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม
                            </label>
                        </div>
                        <div class="form-check">
                            <input id='testNameHidden' type='hidden' value='N' name='approval_invite'>
                            <input class="form-check-input" type="checkbox" value="Y" id="flexCheckDefault" name="approval_invite" <?php if ($row['approval_invite'] == "Y") {
                                                                                                                                    ?> checked="" <?php
                                                                                                                                            } ?>>
                            <label class="form-check-label" for="flexCheckDefault">
                                ผู้จัดการอบรมมีหนังสือเชิญโดยระบุชื่อหรือตำแหน่งผู้เข้ารับการอบรมชัดเจน
                            </label>
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
                <div class = "ExternalFiles text-center">
                            <iframe src="../docs/approval_in/<?php echo $row['doc_in']; ?>"  height="300px" width = "500px" >

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
                        <input type="file" name = "doc_file"  class="form-control"  value="<?php echo $row['doc_in']; ?>" accept="application/pdf"> <br>

                    </div>
                </div>

                <div class="form-group text-center">
                    <div class="col-md-12 mt-3">
                        <!-- <input type="hidden" name="id" value="<?= $row['approval_id']; ?>"> -->
                        <input type="submit" name="update" class="btn btn-primary" value="บันทึก">
                        <a href="index.php" class="btn btn-danger">ยกเลิก</a>
                    </div>
                </div>