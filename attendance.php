<?php

//attendance.php

include('header.php');

?>
<head>
  <style type="text/css">
       table.dataTable tbody tr.myeven {
      background-color: #ffffff;
 }
table.dataTable tbody tr.myodd {
      background-color: #D9E1F2;
 }
  table.dataTable tbody tr.myeven:hover {
      background-color: skyblue;
 }
 table.dataTable tbody tr.myodd:hover {
      background-color: skyblue;
 }
       .card-header{
  background-color: darkorange;
  color: white;
 
  font-size: 23px;
  font-family: sans-serif;
}

  </style>
  
</head>

<div class="container" style="margin-top:30px">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-9 text-center">Attendance-List</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-primary">Take Attendance</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <span id="message_operation"></span>
        <table class="table table-striped table-bordered" id="attendance_table">
          <thead>
            <tr>
              <th>Student Name</th>
              <th>RegNo</th>
              <th>Class</th>
              <th>Status</th>
              <th>Date</th>
              <th>Week</th>
              <th>course_code</th>
              <th>Topic</th>
              
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>

<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<?php

$query = "
SELECT * FROM tbl_grade,tbl_school WHERE grade_id = (SELECT teacher_grade_id FROM tbl_teacher 
    WHERE teacher_id = '".$_SESSION["teacher_id"]."')
    AND school_id = grade_school_id
";

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

?>

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="attendance_form">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <?php
          foreach($result as $row)
          {
          ?>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-left">Class <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <?php
                echo '<label>'.$row["grade_name"].'</label>';
                ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="row">
              
              <div class="col-md-8">
                <input type="hidden" value=" <?php
                echo $row["grade_id"];
                ?>" name="attendance_grade_id" class="form-control" readonly >
               
              </div>
            </div>

            <!-- School ID -->
          <div class="form-group">
            <div class="row">
              
              <div class="col-md-8">
                <input type="hidden" value=" <?php
                echo $row["school_id"];
                ?>" name="grade_school_id" class="form-control" readonly >
               
              </div>
            </div>
            
            <!-- School ID -->

          

          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-left">Date <span class="text-danger">*</span></label>
              <div class=" pull-left col-md-8">
                <input type="text" name="attendance_date" id="attendance_date" class="form-control" readonly />
                <span id="error_attendance_date" class="text-danger"></span>
              </div> 
              </div> 
              </div> 
                <!-- Week selector option -->
              <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-left">Week <span class="text-danger">*</span></label>
             <div class=" pull-left col-md-8">

              <select name="week" id="week" class="form-control" placeholder="Select Week" >
                <option value="select week" disabled>Select Week</option>
                <option value=""></option>
              <option value="Week 1" >Week 1</option>
              <option value="Week 2" >Week 2</option>
              <option value="Week 3" >Week 3</option>
              <option value="Week 4" >Week 4</option>
              <option value="Week 5" >Week 5</option>
              <option value="Week 6" >Week 6</option>
              <option value="Week 7" >Week 7</option>
              <option value="Week 8" >Week 8</option>
              <option value="Week 9" >Week 9</option>
              <option value="Week 10" >Week 10</option>
              <option value="Week 11" >Week 11</option>
              <option value="Week 12" >Week 12</option>
              <option value="Week 13" >Week 13</option>
              <option value="Week 14" >Week 14</option>
              <option value="Other" >Other</option>
             </select>
                <span id="error_week" class="text-danger"></span>
              </div> 
          </div>
          </div>
              <div class="row">
              <label class="col-md-4 text-left">Course_Code<span class="text-danger">*</span></label>
              <div class="pull-left col-md-8">
                <input type="text" name="course_code" id="course_code" class="form-control"
                 placeholder="eg MAT 110"  />
                 <span id="error_course_code" class="text-danger"></span>
                </div>
                <br><br>
                
                <label class="col-md-4 text-left">Topic_Covered <span class="text-danger">*</span></label>
                <div class="pull-left col-md-8">
                <textarea type="text" name="topic" class="form-control" id="topic"
                 placeholder="Introduction to computer... max = 40 char" maxlength="40"></textarea>
                 <span id="error_topic" class="text-danger"></span>

            </div>
            </div>
          
          </div>
          <br><br>
          <div class="form-group" id="student_details">
            <div class="table-responsive">
              <table class="table table-striped table-bordered" id="att_table">
                <thead>
                  <tr>
                    <th>RegNo.</th>
                    <th>Student Name</th>
                    <th>Present</th>
                    <th>Absent</th>
                  </tr>
                </thead>
                <?php
                $sub_query = "
                  SELECT * FROM tbl_student 
                  WHERE student_grade_id = '".$row["grade_id"]."'
                ";
                $statement = $connect->prepare($sub_query);
                $statement->execute();
                $student_result = $statement->fetchAll();
                foreach($student_result as $student)
                {
                ?>
                  <tr>
                    <td><?php echo $student["student_roll_number"]; ?></td>
                    <td>
                      <?php echo $student["student_name"]; ?>
                      <input type="hidden" name="student_id[]" value="<?php echo $student["student_id"]; ?>" />
                    </td>
                    <td>
                      <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" checked value="Present" />
                    </td>
                    <td>
                      <input type="radio" name="attendance_status<?php echo $student["student_id"]; ?>" value="Absent" />
                    </td>
                  </tr>
                <?php
                }
                ?>
              </table>
            </div>
          </div>
          <?php
          }
          ?>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
    </form>
  </div>
</div>



<script>
$(document).ready(function(){
  
  var dataTable = $('#attendance_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"attendance_action.php",
      method:"POST",
      data:{action:"fetch"}
    },
    "scrollY":400,
    "info":true,
    "pageLength":1500,
    "lengthChange":false,
    
    dom: 'Bfrtip',
        buttons: [
            'copy', '', 'excel', 'pdf', 'print'
        ],
        "rowCallback": function( row, data, index ) {
        if(index%2 == 0){
            $(row).removeClass('myodd myeven');
            $(row).addClass('myodd');
        }else{
            $(row).removeClass('myodd myeven');
             $(row).addClass('myeven');
        }
      },
  });

  $('#attendance_date').datepicker({
    format:'yyyy-mm-dd',
    autoclose:true,
    container: '#formModal modal-body'
  });

  function clear_field()
  {
    $('#attendance_form')[0].reset();
    $('#error_attendance_date').text('');
  }

  $('#add_button').click(function(){
    $('#modal_title').text("Add Attendance");
    $('#formModal').modal('show');
    clear_field();
  });

  $('#attendance_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"attendance_action.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function(){
        $('#button_action').val('Please wait...');
        $('#button_action').attr('disabled', 'disabled');
      },
      success:function(data)
      {
        $('#button_action').attr('disabled', false);
        $('#button_action').val($('#action').val());
        if(data.success)
        {
          $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
          clear_field();
          $('#formModal').modal('hide');
          dataTable.ajax.reload();
        }
        if(data.error)
        {
          if(data.error_attendance_date != '')
          {
            $('#error_attendance_date').text(data.error_attendance_date);
          }
          else
          {
            $('#error_attendance_date').text('');
          }
          if(data.error_week != '')
          {
            $('#error_week').text(data.error_week);
          }
          else
          {
            $('#error_week').text('');
          }
          if(data.error_course_code != '')
          {
            $('#error_course_code').text(data.error_course_code);
          }
          else
          {
            $('#error_course_code').text('');
          }
          if(data.error_topic != '')
          {
            $('#error_topic').text(data.error_topic);
          }
          else
          {
            $('#error_topic').text('');
          }
        }
      }
    })
  });


});
</script>

