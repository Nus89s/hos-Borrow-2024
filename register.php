<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนใช้งานระบบ</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300&display=swap" rel="stylesheet">
    <style type="text/css">
		body{
			font-family: 'Prompt', sans-serif;
		}
	</style>
</head>

<body class="bg-warning">

    <div class="container">
        <h1 class="mt-3">ลงทะเบียนใช้งานระบบ</h1>
        <hr>

        <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title"> <small>กรุณากรอกข้อมูลลงทะเบียน</small></h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form id="quickForm"  action="register_db.php" method="post" class="form-horizontal my-5">
                <div class="card-body">
                <div class="form-group">
                <label for="username" class="col-sm-3 control-label">ชื่อผู้ใช้งานระบบ</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_username" class="form-control" required placeholder="ชื่อผู้ใช้งานระบบ ภาษาอังกฤษ">
                </div>
            </div>
            <div class="form-group">
                <label for="cid" class="col-sm-3 control-label">เลขบัตรประชาชน</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_cid" class="form-control" required placeholder="เลขบัตรประชาชน">
                </div>
            </div>
            <div class="form-group">
                <label for="fname" class="col-sm-3 control-label">ชื่อ </label>
                <div class="col-sm-12">
                    <input type="text" name="txt_fname" class="form-control" required placeholder="ชื่อ ระบุคำหน้าด้วย">
                </div>
            </div>
            <div class="form-group">
                <label for="lname" class="col-sm-3 control-label">สกุล</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_lname" class="form-control" required placeholder="สกุล">
                </div>
            </div>
            <div class="form-group">
                <label for="tel" class="col-sm-3 control-label">เบอร์โทรศัพท์</label>
                <div class="col-sm-12">
                    <input type="text" name="txt_tel" class="form-control" required placeholder="เบอร์โทร">
                </div>
            </div>
            <div class="form-group">
                <label for="job" class="col-sm-3 control-label">ตำแหน่งงาน/สายงาน</label>
                <div class="col-sm-12">
                    <select name="txt_job" class="form-control">
                        <option value="" selected="selected">- ตำแหน่งงาน/สายงาน -</option>
                        <option value="1">จพ.การเงินและบัญชี</option>
                        <option value="2">จพ.ทันตสาธรณสุข</option>
                        <option value="3">จพ.ธุรการ</option>
                        <option value="4">จพ.พัสดุ</option>
                        <option value="5">จพ.พัสดุ</option>
                        <option value="6">จพ.เวชสถิติ</option>
                        <option value="7">จพ.สาธารณสุข</option>
                        <option value="8">เจ้าพนักงานเวชกิจฉุกเฉิน</option>
                        <option value="9">จพ.เวชกรรมฟื้นฟู</option>
                        <option value="10">ช่างปูน</option>
                        <option value="11">ทันตแพทย์</option>
                        <option value="12">นักกายภาพบำบัด</option>
                        <option value="13">นักจัดการงานทั่วไป</option>
                        <option value="14">นักจิตวิทยา</option>
                        <option value="15">นักเทคนิคการแพทย์</option>
                        <option value="16">นักประชาสัมพันธ์</option>
                        <option value="17">นักโภชนาการ</option>
                        <option value="18">นักรังสีการแพทย์</option>
                        <option value="19">นักวิชาการคอมพิวเตอร์</option>
                        <option value="20">นักวิชาการเงินและบัญชี</option>
                        <option value="21">นักวิชาการสถิติ</option>
                        <option value="22">นักวิชาการสาธารณสุข</option>
                        <option value="23">นักวิชาการสาธารณสุข(เวชสถิติ)</option>
                        <option value="24">นายช่างเทคนิค</option>
                        <option value="25">นายแพทย์</option>
                        <option value="26">ผู้ช่วยเจ้าหน้าที่สาธารณสุข</option>
                        <option value="27">ผู้ช่วยทันตแพทย์</option>
                        <option value="28">ผู้ช่วยพยาบาล</option>
                        <option value="29">ผู้ช่วยแพทย์แผนไทย</option>
                        <option value="30">พนักงานแพทย์และรังสีเทคนิค</option>
                        <option value="31">พนักงานเกษตรพื้นฐาน</option>
                        <option value="32">พนักงานขับรถยนต์</option>
                        <option value="33">พนักงานช่วยเหลือคนไข้</option>
                        <option value="34">พนักงานธุรการ</option>
                        <option value="35">พนักงานบริการ</option>
                        <option value="36">พนักงานประกอบอาหาร</option>
                        <option value="37">พนักงานประจำห้องยา</option>
                        <option value="38">พนักงานแปล</option>
                        <option value="39">พนักงานพัสดุ</option>
                        <option value="40">พนักงานพิมพ์</option>
                        <option value="41">พนักงานโสตทัศนศึกษา</option>
                        <option value="42">พยาบาลวิชาชีพ</option>
                        <option value="43">แพทย์แผนไทย</option>
                        <option value="44">เภสัชกร</option>
                        <option value="45">หัวหน้าพยาบาล พยาบาลวิชาชีพ</option>
                        <option value="46">พนักงานทั่วไป</option>
                        <option value="47">จพ.เครื่องคอมพิวเตอร์</option>
                        <option value="48">พนักงานช่วยเหลือคนไข้(ผู้ช่วยแพทย์แผนไทย)</option>
                        <option value="49">พนักงานซักฟอก</option>
                        <option value="50">ผู้ช่วยวิจัย</option>
                        <option value="51">นักวิชาการโสตทัศนศึกษา</option>
                        <option value="52">นักวิชาการพัสดุ</option>
                        <option value="53">เจ้าพนักงานรังสีการแพทย์</option>
                        <option value="54">ผู้ช่วยหนักกายภาพบำบัด</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="group_job" class="col-sm-3 control-label">สังกัดกลุ่มงาน/ฝ่ายงาน </label>
                <div class="col-sm-12">
                    <select name="txt_group_job" class="form-control">
                        <option value="" selected="selected">- สังกัดกลุ่มงาน/ฝ่ายงาน -</option>
                        <option value="1">กลุ่มงานบริหารงานทั่วไป</option>
                        <option value="2">กลุ่มงานเทคนิตการแพทย์</option>
                        <option value="3">กลุ่มงานทันตกรรม</option>
                        <option value="4">กลุ่มงานเภสัชและคุ้มครองผู้บริโภค</option>
                        <option value="5">กลุ่มงานทางการแพทย์</option>
                        <option value="6">กลุ่มงานโภชนศาสตร์</option>
                        <option value="7">กลุ่มงานรังสีวิทยา</option>
                        <option value="8">กลุ่มงานเวชศาสตร์ฟื้นฟู</option>
                        <option value="9">กลุ่มงานประกันสุขภาพ ยุทธศาสตร์และสารสนเทศทางการแพทย์</option>
                        <option value="10">กลุ่มงานบริการด้านปฐมภูมิและองค์รวม</option>
                        <option value="11">กลุ่มงานพยาบาล</option>
                        <option value="12">กลุ่มงานการแพทย์แผนไทยและการแพทย์ทางเลือก</option>
                        <option value="13">กลุ่มงานจิตเวชและยาเสพติด</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="position" class="col-sm-3 control-label">ตำแหน่ง</label>
                <div class="col-sm-12">
                    <select name="position" class="form-control">
                        <option value="" selected="selected">- ตำแหน่ง -</option>
                        <option value="1">ข้าราชการพลเรือนสามัญ</option>
                        <option value="2">ลูกจ้างประจำ</option>
                        <option value="3">พนักงานราชการ</option>
                        <option value="4">พนักงานกระทรวงสาธารณสุข</option>
                        <option value="5">ลูกจ้างชั่วคราว</option>
                        <option value="6">ลูกจ้างเหมา</option>
                        <option value="7">ลูกจ้างแพทย์แผนไทย</option>
                        <option value="8">ลูกจ้างชั่วคราว(รายวัน)</option>
                        <option value="9">ลูกจ้างเหมาโครงการ</option>
                        <option value="10">แพทย์หมุนเวียน</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="ms_status" class="col-sm-3 control-label">สถานภาพ</label>
                <div class="col-sm-12">
                    <select name="txt_ms_status" class="form-control">
                        <option value="" selected="selected">- สถานภาพ -</option>
                        <option value="1">โสด</option>
                        <option value="2">สมรส</option>
                        <option value="3">หม้าย</option>
                        <option value="4">หย่า</option>
                        <option value="5">แยกกันอยู่</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="rg" class="col-sm-3 control-label">ศาสนา</label>
                <div class="col-sm-12">
                    <select name="txt_rg" class="form-control">
                        <option value="" selected="selected">- ศาสนา -</option>
                        <option value="1">พุทธ</option>
                        <option value="2">อิสลาม</option>
                        <option value="3">อื่นๆ</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="f_date" class="form-label" for="date">วันที่เริ่มทำงาน</label>
                    <input class="form-control" id="date" name="f_date" placeholder="MM/DD/YYY" type="date" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="birthday" class="form-label" for="date">วันที่เกิด</label>
                    <input class="form-control" id="date" name="birthday" placeholder="MM/DD/YYY" type="date" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="addr" class="form-label">ที่อยู่</label>
                    <textarea class="form-control" name="addr" rows="3"></textarea>
                </div>
            </div>
            <label for="password" class="col-sm-3 control-label">รหัสผ่าน</label>
            <div class="col-sm-12">
                <input type="password" name="txt_password" class="form-control" required placeholder="ป้อนรหัสผ่าน">
            </div>
            <div class="form-group">
                <div class="col-sm-12 mt-3">
                    <input type="submit" name="btn_register" class="btn btn-primary" style="width: 100%;" value="ลงทะเบียน">
                </div>
            </div>

                 <div class="form-group text-center">
                <div class="col-sm-12 mt-3">
                    <h8> มีบัญชีอยู่แล้ว ? </h8>
                    <p><a href="login.php">เข้าสู่ระบบ</a></p>
                </div>
            </div>
                </div>
                <!-- /.card-body -->
            </form>
            <div class="card-footer">
            <strong>Copyright &copy; 2024 <a href="#">ThephaBorrow</a>.</strong>
            </div>
            </div>
            <!-- /.card -->
            </div>
          <!--/.col (left) -->
          <!-- right column -->
        
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    


    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
<style>



    .bg-warning {
        
        background-color: #FF3CAC;
        background-image: linear-gradient(200deg, #FF3CAC 0%, #784BA0 50%, #2B86C5 100%);
        background-repeat: no-repeat;
        background-repeat: no-repeat;
        background-size: cover;
        /* filter: blur(10px); */
    
      }

      
</style>

</html>