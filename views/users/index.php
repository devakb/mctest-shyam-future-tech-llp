<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
        .cursor-pointer{cursor: pointer;}
    </style>
</head>

<body>



    <div class="container my-4">


        <h5>Users List</h5>

        <button type="button" class="btn btn-success" onclick="openModal('create')">Create New User</button>
        <br><br>
        <div class="table-responsive">
            <table class="table" id="users-data">
                <thead>
                    <tr>
                        <th onclick="setSortField('id')" class="cursor-pointer">
                            ID <i class="las la-sort-down" id="sort-id"></i>
                        </th>
                        <th onclick="setSortField('name')" class="cursor-pointer">
                            Name <i class="las la-sort" id="sort-name"></i>
                        </th>
                        <th>Image</th>
                        <th>Address</th>
                        <th>Gender</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>


    </div>

    <!-- Modal -->
    <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="actionModalLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <form id="action-form">

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender <sup class="text-danger">*</sup></label>
                            <select name="gender" id="gender" class="form-control" required>
                                <option value="" disabled>Choose Gender</option>
                                <?php foreach(["Male", "Female"] as $gender): ?>
                                    <option value="<?= $gender ?>"><?= $gender ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <sup class="text-danger">*</sup></label>
                            <textarea name="address" id="address" cols="30" rows="5" class="form-control" placeholder="Enter address" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-5 p-2">
                                <img src="https://via.placeholder.com/150," id="preview" class="img-fluid" alt="">
                            </div>
                            <div class="col-7">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image <sup class="text-danger">*</sup></label>
                                    <input type="file" id="image" name="image" class="form-control" accept="image/png, image/jpeg" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <br><br>

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
            
        </div>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

    <script>

        const url = window.location.origin;
        let sort_field = 'id';
        let sort_dir = 'desc';
        let users = [];

        const setSortField = (field) => {
            if(field == sort_field) { return setSortOrder(sort_dir == "desc" ? "asc" : "desc") }

            sort_field = field; 
            setSortOrder("desc");

        }

        const setSortOrder = (dir) => {
            sort_dir = dir;

            $('#sort-id, #sort-name').attr('class', 'las la-sort');

            $(`#sort-${sort_field}`).attr('class', sort_dir == "desc" ? "las la-sort-down" : "las la-sort-up");

            fetchUsers();
        }

        const fetchUsers = () => {

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: {sort_field,sort_dir},
                success: function (response) {
                    renderUsers(response)
                    users = response;
                }
            });

        }

        const renderUsers = (users) => {

            let tableBody = $('#users-data tbody');

            tableBody.empty();

            users.forEach((user, index) => {

                let row = `<tr>
                    <td>${user.id}</td>
                    <td>${user.name}</td>
                    <td><img src="${user.image}" class="img-thumbnail" style="width: 70px" /></td>
                    <td>${user.address}</td>
                    <td>${user.gender}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openModal('edit',${index})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteUser(${index})">Delete</button>
                    </td>
                </tr>`;

                tableBody.append(row);

            });

        }


        (function(){fetchUsers()})()


        let action_type = null;
        let editing_user = null;

        const openModal = (type, index = null) => {

           switch (type) {
               case 'create':
                   $('#actionModalLabel').text('Create New User');
                   $('#actionModal').modal('show');
                   action_type = 'create';
                   break;
                case 'edit':
                    $('#actionModalLabel').text('Edit User');
                    $('#actionModal').modal('show');
                    setUpEditForm(users[index]);
                    action_type = 'edit';
                    editing_user = index;
                    break;
               default:
                   break;
           }

        }

        const setUpEditForm = (user) => {
            $('#name').val(user.name);
            $('#address').val(user.address);
            $('#gender').val(user.gender);
            $('#preview').attr('src', user.image);
            $('#image').removeAttr('required');
        }

        $('#actionModal').on('hidden.bs.modal', function (e) {
            action_type = null;
            editing_user = null;
            $('#action-form')[0].reset();
            $('#image').attr('required', 'required');
            $('#preview').attr('src', 'https://via.placeholder.com/150');
        })

        $('#action-form').submit(function(e){

            e.preventDefault();

            let method = 'POST';
            let formData = new FormData($('#action-form')[0]);

            (action_type == 'edit') && formData.append('id', users[editing_user].id);
            
            let req_url = url + ((action_type != "edit") ? "/store" : "/update");

            $.ajax({
                url: req_url,
                type: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                headers:{
                    'accept': 'application/json',
                },
                success: function (response) {
                    fetchUsers();
                    $('#actionModal').modal('hide');
                }
            });

        });

        const deleteUser = (index) => {

            if(confirm("Are you sure to delete this user?")){
                $.ajax({
                    url: url + `/delete?id=${users[index].id ?? 0}`,
                    type: "DELETE",
                    headers:{
                        'accept': 'application/json',
                    },
                    success: function (response) {
                        fetchUsers();
                    }
                });
            }

        }

        $('#image').change(function(e){
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onload = function(e){
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        })

    </script>
</body>

</html>