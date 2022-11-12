<?php
    include('variables.php');

    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // should do a check here to match $_SERVER['HTTP_ORIGIN'] to a
        // whitelist of safe domains
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         
    
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
    }

/* ----------------------------------------------------------------------------
                                     INIT
---------------------------------------------------------------------------- */
	$config_file = (object) parse_ini_file("config.ini");
	$output = array(
        'exitcode' => 500,
        'message' => 'Internal Server Error'
    );

    // Server connection

    switch ($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            if (!empty($_POST['token']))
            {
                if ($_POST['token'] == $config_file->api_token)
                {
                    
                }
                else
                {
                    $output['exitcode'] = 403;
                    $output['message'] = 'Access denied ! Wrong token ';
                }
            }
            else
            {
                $output['exitcode'] = 403;
                $output['message'] = 'Access denied ! Token required';
            }
            break;
        case 'POST':
            if (!empty($_POST['token']))
            {
                if ($_POST['token'] == $config_file->api_token)
                {
                    $temperature = floatval($_POST['temperature']);
                    $humidity = floatval($_POST['humidity']);
                    $acceleration = floatval($_POST['acceleration']);
                    $dateTime = date('Y-m-d H:i:s');

                    $sql = "INSERT INTO data (date_time, temperature, humidity, acceleration) VALUES (:date_time, :temperature, :humidity, :acceleration)";
                    $request = $bddConn->prepare($sql);

                    $request->bindParam(':date_time', $dateTime);
                    $request->bindParam(':temperature', $temperature);
                    $request->bindParam(':humidity', $humidity);
                    $request->bindParam(':acceleration', $pressure);

                    if ($request->execute())
                    {
                        $output['exitcode'] = 200;
                        $output['message'] = 'Ok ! Data inserted in database !';
                    }
                }
                else
                {
                    $output['exitcode'] = 403;
                    $output['message'] = 'Access denied ! Wrong token ';
                }
            }
            else
            {
                $output['exitcode'] = 403;
                $output['message'] = 'Access denied ! Token required';
            }
            break;
        default:
            $output['exitcode'] = 501;
            $output['message'] = 'Method not implemented';
            break;
    }
    echo json_encode($output, JSON_PRETTY_PRINT);
?>