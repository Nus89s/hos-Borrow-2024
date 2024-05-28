<?php 

include('../conn.php');




function sentMessage($encodeJson,$datas)
{
    $datasReturn = [];
      $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $datas['url'],
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $encodeJson,
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer ".$datas['token'],
        "cache-control: no-cache",
        "content-type: application/json; charset=UTF-8",
      ),
    ));

    $response = curl_exec($curl);
    // dd($response);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        $datasReturn['result'] = 'E';
        $datasReturn['message'] = $err;
    } else {
        if($response == "{}"){
            $datasReturn['result'] = 'S';
            $datasReturn['message'] = 'Success';
        }else{
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $response;
        }
    }

    return $datasReturn;
}


// อนุมัติโครงการ
if (isset($_REQUEST['appid'])) {

  $conapp = $_REQUEST['appid'];
  $conap = $db->prepare("SELECT ap.* ,u.* from  approval ap 
  left join user u on u.user_id = ap.user_id
  where ap.approval_id = :appid;");
  $conap->bindParam(':appid',$conapp);
  $conap->execute();
  while( $row = $conap->fetch(PDO::FETCH_ASSOC)){
  
$flexDataJson = '{
  "type": "flex",
  "altText": "Flex Message",
  "contents": {
    "type": "carousel",
    "contents": [
      {
          "type": "bubble",
          "body": {
            "type": "box",
            "layout": "vertical",
            "contents": [
              {
                "type": "text",
                "text": "โครงการได้รับอนุมัติแล้ว",
                "weight": "bold",
                "size": "xl"
              },
              {
                "type": "box",
                "layout": "vertical",
                "margin": "lg",
                "spacing": "sm",
                "contents": [
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "text": "เรื่อง",
                        "color": "#666666",
                        "size": "sm",
                        "flex": 1
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_name"].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  },
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "color": "#666666",
                        "size": "sm",
                        "flex": 1,
                        "text": "วันที่"
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_fdate"].''.$row['approval_edate'].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  }
                ]
              }
            ]
          }
        }
    ]
  }
}';
$flexDataJsonDeCode = json_decode($flexDataJson,true);
$datas['url'] = "https://api.line.me/v2/bot/message/push";
$datas['token'] = "Hcd92Q1925NQrQhGyouTBfZRVIovp4PPfQb9th5gX6LkHQIJPVSX01no84JXZuh1y+aaa+N1xYoXlxl9ia4eG8dzvXmdkkjsGlm3GpuIdVfCNt0sw/JfxmCTLf8XQBG02Rfj+sIxK4AONhYqcmoduAdB04t89/1O/w1cDnyilFU=";
$messages['to'] = $row['line_user_id'];
$messages['messages'][] = $flexDataJsonDeCode;
$encodeJson = json_encode($messages);


sentMessage($encodeJson,$datas);


}

}

//อนุมัติส่งหนังสือจังหวัด
if (isset($_REQUEST['appr_id'])) {

  $conapp = $_REQUEST['appr_id'];
  $conap = $db->prepare("SELECT pr.* , ap.* ,u.* from approval_prov pr 
  left join approval ap on ap.approval_id = pr.approval_id
  left join user u on u.user_id = pr.user_id
  where pr.appr_id = :appr_id;");
  $conap->bindParam(':appr_id',$conapp);
  $conap->execute();
  while( $row = $conap->fetch(PDO::FETCH_ASSOC)){
  
$flexDataJson = '{
  "type": "flex",
  "altText": "Flex Message",
  "contents": {
    "type": "carousel",
    "contents": [
      {
          "type": "bubble",
          "body": {
            "type": "box",
            "layout": "vertical",
            "contents": [
              {
                "type": "text",
                "text": "หนังสืออนุมัติราชการส่งจังหวัดแล้ว",
                "weight": "bold",
                "size": "xl"
              },
              {
                "type": "box",
                "layout": "vertical",
                "margin": "lg",
                "spacing": "sm",
                "contents": [
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "text": "เรื่อง",
                        "color": "#666666",
                        "size": "sm",
                        "flex": 1
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_name"].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  },
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "color": "#666666",
                        "size": "sm",
                        "flex": 1,
                        "text": "วันที่"
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_fdate"].''.$row['approval_edate'].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  }
                ]
              }
            ]
          }
        }
    ]
  }
}';
$flexDataJsonDeCode = json_decode($flexDataJson,true);
$datas['url'] = "https://api.line.me/v2/bot/message/push";
$datas['token'] = "Hcd92Q1925NQrQhGyouTBfZRVIovp4PPfQb9th5gX6LkHQIJPVSX01no84JXZuh1y+aaa+N1xYoXlxl9ia4eG8dzvXmdkkjsGlm3GpuIdVfCNt0sw/JfxmCTLf8XQBG02Rfj+sIxK4AONhYqcmoduAdB04t89/1O/w1cDnyilFU=";
$messages['to'] = $row['line_user_id'];
$messages['messages'][] = $flexDataJsonDeCode;
$encodeJson = json_encode($messages);


sentMessage($encodeJson,$datas);


}

}

