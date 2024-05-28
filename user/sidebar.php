<?php 
   
?>

  <aside class="main-sidebar sidebar-dark-primary elevation-4" >
    <!-- Brand Logo -->
    <a href="#" class="brand-link ">
      <img src="../ss.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ระบบยืมเงิน</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="use.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['f_name'] . ' ' . $_SESSION['l_name'];  ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
         
           
          <li class="nav-header">MENU</li>
          <li class="nav-item">
            <a href="list_borrow.php" class="nav-link">
              <i class="nav-icon far bi bi-coin icon-side"></i>
              <p>
               ยืมเงิน
                <!-- <span class="badge badge-info right">2</span> -->
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_pv.php" class="nav-link">
            <i class="nav-icon far bi bi-send-arrow-up-fill icon-side"></i>
              <p>
                หนังสืออนุมัติจังหวัด
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_sumresults.php" class="nav-link">
              <i class="nav-icon far bi bi-card-list icon-side"></i>
              <p>
              สรุปการอบรม
              </p>
            </a>
          </li>
          <li class="nav-header">Logout</li>
          <li class="nav-item">
            <a data-bs-toggle='modal' data-bs-target='#myModal'class="nav-link">
              <i class="nav-icon fas bi bi-box-arrow-left icon-side " ></i>
              <p>ออกจากระบบ</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ออกจากระบบ</h5>
        </button>
      </div>
      <div class="modal-body">
        คุณต้องการอกกจากระบบ หรือไม่ ??
      </div>
      <div class="modal-footer">
     
        <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
        <a type="button" href="../logout.php" class="btn btn-danger  btn-sm">ตกลง</a>
      </div>
    </div>
  </div>
</div>

 