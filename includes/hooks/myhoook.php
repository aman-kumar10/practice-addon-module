<?php


add_hook('ClientLoginShare', 1, function($vars) {
    echo "<pre>"; print_r($vars); die;
});

// Array
// (
//     [username] => admin@jdg.j
//     [password] => cri#69CrE7aB
// )