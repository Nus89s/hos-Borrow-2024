
<?php
session_start();
if (!isset($_SESSION['user_id'])) { // ถ้าไม่ได้เข้าระบบอยู่
    header("location: login.php"); // redirect ไปยังหน้า login.php
    exit;
}


require_once "conn.php";



// if (isset($_GET['approval_id'])) {
//         $id = $_GET['approval_id'];
//         $stmt = $db->query("SELECT * FROM approval WHERE approval_id = $id");
//         $stmt->execute();
//         $data = $stmt->fetch();
// }

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

 (isset($_REQUEST['borid'])) ;
    $pid = $_REQUEST['borid'];
    $insert_stmt = $db->prepare("SELECT ap.*, pr.* ,u.* ,g.* ,j.* ,pv.*, br.* from approval_prov pv
    left join approval ap on ap.approval_id = pv.approval_id
    left join proj_head pr on pr.approval_id = ap.approval_id 
    left join user u on u.user_id = ap.user_id
    left join group_job g on g.g_id = u.g_id 
    left join job j on j.job_id = u.job_id
    left join borrow br on br.approval_id = pv.approval_id
    where pv.approval_id = :apprid");

    $insert_stmt->bindParam(':apprid', $pid);
    $insert_stmt->execute();
    $row = $insert_stmt->fetch();


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
    // 'margin-left'=> '30',
    // 'magin-right'=> '15',
    // 'magin-top'=> '5',
    // 'magin-bottom'=> '5',

    'margin-top'=> '5',
    'margin-bottom'=> '0',
    'margin-left' =>'25',
    'margin-right' => '15',
]);



    
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
		return "&nbsp;$strDay&nbsp;เดือน&nbsp;$strMonthThai&nbsp;พ.ศ.&nbsp;$strYear";
	}
    function DateThaicut($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		// $strHour= date("H",strtotime($strDate));
		// $strMinute= date("i",strtotime($strDate));
		// $strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("" ,"มกราคม" , "กุมภาพันธ์", "มีนาคม" , "เมษายน" ,"พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay&nbsp;$strMonthThai&nbsp;$strYear";
	}
    function thainumDigit($num){

        return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ),
        array( "๐" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ),
        $num);
        };

	$strDate = $row["borrow_date"];
    $strDate1 = $row["approval_fdate"];
    $strDate2 = $row["approval_edate"];
    $Dturn = $row["borrow_turn"];
    $Dofdate = $row["borrow_ofdate"];
    $Dapdate = $row["borrow_apdate"];
    $Dacc = $row["borrow_accmoney"]; 

    
	echo "ThaiCreate.Com Time now : ".thainumDigit(DateThai($strDate));

    if ($row["approval_self"] !='N')
    {
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
    
$text = '<b style=" font-size: 20pt;">ส่วนราชการ</b>&nbsp;&nbsp; โรงพยาบาลเทพา &nbsp;&nbsp;อำเภอเทพา&nbsp;จังหวัดสงขลา&nbsp;โทร. ๐ ๗๔๓๗ ๖๓๕๙ ต่อ ๑๐๐๙
<br><b>ที่</b> ' . $row["appr_number"] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> วันที่ </b>'.thainumDigit(DateThai($strDate)). '
<br><b>เรื่อง</b> ขออนุมัติยืมเงิน
<br>เรียน ผู้อำนวยการโรงพยาบาลเทพา
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เรื่องเดิม
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามที่หน่วยงาน&nbsp;'.$row["approval_organ"].'
<br>ได้ประชุม/อบรม/สัมมนา&nbsp;&nbsp;เรื่อง&nbsp;'.$row["approval_name"].'&nbsp;นั้น&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อเท็จจริง
<br>การประชุม/อบรม/สัมมนา&nbsp;ได้กำหนดเวลาการดำเนินงานในระหว่างวันที่'.thainumDigit(DateThai($row["approval_fdate"])).'&nbsp;ถึงวันที่&nbsp;'.thainumDigit(DateThai($row["approval_edate"])).'
<br>ณ&nbsp;'.$row["approval_addp"].'&nbsp;'.$row["approval_organ"].'
<br>โดยขออนุมัติเบิกจ่ายค่าใช้จ่ายต่างๆ&nbsp;จำนวนเงิน&nbsp;'.$row["borrow_sum"].'&nbsp;บาท&nbsp;&nbsp;&nbsp;(&nbsp;'.Convert($row['borrow_sum']).'&nbsp;)
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อพิจารณา
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในการนี้&nbsp;&nbsp;'.$row["f_name"].' '.$row["l_name"].'
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ขออนุมัติยืมเงิน จำนวนเงิน&nbsp;'.$row["borrow_sum"].'&nbsp;บาท&nbsp;&nbsp;&nbsp;(&nbsp;'.Convert($row['borrow_sum']).'&nbsp;)
<br> 
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณา
<br><p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.............................................................ผู้เบิก&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;'.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง&nbsp;'.$row["job_name"].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.........................................................ผู้รับรอง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;'.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง&nbsp;'.$row["job_name"].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>หัวหน้ากลุ่มงาน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br><p style="text-align:right;"> &nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ.........................................................ผู้อนุมัติ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;นายเดชา&nbsp;แซ่หลี&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง&nbsp;ผู้อำนวยการโรงพยาบาลเทพา&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></p></p>


';
    


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
im {
    background-image: url("KR.png");
    background-repeat: no-repeat;
    background-position: left top;
    background-size: 30px 10px;
  }

</style>
<body>
<div class="container" style="width: 100%">
<p>
<p><img src="KR.png" width="60" height="65">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style = "font-size: 26pt; ">บันทึกข้อความ</b><br>
'.$text.'

</p>

</div>
</div> </body>';



$footer = 'FR-GEN-55 &nbsp;&nbsp;&nbsp; R02 &nbsp; 09/03/44' .'.{PAGENO}';

$uid = $_SESSION['user_id'];

        // $rowu = $db->prepare("SELECT u.*,j.job_name,p.pos_name from user u 
        // LEFT join group_job g on g.g_id = u.g_id
        // left join job j on j.job_id = u.job_id
        // left join position p on p.pos_id = u.pos_id 
        // where u.user_id = '$uid' ");
        // //$rowu->bindParam(':user_id', $uid);
        // $rowu->execute();
        // $rowus = $rowu->fetch();



        $rowu = $db->prepare("SELECT u.*,j.job_name,p.pos_name from user u 
        LEFT join group_job g on g.g_id = u.g_id
        left join job j on j.job_id = u.job_id
        left join position p on p.pos_id = u.pos_id 
        where u.user_id = '$uid'");

    $rowu->bindParam(':apprid', $pid);
    $rowu->execute();
    $rowus = $rowu->fetch();


  if($row['borrow_turn_haft'] == 'Y'){
    $turn = '&nbsp;คือ&nbsp;<b>ภายใน&nbsp;15&nbsp;วัน&nbsp; นับตั้งแต่วันที่เดินทางกลับ</b>';

  } else if ($row['borrow_turn_month'] == 'Y'){

    $turn = '<b>ภายใน&nbsp;30&nbsp;วัน&nbsp;นับตั้งแต่วันที่เดินทางกลับ</b>';
    
  }
$data =
'

<style>
.container{
    font-family: "sarabun";
    font-size: 10pt;
    text-align: left;
}

p{
    text-align: left;
    font-size: 16pt;
    
}
table{
    font-family: "sarabun";

}
</style>



<div class="container">

<table width="100%" style="border-collapse: collapse;font-size:14pt; ">
<tr>
<td style="padding:5px;text-align:left;width:70%;border:0.75px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="font-size: 16pt;">สัญญาการยืมเงิน</b>
<br>ยื่นต่อ&nbsp;ผู้อำนวยการโรงพยาบาลทพา</td>
<td style="padding:10px;text-align:left;width:30%;border:1px solid black; font-size:12pt;">
เลขที่....<b>'.$row['borrow_number'].'</b>..........
<br>วันครบกำหนด <b>'.DateThaicut($row['borrow_edate']).'</b></td>
</tr>
</table>

<div style="
width:320mm;height:15mm;
border:1.5px solid black;
position: auto;   
text-align:center;font-size:14pt;
rotate: 0;
padding:5px;text-align:left; tab-size: 4;">
<b>&nbsp;ข้าพเจ้า&nbsp;'.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;&nbsp;ตำแหน่ง'.$row["job_name"].'
&nbsp;สังกัด&nbsp;โรงพยาบาลเทพา&nbsp;&nbsp;อำเภอเทพา&nbsp;&nbsp;จังหวัดสงขลา
<br>&nbsp;มีความประสงค์ขอยืมเงินจาก&nbsp;เงินบำรุงโรงพยาบาลเทพา&nbsp;เพื่อเป็นค่าใช้จ่ายในการเดินทางไปราชการ ดังรายละเอียดต่อไปนี้
</div>


<table width="100%" style="border-collapse: collapse;font-size:14pt;">
<tr>
<td style="padding:5px;text-align:left;width:60%;border:0.75px solid black;">
&nbsp;ค่าเบี้ยเลี้ยง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$row["borrow_allw"].'&nbsp;&nbsp;บาท
<br>&nbsp;ค่าเช่าที่พัก&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row["borrow_accom"].'&nbsp;&nbsp;บาท
<br>&nbsp;ค่าพาหนะ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row["borrow_veh"].'&nbsp;&nbsp;บาท
<br>&nbsp;ค่าลงทะเบียน&nbsp;&nbsp;&nbsp;'.$row["borrow_regis"].'&nbsp;&nbsp;บาท
<br>&nbsp;ค่าสมนาคุณ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row["borrow_reward"].'&nbsp;&nbsp;บาท
<br>&nbsp;อื่นๆ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$row["borrow_another"].'&nbsp;&nbsp;บาท
<br>&nbsp;รวมเงินทั้งสิ้น&nbsp;&nbsp;&nbsp;'.$row["borrow_sum"].'..บาท&nbsp;(&nbsp;'.Convert($row['borrow_sum']).'  )


</td>
<td style="padding:0px;text-align:left;width:40%;border:0.75px solid black; magin-top: 2;">

หมายเหตุ 

</td>
</tr>
</table>

<div style="
width:320mm;height:20mm;
border:1.5px solid black;
position: auto;   
text-align:center;font-size:14pt;
rotate: 0;
padding:5px;text-align:left;">
&nbsp;&nbsp;&nbsp;'.'ข้าพเจ้าสัญญาว่าจะปฏิบัติตามระเบียบของทางราชการทุกประการ และจะนำใบสำคัญคู่จ่ายที่ถูกต้อง
พร้อมทั้งเงินเหลือจ่าย (ถ้ามี) ส่งใช้ภายในกำหนดไว้ในระเบียบการเบิกจ่ายเงินจากคลังคือ&nbsp;'.$turn.'&nbsp;ถ้าข้าพเจ้าไม่ส่งตามกำหนด&nbsp;ข้าพเจ้ายินยอมให้<br>หักเงินเดือน&nbsp;ค่าจ้าง&nbsp;เบี้ยหวัด&nbsp;บำเหน็จ&nbsp;บำนาญ&nbsp;หรือเงินอื่นใดที่ข้าพเจ้าพึงได้รับจากทางราชการ&nbsp;ชดใช้จำนวนเงินที่ยืมไปจนครบถ้วนได้ทันที
<br>ลายมือชื่อ……………………………………………………..………. ผู้ยืม&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่ '.thainumDigit(DateThai($Dturn)). '

</div>
<div style="
width:320mm;height:20mm;
border:1.5px solid black;
position: auto;   
text-align:center;font-size:14pt;
rotate: 0;
padding:5px;text-align:left;">
<b>เสนอ ผู้อำนวยการโรงพยาบาลเทพา</b>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>ได้ตรวจสอบแล้ว&nbsp;เห็นควรอนุมัติให้ยืมตามใบยืมฉบับนี้ได้จำนวน&nbsp;'.$row['borrow_sum'].'&nbsp;บาท&nbsp;(&nbsp;'.Convert($row['borrow_sum']).'&nbsp;) </b>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ……………………………………………………...……….&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่ '.thainumDigit(DateThai($Dofdate)). '
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;'.$rowus['f_name'].'&nbsp;&nbsp;'.$rowus["l_name"].'&nbsp;)
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;'.$rowus['job_name'].'&nbsp;&nbsp;
&nbsp;<div style = "text-align:center;"><b>คำอนุมัติ</b></div>
&nbsp;&nbsp;&nbsp;&nbsp;อนุมัติให้ยืมตามเงื่อนไขข้างต้นได้
&nbsp;&nbsp;&nbsp;&nbsp;จำนวนเงิน&nbsp;'.$row['borrow_sum'].'&nbsp;บาท&nbsp;&nbsp;&nbsp;(&nbsp;'.Convert($row['borrow_sum']).'&nbsp;)&nbsp;
<br>
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ……………………………………………………...……….ผู้อนุมัติ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่ '.thainumDigit(DateThai($Dapdate)). '
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;นายเดชา แซ่หลี&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง&nbsp;ผู้อำนวยการโรงพยาบาลเทพา&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>


<div style="
width:320mm;height:20mm;
border:1.5px solid black;
position: auto;   
text-align:center;font-size:14pt;
rotate: 0;
padding:5px;text-align:center;">
&nbsp;<b>ใบรับเงิน</b>
<br>&nbsp;&nbsp;&nbsp;&nbsp;ได้รับเงินยืมจำนวนเงิน
&nbsp;'.$row['borrow_sum'].'&nbsp;บาท&nbsp;&nbsp;(&nbsp;'.Convert($row['borrow_sum']).'&nbsp;)&nbsp;ไปเป็นการถูกต้องแล้ว
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ……………………………………………………...……….ผู้รับเงิน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่ '.thainumDigit(DateThai($Dacc)). '
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;&nbsp;&nbsp;'.$row["f_name"].'&nbsp;'.$row["l_name"].'&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<style>
table, th, td {
    border:0.75px solid black;
  }
  </style>
<table width="100%" style="padding:0px;border:0.75px solid black; border-collapse: collapse;font-size:14pt;">
<tr>
    <th width="5%" rowspan="2">ครั้งที่</th>
    <th width="15%" rowspan="2">วันที่/เดือน/ปี</th>
    <th width="27%" colspan="2">รายการส่งใช้</th>
    <th  width="15%" rowspan="2">คงค้าง</th>
    <th width="15%" rowspan="2">ลงชื่อผู้รับหลักฐาน</th>
    <th width="23%" rowspan="2">เลขที่ใบเสร็จ/ใบรับใบเสร็จ</th>
    
  </tr>
  <tr>
    <td><b>ใบสำคัญ/เงินสด</b></td>
    <td><b>จำนวนเงิน</b></td>
  </tr>

<tr>
    <td><p style="color:#ffffff;">3</td>
</tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
<tr>
    <td><p style="color:#ffffff;">3</td>
</tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
<tr>
    <td><p style="color:#ffffff;">3</td>
</tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
<tr>
    <td><p style="color:#ffffff;">3</td>
</tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>

</table>
<div style="text-align:;left;  font-family: sarabun; font-size:14pt;"><b>*กรุณาแนบสำเนาคู่ฉบับพร้อมกับหลักฐานการส่งใช้เงินยืม และส่งหลักฐานการส่งใช้เงินยืมหลังจัดโครงการ/เดินทางกลับ ภายใน 7 วัน</div>

</p>';

// $mpdf->setFooter($footer);
// $mpdf->SetFooter('<div style="text-align: left  font-size: 15pt ">'.$footer.'</div>
//                         <div style="text-align: right  font-size: 15pt ">||PAGE{PAGENO}/{nbpg}</div>');

$mpdf->WriteHTML($content);
// $mpdf->WriteHTML($sen);

$mpdf->AddPageByArray([
    // 'margin-left'=> 15,
    // 'magin-right'=> 5,
    // 'magin-top'=> 0,
    // 'magin-bottom'=> 0,
    'margin-top'=> '5',
    'margin-bottom'=> '0',
    'margin-left' =>'10',
    'margin-right' => '10',
]);
$mpdf->WriteHTML('<div style="text-align:right;  font-family: sarabun; font-size:12pt;"><b>&nbsp;แบบ๘๕๐๐');
$mpdf->WriteHTML($data);
$mpdf->Output();
?>


