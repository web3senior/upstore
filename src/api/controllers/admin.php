<?php

use Firebase\JWT\JWT;

class Admin extends Controller
{
  protected string $_token;
  protected array $_error = [];
  protected string $key = "example_key";

  function __construct()
  {
    parent::__construct();
    (new Session)->init();
    if ((new Session)->get("loggedAdmin")) header("Location:" . URL . 'panel');
    $this->_token = (new Token)->make();

    //echo (new Hash)->create('md5', '102030', HASH_PASSWORD_KEY);
  }

  function encode($payload): string
  {
    //        $payload = array(
    //            "iss" => "http://example.org",
    //            "aud" => "http://example.com",
    //            "iat" => 1356999524,
    //            "nbf" => 1357000000
    //        );
    return $jwt = JWT::encode($payload, $this->key);
  }

  function decode($jwt): object
  {
    JWT::$leeway = 60; // $leeway in seconds
    return $decoded = JWT::decode($jwt, $this->key, array('HS256'));
  }

  function index()
  {
    if (empty($_SESSION['token'])) :
      $this->_token = (new Token)->make();
    else :
      $this->_token = $_SESSION['token'];
    endif;

    (new Session)->init();
    (new Session)->set('token', $this->_token);
    $this->view->token = $this->_token;
    $this->view->render('admin/index');
  }
  /**
   * Log in
   */
  function auth()
  {
    $entityBody = file_get_contents('php://input');
    $data = json_decode($entityBody);
    if (!isset($data->googleRecaptchaToken) && empty($data->googleRecaptchaToken))
      array_push($this->_error, "گوگل کپچا ناموفق");

    //googleRecaptchaToken
    if (empty($this->_error)) {
      $captcha = $data->googleRecaptchaToken;

     // if (($this->gCaptcha($captcha))->success) {
        if (!empty($data->email) && !empty($data->password)) {
          $data = [
            'email' => $data->email,
            'password' => (new Hash)->create('md5', $data->password, HASH_PASSWORD_KEY)
          ];

           $result = $this->model->login($data);

          if (!empty($result) && is_array($result)) {
            (new Httpresponse)->set(202);
            echo json_encode([
              "result" => true,
              "message" => URL . 'panel',
              "admin_info" => ["admin_id" => $result["id"], "avatar" => $result["avatar"], "email" => $result["email"], "fullname" => $result["fullname"]],
              "token" => (new JWTAuth)->encode(["email" => $result["email"], "admin" => true])
            ]);
          } else {
            (new Httpresponse)->set(401);
            array_push($this->_error, "We can not reach you!");
            echo json_encode(["result" => false, "message" => $this->_error]);
          }
        }
      // } else {
      //   array_push($this->_error, "شما به عنوان ربات شناخته شده اید");
      //   echo json_encode(["result" => false, "message" => $this->_error]);
      // }
    }else {
      echo json_encode(["result" => false, "message" => $this->_error]);
    }
  }

  // function auth()
  // {
  //   $entityBody = file_get_contents('php://input');
  //   $data = json_decode($entityBody);
  //   print_r($data);
  //   die;
  //   (new Session)->init();

  //   if (!isset($data->token) && empty($data->token) && ((new Session)->get('token') !== $data->token))
  //     $this->printError("Token not found");


  //   if (!isset($data->googleRecaptcha) && empty($data->googleRecaptcha))
  //     $this->printError("Token is empty or not set");


  //   if (empty($this->_error)) {

  //     $captcha = $data->googleRecaptcha;
  //     $post_data = [
  //       'email' => !empty($data->email) ? $data->email : '',
  //       'password' => !empty($data->password) ? (new Hash)->create('md5', $data->password, HASH_PASSWORD_KEY) : ''
  //     ];

  //     if (!filter_var($data->email, FILTER_VALIDATE_EMAIL))
  //       $this->printError("Your email's format is wrong");

  //     if (($this->gCaptcha($captcha))->success) {
  //       $res = $this->model->getData($post_data);
  //       if ($res) {
  //         echo json_encode(['result' => true, 'message' => URL . 'panel/dashboard']);
  //         exit;
  //       } else $this->printError("You are not an admin");
  //     } else $this->printError($this->_error, "You are a BOT");
  //   }

  //   $this->printError();
  // }

  /**
   * Auto login by Google
   */
  function googleOneTapSigning()
  {
    $id_token = $_POST['credential'];
    $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend


    $payload = $client->verifyIdToken($id_token);
    print_r(($payload));
    die;
    if ($payload) {
      echo 1;
      $userid = $payload['sub'];
      // If request specified a G Suite domain:
      //$domain = $payload['hd'];
    } else {
      // Invalid ID token
    }


    die;



    $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend
    $payload = $client->verifyIdToken($id_token);
    if ($payload) {
      $userid = $payload['sub'];
      $picture = $payload['picture'];
      $email = $payload['email'];
      //$domain = $payload['hd'];
      if ($this->model->getDataByEmail($email)) {
        header('location: ' . URL . 'panel');
      } else {
        header('location: ' . URL . 'admin');
      }
    } else {
      // Invalid ID token
      header('location: ' . URL . 'admin');
    }
  }

  private function gCaptcha($captcha)
  {
    $data = [
      'secret' => GOOGLE_RECAPTCHA_SECREAT_KEY,
      'response' => $captcha,
      'remoteip' => (new Ip)->get()
    ];

    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    return json_decode($response);
  }
}
