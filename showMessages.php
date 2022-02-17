<?php
if (!(isset($_SESSION["user"]))) header("Location:index.php");
$dataUser = $_SESSION["user"];
$messages = getMessages($dataUser[0]["iduser"]);

echo "<h3>Messages send to " . $dataUser[0]["nick"] . "</h3><hr>";
echo "<table border='1'>";
if (isset($_REQUEST["show"])) {
    $m = getMessage($_REQUEST["show"]);
    echo "<tr><td>Date: " . $m[0]['date'] . "</td><td>Time: " . $m[0]["time"] . "</td></tr>";
    echo "<tr><td colspan='2'>Subject: " . $m[0]["subject"] . "</td></tr>";
    echo "<tr><td colspan='2'>" . $m[0]["body"] . "</td></tr>";
    mensajeLeido($_REQUEST["show"]);
} else {
    echo "<tr>
            <th>Subject</th>
            <th>Sender</th>
          </tr>";
    foreach ($messages as $v) {
        $name = getName($v["refsender"]);
        echo "<tr style='background-color: " . ($v["leido"] > 0 ? 'grey' : 'white') . "'>
                 <td><a href='index.php?opt=4&show=" . $v["idmessage"] . "'>" . $v["subject"] . "</a></td>
                 <td>$name</td>
              </tr>";
    }
}
echo "</table>";
echo isset($_REQUEST["show"]) ? "<a href='index.php?opt=4'>Back</a>" : "<a href='index.php?opt=1'>Return Agenda</a>";
echo "<hr><a href='index.php?opt=2'>Logout</a>";

function getMessages($idUser)
{
    $conn = openConection();
    $stmt = $conn->prepare("SELECT * FROM messages WHERE refrecipient = :idUser ");
    $stmt->bindValue(":idUser", $idUser);
    $stmt->execute();
    $conn = null;
    return $stmt->fetchAll();
}

function getName($idUser)
{
    $conn = openConection();
    $stmt = $conn->prepare("SELECT name FROM users WHERE iduser = :idUser");
    $stmt->bindValue(":idUser", $idUser);
    $stmt->execute();
    $conn = null;
    return $stmt->fetch()[0];
}

function getMessage($idMessage)
{
    $conn = openConection();
    $stmt = $conn->prepare("SELECT * FROM messages WHERE idmessage = :idMessage");
    $stmt->bindValue(":idMessage", $idMessage);
    $stmt->execute();
    $conn = null;
    return $stmt->fetchAll();
}

function mensajeLeido($idMessage)
{
    $conn = openConection();
    $stmt = $conn->prepare("UPDATE messages SET leido = 1 WHERE idmessage = :idMessage");
    $stmt->bindValue(":idMessage", $idMessage);
    $stmt->execute();
}