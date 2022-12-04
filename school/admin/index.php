<?php
include_once "../includes/application.php";
include_once "../includes/header.php";

$Application = $GLOBALS['APP'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Admin</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>
<body>
    <?php
    if ($Application->IsAdmin()) // user is admin, show admin panel
    {
        echo "<h1><a href='index.php'>Admin panel</a></h1>";
        echo "<a href=\"logout.php\">Logout</a>";
        $pre = '';
        if(isset($_GET['p']))
        {
        $page="_".$_GET['p'];
        if(!file_exists('./'.$pre.$page.'.php') || $page == "index")
            $page='_error';
        @include './'.$pre.$page.'.php';
        }
        else
        {
        $page = "_index";
        if(!file_exists('./'.$pre.$page.'.php'))
            $page='_error';
        @include './'.$pre.$page.'.php';
        }
    }
    else // user is either not logged in or doesn't have admin rights to access this area
    {
        echo '
        <form action="login.php" method="post"> 
            <fieldset> 
            <legend><b>Login</b></legend> 
            <p><input name="login" size="20" tabindex="1" type="text" /> <label>Email</label></p> 
            <p><input name="password" size="20" tabindex="2" type="password" /> <label>Password</label></p>';
            echo'<p><input name="submit" type="submit" tabindex="3" value="Login" /></p> 
            ';  
            if (isset($_GET['code']) && $_GET['code'] == "401")
                echo "<h2>Invalid username or password.</h2>";
            echo '</fieldset> 
        </form>
        '; 
    }
    ?>
</body>
</html>