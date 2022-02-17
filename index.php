<?php
require_once "connection.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MessengerService</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <h1>Messenger Service</h1>
</header>

<?php
$opt = $_REQUEST["opt"] ?? 0;
switch ($opt) {
    case "1":
        require_once "agenda.php";
        break;
    case "2":
        require_once "logout.php";
        break;
    case "3":
        require_once "newContact.php";
        break;
    case "4":
        require_once "showMessages.php";
        break;
    default:
        if (isset($_SESSION["user"])) {
            header("Location:index.php?opt=1");
        } else {
            require_once "login.php";
        }
        break;
}
?>

</body>
</html>


