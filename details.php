<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=u1663363', 'root', '');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
    echo "Oh no, there was a problem" . $exception->getMessage();
}

$player_id=$_GET["id"];

$query = "SELECT *,
ROUND(SUM(minutes_played / games), 1) as MP,
ROUND(SUM((three_point_fg+two_point_fg) / games),1) as FG,
ROUND(SUM((three_point_fga+two_point_fga) / games),1) as FGA,
ROUND(SUM((three_point_fg+two_point_fg)/(three_point_fga+two_point_fga)),3) as FGP,
ROUND(SUM(three_point_fg / games), 1) as 3P,
ROUND(SUM(three_point_fga / games), 1) as 3PA,
ROUND(SUM((three_point_fg / three_point_fga)),3) as 3PP,
ROUND(SUM(free_throws / games), 1) as FT,
ROUND(SUM(free_throws_attempted / games), 1) as FTA,
ROUND(SUM((free_throws / free_throws_attempted)),3) as FTP,
ROUND(SUM(offensive_rebounds / games),1) as OREB,
ROUND(SUM(defensive_rebounds / games),1) as DREB,
ROUND(SUM((offensive_rebounds + defensive_rebounds) / games),1) as REB,
ROUND(SUM(assists / games), 1) as AST,
ROUND(SUM(steals / games), 1) as STL,
ROUND(SUM(blocks / games), 1) as BLK,
ROUND(SUM(turnovers / games), 1) as TOV,
ROUND(SUM(points / games), 1) as PTS
FROM players WHERE id=:id";
$prep_stmt=$conn->prepare($query);
$prep_stmt->bindValue(":id", $player_id);

$prep_stmt->execute();
$player=$prep_stmt->fetch();

?>

<html>
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <?php
        echo "<h1>{$player["first_name"]} {$player["last_name"]}</h1>";
        ?>
        <table>
            <thead>
                <tr>
                    <th><abbr title="Games Played">GP</abbr></th>
                    <th><abbr title="Minuites Played">MP</abbr></th>
                    <th><abbr title="Field Goals">FG</abbr></th>
                    <th><abbr title="Field Goals Attempted">FGA</abbr></th>
                    <th><abbr title="Field Goal Percentage">FG%</abbr></th>
                    <th><abbr title="Three Point Field Goals">3P</abbr></th>
                    <th><abbr title="Three Point Field Goal Attempts">3PA</abbr></th>
                    <th><abbr title="Three Point Field Goal Percentage">3P%</abbr></th>
                    <th><abbr title="Free Throws">FT</abbr></th>
                    <th><abbr title="Free Throw Attempts">FTA</abbr></th>
                    <th><abbr title="Free Throw Percentage">FT%</abbr></th>
                    <th><abbr title="Offensive Rebounds">OREB</abbr></th>
                    <th><abbr title="Defensive Rebounds">DREB</abbr></th>
                    <th><abbr title="Rebonds">REB</abbr></th>
                    <th><abbr title="Assists">AST</abbr></th>
                    <th><abbr title="Steals">STL</abbr></th>
                    <th><abbr title="Blocks">BLK</abbr></th>
                    <th><abbr title="Turnovers">TOV</abbr></th>
                    <th><abbr title="Points">PTS</abbr></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    echo "<td>{$player["games"]}</td>";
                    echo "<td>{$player["MP"]}</td>";
                    echo "<td>{$player["FG"]}</td>";
                    echo "<td>{$player["FGA"]}</td>";
                    echo "<td>".ltrim($player["FGP"], "0")."</td>";
                    echo "<td>{$player["3P"]}</td>";
                    echo "<td>{$player["3PA"]}</td>";
                    echo "<td>".ltrim($player["3PP"], "0")."</td>";
                    echo "<td>{$player["FT"]}</td>";
                    echo "<td>{$player["FTA"]}</td>";
                    echo "<td>".ltrim($player["FTP"], "0")."</td>";
                    echo "<td>{$player["OREB"]}</td>";
                    echo "<td>{$player["DREB"]}</td>";
                    echo "<td>{$player["REB"]}</td>";
                    echo "<td>{$player["AST"]}</td>";
                    echo "<td>{$player["STL"]}</td>";
                    echo "<td>{$player["BLK"]}</td>";
                    echo "<td>{$player["TOV"]}</td>";
                    echo "<td>{$player["PTS"]}</td>";
                    ?>
                </tr>
            </tbody>
        </table>
    </body>
</html>
