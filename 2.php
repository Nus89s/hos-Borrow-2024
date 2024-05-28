<?php
require_once('conn.php');

//    $uid = $_SESSION['user_id'];
//    $borrow_id = $_REQUEST['borrow_id'];
//    $redate = $_REQUEST['redate'];
//    $qty = $_REQUEST['qty'];
//    $borrow_sum = $_REQUEST['approval_sum'];
   $approval_id = 26;
   //$rmn = $borrow_sum - $qty ;

$ck_re = $db->prepare("SELECT remaining,borrow_id,MAX(redate) dt FROM refund WHERE approval_id = :borrow_id AND borrow_id IN ( SELECT borrow_id FROM refund GROUP BY borrow_id HAVING COUNT(*) > 1 )");
$ck_re->bindParam(':borrow_id',$approval_id);
$ck_re->execute();
$row = $ck_re->fetch(PDO::FETCH_ASSOC);


	$Dt = $row['dt'];
	
	
	echo $Dt;

	



?>