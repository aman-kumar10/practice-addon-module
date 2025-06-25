$(document).ready(function() {
    // Initialize Loggind Table
    $('#loggingTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "../modules/addons/clientslogged/lib/Ajax/loggedin.php",
        "columns": [
            { "data": "firstname"},
            { "data": "lastname"},
            { "data": "email"},
            { "data": "ip_address"},
            { "data": "login"},
            { "data": "logout"},
            { "data": "time"},
            { "data": "status"},
            // { "data": "action_btns", "orderable": false, "searchable": false }
        ]
    });

    // Initialize Login Attempts Table
    $('#attemptsTable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": "../modules/addons/clientslogged/lib/Ajax/attempts.php",
        "columns": [
            { "data": "firstname"},
            { "data": "lastname"},
            { "data": "email"},
            { "data": "attempts"},
            { "data": "ip_address"},
            { "data": "last_attempt"},
            { "data": "status"},
            { "data": "action"},
        ]
    });
});