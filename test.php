<?php
 include('conn.php');



 function sendLineNotify($message){
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
              echo "status : " . $res['status'];
              echo "message : " . $res['message'];
          }
         curl_close($ch);
    }




             $select_stmt = $db->prepare("SELECT ap.*,u.*,b.*, DATEDIFF(b.borrow_edate,curdate()) As DiffDate FROM borrow b
             left join user u on u.user_id = b.user_id
             left join approval ap on ap.approval_id = b.approval_id
             HAVING DiffDate <=30 ");
             $select_stmt->execute();
             while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
            //  echo $row["DiffDate"]; 
            //  echo $row["id"]; 
             //sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");
             sendLineNotify( "โครงการ: " . $row["f_name"] .'  '. "วันที่เริ่ม: " . $row["approval_fdate"] .'  '. "วันที่สิ้นสุด: " . $row["approval_edate"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");
   
              } 
              
              
              
              
              
              
              
              


              
              ?>
 



 
<?php
//  function status_date_notify($date){
//     $time = time();
//     $input = curdate() ;
//     $format = "d/m/Y H:i:s";
//     $timezone = new DateTimeZone("UTC");
//     $date = DateTime::createFromFormat($format, $input, $timezone);
//     $select_Dif = $db->prepare("SELECT *, DATEDIFF(e_date,curdate()) As DiffDate FROM proj HAVING DiffDate <=30 ");
//     $select_Dif->execute();
//     echo $row["DiffDate"]; 
//     while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

//         echo $row["DiffDate"]; 

//     }
// }
// echo "DiffDate"; 
