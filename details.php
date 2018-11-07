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

$stats_q = "SELECT first_name, last_name, height, weight, date_of_birth, number, games,
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
$stats_stmt=$conn->prepare($stats_q);
$stats_stmt->bindValue(":id", $player_id);

$stats_stmt->execute();
$player=$stats_stmt->fetch();

$pos_q = "SELECT positions.name
FROM players
INNER JOIN player_position ON players.id=player_position.player_id
INNER JOIN positions ON player_position.position_id=positions.id WHERE players.id=:id";
$pos_stmt=$conn->prepare($pos_q);
$pos_stmt->bindValue(":id", $player_id);

$pos_stmt->execute();
$player_pos=$pos_stmt->fetchAll();

$awards_q ="SELECT name, year FROM awards
INNER JOIN player_award ON awards.id = player_award.award_id INNER JOIN players ON player_award.player_id = players.id
WHERE players.id = :id";
$awards_stmt=$conn->prepare($awards_q);
$awards_stmt->bindValue(":id", $player_id);

$awards_stmt->execute();
$awards=$awards_stmt->fetchAll();

?>

<html>
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link href="style/style.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
        <header>
            <div class="header-inner">
                <img class="logo" src="img/nba-logo.png">
                <div class="logo-txt">
                    NBA PLAYER SEARCH
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Search</a></li>
                        <li><a href="design.php">Design</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class="page-wrapper">
                <?php
                echo "<h1>{$player["first_name"]} {$player["last_name"]}</h1>";
                foreach($player_pos as $pos){
                    echo "<p>{$pos["name"]}</p>";
                }
                ?>
                <div class="stats-tbl">
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
                </div>
                <?php
                    foreach($awards as $award){
                        echo "<p>{$award["name"]}: {$award["year"]}</p>";
                    }
                ?>
                </div>
        </main>
    </body>
</html>
