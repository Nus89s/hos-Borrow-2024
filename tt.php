<?php 

include('conn.php');

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

$select_stmt = $db->prepare("SELECT ap.*,u.*,g.g_name,j.job_name,b.*, DATEDIFF(b.borrow_edate,curdate()) As DiffDate 
FROM borrow b 
left join user u on u.user_id = b.user_id 
left join approval ap on ap.approval_id = b.approval_id 
left join group_job g on g.g_id = u.g_id 
left join job j on j.job_id = u.job_id
 HAVING DiffDate <=30");
$select_stmt->execute();
while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
//  echo $row["DiffDate"]; 
//  echo $row["id"]; 
//sendLineNotify( "โครงการ: " . $row["name_p"] .'  '. "วันที่เริ่ม: " . $row["f_date"] .'  '. "วันที่สิ้นสุด: " . $row["e_date"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");
//sendLineNotify( "โครงการ: " . $row["f_name"] .'  '. "วันที่เริ่ม: " . $row["approval_fdate"] .'  '. "วันที่สิ้นสุด: " . $row["approval_edate"] .' '. "อีก ".$row["DiffDate"]." วันจะครบกำหนด");

$Di = $row["DiffDate"];

if($Di < 0){

  $tt = "เลยกำหนดชำระ " .$row["DiffDate"]. " วัน";
}
// if ($Di (strlen($Di) > 2)){
// $tk =  mb_substr($Di, 1, 3);
// $tt = "เลยกำหนดชำระ " .$tk;



// "เลยกำหนดชำระ " .RIGHT('$row["DiffDate"]', 4);
 else {
  $tt = "อีก " .$row['DiffDate']. " วันจะครบกำหนด";
}

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
                  "text": "โรงพยาบาลเทพา",
                  "weight": "bold",
                  "color": "#1DB446",
                  "size": "md"
                },
                {
                  "type": "text",
                  "text": "207 หมู่ 5 ถ.ลำไพล-เทพา",
                  "size": "xs",
                  "color": "#aaaaaa",
                  "wrap": true
                },
                {
                  "type": "text",
                  "text": " ต.เทพา อ.เทพา จ.สงขลา 90150",
                  "wrap": true,
                  "color": "#aaaaaa",
                  "size": "xs"
                },
                {
                  "type": "text",
                  "text": "ใบแจ้งหนี้",
                  "weight": "bold",
                  "size": "xxl",
                  "margin": "md",
                  "align": "center"
                },
                {
                  "type": "separator",
                  "margin": "xxl"
                },
                {
                  "type": "box",
                  "layout": "vertical",
                  "margin": "xxl",
                  "spacing": "sm",
                  "contents": [
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ชื่อ-สกุล"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["f_name"].' '.$row["l_name"].'",
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ตำแหน่ง"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["job_name"].'",
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "สังกัด"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["g_name"].'",
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "separator",
                      "margin": "lg"
                    },
                    {
                      "type": "text",
                      "text": "โครงการ",
                      "align": "center",
                      "margin": "lg",
                      "size": "lg"
                    },
                    {
                      "type": "text",
                      "text": "'.$row["approval_name"].'"
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ค่าลงทะเบียน",
                          "size": "sm",
                          "color": "#555555",
                          "flex": 0
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_regis"].'",
                          "size": "sm",
                          "color": "#111111",
                          "align": "end"
                        }
                      ],
                      "margin": "lg"
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ค่าเบี้ยเลี้ยง",
                          "size": "sm",
                          "color": "#555555",
                          "flex": 0
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_allw"].'",
                          "size": "sm",
                          "color": "#111111",
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ค่าเช่าที่พัก",
                          "size": "sm",
                          "color": "#555555",
                          "flex": 0
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_accom"].'",
                          "size": "sm",
                          "color": "#111111",
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ค่าพาหนะ",
                          "flex": 0,
                          "size": "sm",
                          "color": "#555555"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_veh"].'",
                          "align": "end",
                          "size": "sm",
                          "color": "#111111"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ค่าสมนาคุณ",
                          "flex": 0,
                          "size": "sm",
                          "color": "#555555"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_reward"].'",
                          "size": "sm",
                          "color": "#111111",
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "อื่นๆ",
                          "flex": 0,
                          "size": "sm",
                          "color": "#555555"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_another"].'",
                          "size": "sm",
                          "color": "#111111",
                          "align": "end"
                        }
                      ]
                    },
                    {
                      "type": "separator",
                      "margin": "xxl"
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "margin": "xxl",
                      "contents": [
                        {
                          "type": "text",
                          "text": "ยอดรวมทั้งหมด",
                          "size": "sm",
                          "color": "#111111",
                          "decoration": "underline",
                          "weight": "bold"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_sum"].'",
                          "size": "sm",
                          "color": "#111111",
                          "align": "end",
                          "weight": "bold",
                          "decoration": "underline"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "horizontal",
                      "contents": [
                        {
                          "type": "text",
                          "text": "วันครบกำหนด",
                          "size": "sm",
                          "color": "#111111",
                          "weight": "bold"
                        },
                        {
                          "type": "text",
                          "text": "'.$row["borrow_edate"].'",
                          "size": "sm",
                          "color": "#111111",
                          "align": "end",
                          "weight": "bold"
                        }
                      ]
                    },
                    {
                      "type": "box",
                      "layout": "vertical",
                      "contents": [
                        {
                          "type": "text",
                          "text": "'.$tt.'",
                          "size": "lg",
                          "color": "#FA0000",
                          "align": "center",
                          "margin": "md"
                        }
                      ]
                    }
                  ]
                },
                {
                  "type": "separator",
                  "margin": "xxl"
                },
                {
                  "type": "box",
                  "layout": "horizontal",
                  "margin": "md",
                  "contents": [
                    {
                      "type": "text",
                      "text": "เพิ่มเติมติดต่อห้องบริหาร",
                      "size": "xs",
                      "color": "#aaaaaa",
                      "flex": 0
                    },
                    {
                      "type": "text",
                      "text": "#1001",
                      "color": "#aaaaaa",
                      "size": "xs",
                      "align": "end"
                    }
                  ]
                }
            ],
              "width": "300px"
            },
            "styles": {
              "footer": {
                "separator": true
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
?>