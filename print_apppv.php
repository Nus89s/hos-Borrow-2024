<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php
//use Aosy\ThSplitLib\Segment;

use Aosy\ThSplitLib\Segment;

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
 (isset($_REQUEST['appr_id'])) ;
    $pid = $_REQUEST['appr_id'];
    $selectpv_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* ,g.* ,j.* ,pv.* from approval_prov pv
    left join approval ap on ap.approval_id = pv.approval_id
    left join proj_head pr on pr.approval_id = ap.approval_id 
    left join user u on u.user_id = pv.user_id
    left join group_job g on g.g_id = u.g_id 
    left join job j on j.job_id = u.job_id
    where pv.appr_id = :apprid");

    

    $selectpv_stmt->bindParam(':apprid', $pid);
    $selectpv_stmt->execute();
    $row = $selectpv_stmt->fetch();

    // $capp = $db->prepare("select count(ap.approval_id) c from approval ap 
    // left join user u on u.user_id = ap.user_id where ap.approval_fdate BETWEEN '2023-11-01' and '2023-11-30' and ap.approval_self != 'N' and u.user_id = ':uid'");
    // $capp->bindParam(':pid', $pid);
    // $capp->bindParam(':uid',$uid);
    // $capp->execute();
    // $row2 = $capp->fetch();


require_once __DIR__ . '/mpdf/vendor/autoload.php';
require_once('mpdf/src/ThSplitLib/Segment.php');
$segment = new Segment();

// custom font;

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];



// $words = $segment->get_segment_array($text);
// $text = implode("|",$words);



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
$mpdf->useDictionaryLBR = false;

$mpdf->AddPageByArray([
    // 'margin-left'=> '27',
    // 'magin-right'=> '15',
    // 'magin-top'=> '0',
    // 'magin-bottom'=> '0',
    'margin-top'=> '5',
    'margin-bottom'=> '0',
    'margin-left' =>'23',
    'margin-right' => '15',
]);


//$mpdf->SetAutoFont(AUTOFONT_ALL);

//$mpdf->SetAutoFont();



    function DateThai($strDate){
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		// $strHour= date("H",strtotime($strDate));
		// $strMinute= date("i",strtotime($strDate));
		// $strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("" ,"มกราคม" , "กุมภาพันธ์", "มีนาคม" , "เมษายน" ,"พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return " $strDay $strMonthThai $strYear";
	}
    function thainumDigit($num){

        return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),
        array( "๐" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),
        $num);
        };

	$strDate = $row["appr_cdate"];
    $strDate_in = $row["approval_in_date"];
    $strDate1 = $row["approval_fdate"];
    $strDate2 = $row["approval_edate"];
    
	echo "ThaiCreate.Com Time now : ".thainumDigit(DateThai($strDate));

    if ($row["approval_self"] !='N'){
        $str_s = '/';
        if ($row['approval_self'] == 'V'){
            $str_sy = '/';
        }
        else if ($row['approval_self'] == 'P'){
            $str_sn = '/';
        }
    }
    if ($row['approval_hsent'] != 'N'){
        $str_h = '/';
    }
    if ($row['approval_invite'] != 'N'){
        $str_i = '/';
    }
    
 
    
