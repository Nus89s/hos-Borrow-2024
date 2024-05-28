
<?php

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
 (isset($_REQUEST['appid'])) ;
    $pid = $_REQUEST['appid'];
    $insert_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* ,g.* ,j.* from approval ap 
    left join proj_head pr on pr.approval_id = ap.approval_id 
    left join user u on u.user_id = ap.user_id
    left join group_job g on g.g_id = u.g_id 
    left join job j on j.job_id = u.job_id
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

    
        if ($row2['c'] !="") 
        
        {
            
        } else if($row2['c'] >'0'){
            $cv = '/';

        }

      

// $id = $_GET['approval_id'];
// $insert_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* ,g.* ,j.* from approval ap 
// left join proj_head pr on pr.approval_id = ap.approval_id 
// left join user u on u.user_id = ap.user_id
// left join group_job g on g.g_id = u.g_id 
// left join job j on j.job_id = u.job_id
//  where u.user_id = :uid1");
// $insert_stmt->bindParam(':uid1', $uid);
// $insert_stmt->execute();
// $row = $insert_stmt->fetch(PDO::FETCH_ASSOC);

require_once __DIR__ . '/mpdf/vendor/autoload.php';
require_once('mpdf/src/ThSplitLib/Segment.php');
$segment = new Segment();


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

$mpdf->useDictionaryLBR = false;

