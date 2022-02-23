<?php

//student.php

include('header.php');

?>
<head>
<title></title>
<style>
.select2-dropdown {
  top: 22px !important; left: 8px !important;


}
 
    table.dataTable tbody tr.myeven {
      background-color: #ffffff;
 }
 table.dataTable tbody tr.myeven:hover {
      background-color: skyblue;
 }
table.dataTable tbody tr.myodd {
      background-color: #D9E1F2;
 }
 table.dataTable tbody tr.myodd:hover {
      background-color: skyblue;
 }

select2-dropdown {
  top: 22px !important; left: 8px !important;
}
.card-header{
  background-color: darkorange;
  color: white;
  font-size: 23px;
}


  </style>
</head>
</style>
<link rel="stylesheet" href="select2.min.css" />
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<div class="container" style="margin-top:30px">
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-md-9 text-center text-white">HOD List</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-primary">Add New HOD</button>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
          <span id="message_operation"></span>
          <table class="table table-striped table-bordered" id="hod_table">
          <thead>
            <tr>
            
              <th>#Serial Number</th>
              <th>Username.</th>
               <th>HOD School</th>
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
<link rel="stylesheet" href="select2.min.css" />
<style>
.select2-dropdown {top: 22px !important; left: 8px !important;}

    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
</style>

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="hod_form" autocomplete="off">
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
              <label class="col-md-4 text-right">Username <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="admin_user_name" id="admin_user_name" class="form-control" placeholder="olooRichard" />
                <span id="error_admin_user_name" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">Password. <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="admin_password" id="admin_password" class="form-control" />
                <span id="error_admin_password" class="text-danger"></span>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">School<span class="text-danger">*</span></label>
              <div class="col-md-8">
                 <select name="hod_school" id="hod_school" class="form-control" required>
                  <option class="" value="">Select school</option>
                  <?php
                  echo load_school_list($connect);
                  ?>
              </select>
              <span id="error_hod_school" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <input type="hidden" name="admin_id" id="admin_id" />
          <input type="hidden" name="action" id="action" value="Add" />
          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>

      </div>
  </form>
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
        <h3 align="center">Are you sure you want to delete...?</h3>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger btn-sm">OK</button>
        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<script src="select2.min.js"></script>
<script>
$(document).ready(function(){
  
  var dataTable = $('#hod_table').DataTable({
    "processing":true,
    "serverSide":true,
    "pageLength":10,
    "scrollY": 200,
    "ordering":false,
    "ajax":{
      url:"hod_action.php",
      method:"POST",
      data:{action:'fetch'},
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

  function clear_field()
  {
    $('#hod_form')[0].reset();
    $('#error_admin_user_name').text('');
    $('#error_admin_password').text('');
    
    $('#error_hod_school').text('');
  }

  $('#add_button').click(function(){
    $('#modal_title').text('Add HOD');
    $('#button_action').val('Add');
    $('#action').val('Add');
    $('#formModal').modal('show');
    clear_field();
  });

  $('#hod_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"hod_action.php",
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
          if(data.error_admin_user_name != '')
          {
            $('#error_admin_user_name').text(data.error_admin_user_name);
          }
          else
          {
            $('#error_admin_user_name').text('');
          }
          if(data.error_admin_password != '')
          {
            $('#error_admin_password').text(data.error_admin_password);
          }
          else
          {
            $('#error_admin_password').text('');
          }
          if(data.error_hod_school != '')
          {
            $('#error_hod_school').text(data.error_hod_school);
          }
          else
          {
            $('#error_hod_school').text('');
          }
        }
      }
    })
  });

  

  var admin_id = '';

  $(document).on('click', '.edit_hod', function(){
    admin_id = $(this).attr('id');
    clear_field();
    $.ajax({
      url:"hod_action.php",
      method:"POST",
      data:{action:'edit_fetch', admin_id:admin_id},
      dataType:"json",
      success:function(data)
      {
        $('#admin_user_name').val(data.admin_user_name);
        $('#admin_password').val(data.admin_password);
        
        $('#hod_school').val(data.hod_school);
        $('#admin_id').val(data.admin_id);
        $('#modal_title').text('Edit HOD');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');
      }
    })
  });

  $(document).on('click', '.delete_hod', function(){
    admin_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

  $('#ok_button').click(function(){
    $.ajax({
      url:"hod_action.php",
      method:"POST",
      data:{admin_id:admin_id, action:"delete"},
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