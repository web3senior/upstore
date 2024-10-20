<?php

//header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
//header("Access-Control-Max-Age: 3600");
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
    // you want to allow, and if so:
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        // may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

// Connect and authenticate with your Algolia app

class V1 extends Controller
{
    private $_error = null;
    private $secretKey = 'secretd';
    private $version = 'V1';

    function __construct()
    {
        parent::__construct();
        // echo password_hash('1'.' ssds'.'abi', PASSWORD_DEFAULT);
    }

    protected function authorization()
    {
        if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            header('HTTP/1.0 400 Bad Request');
            echo 'Token not found in request';
            exit;
        } else {
            // Verify user
            $token = substr($_SERVER['HTTP_AUTHORIZATION'], strlen("Bearer "), strlen($_SERVER['HTTP_AUTHORIZATION']));
            $result = (new JWTAuth)->decode($token);

            if (!$result['result']) {
                $this->_error = 'toekn is not valid';
                $this->_showError();
                exit();
            }
            return $result;
        }
    }

    function index()
    {
        echo 'It works!';
        die;
    }

    function dashboard()
    {
        $this->request_method("GET");
        $data = $this->model->dashboard($_GET['wallet_addr']);
        if (!empty($data) && is_array($data)) {
            (new Httpresponse)->set(200);
            echo json_encode($data);
        } else {
            $this->_error = "Not found any record!";
            $this->Error();
        }
    }

    function app($operation)
    {
        $body = (array)json_decode(file_get_contents('php://input'));
        $table = ['app', 'id'];

        switch ($operation) {
            case "get":
                $this->request_method('GET');
                $data = $this->model->command('fetch',$table);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "chart":
                $this->request_method('GET');
                $data = $this->model->eventChart($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "add":
               // $this->request_method('POST');

//                $data = json_decode(file_get_contents('http://localhost/universal-fam/up_mainnet_export.json'));
//              foreach ($data as $key => $value) {
             
//               $test =  $this->model->command("insert", ['up_mainnet', 'id'], ["content" => json_encode($value)]);
//              }
// echo "done";
// die;

                $result = $this->model->command("insert", $table, ["content" => json_encode($body["content"])]);
                if ($result > 0) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "New record has been added",
                        "recordId" =>  $result
                    ]);
                    exit();
                }
                break;
            case "update":
                $this->request_method('POST');
                $result = $this->model->command("update", $table, $body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Updated"
                    ]);
                    exit();
                }
                break;
        }
    }

    function meta($operation)
    {
        $body = (array)json_decode(file_get_contents('php://input'));
        $table = ['config', 'id'];

        switch ($operation) {
            case "get":
                $this->request_method('GET');

                $data = $this->model->config($_GET['username']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);

                    header("Content-Type: image/jpeg; charset=UTF-8");
                    echo ('   
                    <svg viewBox="0 0 240 80" xmlns="http://www.w3.org/2000/svg">
                    <style>
                      .small {
                        font: italic 13px sans-serif;
                        font: italic 40px serif;
                        fill: orange;
                      }
                      .heavy {
                        font: bold 30px sans-serif;
                      }
                  
                      /* Note that the color of the text is set with the    *
                       * fill property, the color property is for HTML only */
                      .Rrrrr {
                       
                      }
                    </style>
                  
                    <text x="20" y="35" class="small">' . $_GET['username'] . '</text>
                    
                  </svg>
                    ');


                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "chart":
                $this->request_method('GET');
                $data = $this->model->eventChart($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "add":
                $this->request_method('POST');
                $body['wallet_addr'] = $_GET['wallet_addr'];
                $result = $this->model->command("insert", $table, $body);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "new record added",
                        "added_id" =>  $result
                    ]);
                    exit();
                }
                break;
            case "update":
                $this->request_method('POST');
                $result = $this->model->updateConfig($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Saved"
                    ]);
                    exit();
                }
                break;
        }
    }

    function config($operation)
    {
        $body = (array)json_decode(file_get_contents('php://input'));
        $table = ['config', 'id'];

        switch ($operation) {
            case "get":
                $this->request_method('GET');

                $data = (!empty($_GET['username']) ? $this->model->config($_GET['username']) : $this->model->configByWallet($_GET['wallet_addr']));
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data[0]);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "chart":
                $this->request_method('GET');
                $data = $this->model->eventChart($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "add":
                $this->request_method('POST');
                $body['wallet_addr'] = $_GET['wallet_addr'];
                $result = $this->model->command("insert", $table, $body);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "new record added",
                        "added_id" =>  $result
                    ]);
                    exit();
                }
                break;
            case "update":
                $this->request_method('POST');
                $result = $this->model->updateConfig($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Saved"
                    ]);
                    exit();
                }
                break;
        }
    }

    function view($operation)
    {
        $body = (array)json_decode(file_get_contents('php://input'));
        $table = ['view', 'id'];

        switch ($operation) {
            case "get":
                $this->request_method('GET');
                $data = $this->model->view($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data[0]);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "chart":
                $this->request_method('GET');
                $data = $this->model->viewChart($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "add":
                $this->request_method('POST');

                $body['ip'] = (new Ip)->get();

                $result = $this->model->command("insert", $table, $body);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "new record added",
                        "added_id" =>  $result
                    ]);
                    exit();
                }
                break;
            case "update":
                $this->request_method('POST');
                $result = $this->model->command("update", $table, $body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Updated"
                    ]);
                    exit();
                }
                break;
        }
    }

    function user($operation)
    {
        $body = (array)json_decode(file_get_contents('php://input'));
        $table = ['config', 'id'];

        switch ($operation) {
            case "get":
                $this->request_method('GET');
                $data = $this->model->getUser($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data[0]);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "chart":
                $this->request_method('GET');
                $data = $this->model->eventChart($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "add":
                $this->request_method('POST');
                $body['wallet_addr'] = $_GET['wallet_addr'];
                $result = $this->model->command("insert", $table, $body);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "new record added",
                        "added_id" =>  $result
                    ]);
                    exit();
                }
                break;
            case "check":
                $this->request_method('POST');
                $result = $this->model->checkUser($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Available"
                    ]);
                    exit();
                } else {
                    (new Httpresponse)->set(200);
                    echo json_encode([
                        "result" => false,
                        "message" => "Not available"
                    ]);
                }
                break;
            case "update":
                $this->request_method('POST');
                $result = $this->model->updateUser($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Saved"
                    ]);
                    exit();
                } else {
                    (new Httpresponse)->set(200);
                    echo json_encode([
                        "result" => false,
                        "message" => "Not available"
                    ]);
                }
                break;

            case "telegram":
                $this->request_method('POST');
                $result = $this->model->updateTelegramId($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Saved"
                    ]);
                    exit();
                } else {
                    (new Httpresponse)->set(200);
                    echo json_encode([
                        "result" => false,
                        "message" => "Not available"
                    ]);
                }
                break;
        }
    }

    function voice($operation)
    {
        $body = file_get_contents('php://input');
        $table = ['voice', 'id'];

        switch ($operation) {
            case "send":
                $this->request_method('POST');
                if (isset($_FILES['audio']) and !$_FILES['audio']['error']) {

                    // echo $this->model->getTelegramId($_GET['wallet_addr']);
                    // die;
                    // Store audio in filesystem
                    // $res = file_put_contents( "uploads/audio/".$_FILES['audio']['name'], file_get_contents($_FILES['audio']['tmp_name']) );
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.telegram.org/bot6839061467:AAFGUaHBx8jsyPbFTxL9-spfgUu158AHHDY/sendVoice',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => [
                            'voice' => new CURLFile(
                                $_FILES['audio']['tmp_name'],
                                'audio/ogg',
                                $_FILES['audio']['name']
                            ),
                            'chat_id' =>  $this->model->getTelegramId($_GET['wallet_addr']),
                            'caption' => 'Universal Link 🔊'
                        ],
                    ));

                    $response = json_decode(curl_exec($curl));

                    curl_close($curl);

                    if ($response->ok) {
                        (new Httpresponse)->set(200);
                        echo json_encode(["result" => true, "message" => "Your voice has been sent"]);
                        exit();
                    } else {
                        $this->_error = "error!";
                        $this->Error();
                    }
                } else {
                    throw new Exception("Error Processing Request", 1);
                }
                break;
            case "chart":
                $this->request_method('GET');
                $data = $this->model->eventChart($_GET['wallet_addr']);
                if (!empty($data) && is_array($data)) {
                    (new Httpresponse)->set(200);
                    echo json_encode($data);
                    exit();
                } else {
                    $this->_error = "Not found any record!";
                    $this->Error();
                }
                break;
            case "add":
                $this->request_method('POST');
                $body['wallet_addr'] = $_GET['wallet_addr'];
                $result = $this->model->command("insert", $table, $body);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "new record added",
                        "added_id" =>  $result
                    ]);
                    exit();
                }
                break;
            case "check":
                $this->request_method('POST');
                $result = $this->model->checkUser($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Available"
                    ]);
                    exit();
                } else {
                    (new Httpresponse)->set(200);
                    echo json_encode([
                        "result" => false,
                        "message" => "Not available"
                    ]);
                }
                break;
            case "update":
                $this->request_method('POST');
                $result = $this->model->updateUser($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Saved"
                    ]);
                    exit();
                } else {
                    (new Httpresponse)->set(200);
                    echo json_encode([
                        "result" => false,
                        "message" => "Not available"
                    ]);
                }
                break;

            case "telegram":
                $this->request_method('POST');
                $result = $this->model->updateTelegramId($body, $_GET['wallet_addr']);
                if ($result) {
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "message" => "Saved"
                    ]);
                    exit();
                } else {
                    (new Httpresponse)->set(200);
                    echo json_encode([
                        "result" => false,
                        "message" => "Not available"
                    ]);
                }
                break;
        }
    }

    function login()
    {

        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody);
        $this->request_method("POST");

        if (!empty($data->phone) && !empty($data->password)) {
            $result = $this->model->login($data->phone);


            if (is_array($result) && !empty($result)) {
                // Verify the hash against the password entered
                $verify = password_verify($data->password, $result[0]['password']);

                if ($verify) {
                    // $res = (new Email)->send($data->email, 'ورود به حسا کاربی', 'کارر گرمی شما هم کون اد حساب کاربی خود دید');
                    // echo $res;
                    (new Httpresponse)->set(202);
                    echo json_encode([
                        "result" => true,
                        "token" => (new JWTAuth)->encode(["id" => $result[0]['id'], "phone" => $data->phone])
                    ]);
                } else {
                    (new Httpresponse)->set(200);
                    $this->_error = "نام کاربری یا کلمه عبور صحیح نمی باشد";
                    echo json_encode(["result" => false, "message" => $this->_error]);
                }
            } else {
                (new Httpresponse)->set(200);
                $this->_error = "نام کاربری یا کلمه عبور صحیح نمی باشد";
                echo json_encode(["result" => false, "message" => $this->_error]);
            }
        }
    }

    function uploadRequestDoc($id, $field)
    {
        $entityBody = file_get_contents('php://input');
        $data = json_decode($entityBody);
        $this->request_method("POST");


        if (!empty($_FILES[$field]['name'])) {
            $_POST[$field] = (new Upload)->documentUpload($field, 0, time());
            $result = $this->model->uploadRequestDoc($_POST[$field], $id, $field);
            if ($result) {
                (new Httpresponse)->set(202);
                echo json_encode([
                    "result" => true,
                    "message" => 'dd   '
                ]);
            } else {
                (new Httpresponse)->set(401);
                $this->_error = "error!";
                echo json_encode(["result" => false, "message" => $this->_error]);
            }
        } else {
            echo "no file selected";
        }
    }

    private function request_method($arg)
    {
        //header("Access-Control-Allow-Methods: " . $arg);
        if (strtolower($_SERVER['REQUEST_METHOD']) !== strtolower($arg)) {
            (new Httpresponse)->set(405);
            echo (json_encode(["message" => "Request method must be correct set!"]));
            exit();
        }
    }

    private function _showError()
    {
        if (!empty($this->_error))  $this->Error();
    }

    /**
     * Authorization
     * @param String $key
     */
    private function Error()
    {
        if (isset($this->_error)) {
            if (!empty($this->_error)) {
                (new Httpresponse)->set(400);
                echo json_encode([
                    "result" => false,
                    "message" => $this->_error
                ]);
            }
        } else {
            (new Httpresponse)->set(400);
            echo ('{"message":"Please contact with programmer!"}');
            exit();
        }
    }
}
