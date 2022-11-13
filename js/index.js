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
var nameIconsMenu = Array('home', 'temperature', 'humidity', 'report');

var detailsItemContainer = document.getElementsByClassName('detailsItemContainer');
var fahrenHeitValue = document.getElementById('fahrenHeitValue');
var dayNightIcon = document.getElementById('dayNightIcon');
var dateContainer = document.getElementById('dateContainer');
var timeContainer = document.getElementById('timeContainer');

var temperatureValue = document.getElementById('temperatureValue');
var humidityValue = document.getElementById('humidityValue');
var reportValue = document.getElementById('reportValue');

var btnClose = document.getElementById('btnClose');
var overlay_home = document.getElementById('overlay_home');
var popup_home = document.getElementById('popup_home');

// MQTT communication
var mqtt;
var reconnectTimeout = 2000;
var port = 8000;
var topicStr = "Pierre/Temp";

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

// Popup ----------------------------------------------------------------------

btnClose.addEventListener('click', function() {
    overlay_home.style.display = 'none';
    popup_home.style.display = 'none';
});

/* ----------------------------------------------------------------------------
                                FUCNTIONS
---------------------------------------------------------------------------- */

function timelayout(num) {
    return (num.length == 1) ? '0' + num : num ;
}

function dayTime(time) {
    return (time > 8 && time < 20);
}

function MQTTconnect() {
	mqtt = new Paho.MQTT.Client("broker.hivemq.com",port,"/mqtt","web_" + parseInt(Math.random() * 100, 10) );
	var options = {
		timeout: 3,
		useSSL: false,
		cleanSession: true,
		onSuccess: onConnect,
		onFailure: function (message) {
			$('#status').val("Connection failed: " + message.errorMessage + "Retrying");
			setTimeout(MQTTconnect, reconnectTimeout);
		}
	};
	mqtt.onConnectionLost = onConnectionLost;
	mqtt.onMessageArrived = onMessageArrived;
	mqtt.connect(options);
}
function onConnect() {
	$('#status').val('Connected to host ');
	// Connection succeeded; subscribe to our topic
	mqtt.subscribe(topicStr, {qos: 0});
	$('#topic').val(topicStr)
}
function onConnectionLost(response) {
	setTimeout(MQTTconnect, reconnectTimeout);
	$('#status').val("connection lost: " + response.errorMessage + ". Reconnecting");
};
function onMessageArrived(message) {
	var topic = message.destinationName;
	var payload = message.payloadString;
    
    payload = JSON.parse(payload);

    temperatureValue.innerText = payload.temperature + "°C";
    humidityValue.innerText = payload.humidity + "%";

    var fahrenHeitTemp = Math.round((parseFloat(temperatureValue.innerText.substring(0,5)) * (9 / 5) + 32) * 100) / 100;
    fahrenHeitValue.innerHTML = fahrenHeitTemp.toString() + "°F";
    // accelerationXValue.innerText = payload.acceleration_x;
    // accelerationYValue.innerText = payload.acceleration_y;
    // accelerationZValue.innerText = payload.acceleration_z;
	// chartTemperature.data.labels.push(nbData);
	// chartTemperature.data.datasets.forEach((dataset) => {
    //     dataset.data.push(parseInt(payload));
    // });
	// chartTemperature.update();
};