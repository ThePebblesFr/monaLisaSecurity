/*
    __________________________________________________________________________
   |                                                                          |
   |                        MONA LISA SECURITY - SCRIPT                       |
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

// Variables ------------------------------------------------------------------
var borderRadius = '30px';
var daysOfTheWeek = Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
var monthsOfTheYear = Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
var colors = Array('#FFFFFF', '#ece8e9', '#dd7478', '#b4001b', '#c9dc82', '#232908');
var nameIconsMenu = Array('home', 'temperature', 'humidity', 'report');

// Items ----------------------------------------------------------------------
var itemsMenu = document.getElementsByClassName('menuItemContainer');
var fakeItem = document.getElementsByClassName('fakeItemContainer');
var iconsMenu = document.getElementsByClassName('menuIcon');

var homeIcon = document.getElementById('homeIcon');
var logoWeatherStation = document.getElementById('logoWeatherStation');
var logoMines = document.getElementById('logoMines');

var page = document.getElementsByClassName('titleTopContainer')[0].innerText.toLowerCase();

/* ----------------------------------------------------------------------------
                                    MAIN
---------------------------------------------------------------------------- */

// Menu items management ------------------------------------------------------
for (var i = 0; i < iconsMenu.length; i++) {

    // Hovering effects
    if (page !== nameIconsMenu[i])
    {
        itemsMenu[i].addEventListener('mouseenter', (function(arg) {
            return function() {
                iconsMenu[arg].src = 'assets/images/' + nameIconsMenu[arg] + '_icon_colored.png';
                if (arg != 0)
                {
                    itemsMenu[arg - 1].style.borderBottomRightRadius = borderRadius;
                }
                else
                {
                    fakeItem[0].style.borderBottomRightRadius = borderRadius;
                }
                if (arg != iconsMenu.length - 1)
                {
                    itemsMenu[arg + 1].style.borderTopRightRadius = borderRadius;
                }
                else
                {
                    fakeItem[1].style.borderTopRightRadius = borderRadius;
                }
            }
        }) (i));

        itemsMenu[i].addEventListener('mouseleave', (function(arg) {
            return function() {
                iconsMenu[arg].src = 'assets/images/' + nameIconsMenu[arg] + '_icon.png';
                if (arg != 0)
                {
                    itemsMenu[arg - 1].style.borderBottomRightRadius = '0px';
                }
                else
                {
                    fakeItem[0].style.borderBottomRightRadius = '0px';
                }
                if(arg != iconsMenu.length - 1)
                {
                    itemsMenu[arg + 1].style.borderTopRightRadius = '0px';
                }
                else
                {
                    fakeItem[1].style.borderTopRightRadius = '0px';
                }
            }
        }) (i));
    }
    else
    {
        iconsMenu[i].src = 'assets/images/' + nameIconsMenu[i] + '_icon_colored.png';
        itemsMenu[i].style.backgroundColor = '#FFFFFF';
        if (i != 0)
        {
            itemsMenu[i - 1].style.borderBottomRightRadius = borderRadius;
        }
        else
        {
            fakeItem[0].style.borderBottomRightRadius = borderRadius;
        }
        if (i != iconsMenu.length - 1)
        {
            itemsMenu[i + 1].style.borderTopRightRadius = borderRadius;
        }
        else
        {
            fakeItem[1].style.borderTopRightRadius = borderRadius;
        }
    }

    // Clicking effects
    itemsMenu[i].addEventListener('click', (function(arg) {
        return function() {
            window.location.href = (arg == 0) ? 'index.php' : nameIconsMenu[arg] + '_page.php';
        }
    }) (i));
}

homeIcon.addEventListener('click', function() {
    window.location.href = 'index.php';
});

logoMines.addEventListener('click', function() {
    window.open('https://emse.fr/', '_blank');
});

contributorsContainer.addEventListener('click', function() {
    window.open('https://github.com/ThePebblesFr/monaLisaSecurity', '_blank');
});