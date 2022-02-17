<?php
if (!(isset($_SESSION["user"]))) header("Location:index.php");
$dataUser = $_SESSION["user"];
if (isset($_REQUEST["add"])) addToAgenda($_REQUEST["add"], $dataUser[0]["iduser"]);
$noContacts = getNoContacts($dataUser[0]["iduser"]);
?>

    <h3>Add New Contact</h3>
    <hr>
    <table>

        <?php
        foreach ($noContacts as $v) {
            echo "<tr><td><a href='index.php?opt=3&add=" . $v["iduser"] . "'>" . $v["name"] . "</a></td></tr>";
        }
        ?>

    </table>
    <hr>
    <a href="index.php?opt=1">Return Agenda</a>


<?php
function getNoContacts($idUser)
{
    $conn = openConection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE iduser NOT IN (SELECT idcontact FROM contacts WHERE iduser = :idUser) AND iduser <> :idUser");
    $stmt->bindValue(":idUser", $idUser);
    $stmt->execute();
    $conn = null;
    return $stmt->fetchAll();
}

function addToAgenda($idNewContact, $idUser)
{
    $conn = openConection();
    $stmt = $conn->prepare("INSERT INTO contacts (iduser, idcontact) VALUES (:idUser, :idContact)");
    $stmt->bindValue(":idUser", $idUser);
    $stmt->bindValue(":idContact", $idNewContact);
    $stmt->execute();
    $conn = null;
}
