<?php

//index.php

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


.select2-dropdown {
  top: 22px !important; left: 8px !important;
}
.card-header{
  background-color: darkorange;
  color: white;
 
  font-size: 23px;
  font-family: sans-serif;
}
 table.dataTable tbody tr.myeven:hover {
      background-color: skyblue;
 }
 table.dataTable tbody tr.myodd:hover {
      background-color: skyblue;
 }


  </style>
</head>
<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9 text-center">Overall Student Attendance Status</div>
        <div class="col-md-3" align="right">
          
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <table class="table table-striped table-bordered" id="student_table">
          <thead>
            <tr>
              
              <th>Class Name</th>
              <th>Attendance Percentage</th>
              <th>Passed Target</th>
              
             
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


<style>
    .datepicker
    {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>


<script>
$(document).ready(function(){
	 
   var dataTable = $('#student_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "scrollY": 400,
    "info": true,
    "pagelength":1000,
    "ajax":{
      url:"classPercentage_action.php",
      type:"POST",
      data:{action:'index_fetch'}
    },
     dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ],
          "columnDefs":[
      {
        "targets":[],
        "orderable":true,
      },
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

   

   });


</script>