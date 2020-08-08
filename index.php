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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" media="screen" href="style.css?v=1.0">
    </head>

    <body>
        <form method='post' action='menu.php' style="text-align:center;">
            <input type="password" name='pw' style='font-size:30px; padding:20px; margin:50px 0px; width:200px;' />
            
            <button type="submit" id="btnLogin">Submit Password <br /> Enviar Senha</button>
        </form>
    </body>
</html>