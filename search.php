<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=u1663363', 'root', '');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
    echo "Oh no, there was a problem" . $exception->getMessage();
}

$searchterm="%{$_GET["q"]}%";

$query = "SELECT * FROM players WHERE first_name LIKE :searchterm OR last_name LIKE :searchterm";
$prep_stmt=$conn->prepare($query);
$prep_stmt->bindValue(":searchterm", $searchterm);

$prep_stmt->execute();
$players=$prep_stmt->fetchAll();

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
        <?php
        foreach($players as $player){
            echo "<a href='details.php?id={$player["id"]}'>";
            echo "{$player["first_name"]} {$player["last_name"]}";
            echo "</a>";
            echo "<br>";
        }
        ?>
    </body>
</html>
