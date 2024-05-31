$(document).ready(function() {
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var userId = button.data('userid');
        var userName = button.data('username');
        var userEmail = button.data('useremail');
        var userRole = button.data('userrole');
        var userLevel = button.data('userlevel');
        var userCoins = button.data('usercoins');

        var modal = $(this);
        modal.find('#editForm').attr('action', '/admin-users/' + userId);
        modal.find('#username').val(userName);
        modal.find('#useremail').val(userEmail);
        modal.find('#role').val(userRole);
        modal.find('#level').val(userLevel);
        modal.find('#coins').val(userCoins);
    });

    $('#confirmModal').on('show.bs.modal', function (event) {
        let button = $(event.relatedTarget);
        let userId = button.data('userid');
        let formAction = '/admin-users/' + userId; // Update the form action URL
        $('#deleteForm').attr('action', formAction);
    });

    // Hide alerts after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);

});
