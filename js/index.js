/*
    __________________________________________________________________________
   |                                                                          |
   |                 MY WHEATHER STATION - CHARTS INDEX                       |
   |                                                                          |
   |    Author            :   M. JALES, P. GARREAU                            |
   |    Status            :   Under Development                               |
   |    Last Modification :   16/09/2022                                      |
   |    Project           :   EMBEDDED LINUX PROJECT                          |
   |                                                                          |
   |__________________________________________________________________________|
   
*/

/* ----------------------------------------------------------------------------
                                     INIT
---------------------------------------------------------------------------- */
var urlData = "http://software-developments-pg.com/others/myWeatherStation/all_data.php";

var iconsMenu = document.getElementsByClassName('menuIcon');
var nameIconsMenu = Array('home', 'temperature', 'humidity', 'pressure');

var detailsItemContainer = document.getElementsByClassName('detailsItemContainer');

var fahrenHeitValue = document.getElementById('fahrenHeitValue');
var dayNightIcon = document.getElementById('dayNightIcon');
var dateContainer = document.getElementById('dateContainer');
var timeContainer = document.getElementById('timeContainer');

/* ----------------------------------------------------------------------------
                                    MAIN
---------------------------------------------------------------------------- */

// Charts index.php -----------------------------------------------------------

for (var i = 0; i < iconsMenu.length; i++) {
    if (i != 3)
    {
        var j = i + 1;
        detailsItemContainer[i].addEventListener('click', (function(arg) {
            return function() {
                window.location.href = nameIconsMenu[arg] + '_page.php';
            }
        }) (j));
    }
}

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
    dayNightIcon.src = (dayTime(today.getHours())) ? 'assets/images/day_icon.png' : 'assets/images/night_icon.png';
}, 1000);

/* ----------------------------------------------------------------------------
                                FUCNTIONS
---------------------------------------------------------------------------- */

function timelayout(num) {
    return (num.length == 1) ? '0' + num : num ;
}

function dayTime(time) {
    return (time > 8 && time < 20);
}