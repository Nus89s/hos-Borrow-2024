<?php

require_once('conn.php');
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
 



sendLineNotify("โครงการ: " . $approval_name . '  ' . "วันที่ครบสัญญา: " .$strDate );

//if($date == $date ){
  //$date = date("Y-m-d H:i:s".strtotime("today 13:44"));

  
  $select_stmt = $db->prepare("SELECT ap.*,br.*, DATEDIFF(borrow_edate,curdate()) As DiffDate FROM borrow br 
  left join approval ap on ap.approval_id = br.approval_id HAVING DiffDate <=30");
  $select_stmt->execute();
  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
  sendLineNotify( "โครงการ: " . $row["approval_name"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

   } 
//}
?>