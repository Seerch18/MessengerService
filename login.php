<?php
$fieldsEmpty = false;
$error = false;
if (isset($_REQUEST["login"])) {
    $fieldsEmpty = empty($_REQUEST["user"]) || empty($_REQUEST["pass"]);
    if (!$fieldsEmpty) {
        $dataUser = getQuery($_REQUEST["user"], $_REQUEST["pass"]);
        if (count($dataUser) > 0) {
            $_SESSION["user"] = $dataUser;
            $_SESSION["first"] = true;
            header("Location:index.php?opt=1");
        } else {
            $error = true;
        }
    }
}
?>

    <h3>Login</h3>
    <hr>
    <form action="" method="post">
        <label for="user">User: </label>
        <input type="text" name="user" id="user"><br>
        <label for="password">Password: </label>
        <input type="password" name="pass" id="password"><br>
        <?= $fieldsEmpty ? "<span style='color: red'>The fields can not be empty</span>" : "" ?>
        <?= $error ? "<span style='color: red'>Wrong User or Password</span>" : "" ?>
        <hr>
        <input type="submit" value="Login" name="login">
    </form>

<?php
function getQuery($nick, $pass)
{
    $conn = openConection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE nick like :nick AND password like :password");
    $stmt->bindValue(":nick", $nick);
    $stmt->bindValue(":password", $pass);
    $stmt->execute();
    return $stmt->fetchAll();
}
