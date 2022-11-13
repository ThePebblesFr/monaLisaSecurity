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

// MQTT communication
var mqtt;
var reconnectTimeout = 2000;
var port = 8000;
var topicStr = "Pierre/Temp";

var connected = false;
var alert_intrusion_acceleration = false;
var alert_intrusion_temperature = false;
var alert_intrusion_humidity = false;


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
    connected = true;
	$('#status').val('Connected to host ');
	// Connection succeeded; subscribe to our topic
	mqtt.subscribe(topicStr, {qos: 0});
	$('#topic').val(topicStr)
}
function onConnectionLost(response) {
    connected = false;
	setTimeout(MQTTconnect, reconnectTimeout);
	$('#status').val("connection lost: " + response.errorMessage + ". Reconnecting");
};
function onMessageArrived(message) {
	var topic = message.destinationName;
	var payload = message.payloadString;
    
    payload = JSON.parse(payload);
    if (payload.temperature != null) {
        temperatureValue.innerText = payload.temperature + "°C";
        var fahrenHeitTemp = Math.round((parseFloat(temperatureValue.innerText.substring(0,5)) * (9 / 5) + 32) * 100) / 100;
        fahrenHeitValue.innerHTML = fahrenHeitTemp.toString() + "°F";

        if (payload.alert_temperature == "1") {
            alert_intrusion_temperature = true;
        }
        if (payload.alert_humidity == "1") {
            alert_intrusion_humidity = true;
        }
    }
    else if (payload.alert_acceleration != null) {
        if (payload.alert_acceleration == "1")
        {
            alert_intrusion_acceleration = true;
        }
    }
};