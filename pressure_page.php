<?php
/*
    __________________________________________________________________________
   |                                                                          |
   |                    MONA LISA SECURITY - PRESSURE                         |
   |                                                                          |
   |    Author            :   P. GARREAU, M. JALES                            |
   |    Status            :   Under Development                               |
   |    Last Modification :   04/11/2022                                      |
   |    Project           :   IoT PROJECT                                     |
   |                                                                          |
   |__________________________________________________________________________|

*/
    include('./backend/variables.php');

    $daysOfTheWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    $monthOfTheYear = array('Zebre', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    // Get Last Data
    $getLastData = 'SELECT * FROM data ORDER BY date_time DESC LIMIT 1';
    $requestGetLastData = $bddConn->query($getLastData);
    $outputGetLastData = $requestGetLastData->fetch();

    // Get Min acceleration_x of the day
    $dayX = date('Y-m-d');
    $dayX_plus_1 = date('Y-m-d', strtotime('+1 day'));
    $getXMinDaily = 'SELECT acceleration_x FROM data WHERE date_time >= "'.$dayX.'" AND date_time < "'.$dayX_plus_1.'" ORDER BY acceleration_x ASC LIMIT 1';
    $requestGetXMinDaily = $bddConn->query($getXMinDaily);
    $outputGetXMinDaily = $requestGetXMinDaily->fetch();

    // Get Average acceleration_x of the day
    $getXAvgDaily = 'SELECT AVG(acceleration_x) AS "avg_acceleration_x" FROM data WHERE date_time >= "'.$dayX.'" AND date_time < "'.$dayX_plus_1.'"';
    $requestGetXAvgDaily = $bddConn->query($getXAvgDaily);
    $outputGetXAvgDaily = $requestGetXAvgDaily->fetch();

    // Get Max acceleration_x of the day
    $getXMaxDaily = 'SELECT acceleration_x FROM data WHERE date_time >= "'.$dayX.'" AND date_time < "'.$dayX_plus_1.'" ORDER BY acceleration_x DESC LIMIT 1';
    $requestGetXMaxDaily = $bddConn->query($getXMaxDaily);
    $outputGetXMaxDaily = $requestGetXMaxDaily->fetch();

    // Get Min acceleration_y of the day
    $dayY = date('Y-m-d');
    $dayY_plus_1 = date('Y-m-d', strtotime('+1 day'));
    $getYMinDaily = 'SELECT acceleration_y FROM data WHERE date_time >= "'.$dayY.'" AND date_time < "'.$dayY_plus_1.'" ORDER BY acceleration_y ASC LIMIT 1';
    $requestGetYMinDaily = $bddConn->query($getYMinDaily);
    $outputGetYMinDaily = $requestGetYMinDaily->fetch();

    // Get Average acceleration_y of the day
    $getYAvgDaily = 'SELECT AVG(acceleration_y) AS "avg_acceleration_y" FROM data WHERE date_time >= "'.$dayY.'" AND date_time < "'.$dayY_plus_1.'"';
    $requestGetYAvgDaily = $bddConn->query($getYAvgDaily);
    $outputGetYAvgDaily = $requestGetYAvgDaily->fetch();

    // Get Max acceleration_y of the day
    $getYMaxDaily = 'SELECT acceleration_y FROM data WHERE date_time >= "'.$dayY.'" AND date_time < "'.$dayY_plus_1.'" ORDER BY acceleration_y DESC LIMIT 1';
    $requestGetYMaxDaily = $bddConn->query($getYMaxDaily);
    $outputGetYMaxDaily = $requestGetYMaxDaily->fetch();

    // Get Min acceleration_z of the day
    $dayZ = date('Y-m-d');
    $dayZ_plus_1 = date('Y-m-d', strtotime('+1 day'));
    $getZMinDaily = 'SELECT acceleration_z FROM data WHERE date_time >= "'.$dayZ.'" AND date_time < "'.$dayZ_plus_1.'" ORDER BY acceleration_z ASC LIMIT 1';
    $requestGetZMinDaily = $bddConn->query($getZMinDaily);
    $outputGetZMinDaily = $requestGetZMinDaily->fetch();

    // Get Average acceleration_z of the day
    $getZAvgDaily = 'SELECT AVG(acceleration_z) AS "avg_acceleration_z" FROM data WHERE date_time >= "'.$dayZ.'" AND date_time < "'.$dayZ_plus_1.'"';
    $requestGetZAvgDaily = $bddConn->query($getZAvgDaily);
    $outputGetZAvgDaily = $requestGetZAvgDaily->fetch();

    // Get Max acceleration_z of the day
    $getZMaxDaily = 'SELECT acceleration_z FROM data WHERE date_time >= "'.$dayZ.'" AND date_time < "'.$dayZ_plus_1.'" ORDER BY acceleration_z DESC LIMIT 1';
    $requestGetZMaxDaily = $bddConn->query($getZMaxDaily);
    $outputGetZMaxDaily = $requestGetZMaxDaily->fetch();

    // Get first day
    $getFirstDay = 'SELECT * FROM data ORDER BY date_time DESC LIMIT 1';
    $requestGetFirstDay = $bddConn->query($getFirstDay);
    $outputGetFirstDay = $requestGetFirstDay->fetch();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="css/pressure.css" />
        <link rel="icon" type="image/x-icon" href="assets/images/monaLisa_icon.ico" />
        <title>Sécurité de Mona Lisa - Pressure</title>
        <script type="text/javascript" src="js/jQuery.js"></script>
        <script src="node_modules/chart.js/dist/chart.js"></script>
        <script type="text/javascript">
            var datesHistoric = Array();
            var chartHourlyHum = Array(Array());
        </script>
    </head>
    <body>
        <section class="leftContainer">
            <section class="menuContainer">
                <div class="fakeItemContainer"></div>
                <div class="menuItemContainer">
                    <img src="assets/images/home_icon.png" class="menuIcon" id="homeIcon"/>
                </div>
                <div class="menuItemContainer">
                    <img src="assets/images/temperature_icon.png" class="menuIcon"/>
                </div>
                <div class="menuItemContainer">
                    <img src="assets/images/humidity_icon.png" class="menuIcon"/>
                </div>
                <div class="menuItemContainer">
                    <img src="assets/images/pressure_icon.png" class="menuIcon"/>
                </div>
                <div class="fakeItemContainer"></div>
            </section>
            <section class="logoMinesContainer">
                <img src="assets/images/logo_mines.png" class="logoMines" id="logoMines"/>
            </section>
        </section>
        <section class="topContainer">
            <div class="logoWeatherStationContainer">
                <img src="assets/images/monoLisaSecurity_logo.png" class="logoWeatherStation" id="logoWeatherStation"/>
            </div>
            <div class="titleTopContainer">PRESSURE</div>
            <div class="contributorsContainer" id="contributorsContainer">
                <img src="assets/images/photo_mickael.png" class="pdpImage" id="imageMickael"/>
                <img src="assets/images/photo_pierre.png" class="pdpImage" id="imagePierre"/>
                <div class="contributors">Contributors</div>
            </div>
        </section>
        <section class="bodyContainer">
            <section class="tempBodyContainer">
                <section class="leftBodyContainer">
                    <section class="leftTopBodyContainer">
                        <div class="leftTopLeftContainer">
                            <section class="iconDetailedPageContainer">
                                <img src="assets/images/pressure_icon_colored.png" class="iconDetailedPage"/>
                            </section>
                            <section class="dataItemDetailedPageContainer">
                                <div class="celsiusData" id="pressureValue"><?php echo number_format($outputGetLastData['acceleration_X'], 2); ?>%</div>
                            </section>
                        </div>
                        <section class="dateTimeDetailedPageContainer">
                            <div class="dateSmallContainer" id="dateContainer">Monday 19 September</div>
                            <div class="timeSmallContainer" id="timeContainer">10:43</div>
                        </section>
                    </section>
                    <section class="leftBottomBodyContainer">
                        <div class="graphDetailedPageSubContainer">
                            <?php
                                $dayX_x = new DateTime($outputGetFirstDay['date_time']);
                                $dayX_x_1 = clone $dayX_x;
                                $dayX_x_1->modify('+1 day');
                                $getXAvgDay = 'SELECT AVG(acceleration_x) AS "avg_acceleration_x" FROM data WHERE date_time >= "'.$dayX_x->format('Y-m-d').'" AND date_time < "'.$dayX_x_1->format('Y-m-d').'"';
                                $requestGetXAvgDay = $bddConn->query($getXAvgDay);
                                $outputGetXAvgDay = $requestGetXAvgDay->fetch();
                                $nb_days = 0;
                                while ($outputGetXAvgDay['avg_acceleration_x'] != NULL)
                                {
                                    echo '<canvas id="chart_detailed_hum'.$nb_days.'"></canvas>';
                                    $dayX_x->modify('-1 day');
                                    $dayX_x_1->modify('-1 day');
                                    $getXAvgDay = 'SELECT AVG(acceleration_x) AS "avg_acceleration_x" FROM data WHERE date_time >= "'.$dayX_x->format('Y-m-d').'" AND date_time < "'.$dayX_x_1->format('Y-m-d').'"';
                                    $requestGetXAvgDay = $bddConn->query($getXAvgDay);
                                    $outputGetXAvgDay = $requestGetXAvgDay->fetch();
                                    
                                    ?>
                                    <script type="text/javascript">
                                        var nb_days_1 = <?php echo json_encode($nb_days); ?>;
                                        document.getElementById('chart_detailed_hum' + nb_days_1).style.display = (nb_days_1 > 0) ? 'none' : 'block';
                                        chartHourlyHum.push(Array());
                                    </script>
                                    <?php
                                    $nb_days++;
                                }
                            ?>
                        </div>
                    </section>
                    <section class="leftStatsBodyContainer">
                        <div>Minimum : <?php echo number_format($outputGetXMinDaily['acceleration_x'], 2); ?>%</div>
                        <div>Averaged : <?php echo number_format($outputGetXAvgDaily['acceleration_x'], 2); ?>%</div>
                        <div>Maximum : <?php echo number_format($outputGetXMaxDaily['acceleration_x'], 2); ?>%</div>
                    </section>
                </section>
                <section class="rightBodyContainer">
                    <section class="titleHistoricContainer">Past days</section>
                    <section class="contentHistoricContainer">
                    <?php
                        $dayX_x = new DateTime($outputGetFirstDay['date_time']);
                        $dayX_x_1 = clone $dayX_x;
                        $dayX_x_1->modify('+1 day');
                        $getXAvgDay = 'SELECT AVG(acceleration_x) AS "avg_acceleration_x" FROM data WHERE date_time >= "'.$dayX_x->format('Y-m-d').'" AND date_time < "'.$dayX_x_1->format('Y-m-d').'"';
                        $requestGetXAvgDay = $bddConn->query($getXAvgDay);
                        $outputGetXAvgDay = $requestGetXAvgDay->fetch();
                        $nb_days = 0;
                        while ($outputGetXAvgDay['avg_acceleration_x'] != NULL)
                        {
                    ?>
                        <div class="itemHistoricContainer">
                            <div class="dayHistoricContainer"><?php echo $daysOfTheWeek[date("w", strtotime($dayX_x->format('Y-m-d')))] . ' ' . $dayX_x->format('d') . ' ' . $monthOfTheYear[intval($dayX_x->format('m'))]; ?></div>
                            <div><?php echo number_format($outputGetXAvgDay['avg_acceleration_x'], 2); ?>%</div>
                            <div><img src="assets/images/high_pressure.png" class="iconHistoric" id="pressureHistoricIcon<?php echo $nb_days; ?>"/></div>
                        </div>
                        <script type="text/javascript">
                            datesHistoric.push(<?php echo json_encode($dayX_x->format('Y-m-d')); ?>)
                            var nb_days = <?php echo json_encode($nb_days); ?>;
                            var valueHum = <?php echo json_encode($outputGetXAvgDay['avg_acceleration_x']); ?>;
                            document.getElementById("pressureHistoricIcon" + nb_days).src = (valueHum > 50) ? "assets/images/high_pressure.png" : "assets/images/high_pressure.png";
                        </script>
                    <?php
                            $dayX_x->modify('-1 day');
                            $dayX_x_1->modify('-1 day');
                            $getXAvgDay = 'SELECT AVG(acceleration_x) AS "avg_acceleration_x" FROM data WHERE date_time >= "'.$dayX_x->format('Y-m-d').'" AND date_time < "'.$dayX_x_1->format('Y-m-d').'"';
                            $requestGetXAvgDay = $bddConn->query($getXAvgDay);
                            $outputGetXAvgDay = $requestGetXAvgDay->fetch();
                            $nb_days++;
                        }
                    ?>
                    </section>
                </section>
            </section>
        </section>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/pressure.js"></script>
        <script type="text/javascript" src="js/dimensions.js"></script>
        <script type="text/javascript">
            var today = new Date();
            var dayNumber = (today.getDate() < 10) ? '0' + today.getDate() : today.getDate();
            var realMonth = parseInt(today.getMonth()) + 1;
            var monthNumber = (realMonth < 10) ? '0' + realMonth : realMonth;
            var chartNbData = 0;
            var urlRequest = urlData + '?data=pressure&day=' + today.getFullYear() + '-' + monthNumber + '-' + dayNumber;
            $.ajax({
                type: 'GET',
                url: urlRequest,
                success: function(data) {
                    data = JSON.parse(data);
                    for (var i = 0; i < 24; i++)
                    {
                        chartNbData++;
                        if (data[i] != 0)
                        {
                            chartHourlyHum[0].push(parseFloat(data[i]));
                        }
                        else
                        {
                            chartHourlyHum[0].push(null);
                        }
                    }
                }
            });
            setTimeout(function() {
                const ctx_detailed_hum = document.getElementById('chart_detailed_hum0').getContext('2d');
                const chartDetailedPressure = new Chart(ctx_detailed_hum, {
                type: 'line',
                data: {
                    labels: timeOfTheDay,
                    datasets: [{
                        label: '',
                        data: chartHourlyHum[0],
                        fill: false,
                        borderColor: colors[5],
                        pointBackgroundColor: colors[5],
                        tension: 0.2
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: colors[0],
                                borderColor: colors[0]
                            },
                            ticks: {
                                color: colors[0],
                            }
                        },
                        y: {
                            grid: {
                                color: colors[0],
                                borderColor: colors[0]
                            },
                            ticks: {
                                color: colors[0],
                            }
                        }
                    }
                }
            });
            }, 1000);

            var itemHistoricContainer = document.getElementsByClassName("itemHistoricContainer");
            itemHistoricContainer[0].style.backgroundColor = colors[3];

            for (var i = 0; i < itemHistoricContainer.length; i++)
            {
                itemHistoricContainer[i].addEventListener("click", (function(arg) {
                    return function() {
                        for (var j = 0; j < itemHistoricContainer.length; j++)
                        {
                            itemHistoricContainer[j].style.backgroundColor = (arg != j) ? 'transparent' : colors[3];
                        }
                        urlRequest = urlData + '?data=pressure&day=' + datesHistoric[arg];
                        $.ajax({
                            type: 'GET',
                            url: urlRequest,
                            success: function(data) {
                                data = JSON.parse(data);
                                for (var i = 0; i < 24; i++)
                                {
                                    chartNbData++;
                                    if (data[i] != 0)
                                    {
                                        chartHourlyHum[arg].push(parseFloat(data[i]));
                                    }
                                    else
                                    {
                                        chartHourlyHum[arg].push(null);
                                    }
                                }
                                document.getElementById('chart_detailed_hum' + arg).style.display = 'block';
                                for (var j = 0; j < itemHistoricContainer.length; j++)
                                {
                                    document.getElementById('chart_detailed_hum' + j).style.display = (arg != j) ? 'none' : 'block';
                                }
                                const ctx_detailed_hum = document.getElementById('chart_detailed_hum' + arg).getContext('2d');
                                const chartDetailedPressure = new Chart(ctx_detailed_hum, {
                                type: 'line',
                                data: {
                                    labels: timeOfTheDay,
                                    datasets: [{
                                        label: '',
                                        data: chartHourlyHum[arg],
                                        fill: false,
                                        borderColor: colors[5],
                                        pointBackgroundColor: colors[5],
                                        tension: 0.2
                                    }]
                                },
                                options: {
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    },
                                    scales: {
                                        x: {
                                            grid: {
                                                color: colors[0],
                                                borderColor: colors[0]
                                            },
                                            ticks: {
                                                color: colors[0],
                                            }
                                        },
                                        y: {
                                            grid: {
                                                color: colors[0],
                                                borderColor: colors[0]
                                            },
                                            ticks: {
                                                color: colors[0],
                                            }
                                        }
                                    }
                                }
                            });
                            }
                        });
                    };
                }) (i));
            }
        </script>
    </body>
</html>