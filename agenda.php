<?php
if (!(isset($_SESSION["user"]))) header("Location:index.php");
$dataUser = $_SESSION["user"];
if (isset($_SESSION["first"])) {
    echo "<script>alert('Welcome " . $dataUser[0]["nick"] . "')</script>";
    unset($_SESSION["first"]);
}
if (isset($_REQUEST["delete"])) deleteContact($_REQUEST["delete"]);

$idContacts = getIdContacts($dataUser[0]["iduser"]);
$contacts = getContacts($idContacts);

echo "<h3>" . $dataUser[0]['nick'] . "'s address book</h3><hr>";

if (isset($_REQUEST["contact"])) {
    $ind = $_REQUEST["contact"];
    if (isset($_REQUEST["send"])) {
        $subject = $_REQUEST["subject"];
        $body = $_REQUEST["body"];
        insertMessage($subject, $body, $contacts, $ind);
    }
    echo "<form action='index.php?opt=1&contact=$ind' method='post'>";
    echo "<label for='to'>To: </label><input type='text' name='to' id='to' placeholder='" . $contacts[$ind][0]['name'] . "' disabled><br>";
    echo "<label for='from'>From: </label><input type='text' name='from' id='from' placeholder='" . $dataUser[0]["nick"] . "' disabled><br>";
    echo "<label for='subject'>Subject: </label><input type='text' name='subject' id='subject'><br>";
    echo "<textarea rows='5' cols='30' name='body'></textarea><br>";
    echo "<input type='submit' value='Send Message' name='send'><br>";
    echo "</form>";
    echo "<a href='index.php?opt=1'>Back</a>";
} else {
    echo "<table>
            <tr>
                <th>User</th>
                <th>Name</th>
                <th>Messages</th>
                <th>Delete</th>
            </tr>";
    for ($i = 0; $i < count($contacts); $i++) {
        echo "<tr>
                  <td>" . $contacts[$i][0]['iduser'] . "</td>
                  <td>" . $contacts[$i][0]['name'] . "</td>
                  <td><a href='index.php?opt=1&contact=" . $i . "'><img src='img/send.jpg' class='img-send'></a></td>
                  <td><a href='index.php?opt=1&delete=" . $contacts[$i][0]['iduser'] . "'><img src='img/delete.png' class='img-delete'></a></td>
              </tr>";
    }
    echo "</table>";
}

if (!(isset($_REQUEST["contact"]))) {
    echo "<a href='index.php?opt=3'>New Contact</a><br>
          <a href='index.php?opt=4'>Show Messages</a>";

    echo "<h3>You have unread messages...<a href='index.php?opt=4'>" . getUnreadMessages($dataUser[0]["iduser"]) . "</a></h3>";
}
echo "<hr><a href='index.php?opt=2'>Logout</a>";

function getIdContacts($id)
{
    $conn = openConection();
    $stmt = $conn->prepare("SELECT idcontact FROM contacts WHERE iduser = :id");
    $stmt->bindValue(":id", $id);
    $stmt->execute();
    $conn = null;
    return $stmt->fetchAll();
}

function getContacts($idContact)
{
    $aContact = array();
    $conn = openConection();
    foreach ($idContact as $id) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE iduser = :iduser");
        $stmt->bindValue(":iduser", $id["idcontact"]);
        $stmt->execute();
        array_push($aContact, $stmt->fetchAll());
    }
    $conn = null;
    return $aContact;
}

function insertMessage($subject, $body, $contacts, $ind)
{
    $refrecipient = $contacts[$ind][0]["iduser"];
    $conn = openConection();
    $stmt = $conn->prepare("INSERT INTO messages (refsender, refrecipient, date, time, subject, body, leido) VALUES (:refsender, :refrecipient, now(), now(), :subject, :body, 0)");
    $stmt->bindValue(":refsender", $_SESSION["user"][0]["iduser"]);
    $stmt->bindValue(":refrecipient", $refrecipient);
    $stmt->bindValue(":subject", $subject);
    $stmt->bindValue(":body", $body);
    $stmt->execute();
    $conn = null;
}

function deleteContact($idContact)
{
    $conn = openConection();
    $stmt = $conn->prepare("DELETE FROM contacts WHERE iduser = :iduser AND idcontact = :idcontact");
    $stmt->bindValue(":iduser", $_SESSION["user"][0]["iduser"]);
    $stmt->bindValue(":idcontact", $idContact);
    $stmt->execute();
    $conn = null;
}

function getUnreadMessages($idUser)
{
    $conn = openConection();
    $stmt = $conn->prepare("SELECT count(*) FROM messages WHERE refrecipient = :idUser AND leido = 0");
    $stmt->bindValue(":idUser", $idUser);
    $stmt->execute();
    $conn = null;
    return $stmt->fetch()[0];
}