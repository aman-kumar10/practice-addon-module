<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
{* <link rel="stylesheet" href="../modules/addons/clientslogged/assets/css/admin.css"> *}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
{* <script src="../modules/addons/clientslogged/assets/js/admin.js"></script> *}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

<div class="clientslogged-header">

    <div class="head-items">
        <ul class="tabs-list">
            <li class="header-tab"><a href="addonmodules.php?module=clientslogged" class="head-itm {if $tplVar['tab'] =='loggedin'}active {/if} "><i class="fa fa-user" aria-hidden="true"></i> Login Activities</a></li>
            <li class="header-tab"><a href="addonmodules.php?module=clientslogged&action=attempts" class="head-itm {if $tplVar['tab'] =='attempts'}active {/if} "><i class="fas fa-file-invoice"></i> Login Attempts</a></li>
        </ul>    
    </div>

</div>