<?php

class U_Model extends Model
{

  public function __construct()
  {
    parent::__construct();
  }

  private function getUserIdByUsername($username)
  {
    $result = $this->db->select("SELECT id FROM `user` u WHERE u.username = :username", [':username' => $username]);
    if (is_array($result) && !empty($result)) return $result[0]['id'];
    else  {
      throw new Exception('Username not found');
    }
  }

  function config($username)
  {
    $user_id = $this->getUserIdByUsername($username);
    $data = [
      "user_id" => $user_id,
      "ip" => (new Ip)->get()
    ];

    // Check duplicated view
    $isDuplicated = $this->db->select("select COUNT(id) AS `total` from `view` where `ip` = :ip AND `dt` LIKE '%" . date("Y-m-d") . "%' AND `user_id` = :user_id", [
      ':ip' => $data["ip"],
      ':user_id' => $user_id,
    ]);

    // A view per day
    if ($isDuplicated[0]['total'] < 1) $this->db->insert("view", $data);

    return  $this->db->select("SELECT * FROM `config` c INNER JOIN `user` u ON u.id = c.user_id WHERE u.username = :username", [':username' => $username]);
  }
}
