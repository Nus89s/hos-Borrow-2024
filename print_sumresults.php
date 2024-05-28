
<?php
session_start();
if (!isset($_SESSION['user_id'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.php"); // redirect ไปยังหน้า login.php
    exit;
}


require_once "conn.php";

$uid = $_SESSION['user_id'];


// if (isset($_GET['approval_id'])) {
//         $id = $_GET['approval_id'];
//         $stmt = $db->query("SELECT * FROM approval WHERE approval_id = $id");
//         $stmt->execute();
//         $data = $stmt->fetch();
// }
 (isset($_REQUEST['pjsid'])) ;
    $pid = $_REQUEST['pjsid'];
    $insert_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* ,g.* ,j.*,pv.* ,ps.*  from approval ap 
    left join proj_head pr on pr.approval_id = ap.approval_id 
    left join user u on u.user_id = ap.user_id
    left join group_job g on g.g_id = u.g_id 
    left join job j on j.job_id = u.job_id
    left join approval_prov pv on pv.approval_id = ap.approval_id
    left join proj_summary ps on ps.approval_id = ap.approval_id
    where ap.approval_id = :pid");

    $insert_stmt->bindParam(':pid', $pid);
    $insert_stmt->execute();
    $row = $insert_stmt->fetch();

    $capp = $db->prepare("select count(ap.approval_id) c from approval ap 
    left join user u on u.user_id = ap.user_id where ap.approval_fdate BETWEEN '2023-11-01' and '2023-11-30' and ap.approval_self != 'N' and u.user_id = ':uid'");
    $capp->bindParam(':pid', $pid);
    $capp->bindParam(':uid',$uid);
    $capp->execute();
    $row2 = $capp->fetch();
      

require_once __DIR__ . '/mpdf/vendor/autoload.php';

//custom font
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];



$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/font',
    ]),
    'fontdata' => $fontData + [
        'sarabun' => [
            'R' => 'THSarabunNew.ttf',
            'I' => 'THSarabunNew Italic.ttf',
            'B' =>  'THSarabunNew Bold.ttf',
        ]
    ],
    'format' => [210, 297],
]);

$mpdf->AddPageByArray([
    'margin-left'=> '30',
    'magin-right'=> '5',
    'magin-top'=> '5',
    'magin-bottom'=> '5',
]);

// $text = '';
// function datethai($dateth){
//     $year = date_format(date_create($dateth),"Y") + 543;
//     $month = date_format(date_create($dateth),"m") ;
//     $day = date_format(date_create($dateth),"d") ; 
//     $strmonth = array("" ,"มกราคม" , "กุมภาพันธ์", "มีนาคม" , "เมษายน" ,"พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    
//     return (int) $day . '' .$strmonth[intval($month)] . '' . $year ;
    
//     }

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
		return "$strDay เดือน $strMonthThai พ.ศ. $strYear";
	}
    function thainumDigit($num){

        return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),
        array( "๐" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),
        $num);
        };

	$strDate = $row["approval_date"];
    $strDate1 = $row["approval_fdate"];
    $strDate2 = $row["approval_edate"];
    
	echo "ThaiCreate.Com Time now : ".thainumDigit(DateThai($strDate));

    if ($row['pjs_abt'] =='high'){
        $str_high = '/';

    } else 
    if ($row['pjs_abt'] == 'mid'){
        $str_mid = '/';

    }
    else if ($row['pjs_abt'] == 'low'){

        $str_low = '/';
    }
   

    

    


$content = '
<style>
.container{
    font-family: "sarabun";
    font-size: 10pt;
}

p{
    text-align: justify;
    font-size: 16pt;
    
}
h1{
    text-align: center;
}

</style>
<div class="container" style="width: 100%">



