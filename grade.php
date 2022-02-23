<?php

//grade.php >> manage classes

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



.select2-dropdown {
  top: 22px !important; left: 8px !important;
}
.card-header{
  background-color: darkorange;
  color: white;
 
  font-size: 23px;
  font-family: sans-serif;
}

  </style>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<div class="container" style="margin-top:30px">
  <div class="card">
  	<div class="card-header">
      <div class="row">
        <div class="col-md-9 text-center">Class-List</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-primary">Add New Class</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
        <table class="table table-striped table-bordered" id="grade_table">
          <thead>
            <tr>
              <th>#Serial_Number</th>
              <th>Class-Name</th>
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

<div class="modal" id="formModal">
  <div class="modal-dialog">
    <form method="post" id="grade_form">
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
              <label class="col-md-4 text-right">Class-Name <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input type="text" name="grade_name" id="grade_name" class="form-control" placeholder="EDA 2.1" />
                <span id="error_grade_name" class="text-danger"></span>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <div class="row">
              <label class="col-md-4 text-right">School<span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="grade_school_id" id="grade_school_id" class="form-control" required>
                  <option class="" value="">Select school</option>
                  <?php
                  echo load_school_list($connect);
                  ?>
              </select>
              <span id="error_grade_school_id" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <input type="hidden" name="grade_id" id="grade_id" />
          <input type="hidden" name="action" id="action" value="Add" />

          <input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Add -- deleteModal as id below to allow HODS TO DELETE CLASSES -->
<div class="modal" id="">
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
        <button type="button" name="ok_button" id="ok_button" class="btn btn-danger btn-sm">OK</button>
        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>



<script>
$(document).ready(function(){
	
  var dataTable = $('#grade_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"grade_action.php",
      type:"POST",
      data:{action:'fetch'}
    },
    "columnDefs":[
      {
        "targets":[0, 1, 2],
        "orderable":false,
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

  $('#add_button').click(function(){
    $('#modal_title').text('Add Class');
    $('#button_action').val('Add');
    $('#action').val('Add');
    $('#formModal').modal('show');
    clear_field();
  });

  function clear_field()
  {
    $('#grade_form')[0].reset();
    $('#error_grade_name').text('');
    $('#error_grade_school_id').text('');
  }

  $('#grade_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"grade_action.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function()
      {
        $('#button_action').attr('disabled', 'disabled');
        $('#button_action').val('Please wait...');
      },
      success:function(data)
      {
        $('#button_action').attr('disabled', false);
        $('#button_action').val($('#action').val());
        if(data.success)
        {
          $('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
          clear_field();
          dataTable.ajax.reload();
          $('#formModal').modal('hide');
        }
        if(data.error)
        {
          if(data.error_grade_name != '')
          {
            $('#error_grade_name').text(data.error_grade_name);
          }
          else
          {
            $('#error_grade_name').text('');
          }
            if(data.error_grade_school_id != '')
          {
            $('#error_grade_school_id').text(data.error_grade_school_id);
          }
          else
          {
            $('#error_grade_school_id').text('');
          }
        }
      }
    })
  });

  var grade_id = '';

  $(document).on('click', '.edit_grade', function(){
    grade_id = $(this).attr('id');
    clear_field();
    $.ajax({
      url:"grade_action.php",
      method:"POST",
      data:{action:'edit_fetch', grade_id:grade_id},
      dataType:"json",
      success:function(data)
      {
        $('#grade_name').val(data.grade_name);
        $('#grade_id').val(data.grade_id);
         $('#grade_school_id').val(data.grade_school_id);
        $('#modal_title').text('Edit Class');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');
      }
    })
  });

  $(document).on('click', '.delete_grade', function(){
    grade_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

  $('#ok_button').click(function(){
    $.ajax({
      url:"grade_action.php",
      method:"POST",
      data:{grade_id:grade_id, action:'delete'},
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