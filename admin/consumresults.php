<title>ยืนยันสรุปอบรม</title>
<?php
include 'head_admin.php';
include 'nav_admit.php';
include 'sidebar_admin.php'




?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">

        <div class="container text-center">

            <h3><label class="form-text">ยืนยันสรุปอบรม</label></h3>

        </div>
        <br>

        <?php
        if (isset($updateapptMsg)) {
        ?>
            <script>
                setTimeout(function() {
                    swal({
                        title: "<?php echo $updateapptMsg; ?>",
                        type: "success"
                    }, function() {
                        window.location = "index.php"; //หน้าที่ต้องการให้กระโดดไป
                    });
                }, 1000);
            </script>

        <?php } ?>


        <div class="input-group">


        </div>
        <!-- Modal -->


        <div class="card">
            <!-- <div class="card-header">
                <h3 class="text-center">บันทึกโครงการ</h3>
              </div> -->
            <!-- /.card-header -->
            <div class="card-body" style="background-color:#f2ece3 ">

                <form id="myform2" action="editapp.php" method="post">
                    <?php
                    if (isset($_REQUEST['upd'])); {

                        $pid = $_REQUEST['a_id'];
                        $co = $db->prepare("SELECT ap.*,g.g_name from approval ap 
            left join user u on u.user_id = ap.user_id
            left join group_job g on g.g_id = u.user_id where approval_id = :pid");
                        $co->bindParam(':pid', $pid);
                        $co->execute();
                        $row = $co->fetch();
                    }
                    ?>
                    <div class="container">


                        <!-- Name input -->
                        <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="name4">ความหนังสือ</label>

                                <input type="text" value="<?php echo $row['approval_number']; ?>" class="form-control" placeholder="" name="approval_number" readonly>
                            </div>


                            <div class="col">
                                <?php
                                $date = date("Y-m-d");

                                ?>
                                <label class="form-label" class="form-label" for="date">ลงวันที่</label>

                                <input class="form-control" value="<?php echo $row['approval_in_date']; ?>" id="date" name="approval_in_date" placeholder="MM/DD/YYY" type="date" required readonly>
                            </div>
                        </div>
                        <!-- <div class="col">
                  <label for="firstname" class="form-label" for="date">ระหว่างวันที่</label>
                  <label class="form-text">(วันที่ดำเนินการ)</label>
                  <input class="form-control" id="date" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required>
                </div> -->
                        <div class="form-outline mb-4">
                            <label class="form-label" for="name4">เรื่อง</label>

                            <input type="text" id="name4" value="<?php echo $row['approval_in_name']; ?>" name="approval_in_name" class="form-control" required readonly>

                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="g_name">เขียนที่</label>
                                <input type="text" class="form-control" name="g_name" readonly placeholder=<?php echo $row["g_name"]; ?>>
                            </div>

                            <div class="col">
                                <?php
                                $date = date("Y-m-d");

                                ?>
                                <label class="form-label" for="name4">เรียน</label>
                                <input type="text" class="form-control" name="text1" readonly placeholder="ผู้อำนวยการโรงพยาบาลเทพา" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="approval_date">ลงวันที่</label>
                                <input type="date" value="<?php echo $date; ?>" class="form-control" name="approval_date" readonly placeholder="<?php echo $date; ?>">
                            </div>

                            <div class="col">
                                <label class="form-label" for="name4">ประเภท</label>
                                <input type="text" class="form-control" name="text1" readonly placeholder="<?php echo $row['approval_type']; ?>" />

                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label class="form-label" for="approval_name">เรื่อง</label>
                                <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" readonly name="approval_name" class="form-control">
                            </div>
                            <br>

                        </div>

                        <div class="form-outline mb-4">
                            <label class="form-label" for="approval_organ">จัดโดย</label>
                            <input type="text" id="name4" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" class="form-control" required readonly>
                        </div>

                        <div class="form-outline mb-4">
                            <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>

                            <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" required readonly>

                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label for="firstname" class="form-label" for="approval_fdate">ระหว่างวันที่</label>

                                <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required readonly>
                            </div>
                            <div class="col">
                                <?php
                                $date = date("Y-m-d");

                                ?>
                                <label class="form-label" for="approval_edate">ถึงวันที่</label>

                                <input class="form-control" id="date" value="<?php echo $row['approval_edate']; ?>" name="approval_edate" placeholder="MM/DD/YYY" type="date" required readonly>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col">
                                <label class="form-label" for="approval_sum">ค่าลงทะเบียน</label>

                                <input type="text" class="form-control" value="<?php echo $row['approval_sum']; ?>" name="approval_sum" readonly>
                            </div>
                            <div class="col">
                                <label class="form-label" for="approval_numof">ครั้งที่</label>

                                <input type="text" class="form-control" value="<?php echo $row['approval_numof']; ?>" readonly name="approval_numof" />
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col">
                                <label for="approval_self" class="col control-label">ข้าพเจ้าขออนุมัติการเข้าเข้าร่วมเองโดยเป็นการเข้าร่วม</label>
                                <div class="col-sm-12">
                                    <select name="approval_self" class="form-control" readonly>
                                        <option value="<?php echo $row['approval_self']; ?>" selected="selected"> <?php if ($row["approval_self"] == 'V') {
                                                                                                                        echo  "เชิงวิชาชีพ";
                                                                                                                    } else if ($row["approval_self"] == 'P') {
                                                                                                                        echo "งานที่ต้องรับผิดชอบเพิ่มเติม";
                                                                                                                    } else if ($row["approval_self"] == 'N') {
                                                                                                                        echo "-";
                                                                                                                    } ?> </option>

                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <br>
                                <div class="form-check">
                                    <input id='testNameHidden' type='hidden' value='N' name='approval_hsent' readonly>
                                    <input class="form-check-input" type="checkbox" value="Y" id="flexCheckChecked" name="approval_hsent" <?php if ($row['approval_hsent'] == "Y") {
                                                                                                                                            ?> checked="" <?php
                                                                                                                                            } ?>>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input id='testNameHidden' type='hidden' value='N' name='approval_invite' readonly>
                                    <input class="form-check-input" type="checkbox" value="Y" id="flexCheckDefault" name="approval_invite" <?php if ($row['approval_invite'] == "Y") {
                                                                                                                                            ?> checked="" <?php
                                                                                                                                            } ?>>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        ผู้จัดการอบรมมีหนังสือเชิญโดยระบุชื่อหรือตำแหน่งผู้เข้ารับการอบรมชัดเจน
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <label class="form-label" for="textarea4">วัตถุประสงค์การเข้าร่วม</label>
                        <textarea id="textarea3" rows="4" name="approval_obj" readonly class="form-control" required><?php echo $row['approval_obj']; ?> </textarea>
                        <br>
                        <label class="form-label" for="textarea4">ประโยชน์ที่คาดว่าจะได้รับ</label>
                        <textarea id="textarea3" rows="4" name="approval_benf" readonly class="form-control" required><?php echo $row['approval_benf']; ?></textarea>
                        <br>
                        <label class="form-label" for="textarea4">กิจกรรมที่คาดว่าจะสามารถดำเดินการได้ภายหลังเข้าร่วม</label>
                        <textarea id="textarea3" rows="4" name="approval_ex" readonly class="form-control" required><?php echo $row['approval_ex']; ?></textarea>




                        <div class="row">
                            <br>

                            <div class="col text-center">
                                <font class="form-text" color="red">ไฟล์หนังสือเชิญ</font>
                                <a href="../docs/approval_in/<?php echo $row['doc_in']; ?>" target="_blank" class="btn btn-info btn-sm"> เปิดดู </a>
                            </div>
                            </div>
                            <br>
                           
                            <div class="row">
                                <div class="col">
                                    <div class="ExternalFiles text-center">
                                        <iframe src="../docs/approval_in/<?php echo $row['doc_in']; ?>" height="500px" width="500px">

                                        </iframe>

                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center">
                                <div class="col-md-12 mt-3">
                                    <input type="hidden" name="id" value="<?= $row['approval_id']; ?>">
                                    <!-- <input type="submit" name="update" class="btn btn-primary" value="บันทึก"> -->
                                    <a href="sumresults.php" class="btn btn-danger">กลับ</a>
                                </div>
                            </div>
                           
                </form>
            </div>
        </div>
        <style>
            .icon-red {
                color: darkred;
                font-size: 20px;
            }

            .icon-green {
                color: green;
                font-size: 25px;
            }

            .icon-yellow {
                color: darkorange;
                font-size: 25px;
            }

            .icon-lock {
                color: red;
                font-size: 25px;
            }

            .w3-sidebar {
                position: fixed;
                top: 0px;
                bottom: 0;
                left: 0;
                z-index: 1000;
                padding: 20px;
                background-color: #6bc4ff;
                width: 220px;

            }
        </style>

    </section>
    <!-- /.content -->
</div>







</html>

<?php
include 'footer.php';
?>