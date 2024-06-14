<?php 

session_start();

require('src/connection.php');

if (!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {

    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pass_confirm = $_POST['password_confirm'];

    // VERIF PASSWORD == PASSWORD_CONFIRM
    if ($password != $pass_confirm) {
        header('location: ../?error=1&pass=1');
    }

    // TEST EMAIL UTILISE
    $req = $db->prepare("SELECT count(*) as numberEmail
                         FROM users
                         WHERE email = ?");

    $req->execute(array($email));

    while ($email_verification = $req->fetch()) {
        if ($email_verification['numberEmail'] != 0) {
            header('location: ../?error=1&email=1');
        }
    }

    // HASH
    $secret = sha1($email).time();
    $secret = sha1($secret).time().time();

    // CRYPTAGE PASSWORD
    $password = "aq1".sha1($password."1254")."25";

    // ENVOIE DE LA REQUETE
    $req = $db->prepare('INSERT INTO users(pseudo, email, password, secret)
                         VALUES (?, ?, ?, ?)');
    
    $req->execute(array($pseudo, $email, $password, $secret));

    header('location: ../?success=1');

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace membre</title>
    <link rel="stylesheet" type="text/css" href="design/default.css" />
</head>
<body>
    <header>
        <h1>Inscription</h1>
    </header>

    <div class="container">

        <?php 

            if (!isset($_SESSION['connect'])) { 
        
        ?>

        <p id="info">Bienvenue sur mon site, pour en voir plus, inscrivez-vous. Sinon, <a href="connection.php">connectez-vous</a>.</p>

        <?php 

            if (isset($_GET['error'])) {
                if (isset($_GET['pass'])) {
                    echo '<p id="error">Les mots de passe ne sont pas identiques</p>';
                } else if (isset($_GET['email'])) {
                    echo '<p id="error">Cette adresse email est déjà prise.</p>';
                } 
            }
            else if (isset($_GET['success'])) {
                echo '<p id="success">Inscription prise correctement en compte</p>';
            }

        ?>

        <div id="form">
            <form method="post" action="index.php">
                <table>
                    <tr>
                        <td>Pseudo</td>
                        <td><input type="text" name="pseudo" placeholder="Pseudo" required /></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="email" name="email" placeholder="example@google.com" required /></td>
                    </tr>
                    <tr>
                        <td>Mot de passe</td>
                        <td><input type="password" name="password" placeholder="*****" required /></td>
                    </tr>
                    <tr>
                        <td>Retaper mot de passe</td>
                        <td><input type="password" name="password_confirm" placeholder="*****" required /></td>
                    </tr>
                </table>
                <div id="button">
                    <button>Inscription</button>
                </div>
            </form>
        </div>
        <?php } else { ?>
            <p id="info">
                Bonjour <?= $_SESSION['pseudo'] ?><br />
                <a href="disconnection.php">Déconnexion</a>
            </p>
        <?php } ?>
    </div>

</body>
</html>