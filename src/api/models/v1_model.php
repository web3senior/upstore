<?php

class V1_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function command($o, $tbl, $data = false, $id = false)
    {
        switch ($o) {
            case "count":
                return $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;");
            case "fetch":
                return $this->db->select("select * from `$tbl[0]`");
            case "delete":
                return $this->db->delete("$tbl[0]", "`$tbl[1]`='$id'");
            case "insert":
                return $this->db->insert("$tbl[0]", $data);
            case "info":
                return $this->db->select("select * from `$tbl[0]` where `$tbl[1]`=:val", [':val' => $id]);
            case "update":
                return $this->db->update("$tbl[0]", $data, "`$tbl[1]`='{$id}'");
            default:
                die("O is unknown!");
        }
    }

    function getUserIdByAddress($addr)
    {
        return $this->db->select("SELECT id FROM `user` u WHERE u.`wallet_addr` = :addr", [':addr' => $addr])[0]['id'];
    }

    function getTelegramId($addr)
    {
        return $this->db->select("SELECT telegramId FROM `user` u WHERE u.`wallet_addr` = :addr", [':addr' => $addr])[0]['telegramId'];
    }

    function getUserIdByUsername($username)
    {
        return $this->db->select("SELECT id FROM `user` u WHERE u.username = :username", [':username' => $username])[0]['id'];
    }

    function getUser($addr)
    {
        return $this->db->select("SELECT * FROM `user` u WHERE u.`wallet_addr` = :addr", [':addr' => $addr])[0]['id'];
    }

    function dashboard($addr)
    {
        $past7days = strtotime("-7 day", strtotime(date('y-m-d')));

        $data = [
            'link' =>  count(json_decode($this->db->select('SELECT * FROM `config` c inner join `user` u ON c.`user_id` = u.`id` WHERE u.`wallet_addr` = :addr', [':addr' => $addr])[0]['links'])),
            'view' =>  $this->db->select('SELECT COUNT(*) as `total` FROM `view` v inner join `user` u ON v.`user_id`=u.`id` WHERE u.`wallet_addr` = :addr', [':addr' => $addr])[0]['total'],
            'event' =>  $this->db->select('SELECT COUNT(*) as `total` FROM `event` e inner join `user` u ON e.`user_id`=u.`id` WHERE u.`wallet_addr` = :addr', [':addr' => $addr])[0]['total'],
            'past24hours' => [
                'view' =>  $this->db->select('SELECT COUNT(*) as `total` FROM `view` v inner join `user` u ON v.`user_id`=u.`id` WHERE u.`wallet_addr` = :addr AND v.`dt` LIKE "%' . date("Y-m-d") . '%"', [':addr' => $addr])[0]['total'],
                'event' =>  $this->db->select('SELECT COUNT(*) as `total` FROM `event` e inner join `user` u ON e.`user_id`=u.`id` WHERE u.`wallet_addr` = :addr AND e.`dt` LIKE "%' . date("Y-m-d") . '%"', [':addr' => $addr])[0]['total'],
            ],
            'past7days' =>  [
                'view' =>  $this->db->select('SELECT COUNT(*) as `total`, UNIX_TIMESTAMP(v.`dt`) as `dt` FROM `view` v inner join `user` u ON v.`user_id`=u.`id` WHERE u.`wallet_addr` = :addr AND v.`dt` > "' .  $past7days . '"', [':addr' => $addr])[0]['total'],
                'event' =>  $this->db->select('SELECT COUNT(*) as `total`, UNIX_TIMESTAMP(e.`dt`) as `dt` FROM `event` e inner join `user` u ON e.`user_id`=u.`id` WHERE u.`wallet_addr` = :addr AND e.`dt` > "' . $past7days . '"', [':addr' => $addr])[0]['total'],
            ]
        ];

        $data['past24hours']['ctr'] = ceil(($data['past24hours']['event'] / (($data['past24hours']['view'] < 1) ? 1 : $data['past24hours']['view'])) * 100);
        $data['past7days']['ctr'] = ceil(($data['past7days']['event'] / (($data['past7days']['view'] < 1) ? 1 : $data['past7days']['view'])) * 100);
        $data['ctr'] = ceil(($data['event'] / (($data['view'] < 1) ? 1 : $data['view'])) * 100);
        return $data;
    }

    function event($addr)
    {
        return $this->db->select("SELECT *, COUNT(*) AS `count` FROM event WHERE `user_id` = :id LIMIT 1;", [':id' => $this->getUserIdByAddress($addr)]);
    }

    function eventChart($addr)
    {
        return [
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-01%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-02%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-03%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-04%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-05%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-06%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-07%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-08%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-09%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-10%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-11%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `event` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-12%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
        ];
    }

    function view($addr)
    {
        return $this->db->select("
SELECT
    *,
    COUNT(*) AS `count`
FROM
    view
WHERE
    `user_id` = :id
LIMIT 1
        ", [':id' => $this->getUserIdByAddress($addr)]);
    }

    function viewChart($addr)
    {
        return [
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-01%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-02%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-03%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-04%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-05%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-06%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-07%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-08%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-09%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-10%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-11%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
            $this->db->select("SELECT COUNT(*) AS `count` FROM `view` WHERE `user_id` = :id AND `dt` like '%" . date("Y") . "-12%'", [':id' => $this->getUserIdByAddress($addr)])[0]["count"],
        ];
    }

    function addEvent($username, $event, $name)
    {
        $user_id = $this->getUserIdByUsername($username);
        $data = [
            "user_id" => $user_id,
            "event" => $event,
            "name" => $name,
            "ip" => (new Ip)->get(),
        ];
       return $this->db->insert("event", $data);
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
        if ($isDuplicated[0]['total'] < 1) $this->command("insert", ["view", "id"], $data);

        return  $this->db->select("SELECT * FROM `config` c INNER JOIN `user` u ON u.id = c.user_id WHERE u.username = :username", [':username' => $username]);
    }

    function configByWallet($addr)
    {
        $result =  $this->db->select("SELECT * FROM `config` c INNER JOIN `user` u ON u.id = c.user_id WHERE u.`wallet_addr` = :addr", [':addr' => $addr]);

        if (count($result) < 1) {
            $user_id = $this->command('insert', ['user', 'id'], [
                "wallet_addr" => $addr,
                "username" => "ul" . time()
            ]);

            $this->command('insert', ['config', 'id'], [
                "user_id" => $user_id
            ]);
            $result =  $this->db->select("SELECT * FROM `config` c INNER JOIN `user` u ON u.id = c.user_id WHERE u.`wallet_addr` = :addr", [':addr' => $addr]);
        }

        return $result;
    }


    function updateConfig($data, $addr)
    {
        $user_id =  $this->db->select("
SELECT
    id
FROM
    `user` u
WHERE
    u.`wallet_addr` = :addr", [':addr' => $addr])[0]['id'];

        return $this->db->update("config", $data, "`user_id`='{$user_id}'");
    }

    function updateUser($data, $addr)
    {
        $isFree = $this->db->select("select COUNT(*) as `total` from `user` where `username` = :username", [':username' => $data['username']]);
        if ($isFree[0]["total"] < 1) return $this->db->update("user", $data, "`id` = '{$this->getUserIdByAddress($addr)}'");
    }
   function updateTelegramId($data, $addr)
    {
        return $this->db->update("user", $data, "`id` = '{$this->getUserIdByAddress($addr)}'");
    }
    function checkUser($data)
    {
        $result = $this->db->select("select COUNT(*) as `total` from `user` where `username` = :username", [':username' => $data['username']]);
        if ($result[0]["total"] < 1) return true;
        else return false;
    }
}
