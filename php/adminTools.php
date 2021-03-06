<?php
    include_once ('sessionManager.php');
    include_once ('User.php');

    if(!isset($_SESSION['email'])) {
        header("Location: errore.php?errorCode=paginaNonDisponibile");
    }

    if(!User::isAdmin($_SESSION['email'])){
        header("Location: errore.php?errorCode=nonAdmin");
    }

?>  
<!DOCTYPE html>
<html lang="en">
    <head>
    <title>Admin Tools &#124; DevSpace</title>
        <meta charset="UTF-8">
        <meta name="description" content="Administrator tools page" />
        <meta name="keywords" content="computer, science, informatica, development, teconologia, technology" />
        <meta name="author" content="Barzon Giacomo, De Filippis Francesco, Greggio Giacomo, Roverato Michele" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta name="theme-color" content="#F5F5F5" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"/>	
        <?php include_once ('favicon.php'); ?>
        <link rel="stylesheet" type="text/css" href="./style/style.css" />
        <link rel="stylesheet" type="text/css" href="./style/print.css" media="print"/>
        <script src="./script/scripts.js"></script>
        
    </head>
    
    <body>
        <?php
        include_once('navbar.php');
        SimpleNavbar::printSimpleNavbar();
        
        $nickname = unserialize($_SESSION['userInfo'])->nickname;
        if(User::isBanned($nickname)) {
            echo '<div id="registration-form">
            <div id="login-error-box-zone"></div>
            <div class="regform-main-section">
            <ul class="regform-errorbox">
                <li>Your account has been suspended, you can\'t use admin tools anymore. In order to get back your admin role
                another user have to remove your suspension.</li>
            </ul>
            </div>';
            echo '<noscript>';
            SimpleNavbar::printSimpleNavbar(true);
            echo '</noscript>';
            echo '</div>';
        } else {
            echo "<div id='registration-form'>
            <div class='regform-introduction'>
                <h1>Dev Space</h1>
                <h2>Admin tools</h2>
            </div>
            <div class='regform-main-section'>
                <ul class='simple-list'>
                    <li><a href='manageArguments.php'>Create or delete topics</a></li>
                    <li><a href='addAdmin.php'>Add new administrator</a></li>
                    <li><a href='manageUsers.php'>User suspension</a></li>
                </ul>
            </div>";
            echo '<noscript>';
            SimpleNavbar::printSimpleNavbar(true);
            echo '</noscript>';
            echo "</div>";
        }

        echo '<noscript>';
        SimpleNavbar::printNoJSWarning();
        echo '</noscript>';
        ?>
    </body>
</html>