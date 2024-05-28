<?php 
session_start();
if (!isset($_SESSION['user_id'])) { // ถ้าไม่ได้เข้าระบบอยู่
  header("location: ../login.php"); // redirect ไปยังหน้า login.php
  exit;
}
// ('login_action.php');

require_once('../conn.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link rel="website icon" type="png" href="../bor.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
  <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
  <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<style type="text/css">
		body{
			font-family: 'Prompt', sans-serif;
		}
	</style>

</head>


<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<title>จัดการข้อมูลผู้ใช้</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
include 'nav_admit.php';
include 'sidebar_admin.php';


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

if (isset($_REQUEST['delete_id'])) {
    $id = $_REQUEST['delete_id'];
  
    $delete_stmt = $db->prepare('DELETE FROM user WHERE user_id = :id');
    $delete_stmt->bindParam(':id', $id);
    $delete_stmt->execute();

    $deleteMsg = "ลบข้อมูลสำเร็จ";
  
    //header("refresh:5;index.php");
  } //END delete
  

if(isset($_REQUEST['aadmin'])){

    $aduser = $_REQUEST['ad_name'];
    $adpass = $_REQUEST['ad_pass'];
    $adfname = $_REQUEST['ad_fname'];
    $adlname = $_REQUEST['ad_lname'];
    $adtel = $_REQUEST['tel'];
    $adposition = $_REQUEST['position'];
    $adjob = $_REQUEST['job'];
    $adgroupjob = $_REQUEST['group_job'];

    
    $stmtad = $db->prepare("INSERT INTO user(user_name,  f_name, l_name, tel, job_id, g_id, pos_id, password,status) 
                            VALUES (:aduser, :adfname, :adlname, :adtel, :adjob, :adgroupjob, :adposition, :adpass,'admin')");
    //bindParam data type
    //$stmt2->bindParam(':approval_date',$approval_date);
    $stmtad->bindParam(':aduser', $aduser);
    $stmtad->bindParam(':adpass', $adpass);
    $stmtad->bindParam(':adfname', $adfname);
    $stmtad->bindParam(':adlname', $adlname);
    $stmtad->bindParam(':adtel', $adtel);
    $stmtad->bindParam(':adposition', $adposition);
    $stmtad->bindParam(':adjob', $adjob);
    $stmtad->bindParam(':adgroupjob', $adgroupjob);

    if($stmtad->execute()){
        //header("refresh:2; namage_user.php");

        $stmtadMsg = "เพิ่มข้อมูลสำเร็จ";
    }



}

if (isset($_REQUEST['editad'])) {


    $user_id = $_REQUEST['user_id'];
    $user_name = $_REQUEST['user_name'];
    $newpass = $_REQUEST['newpass'];
    $edit_fname = $_REQUEST['edit_fname'];
    $edit_lname = $_REQUEST['edit_lname'];
    $edit_tel = $_REQUEST['edit_tel'];
    $edit_position = $_REQUEST['edit_position'];
    $edit_job = $_REQUEST['edit_job'];
    $edit_group_job = $_REQUEST['edit_group_job'];
    $edit_ms_status = $_REQUEST['edit_ms_status'];
    $line_user_id = $_POST['line_user_id'];

    $edit = $db->prepare("UPDATE `user` SET user_name = :user_name , password = :newpass,
                        f_name = :edit_fname, l_name = :edit_lname, tel = :edit_tel, job_id = :edit_job,
                        g_id = :edit_group_job, pos_id = :edit_position, line_user_id = :line_user_id WHERE `user_id` = :user_id; ");

    $edit->bindParam(':user_id',$user_id);
    $edit->bindParam(':user_name',$user_name);
    $edit->bindParam(':newpass',$newpass);
    $edit->bindParam(':edit_fname',$edit_fname);
    $edit->bindParam(':edit_lname',$edit_lname);
    $edit->bindParam(':edit_tel',$edit_tel);
    $edit->bindParam(':edit_position',$edit_position);
    $edit->bindParam(':edit_job',$edit_job);
    $edit->bindParam(':edit_group_job',$edit_group_job);
    $edit->bindParam(':line_user_id',$line_user_id);
    

    $edit->execute();
    $editMsg = "แก้ไขข้อมูลสำเร็จ";


} //endedit
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

            <h3><label class="form-text">จัดการข้อมูลผู้ใช้</label></h3>
        </div>
        <br>


        <?php
    if (isset($stmtadMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $stmtadMsg; ?>",
            type: "success"
          }, function() {
            window.location = "namage_user.php";
          });
        }, 1000);
      </script>

    <?php
    } ?>
        <?php
    if (isset($editMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $editMsg; ?>",
            type: "success"
          }, function() {
            window.location = "namage_user.php";
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
            window.location = "namage_user.php";
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

         $stmt = $db->prepare("SELECT * from position");
         $stmt->bindParam(':uid1', $uid1);
         $stmt->execute();

        ?>


        <div class="card">
            <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
            <!-- /.card-header -->
            <div class="card-body">

            <div class="d-flex flex-row-reverse bd-highlight">
            <a type="button" href="#" name="addadmin" class="btn btn-success  btn-sm" data-bs-toggle='modal' data-bs-target='#addadmin'>เพิ่มแอดมิน</a>
            </div>
                <br>
                <div class="modal fade" id="addadmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"> เพิ่มแอดมิน</h5>
                                                </button>
                                            </div>

                                            <div class="modal-body">

                                            <form method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ชื่อผู้ใช้</span>
                                                        <input type="text" class="form-control" name="ad_name"  placeholder="ภาษาอังกฤษ">
                                                    </div>
                                                    <div class="col">
                                                        <span>รหัสผ่าน</span>
                                                        <input type="password" class="form-control" name="ad_pass" placeholder="กรอกรหัสผ่านใหม่">
                                                        <!-- placeholder=<?php echo $row["password"]; ?> -->
                                                    </div>


                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ชื่อ</span>
                                                        <input type="text" class="form-control" name="ad_fname" required  >
                                                    </div>
                                                    <div class="col">
                                                        <span>สกุล</span>
                                                        <input type="text" class="form-control" name="ad_lname" required  >
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>เบอร์โทรศัพท์</span>
                                                        <input type="text" class="form-control" name="tel" required  >
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ตำแหน่ง</span>
                                                        <select name="position" class="form-control">
                                                            <option value="" selected="selected">--</option>
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
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ตำแหน่งงาน/สายงาน</span>
                                                        <select name="job" class="form-control">
                                                            <option value="" selected="selected">--</option>
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
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>สังกัดกลุ่มงาน/ฝ่ายงาน</span>
                                                        <select name="group_job" class="form-control">
                                                            <option value="" selected="selected">--</option>
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

                                                <br>

                                                <div class="modal-footer">

                                                    <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
                                                    <!-- <a type="submit"  name="aadmin" class="btn btn-success  btn-sm" >เพิ่ม</a> -->
                                                    <input type="submit" name="aadmin" class="btn btn-success  btn-sm" value="เพิ่ม">
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                
                <table id="Table" class="table table-striped thead-dark" name="from1">
                    <thead class="table-info">
                        <tr>
                            <th scope="col" width="5%">ลำดับ</th>

                            <th scope="col" width="30%">ชื่อ-สกุล</th>
                            <th scope="col" width="50%">ตำแหน่ง</th>
                            <!-- <th scope="col">กลุ่มงาน</th> -->
                            <th scope="col" width="5%">ลบ</th>
                            <th scope="col" width="5%">แก้ไข</th>

                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php
                        $uid1 = $_SESSION['user_id'];

                        if (isset($_REQUEST['pos_id'])) {
                            $pos = $_REQUEST['pos_id'];
                            $select_stmt = $db->prepare("SELECT u.*,j.job_name,p.pos_name,g.g_name,r.re_name,m.ms_name FROM `user`u 
                            left join job j on j.job_id = u.job_id
                            left join position p on p.pos_id = u.pos_id
                            left join group_job g on g.g_id = u.g_id
                            left join religion_type r on r.re_id = u.re_id
                            left join marital_status m on m.ms_id = u.ms_id 
                            where p.pos_id = $pos");
                            $select_stmt->bindParam(':uid1', $uid1);
                            $select_stmt->execute();


                        }

                        $select_stmt = $db->prepare("SELECT u.*,j.job_name,p.pos_name,g.g_name,r.re_name,m.ms_name FROM `user`u 
                        left join job j on j.job_id = u.job_id
                        left join position p on p.pos_id = u.pos_id
                        left join group_job g on g.g_id = u.g_id
                        left join religion_type r on r.re_id = u.re_id
                        left join marital_status m on m.ms_id = u.ms_id 
                        ");
                        $select_stmt->bindParam(':uid1', $uid1);
                        $select_stmt->execute();
                                
                       

                        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

                            global $x;
                            $x++;


                            // $ST = $row["approval_st"];

                            //   if ($row["borrow_st"] == 'W') {
                            //     $str_sy = 'N';
                            //   } else {
                            //     $str_sy = 'Y';
                            //   }

                        ?>
                            <tr>
                                <td><?php echo $x ?></td>

                                <td> 
                                    <?php echo $row["f_name"];
                                    echo "&nbsp;" . $row["l_name"]; ?>
                                    <?php if($row["status"] == "admin") { ?>
                                       <span class = "text-danger">* </span>
                                        <?php }?>
                                        
                                    
                                </td>
                                <td><?php echo $row["job_name"]; ?></td>
                                <!-- <td><?php echo $row["g_name"]; ?></td> -->
                                <td class="text-center"><a type="button" href="?delete_id=<?php echo $row['user_id']; ?>" class="btn btn-danger  btn-sm">ลบ</a>
                                </td>
                                <td><a class="btn btn-warning btn-sm " data-bs-toggle='modal' data-bs-target='#edit_<?php echo $row['user_id']; ?>' name="#">แก้ไข
                                   
                                </td>

                                <div class="modal fade" id="edit_<?php echo $row['user_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog " role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel"> แก้ไขข้อมูลผู้ใช้</h5>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ชื่อผู้ใช้</span>
                                                        <input type="text" class="form-control" name="user_name" readonly value=<?php echo $row["user_name"]; ?>>
                                                    </div>
                                                    <div class="col">
                                                        <span>รหัสผ่าน</span>
                                                        <input type="password" class="form-control" name="newpass" placeholder="กรอกรหัสผ่านใหม่">
                                                        <!-- placeholder=<?php echo $row["password"]; ?> -->
                                                    </div>


                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ชื่อ</span>
                                                        <input type="text" class="form-control" name="edit_fname" required readonly value=<?php echo $row["f_name"];?>>
                                                    </div>
                                                    <div class="col">
                                                        <span>สกุล</span>
                                                        <input type="text" class="form-control" name="edit_lname" required readonly value=<?php echo  $row["l_name"]; ?>>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>เบอร์โทรศัพท์</span>
                                                        <input type="text" class="form-control" name="edit_tel" required  value=<?php echo $row["tel"];  ?>>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ตำแหน่ง</span>
                                                        <select name="edit_position" class="form-control">
                                                            <option value=<?php echo $row["pos_id"]; ?> selected="selected"><?php echo $row["pos_name"]; ?></option>
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
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>ตำแหน่งงาน/สายงาน</span>
                                                        <select name="edit_job" class="form-control">
                                                            <option value=<?php echo $row["job_id"];  ?> selected="selected"><?php echo $row["job_name"];  ?></option>
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
                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>สังกัดกลุ่มงาน/ฝ่ายงาน</span>
                                                        <select name="edit_group_job" class="form-control">
                                                            <option value=<?php echo $row["g_id"]; ?> selected="selected"><?php echo $row["g_name"]; ?></option>
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

                                                <br>
                                                <div class="row">
                                                    <div class="col">
                                                        <span>สถานภาพ</span>
                                                        <select name="edit_ms_status" class="form-control">
                                                            <option value=<?php echo $row["ms_id"];  ?> selected="selected"><?php echo $row["ms_name"];  ?></option>
                                                            <option value="1">โสด</option>
                                                            <option value="2">สมรส</option>
                                                            <option value="3">หม้าย</option>
                                                            <option value="4">หย่า</option>
                                                            <option value="5">แยกกันอยู่</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <br>
                                                <?php if ($row['line_user_id'] != '') { ?>
                                                    <div class="row">
                                                        <div class="col">
                                                            <span>Line User id</span>
                                                            <input type="text" class="form-control" name="showline_user_id" required readonly value=<?php echo $row["line_user_id"];  ?>>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="row">
                                                        <div class="col">
                                                            <span>Line User id</span>
                                                            <input type="text" class="form-control" name="line_user_id"   value=<?php echo $row["line_user_id"];  ?>>
                                                        </div>
                                                    </div>
                                               <?php }?>

                                                <div class="modal-footer">

                                                    <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
                                                    <input type="hidden" name="user_id" value="<?= $row['user_id'];?>">
                                                    <input type="submit" name="editad" class="btn btn-danger  btn-sm" value="ตกลง"></input>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>



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
<!-- /.content-wrapper -->

<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


</html>
<?php
include 'footer.php'
?>