$text = '<b style=" font-size: 20pt;"><b>ส่วนราชการ</b>&nbsp;&nbsp; โรงพยาบาลเทพา &nbsp;&nbsp;กลุ่มงานบริหารทั่วไป โทร. ๐ ๗๔๓๗ ๖๓๕๙ ต่อ ๑๐๐๙
<br><b>ที่</b> ' . $row['appr_number'] . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>วันที่</b>'.thainumDigit(DateThai($strDate)). '
<br><b>เรื่อง</b> ขออนุมัติเดินทางไปราชการ
<br>เรียน นายแพทย์สาธารณสุขจังหวัดสงขลา
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>๑.เรื่องเดิม</b> 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามหนังสือสำนักสาธารณสุขจังหวัดสงขลา ที่ '.$row["approval_number"].' ลงวันที่'.thainumDigit(DateThai($row["approval_in_date"])).'
เรื่อง ขอส่งสำเนาหนังสือ เรื่อง '.$row["approval_in_name"].'เรื่อง '.$row["approval_name"].' ระหว่างวันที่'.thainumDigit(DateThai($row["approval_fdate"])).'ถึงวันที่'.thainumDigit(DateThai($row["approval_edate"])).' ณ '.$row["approval_addp"].' '.$row["approval_organ"].' โดยเชิญผู้เกี่ยวข้องเข้าร่วมโครงการดังกล่าว นั้น
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>๒.ข้อเท็จจริง</b> 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;โรงพยาบาลเทพาได้พิจารณาแล้วขออนุมัติให้ '.$row["f_name"].' '.$row["l_name"].' ตำแหน่ง'.$row["job_name"].' เข้าร่วมบรมตามโครงการดังกล่าว
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>๓.ข้อกฎหมาย</b> 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๓.๑ ระเบียบสำนักนายกรัฐมนตรีว่าด้วยการอนุมัติเดินทางไปราชการและ การจัดการประชุมของทางราชการ พ.ศ.๒๕๒๔ หมวด ๑ การขออนุมัติเดินทางไปราชการ ส่วนที่ ๑ การเดินทางไปราชการในอาณาจักร
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ ๑๑ (๕) ผู้ว่าราชการจังหวัดสำหรับการเดินทางของข้าราชการและลูกจ้างในราชการบริหารส่วนภูมิภาคในจังหวัดนั้นทุกตำแหน่ง
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;๓.๒ ตามคำสั่งจังหวัดสงขลาที่ ๕๙๒๗/๒๕๖๕ ลงวันที่ ๑๓ ธันวาคม ๒๕๖๕ เรื่องยกเลิกคำสั่งและมอบอำนาจให้รองผู้ว่าราชการจังหวัดปลัดจังหวัดหัวหน้าส่วนราชการประจำจังหวัดหัวหน้าส่วนราชการสังกัดบริหารราชการส่วนกลางและนายอำเภอปฏิบัติราชการแทนผู้ว่าราชการจังหวัดสงขลา ผนวก ง 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ ๑.๑๔ หัวหน้าส่วนราชการประจำจังหวัดเป็นผู้มีอำนาจอนุมัติการเดินทางไปราชการ ในราชอาณาจักรของข้าราชการลูกจ้างและพนักงานราชการในสังกัดทุกตำแหน่งรวมถึงพิจารณาให้ใช้รถยนต์ส่วนตัวในการไปราชการดังกล่าวตามความจำเป็น
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>๔.ข้อพิจารณา</b> 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในการนี้ขออนุมัติให้ '.$row["f_name"].' '.$row["l_name"].' ตำแหน่ง'.$row["job_name"].' เดินทางไปราชการโดย'.$row["approval_veh"].'ระหว่างวันที่'.thainumDigit(DateThai($row["approval_fdate"])).' ถึงวันที่'.thainumDigit(DateThai($row["approval_edate"])).' และขอเบิกค่าใช้จ่ายต่างๆในการเดินทางไปราชการจากต้นสังกัด
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>๕.ข้อเสนอ</b> 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ 


</p>';
$words = $segment->get_segment_array($text);
$text = implode("|",$words);

$content = '
<style>
.container{
    font-family: "sarabun";
    font-size: 10pt;
    word-wrap: break-word; 
    
}

p{
    text-align: justify;
    font-size: 15.2pt;
    font-family: "sarabun";
    word-wrap: break-word; 
   
    
}

h1{
    font-family: "sarabun";
    font-size: 26pt;
    text-align: center;
    top: 0;
    word-wrap: break-word; 
}
.text{
    font-size: 30pt;

}
img {
    float: none;
  }

  body {
    background-image: src="KR.png";
    background-repeat: no-repeat;
    word-wrap: break-word; 
  }

</style><p>
<img src="KR.png" width="55" height="60">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style = "font-size: 26pt; font-family: sarabun;">บันทึกข้อความ</b>
<br>
'.$text.'
<br>
<p style="text-align:right;"> (นายเดชา แซ่หลี)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>ผู้อำนวยการโรงพยาบาลเทพา&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


</div> ';



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

</body>'; 

$footer = 'FR-GEN-55 &nbsp;&nbsp;&nbsp; R02 &nbsp; 09/03/44' .'.{PAGENO}';
// $words = $segment->get_segment_array($text);
// $text = implode("|",$words);

// $mpdf->setFooter($footer);

// $mpdf->SetFooter('<div style="text-align: left  font-size: 15pt ">'.$footer.'</div>
//                         <div style="text-align: right  font-size: 15pt ">||PAGE{PAGENO}/{nbpg}</div>');

$mpdf->WriteHTML($content);
// $mpdf->WriteHTML($sen);

// $mpdf->AddPage();

$mpdf->Output();
?>

