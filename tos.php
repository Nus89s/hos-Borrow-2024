<?php
	function sentMessage($encodeJson,$datas)
	{
		$datasReturn = [];
      	$curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $datas['url'],
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $encodeJson,
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ".$datas['token'],
            "cache-control: no-cache",
            "content-type: application/json; charset=UTF-8",
          ),
        ));

        $response = curl_exec($curl);
        // dd($response);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $datasReturn['result'] = 'E';
            $datasReturn['message'] = $err;
        } else {
            if($response == "{}"){
                $datasReturn['result'] = 'S';
                $datasReturn['message'] = 'Success';
            }else{
                $datasReturn['result'] = 'E';
                $datasReturn['message'] = $response;
            }
        }

        return $datasReturn;
	}




  ?>
  <!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Bootstrap Datepicker</title>
<!-- bootstrap css js -->
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link src="https://code.jquery.com/qunit/qunit-2.11.2.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css" >
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">


<!-- javascript code -->

<head>
<body>

<div class="container">
  <h2>Date Picker</h2>
  <form>
  
  <div class="form-group">
    <label>Start Date</label>
    <input type="date" name="s_date" id="s_date" class="form-control s_date" min="<?php echo date('Y-m-d'); ?>" >
  </div>

  <div class="form-group">
    <label>End Date</label>
    <input type="date" name="e_date" id="e_date" class="form-control e_date">
  </div>

  
  <button type="button" class="btn btn-primary save" id="save">Save</button>
</form>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    var minDate = new Date();
    $("#s_date").datepicker({
      showAnim : 'drop',
      numberOfMonth : 1,
      minDate : minDate,
      dateFormat : 'yy-mm-dd',
      onClose : function(selectedDate)
      {
       $('#e_date').datepicker("option","minDate",selectedDate);
     } 
   });
    $(".e_date").datepicker({
      showAnim : 'drop',
      numberOfMonth : 1,
      minDate : minDate,
      dateFormat : 'yy-mm-dd', 
    });
  });
</script>
</body>
</html>