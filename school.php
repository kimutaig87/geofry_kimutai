<?php

//grade.php

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
        <div class="col-md-9 text-center">Schools</div>
        <div class="col-md-3" align="right">
          <button type="button" id="add_button" class="btn btn-primary">Add New School</button>
        </div>
      </div>
    </div>
  	<div class="card-body">
  		<div class="table-responsive">
        <span id="message_operation"></span>
        <table class="table table-striped table-bordered" id="school_table">
          <thead>
            <tr>
              <th>School-Name</th>
              
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
              <label class="col-md-4 text-right">School-Name<span class="text-danger">*</span></label>
              <div class="col-md-8">
              <select name="school_name" id="school_name" class="form-control">
                  <option value=""></option>
                  <option value="INFOCOMS">INFOCOMS</option>
                  
                  <option value="SCIENCE">SCIENCE</option>
                  
                  <option value="EDUCATION">EDUCATION</option>
                  
                  <option value="SASS">SASS</option>
                  
                  <option value="SANRES">SANRES</option>
                  
                  <option value="BUSINESS & HR DEV">BUSINESS & HR DEV</option>
                   <option value="OTHER">OTHER</option>
                     <option value="MEDICINE">MEDICINE</option>
                  
              </select>
              </select>
              <span id="error_school_name" class="text-danger"></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <input type="hidden" name="school_id" id="school_id" />
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
	
  var dataTable = $('#school_table').DataTable({
    "processing":true,
    "serverSide":true,
    "order":[],
    "ajax":{
      url:"school_action.php",
      type:"POST",
      data:{action:'fetch'}
    },
    "columnDefs":[
      {
        "targets":[],
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
    $('#modal_title').text('Add School');
    $('#button_action').val('Add');
    $('#action').val('Add');
    $('#formModal').modal('show');
    clear_field();
  });

  function clear_field()
  {
    $('#grade_form')[0].reset();
    
    $('#error_school_name').text('');
  }

  $('#grade_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"school_action.php",
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
            if(data.error_school_name != '')
          {
            $('#error_school_name').text(data.error_school_name);
          }
          else
          {
            $('#error_school_name').text('');
          }
        }
      }
    })
  });

  var school_id = '';

  $(document).on('click', '.edit_school', function(){
    school_id = $(this).attr('id');
    clear_field();
    $.ajax({
      url:"school_action.php",
      method:"POST",
      data:{action:'edit_fetch', school_id:school_id},
      dataType:"json",
      success:function(data)
      {
        $('#school_name').val(data.grade_name);
        $('#school_id').val(data.school_id);
         
        $('#modal_title').text('Edit Class');
        $('#button_action').val('Edit');
        $('#action').val('Edit');
        $('#formModal').modal('show');
      }
    })
  });

  $(document).on('click', '.delete_school', function(){
    school_id = $(this).attr('id');
    $('#deleteModal').modal('show');
  });

  $('#ok_button').click(function(){
    $.ajax({
      url:"school_action.php",
      method:"POST",
      data:{school_id:school_id, action:'delete'},
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
