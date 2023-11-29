<?php
    include "db.php";
    $post = file_get_contents('php://input');

    // For debugging: Log the raw POST data.
    file_put_contents("debug_postdata.txt", $postdata);

    if(empty($postdata)) {
        echo json_encode(["error" => "No data received"]);
    } else {
        $request = json_decode($postdata, true);  // Using true to get an associative array.
        if(null === $request) {
            echo json_encode(["error" => "JSON decoding error: " . json_last_error_msg()]);
        } else {
            echo json_encode(["message" => "Hello from the other side"]);
        }
    }
?>