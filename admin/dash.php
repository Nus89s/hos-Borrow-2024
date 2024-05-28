
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

function DateThai($strDate)
{
  $strYear = date("Y",strtotime($strDate))+543;
  $strMonth= date("n",strtotime($strDate));
  //$strDay= date("j",strtotime($strDate));
  // $strHour= date("H",strtotime($strDate));
  // $strMinute= date("i",strtotime($strDate));
  // $strSeconds= date("s",strtotime($strDate));
  $strMonthCut = Array("" ,"มกราคม" , "กุมภาพันธ์", "มีนาคม" , "เมษายน" ,"พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
  $strMonthThai=$strMonthCut[$strMonth];
  return "$strMonthThai พ.ศ. $strYear";
}


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

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>



<?php







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

      <h3><label class="form-text"></label></h3>
    </div>
    <br>


    <div class="card">
      
   
      <div class="card-body">
      <?php  
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

                <?php 
                
               

                 if(isset($_POST['filter'])){
  
                  $D = $_POST['filter']; 
                  //echo  $D;
                 // $uid1 = $_SESSION['user_id'];
                
                  // $select_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* from approval ap left join proj_head pr on pr.approval_id = ap.approval_id left join user u on u.user_id = ap.user_id 
                  //                             where ap.fiscalYear in ('$D') order by ap.approval_id DESC");

                  $select = $db->prepare("SELECT * FROM(SELECT SUM(br.borrow_sum) as total, MONTH(approval_date) mon, approval_date FROM approval ap left join borrow br on br.approval_id = ap.approval_id
                                               where  ap.fiscalYear in ('$D') GROUP by mon ORDER by ap.approval_date ) 
                                              w WHERE total IS NOT NULL");
                
                  $select->execute();
                  
                  // $sct = $db->prepare("SELECT * FROM(SELECT SUM(br.borrow_sum) as total, MONTH(approval_date) mon, approval_date FROM approval ap left join borrow br on br.approval_id = ap.approval_id
                  //                              where  ap.fiscalYear in ('$D') GROUP by mon ORDER by ap.approval_date ) 
                  //                             w WHERE total IS NOT NULL");

                  $sct = $db->prepare("SELECT * FROM( SELECT SUM(r.quantity) as total, MONTH(redate) mon, redate FROM approval ap left join borrow br on br.approval_id = ap.approval_id left join refund r on r.approval_id = ap.approval_id where ap.fiscalYear in ('$D') GROUP by mon ORDER by ap.approval_date ) w WHERE total IS NOT NULL");
                
                  $sct->execute();


              
                
                 } else {


              $uid1 = $_SESSION['user_id'];

              $stmtMax = $db->prepare("SELECT MAX(fiscalYear) mx FROM approval");
              $stmtMax->execute();
              $sMax = $stmtMax->fetch(PDO::FETCH_ASSOC);
              $D = $sMax['mx'];

            

                   $select = $db->prepare("SELECT * FROM(SELECT SUM(br.borrow_sum) as total, MONTH(approval_date) mon, approval_date FROM approval ap 
                   left join borrow br on br.approval_id = ap.approval_id where  ap.fiscalYear in ('$D')
                   GROUP by mon ORDER by ap.approval_date ) w WHERE total IS NOT NULL");
                   //$select->bindParam(':uid1', $uid1);
                   $select->execute();


                   
                  //  $sct = $db->prepare("SELECT * FROM(SELECT SUM(br.borrow_sum) as total, MONTH(approval_date) mon, approval_date FROM approval ap 
                  //  left join borrow br on br.approval_id = ap.approval_id where  ap.fiscalYear in ('$D')
                  //  GROUP by mon ORDER by ap.approval_date ) w WHERE total IS NOT NULL");
                   $sct = $db->prepare("SELECT * FROM( SELECT SUM(r.quantity) as total, MONTH(redate) mon, redate FROM approval ap left join borrow br on br.approval_id = ap.approval_id left join refund r on r.approval_id = ap.approval_id where ap.fiscalYear in ('$D') GROUP by mon ORDER by ap.approval_date ) w WHERE total IS NOT NULL");
                   //$select->bindParam(':uid1', $uid1);
                   $sct->execute();
                  }
                  //$uid1 = $_SESSION['user_id'];
//$select = $db->prepare("SELECT SUM(br.borrow_sum) as total, MONTH(approval_date) mon, approval_date FROM approval ap left join borrow br on br.approval_id = ap.approval_id GROUP by mon ORDER by ap.approval_date");
// $row1 = $select->fetch(PDO::FETCH_ASSOC);
//                 $saleData = array();
//                 foreach ($row1 as $k)
//                  {
//                 $saleData[] = "['".$row1['mon']."'".", ".$row1['total']."]";
//                 }
                
//                 $saleData = implode(",", $saleData);
//                    echo $saleData;
                // }

              
           ?>
      <section class="col connectedSortable mt-3">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                   
                </h3>
                <div class="card-tools">
                  <!-- <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li>
                  </ul> -->
                </div>
              </div><!-- /.card-header -->
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                
                  <div id="chart_div" class="text-center" style="width: 1500px; height: 500px; "></div>
                
                </div>
                <?php
                //  while($row = $select->fetch(PDO::FETCH_ASSOC)){




                //   // echo $row['mon']; 
                //   //  echo $row['total']; 
                
                //    $s = array("['".DateThai($row['approval_date'])."'".", ".$row['total']."]".",");
                //    $s = implode(",", $s);
                  
                //   echo $s;
                //  }
             ?>
            </div>
          

          </section>

          <section class="col connectedSortable mt-3">
          <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Sales 
                </h3>
                <div class="card-tools">

                </div>
              </div><!-- /.card-header -->
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                
                  <div id="chart_div1" class="text-center" style="width: 1500px; height: 500px; "></div>
                
                </div>
                <?php
                //  while($row = $select->fetch(PDO::FETCH_ASSOC)){




                //   // echo $row['mon']; 
                //   //  echo $row['total']; 
                
                //    $s = array("['".DateThai($row['approval_date'])."'".", ".$row['total']."]".",");
                //    $s = implode(",", $s);
                  
                //   echo $s;
                //  }
             ?>
            </div>
            </section>

         
      </div>
    </div>
    <?php 
    

    
    ?>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],


          <?php echo $saleData;?>
        ]);

        var options = {
          title: 'My Daily Activities'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>






    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([

          ['mon', 'จำนวนเงินยืม'],
        //  ['2023-12-20', 53900],['2024-01-04', 37600],['2024-02-07', 0]

         <?php 
         while($row = $select->fetch(PDO::FETCH_ASSOC)){
          




          // echo $row['mon']; 
          //  echo $row['total']; 
        
           $s = array("['".DateThai($row['approval_date'])."'".", ".$row['total']."]".",");
           $s = implode(",", $s);
          
          echo $s;
        
         
        }
        // echo  $s;
         
         ?>


           // echo $row['mon']; echo $row['mon'];
          

          // ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
          // ['2004/05',  165,      938,         522,             998,           450,      614.6],
          // ['2005/06',  135,      1120,        599,             1268,          288,      682],
          // ['2006/07',  157,      1167,        587,             807,           397,      623],
          // ['2007/08',  139,      1110,        615,             968,           215,      609.4],
          // ['2008/09',  136,      691,         629,             1026,          366,      569.6]



        ]);

        var options = {
          title : 'แยกรายเดือน ปีงบประมาณ <?php  echo  $D;  ?>',
          //vAxis: {title: 'ยอดยืม(บาท)'},
          hAxis: {title: 'เดือน'},
          seriesType: 'bars',
          series: {1: {type: 'line'},
          0: { color: 'red' }  }
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>




<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var data = google.visualization.arrayToDataTable([

          ['mon', 'จำนวนเงินคืน'],
        //  ['2023-12-20', 53900],['2024-01-04', 37600],['2024-02-07', 0]

         <?php 
         while($row1 = $sct->fetch(PDO::FETCH_ASSOC)){




          // echo $row['mon']; 
          //  echo $row['total']; 
        
           $s = array("['".DateThai($row1['redate'])."'".", ".$row1['total']."]".",");
           $s = implode(",", $s);
          
          echo $s;
        
         
        }
        // echo  $s;
         
         ?>


           // echo $row['mon']; echo $row['mon'];
          

          // ['Month', 'Bolivia', 'Ecuador', 'Madagascar', 'Papua New Guinea', 'Rwanda', 'Average'],
          // ['2004/05',  165,      938,         522,             998,           450,      614.6],
          // ['2005/06',  135,      1120,        599,             1268,          288,      682],
          // ['2006/07',  157,      1167,        587,             807,           397,      623],
          // ['2007/08',  139,      1110,        615,             968,           215,      609.4],
          // ['2008/09',  136,      691,         629,             1026,          366,      569.6]



        ]);

        var options = {
          title : 'แยกรายเดือน ปีงบประมาณ <?php  echo  $D;  ?>',
          //vAxis: {title: 'ยอดยืม(บาท)'},
          hAxis: {title: 'เดือน'},
          seriesType: 'bars',
          series: {5: {type: 'line'},
          0: { color: 'green' } }
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div1'));
        chart.draw(data, options);
      }
    </script>


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
