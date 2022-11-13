<?php
    include('backend/variables.php');

    $sql = "SELECT COUNT(*) AS 'nb_reports' FROM reports";
    $request = $bddConn->query($sql);
    $request_output = $request->fetch();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/style.css" />
        <title>Mona Lisa Security - Welcome</title>
        <script type="text/javascript" src="js/jQuery.js"></script>
        <script src="js/mqttws31.js" type="text/javascript"></script>
        <script src="node_modules/chart.js/dist/chart.js"></script>
        <link rel="icon" type="image/x-icon" href="assets/images/monaLisa_icon.ico" />
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
                <img src="assets/images/monaLisaSecurity_logo.png" class="logoWeatherStation" id="logoWeatherStation"/>
            </div>
            <div class="titleTopContainer">DASHBOARD</div>
            <div class="contributorsContainer" id="contributorsContainer">
                <img src="assets/images/photo_mickael.png" class="pdpImage" id="imageMickael"/>
                <img src="assets/images/photo_pierre.png" class="pdpImage" id="imagePierre"/>
                <div class="contributors">Contributors</div>
            </div>
        </section>
        <section class="bodyContainer">
            <section class="topBodyContainer">
                <section class="topLeftBodyContainer">
                    <section class="dateTimeContainer">
                        <div class="dateContainer" id="dateContainer">Monday 19 September</div>
                        <div class="timeContainer" id="timeContainer">10:43</div>
                    </section>
                    <section class="dataHomeContainer">
                            <div class="dayNightContainer"><img src="assets/images/day_icon.png" class="realTimeDayNightIcon" id="dayNightIcon"/></div>
                    </section>
                </section>
                <section class="topRightBodyContainer">
                    <section class="graphContainer">
                        <div class="titleDataHome">Temperature</div>
                        <div class="graphSubContainer">
                            <div class="loaderHomeContainer" id="loaderTemperature">
                                <div class="loader-wrapper">
                                    <div class="loader">
                                        <div class="loader loader-inner"></div>
                                    </div>
                                </div>
                            </div>
                            <canvas id="chart_detailed_temp"></canvas>
                        </div>
                    </section>
                    <section class="dataHomeContainer">
                        <div class="dataItemContainer">
                            <div><img src="assets/images/temperature_icon_colored.png" class="realTimeTemperatureIcon" id="temperatureIcon"/></div>
                            <div class="celsiusData" id="temperatureValue"> °C</div>
                            <div class="fahrneheitData" id="fahrenHeitValue"> °F</div>
                        </div>
                        <div class="detailsItemContainer" id="detailsItemContainerTop">See details</div>
                    </section>
                </section>
            </section>
            <section class="bottomBodyContainer">
                <section class="bottomLeftBodyContainer">
                    <section class="graphContainer">
                        <div class="titleDataHome" style="color: var(--white-color);">Relative Humidity</div>
                        <div class="graphSubContainer">
                            <div class="loaderHomeContainer" id="loaderHumidity">
                                <div class="loader-wrapper">
                                    <div class="loader">
                                        <div class="loader loader-inner"></div>
                                    </div>
                                </div>
                            </div>
                            <canvas id="chart_detailed_hum"></canvas>
                        </div>
                    </section>
                    <section class="dataHomeContainer">
                        <div class="dataItemContainer">
                            <div ><img src="assets/images/humidity_icon.png" class="realTimeTemperatureIcon" id="humidityIcon"/></div>
                            <div class="celsiusData" style="color: var(--white-color);" id="humidityValue"><?php echo number_format($outputGetLastData['humidity'], 2); ?>%</div>
                        </div>
                        <div class="detailsItemContainer">See details</div>
                    </section>
                </section>
                <section class="bottomRightBodyContainer">
                    <section class="graphContainer">
                        <div class="titleDataHome" style="color: var(--white-color);">Reports</div>
                        <div class="graphSubContainer">
                            
                        </div>
                    </section>
                    <section class="dataHomeContainer">
                        <div class="dataItemContainer">
                            <div ><img src="assets/images/report_icon.png" class="realTimeTemperatureIcon" /></div>
                            <div class="celsiusData" style="color: var(--white-color);" id="accelerationXvalue"><?php echo $request_output['nb_reports']; ?></div>
                        </div>
                        <div class="detailsItemContainer">See details</div>
                    </section>
                </section>
            </section>
        </section>
        <div id="overlay_home" class="overlay_home">
            <div id="popup_home" class="popup_home">
                <h2 class="headerPopup">
                    <div class="popupTitle">WARNING - Security system disabled</div>
                    <span id="btnClose" class="btnClose">&times;</span>
                </h2>
                <div class="popupDescription">
                    <img src="assets/images/disconnected_devices.png" class="disconnectedDevicesImg"/>
                    <br />
                    <div>It seems that the security system is not plugged or is turned off. Please call an operator.</div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/index.js"></script>
        <script type="text/javascript" src="js/dimensions.js"></script>
        <script type="text/javascript">
            // MQTT Communication
            $(document).ready(function() {
                MQTTconnect();
            });

            var loaderTemperature = document.getElementById('loaderTemperature');
            var loaderHumidity = document.getElementById('loaderHumidity');
            var loaderPressure = document.getElementById('loaderPressure');

            var today = new Date();
            var dayNumber = (today.getDate() < 10) ? '0' + today.getDate() : today.getDate();
            var timeOfSixLastHours = Array(today.getHours()-5 +'h', today.getHours()-4 +'h', today.getHours()-3 +'h', today.getHours()-2 +'h', today.getHours()-1 +'h', today.getHours() +'h');            
            var realMonth = parseInt(today.getMonth()) + 1;
            var monthNumber = (realMonth < 10) ? '0' + realMonth : realMonth;
            var chartNbData = 0;
            var chartHourlyTemp = Array();
            var urlData = "http://software-developments-pg.com/others/monaLisaSecurity/backend/all_data.php";
            var urlRequest = urlData + '?data=temperature&day=' + today.getFullYear() + '-' + monthNumber + '-' + dayNumber;
            $.ajax({
                type: 'GET',
                url: urlRequest,
                success: function(data) {
                    
                    data = JSON.parse(data);
                    for (var i = 6; i > 0; i--)
                    {
                        chartNbData++;
                        if (data[i] != 0)
                        {
                            chartHourlyTemp.push(parseFloat(data[i]));
                        }
                        else
                        {
                            chartHourlyTemp.push(null);
                        }
                    }
                }
            });
            setTimeout(function() {
                loaderTemperature.style.display = "none";
                const ctx_detailed_temp = document.getElementById('chart_detailed_temp').getContext('2d');
                const chartDetailedTemperature = new Chart(ctx_detailed_temp, {
                type: 'line',
                data: {
                    labels: timeOfSixLastHours,
                    datasets: [{
                        label: '',
                        data: chartHourlyTemp,
                        fill: false,
                        borderColor: colors[0],
                        pointBackgroundColor: colors[0],
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
        </script>
        <script type="text/javascript">
            var today = new Date();
            var dayNumber = (today.getDate() < 10) ? '0' + today.getDate() : today.getDate();
            var timeOfSixLastHours = Array(today.getHours()-5 +'h', today.getHours()-4 +'h', today.getHours()-3 +'h', today.getHours()-2 +'h', today.getHours()-1 +'h', today.getHours() +'h');            
            var realMonth = parseInt(today.getMonth()) + 1;
            var monthNumber = (realMonth < 10) ? '0' + realMonth : realMonth;
            var chartNbData = 0;
            var chartHourlyHum = Array();
            var urlRequest = urlData + '?data=humidity&day=' + today.getFullYear() + '-' + monthNumber + '-' + dayNumber;
            $.ajax({
                type: 'GET',
                url: urlRequest,
                success: function(data) {
                    data = JSON.parse(data);
                    for (var i = 6; i > 0; i--)
                    {
                        chartNbData++;
                        if (data[i] != 0)
                        {
                            chartHourlyHum.push(parseFloat(data[i]));
                        }
                        else
                        {
                            chartHourlyHum.push(null);
                        }
                    }
                }
            });
            setTimeout(function() {
                loaderHumidity.style.display = "none";
                const ctx_detailed_hum = document.getElementById('chart_detailed_hum').getContext('2d');
                const chartDetailedHumidity = new Chart(ctx_detailed_hum, {
                type: 'line',
                data: {
                    labels: timeOfSixLastHours,
                    datasets: [{
                        label: '',
                        data: chartHourlyHum,
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
        </script>
    </body>
</html>