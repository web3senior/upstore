<?php

class Admin_Model extends Model
{
  private $_session, $_time, $_date, $_ip, $_userAgent;

  function __construct()
  {
    parent::__construct();
    (new Session)->init();
    $this->_session = session_id();
    $this->_time = time();
    $this->_date = date('Y/m/d h:m:s');
    $this->_ip = $_SERVER['REMOTE_ADDR'];
    $this->_userAgent = $_SERVER['HTTP_USER_AGENT'];
  }
  public function login($data)
  {
      $data = $this->db->select(
          'SELECT * FROM `admin` WHERE `email`=:email AND `password`=:password;',
          [':email' => $data['email'], ':password' => $data['password']]
      );
      if (is_array($data) && !empty($data))
          return $data[0];
      else
          return 0;
  }
  protected function setSession($result)
  {
    $_email_body = "<body>
 <main style='padding: 20px;border: 40px solid #ffea80;line-height: 2.8rem;'>
  <table align='center' border='0' cellpadding='0' cellspacing='0' style='background-color:#ffffff; color:#091e42;font-size:16px; line-height:26px; margin:0 auto; text-align:left; width:80%'>
   <tbody>
    <tr>
     <td>Hi there,<br />
      I, the AR Bot, am here to inform you about a New Login Attempt.<br />
      Here are the details of the login attempt:<br />
      <br />
      URL = <strong>" . URL . "</strong><br />
      Date = <strong>" . $this->_date . "</strong><br />
      User Agent = <strong>" . $this->_userAgent . "</strong><br />
      IP Address = <strong>" . $this->_ip . "</strong><br />
      <br />
      If this wasn&rsquo;t you, we suggest you change your password in the <strong>Account</strong> section and enable Two-Factor Authentication on your account for better security.<br />
      <br />
      In case of any questions, contact our support through live chat or a ticket.<br />
      <br />Thank you,<br />AR Bot<br /><a href='https://nightdvlpr.ir' style='word-wrap:break-word;color:#6dc6dd;font-weight:normal;text-decoration:underline' target='_blank'>www.nightdvlpr.ir</a></td></tr></tbody></table></main></body>";

    (new Session)->init();
    (new Session)->set('loggedAdmin', true);
    (new Session)->set('adminInfo', $result);
    $chk = @md5($_SERVER['HTTP_ACCEPT_CHARSET'] . $_SERVER['HTTP_ACCEPT_ENCODING'] . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    (new Session)->set('key', $chk);
    (new Session)->set('expire_activity', time() + (8 * 60 * 60));

    // send email notification
    // (new Email)->mail_send(EMAIL, 'New Login Alert[' . URL . ']', $_email_body);
  }

  function getData($data)
  {

    $result = $this->db->select("
        SELECT
            *
        FROM
            `admin`
        WHERE
            `email` = :email AND `password` = :password LIMIT 1;", [':email' => $data['email'], ':password' => $data['password']]);

    if (!empty($result) && count($result) > 0) :
      $this->setSession($result);
      return true;
    endif;
    return false;
  }

  function getDataByEmail($email)
  {
    $result = $this->db->select('SELECT *, COUNT(admin_id) AS `total` FROM `admin` WHERE email=:email limit 1;',
      [':email' => $email]);

    if ($result[0]['total'] > 0) :
      $this->setSession($result);
      return true;
    endif;

    return false;
  }
}