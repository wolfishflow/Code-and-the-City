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

    <h1 class='center-align'>Next bus</h1>

    <?php

                $_SESSION["stop_id"] = $_GET["stop_id"];

            	try {
            	$dbh = new PDO("mysql:host=localhost;dbname=codeinthecity", "hackathon", "aubergine");
            	}
            	catch (Exception $e) {
            		die("<p>{$e->getMessage()}</p></body></html>");
            	}

            	$command = "select stop_lat, stop_lon from stops WHERE stop_id = ?";

            	$stmt = $dbh->prepare($command);
                $userParams = array($_SESSION["stop_id"]);
            	$stmt->execute($userParams);


            	while ($row = $stmt->fetch()) {
                    $x = $row["stop_lat"];
                    $y = $row["stop_lon"];
            	}
    ?>

    <!-- Google API here -->

      <div id="output"></div>

    <div id="map"></div>
    <script>
      function initMap() {
        var bounds = new google.maps.LatLngBounds;
        var markersArray = [];



        var originlat = <?php echo $x ?>;
        var originlon = <?php echo $y ?>;



        var origin1 = {lat: parseFloat(originlat), lng: parseFloat(originlon)};
        var destinationB = {lat: 43.5886571, lng:-79.6462263};

        var destinationIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=D|FF0000|000000';
        var originIcon = 'https://chart.googleapis.com/chart?' +
            'chst=d_map_pin_letter&chld=O|FFFF00|000000';


        var geocoder = new google.maps.Geocoder;

        var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
          origins: [origin1],
          destinations: [destinationB],
          travelMode: google.maps.TravelMode.TRANSIT,
          unitSystem: google.maps.UnitSystem.METRIC,
          avoidHighways: false,
          avoidTolls: true
        }, function(response, status) {
          if (status !== google.maps.DistanceMatrixStatus.OK) {
            alert('Error was: ' + status);
          } else {
            var originList = response.originAddresses;
            var destinationList = response.destinationAddresses;
            var outputDiv = document.getElementById('output');
            outputDiv.innerHTML = '';
            deleteMarkers(markersArray);

            var showGeocodedAddressOnMap = function(asDestination) {
              var icon = asDestination ? destinationIcon : originIcon;
              return function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                  map.fitBounds(bounds.extend(results[0].geometry.location));
                  markersArray.push(new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    icon: icon
                  }));
                } else {
                  alert('Geocode was not successful due to: ' + status);
                }
              };
            };

            for (var i = 0; i < originList.length; i++) {
              var results = response.rows[i].elements;
              geocoder.geocode({'address': originList[i]},
                  showGeocodedAddressOnMap(false));
              for (var j = 0; j < results.length; j++) {
                geocoder.geocode({'address': destinationList[j]},
                    showGeocodedAddressOnMap(true));
                outputDiv.innerHTML +=
                    "<b><br><center>" +originList[i] + '</b>'+ ' TO ' +  "<b>" + destinationList[j] + "</b>"+
                    '<br>Distance: <b>' + results[j].distance.text + '</b> IN <b>' +
                    results[j].duration.text + '</b></center><br>' ;
              }
            }
          }
        });
      }

      function deleteMarkers(markersArray) {
        for (var i = 0; i < markersArray.length; i++) {
          markersArray[i].setMap(null);
        }
        markersArray = [];
      }


    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAtwhgKL0AT-8yTV0AArXOGsWILCqnc3TU&callback=initMap">
    </script>

    <div class="container">
        <div class="section">
            <div id="wave_button" class="btn-large center-align blue" onclick="wave()" style="width: 100%;">
                Wave!
            </div>
            <div class="center-align">


            <div id="spinner" class="preloader-wrapper active" style="visibility:hidden;">
                <div class="spinner-layer spinner-red-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
              </div>
              </div>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <?php

                $_SESSION["stop_id"] = $_GET["stop_id"];

            	try {
            	$dbh = new PDO("mysql:host=localhost;dbname=codeinthecity", "hackathon", "aubergine");
            	}
            	catch (Exception $e) {
            		die("<p>{$e->getMessage()}</p></body></html>");
            	}

            	$command = "select * from stop_times st inner join stops s on st.stop_id = s.stop_id inner join trips t on t.trip_id = st.trip_id where st.stop_id = ? and t.route_id = ? order by st.arrival_time";

            	$stmt = $dbh->prepare($command);
                $userParams = array($_SESSION["stop_id"], $_SESSION["route_id"]);
            	$stmt->execute($userParams);

                $time = time();

            	echo "<ul class='collapsible' data-collapsible='accordion'><li><div class='collapsible-header'>Previous times</div>";

            	while ($row = $stmt->fetch()) {

                    if ($time > strtotime($row[arrival_time])) {
                        echo "<div class='collapsible-body' style='color: red'>" . $row[arrival_time] . "</div>";
                    }

                    //else {
                    //    echo "<td>" . $row[arrival_time] . "</td>";
                    //}
            		//echo "</tr>";

                    //$_SESSION["lon"] = $row["stop_lon"];
                    //$_SESSION["lat"] = $row["stop_lat"];
            	}
                echo "</li></ul>";

                $stmt->execute($userParams);
                echo "<ul class='collapsible' data-collapsible='accordion'><li><div class='collapsible-header'>Upcoming arrivals</div>";

            	while ($row = $stmt->fetch()) {

                    if ($time <= strtotime($row[arrival_time])) {
                        echo "<div class='collapsible-body'>" . $row[arrival_time] . "</div>";
                    }

                    //else {
                    //    echo "<td>" . $row[arrival_time] . "</td>";
                    //}
            		//echo "</tr>";

                    //$_SESSION["lon"] = $row["stop_lon"];
                    //$_SESSION["lat"] = $row["stop_lat"];
            	}
                echo "</li></ul>";

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
<script type="text/javascript" src="js/wave.js"></script>
</body>
</html>
