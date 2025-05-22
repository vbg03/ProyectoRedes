<?php
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $url = "http://localhost:3005/admin/users/" . urlencode($id);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        echo "ok";
    } else {
        echo "error";
    }
} else {
    echo "error";
}
