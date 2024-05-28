<title>สรุปสถิติ</title>
<?php
include 'head_admin.php';
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


if (isset($_REQUEST['appid'])) {
  $conapp = $_REQUEST['appid'];
  $conap = $db->prepare("UPDATE `proj_head` SET `approval_st` = 'Y' ,appr_st = 'N' WHERE `approval_id` = :appid;");

  $conap->bindParam(':appid', $conapp);
  // $conap->execute();
  if ($conap->execute()) {

    //$lastID = $db->lastInsertId();

    //echo $lastID;
    $conapp = $_REQUEST['appid'];
    $select = $db->prepare("select * from approval where `approval_id` = :appid");
    $select->bindParam(':appid', $conapp);
    $select->execute();
    $row1 = $select->fetch(PDO::FETCH_ASSOC);

    $uid = $row1['user_id'];
    $conapp = $_REQUEST['appid'];
    $approval_number = $row1['approval_number'];
    $approval_name = $row1['approval_name'];


    //echo $uid;


    $stmt2 = $db->prepare("INSERT INTO approval_prov (user_id ,approval_id,approval_name,approval_number,appr_status) VALUES (:user_id,:appid,:approval_name,:approval_number,'N')");
    //bindParam data type
    //$stmt2->bindParam(':approval_date',$approval_date);
    $stmt2->bindParam(':user_id', $uid);
    $stmt2->bindParam(':appid', $conapp);
    $stmt2->bindParam(':user_id', $uid);
    $stmt2->bindParam(':approval_number', $approval_number);
    $stmt2->bindParam(':approval_name', $approval_name);

    if ($result2 = $stmt2->execute()) {

      $lastID = $db->lastInsertId();

      //echo $lastID;

      $uapid = $db->prepare("UPDATE proj_head SET  appr_id = $lastID WHERE approval_id = $conapp");
      //bindParam data type
      //$uapid->execute();
      if ($uapid->execute()) {
      }
    }



    // header("refresh:2;index.php");
  }


  $confirmMsg = "บันทึกข้อมูลสำเร็จ";
  //header('Location: admin_home.php');


} //END $_REQUEST['appid']




// if($date == $date ){
//   $date = date("Y-m-d H:i:s".strtotime("today 13:44"));
//   $select_stmt = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//   $select_stmt->execute();
//   while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//   sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

//    } }
?>





<?php



$uid1 = $_SESSION['user_id'];
$select = $db->prepare("SELECT u.*, g.g_name FROM `user` u left join group_job g on g.g_id = u.g_id WHERE user_id = :uid1 ");
$select->bindParam(':uid1', $uid1);
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC)

?>


<!-- <table class="table table-striped thead-dark" name="from1" id="example"> -->


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

      <h3><label class="form-text">สรุปสถิติ</label></h3>
    </div>
    <br>

    <div class="card">
      <div class="card-body">
        รอกราฟ
      </div>
    </div>


    <div class="card">
      <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
      <!-- /.card-header -->
      <div class="card-body">
        <table id="Table" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example1_info">

          <thead class="table-info ">
            <tr>
              <th scope="col" width="3%">#</th>
              <th scope="col" width="20%">QRcode</th>
              <th scope="col" width="70%">รายละเอียดโครงการ</th>
              <th scope="col" width="7%">เพิ่มเติม</th>

            </tr>
          </thead>
          <tbody id="myTable">
            <?php



            $uid1 = $_SESSION['user_id'];

            $select_stmt = $db->prepare("SELECT ap.*, pr.* ,u.*,DATEDIFF(b.borrow_edate,curdate()) As DiffDate 
                               from approval ap 
                               left join proj_head pr on pr.approval_id = ap.approval_id 
                               left join user u on u.user_id = ap.user_id 
                               left join borrow b on b.approval_id = ap.approval_id ORDER by ap.approval_id DESC");

            $select_stmt->execute();

            while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

              global $x;
              $x++;
            ?>
              <tr>
                <?php
                // $ST = $row["approval_st"];

                if ($row["approval_st"] == 'N') {
                  $str_sy = 'N';
                } else {
                  $str_sy = 'Y';
                }

                $app = $row["approval_id"]
                ?>
                <style>
                  .icon-green {
                    color: green;
                    font-size: 25px;
                  }

                  .icon-yellow {
                    color: darkorange;
                    font-size: 25px;
                  }

                  .icon-red {
                    color: darkred;
                    font-size: 20px;
                  }
                </style>
                <td><?php echo $x ?></td>
                <!-- <td><img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=http://192.168.102.29/borrow/admin/stat.php?approval_id=<?php echo $row["approval_id"]; ?>/&choe=UTF-8" title="Link to my Website" /></td> -->
                <td>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=http://192.168.102.25/borrow/admin/stat.php?approval_id=<?php echo $row["approval_id"]; ?>" alt="สแกนเพื่อตรวจสอบ">
                </td>
                <td><?php echo $row["approval_name"]; ?>
                  <br>
                  <?php echo $row["f_name"]; ?>&nbsp;<?php echo $row["l_name"]; ?>
                  <br> <?php if ($row["borrow_st"] == 'Y') { ?>
                    อีก <?php echo $row["DiffDate"]; ?> วันจะครบกำหนด
                  <?php  } else if ($row["borrow_st"] == 'W') { ?>
                    กำลังดำเนินการ
                  <?php } else { ?>
                    ยังไม่ได้ทำเรื่องยืมเงิน
                  <?php } ?>

                </td>
                <td>
                  <a class="btn btn-warning btn-sm bi bi-eye-fill" href="stat.php?approval_id=<?php echo $row["approval_id"]; ?>"></a>
                </td>



                <!-- <td><a href="?delete_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger">ลบ</a></td>  href = "confirm.php?appid=<?php echo $row["approval_id"]; ?>"-->
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
<?php
include 'footer.php';
?>