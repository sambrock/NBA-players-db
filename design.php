<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=u1663363', 'root', '');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
    echo "Oh no, there was a problem" . $exception->getMessage();
}

$players_q  = "SELECT * FROM players";
$players_set = $conn->query($players_q);
$players = $players_set->fetchAll();

$stats_q  = "SELECT * FROM statistics";
$stats_set = $conn->query($stats_q);
$stats = $stats_set->fetchAll();

$teams_q  = "SELECT * FROM teams";
$teams_set = $conn->query($teams_q);
$teams = $teams_set->fetchAll();

$positions_q  = "SELECT * FROM positions";
$positions_set = $conn->query($positions_q);
$positions = $positions_set->fetchAll();

$awards_q  = "SELECT * FROM awards";
$awards_set = $conn->query($awards_q);
$awards = $awards_set->fetchAll();

$player_position_q  = "SELECT * FROM player_position";
$player_position_set = $conn->query($player_position_q);
$player_position = $player_position_set->fetchAll();

$player_award_q  = "SELECT * FROM player_award";
$player_award_set = $conn->query($player_award_q);
$player_award = $player_award_set->fetchAll();

?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Design - NBA Player Search</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <header>
            <div class="header-inner">
                <a href="index.php"><img class="logo" src="img/nba-logo.png"></a>
                <div class="logo-txt"><a href="index.php">NBA PLAYER SEARCH</a></div>
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
                <h2>Scenario</h2>
                <ul>
                    <li>Uesrs search the database for players in the NBA by first name, last name, team and/or position.</li>
                    <li>A player's stats are stored in the database as totals, and are convereted to a per game average when displayed.</li>
                    <li>Related players are ordered first by teammates, then by players who average the closest points per game and play the same position as the player.</li>
                </ul>
                <h2>Class diagram</h2>
                <img src="img/class.svg" height="600">
                <h2>Physical data model</h2>
                <img src="img/physical.svg" height="600">
                <h2>Database tables</h2>

                <h3>players</h3>
                <div class="design-tbl">
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>last_name</th>
                                <th>fist_name</th>
                                <th>height</th>
                                <th>weight</th>
                                <th>date_of_birth</th>
                                <th>number</th>
                                <th>team_id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($players as $player){
                                echo "<tr>";
                                echo "<td>{$player["id"]}</td>";
                                echo "<td>{$player["last_name"]}</td>";
                                echo "<td>{$player["first_name"]}</td>";
                                echo "<td>{$player["height"]}</td>";
                                echo "<td>{$player["weight"]}</td>";
                                echo "<td>{$player["date_of_birth"]}</td>";
                                echo "<td>{$player["number"]}</td>";
                                echo "<td>{$player["team_id"]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>statistics</h3>
                <div class="design-tbl">
                    <table>
                        <thead>
                            <tr>
                                <th>player_id</th>
                                <th>games</th>
                                <th>minutes_played</th>
                                <th>three_point_fg</th>
                                <th>three_point_fga</th>
                                <th>two_point_fg</th>
                                <th>two_point_fga</th>
                                <th>free_throws</th>
                                <th>free_throws_attempted</th>
                                <th>offensive_rebounds</th>
                                <th>defensive_rebounds</th>
                                <th>assists</th>
                                <th>steals</th>
                                <th>blocks</th>
                                <th>turnovers</th>
                                <th>points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($stats as $stat){
                                echo "<tr>";
                                echo "<td>{$stat["player_id"]}</td>";
                                echo "<td>{$stat["games"]}</td>";
                                echo "<td>{$stat["minutes_played"]}</td>";
                                echo "<td>{$stat["three_point_fg"]}</td>";
                                echo "<td>{$stat["three_point_fga"]}</td>";
                                echo "<td>{$stat["two_point_fg"]}</td>";
                                echo "<td>{$stat["two_point_fga"]}</td>";
                                echo "<td>{$stat["free_throws"]}</td>";
                                echo "<td>{$stat["free_throws_attempted"]}</td>";
                                echo "<td>{$stat["offensive_rebounds"]}</td>";
                                echo "<td>{$stat["defensive_rebounds"]}</td>";
                                echo "<td>{$stat["assists"]}</td>";
                                echo "<td>{$stat["steals"]}</td>";
                                echo "<td>{$stat["blocks"]}</td>";
                                echo "<td>{$stat["turnovers"]}</td>";
                                echo "<td>{$stat["points"]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>teams</h3>
                <div class="design-tbl">
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>name</th>
                                <th>abbreveation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($teams as $team){
                                echo "<tr>";
                                echo "<td>{$team["id"]}</td>";
                                echo "<td>{$team["name"]}</td>";
                                echo "<td>{$team["abbreviation"]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>positions</h3>
                <div class="design-tbl">
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($positions as $position){
                                echo "<tr>";
                                echo "<td>{$position["id"]}</td>";
                                echo "<td>{$position["name"]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>awards</h3>
                <div class="design-tbl">
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($awards as $award){
                                echo "<tr>";
                                echo "<td>{$award["id"]}</td>";
                                echo "<td>{$award["name"]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>player_position</h3>
                <div class="design-tbl">
                    <table>
                        <thead>
                            <tr>
                                <th>player_id</th>
                                <th>position_id</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($player_position as $p_p){
                                echo "<tr>";
                                echo "<td>{$p_p["player_id"]}</td>";
                                echo "<td>{$p_p["position_id"]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h3>player_award</h3>
                <div class="design-tbl">
                    <table>
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>player_id</th>
                                <th>award_id</th>
                                <th>year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($player_award as $p_a){
                                echo "<tr>";
                                echo "<td>{$p_a["id"]}</td>";
                                echo "<td>{$p_a["player_id"]}</td>";
                                echo "<td>{$p_a["award_id"]}</td>";
                                echo "<td>{$p_a["year"]}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <h2>References</h2>
                <ul>
                    <li>Players images and logos from https://www.wikipedia.org/.</li>
                    <li></li>
                </ul>
            </div>
        </main>
        <script src="js/js.js"></script>
    </body>
</html>
