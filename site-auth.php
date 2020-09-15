<?php
if(!isset($_SESSION)) session_start();
require_once 'config.php';
require_once 'functions.php';

if(rememberMeCookie($pdo)){
    $mySforceConnection->login(
        constant($_SESSION['role'] . '_USERNAME'), 
        constant($_SESSION['role'] . '_PASSWORD')
        .constant($_SESSION['role'] . '_SECURITY_TOKEN')
    );
}else{
    ?>
    <form method='post' action='#' style='text-align:center;' id='frmLogin'>
        <h2 style='text-align:center;'>Inventory</h2>
        <input type='text' id='un' name='un' style='font-size:20px; padding:20px; margin:30px 0px; width:80%;' placeholder='Name/Nome'/>
        <input type='password' id='pw' name='pw' style='font-size:20px; padding:20px; margin:30px 0px; width:80%;' placeholder='Password/Senha' />
        
        <a href='#' id='btnLogin'>Submit Password <br /> Enviar Senha</a>
    </form> 
<?php
}
    
    
function rememberMeCookie($pdo){
    $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if ($cookie) {
        list ($user, $token, $mac) = explode(':', $cookie);
        if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, SITE_KEY), $mac)) {
            return false;
        }
        $row = fetchTokenByUserName($pdo, filter_var($user, FILTER_SANITIZE_STRING));
        if (isset($row['token']) && hash_equals($token, $row['token'])) {
            $_SESSION['user'] = $user;
            $_SESSION['site_auth'] = TRUE;
            $_SESSION['role'] = $row['session_role'];
            $_SESSION['inv_item'] = isset($_SESSION['inv_item']) ? $_SESSION['inv_item'] : 'inv';
            return true;
        }else{
            return false;
        }
    }
}

function fetchTokenByUserName($pdo, $user){
    $sql = "SELECT
            token,
            session_role
        FROM 
            rememberme
        WHERE
            username = :username
        LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $user, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row;
}