$mpdf->AddPageByArray([
    'magin-top'=> '5',
    'magin-bottom'=> '0',
    'margin-left'=> '25',
    'magin-right'=> '15',
    // 'margin-top'=> '5',
    // 'margin-bottom'=> '0',
    // 'margin-left' =>'23',
    // 'margin-right' => '15',
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

    if ($row["approval_self"] =='V' || $row["approval_self"] =='P' )
    {
        $str_s = '/';
        if ($row['approval_self'] == 'V'){
            $str_sy = '/';
        }
        else if ($row['approval_self'] == 'P'){
            $str_sn = '/';
        }
    }
    if ($row['approval_self'] == 'H'){
        $str_h = '/';
    }
    if ($row['approval_self'] == 'B'){
        $str_i = '/';
    }

    $select_cap = $db->prepare("SELECT ap.*, pr.* ,u.* from approval ap left join proj_head pr on pr.approval_id = ap.approval_id left join user u on u.user_id = ap.user_id ");
    $select_cap->execute();
    

    
$text = '<p>' . $row["g_name"] . '
<br>วันที่ '.thainumDigit(DateThai($strDate)). '
<br>เรื่องขออนุมัตเข้าร่วม '.$row["approval_type"].' เรื่อง '.$row["approval_name"].'
<br>เรียน ผู้อำนวยการโรงพยาบาลเทพา
<br>สิ่งที่ส่งมาด้วย &nbsp;&nbsp;&nbsp;1.หนังสือที่ '.$row["approval_number"].'
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2.แบบบันทึกข้อมูลประชุม/อบรม/สัมมนา ส่วนบุคคล (FR-GEN-20)
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ข้าพเจ้า '.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;'.' ตำแหน่ง'.'&nbsp;'.$row["job_name"].'
<br>มีความประสงค์ที่จะขอเข้ารับการฝึกอบรม เรื่อง '.$row["approval_name"].'
<br>จัดโดย  '.$row["approval_organ"].' วันที่ '.thainumDigit(DateThai($strDate1)). ' ถึงวันที่'.thainumDigit(DateThai($strDate2)). ' สถานที่ '.$row["approval_addp"].' ค่าลงทะเบียน '.$row['approval_sum'].' บาท โดยเป็นการฝึกอบรมครั้งที่ '.$row["approval_numof"].' ของปีงบประมาณ เป็นการประชุมซึ่ง
( '.$str_s.' ) ข้าพเจ้าขออนุมัติเข้ารับการอบรมเองโดยเป็นการอบรม ( '.$str_sy.' ) เชิงวิชาชีพ ( '.$str_sn.' ) งานที่ต้องรับผิดชอบเพิ่มเติม
<br>( '.$str_h.' ) โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม  
<br>( '.$str_i.' ) ผู้จัดการอบรมมีหนังสือเชิญโดยระบุชื่อหรือตำแหน่งผู้เข้ารับการอบรมชัดเจน  
<br>และข้าพเจ้าเห็นว่าการอบรมครั้งนี้จะมีประโยชน์ในการปฏิบัติงาน โดยมีรายละเอียดดังต่อไปนี้
<br> 1.วัตถุประสงค์ของฝึกการอบรม
<br>&nbsp;' .$row["approval_obj"].'
<br> 2.ประโยชน์ที่คาดว่าจะได้รับจากการฝึกอบรม
<br>&nbsp;' .$row["approval_benf"].'
<br> 3.กิจกรรมที่คาดว่าจะสามารถดำเนินการได้ภายหลังการฝึกอบรม 
<br> &nbsp;' .$row["approval_ex"].' '

// .$cab = $db->prepare("SELECT ap.*,g.g_name,pv.*,ps.*,br.* from approval ap 
// left join user u on u.user_id = ap.user_id
// left join approval_prov pv on pv.approval_id = ap.approval_id
// left join proj_summary ps on ps.approval_id = ap.approval_id
// left join borrow br on br.approval_id = ap.approval_id
// left join group_job g on g.g_id = u.user_id where ap.approval_id = :pid");
// $cab->execute()
// while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {

// }


.$text='
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ภายในปีงบประมาณนี้ ข้าพเจ้าได้เข้ารับการฝึกอบรมมาแล้ว ดังรายละเอียดต่อไปนี้
<br> (  ) ขออนุมัติเข้ารับการอบรมเอง 
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (  ) เชิงวิชาชีพ  จำนวน  ครั้ง ดังนี้

<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง..............วันที่ ……… สถานที่………… จัดโดย…………………
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง……………………………………วันที่ ……… สถานที่………… จัดโดย…………………
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง……………………………………วันที่ ……… สถานที่………… จัดโดย…………………
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง……………………………………วันที่ ……… สถานที่………… จัดโดย…………………

<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ( ) งานที่ต้องรับผิดชอบเพิ่มเติม   จำนวน ………………….. ครั้ง ดังนี้
<br>  ( ) โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม  จำนวน…….. ครั้ง  
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง……………………………………วันที่ ……… สถานที่………… จัดโดย…………………
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง……………………………………วันที่ ……… สถานที่………… จัดโดย…………………
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง……………………………………วันที่ ……… สถานที่………… จัดโดย…………………
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- เรื่อง……………………………………วันที่ ……… สถานที่………… จัดโดย…………………
<br>  ( ) ผู้จัดการอบรมมีหนังสือเชิญโดยระบุชื่อหรือตำแหน่งผู้เข้ารับการอบรมชัดเจน  จำนวน……………….ครั้ง  
<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณา

';


    $words = $segment->get_segment_array($text);
    $text = implode("|",$words);


$content = '
<style>
.container{
    font-family: "sarabun";
    font-size: 10pt;
}

p{
    text-align: justify;
    font-size: 16pt;
    word-wrap: break-word;
    
}
h1{
    text-align: center;
}

</style>
<div class="container" style="width: 100%">
<h1>บันทึกขออนุมัติเข้ารับการฝึกอบรม 
<br>โรงพยาบาลเทพา</h1> 
'.$text.'
<p style="text-align:right;">ผู้ขอเข้ารับการฝึกอบรม ………………………………………&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( '.$row["f_name"].'&nbsp;'.$row["l_name"].' )&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>ตำแหน่ง '.$row["job_name"].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p>ความคิดเห็นผู้บังคับบัญชา ระดับที่ ๑
<br>ข้อคิดเห็น………………………………………………………………………………………………………
<p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;..........................................&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>ตำแหน่ง&nbsp;.............................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p>ความคิดเห็นผู้บังคับบัญชา ระดับที่ ๒
<br>ข้อคิดเห็น………………………………………………………………………………………………………
<p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;..........................................&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>ตำแหน่ง&nbsp;.............................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p>ความคิดเห็นผู้อำนวยการโรงพยาบาล
<br>อนุมัติ……………………………………………………&nbsp;&nbsp;&nbsp;ไม่อนุมัติ…………………………………………………
<p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;นายเดชา แซ่หลี&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>';



$footer = 'FR-GEN-55 &nbsp;&nbsp;&nbsp; R02 &nbsp; 09/03/44' .'.{PAGENO}';

// $mpdf->setFooter($footer);
$mpdf->SetFooter('<div style="text-align: left  font-size: 15pt ">'.$footer.'</div>
                        <div style="text-align: right  font-size: 15pt ">||PAGE{PAGENO}/{nbpg}</div>');
$mpdf->WriteHTML($content);
// $mpdf->WriteHTML($sen);

// $mpdf->AddPage();

$mpdf->Output();
?>


