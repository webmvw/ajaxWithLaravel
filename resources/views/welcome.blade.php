<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jquery data table js -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

    <!-- jquery datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">

    <title>Hello, world!</title>
  </head>
  <body style="background: #f4f4f4;">

    <br>
    <br>

    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 id="addTitle" class="card-title">Add Information</h3>
              <h3 id="updateTitle" class="card-title">Edit Information</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" name="name" class="form-control form-control-sm" id="name" aria-describedby="emailHelp">
                  <span class="text-danger" id="nameError"></span>
                </div>
                <div class="mb-3">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control form-control-sm" id="address">
                  <span class="text-danger" id="addressError"></span>
                </div>
                <input type="hidden" id="id">
                <button type="submit" id="addInfoBtn" onclick="addData()" class="btn btn-sm btn-primary">Add Info</button>
                <button type="submit" id="updateInfoBtn" onclick="updateData()" class="btn btn-sm btn-primary">Update Info</button>
            </div>
            <div class="card-footer"></div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Information list</h3>
            </div>
            <div class="card-body">
              <table id="myTable" class="table table-sm table-striped table-responsive table-hober table-bordered">
                <thead>
                  <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                  <tr>
                    <th>SL</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="card-footer"></div>
          </div>
        </div>
      </div>
    </div>


    <script type="text/javascript">
      
      $('#addTitle').show();
      $('#updateTitle').hide();
      $('#addInfoBtn').show();
      $('#updateInfoBtn').hide();

      // ajax setup
      $.ajaxSetup({
        headers:{
          'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
      });


      // =========== get all data from database ==============
      function allData(){
        $.ajax({
          type:"GET",
          dataType: 'json',
          url: "/allData",
          success: function(response){
            var data = ""
            $.each(response, function(key,value){
              data = data + "<tr>"
              data = data + "<td>"+(key+1)+"</td>"
              data = data + "<td>"+value.name+"</td>"
              data = data + "<td>"+value.address+"</td>"
              data = data + "<td>"
              data = data + "<button onclick='editData("+value.id+")' class='btn btn-sm btn-success'>Edit</button>"
              data = data + "<button onclick='deleteData("+value.id+")' class='btn btn-sm btn-danger'>Delete</button>"
              data = data + "</td>"
              data = data + "</tr>"
            })
            $('tbody').html(data)
          }
        })
      }
      allData();
      // ============= get all data form database ==================



      // =========== clear data ===============
      function clearData(){
        $('#name').val('');
        $('#address').val('');
        $('#nameError').text('');
        $('#addressError').text('');
      }
      // ===========clear data ===============


      // ============ insert data into database ===================
      function addData(){
        var name = $('#name').val();
        var address = $('#address').val();
        
        $.ajax({
          type: "POST",
          dataType: "json",
          data: {name:name, address:address},
          url: "/store",
          success: function(response){
            allData();
            clearData();
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: 'Data Added Success',
              showConfirmButton: false,
              timer: 1500
            });
          },
          error: function(error){
            $('#nameError').text(error.responseJSON.errors.name);
            $('#addressError').text(error.responseJSON.errors.address);
          }  
        })
      }
      // =============== insert data from database ==================



      // =============== edit data =================
      function editData(id){
        $.ajax({
          type: "GET",
          dataType: 'json',
          url: "/edit/"+id,
          success:function(response){
            $('#name').val(response.name);
            $('#address').val(response.address);
            $('#id').val(response.id);
            $('#addTitle').hide();
            $('#updateTitle').show();
            $('#addInfoBtn').hide();
            $('#updateInfoBtn').show();
          }
        })
      }
      // =============== edit data ====================


       // ============= update data =========================
      function updateData(){
        var name = $('#name').val();
        var address = $('#address').val();
        var id = $('#id').val();
        $.ajax({
          type: "POST",
          dataType: "json",
          data: {name:name, address:address},
          url: "/update/"+id,
          success: function(response){
            $('#addTitle').show();
            $('#updateTitle').hide();
            $('#addInfoBtn').show();
            $('#updateInfoBtn').hide();
            allData();
            clearData();
            Swal.fire({
              toast: true,
              position: 'top-end',
              icon: 'success',
              title: 'Data Updated Success',
              showConfirmButton: false,
              timer: 1500
            });
          },
          error: function(error){
            $('#nameError').text(error.responseJSON.errors.name);
            $('#addressError').text(error.responseJSON.errors.address);
          }  
        })
      }
      // ==================== update data ========================



      // ================= delete data =====================
      function deleteData(id){
        Swal.fire({
          title: 'Are You Sure To Delete?',
          text: "Once deleted, you will not be able recover this imaginary file!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              type:"GET",
              dataType: 'json',
              url: "/delete/"+id,
              success:function(response){
                $('#addTitle').show();
                $('#updateTitle').hide();
                $('#addInfoBtn').show();
                $('#updateInfoBtn').hide();
                allData();
                clearData();
                Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
              }
            })
          }
        })
      }


  // jquery data table 
  $(document).ready( function () {
      $('#myTable').DataTable();
  } );      






    </script>




    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    
  </body>
</html>