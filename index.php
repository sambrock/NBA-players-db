<?php
// More PHP magic here
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <header>
            <div class="header-inner">
                <div class="logo">
                    <img src="img/nba-logo.png" height="100">
                </div>
                <nav>
                    <ul>
                        <li><a href="index.php">Search</a></li>
                        <li><a href="index.php">Design</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class="search-div">
                <form action="search.php" method="get">
                    <input type="text" class="home-search" name="q" id="search">
<!--                    <input type="submit" name="submitBtn">-->
                </form>
            </div>
        </main>
    </body>
</html>