//จังหวัดยืนยันแล้ว
if (isset($_REQUEST['scanpv_id'])) {

  $conapp = $_REQUEST['scanpv_id'];
  $conap = $db->prepare("SELECT pr.* , ap.* ,u.* from approval_prov pr 
  left join approval ap on ap.approval_id = pr.approval_id
  left join user u on u.user_id = pr.user_id
  where pr.appr_id = :appr_id;");
  $conap->bindParam(':appr_id',$conapp);
  $conap->execute();
  while( $row = $conap->fetch(PDO::FETCH_ASSOC)){
  
$flexDataJson = '{
  "type": "flex",
  "altText": "Flex Message",
  "contents": {
    "type": "carousel",
    "contents": [
      {
          "type": "bubble",
          "body": {
            "type": "box",
            "layout": "vertical",
            "contents": [
              {
                "type": "text",
                "text": "ยืนยันหนังสืออนุมัติราชการแล้ว",
                "weight": "bold",
                "size": "xl"
              },
              {
                "type": "box",
                "layout": "vertical",
                "margin": "lg",
                "spacing": "sm",
                "contents": [
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "text": "เรื่อง",
                        "color": "#666666",
                        "size": "sm",
                        "flex": 1
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_name"].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  },
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "color": "#666666",
                        "size": "sm",
                        "flex": 1,
                        "text": "วันที่"
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_fdate"].''.$row['approval_edate'].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  }
                ]
              }
            ]
          }
        }
    ]
  }
}';
$flexDataJsonDeCode = json_decode($flexDataJson,true);
$datas['url'] = "https://api.line.me/v2/bot/message/push";
$datas['token'] = "Hcd92Q1925NQrQhGyouTBfZRVIovp4PPfQb9th5gX6LkHQIJPVSX01no84JXZuh1y+aaa+N1xYoXlxl9ia4eG8dzvXmdkkjsGlm3GpuIdVfCNt0sw/JfxmCTLf8XQBG02Rfj+sIxK4AONhYqcmoduAdB04t89/1O/w1cDnyilFU=";
$messages['to'] = $row['line_user_id'];
$messages['messages'][] = $flexDataJsonDeCode;
$encodeJson = json_encode($messages);


sentMessage($encodeJson,$datas);


}

}


