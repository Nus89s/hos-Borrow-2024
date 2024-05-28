<?php

?>

<div class="modal fade" id="con_<?php echo $row['approval_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">ต้องการยืนยันการขออบรม ?</h5>
        </button>
      </div>
      <div class="modal-body">
        <br>
        <div class="row">
          <div class="col">
            <label class="form-label" for="name4">ความหนังสือ</label>
            <span>(เลขหนังสือเชิญ)</span>
            <input type="text" value="<?php echo $row['approval_number']; ?>" class="form-control" placeholder="" name="approval_number" readonly>
          </div>


          <div class="col">
            <?php
            $date = date("Y-m-d");

            ?>
            <label class="form-label" class="form-label" for="date">ลงวันที่</label>
            <span>(วันที่หนังสือเชิญ)</span>
            <input class="form-control" value="<?php echo $row['approval_in_date']; ?>" id="date" name="approval_in_date" placeholder="MM/DD/YYY" type="date" required readonly>
          </div>
        </div>
        <div class="form-outline mb-4">
          <label class="form-label" for="name4">เรื่อง</label>
          <span>(จากหนังสือเชิญ)</span>
          <input type="text" id="name4" value="<?php echo $row['approval_in_name']; ?>" name="approval_in_name" class="form-control" required readonly>

        </div>
        <div class="row">


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
              <input class="form-check-input" type="checkbox" value="H" id="flexCheckChecked" name="approval_self" <?php if ($row['approval_self'] == "H") {
                                                                                                                    ?> checked="1" <?php
                                                                                                                                  } ?>>
              <label class="form-check-label" for="flexCheckDefault">
                โรงพยาบาลเป็นผู้ส่งเข้ารับการอบรม
              </label>
            </div>
            <div class="form-check">
              <input id='testNameHidden' type='hidden' value='N' name='approval_invite' readonly>
              <input class="form-check-input" type="checkbox" value="B" id="flexCheckChecked" name="approval_self" <?php if ($row['approval_self'] == "B") {
                                                                                                                    ?> checked="1" <?php
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

        <br>
        <div class="row text-center">
          <br>

          <div class="col sm-1">

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

        <div class="modal-footer">

          <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
          <!-- <a type="button" href="?<?php echo $row["user_id"]; ?>" name="edit" class="btn btn-danger  btn-sm">ตกลง</a> -->
          <a type="submit" name="adduserline" class="btn btn-primary btn-sm" href="?appid=<?php echo $row['approval_id']; ?>" value="ยืนยัน">ยืนยัน</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END ต้องการยืนยันการขออบรม  -->

<div class="modal fade" id="conpv<?php echo $row['appr_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">อนุมัติหนังสือส่งจังหวัด</h5>
        </button>
      </div>
      <div class="modal-body">
        <form enctype="multipart/form-data" method="post" action="apppv.php">


          <div class="row">
            <div class="col">
              <label class="form-label" for="g_name">เรื่อง</label>
              <input type="text" class="form-control" name="g_name" readonly placeholder="ขออนุมัติเดินทางไปราชการ">
            </div>

            <div class="col">
              <label class="form-label" for="name4">เรียน</label>
              <input type="text" class="form-control" name="text1" readonly placeholder="นายแพทย์สาธารณสุขจังหวัดสงขลา" />

            </div>

          </div>

          <br>
          <div class="row">
            <div class="col">
              <label class="form-label" for="appr_number">เลขที่</label>
              <span>(เลขที่หนังสือ)</span>
              <input type="text" value="<?php echo $row['appr_number']; ?>" placeholder required class="form-control" name="appr_number">
            </div>

            <?php
            $date = date("Y-m-d");

            ?>


            <div class="col">
              <label class="form-label" for="appr_cdate">ลงวันที่</label>
              <input type="date" value="<?php echo $date; ?>" class="form-control" name="appr_cdate" readonly placeholder="<?php echo $date; ?>">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col">
              <label class="form-label" for="approval_name">ความหนังสือ</label>
              <span>(เลขหนังสือเชิญ)</span>
              <input type="text" id="name4" value="<?php echo $row['approval_number']; ?>" name="approval_number" class="form-control" readonly placeholder>
            </div>
            <br>
            <div class="col">
              <label for="group_job" class="form-label">ประเภท</label>
              <div class="col-sm-12">
                <input type="text" id="name4" value="<?php echo $row['approval_type']; ?>" name="approval_type" class="form-control" readonly placeholder>

              </div>
            </div>
          </div>
          <br>
          <div class="form-outline mb-4">
            <label class="form-label" for="approval_organ">เรื่อง</label>
            <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" name="approval_name" class="form-control" required readonly placeholder>
          </div>
          <div class="form-outline mb-4">
            <label class="form-label" for="approval_organ">จัดโดย</label>
            <input type="text" id="name4" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" class="form-control" required readonly placeholder>
          </div>

          <div class="form-outline mb-4">
            <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
            <span>(ไม่ต้องใส่ ณ )</span>
            <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" required readonly placeholder>

          </div>
          <!-- <div class="row mt-2">
                <div class="col">
                    <label for="firstname" class="form-label" for="approval_fdate">ระหว่างวันที่</label>
                    <span>(วันที่ดำเนินการ)</span>
                    <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required readonly placeholder>
                </div>
                <div class="col">
                    <?php
                    $date = date("Y-m-d");

                    ?>
                    <label class="form-label" for="approval_edate">ถึงวันที่</label>
                    <span>(วันที่ดำเนินการ)</span>
                    <input class="form-control" id="date" value="<?php echo $row['approval_edate']; ?>" name="approval_edate" placeholder="MM/DD/YYY" type="date" required readonly placeholder>
                </div>
                <div class="col">
                    <label class="form-label" for="approval_sum">ค่าลงทะเบียน</label>
                    <span>(บาท)</span>
                    <input type="text" class="form-control" value="<?php echo $row['approval_sum']; ?>" name="approval_sum" readonly placeholder>
                </div>
                <div class="col">
                    <label class="form-label" for="approval_numof">ครั้งที่</label>
                    <span>(ครั้งที่เข้าร่วม)</span>
                    <input type="text" class="form-control" value="<?php echo $row['approval_numof']; ?>" name="approval_numof" readonly placeholder />
                </div>
            </div> -->
          <hr>

          <div class="modal-footer">

            <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
            <input type="hidden" name="appr_id" value="<?= $row['appr_id']; ?>">
            <input type="submit" name="uppvdate" class="btn btn-primary" value="บันทึกก">
            <!-- <input type="submit" name="conpvv" class="btn btn-primary btn-sm" value="บันทึก5"> -->


          </div>
        </form>
      </div>

    </div>
  </div>
</div>
<!-- END อนุมัติหนังสือส่งจังหวัด  -->

<div class="modal fade" id="edbr_<?php echo $row['approval_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">แก้ไขข้อมูลยืมเงิน ?</h5>
        </button>
      </div>
      <div class="modal-body">

        <br>
        <form action="list_borrow.php" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col">
              <?php
              $date = date("Y-m-d");

              ?>
              <label class="form-label" for="name4">เรียน</label>
              <input type="text" class="form-control" name="text1" readonly placeholder="ผู้อำนวยการโรงพยาบาลเทพา" />

            </div>
            <div class="col">
              <label class="form-label" for="approval_number">ความหนังสือ</label>
              <span>(เลขหนังสือเชิญ)</span>
              <input type="text" value="<?php echo $row['approval_number']; ?>" required readonly class="form-control" name="approval_number">
            </div>
            <div class="col">
              <label class="form-label" for="borrow_date">ลงวันที่</label>
              <input type="date" value="<?php echo $date; ?>" class="form-control" name="borrow_date" readonly placeholder="<?php echo $date; ?>">
            </div>
          </div>

          <div class="row">
            <div class="col">
              <br>
              <label class="form-label" for="borrow_name">ตามที่</label>
              <span>(หน่วยงาน)</span>
              <input type="text" id="name4" name="borrow_name" value="<?php echo $row['approval_organ']; ?>" class="form-control" readonly placeholder="">
              <br>
              <div class="row">
                <div class="col">
                  <label class="form-label" for="borrow_name">เรื่อง</label>
                  <input type="text" id="name4" name="borrow_name" value="<?php echo $row['approval_name']; ?>" class="form-control" readonly placeholder="">

                  <br>
                  <div class="row ">
                    <div class="col">
                      <label for="firstname" class="form-label" for="approval_fdate">ระหว่างวันที่</label>
                      <span>(วันที่ดำเนินการ)</span>
                      <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" readonly placeholder="MM/DD/YYY" type="date" required>
                    </div>
                    <div class="col">
                      <?php
                      $date = date("Y-m-d");

                      ?>
                      <label class="form-label" for="approval_edate">ถึงวันที่</label>
                      <span>(วันที่ดำเนินการ)</span>
                      <input class="form-control" id="date" value="<?php echo $row['approval_edate']; ?>" name="approval_edate" readonly placeholder="MM/DD/YYY" type="date" required>
                      <br>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                      <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" readonly required>
                    </div>
                    <div class="col">
                      <label class="form-label" for="approval_addp">จำนวนเงินที่ยืม</label>
                      <input type="text" id="name4" value="<?php echo $row['borrow_sum']; ?> บาท" name="borrow_sum" class="form-control" readonly required>


                    </div>


                    <br>
                    <div class="row">

                      <div class="col">
                        <br>
                        <input id='borrow_allwHidden' type='hidden' value='0' name='borrow_allw'>
                        <label class="form-label" for="borrow_allw">ค่าเบี้ยเลี้ยง</label>

                        <input type="text" required class="form-control" value="<?php echo $row['borrow_allw']; ?>" name="borrow_allw">
                      </div>

                      <div class="col">
                        <br>
                        <input id='borrow_accomHidden' type='hidden' value='0' name='borrow_accom'>
                        <label class="form-label" for="borrow_accom">ค่าเช่าที่พัก</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_accom']; ?>" name="borrow_accom" />
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_vehHidden' type='hidden' value='0' name='borrow_veh'>
                        <label class="form-label" for="borrow_veh">ค่าพาหนะ</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_veh']; ?>" name="borrow_veh" />
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_regis' type='hidden' value='0' name='borrow_regis'>
                        <label class="form-label" for="borrow_regis">ค่าลงทะเบียน</label>

                        <!-- <input type="text" class="form-control" name="borrow_regis" /> -->
                        <input type="text" value="<?php echo $row['approval_sum']; ?>" required readonly class="form-control" name="borrow_regis">
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_rewardHidden' type='hidden' value='0' name='borrow_reward'>
                        <label class="form-label" for="borrow_reward">ค่าสมนาคุณ</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_reward']; ?>" name="borrow_reward" />
                      </div>
                      <div class="col">
                        <br>
                        <input id="borrow_anotherHidden" type="hidden" value="0" name="borrow_another">

                        <label class="form-label" for="borrow_another">อื่นๆ</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_another']; ?>" name="borrow_another" />
                      </div>

                    </div>

                  </div>
                  <div class="form-outline mb-4">
                    <input id='borrowcomHidden' type='hidden' value='-' name='borrow_com'>
                    <label class="form-label" for="borrow_com">หมายเหตุ</label>
                    <textarea id="textarea3" rows="2" name="borrow_com" class="form-control"><?php echo $row['borrow_com']; ?></textarea>
                  </div>

                  <div class="modal-footer">

                    <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
                    <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
                    <input type="submit" name="upbo" class="btn btn-primary btn-sm" value="ยืนยัน">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div> 
<!-- END แก้ไขข้อมูลยืมเงิน  -->

<div class="modal fade" id="scanbr<?php echo $row['approval_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel"> เพิ่มไฟล์ Scan การยืมเงิน</h5>
        </button>
      </div>
      <div class="modal-body">
        <form action="borrow.php" method="post" enctype="multipart/form-data">
          <div class="row">
            <div class="col">
              <label class="form-label" for="borrow_name">ตามที่</label>
              <span>(หน่วยงาน)</span>
              <input type="text" id="name4" name="borrow_name" value="<?php echo $row['approval_organ']; ?>" class="form-control" readonly placeholder="">
              <br>
              <div class="row">
                <div class="col">
                  <label class="form-label" for="borrow_name">เรื่อง</label>
                  <input type="text" id="name4" name="borrow_name" value="<?php echo $row['approval_name']; ?>" class="form-control" readonly placeholder="">

                  <br>
                  <div class="row">
                    <div class="col">
                      <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                      <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" readonly required>
                    </div>
                    <div class="col">
                      <label class="form-label" for="approval_addp">จำนวนเงินที่ยืม</label>
                      <input type="text" id="name4" value="<?php echo $row['borrow_sum']; ?> บาท" name="borrow_sum" class="form-control" readonly required>

                    </div>

                    <br>
                    <div class="row">

                      <div class="col">
                        <br>
                        <input id='borrow_allwHidden' type='hidden' value='0' name='borrow_allw'>
                        <label class="form-label" for="borrow_allw">ค่าเบี้ยเลี้ยง</label>

                        <input type="text" required class="form-control" value="<?php echo $row['borrow_allw']; ?>" name="borrow_allw" required readonly>
                      </div>

                      <div class="col">
                        <br>
                        <input id='borrow_accomHidden' type='hidden' value='0' name='borrow_accom'>
                        <label class="form-label" for="borrow_accom">ค่าเช่าที่พัก</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_accom']; ?>" name="borrow_accom" required readonly/>
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_vehHidden' type='hidden' value='0' name='borrow_veh'>
                        <label class="form-label" for="borrow_veh">ค่าพาหนะ</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_veh']; ?>" name="borrow_veh" required readonly />
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_regis' type='hidden' value='0' name='borrow_regis'>
                        <label class="form-label" for="borrow_regis">ค่าลงทะเบียน</label>

                        <!-- <input type="text" class="form-control" name="borrow_regis" /> -->
                        <input type="text" value="<?php echo $row['approval_sum']; ?>" required readonly class="form-control" name="borrow_regis">
                      </div>
                      <div class="col">
                        <br>
                        <input id='borrow_rewardHidden' type='hidden' value='0' name='borrow_reward'>
                        <label class="form-label" for="borrow_reward">ค่าสมนาคุณ</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_reward']; ?>" name="borrow_reward" required readonly/>
                      </div>
                      <div class="col">
                        <br>
                        <input id="borrow_anotherHidden" type="hidden" value="0" name="borrow_another">

                        <label class="form-label" for="borrow_another">อื่นๆ</label>

                        <input type="text" class="form-control" value="<?php echo $row['borrow_another']; ?>" name="borrow_another" required readonly/>
                      </div>

                    </div>

                  </div>
                  <div class="form-outline mb-4">
                    <input id='borrowcomHidden' type='hidden' value='-' name='borrow_com'>
                    <label class="form-label" for="borrow_com">หมายเหตุ</label>
                    <textarea id="textarea3" rows="2" name="borrow_com" required readonly class="form-control"><?php echo $row['borrow_com']; ?></textarea>
                  </div>

                  <div class = "row">
                    <div class="col">
                    <label class="form-label" for="approval_addp">เลขที่สัญญา</label>
                      <input type="text" id="name4" value="<?php echo $row['borrow_number']; ?> " name="borrow_sum" class="form-control" readonly required>
                    </div>
                    <div class="col">
                    <label class="form-label" for="approval_addp">วันที่รับเงิน</label>
                      <input type="text" id="name4" value="<?php echo $row['borrow_accmoney']; ?> " name="borrow_sum" class="form-control" readonly required>
                    </div>
                    <div class="col">
                    <label class="form-label" for="approval_addp">วันที่ครบกำหนด</label>
                      <input type="text" id="name4" value="<?php echo $row['borrow_edate']; ?> " name="borrow_sum" class="form-control" readonly required>
                    </div>

                  </div>
                  <br>
                <label class =  "form-label">อัพโหลดไฟล์หนังสือสัญญาการยืมเงิน</label><br>
                         <font class="form-text" color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น </font>
                        <input type="file" name="scanbr" required   class="form-control" accept="application/pdf"> 



                        <div class="row text-center mt-5">
                          
                    <div class="col">
                     
                    <label>หนังสือสัญญายืมเงิน</label>
                    <a href="../docs/bor_doc/<?php echo $row['borrow_doc']; ?>" target="_blank" class="btn btn-info btn-sm"> เปิดดู </a>
                    <br>
                    
                    <iframe src="../docs/bor_doc/<?php echo $row['borrow_doc']; ?>"  height="300px" width = "500px" ></iframe>
                    </div>
                      </div>
                    
           

      


                  <div class="modal-footer">

                    <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
                    <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
                    <input type="submit" name="upscbor" class="btn btn-primary btn-sm" value="ยืนยัน">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div> 
<!-- END อัปโหลด ไฟล์ pdf ยืมเงิน  -->

<div class="modal fade" id="refund_<?php echo $row['borrow_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">คืนเงิน</h5>
        </button>
      </div>
      <div class="modal-body">
        
        
        <form enctype="multipart/form-data" method="post" action="refund.php">
          <div class="row">
            

            <?php
            $date = date("Y-m-d");

            ?>


            
          </div>
          <div class="row mt-2">
          <div class="col">
              <label class="form-label" for="appr_cdate">ลงวันที่</label>
              <input type="date" value="<?php echo $date; ?>" class="form-control" name="redate" readonly placeholder="<?php echo $date; ?>">
            </div>
            <br>
            <div class="col">
              <label for="group_job" class="form-label">ประเภท</label>
              <div class="col-sm-12">
                <input type="text" id="name4" value="<?php echo $row['approval_type']; ?>" name="approval_type" class="form-control" readonly placeholder>

              </div>
            </div>
          </div>
          <br>
          <div class="form-outline mb-4">
            <label class="form-label" for="approval_organ">เรื่อง</label>
            <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" name="approval_name" class="form-control" required readonly placeholder>
          </div>
          <div class="form-outline mb-4">
            <label class="form-label" for="approval_organ">จัดโดย</label>
            <input type="text" id="name4" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" class="form-control" required readonly placeholder>
          </div>


          <?php 
           $brrow_id = $row['borrow_id'];
          $maxrefund = $db->prepare("SELECT * FROM refund where borrow_id = :brrow_id ORDER by ref_id desc limit 1"); 
          $maxrefund->bindParam(':brrow_id',$brrow_id);
          $maxrefund->execute();
          $maxrow = $maxrefund->fetch(PDO::FETCH_ASSOC);
          ?>



          <div class="row mt-2">
                <div class="col">
                    <label for="firstname" class="form-label" for="approval_fdate">ยอดที่จ่าย</label>
                   
                    <input class="form-control" id="qty"  name="qty"  placeholder required>
            
                </div>
                <div class="col">
                    <label class="form-label" for="approval_edate">คงเหลือ</label>
                    
                    <input class="form-control" id="remaining" value="<?php echo $maxrow['remaining']; ?>" name="remaining" readonly placeholder>
                </div>
                <div class="col">
                    <label class="form-label" for="approval_sum">ทั้งหมด</label>
                    <span>(บาท)</span>
                    <input type="text" class="form-control" value="<?php echo $row['approval_sum']; ?>" name="approval_sum" readonly placeholder>
                </div>
                
            </div>
          <!-- <hr>
                <b>คืนเงินล่าสุด</b>

        
                
                <div class="row mt-2">
                  
                <div class="col">
                    <label class="form-label" for="approval_sum">วันที่จ่าย</label>
                    <span>(บาท)</span>
                    <input type="text" class="form-control" value="<?php echo $row['redate']; ?>" name="redate1" readonly placeholder>
                </div>
                <div class="col">
                    <label for="firstname" class="form-label" for="approval_fdate">ยอดที่จ่าย</label>
                   
                    <input class="form-control" id="qty1" value="<?php echo $row['quantity']; ?>" name="qty1"  placeholder >
            
                </div>
                <div class="col">
                    <label class="form-label" for="approval_edate">คงเหลือ</label>
                    
                    <input class="form-control" id="remaining" value="<?php echo $row['remaining']; ?>" name="remaining" readonly placeholder>
                </div>
                
                
            </div>
                -->
               

      </div>
      <div class="modal-footer">

        <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
        <input type="hidden" name="borrow_id" value="<?= $row['borrow_id']; ?>">
        <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
        <input type="submit" name="e_refund" class="btn btn-warning btn-sm" value="บันทึก">
<!-- <input type="submit" name="conpvv" class="btn btn-primary btn-sm" value="บันทึก5"> -->

</form>

      </div>

      
    </div>
  </div>
</div>
<!-- END คืนเงิน  -->


<div class="modal fade" id="Maxrefund_<?php echo $row['borrow_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">คืนเงิน</h5>
        </button>
      </div>
      <div class="modal-body">
        
        
        <form enctype="multipart/form-data" method="post" action="refund.php">

       <div class="row ">


         <div class="col">
           <label for="firstname" class="form-label" for="approval_fdate">  วันที่จ่าย</label>
          </div>
          <div class="col">
            <label class="form-label" for="approval_edate">  ยอดที่จ่าย</label>   
          </div>
          <div class="col">
           
            <label class="form-label" for="approval_edate"> คงเหลือ</label>   
          </div>
          <div class="col">
            <label class="form-label" for="approval_sum">  ทั้งหมด</label>
            <span>(บาท)</span>
          </div>
       </div>
       


          <?php 
           $brrow_id = $row['borrow_id'];
          $maxrefund = $db->prepare("SELECT * FROM refund where borrow_id = :brrow_id ORDER by ref_id desc"); 
          $maxrefund->bindParam(':brrow_id',$brrow_id);
          $maxrefund->execute();
          while($maxrow = $maxrefund->fetch(PDO::FETCH_ASSOC)) {?>

              <div class="row mt-2">
                <div class="col">
                   
                    <input class="form-control" id="redate" value="<?php echo $maxrow['redate']; ?>" name="redate" readonly placeholder>
                
                </div>
                <div class="col">
                
                <input class="form-control" id="quantity" value="<?php echo $maxrow['quantity']; ?>" name="quantity" readonly placeholder>
                </div>
                <div class="col">
                     
                    <input class="form-control" id="remaining" value="<?php if ($maxrow['remaining'] == 0.00) {echo '*** '.$maxrow['remaining'].' คืนครบ';} else echo $maxrow['remaining']; ?>" name="remaining" readonly placeholder>
                </div>
                <div class="col">
                    
                    <input type="text" class="form-control" value="<?php echo $maxrow['borrow_sum']; ?>" name="approval_sum" readonly placeholder>
                </div>
                
            </div>



         <?php }
          
          ?>

         
        
               

      </div>
      <div class="modal-footer">

        <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิด</a>
        <input type="hidden" name="borrow_id" value="<?= $row['borrow_id']; ?>">
        <input type="hidden" name="approval_id" value="<?= $row['approval_id']; ?>">
        <!-- <input type="submit" name="e_refund" class="btn btn-warning btn-sm" value="บันทึก"> -->
<!-- <input type="submit" name="conpvv" class="btn btn-primary btn-sm" value="บันทึก5"> -->

</form>

      </div>

      
    </div>
  </div>
</div>
<!-- END เช็คเงิน -->



<div class="modal fade" id="scanpv<?php echo $row['appr_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">อัพโหลดไฟล์หนังสือขอไปราชการ</h5>
        </button>
      </div>
      <div class="modal-body">

        <form action="apppv.php" method="post" enctype="multipart/form-data">
        
        <div class="row">
                <div class="col">
                    <label class="form-label" for="g_name">เรื่อง</label>
                    <input type="text" class="form-control"  name="g_name" readonly placeholder="ขออนุมัติเดินทางไปราชการ">
                </div>

                <!-- <div class="col">
                    <label class="form-label" for="name4">เรียน</label>
                    <input type="text" class="form-control" name="text1" readonly placeholder="นายแพทย์สาธารณสุขจังหวัดสงขลา" />
                    
                </div> -->
               
           
                <div class="col">
                    <label class="form-label" for="appr_number">เลขที่</label>
                    <span>(เลขที่หนังสือ)</span>
                    <input type="text"  value="<?php echo $row['appr_number']; ?>" placeholder readonly class="form-control"  name="appr_number" >
                </div>

                    <?php
                    $date = date("Y-m-d");

                    ?>

                <div class="col">
                    <label class="form-label" for="approval_name">ความหนังสือ</label>
                    <span>(เลขหนังสือเชิญ)</span>
                    <input type="text" id="name4" value="<?php echo $row['approval_number']; ?>" name="approval_number" class="form-control" readonly placeholder>
                </div>
            </div>
           
            
            <div class="row">
                <div class="col">
                    <label class="form-label" for="approval_organ">เรื่อง</label>
                    <input type="text" id="name4" value="<?php echo $row['approval_name']; ?>" name="approval_name" class="form-control" required readonly placeholder>
                </div>
                <div class="col">
                    <label class="form-label" for="approval_organ">จัดโดย</label>
                    <input type="text" id="name4" value="<?php echo $row['approval_organ']; ?>" name="approval_organ" class="form-control" required readonly placeholder>
                </div>
            </div>
            <div class="row mt-2">

              <br>
              <div class="col">
                  <label for="group_job" class="form-label" >ประเภท</label>
                  <div class="col-sm-12" >
                  <input type="text" id="name4" value="<?php echo $row['approval_type']; ?>" name="approval_type" class="form-control" readonly placeholder>
                  </div>
              </div>
              <div class="col">
                  <label class="form-label" for="approval_numof">ครั้งที่</label>
                  <span>(ครั้งที่เข้าร่วม)</span>
                  <input type="text" class="form-control" value="<?php echo $row['approval_numof']; ?>" name="approval_numof" readonly placeholder />
              </div>
              <div class="col">
                    <label class="form-label" for="approval_addp">สถานที่ดำเนินการ</label>
                    <input type="text" id="name4" value="<?php echo $row['approval_addp']; ?>" name="approval_addp" class="form-control" required readonly placeholder>
    
                </div>
          </div>
            <div class="row mt-2">
                <div class="col">
                    <label for="firstname" class="form-label" for="approval_fdate">ระหว่างวันที่</label>
                    <span>(วันที่ดำเนินการ)</span>
                    <input class="form-control" id="date" value="<?php echo $row['approval_fdate']; ?>" name="approval_fdate" placeholder="MM/DD/YYY" type="date" required readonly placeholder>
                </div>
                <div class="col">
                    <?php
                    $date = date("Y-m-d");

                    ?>
                    <label class="form-label" for="approval_edate">ถึงวันที่</label>
                    <span>(วันที่ดำเนินการ)</span>
                    <input class="form-control" id="date" value="<?php echo $row['approval_edate']; ?>" name="approval_edate" placeholder="MM/DD/YYY" type="date" required readonly placeholder>
                </div>
            
                <div class="col">
                    <label class="form-label" for="approval_sum">ค่าลงทะเบียน</label>
                    <span>(บาท)</span>
                    <input type="text" class="form-control" value="<?php echo $row['approval_sum']; ?>" name="approval_sum" readonly placeholder>
                </div>
                
            </div>
            <br>
                <label class =  "form-label">อัพโหลดไฟล์หนังสือยืนยันจากจังหวัด</label><br>
                         <font class="form-text" color="red">*อัพโหลดได้เฉพาะ .pdf เท่านั้น </font>
                        <input type="file" name="scanpv" required   class="form-control" accept="application/pdf"> 

                  <div class="modal-footer">

                    <a type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ยกเลิก</a>
                  
                    <input type="hidden" name="scanpv_id" value="<?= $row['appr_id'];?>">
                    <input type="submit" name="upscanpv" class="btn btn-primary btn-sm" value="บันทึก">
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- END -->



