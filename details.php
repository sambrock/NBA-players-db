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

$stats_q = "SELECT first_name, last_name, date_of_birth, abbreviation, name, number, games,
FLOOR(height/(12*2.54)) as ft,
ROUND((height mod(12*2.54))/2.54) as inch,
CEILING(ROUND(SUM(weight*2.2046226218)/5)*5) as lbs,
CONCAT(MONTHNAME(date_of_birth),' ', DAY(date_of_birth), ', ', YEAR(date_of_birth)) as DOB,
ROUND(SUM(minutes_played / games), 1) as MP,
ROUND(SUM((three_point_fg+two_point_fg) / games),1) as FG,
ROUND(SUM((three_point_fga+two_point_fga) / games),1) as FGA,
ROUND(SUM((three_point_fg+two_point_fg)/(three_point_fga+two_point_fga)),3) as FGP,
ROUND(SUM(three_point_fg / games), 1) as 3P,
ROUND(SUM(three_point_fga / games), 1) as 3PA,
ROUND(SUM((three_point_fg / three_point_fga)),3) as 3PP,
ROUND(SUM(free_throws / games), 1) as FT,
ROUND(SUM(free_throws_attempted / games), 1) as FTA,
ROUND(SUM(free_throws / free_throws_attempted),3) as FTP,
ROUND(SUM(offensive_rebounds / games),1) as OREB,
ROUND(SUM(defensive_rebounds / games),1) as DREB,
ROUND(SUM((offensive_rebounds + defensive_rebounds) / games),1) as REB,
ROUND(SUM(assists / games), 1) as AST,
ROUND(SUM(steals / games), 1) as STL,
ROUND(SUM(blocks / games), 1) as BLK,
ROUND(SUM(turnovers / games), 1) as TOV,
ROUND(SUM(points / games), 1) as PTS
FROM players INNER JOIN statistics ON players.id=statistics.player_id INNER JOIN teams ON players.team_id=teams.id
WHERE players.id=:id";
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

$awards_q ="SELECT name, GROUP_CONCAT(year SEPARATOR ', ') as year FROM awards INNER JOIN player_award ON awards.id = player_award.award_id INNER JOIN players ON player_award.player_id = players.id WHERE players.id = :id GROUP BY awards.name";
$awards_stmt=$conn->prepare($awards_q);
$awards_stmt->bindValue(":id", $player_id);

$awards_stmt->execute();
$awards=$awards_stmt->fetchAll();

$player_team=$player["abbreviation"];
$player_pts=$player["PTS"];
$player_position=$player_pos[0]["name"];

$related_q ="SELECT players.id as player_id, first_name, last_name, SUM(points / games) as PTS, teams.abbreviation as team, number, positions.name as position FROM players INNER JOIN statistics ON players.id=statistics.player_id INNER JOIN teams ON players.team_id = teams.id INNER JOIN player_position ON players.id=player_position.player_id INNER JOIN positions ON player_position.position_id=positions.id WHERE (NOT players.id=:id AND (abbreviation=:team OR positions.name=:position)) GROUP BY abbreviation, last_name ORDER BY FIELD(team, '".$player_team."') DESC, ABS(SUM(points/games)-".$player_pts.") LIMIT 3";
$related_stmt=$conn->prepare($related_q);
$related_stmt->bindValue(":id", $player_id);
$related_stmt->bindValue(":team", $player_team);
$related_stmt->bindValue(":position", $player_position);

$related_stmt->execute();
$related_players=$related_stmt->fetchAll();

?>

<html>
    <head>
        <title><?php echo "{$player["first_name"]} {$player["last_name"]}" ?> - NBA Player Search</title>
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
                <div class="details-container">
                    <div class="player-details">
                        <div class="player-fname"> <?php echo "{$player["first_name"]}"; ?></div>
                        <div class="player-lname"> <?php echo "".strtoupper($player["last_name"]).""; ?></div>
                        <div class="player-info">
                            <?php
                            echo "<img src='img/teams/{$player["abbreviation"]}.png'> ";
                            echo "<span> | #{$player["number"]} | </span>";
                            foreach($player_pos as $pos){
                                echo "<span>{$pos["name"]}</span>";
                            }
                            ?>
                        </div>
                        <div class="player-measurements">
                            <span>Height</span><span>Weight</span>
                            <div class="player-height"><?php echo "<span class='measurement'>{$player["ft"]}</span><span class='unit'>ft </span><span class='measurement'>{$player["inch"]}</span><span class='unit'>in</span>" ?></div>
                            <div class="player-weight"><?php echo "<span class='measurement'>{$player["lbs"]}</span><span class='unit'>lbs</span>" ?></div>
                        </div>
                        <div class="player-awards">
                            <span>Born:</span>
                            <?php echo "<span class='award'>{$player["DOB"]}</span>"; ?>
                            <span>Team:</span>
                            <?php echo "<span class='award'>{$player["name"]}</span>"; ?>
                            <?php
                            foreach($awards as $award){
                                echo "<span>{$award["name"]}:</span> <span class='award'>{$award["year"]}</span>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="player-img">
                        <?php echo '<img src="img/players/'.strtolower($player["first_name"]).'-'.strtolower($player["last_name"]).'.jpg" alt="'.$player["first_name"].' '.$player["last_name"].'"/>';?>
                    </div>
                </div>
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
            </div>
                <div class="related-players">
                    <?php
                        foreach($related_players as $player){
                            echo "<div class='related-player'>";
                            echo "<div class='related-player-name'><a href='details.php?id={$player["player_id"]}'>{$player["first_name"]} {$player["last_name"]}</a></div>";
                            echo "<div class='related-player-info'>";
                            echo "<img src='img/teams/{$player["team"]}.png'> ";
                            echo "<span> | #{$player["number"]} | </span>";
                            echo "<span>{$player["position"]}</span>";
                            echo "</div>";
                            echo "</div>";
                        }
                    ?>
                </div>
        </main>
    </body>
</html>
