<?php
function showError($message, $statusCode = 500) {
    header('Content-Type: application/json', true, $statusCode);
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

function showViewError($message) {
    die($message);
}

function redirect($url) {
    header('Location: ' . $url);
    exit();
}