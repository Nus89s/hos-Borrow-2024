<title>บันทึกการยืมเงิน</title>
<?php
include 'header.php';



if (isset($_REQUEST['upbo'])) {
  $approval_id = $_REQUEST['approval_id'];
  $borrow_date = $_REQUEST['borrow_date'];
  // $borrow_sum = $_REQUEST['borrow_sum'];
  $borrow_allw = $_REQUEST['borrow_allw'];
  $borrow_accom = $_REQUEST['borrow_accom'];
  $borrow_veh = $_REQUEST['borrow_veh'];
  $borrow_regis = $_REQUEST['borrow_regis'];
  $borrow_reward = $_REQUEST['borrow_reward'];
  $borrow_com = $_REQUEST['borrow_com'];
  $borrow_another = $_REQUEST['borrow_another'];
  // $borrow_turn_haft = $_REQUEST['borrow_turn_haft'];
  // $borrow_turn_month = $_REQUEST['borrow_turn_month'];

  $sum = $borrow_allw + $borrow_accom + $borrow_veh + $borrow_regis + $borrow_reward + $borrow_another;


  $sql = $db->prepare("UPDATE borrow SET borrow_date = :borrow_date,borrow_date = :borrow_date ,borrow_sum = :sum ,
                            borrow_allw = :borrow_allw,borrow_accom = :borrow_accom,borrow_veh = :borrow_veh,
                            borrow_regis = :borrow_regis,borrow_reward = :borrow_reward,borrow_com = :borrow_com,borrow_another = :borrow_another
                            WHERE approval_id = :approval_id");


  $sql->bindParam(":approval_id", $approval_id);
  $sql->bindParam(":borrow_date", $borrow_date);
  // $sql->bindParam(":borrow_sum", $borrow_sum);
  $sql->bindParam(":borrow_allw", $borrow_allw);
  $sql->bindParam(':borrow_accom', $borrow_accom);
  $sql->bindParam(':borrow_veh', $borrow_veh);
  $sql->bindParam(':borrow_regis', $borrow_regis);
  $sql->bindParam(':borrow_reward', $borrow_reward);
  $sql->bindParam(':borrow_com', $borrow_com);
  $sql->bindParam(':borrow_another', $borrow_another);
  $sql->bindParam(':sum', $sum);

  // $sql->bindParam(':borrow_turn_haft', $borrow_turn_haft);
  // $sql->bindParam(':borrow_turn_month', $borrow_turn_month);
  if ($sql->execute()) {


    $approval_id = $_REQUEST['approval_id'];
    $upbor = $db->prepare("SELECT * from borrow where approval_id = :approval_id ");
    $upbor->bindParam(":approval_id", $approval_id);
    $upbor->execute();
    $upbor_id = $upbor->fetch(PDO::FETCH_ASSOC);
    $borrow_id = $upbor_id['borrow_id'];
    $approval_id = $upbor_id['approval_id'];

    $con_b = $db->prepare("UPDATE `proj_head` SET `borrow_st` = 'W'   WHERE `approval_id` = :apprid;");
    $con_b->bindParam(':apprid', $approval_id);
    $con_b->execute();
    $bormesg = "บันทึกข้อมูลสำเร็จ";
    //header("refresh:5;list_borrow.php");
  }



  // $upborow = $db->prepare("UPDATE `proj_head` SET `borrow_id` = ':borrow_id'  WHERE `approval_id` = :approval_id;");
  // $upborow->bindParam(":borrow_id", $borrow_id);
  // $upborow->bindParam(":approval_id", $approval_id);
  // $upbor->execute();


  // if ($sql) {
  //     $_SESSION['success'] = "Data has been updated successfully";
  //     header("location: list_borrow.php");
  // } else {
  //     $_SESSION['error'] = "Data has not been updated successfully";
  header("location: list_borrow.php");
  // }
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

// if (isset($_REQUEST['resum'])) {
//     $borrow_allw = $_REQUEST['borrow_allw'];
//     $borrow_accom = $_REQUEST['borrow_accom'];
//     $borrow_veh = $_REQUEST['borrow_veh'];
//     $borrow_regis = $_REQUEST['borrow_regis'];
//     $borrow_reward = $_REQUEST['borrow_reward'];
//     //$borrow_com = $_REQUEST['borrow_com'];
//     $borrow_another = $_REQUEST['borrow_another'];

//     $total = $borrow_allw + $borrow_accom + $borrow_veh + $borrow_regis + $borrow_reward  + $borrow_another ;

//     echo $borrow_allw ;
//     echo $borrow_accom ;
//     echo $borrow_veh ;
//     echo $borrow_regis ;
//     echo $borrow_reward ;
//     echo $borrow_another  ;

// }
include 'nav.php';
include 'sidebar.php';
?>

<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">

    </div>
  </div>

  <section class="content">

    <div class="container text-center">
      <!-- <h4>อนุมัติหนังสือส่งจังหวัด</h4> -->
      <h3><label class="form-text">บันทึกการยืมเงิน</label></h3>
    </div>
    <br>

    <div class="input-group">


    </div>
    <!-- Modal -->
    <div class="card-body">


      <form id="myform2" action="add_borrow.php" method="post" enctype="multipart/form-data">
        <?php
        if (isset($_REQUEST['addborrow'])); {

          $ar_id = $_REQUEST['approval_id'];
          $co = $db->prepare("SELECT ap.*,g.g_name from approval ap 
            left join user u on u.user_id = ap.user_id
            left join group_job g on g.g_id = u.user_id where approval_id = :pid");

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
              <input type="date" value="<?php echo $date; ?>" class="form-control" name="borrow_date" readonly placeholder="<?php echo $date; ?>">
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
                  <div class="row">
                    <div class="col">
                      <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                      <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" readonly required>
                    </div>


                    <br>
                    <div class="row">

                      <div class="col">
                        <br>
                        <input id='borrow_allwHidden' type='hidden' value='0' name='borrow_allw'>
                        <label class="form-label" for="borrow_allw">ค่าเบี้ยเลี้ยง</label>

                        <input type="text" required class="form-control" name="borrow_allw">
                      </div>

                      <div class="col">
                        <br>
                        <input id='borrow_accomHidden' type='hidden' value='0' name='borrow_accom'>
                        <label class="form-label" for="borrow_accom">ค่าเช่าที่พัก</label>

                        <input type="text" class="form-control" name="borrow_accom" />
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_vehHidden' type='hidden' value='0' name='borrow_veh'>
                        <label class="form-label" for="borrow_veh">ค่าพาหนะ</label>

                        <input type="text" class="form-control" name="borrow_veh" />
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_regis' type='hidden' value='0' name='borrow_regis'>
                        <label class="form-label" for="borrow_regis">ค่าลงทะเบียน</label>

                        <!-- <input type="text" class="form-control" name="borrow_regis" /> -->
                        <input type="text" value="<?php echo $row['approval_sum']; ?>" required readonly class="form-control" name="borrow_regis">
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_rewardHidden' type='hidden' value='0' name='borrow_reward'>
                        <label class="form-label" for="borrow_reward">ค่าสมนาคุณ</label>

                        <input type="text" class="form-control" name="borrow_reward" />
                      </div>
                      <div class="col">
                        <br>
                        <input id="borrow_anotherHidden" type="hidden" value="0" name="borrow_another">

                        <label class="form-label" for="borrow_another">อื่นๆ</label>

                        <input type="text" class="form-control" name="borrow_another" />
                      </div>

                    </div>

                  </div>
                  <div class="form-outline mb-4">
                    <input id='borrowcomHidden' type='hidden' value='-' name='borrow_com'>
                    <label class="form-label" for="borrow_com">หมายเหตุ</label>
                    <textarea id="textarea3" rows="2" name="borrow_com" class="form-control"></textarea>
                  </div>
                  <div class="form-group text-center">
                    <div class="col-md-12 mt-3">
                      <input type="hidden" name="id" value="<?= $row['approval_id']; ?>">
                      <input type="submit" name="upbo" class="btn btn-primary" value="บันทึก">
                      <a href="list_borrow.php" class="btn btn-danger">ยกเลิก</a>
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

</html>

<?php 
include 'footer.php' ?>