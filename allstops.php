<?php
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <!--Import Google Icon Font-->
        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

<body>
    <nav class="orange" role="navigation">
        <div class="nav-wrapper container">
          <a id="logo-container" href="index.html" class="brand-logo center">MiWave</a>
          <ul class="right hide-on-med-and-down">
            <li><a class="white-text" href="#alerts">Real Time</a></li>
            <li><a href="#hail">Hail</a></li>
            <li><a href="#alerts">Alerts</a></li>
          </ul>

          <ul id="nav-mobile" class="side-nav orange">

              <div class="container">
                  <img src="imgs/img1.png" alt="Logo" class="circle responsive-img">
              </div>
              <div class="divider"></div>
            <div class="container">
                <li><a class="white-text" href="allroutes.php">Real Time</a></li>
                <li><a class="white-text" href="#hail">Hail</a></li>
                <li><a class="white-text" href="#alerts">Alerts</a></li>
            </div>

          </ul>
          <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
        </div>
    </nav>

    <div class="container">
        <div class="section">
            <?php

                $_SESSION["route_id"] = $_GET["route_id"];
                $_SESSION["direction"] = $_GET["direction"];

            	try {
            	$dbh = new PDO("mysql:host=localhost;dbname=codeinthecity", "hackathon", "aubergine");
            	}
            	catch (Exception $e) {
            		die("<p>{$e->getMessage()}</p></body></html>");
            	}

            	$command = "select * from trips t inner join stop_times st on t.trip_id = st.trip_id inner join stops s on s.stop_id = st.stop_id where t.route_id = ? and t.trip_headsign = ?";

            	$stmt = $dbh->prepare($command);
                $userParams = array($_SESSION["route_id"], $_SESSION["direction"]);
            	$stmt->execute($userParams);

                echo "<h1 class='center-align'>All Stops</h1>";
            	echo "<table>";

            	while ($row = $stmt->fetch()) {

            		echo "<tr>";
            		echo "<td><a href='nextbus.php?stop_id=$row[stop_id]'>$row[stop_name]</a></td>";
            		echo "</tr>";

            	}

                echo "</table>";
            ?>
        </div>
    </div>


<footer class="page-footer blue lighten-1">
    <div class="footer-copyright blue darken-1">
    <div class="container">
      Data Sources from <a class="white-text" href="http://m.miway.ca">MiWAY</a>
    </div>
</div>
</footer>

<!-- Scripts -->
<!--Import jQuery before materialize.js-->
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
<script type="text/javascript" src="/script/basejs.js"></script>
</body>
</html>
