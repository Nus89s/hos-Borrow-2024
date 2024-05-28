<?php


require_once('conn.php');
$select_stmt = $db->prepare("SELECT ap.*,br.*,u.*, DATEDIFF(borrow_edate,curdate()) As DiffDate FROM borrow br 
left join approval ap on ap.approval_id = br.approval_id 
left join user u on u.user_id = ap.user_id HAVING DiffDate <=30");
$select_stmt->execute();
while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//sendLineNotify( "โครงการ: " . $row["approval_name"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

$access_token = 'Hcd92Q1925NQrQhGyouTBfZRVIovp4PPfQb9th5gX6LkHQIJPVSX01no84JXZuh1y+aaa+N1xYoXlxl9ia4eG8dzvXmdkkjsGlm3GpuIdVfCNt0sw/JfxmCTLf8XQBG02Rfj+sIxK4AONhYqcmoduAdB04t89/1O/w1cDnyilFU=';
$userId = $row["line_user_id"];
$messages = array(

     'type' => 'text',
     'text' => 'โครงการ: ' .$row["approval_name"].' '.$row["f_name"].' '.$row["l_name"].$row["DiffDate"]." วันจะครบกำหนด",
     //'text' => $row["f_name"].' '.$row["l_name"],
    
);

$post = json_encode(array(
    'to' => array($userId),
    'messages' => array($messages),
));
$url = 'https://api.line.me/v2/bot/message/multicast';
$headers = array('Content-Type: application/json', 'Authorization: Bearer '.$access_token);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
echo $result;
 }

 
