/*
    __________________________________________________________________________
   |                                                                          |
   |                 MONA LISA SECURITY - TEMPERATURE SCRIPT                  |
   |                                                                          |
   |    Author            :   P. GARREAU, M. JALES                            |
   |    Status            :   Under Development                               |
   |    Last Modification :   04/11/2022                                      |
   |    Project           :   IoT PROJECT                                     |
   |                                                                          |
   |__________________________________________________________________________|

*/

/* ----------------------------------------------------------------------------
                                     INIT
---------------------------------------------------------------------------- */
var urlData = "http://software-developments-pg.com/others/monaLisaSecurity/all_data.php";

var iconsMenu = document.getElementsByClassName('menuIcon');
var nameIconsMenu = Array('home', 'temperature', 'humidity', 'report');

var temperatureValue = document.getElementById('temperatureValue');
var fahrenHeitValue = document.getElementById('fahrenHeitValue');
var dayNightIcon = document.getElementById('dayNightIcon');
var dateContainer = document.getElementById('dateContainer');
var timeContainer = document.getElementById('timeContainer');

var timeOfTheDay = Array('00h', '1h', '2h', '3h', '4h', '5h', '6h', '7h', '8h', '9h', '10h', '11h', '12h', '13h', '14h', '15h', '16h', '17h', '18h', '19h', '20h', '21h', '22h', '23h');

/* ----------------------------------------------------------------------------
                                    MAIN
---------------------------------------------------------------------------- */

// Data conversion and icons --------------------------------------------------

var fahrenHeitTemp = Math.round((parseFloat(temperatureValue.innerText.substring(0,5)) * (9 / 5) + 32) * 100) / 100;
fahrenHeitValue.innerHTML = fahrenHeitTemp.toString() + "°F";

// Date and time --------------------------------------------------------------

var today = new Date();
    dateContainer.innerText = daysOfTheWeek[today.getDay()] + ' ' + today.getDate() + ' ' + monthsOfTheYear[today.getMonth()];
    timeContainer.innerText = timelayout(today.getHours().toString()) + ':' + timelayout(today.getMinutes().toString());

setInterval(function() {
    var today = new Date();
    dateContainer.innerText = daysOfTheWeek[today.getDay()] + ' ' + today.getDate() + ' ' + monthsOfTheYear[today.getMonth()];
    timeContainer.innerText = timelayout(today.getHours().toString()) + ':' + timelayout(today.getMinutes().toString());
}, 1000);

// Chart temperature.php -----------------------------------------------------------


/* ----------------------------------------------------------------------------
                                FUCNTIONS
---------------------------------------------------------------------------- */

function timelayout(num) {
    return (num.length == 1) ? '0' + num : num ;
}

function dayTime(time) {
    return (time > 8 && time < 20);
}