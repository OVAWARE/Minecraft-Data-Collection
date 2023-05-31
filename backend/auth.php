<?php
function checkAuthentication() {
    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        if (!checkToken($token)) {
            redirectToRegister();
        }
    } else {
        redirectToRegister();
    }
    http_response_code(200);
}

function redirectToRegister() {
    header('Location: ./register.php');
    exit;
}

function checkToken($token) {
    $accountsFile = 'accounts.json';

    if (!file_exists($accountsFile)) {
        return false;
    }

    $accountsJson = file_get_contents($accountsFile);
    $accounts = json_decode($accountsJson, true);

    foreach ($accounts as $account) {
        if ($account['Token'] === $token) {
            return true;
        }
    }

    return false;
}


function signInUser($username) {
    $_SESSION['username'] = $username;
}