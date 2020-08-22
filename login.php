<?php 
require_once 'config.php';
require_once 'functions.php';
if(isset($_POST['pw'])){
    switch(filter_var($_POST['pw'], FILTER_SANITIZE_STRING)){
        case CREW_SITEPW:
            $_SESSION['site_auth'] = TRUE;
            $_SESSION['role'] = 'CREW';
        break;
        case MNGR_SITEPW:
            $_SESSION['site_auth'] = TRUE;
            $_SESSION['role'] = 'MNGR';
        break;
        default:
            die();
    }
    $_SESSION['inv_item'] = 'inv';
    rememberMe($pdo, filter_var($_POST['un'], FILTER_SANITIZE_STRING));
}else{
    die();
}


function rememberMe($pdo, $user){
    $token = bin2hex(openssl_random_pseudo_bytes(128));
    bdump($token);
    storeTokenForUser($pdo, $user, $token);
    $cookie = $user . ':' . $token;
    $mac = hash_hmac('sha256', $cookie, 'SECRET_KEY');
    $cookie .= ':' . $mac;
    setcookie('rememberme', $cookie);
}


function storeTokenForUser($pdo, $user, $token){
    $sql = "INSERT INTO 
            rememberme 
        SET 
            username = :username,
            token = :token
        ON DUPLICATE KEY UPDATE
            username = :username,
            token = :token";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $user, PDO::PARAM_STR);
    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    return $pdo->lastInsertId();
}