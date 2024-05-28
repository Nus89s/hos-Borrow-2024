<?php session_start();
require_once('conn.php');



if (isset($_REQUEST['appid'])) {
    $conapp = $_REQUEST['appid'];
    $conap = $db->prepare("UPDATE `proj_head` SET `approval_st` = 'Y' ,appr_st = 'N' WHERE `approval_id` = :appid;");

    $conap->bindParam(':appid',$conapp);
    // $conap->execute();
    if ($conap->execute()) {

      //$lastID = $db->lastInsertId();
  
      //echo $lastID;
      $select = $db->prepare("select * from approval where `approval_id` = $conapp");
      $select->execute();
      $row1 = $select->fetch(PDO::FETCH_ASSOC);

      $uid = $row1['user_id'];
      $conapp = $_REQUEST['appid'];
      $approval_number = $row1['approval_number'];
      $approval_name = $row1['approval_name'];

      
      //echo $uid;

      
      $stmt2 = $db->prepare("INSERT INTO approval_prov (user_id ,approval_id,approval_name,approval_number,appr_status) VALUES (:user_id,:appid,:approval_name,:approval_number,'N')");
      //bindParam data type
      //$stmt2->bindParam(':approval_date',$approval_date);
      $stmt2->bindParam(':user_id',$uid);
      $stmt2->bindParam(':appid',$conapp);
      $stmt2->bindParam(':user_id',$uid);
      $stmt2->bindParam(':approval_number',$approval_number);
      $stmt2->bindParam(':approval_name',$approval_name);

     if ($result2 = $stmt2->execute()){
        $lastID = $db->lastInsertId();

      //echo $lastID;
  
      $uapid = $db->prepare("UPDATE proj_head SET  appr_id = $lastID WHERE approval_id = $conapp");
      //bindParam data type
      //$uapid->execute();
      if ($uapid->execute()) {
        $confirmMsg = "บันทึกข้อมูลสำเร็จ";
      
        header('Location: admin_home.php');
  
      }
     
     }
       


  
      // header("refresh:2;index.php");
    }
    



    
  } //END $_REQUEST['appid']

  
   if (isset($_REQUEST['appr_id'])){
  
    $apprid = $_REQUEST['appr_id'];
    // $conpv = $db->prepare("UPDATE `proj_head` SET `appr_st` = 'Y' WHERE `appr_id` = :apprid;") ;
    // $conpv->bindParam(':apprid',$apprid);
    // $conar = $conpv -> execute();
  
  
    $confpv = $db->prepare("UPDATE `proj_head` SET `appr_st` = 'Y' WHERE `appr_id` = :apprid;");
    $confpv->bindParam(':apprid',$apprid);
    if ($confirmpv = $confpv->execute()){

      $apprid = $_REQUEST['appr_id'];
      $constatuspv = $db->prepare("UPDATE  approval_prov SET appr_status = 'Y' WHERE `appr_id` = :apprid;");
      $constatuspv->bindParam(':apprid', $apprid);
      $restm_pv = $constatuspv->execute();

      if ($restm_pv = $constatuspv->execute()){
        $lastID = $db->lastInsertId();

      //echo $lastID;
  
      $uappvid = $db->prepare("UPDATE proj_head SET borrow_id = $lastID WHERE appr_id = $apprid");
      //bindParam data type
      $uappvid->execute();
     
     }

      header('Location: approval_ prov.php');
    }
  
  
  
  
  
    
  }

  // if (isset($_REQUEST['apprid'])) {

  //   $apprid = $_REQUEST['apprid'];
  //   //$conapp = $_REQUEST['appid'];
  //   // $appr_number = $_REQUEST['appr_number'];
  //   // $appr_cdate = $_REQUEST["appr_cdate"];
  //   $u_apprv = $db->prepare("UPDATE `proj_head` SET `appr_st` = 'Y' ,borrow_st = 'N' WHERE `appr_id` = :apprid;");
  //   $u_apprv->bindParam(':apprid',$apprid);
  //   //$u_apprv->bindParam(':appid',$conapp);
    
  //   if($upapprv = $u_apprv->execute());{

  //     $apprid = $_REQUEST['apprid'];
  //     $appr_number = $_REQUEST['appr_number'];
  //     $appr_cdate = $_REQUEST["appr_cdate"];
  //     $stm_pv = $db->prepare("UPDATE  approval_prov SET appr_number = :appr_number ,appr_cdate = :appr_cdate WHERE `appr_id` = :apprid;");
  //     $stm_pv->bindParam(':apprid',$apprid);
  //     $stm_pv->bindParam(':appr_number',$appr_number);
  //     $stm_pv->bindParam(':appr_cdate',$appr_cdate);
  //     $restm_pv = $stm_pv->execute();

  //     header('Location: approval_ prov.php');

  //   }

  // }
 //header('Location: approval_ prov.php');

 

?>