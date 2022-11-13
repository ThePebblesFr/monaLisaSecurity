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
        acceleration
        comment*/

    $daysOfTheWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    $monthOfTheYear = array('Zebre', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

    // Get number of Reports
    $getNbReports = 'SELECT COUNT(*) AS "nb_reports" FROM reports';
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

    // Get first report
    $getFirstReport = 'SELECT * FROM reports ORDER BY date_time_report DESC LIMIT 1';
    $requestGetFirstReport = $bddConn->query($getFirstReport);
    $outputGetFirstReport = $requestGetFirstReport->fetch();
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
            var chartHourlyRep = Array(Array());
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
                                <div class="celsiusData" id="reportValue"><?php echo $outputGetNbReports['nb_reports']; ?> reports</div>
                            </section>
                        </div>
                        <section class="dateTimeDetailedPageContainer">
                            <div class="dateSmallContainer" id="dateContainer">Monday 19 September</div>
                            <div class="timeSmallContainer" id="timeContainer">10:43</div>
                        </section>
                    </section>
                    <section class="leftBottomBodyContainer">
                        <section class="titleReportContainer">REPORTS</section>
                        <div class="reportHeaderOverContainer">
                            <div class="reportHeaderContainer">
                                <section class="columnTimeHeaderContainer">
                                    <div class="timeColumnHeaderContainer">Date</div>
                                    <div class="timeColumnHeaderContainer">Problem</div>
                                </section>
                                <section class="columnTimeHeaderContainer">
                                    <div class="timeColumnHeaderContainer">Date</div>
                                    <div class="timeColumnHeaderContainer">Rearming</div>
                                </section>
                                <section class="columnTimeHeaderContainer">
                                    <div class="nameColumnHeaderContainer">Cause</div>
                                </section>
                                <section class="columnHeaderContainer">
                                    <div class="nameColumnHeaderContainer">Comment</div>
                                </section>
                            </div>
                        </div>
                        <div class="listReportContainer">
                           <?php
                                $getListReportDaily = 'SELECT * FROM reports ';
                                $requestGetListReportDaily = $bddConn->query($getListReportDaily);
                                while ($outputGetListReportDaily = $requestGetListReportDaily->fetch()) 
                                {
                                    echo '<div class="reportContainer">';
                                    $dateTimeReport = $outputGetListReportDaily['date_time_report'];
                                    $dateTimeRearmement = $outputGetListReportDaily['date_time_rearmement'];
                                    $temperature = $outputGetListReportDaily['temperature'];
                                    $humidity = $outputGetListReportDaily['humidity'];
                                    $acceleration = $outputGetListReportDaily['acceleration'];
                                    $comment = $outputGetListReportDaily['comment'];
                                    echo '<div class="columnTimeReportContainer">' . substr($dateTimeReport, 0, -3) . '</div>';
                                    echo '<div class="columnTimeReportContainer">' . substr($dateTimeRearmement, 0, -3) . '</div>';
                                    echo '<div class="columnTimeReportContainer">';
                                    echo '<section class="causeReportContainer">';
                                    if ($temperature)
                                    {
                                        echo '<div class="columnTimeReportContainer">Temperature</div>';
                                    }
                                    if ($humidity)
                                    {
                                        echo '<div class="columnTimeReportContainer">Humidity</div>';
                                    }
                                    if ($acceleration)
                                    {
                                        echo '<div class="columnTimeReportContainer">Acceleration</div>';
                                    }
                                    echo '</section>';
				                    echo '</div>';
                                    echo '<div class="columnReportContainer">' . $comment . '</div>';
				                    echo '</div>';
                                }
                            ?>
                        </div>
                    </section>
                </section>
            </section>
        </section>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/report.js"></script>
        <script type="text/javascript" src="js/dimensions.js"></script>
    </body>
</html>