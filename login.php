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
    rememberMe($pdo, filter_var($_POST['un'], FILTER_SANITIZE_STRING), $_SESSION['role']);
}else{
    die();
}


function rememberMe($pdo, $user, $role){
    $token = bin2hex(openssl_random_pseudo_bytes(128));
    if(storeTokenForUser($pdo, $user, $token, $role)){
        $cookie = $user . ':' . $token;
        $mac = hash_hmac('sha256', $cookie, SITE_KEY);
        $cookie .= ':' . $mac;
        setcookie('rememberme', $cookie, time() + (86400 * 90));
    }
}


function storeTokenForUser($pdo, $user, $token, $session_role){
    $sql = "INSERT INTO 
            rememberme 
        SET 
            username = :username,
            token = :token,
            session_role = :session_role,
            created = now()
        ON DUPLICATE KEY UPDATE
            username = :username,
            token = :token,
            session_role = :session_role,
            created = now()";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $user, PDO::PARAM_STR);
    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
    $stmt->bindValue(':session_role', $session_role, PDO::PARAM_STR);
    return $stmt->execute();
}