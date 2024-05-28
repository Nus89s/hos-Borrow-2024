<?php


if (isset($_REQUEST['consum'])) {
  $conapp = $_REQUEST['consum'];
  $conap = $db->prepare("UPDATE `proj_head` SET pjs_st = 'Y' WHERE `approval_id` = :appid;");

  $conap->bindParam(':appid',$conapp);
  // $conap->execute();
  $conap->execute();
  $confirmMsg = "บันทึกข้อมูลสำเร็จ";
  //header('Location: sumresults.php');


  include 'nofyconapp.php';
  



  
} //END $_REQUEST['appid']




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
  
    <h3><label class="form-text">บันทึกสรุปอบรม</label></h3>
    </div>
    <br>

    <div class="input-group">


    </div>
    <!-- Modal -->
    <?php

    

$uid1 = $_SESSION['user_id'];
$select = $db->prepare("SELECT u.*, g.g_name FROM `user` u left join group_job g on g.g_id = u.g_id WHERE user_id = :uid1 ");
$select->bindParam(':uid1', $uid1);
$select->execute();

$row = $select->fetch(PDO::FETCH_ASSOC)

?>

<div class="card">
              <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">

              
    <?php
    if (isset($confirmMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $confirmMsg; ?>",
            type: "success",
            timer: 1500,
          }, function() {
            window.location = "sumresults.php"; 
          });
        }, 150);
      </script>

    <?php
    } ?>


              <table id="Table" class="table table-striped thead-dark" name="from1">
      <thead class="table-info">
        <tr class="text-center">
          <th scope="col">#</th>
          <th scope="col">สถานะ</th>
          <th scope="col">ชื่อโครงการ</th>
          <th scope="col">ชื่อผู้ขอ</th>
          <th scope="col">วันที่เริ่ม</th>
          <th scope="col">วันที่สิ้นสุด</th>
          <th scope="col">ค่าลงทะเบียน</th>
          <th scope="col">สถานที่</th>
          <th scope="col sm-3"></th>
          <th scope="col">พิมพ์</th>
          <th scope="col " class="text-center">ตัวเลือก</th>
        </tr>
      </thead>
      <tbody id="myTable">
        <?php

     

        $uid1 = $_SESSION['user_id'];

        $select_stmt = $db->prepare("SELECT * from proj_summary pv
        left join approval ap on ap.approval_id = pv.approval_id
        left join proj_head pr on pr.approval_id = ap.approval_id
        left join user u on u.user_id = pv.user_id where pr.pjs_st in ('W','Y') ORDER BY pv.pjs_id DESC");

        $select_stmt->execute();

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;
        ?>
          <tr class="text-center">
          <?php
          // $ST = $row["approval_st"];

          if ($row["pjs_st"] == 'Y') {
            $str_sy = 'Y';
          } else {
            $str_sy = 'W';
          }
          ?>
          <style>
            .icon-green {
                color: green;
                font-size: 25px;
              }
              .icon-yellow{
                color: darkorange;
                font-size: 25px;
              }
              .icon-red{
                color: darkred;
                font-size: 20px;
              }
          </style>
            <td><?php echo $x ?></td>
            <td><?php if ($str_sy == 'W'){
              
             ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else {?> <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?></td>
            <td><?php echo $row["approval_name"]; ?></td>
            <td><?php echo $row["f_name"];
                echo "&nbsp;" . $row["l_name"]; ?></td>
            <td><?php echo $row["approval_fdate"]; ?></td>
            <td><?php echo $row["approval_edate"]; ?></td>
            <td><?php echo $row["approval_sum"]; ?> บาท</td>
            <td><?php echo $row["approval_addp"]; ?></td>
            <td> <a  href="consumresults.php?a_id=<?php echo $row["approval_id"]; ?>" name = "upd" class="bi bi-eye"></a></td>
            <td><?php if ($str_sy == 'Y'){
              ?><a target="_blank" href="../print_sumresults.php?pjsid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red" ></a> <?php } ?>   </td>
            <td><?php if ($str_sy == 'W'){
              ?>
              <a type="submit" href = "?consum=<?php echo $row["approval_id"]; ?>"  class="btn btn-success btn-sm"  >ยืนยัน</button>
              <!-- <a type="submit" href = "#"    class="btn btn-success btn-sm"  >ยืนยัน</button> -->
              <?php } else {?> <button type="button" class="btn btn-info btn-sm">ยืนยันแล้ว</button><?php } ?>
            </td>


            <!-- <td><a href="?delete_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger">ลบ</a></td> -->
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
  <!-- /.content-wrapper -->
 

</html>