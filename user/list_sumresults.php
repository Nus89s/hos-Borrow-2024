<title>บันทึกสรุปการอบรม</title>
<ii?php
  include 'header.php';
  include 'nav.php';
  include 'sidebar.php';

if (isset($_REQUEST['delete_id'])) {
  $id = $_REQUEST['delete_id'];
  $delete_stmt = $db->prepare('DELETE FROM proj WHERE id = :id');
  $delete_stmt->bindParam(':id', $id);
  $delete_stmt->execute();

  header('Location:index.php');
} //END delete

if (isset($_REQUEST['btn_cancel'])) {

  header("index.php");
}



if (isset($_REQUEST['btn_insert'])) {
  $uid = $_SESSION['user_id'];
  $approval_in_name = $_REQUEST['approval_in_name'];
  $approval_in_date = $_REQUEST['approval_in_date'];
  $approval_number = $_REQUEST['approval_number'];
  $approval_date = $_REQUEST['approval_date'];
  $approval_name = $_REQUEST['approval_name'];
  $approval_type = $_REQUEST['approval_type'];
  $approval_organ = $_REQUEST['approval_organ'];
  $approval_addp = $_REQUEST['approval_addp'];
  $approval_fdate = $_REQUEST['approval_fdate'];
  $approval_edate = $_REQUEST['approval_edate'];
  $approval_sum = $_REQUEST['approval_sum'];
  $approval_numof = $_REQUEST['approval_numof'];
  $approval_self = $_REQUEST['approval_self'];
  // $approval_hsent = $_REQUEST['approval_hsent'];
  $approval_hsent = $_REQUEST['approval_hsent'];
  $approval_invite = $_REQUEST['approval_invite'];
  $approval_obj = $_REQUEST['approval_obj'];
  $approval_benf = $_REQUEST['approval_benf'];
  $approval_ex = $_REQUEST['approval_ex'];

  $insert_stmt = $db->prepare("INSERT INTO approval(user_id,approval_number,approval_in_name,approval_in_date, approval_date,approval_name,approval_type,approval_organ,approval_addp,approval_fdate,approval_edate,approval_sum,approval_numof,approval_self,approval_hsent,approval_invite,approval_obj,approval_benf,approval_ex) 
                               VALUES (:uid ,:approval_number,:approval_in_name,:approval_in_date, :approval_date, :approval_name, :approval_type, :approval_organ , :approval_addp,:approval_fdate, :approval_edate,:approval_sum,:approval_numof,:approval_self, :approval_hsent,:approval_invite,:approval_obj,:approval_benf,:approval_ex)");

  $insert_stmt->bindParam(':uid', $uid);
  $insert_stmt->bindParam(':approval_number', $approval_number);
  $insert_stmt->bindParam(':approval_in_name', $approval_in_name);
  $insert_stmt->bindParam(':approval_in_date', $approval_in_date);
  $insert_stmt->bindParam(':approval_date', $approval_date);
  $insert_stmt->bindParam(':approval_name', $approval_name);
  $insert_stmt->bindParam(':approval_type', $approval_type);
  $insert_stmt->bindParam(':approval_organ', $approval_organ);
  $insert_stmt->bindParam(':approval_addp', $approval_addp);
  $insert_stmt->bindParam(':approval_fdate', $approval_fdate);
  $insert_stmt->bindParam(':approval_edate', $approval_edate);
  $insert_stmt->bindParam(':approval_sum', $approval_sum);
  $insert_stmt->bindParam(':approval_numof', $approval_numof);
  $insert_stmt->bindParam(':approval_self', $approval_self);
  $insert_stmt->bindParam(':approval_hsent', $approval_hsent);
  $insert_stmt->bindParam(':approval_invite', $approval_invite);
  $insert_stmt->bindParam(':approval_obj', $approval_obj);
  $insert_stmt->bindParam(':approval_benf', $approval_benf);
  $insert_stmt->bindParam(':approval_ex', $approval_ex);
  if ($insert_stmt->execute()) {

    $lastID = $db->lastInsertId();

    //echo $lastID;

    $stmt2 = $db->prepare("INSERT INTO proj_head (prodate, user_id ,approval_id,approval_st)VALUES (:approval_date,$uid,$lastID, 'N')");
    //bindParam data type
    $stmt2->bindParam(':approval_date', $approval_date);
    $result2 = $stmt2->execute();


    header("refresh:2;index.php");
  }
  sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่เริ่ม: " . $approval_fdate . '  ' . "วันที่สิ้นสุด: " . $approval_edate);
} //END ----insert 



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
    <h4><label class="form-text">บันทึกสรุปการอบรม</label></h4>
    </div>
    <br>

    <?php
    if (isset($errorMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $errorMsg; ?>",
            type: "error"
          }, function() {
            window.location = "index.php"; 
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
            window.location = "index.php"; 
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
            window.location = "index.php"; 
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
<table  id="Table" class="table table-striped thead-dark" name="from1">
      <thead class="table-info">
        <tr>
          <th scope="col">ลำดับ</th>
          <th scope="col">สถานะ</th>
          <!-- <th scope="col">เลขที่ขออนุมัติส่งจังหวัด</th> -->
          <th scope="col">ชื่อโครงการ</th>
          <th scope="col">วันที่เริ่ม</th>
          <th scope="col">วันที่สิ้นสุด</th>
          <th scope="col">สถานที่</th>
          <th scope="col">พิมพ์</th>
          <th scope="col">action</th>
        </tr>
      </thead>
      <tbody id="myTable">
        <?php
        $uid1 = $_SESSION['user_id'];

        $select_stmt = $db->prepare("SELECT ps.*, ap.* , u.* , av.*,pr.* from proj_summary ps 
        left join approval ap on ap.approval_id = ps.approval_id
        left join user u on u.user_id = ps.user_id 
        left join approval_prov av on av.approval_id = ps.approval_id
        left join proj_head pr on pr.approval_id = ap.approval_id
        where ap.user_id = :uid1 and pr.borrow_st = 'Y' and pr.pjs_st in ('N','Y','W') ");

// and pr.appr_st = 'Y' and pr.borrow_st in ('N','Y','W')
        $select_stmt->bindParam(':uid1', $uid1);
        $select_stmt->execute();

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;

          if ($row["pjs_st"] == 'N') {
            $str_sy = 'N';
          } else {
            $str_sy = 'Y';
          }

          if ($row["pjs_sumprice"] == '0'){
            $pjs_sum = 'Y';
          }else{
            $pjs_sum = 'N';
          }
          

        ?>
          <tr>
            <td><?php echo $x ?></td>
            <td ><?php if ($str_sy == 'N'){
              
              ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-red "></i><?php } else if ($row["pjs_st"] =='W') {?> <i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else {?>
              <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?>
             </td>
            <!-- <td><?php echo $row["appr_number"]; ?> </td> -->
            <td><?php echo $row["approval_name"]; ?></td>
            <td><?php echo $row["approval_fdate"]; ?></td>
            <td><?php echo $row["approval_edate"]; ?></td>
            <td><?php echo $row["approval_addp"]; ?></td>
            <td><a target="_blank" href="../print_sumresults.php?pjsid=<?php echo $row["approval_id"]; ?>" class="bi bi-file-earmark-pdf-fill icon-red"></a></td>
            <td><?php if ($str_sy == 'N' ) { ?><a class="btn btn-warning btn-sm " href="add_sumresults.php?approval_id=<?php echo $row['approval_id']; ?>" name = "addsum">บันทึกสรุป
                <?php }  ?> 

            </td>

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
 
</html>
<?php 
include 'footer.php'
?>