<?php
session_start();
$_SESSION['site_auth'] = FALSE;
?>

<!DOCTYPE html>

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Inventory</title>
        <meta name="description" content="Inventory">
        <meta name="author" content="Johnny Stenson">
    </head>

    <body>
        <form method='post' action='menu.php' style="text-align:center;">
            <input type="password" name='pw' style='font-size:30px; padding:20px; margin:50px; width:90%;' />
            <input type='submit' style='font-size:30px; padding:20px; margin:50px; width:90%;' value='Submit Password / Enviar Senha' />
        </form>
    </body>
</html>