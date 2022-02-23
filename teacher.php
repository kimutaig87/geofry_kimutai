<?php

include('header.php');

?>
<head>
<title></title>
<style>
.select2-dropdown {top: 22px !important; left: 8px !important;}
 
.card-header{
  background-color: darkorange;
  color: white;
  font-size: 23px;
}
 table.dataTable tbody tr.myeven:hover {
      background-color: skyblue;
 }
 table.dataTable tbody tr.myodd:hover {
      background-color: skyblue;
 }
</style>
<link rel="stylesheet" href="select2.min.css" />
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>


<div class="container" style="margin-top:30px">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-9 text-center text-white" style="background-color:darkorange; padding:10px 0 10px 0; font-weight:bolder;">Lecturers List</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-primary">Add New Lecturer</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <span id="message_operation"></span>
        <table class="table table-striped table-bordered" id="teacher_table">
          <thead>
            <tr>
              
              <th>Lecturer_Name</th>
              <th>Email Address</th>
              <th>Default_Class</th>
              <th>More</th>
              <th>Edit</th>
              <th>Delete</th>
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

    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="teacher_form" enctype="multipart/form-data">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" id="modal_title"></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Lecturer Name <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="teacher_name" id="teacher_name" class="form-control" />
                <span id="error_teacher_name" class="text-danger"></span>
              </div>
            </div>
          </div>  
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Email Address <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="teacher_emailid" id="teacher_emailid" class="form-control" />
                <span id="error_teacher_emailid" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Password <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="password" name="teacher_password" id="teacher_password" class="form-control" />
                <span id="error_teacher_password" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Class <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="teacher_grade_id" id="teacher_grade_id" class="form-control">
                  <option value="">Assign Default Class </option>
                  <?php
                  echo load_grade_list($connect);
                  ?>
                </select>
                <br>
                <span id="error_teacher_grade_id" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          
          <input type="hidden" name="teacher_id" id="teacher_id" />
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
    </form>
  </div>
</div>

<div class="modal" id="viewModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Lecturer Info</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="teacher_details">

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="modal" id="deleteModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Delete Confirmation</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h3 align="center">Are you sure you want to remove this?</h3>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger ">OK</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<script src="select2.min.js"></script>
<script>
$(document).ready(function(){
  var dataTable = $('#teacher_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"teacher_action.php",
      type:"POST",
      data:{action:'fetch'}
    },
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
  $("#teacher_grade_id").select2( {
  placeholder: "Select Default Class",
  allowClear: true
  } );

 
  function clear_field()
  {
    $('#teacher_form')[0].reset();
    $('#error_teacher_name').text('');
   
    $('#error_teacher_emailid').text('');
    $('#error_teacher_password').text('');
    
    $('#error_teacher_grade_id').text('');
  }

  $('#add_button').click(function(){
    $('#modal_title').text("Add Lecturer");
    $('#button_action').val('Add');
    $('#action').val('Add');
    $('#formModal').modal('show');
    clear_field();
  });

  $('#teacher_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"teacher_action.php",
      method:"POST",
      data:new FormData(this),
      dataType:"json",
      contentType:false,
      processData:false,
      beforeSend:function()
      {        
        $('#button_action').val('Please wait...');
        $('#button_action').attr('disabled', 'disabled');
      },
      success:function(data){
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
          if(data.error_teacher_name != '')
          {
            $('#error_teacher_name').text(data.error_teacher_name);
          }
          else
          {
            $('#error_teacher_name').text('');
          }
          if(data.error_teacher_emailid != '')
          {
            $('#error_teacher_emailid').text(data.error_teacher_emailid);
          }
          else
          {
            $('#error_teacher_emailid').text('');
          }
          if(data.error_teacher_password != '')
          {
            $('#error_teacher_password').text(data.error_teacher_password);
          }
          else
          {
            $('#error_teacher_password').text('');
          }
          if(data.error_teacher_grade_id != '')
          {
            $('#error_teacher_grade_id').text(data.error_teacher_grade_id);
          }
          else
          {
            $('#error_teacher_grade_id').text('');
          }
        }
      }
    });
  });

  var teacher_id = '';

  $(document).on('click', '.view_teacher', function(){
    teacher_id = $(this).attr('id');
    $.ajax({
      url:"teacher_action.php",
      method:"POST",
      data:{action:'single_fetch', teacher_id:teacher_id},
      success:function(data)
      {
        $('#viewModal').modal('show');
        $('#teacher_details').html(data);
      }
    });
  });

  $(document).on('click', '.edit_teacher', function(){
    teacher_id = $(this).attr('id');
    clear_field();
    $.ajax({
      url:"teacher_action.php",
      method:"POST",
      data:{action:'edit_fetch', teacher_id:teacher_id},
      dataType:"json",
      success:function(data)
      {
        $('#teacher_name').val(data.teacher_name);
      
        $('#teacher_grade_id').val(data.teacher_grade_id);
        
        $('#teacher_id').val(data.teacher_id);
        $('#modal_title').text('Edit Lecturer');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');
      }
    });
  });

  $(document).on('click', '.delete_teacher', function(){
    teacher_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

  $('#ok_button').click(function(){
    $.ajax({
      url:"teacher_action.php",
      method:"POST",
      data:{teacher_id:teacher_id, action:'delete'},
      success:function(data)
      {
        $('#message_operation').html('<div class="alert alert-success">'+data+'</div>');
        $('#deleteModal').modal('hide');
        dataTable.ajax.reload();
      }
    })
  });

});
</script>