<p>
<b >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บันทึกสรุปอบรม
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;โรงพยาบาลเทพา</b>
<br>
' . $text . '' . $row["g_name"] . '
<br> วันที่ '.thainumDigit(DateThai($strDate)). '
<br> เรื่อง&nbsp;ส่งรายงานการฝึกอบรม
<br> เรียน ผู้อำนวยการโรงพยาบาลเทพา
<br> อ้างถึง หนังสือที่ '.$row["appr_number"].'
<br> สิ่งที่ส่งมาด้วย &nbsp;   ๑.รายงานสรุปเนื้อหาการฝึกอบรม ๑ ฉบับ
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ๒.เอกสารประกอบการฝึกอบรมจำนวน………………….ชุด
<br> ตามที่หนังสือ '.$row["appr_number"].' ได้อนุมัติให้ข้าพเจ้า'.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;'.'ตำแหน่ง'.'&nbsp;'.$row["job_name"].'
<br> ได้เข้าร่วมการฝึกอบรมเรื่อง '.$row["approval_name"].' ณ '.$row["approval_addp"].'
<br> ระหว่างวันที่ '.thainumDigit(DateThai($strDate1)). ' ถึงวันที่ '.thainumDigit(DateThai($strDate2)). '  จัดโดย  '.$row["approval_organ"].' 
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;บัดนี้การฝึกอบรมได้เสร็จสิ้นลงแล้ว ข้าพเจ้าจึงขอสรุปการฝึกอบรมดังนี้
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๑. สรุปเนื้อหาการฝึกอบรม 
<br> &nbsp;&nbsp;&nbsp;&nbsp;'.$row['pjs_detail'].'
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๒. ประโยชน์ที่ได้รับจากการฝึกอบรม  
<br> &nbsp;&nbsp;&nbsp;&nbsp;'.$row['pjs_benf'].'
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๓. กิจกรรมที่คาดว่าจะดำเนินการได้ภายหลังการฝึกอบรม (ระบุระยะเวลาไม่เกิน 6 เดือน )  
<br> &nbsp;&nbsp;&nbsp;&nbsp;'.$row['pjs_ex'].'
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๔. วิทยากรในการฝึกอบรมมีความรู้ ความสามารถ  ( '.$str_high.' ) มาก  ( '.$str_low.' ) น้อย  ( '.$str_mid.' ) ปานกลาง  
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๕. ค่าใช้จ่ายในการฝึกอบรม  
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ค่าลงทะเบียน '.$row['pjs_regis'].' บาท 
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ค่าเบี้ยเลี้ยงเดินทาง '.$row['pjs_allw'].' บาท
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ค่าที่พัก '.$row['pjs_accom'].' บาท 
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ค่ายานพาหนะ '.$row['pjs_veh'].' บาท 
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ค่าใช้จ่ายอื่น ๆ '.$row['pjs_other'].' บาท 
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- รวม '.$row['pjs_sumprice'].' บาท
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๖. ค่าใช้จ่ายในการฝึกอบรม  
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๗. ความคิดเห็น/เสนอแนะ ต่อการอบรมครั้งนี้  
<br> &nbsp;&nbsp;&nbsp;&nbsp;'.$row['pjs_comment'].'
<br>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณา
<br><p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;'.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง&nbsp;'.$row["job_name"].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p>ความคิดเห็นผู้บังคับบัญชา ระดับที่ ๑
<br>ข้อคิดเห็น………………………………………………………………………………………………………
<p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;..........................................&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>ตำแหน่ง&nbsp;.............................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p>ความคิดเห็นผู้บังคับบัญชา ระดับที่ ๒
<br>ข้อคิดเห็น………………………………………………………………………………………………………
<p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;'.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>ตำแหน่ง&nbsp;.............................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p>ความเห็นของผู้อำนวยการโรงพยาบาล
<br>ข้อคิดเห็น………………………………………………………………………………………………………
<p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

</div>';

$sen = '<style>
   .br {
    font-family: "sarabun";
        text-align:"right";
    }
</style>   
<br> จึงเรียนมาเพื่อโปรดพิจารณา
<br>ผู้ขอเข้ารับการฝึกอบรม ………………………………………
<br>(…………………………………….…….)
<br>ตำแหน่ง …………………………………………..

';

$footer = 'FR-GEN-57 &nbsp;&nbsp;&nbsp; R02 &nbsp; 09/03/44' .'.{PAGENO}';

// $mpdf->setFooter($footer);
$mpdf->SetFooter('<div style="text-align: left  font-size: 15pt ">'.$footer.'</div>
                        <div style="text-align: right  font-size: 15pt ">||PAGE{PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($content);
// $mpdf->WriteHTML($sen);

// $mpdf->AddPage();

$mpdf->Output();
?>


