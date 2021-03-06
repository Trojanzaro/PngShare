<?php
    error_reporting(E_ALL ^ E_NOTICE);

    include 'config.php';

    $db_o = new DB_O();
    $db_o = $db_o->get_db();

    if(isset($_GET["email"])) {
        $stmt = $db_o->prepare("SELECT username, avatar FROM users WHERE email = ?;");
        $stmt->bind_param("s", $_GET["email"]);
    } elseif(isset($_GET["userId"])) {
        $stmt = $db_o->prepare("SELECT username, avatar FROM users WHERE id = ?;");
        $stmt->bind_param("s", $_GET["userId"]);
    }
    $stmt->execute();
    $stmt->bind_result($username, $avatar);
    $stmt->fetch();
    if($avatar == '') {
        header('Content-type: application/json');
        http_response_code(404);
        $body = array("status" => 404,  "message" =>"user avatar not found");
        echo utf8_encode(json_encode($body));
        exit();
    } else {
        $img_path = '/png_share_data/' .$username. '/' .$avatar;
        header('Content-Type: image/png');
        readfile($img_path);
    }
?>