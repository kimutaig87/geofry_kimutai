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
}


.select2-dropdown {
  top: 22px !important; left: 8px !important;
}

  </style>

  
<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">
<!-- font awesome -->
<!-- JS -->
<link rel="stylesheet" href="select2.min.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.print.min.js"></script>

</head>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9 text-center">Attendance List</div>
        
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <table class="table table-striped table-bordered" id="attendance_table">
          <thead>
            <tr>
              <th>Student-Name</th>
              <th>RegNo</th>
              <th>Class</th>
              <th>Status</th>
              <th>Date</th>
              <th>Week</th>
              <th>Course_Code</th>
              <th>Topic_Covered</th>
              <th>Lecturer</th>
              
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

<script type="text/javascript" src="../js/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="../css/datepicker.css" />

<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<div class="modal" id="reportModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      

      <!-- Modal body -->
     
<script>
$(document).ready(function(){
	
  var dataTable = $('#attendance_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"attendance_action.php",
      type:"POST",
      data:{action:'fetch'}
    },
    dom: 'Bfrtip',
        buttons: [
            'excel', 'pdf', 'print'
        ],
     "lengthChange": false,
    "scrollY": 400,
    "bautoWidth":false,
    "scrollX": true,
    "scrollCollapse":true,
    "pageLength":150000,
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

  $('.input-daterange').datepicker({
    todayBtn: "linked",
    format: "yyyy-mm-dd",
    autoclose: true,
    container: '#formModal modal-body'
  });

  $(document).on('click', '#report_button', function(){
    $('#reportModal').modal('show');
  });

  $('#create_report').click(function(){
    var grade_id = $('#grade_id').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var error = 0;

    if(grade_id == '')
    {
      $('#error_grade_id').text('Grade is Required');
      error++;
    }
    else
    {
      $('#error_grade_id').text('');
    }

    if(from_date == '')
    {
      $('#error_from_date').text('From Date is Required');
      error++;
    }
    else
    {
      $('#error_from_date').text('');
    }

    if(to_date == '')
    {
      $('#error_to_date').text("To Date is Required");
      error++;
    }
    else
    {
      $('#error_to_date').text('');
    }

    if(error == 0)
    {
      $('#from_date').val('');
      $('#to_date').val('');
      $('#formModal').modal('hide');
      window.open("report.php?action=attendance_report&grade_id="+grade_id+"&from_date="+from_date+"&to_date="+to_date);
    }

  });

  $('#chart_button').click(function(){
    $('#chart_grade_id').val('');
    $('#attendance_date').val('');
    $('#chartModal').modal('show');
  });

  $('#create_chart').click(function(){
    var grade_id = $('#chart_grade_id').val();
    var attendance_date = $('#attendance_date').val();
    var error = 0;
    if(grade_id == '')
    {
      $('#error_chart_grade_id').text('Grade is Required');
      error++;
    }
    else
    {
      $('#error_chart_grade_id').text('');
    }
    if(attendance_date == '')
    {
      $('#error_attendance_date').text('Date is Required');
      $error++;
    }
    else
    {
      $('#error_attendance_date').text('');
    }

    if(error == 0)
    {
      $('#attendance_date').val('');
      $('#chart_grade_id').val('');
      $('#chartModal').modal('show');
      window.open("chart1.php?action=attendance_report&grade_id="+grade_id+"&date="+attendance_date);
    }

  });

});
</script>