//อนุมัติสรุปอบรม
if (isset($_REQUEST['consum'])) {

  $conapp = $_REQUEST['consum'];
  $conap = $db->prepare("SELECT ps.* , ap.* ,u.* from proj_summary ps 
  left join approval ap on ap.approval_id = ps.approval_id
  left join user u on u.user_id = ps.user_id
  where ap.approval_id = :appid;");
  $conap->bindParam(':appid',$conapp);
  $conap->execute();
  while( $row = $conap->fetch(PDO::FETCH_ASSOC)){
  
$flexDataJson = '{
  "type": "flex",
  "altText": "Flex Message",
  "contents": {
    "type": "carousel",
    "contents": [
      {
          "type": "bubble",
          "body": {
            "type": "box",
            "layout": "vertical",
            "contents": [
              {
                "type": "text",
                "text": "ยืนยันสรุปโครงการแล้ว",
                "weight": "bold",
                "size": "xl"
              },
              {
                "type": "box",
                "layout": "vertical",
                "margin": "lg",
                "spacing": "sm",
                "contents": [
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "text": "เรื่อง",
                        "color": "#aaaaaa",
                        "size": "sm",
                        "flex": 1
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_name"].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  },
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "color": "#aaaaaa",
                        "size": "sm",
                        "flex": 1,
                        "text": "วันที่"
                      },
                      {
                        "type": "text",
                        "text": "approval_fdate approval_edate",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  }
                ]
              }
            ]
          },
          "styles": {
            "body": {
              "backgroundColor": "#D0ECE7"
            }
          }
        }
    ]
  }
}';
$flexDataJsonDeCode = json_decode($flexDataJson,true);
$datas['url'] = "https://api.line.me/v2/bot/message/push";
$datas['token'] = "Hcd92Q1925NQrQhGyouTBfZRVIovp4PPfQb9th5gX6LkHQIJPVSX01no84JXZuh1y+aaa+N1xYoXlxl9ia4eG8dzvXmdkkjsGlm3GpuIdVfCNt0sw/JfxmCTLf8XQBG02Rfj+sIxK4AONhYqcmoduAdB04t89/1O/w1cDnyilFU=";
$messages['to'] = $row['line_user_id'];
$messages['messages'][] = $flexDataJsonDeCode;
$encodeJson = json_encode($messages);


sentMessage($encodeJson,$datas);


}

}

//ยืนยันสัญญายืมเงิน
if (isset($_REQUEST['conbo'])) {

  $conapp = $_REQUEST['conbo'];
  $conap = $db->prepare("SELECT  ps.* ,u.* from approval ps 
  left join user u on u.user_id = ps.user_id
  where ps.approval_id = :appid;");
  $conap->bindParam(':appid',$conapp);
  $conap->execute();
  while( $row = $conap->fetch(PDO::FETCH_ASSOC)){
  
$flexDataJson = '{
  "type": "flex",
  "altText": "Flex Message",
  "contents": {
    "type": "carousel",
    "contents": [
      {
          "type": "bubble",
          "body": {
            "type": "box",
            "layout": "vertical",
            "contents": [
              {
                "type": "text",
                "text": "สัญญายืมเงินได้รับการยืนยันแล้ว",
                "weight": "bold",
                "size": "xl"
              },
              {
                "type": "box",
                "layout": "vertical",
                "margin": "lg",
                "spacing": "sm",
                "contents": [
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "text": "เรื่อง",
                        "color": "#aaaaaa",
                        "size": "sm",
                        "flex": 1
                      },
                      {
                        "type": "text",
                        "text": "'.$row["approval_name"].'",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  },
                  {
                    "type": "box",
                    "layout": "baseline",
                    "spacing": "sm",
                    "contents": [
                      {
                        "type": "text",
                        "color": "#aaaaaa",
                        "size": "sm",
                        "flex": 1,
                        "text": "วันที่"
                      },
                      {
                        "type": "text",
                        "text": "approval_fdate approval_edate",
                        "wrap": true,
                        "color": "#666666",
                        "size": "sm",
                        "flex": 5
                      }
                    ]
                  }
                ]
              }
            ]
          },
          "styles": {
            "body": {
              "backgroundColor": "#D0ECE7"
            }
          }
        }
    ]
  }
}';
$flexDataJsonDeCode = json_decode($flexDataJson,true);
$datas['url'] = "https://api.line.me/v2/bot/message/push";
$datas['token'] = "Hcd92Q1925NQrQhGyouTBfZRVIovp4PPfQb9th5gX6LkHQIJPVSX01no84JXZuh1y+aaa+N1xYoXlxl9ia4eG8dzvXmdkkjsGlm3GpuIdVfCNt0sw/JfxmCTLf8XQBG02Rfj+sIxK4AONhYqcmoduAdB04t89/1O/w1cDnyilFU=";
$messages['to'] = $row['line_user_id'];
$messages['messages'][] = $flexDataJsonDeCode;
$encodeJson = json_encode($messages);


sentMessage($encodeJson,$datas);


}

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
//sendLineNotify( "โครงการ: " . $row["f_name"] .'  '. "วันที่เริ่ม: " . $row["approval_fdate"] .'  '. "วันที่สิ้นสุด: " . $row["approval_edate"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

} 
?>