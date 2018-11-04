<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=u1663363', 'root', '');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception)
{
    echo "Oh no, there was a problem" . $exception->getMessage();
}

if(isset($_GET['q'])){
    $searchterm="%{$_GET["q"]}%";

    $query = "SELECT *, players.first_name, players.last_name, players.id as player_id, teams.id as team_id, ROUND(SUM(points / games), 1) as PTS, ROUND(SUM((offensive_rebounds + defensive_rebounds) / games),1) as REB, ROUND(SUM(assists / games), 1) as AST, ROUND(SUM(blocks / games), 1) as BLK FROM players INNER JOIN teams ON players.team_id=teams.id WHERE first_name LIKE :searchterm OR last_name LIKE :searchterm OR teams.name LIKE :searchterm GROUP BY players.last_name";
    $prep_stmt=$conn->prepare($query);
    $prep_stmt->bindValue(":searchterm", $searchterm);

    $prep_stmt->execute();
    $players=$prep_stmt->fetchAll();

    $pos_q = "SELECT positions.name
    FROM players
    INNER JOIN player_position ON players.id=player_position.player_id
    INNER JOIN positions ON player_position.position_id=positions.id WHERE first_name LIKE :searchterm OR last_name LIKE :searchterm";
    $pos_stmt=$conn->prepare($pos_q);
    $pos_stmt->bindValue(":searchterm", $searchterm);

    $pos_stmt->execute();
    $player_pos=$pos_stmt->fetch();

    $count_stmt = "SELECT COUNT(players.last_name) as count FROM players INNER JOIN teams ON players.team_id=teams.id WHERE first_name LIKE :searchterm OR last_name LIKE :searchterm OR teams.name LIKE :searchterm";
    $count_stmt=$conn->prepare($count_stmt);
    $count_stmt->bindValue(":searchterm", $searchterm);

    $count_stmt->execute();
    $count=$count_stmt->fetch();
}

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>
        <header>
            <div class="header-inner">
                <img class="logo" src="img/nba-logo.png">
                <div class="logo-txt">NBA PLAYER SEARCH</div>
                <nav>
                    <ul>
                        <li><a href="index.php">Search</a></li>
                        <li><a href="design.php">Design</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class="search-container">
                <div class="search-div">
                    <form action="index.php" method="GET">
                        <input type="text" class="home-search" name="q" id="search" placeholder="Search by player, team or position">
                        <span class="adv-search">Advanced search</span>
                    </form>
                </div>
            </div>
            <?php if(isset($_GET['q'])){ ?>
            <div class="results-container">
                <div class="results">
                    <div class="results-header">
                        <span class="results-num"><?php echo $count["count"]; ?> results</span>
                        <div class="result-stat-header">
                            <span>PTS</span>
                            <span>REB</span>
                            <span>AST</span>
                            <span>BLK</span>
                        </div>
                    </div>
                    <?php
                        foreach($players as $player){
                            echo "<div class='result-player'>";
                            echo "<div class='result-player-name'><a href='details.php?id={$player["player_id"]}'>{$player["first_name"]} {$player["last_name"]}</a></div>";
                            echo "<div class='result-player-info'>#{$player["number"]} | <span></span> | {$player["abbreviation"]}</div>";
                            echo "<div class='result-player-stats'><span class='result-stat'>{$player["PTS"]}</span><span class='result-stat'>{$player["REB"]}</span><span class='result-stat'>{$player["AST"]}</span><span class='result-stat'>{$player["BLK"]}</span></div>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
            <?php } ?>
        </main>
        <script src="js/js.js"></script>
    </body>
</html>
