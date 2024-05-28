<?php


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
  $conapp = $_REQUEST['appid'];
  $confirmMsg = "บันทึกข้อมูลสำเร็จ";
  include 'nofyconapp.php';


  //header('Location: admin_home.php');


} //END $_REQUEST['appid']




// if($date == $date ){
//   $date = date("Y-m-d H:i:s".strtotime("today 13:44"));
//   $select_stmt = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//   $select_stmt->execute();
//   while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//   sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

//    } }


function fiscalYear($date)
{
  // วันที่ที่ต้องการตรวจสอบ
  list($year, $month, $day) = explode("-", $date);
  // วันที่ที่ส่งมา (mktime)
  $cday = mktime(0, 0, 0, $month, $day, $year);
  // ปีงบประมาณตามค่าที่ส่งมา (mktime)
  $d1 = mktime(0, 0, 0, 10, 1, $year);
  // ปีใหม่
  $d2 = mktime(0, 0, 0, 9, 30, $year + 1);
  if ($cday >= $d1 && $cday < $d2) {
    // 1 ตค. -  ธค.

    $year++;
  }
  $year += 543;
  //echo "$date = $year <br>";
  echo $year;
}
//fiscalYear('2015-08-18'); 
//fiscalYear('2014-09-30'); 


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

      <h3><label class="form-text">ยืนยันการขออบรม</label></h3>
    </div>
    <br>

    <div class="input-group">


    </div>
    <!-- Modal -->
    <?php
    if (isset($confirmMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $confirmMsg; ?>",
            type: "success"
          }, function() {
            window.location = "admin_home.php";
          });
        }, 1000);
      </script>


    <?php  } ?>

    <!-- <?php print_r($_SESSION); ?> -->


    <!-- Modal -->


    <div class="card">
      <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
      <!-- /.card-header -->
      <div class="card-body">

        <?php
        //show data in select option
        // $stmt = $db->prepare("SELECT approval_date, CASE WHEN approval_date BETWEEN '2022-10-01' and '2023-09-30' THEN '2566' 
        //                       WHEN approval_date BETWEEN '2023-10-01' and '2024-09-30' THEN '2567' 
        //                       WHEN approval_date BETWEEN '2024-10-01' and '2025-09-30' THEN '2568' 
        //                       WHEN approval_date BETWEEN '2025-10-01' and '2026-09-30' THEN '2569' 
        //                       WHEN approval_date BETWEEN '2026-10-01' and '2027-09-30' THEN '2570' 
        //                       WHEN approval_date BETWEEN '2028-10-01' and '2029-09-30' THEN '2571' 
        //                       WHEN approval_date BETWEEN '2029-10-01' and '2030-09-30' THEN '2572' 
        //                       END a FROM approval GROUP BY a");
        // $stmt->execute();
        // $rs = $stmt->fetchAll();


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

        <table class="table table-striped thead-dark" name="from1" id="Table">

          <thead class="table-info">
            <tr>

              <th scope="col" width="5%">#</th>
              <th scope="col" width="5%">สถานะ</th>
              <th scope="col" width="40%">ชื่อโครงการ</th>
              <th scope="col" width="20%">ชื่อผู้ขอ</th>
              <!-- <th scope="col">วันที่เริ่ม</th>
       <th scope="col">วันที่สิ้นสุด</th> -->
              <th scope="col" width="10%">ค่าลงทะเบียน</th>
              <!-- <th scope="col" width="25">สถานที่</th> -->
              <!-- <th scope="col sm-3"></th> -->
              <th scope="col " width="10%">พิมพ์</th>
              <th scope="col " width="20%">ตัวเลือก</th>
            </tr>
          </thead>
          <tbody id="myTable">
            <?php
            //print_r($_POST);
            if (isset($_POST['filter'])) {

              $D = $_POST['filter']; 
              //echo  $D;
              $uid1 = $_SESSION['user_id'];

              $select_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* from approval ap left join proj_head pr on pr.approval_id = ap.approval_id left join user u on u.user_id = ap.user_id 
                              where ap.fiscalYear in ('$D') order by ap.approval_id DESC");

              $select_stmt->execute();
            } else {

              $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
              $stmtMax->execute();
              $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
              $sM = $sMax['mx'];

              $uid1 = $_SESSION['user_id'];

              //echo $sM;

              $select_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* from approval ap left join proj_head pr on pr.approval_id = ap.approval_id left join user u on u.user_id = ap.user_id 
                               where ap.fiscalYear in ('$sM')  order by ap.approval_id DESC");

              $select_stmt->execute();
            }



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
                <td><?php if ($str_sy == 'N') {

                    ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else { ?> <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?></td>
                <td><?php echo $row["approval_name"];   ?></td>
                <td><?php echo $row["f_name"];
                    echo "&nbsp;" . $row["l_name"]; ?></td>
                <!-- <td><?php echo fiscalYear('2014-09-30');
                          $row["approval_fdate"]; ?></td>
         <td><?php echo $row["approval_edate"]; ?></td> -->
                <td><?php echo $row["approval_sum"]; ?> บาท</td>
                <!-- <td><?php echo $row["approval_addp"]; ?></td> -->
                <!-- <td><a href="print_approval.php" >พิมพ์ PDF </a></td> -->
                <td>
                  <?php if ($str_sy == 'Y') {  ?>
                    <a href="adminapp.php?a_id=<?php echo $row["approval_id"]; ?>" name="upd" class="bi bi-eye"></a>
                    <a target="_blank" href="../print_approval.php?appid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red"></a> <?php } ?>
                </td>
                <td><?php if ($str_sy == 'N') {
                    ?>
                    <!-- <a type="submit" href="?appid=<?php echo $row['approval_id']; ?>" class="btn btn-success btn-sm">ยืนยัน </button> -->
                    <a class="btn btn-warning btn-sm " data-bs-toggle='modal' data-bs-target='#con_<?php echo $row['approval_id']; ?>' name="#">ยืนยัน
                    <?php } else { ?> <button type="button" class="btn btn-info btn-sm">ยืนยันแล้ว</button><?php } ?>
                </td>

                <?php
                include 'modal.php';
                ?>


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
    </style>

  </section>
  <!-- /.content -->
</div>



</html>



</body>

</html>