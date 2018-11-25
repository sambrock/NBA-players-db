<?php
try{
    $conn = new PDO('mysql:host=localhost;dbname=u1663363', 'root', '');
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch (PDOException $exception){
    echo "Oh no, there was a problem" . $exception->getMessage();
}

if(isset($_GET['q'])){
    $searchterm=$_GET["q"];
}
if(isset($_GET['t'])){
    $team=$_GET["t"];
}
if(isset($_GET['p'])){
    $position=$_GET["p"];
}

if(!isset($_GET["page"])){
    $page = 1;
}else{
    $page = $_GET["page"];
}

$limit = 8;
$offset = ($page-1)*$limit;

$query = "SELECT DISTINCT players.first_name, players.last_name, players.id as player_id, teams.id as team_id, players.number, teams.abbreviation,
ROUND(SUM(points / games), 1) as PTS,
ROUND(SUM((offensive_rebounds + defensive_rebounds) / games),1) as REB,
ROUND(SUM(assists / games), 1) as AST, ROUND(SUM(blocks / games), 1) as BLK,
(
SELECT count(DISTINCT players.id) FROM players INNER JOIN player_position ON players.id=player_position.player_id
INNER JOIN positions ON player_position.position_id=positions.id INNER JOIN statistics ON players.id=statistics.player_id INNER JOIN teams ON players.team_id=teams.id
WHERE ((first_name LIKE :searchterm OR last_name LIKE :searchterm OR CONCAT(first_name,' ', last_name) LIKE :searchterm) OR :searchterm IS NULL)
AND (teams.abbreviation = :team OR :team IS NULL)
AND (positions.name = :position OR :position IS NULL)
) as num_of_results
FROM players INNER JOIN player_position ON players.id=player_position.player_id
INNER JOIN positions ON player_position.position_id=positions.id INNER JOIN statistics ON players.id=statistics.player_id INNER JOIN teams ON players.team_id=teams.id
WHERE ((first_name LIKE :searchterm OR last_name LIKE :searchterm OR CONCAT(first_name,' ', last_name) LIKE :searchterm) OR :searchterm IS NULL)
AND (teams.abbreviation = :team OR :team IS NULL)
AND (positions.name = :position OR :position IS NULL)
GROUP BY players.id, positions.name LIMIT :limit OFFSET :offset";
$prep_stmt=$conn->prepare($query);
$prep_stmt->bindValue(':searchterm', '%'.$searchterm.'%');
$prep_stmt->bindValue(':team', $team);
$prep_stmt->bindValue(':position', $position);
$prep_stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
$prep_stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

$prep_stmt->execute();
$players=$prep_stmt->fetchAll();

$pos_q = "SELECT players.id as player_id, positions.name
FROM players INNER JOIN player_position ON players.id=player_position.player_id INNER JOIN positions ON player_position.position_id=positions.id";
$pos_stmt=$conn->prepare($pos_q);

$pos_stmt->execute();
$positions=$pos_stmt->fetchAll();

$count = ($players[0]["num_of_results"]);
if($count == null){
    $count = 0;
}
$number_of_pages = ceil($count/$limit);

?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php if(isset($_GET['q'])){ echo "{$searchterm} - "; }  ?>NBA Player Search</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
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
            <div class="search-container">
                <form action="index.php" method="GET" id="search-form">
                    <div class="search-box">
                        <input type="text" class="form-control" name="q" id="search" placeholder="Search by player name" value="<?php if(isset($_GET['q'])){ echo "$searchterm"; } ?>"><button type="sumbit" class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="filters">
                        <div class="drop-down-holder">
                            <div class="drop-down" id="team"><?php if(isset($_GET['t'])){ echo $team; } else { echo "Team";}; ?></div>
                            <div class="drop-down-items" id="items-team">
                                <ul>
                                    <li data-value="ATL">Atlanta Hawks</li>
                                    <li data-value="BOS">Boston Celtics</li>
                                    <li data-value="BKN">Brooklyn Nets</li>
                                    <li data-value="CHA">Charlotte Hornets</li>
                                    <li data-value="CHI">Chicago Bulls</li>
                                    <li data-value="CLE">Cleaveland Cavaliers</li>
                                    <li data-value="DAL">Dallas Mavericks</li>
                                    <li data-value="DEN">Denver Nuggets</li>
                                    <li data-value="DET">Detroit Pistons</li>
                                    <li data-value="GSW">Golden State Warriors</li>
                                    <li data-value="HOU">Houston Rockets</li>
                                    <li data-value="IND">Indiana Pacers</li>
                                    <li data-value="LAC">Los Angeles Clippers</li>
                                    <li data-value="LAL">Los Angeles Lakers</li>
                                    <li data-value="MEM">Memphis Grizzlies</li>
                                    <li data-value="MIA">Miami Heat</li>
                                    <li data-value="MIL">Milwaukee Bucks</li>
                                    <li data-value="MIN">Minnesota Timberwolves</li>
                                    <li data-value="NOP">New Orleans Pelicans</li>
                                    <li data-value="NYK">New York Knicks</li>
                                    <li data-value="OKC">Oklahoma City Thunder</li>
                                    <li data-value="ORL">Orlando Magic</li>
                                    <li data-value="PHI">Philadelphia 76ers</li>
                                    <li data-value="PHX">Phoenix Suns</li>
                                    <li data-value="POR">Portland Trail Blazers</li>
                                    <li data-value="SAC">Sacramento Kings</li>
                                    <li data-value="SAS">San Antonio Spurs</li>
                                    <li data-value="TOR">Toronto Raptors</li>
                                    <li data-value="UTA">Utah Jazz</li>
                                    <li data-value="WAS">Washington Wizards</li>
                                </ul>
                            </div>
                            <input type="hidden" name="t" value="<?php if(isset($_GET['t'])){ echo $team; }?>" id="team-select" class="form-control">
                        </div>
                        <div class="drop-down-holder">
                            <div class="drop-down" id="position"><?php if(isset($_GET['p'])){ echo $position; } else { echo "Position";}; ?></div>
                            <div class="drop-down-items" id="items-position">
                                <ul>
                                    <li data-value="G">Guard</li>
                                    <li data-value="F">Forward</li>
                                    <li data-value="C">Center</li>
                                </ul>
                            </div>
                        </div>

                        <input type="hidden" name="p" value="<?php if(isset($_GET['p'])){ echo $position; }?>" id="position-select" class="form-control">
                        <input type="hidden" name="page" value="1">
                    </div>
                </form>
            </div>
            <?php if(isset($_GET['q']) || isset($_GET['t']) || isset($_GET['p'])){ ?>
            <div class="results-container">
                <div class="results">
                    <div class="results-header">
                        <span class="results-num"><?php echo $count; ?> results</span>
                        <div class="result-stat-header">
                            <span><abbr title="Points">PTS</abbr></span>
                            <span><abbr title="Rebonds">REB</abbr></span>
                            <span><abbr title="Assists">AST</abbr></span>
                            <span><abbr title="Blocks">BLK</abbr></span>
                        </div>
                    </div>
                    <?php
                                                                                  if($players){
                                                                                      foreach($players as $player){
                                                                                          echo "<div class='result-player'>";
                                                                                          echo "<div class='result-player-name'><a href='details.php?id={$player["player_id"]}'>{$player["first_name"]} {$player["last_name"]}</a></div>";
                                                                                          echo "<div class='result-player-info'>";
                                                                                          echo "<img src='img/teams/{$player["abbreviation"]}.png'> ";
                                                                                          echo "<span> | #{$player["number"]} | </span>";
                                                                                          foreach($positions as $pos){
                                                                                              if($pos["player_id"] == $player["player_id"]){
                                                                                                  echo "<span>{$pos["name"]}</span>";
                                                                                              }
                                                                                          }
                                                                                          echo "</div>";
                                                                                          echo "<div class='result-player-stats'><span>{$player["PTS"]}</span><span>{$player["REB"]}</span><span>{$player["AST"]}</span><span>{$player["BLK"]}</span></div>";
                                                                                          echo "</div>";
                                                                                      }
                    ?>
                </div>
            </div>
            <div class="pagination">
                <ul>
                    <?php
                                                                                      if($number_of_pages!=1){
                                                                                          if($page==1){
                                                                                              echo "<li><i class='fas fa-chevron-left'></i></li>";
                                                                                          }else{
                                                                                              echo "<li><a href='index.php?".str_replace('&page='.$_GET["page"], '&page='.($page-1), $_SERVER['QUERY_STRING'])."'><i class='fas fa-chevron-left'></i></a></li>";
                                                                                          }
                                                                                          for ($page_num=1;$page_num<=$number_of_pages;$page_num++){
                                                                                              if($page_num == $page){
                                                                                                  echo "<li class='page active'>".$page."</li>";
                                                                                              }else{
                                                                                                  echo"<li><a href='index.php?";
                                                                                                  if(isset($_GET['q'])){
                                                                                                      echo "q=".$searchterm."&";
                                                                                                  }
                                                                                                  if(isset($_GET['t'])){
                                                                                                      echo "t=".$team."&";
                                                                                                  }
                                                                                                  if(isset($_GET['p'])){
                                                                                                      echo "p=".$position."&";
                                                                                                  }
                                                                                                  echo "page=".$page_num."'>".$page_num."</a></li>";
                                                                                              }
                                                                                          }
                                                                                          if($page==$number_of_pages){
                                                                                              echo "<li><i class='fas fa-chevron-right'></i></li>";
                                                                                          }else{
                                                                                              echo "<li><a href='index.php?".str_replace('&page='.$_GET["page"], '&page='.($page+1), $_SERVER['QUERY_STRING'])."'><i class='fas fa-chevron-right'></i></a></li>";
                                                                                          }
                                                                                      }
                    ?>
                </ul>
            </div>
            <?php } else { echo "<div class='no-results'>Your search returned no results. Please adjust your search.</div>"; } }?>
        </main>
        <script src="js/js.js"></script>
    </body>
</html>
