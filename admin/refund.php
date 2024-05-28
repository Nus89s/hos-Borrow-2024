<title>คืนเงินโครงการ</title>
<?php 
  include 'head_admin.php';
  include 'nav_admit.php';
  include 'sidebar_admin.php';
 

if(isset($_REQUEST['e_refund'])){
   
   //$approval_id = $_REQUEST['approval_id'];
   $uid = $_SESSION['user_id'];
   $borrow_id = $_REQUEST['borrow_id'];
   $redate = $_REQUEST['redate'];
   $qty = $_REQUEST['qty'];
   $borrow_sum = $_REQUEST['approval_sum'];
   $approval_id = $_REQUEST['approval_id'];
  

  //$ck_re = $db->prepare("SELECT quantity,remaining,borrow_id,MAX(ref_id) dt FROM refund WHERE approval_id = :approval_id AND borrow_id IN ( SELECT borrow_id FROM refund GROUP BY borrow_id HAVING COUNT(*) >= 1 )");
  $ck_re = $db->prepare("SELECT ref_id,quantity,remaining,borrow_id FROM refund WHERE approval_id = :approval_id order by ref_id desc LIMIT 1");
  $ck_re->bindParam(':approval_id',$approval_id);
  $ck_re->execute();
  $row = $ck_re->fetch(PDO::FETCH_ASSOC);

  

    //$Dt = $row['dt'];
    $bor_id = $row['borrow_id'];
    $remai = $row['remaining'];

    $rmn1 = $remai - $qty;

    //echo $Dt;


    if($bor_id != ''){

    $re_to = $db->prepare("INSERT INTO refund(approval_id,borrow_id,redate,admin_id,quantity,remaining,borrow_sum)
                           Value (:approval_id,:borrow_id,:redate,:uid,:qty,:remaining,:borrow_sum) ");
                          
    // $re_to->bindParam(':dt',$Dt);
    $re_to->bindParam(':borrow_id',$bor_id);
    $re_to->bindParam(':redate',$redate);
    $re_to->bindParam(':qty',$qty);
    $re_to->bindParam(':uid',$uid);
    $re_to->bindParam(':remaining',$rmn1);
    $re_to->bindParam(':approval_id',$approval_id);
    $re_to->bindParam(':borrow_sum',$borrow_sum);
    if($ck_reto = $re_to->execute());{



      //$b_id = $_REQUEST['borrow_id'];
      //$ck_ref = $db->prepare("select borrow_id,quantity,remaining from refund where borrow_id = :borrow_id");
      $ck_ref = $db->prepare("select ref_id,borrow_id,quantity,remaining from refund where borrow_id = :borrow_id ORDER by ref_id desc LIMIT 1");
      $ck_ref ->bindParam(':borrow_id',$bor_id);
      $ck_ref->execute();
      $ck_refrow = $ck_ref->fetch(PDO::FETCH_ASSOC);
      $ck_bid = $ck_refrow['borrow_id']; 
      $ck_bqty = $ck_refrow['quantity']; 
      $ck_brmn = $ck_refrow['remaining']; 
  
      //$sumbor = $ck_bqty - $ck_brmn ;
  
     
  
      if($ck_brmn == 0.00){
  
        
      $e_fe = $db->prepare("UPDATE `proj_head` SET `ref_st` = 'Y' WHERE `borrow_id` = :borrow_id"); 
      $e_fe->bindParam(':borrow_id',$ck_bid);
      $e_fe->execute();
      
      $e_feMsg = "สมบูรณ์!!!";
      
  
       }
    }

    $re_toMsg = "บันทึกสำเร็จ";

  } else {

 $rmn = $borrow_sum - $qty ;

    $e_refund = $db->prepare("INSERT INTO refund(approval_id,borrow_id,redate,admin_id,quantity,remaining,borrow_sum)
                               Value (:approval_id,:borrow_id,:redate,:uid,:qty,:rmn,:borrow_sum) ");
    $e_refund->bindParam(':borrow_id',$borrow_id);
    $e_refund->bindParam(':redate',$redate);
    $e_refund->bindParam(':qty',$qty);
    $e_refund->bindParam(':uid',$uid);
    $e_refund->bindParam(':rmn',$rmn);
    $e_refund->bindParam(':approval_id',$approval_id);
    $e_refund->bindParam(':borrow_sum',$borrow_sum);
    if($slt = $e_refund->execute());{

      $borrow_id = $_REQUEST['borrow_id'];
      $serfe = $db->prepare("select borrow_id from refund where borrow_id = :borrow_id");
      $serfe ->bindParam(':borrow_id',$borrow_id);
      $serfe->execute();
      $serrow = $serfe->fetch(PDO::FETCH_ASSOC);
      $borfe = $serrow['borrow_id']; 

    $e_e = $db->prepare("UPDATE `proj_head` SET `ref_st` = 'W' WHERE `borrow_id` = :borrow_id"); 
    $e_e->bindParam(':borrow_id',$borfe);
    $e_e->execute();


    


      $e_Msg = "บันทึกเรียบร้อย";
    }

  }
   

   



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

function DateThai($strDate)
{
    $strYear = date("Y",strtotime($strDate))+543;
    $strMonth= date("n",strtotime($strDate));
    $strDay= date("j",strtotime($strDate));
    // $strHour= date("H",strtotime($strDate));
    // $strMinute= date("i",strtotime($strDate));
    // $strSeconds= date("s",strtotime($strDate));
    $strMonthCut = Array("" ,"มกราคม" , "กุมภาพันธ์", "มีนาคม" , "เมษายน" ,"พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $strMonthThai=$strMonthCut[$strMonth];
    return "$strDay&nbsp;$strMonthThai&nbsp;พ.ศ.&nbsp;$strYear";
}
function thainumDigit($num){

    return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),
    array( "๐" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),
    $num);
    };


    if (isset($_REQUEST['addre'])){
        $uid = $_REQUEST['user_id'];
        $lineuser = $_REQUEST['lineuser'];
      
        $adline = $db->prepare("UPDATE `user` SET `line_user_id` = :lineuser  WHERE `user_id` = :uid;");
        $adline->bindParam(':uid',$uid);
        $adline->bindParam(':lineuser',$lineuser);
        
        if ($line = $adline->execute()){
      
          $lineMsg = "บันทึกเรียบร้อย";
        }
      
      }
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
  
    <h3><label class="form-text">คืนเงินโครงการ</label></h3>
    </div>

    <br>
    <?php
    if (isset($e_feMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $e_feMsg; ?>",
            type: "success"
          }, function() {
            window.location = "refund.php";
          });
        }, 1000);
      </script>

    <?php
    } ?>


    <?php
    if (isset($e_Msg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $e_Msg; ?>",
            type: "success"
          }, function() {
            window.location = "refund.php";
          });
        }, 1000);
      </script>

    <?php
    } ?>


    <?php
    if (isset($re_toMsg)) {
    ?>
      <script>
        setTimeout(function() {
          swal({
            title: "<?php echo $re_toMsg; ?>",
            type: "success"
          }, function() {
            window.location = "refund.php";
          });
        }, 1000);
      </script>

    <?php
    } ?>

    <div class="input-group">


    </div>
 
    <!-- <div class="row mt-2">
                  
                  <div class="col">
                      <label class="form-label" for="approval_sum">วันที่จ่าย</label>
                      <span>(บาท)</span>
                      <input type="text" class="form-control" value="<?php echo $row['redate']; ?>" name="redate1" readonly placeholder>
                  </div>
                  <div class="col">
                      <label for="firstname" class="form-label" for="approval_fdate">ยอดที่จ่าย</label>
                     
                      <input class="form-control" id="qty1" value="<?php echo $row['quantity']; ?>" name="qty1"  placeholder >
              
                  </div>
                  <div class="col">
                      <label class="form-label" for="approval_edate">คงเหลือ</label>
                      
                      <input class="form-control" id="remaining" value="<?php echo $row['remaining']; ?>" name="remaining" readonly placeholder>
                  </div>
                  
                  
