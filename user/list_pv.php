<title>หนังสือส่งอนุมัติจังหวัด</title>
<?php

include 'header.php';
include 'nav.php';
include 'sidebar.php'

?>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
      </div>
    </div>

    <section class="content">

    <div class="container text-center">
    <h3><label class="form-text">หนังสือส่งอนุมัติจังหวัด</label></h3>
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
        $stmt = $db->prepare("SELECT fiscalYear FROM approval  GROUP by fiscalYear");
        $stmt->execute();
        $rs = $stmt->fetchAll();


        $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
        $stmtMax->execute();
        $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
        $sM = $sMax['mx'];
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
              <table id="Table" class="table table-striped " name="from1">
      <thead class="table-info">
        <tr >
          <th scope="col" width="5%">#</th>
          <th scope="col" width="5%">สถานะ</th>
          <th scope="col"width="55%">ชื่อโครงการ</th>
          <th scope="col" width="20%" >ชื่อผู้ขอ</th>
          <!-- <th scope="col">เลขที่ขออนุมัติ</th>
          <th scope="col">เลขที่หนังสือจังหวัด</th> -->
          <!-- <th scope="col">สถานที่</th> -->
          <th scope="col" width="5%">พิมพ์</th>
          <th scope="col" width="10%">แก้ไข</th>

        </tr>
      </thead>
      <tbody id="myTable">
        <?php
        if (isset($_POST['filter'])) {

          $D = $_POST['filter']; 
          //echo  $D;
          $uid1 = $_SESSION['user_id'];

          $select_stmt = $db->prepare("SELECT * from approval_prov pv
          left join approval ap on ap.approval_id = pv.approval_id
          left join proj_head pr on pr.approval_id = ap.approval_id
          left join user u on u.user_id = pv.user_id where ap.user_id = :uid1 and pv.appr_status in ('W','N','Y') and ap.fiscalYear in ('$D') ORDER by ap.approval_id DESC");
          $select_stmt->bindParam(':uid1', $uid1);
          $select_stmt->execute();
        } else {

          
          
                  $uid1 = $_SESSION['user_id'];
                 
          
                  $select_stmt = $db->prepare("SELECT * from approval_prov pv
                  left join approval ap on ap.approval_id = pv.approval_id
                  left join proj_head pr on pr.approval_id = ap.approval_id
                  left join user u on u.user_id = pv.user_id where ap.user_id = :uid1 and pv.appr_status in ('W','N','Y') and ap.fiscalYear in ('$sM') ORDER by ap.approval_id DESC");
                  $select_stmt->bindParam(':uid1', $uid1);
                  $select_stmt->execute();


        }

     

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;
        ?>
          <tr >
          <?php
          // $ST = $row["approval_st"];

          if ($row["appr_status"] == 'N') {
            $str_sy = 'N';
          } else if ($row["appr_status"] == 'W') {
            $str_sy = 'W';
          } else {
            $str_sy = 'Y';
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
            <td><?php if ($str_sy == 'N'){
              
             ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-red"></i><?php } else if ($str_sy == 'W') {?> <i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else {?>
             <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?>
            
            </td>
            <td><?php echo $row["approval_name"]; ?></td>
            <td><?php echo $row["f_name"];
                echo "&nbsp;" . $row["l_name"]; ?></td>
            <!-- <td><?php echo $row["approval_number"]; ?></td>
            <td><?php echo $row["appr_number"]; ?></td> -->
            <!-- <td><?php echo $row["approval_addp"]; ?></td> -->
            <!-- <td><a href="print_approval.php" >พิมพ์ PDF </a></td> -->
            <td><?php if ($str_sy == 'Y'){ ?> <a href="../docs/app_prov/con_pv/<?php echo $row['doc_pv']; ?>" target="_blank" class="bi bi-file-earmark-pdf-fill icon-red" name = "printpv"> </a>
          
            <?php }?>  </td>
          
            <td> 
            <?php if ($str_sy == 'N'){
              ?><a class="btn btn-success btn-sm" name = "conpv" >รอการยืนยัน</button>
              <?php } else if (($str_sy == 'W')){?> <a class="btn btn-warning btn-sm" name = "scanpv">รอจังหวัดอนุมัติ<?php 
              } else {?><button type="button" class="btn btn-info btn-sm">อนุมัติแล้ว</button> <?php }?>
              
              <!-- <a   class="bi bi-pencil-fill"href="editappv.php?appr_id=<?php echo $row['appr_id']; ?>" name = "uppv" >
                  </a> -->
                
                
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
 
</html>
<?php 
include 'footer.php'
?>