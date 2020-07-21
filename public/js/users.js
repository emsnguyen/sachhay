var id = null;
$(document).ready(function () {
    $('#editUserModal').on('show.bs.modal', function (e) {
        // get information to update quickly to modal view as loading begins

        var button = $(e.relatedTarget); // Button that triggered the modal
        var name = button.data('name'); // Extract info from data-* attributes
        var email = button.data('email');
        var role = button.data('role');
        var banned = button.data('banned');
        id = button.data('id');
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

        // //set what we got to our form
        $('#edit-form').find('[name="name"]').val(name);
        $('#edit-form').find('[name="email"]').val(email);
        $('#edit-form').find('[name="role"]').val(role);
        if (banned) {
            $('input[type="checkbox"]').prop("checked", true);
        }
    });
});

function deleteUser(userId) {
    $.ajax({
        url: "/dashboard/users/" + userId,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "DELETE",
        success: function () {
            console.log("deleted user " + userId);
            // remove row
            $('#row-' + userId).remove();
        },
        error: function(data) {
            console.log(data);
        }
    });
}

$("#modal-btn-edit").click(function () {
    var name = $('#edit-form').find('[name="name"]').val();
    var email = $('#edit-form').find('[name="email"]').val();
    var role = $('#edit-form').find('[name="role"]').val();
    var banned = $('input[type="checkbox"]').is(":checked") ? 1 : 0;
    $.ajax({
        url: "/dashboard/users/" + id,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: "PUT",
        data: {
           id, name, email, role, banned
        },
        dataType: "JSON",
        success: function (data) {
            location.reload();
        },
        error: function(data) {
            console.log(data);
        }
    });
});