</div> -->


<div class="card">
              <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
              <!-- /.card-header -->
              <div class="card-body">
    <table id="Table" class="table table-striped " name="from1">
      <thead class="table-info">
        <tr >
          <th scope="col" width="5%">#</th>
          <th scope="col" width="10%">สถานะ</th>
          <th scope="col" width="55%">ชื่อโครงการ</th>
          <th scope="col" width="20%">ชื่อผู้ขอ</th>
          <!-- <th scope="col">เลขที่ขออนุมัติ</th>
          <th scope="col">เลขที่หนังสือจังหวัด</th>
          <th scope="col">พิมพ์</th>
          <th scope="col">action</th> -->
          <th scope="col" width="10%">แก้ไข</th>

        </tr>
      </thead>
      <tbody id="myTable">
        <?php

     

        $uid1 = $_SESSION['user_id'];

        $select_stmt = $db->prepare("SELECT ap.*, pr.borrow_st ,u.*,re.remaining,re.redate,re.quantity,br.borrow_id,pr.ref_st from  approval ap
        left join borrow br on  br.approval_id = ap.approval_id
        left join proj_head pr on pr.approval_id = ap.approval_id 
        left join refund re on re.approval_id = ap.approval_id
        left join user u on u.user_id = ap.user_id where  pr.borrow_st in ('Y') GROUP BY ap.approval_id order by ap.approval_id DESC");

        $select_stmt->execute();

        while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

          global $x;
          $x++;
        ?>
          <tr >
          <?php
          // $ST = $row["approval_st"];

          if ($row["ref_st"] == 'W') {
            $str_sy = 'W';
          } else if ($row["ref_st"] == 'Y') {
            $str_sy = 'Y';

          }else if ($row["ref_st"] == null) {
            $str_sy = 'N';

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
              
             ?><i class="bi bi-exclamation-diamond-fill bi-3x icon-red"></i><?php } else if ($str_sy == 'W') {?> <i class="bi bi-exclamation-diamond-fill bi-3x icon-yellow"></i><?php } else 
             if ($str_sy == 'Y') {?>
             <i class="bi bi-file-check-fill icon-green bi-3x"></i><?php } ?>
            
            </td>
            <td><?php echo $row["approval_name"]; ?></td>
            <td><?php echo $row["f_name"];
                echo "&nbsp;" . $row["l_name"]; ?></td>
            <td> 
             <a data-bs-toggle='modal' data-bs-target='#refund_<?php echo $row["borrow_id"]; ?>' class="btn btn-warning btn-sm">คืนเงิน
             <a data-bs-toggle='modal' data-bs-target='#Maxrefund_<?php echo $row["borrow_id"]; ?>' class="btn btn-info btn-sm">ตรวจสอบ 
                </td>



             
                <?php 
               include 'modal.php';
               ?>
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