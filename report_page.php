<?php
/*
    __________________________________________________________________________
   |                                                                          |
   |                    MONA LISA SECURITY - REPORT                           |
   |                                                                          |
   |    Author            :   P. GARREAU, M. JALES                            |
   |    Status            :   Under Development                               |
   |    Last Modification :   04/11/2022                                      |
   |    Project           :   IoT PROJECT                                     |
   |                                                                          |
   |__________________________________________________________________________|

*/
    include('./backend/variables.php');
    /*reports
        id_report
        data_time_report
        data_time_rearmement
        temperature
        humidity
        acceleration*/

    $daysOfTheWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    $monthOfTheYear = array('Zebre', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    // Get number of Reports
    $getNbReports = "SELECT COUNT(*) AS 'nb_reports' FROM reports";
    $requestGetNbReports = $bddConn->query($getNbReports);
    $outputGetNbReports = $requestGetNbReports->fetch();

    // Get Last Data
    $getLastData = 'SELECT * FROM reports ORDER BY date_time_report DESC LIMIT 1';
    $requestGetLastData = $bddConn->query($getLastData);
    $outputGetLastData = $requestGetLastData->fetch();

    // Get Min of the day
    $day = date('Y-m-d');
    $day_plus_1 = date('Y-m-d', strtotime('+1 day'));
    $getMinDaily = 'SELECT humidity FROM data WHERE date_time >= "'.$day.'" AND date_time < "'.$day_plus_1.'" ORDER BY humidity ASC LIMIT 1';
    $requestGetMinDaily = $bddConn->query($getMinDaily);
    $outputGetMinDaily = $requestGetMinDaily->fetch();

    // Get Average of the day
    $getAvgDaily = 'SELECT AVG(humidity) AS "avg_humidity" FROM data WHERE date_time >= "'.$day.'" AND date_time < "'.$day_plus_1.'"';
    $requestGetAvgDaily = $bddConn->query($getAvgDaily);
    $outputGetAvgDaily = $requestGetAvgDaily->fetch();

    // Get Max of the day
    $getMaxDaily = 'SELECT humidity FROM data WHERE date_time >= "'.$day.'" AND date_time < "'.$day_plus_1.'" ORDER BY humidity DESC LIMIT 1';
    $requestGetMaxDaily = $bddConn->query($getMaxDaily);
    $outputGetMaxDaily = $requestGetMaxDaily->fetch();

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
        <link rel="stylesheet" href="css/report.css" />
        <link rel="icon" type="image/x-icon" href="assets/images/monaLisa_icon.ico" />
        <title>Sécurité de Mona Lisa - Report</title>
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
                    <img src="assets/images/report_icon.png" class="menuIcon"/>
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
            <div class="titleTopContainer">REPORT</div>
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
                                <img src="assets/images/report_icon_colored.png" class="iconDetailedPage"/>
                            </section>
                            <section class="dataItemDetailedPageContainer">
                                <div class="celsiusData" id="reportValue"><?php echo $outputGetNbReports['nb_reports']; ?></div>
                            </section>
                        </div>
                        <section class="dateTimeDetailedPageContainer">
                            <div class="dateSmallContainer" id="dateContainer">Monday 19 September</div>
                            <div class="timeSmallContainer" id="timeContainer">10:43</div>
                        </section>
                    </section>
                    <section class="leftBottomBodyContainer">
                        <h1>Meilleur Consommateur</h1>
                        <div class="productsHeaderMeilleurConsoOverContainer"><div class="productHeaderMeilleurConsoContainer" id="header_meilleur_consommateur"><div class="nameMeilleurConsoContainer">Cotisant</div><div class="consoMeilleurConsoContainer">Total</div></div></div>';
                        <div class="meilleurConsosContainer" id="products_meilleur_consommateur">
                           <?php/*
                                $day_x = new DateTime($outputGetFirstDay['date_time']);
                                $day_x_1 = clone $day_x;
                                $day_x_1->modify('+1 day');
                                $getAvgDay = 'SELECT AVG(humidity) AS "avg_humidity" FROM data WHERE date_time >= "'.$day_x->format('Y-m-d').'" AND date_time < "'.$day_x_1->format('Y-m-d').'"';
                                $requestGetAvgDay = $bddConn->query($getAvgDay);
                                $outputGetAvgDay = $requestGetAvgDay->fetch();
                                $nb_days = 0;
                                while ($outputGetAvgDay['avg_humidity'] != NULL)
                                {
                                    echo '<canvas id="chart_detailed_hum'.$nb_days.'"></canvas>';
                                    $day_x->modify('-1 day');
                                    $day_x_1->modify('-1 day');
                                    $getAvgDay = 'SELECT AVG(humidity) AS "avg_humidity" FROM data WHERE date_time >= "'.$day_x->format('Y-m-d').'" AND date_time < "'.$day_x_1->format('Y-m-d').'"';
                                    $requestGetAvgDay = $bddConn->query($getAvgDay);
                                    $outputGetAvgDay = $requestGetAvgDay->fetch();
                                    
                                    ?>
                                    <script type="text/javascript">
                                        var nb_days_1 = <?php echo json_encode($nb_days); ?>;
                                        document.getElementById('chart_detailed_hum' + nb_days_1).style.display = (nb_days_1 > 0) ? 'none' : 'block';
                                        chartHourlyHum.push(Array());
                                    </script>
                                    <?php
                                    $nb_days++;
                                }*/
                            ?>
                    </section>
                </section>
                <section class="rightBodyContainer">
                    <section class="titleHistoricContainer">Past days</section>
                    <section class="contentHistoricContainer">
                    <?php/*
                        $day_x = new DateTime($outputGetFirstDay['date_time']);
                        $day_x_1 = clone $day_x;
                        $day_x_1->modify('+1 day');
                        $getAvgDay = 'SELECT AVG(humidity) AS "avg_humidity" FROM data WHERE date_time >= "'.$day_x->format('Y-m-d').'" AND date_time < "'.$day_x_1->format('Y-m-d').'"';
                        $requestGetAvgDay = $bddConn->query($getAvgDay);
                        $outputGetAvgDay = $requestGetAvgDay->fetch();
                        $nb_days = 0;
                        while ($outputGetAvgDay['avg_humidity'] != NULL)
                        {
                    ?>
                        <div class="itemHistoricContainer">
                            <div class="dayHistoricContainer"><?php echo $daysOfTheWeek[date("w", strtotime($day_x->format('Y-m-d')))] . ' ' . $day_x->format('d') . ' ' . $monthOfTheYear[intval($day_x->format('m'))]; ?></div>
                            <div><?php echo number_format($outputGetAvgDay['avg_humidity'], 2); ?>%</div>
                            <div><img src="assets/images/report_icon.png" class="iconHistoric" id="humidityHistoricIcon<?php echo $nb_days; ?>"/></div>
                        </div>
                        <script type="text/javascript">
                            datesHistoric.push(<?php echo json_encode($day_x->format('Y-m-d')); ?>)
                            var nb_days = <?php echo json_encode($nb_days); ?>;
                            var valueHum = <?php echo json_encode($outputGetAvgDay['avg_humidity']); ?>;
                            document.getElementById("humidityHistoricIcon" + nb_days).src = (valueHum > 50) ? "assets/images/report_icon.png" : "assets/images/report_icon.png";
                        </script>
                    <?php
                            $day_x->modify('-1 day');
                            $day_x_1->modify('-1 day');
                            $getAvgDay = 'SELECT AVG(humidity) AS "avg_humidity" FROM data WHERE date_time >= "'.$day_x->format('Y-m-d').'" AND date_time < "'.$day_x_1->format('Y-m-d').'"';
                            $requestGetAvgDay = $bddConn->query($getAvgDay);
                            $outputGetAvgDay = $requestGetAvgDay->fetch();
                            $nb_days++;
                        }*/
                    ?>
                    </section>
                </section>
            </section>
        </section>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/humidity.js"></script>
        <script type="text/javascript" src="js/dimensions.js"></script>
        <script type="text/javascript">
            var today = new Date();
            var dayNumber = (today.getDate() < 10) ? '0' + today.getDate() : today.getDate();
            var realMonth = parseInt(today.getMonth()) + 1;
            var monthNumber = (realMonth < 10) ? '0' + realMonth : realMonth;
            var chartNbData = 0;
            var urlRequest = urlData + '?data=humidity&day=' + today.getFullYear() + '-' + monthNumber + '-' + dayNumber;
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
                const chartDetailedHumidity = new Chart(ctx_detailed_hum, {
                type: 'line',
                data: {
                    labels: timeOfTheDay,
                    datasets: [{
                        label: '',
                        data: chartHourlyHum[0],
                        fill: false,
                        borderColor: colors[2],
                        pointBackgroundColor: colors[2],
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
                                color: colors[5],
                                borderColor: colors[5]
                            },
                            ticks: {
                                color: colors[5],
                            }
                        },
                        y: {
                            grid: {
                                color: colors[5],
                                borderColor: colors[5]
                            },
                            ticks: {
                                color: colors[5],
                            }
                        }
                    }
                }
            });
            }, 1000);

            var itemHistoricContainer = document.getElementsByClassName("itemHistoricContainer");
            itemHistoricContainer[0].style.backgroundColor = colors[4];

            for (var i = 0; i < itemHistoricContainer.length; i++)
            {
                itemHistoricContainer[i].addEventListener("click", (function(arg) {
                    return function() {
                        for (var j = 0; j < itemHistoricContainer.length; j++)
                        {
                            itemHistoricContainer[j].style.backgroundColor = (arg != j) ? 'transparent' : colors[4];
                        }
                        urlRequest = urlData + '?data=humidity&day=' + datesHistoric[arg];
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
                                const chartDetailedHumidity = new Chart(ctx_detailed_hum, {
                                type: 'line',
                                data: {
                                    labels: timeOfTheDay,
                                    datasets: [{
                                        label: '',
                                        data: chartHourlyHum[arg],
                                        fill: false,
                                        borderColor: colors[2],
                                        pointBackgroundColor: colors[2],
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
                                                color: colors[5],
                                                borderColor: colors[5]
                                            },
                                            ticks: {
                                                color: colors[5],
                                            }
                                        },
                                        y: {
                                            grid: {
                                                color: colors[5],
                                                borderColor: colors[5]
                                            },
                                            ticks: {
                                                color: colors[5],
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