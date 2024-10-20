<?php

class Panel_Model extends Model
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
        return $this->db->select("select * from `$tbl[0]`;");
      case "delete":
        return $this->db->delete("$tbl[0]", "`$tbl[1]`='$id'");
      case "insert":
        return $this->db->insert("$tbl[0]", $data);
      case "info":
        return $this->db->select("select * from `$tbl[0]` where `$tbl[1]`=:id", [':id' => $id]);
      case "update":
        return $this->db->update("$tbl[0]", $data, "`$tbl[1]`='{$id}'");
      default:
        die("O is unknown!");
    }
  }

  /**
   * @return array
   */
  public function dashboard()
  {
    return [
      'expert' => $this->db->select("select count(*) as `total` from `expert`;"),
      'department' => $this->db->select("select count(*) as `total` from `department`;"),
      'company' =>  $this->db->select("select count(*) as `total` from `company`;"),
      'deal' =>  $this->db->select("select count(*) as `total` from `deal`;"),
      'service' => $this->db->select("select count(*) as `total` from `service`;"),
      'access' =>  [["total" => 0]],
      'legal' =>   $this->db->select("select count(*) as `total` from `legal`;"),
      'password' =>  [["total" => 0]],
      'employee' =>   $this->db->select("select count(*) as `total` from `employee`;"),
       'accounting' =>   $this->db->select("select SUM(total) as `total` from `accounting`;"),
       'chart_accounting' =>   $this->db->select("select type, count(*) as `total` from `accounting` group by type;"),
       'chart_expert' =>   $this->db->select("select score, count(*) as `total` from `expert` group by score;"),
      'expert_chart' =>   $this->db->select("
      SELECT
    p.name AS `province_name`,
    COUNT(`province_id`) AS `total`
FROM
    `expert` e
INNER JOIN `province` p ON
    p.id = e.province_id
GROUP BY
    `province_id`
      "),
      'deal_to_end' =>   $this->db->select("
      SELECT
      COUNT(`id`) as `total`
  FROM
      `deal`
      WHERE DATE_ADD(`dt`, INTERVAL `period` DAY) > DATE_SUB('" . jdate('Y-m-d', time()) . "', INTERVAL 30 DAY)
      "),
      'deal_to_end_list' =>   $this->db->select("
      SELECT
     *
  FROM
      `deal`
      WHERE end_dt > '" . date_format(date_sub(date_create(jdate('Y-m-d', time(), '', '', 'en')), date_interval_create_from_date_string("60 days")), "Y-m-d") . "'
      "),
      'expertWithScore' =>   $this->db->select("select count(*) as `total` from `expert` where score='0';"),
      'expertWithoutScore' =>   $this->db->select("select count(*) as `total` from `expert` where score='1';")
    ];
  }

  function legal($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                l.*,
                c.`name` as `company_name`
            FROM
                `$tbl[0]` l
                inner join `company` c on c.id = l.company_id
            ORDER BY l.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function accounting($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                a.*
            FROM
                `$tbl[0]` a
            ORDER BY a.`total` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function setting($tbl, $start)
  {
    return $this->db->select("
            SELECT
                a.*,
                (select count(*) from `$tbl[0]`) as total
            FROM
                `$tbl[0]` a
            ORDER BY a.`id` DESC
            LIMIT $start, 10;");
  }

  function legal2($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                l.*,
                c.`name` as `company_name`
            FROM
                `$tbl[0]` l
                inner join `company` c on c.id = l.company_id
            ORDER BY l.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function service($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                l.*
            FROM
                `$tbl[0]` l
            ORDER BY l.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function access($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                *,
                a.`id` as `access_id`,
                a.`status` as `access_status`,
                a.`description` as `access_description`,
                e.`id` as `employee_id`
            FROM
                `$tbl[0]` a
            INNER JOIN `employee` e ON
                a.employee_id = e.id
            ORDER BY a.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function department($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
               *
            FROM
                `$tbl[0]`
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function employee($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `$tbl[0]` a
            ORDER BY a.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }
  function deal($tbl, $start)
  {
    $q = "";
    if (isset($_GET['q_fullname']) && !empty($_GET['q_fullname'])) {
      $q .= "AND e.`fullname` like '%" .trim($_GET['q_fullname']) . "%'  ";
    }
  
    return [
      'data' => $this->db->select("
      SELECT
      d.*,
      e.fullname as `expert_fullname`,
      dp.name as `department_name`
  FROM
      `deal` d
      inner JOIN `expert` e ON
      e.id = d.expert_id
      inner join `department` dp on
      dp.id = d.department_id
      WHERE d.status != -1 $q
  ORDER BY
      d.`id`
  DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function deal_view($id)
  {
    return $this->db->select("
    SELECT
    d.*,
    e.*,
    fs.*
FROM
    `deal` d
    inner JOIN `expert` e ON
    e.id = d.expert_id
    inner join `field_study` fs on
    e.`field_study_id` = fs.id
where d.id=:id", [":id" => $id]);
  }


  function deal2($tbl, $start)
  {
    $q = "";
    if (isset($_GET['q_fullname']) && !empty($_GET['q_fullname'])) {
      $q .= "AND c.`fullname` like '%" .trim($_GET['q_fullname']) . "%'  ";
    }
    

    return [
      'data' => $this->db->select("
      SELECT
      d.*,
      c.fullname as `expert_fullname`,
      dp.name as `department_name`
  FROM
      `deal2` d
      inner JOIN `client` c ON
      c.id = d.client_id
      inner join `department` dp on
      dp.id = d.department_id
      WHERE d.status != -1 $q
  ORDER BY
      d.`id`
  DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }


  function deal2_view($id)
  {
    return $this->db->select("
    SELECT
    d.*,
    c.*
FROM
    `deal2` d
    inner JOIN `client` c ON
    c.id = d.client_id
where d.id=:id", [":id" => $id]);
  }

  function expert($tbl, $start)
  {
    $q = "";
    if (isset($_GET['q_fullname']) && !empty($_GET['q_fullname'])) {
      $q .= "AND e.`fullname` like '%" .trim($_GET['q_fullname']) . "%'  ";
    }
    
    if (isset($_GET['q_nationalcode']) && !empty($_GET['q_nationalcode'])) {
      $q .= "AND e.`nationalcode` like '%" .trim($_GET['q_nationalcode']) . "%'  ";
    }

    return [
      'data' => $this->db->select("
      SELECT
      e.*,
      COUNT(eu.id) AS `expert_upload_count`,
      a.fullname as `admin_fullname`,
      p.name as `province_name`,
      c.name as `city_name`
  FROM
      `expert` e
      left JOIN `expert_upload` eu ON
      e.id = eu.expert_id
      left JOIN `admin` a ON
      e.admin_id = a.id
      inner join `province` p on p.id = e.province_id
      inner join `city` c on c.id = e.city_id 
      WHERE e.status != -1 $q
  GROUP BY
      e.id
  ORDER BY
      e.`id`
  DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function expert_export()
  {
    return  $this->db->select("
SELECT
    GROUP_CONCAT(`mobile`) as `mobiles`
FROM
    `expert`

");
  }
  
  function client_export()
  {
    return  $this->db->select("
SELECT
    GROUP_CONCAT(`mobile`) as `mobiles`
FROM
    `client`

");
  }

  function expert_view($id)
  {
    return $this->db->select("
    SELECT
    e.*,
    p.name as `province_name`,
    c.name as `city_name`
FROM
    `expert` e
    inner JOIN `province` p ON
    p.id =e.province_id
    inner JOIN `city` c ON
    c.id =e.city_id
where e.id=:id", [":id" => $id]);
  }


  function expert_upload($tbl, $id)
  {
    return  $this->db->select("
            SELECT
                *
            FROM
                `$tbl[0]` t
            WHERE
                `expert_id`=:expert_id
            ORDER BY t.`id` DESC", [":expert_id" => $id]);
  }
  function client($tbl, $start)
  {
    $q = "";
    if (isset($_GET['q_fullname']) && !empty($_GET['q_fullname'])) {
      $q .= "AND e.`fullname` like '%" .trim($_GET['q_fullname']) . "%'  ";
    }
    
    if (isset($_GET['q_nationalcode']) && !empty($_GET['q_nationalcode'])) {
      $q .= "AND e.`nationalcode` like '%" .trim($_GET['q_nationalcode']) . "%'  ";
    }
   
    return [
      'data' => $this->db->select("
      SELECT
      e.*,
      COUNT(eu.id) AS `expert_upload_count`,
      a.fullname as `admin_fullname`
  FROM
      `client` e
      left JOIN `client_upload` eu ON
      e.id = eu.client_id
      left JOIN `admin` a ON
      e.admin_id = a.id
      WHERE e.status != -1 $q
  GROUP BY
      e.id
  ORDER BY
      e.`id`
  DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }
  function client_upload($tbl, $id)
  {
    return  $this->db->select("
            SELECT
                *
            FROM
                `$tbl[0]` t
            WHERE
                `client_id`=:id
            ORDER BY t.`id` DESC", [":id" => $id]);
  }
  function password($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                p.*,
                c.name as `company_name`
            FROM
                `$tbl[0]` p
            inner join `company` c on 
            p.company_id = c.id
            ORDER BY p.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }

  function education($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `$tbl[0]` s
            ORDER BY s.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }


  function field_study($tbl, $start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `$tbl[0]` s
            ORDER BY s.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }
  function company($tbl, $start)
  {
    $q = "";
    if (isset($_GET['q_name']) && !empty($_GET['q_name'])) {
      $q .= "AND c.`name` like '%" .trim($_GET['q_name']) . "%'  ";
    }
  
    return [
      'data' => $this->db->select("
      SELECT
      c.*,
      a.fullname AS `admin_fullname`,
      p.name AS `province_name`,
      ci.name AS `city_name`
  FROM
      `company` c
  LEFT JOIN `admin` a ON
      c.admin_id = a.id
  LEFT JOIN `province` p ON
      c.province_id = p.id
  LEFT JOIN `city` ci ON
      c.city_id = ci.id
      WHERE c.status != -1 $q
  GROUP BY
      c.id
  ORDER BY
      c.`id`
  DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`$tbl[1]`) as `total` from `$tbl[0]`;")[0]['total']
    ];
  }


  function report()
  {
    return $this->db->select("
    SELECT
    cr.name,
     count(cr.name) as total
    FROM
        `company_rate` cr
    
    group by cr.name
");
  }

  function company_upload($tbl, $id)
  {
    return  $this->db->select("
            SELECT
                *
            FROM
                `$tbl[0]` t
            WHERE
                `company_id`=:id
            ORDER BY t.`id` DESC", [":id" => $id]);
  }


  function company_member($tbl, $id)
  {
    return  $this->db->select("
            SELECT
                t.*,
                e.fullname as `expert_fullname`,
                e.id as `expert_id`
            FROM
                `$tbl[0]` t
                inner join `expert` e on t.expert_id = e.id 
            WHERE
                `company_id`=:id
            ORDER BY t.`id` DESC", [":id" => $id]);
  }

  function company_rate($tbl, $id)
  {
    return  $this->db->select("
            SELECT
                *
            FROM
                `$tbl[0]` t
            WHERE
                `company_id`=:id
            ORDER BY t.`id` DESC", [":id" => $id]);
  }


  function company_expert($tbl, $id)
  {
    return  $this->db->select("
            SELECT
                t.*,
               
                e.id as `expert_id`,
                e.fullname as `expert_fullname`,
                e.score as `expert_score`
            FROM
                `$tbl[0]` t
                inner join expert as e on
                e.id = t.expert_id
            WHERE
                `company_id`=:id
            ORDER BY t.`id` DESC", [":id" => $id]);
  }


  function expert_in_company($id)
  {
    return  $this->db->select("
            SELECT
                ce.*,
               c.*,
                c.id as `company_id`
            FROM
                `company_expert` ce
                inner join company as c on
                c.id = ce.company_id
            WHERE
                ce.`expert_id`=:id
            ORDER BY ce.`id` DESC", [":id" => $id]);
  }

  function company_view($tbl, $id)
  {
    return  $this->db->select("
            SELECT
                c.*,
                a.fullname as `admin_fullname`,
                p.name as `province_name`,
                ci.name as `city_name`
            FROM
                `$tbl[0]` c
                left JOIN `admin` a ON
                c.admin_id = a.id

                left JOIN `province` p ON
                c.province_id = p.id
                
                left JOIN `city` ci ON
                c.city_id = ci.id
             
            where c.id = :id
            LIMIT 1;", [':id' => $id]);
  }























  function p_request_type($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `p_request_type` p
            ORDER BY p.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `p_request_type`;")[0]['total']
    ];
    return $r;
  }


  function p_request($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `p_request` p
            ORDER BY p.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `p_request`;")[0]['total']
    ];
    return $r;
  }

  function p_element_category($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `p_element_category` p
            ORDER BY p.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `p_element_category`;")[0]['total']
    ];
    return $r;
  }

  function p_admin($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `p_admin` p
            ORDER BY p.`id` DESC
            LIMIT $start, 10"),
      'total' => $this->db->select("select count(`id`) as `total` from `p_admin`;")[0]['total']
    ];
    return $r;
  }

  function idea($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                i.*,
                ic.`name` as `ideaCategoryName`
            FROM
                `idea` i
                inner join `ideacategory` ic
                on i.`ideacategory_id` = ic.`id`
            ORDER BY i.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `idea`;")[0]['total']
    ];
    return $r;
  }

  function config($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `config` c
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `config`;")[0]['total']
    ];
    return $r;
  }

  function event($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `event` e
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `event`;")[0]['total']
    ];
    return $r;
  }

  function link($start)
  {
    $q = " ";

    if (isset($_GET['t']) && !empty($_GET['t'])) {
      $q .= "where `title` like '%" . $_GET['t'] . "%'";
    }

    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `link` l
            $q
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `link` $q;")[0]['total']
    ];
    return $r;
  }



  function layer($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `layer` l
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `layer`;")[0]['total']
    ];
    return $r;
  }

  function statistics($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `statistics` l
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `statistics`;")[0]['total']
    ];
    return $r;
  }


  function gis($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `gis` l
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `gis`;")[0]['total']
    ];
    return $r;
  }
  function ideacategory($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `ideacategory` l
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `ideacategory`;")[0]['total']
    ];
    return $r;
  }

  function broadcast($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `broadcast` l
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `broadcast`;")[0]['total']
    ];
    return $r;
  }


  function book($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `book` l
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `book`;")[0]['total']
    ];
    return $r;
  }



  function project($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                *
            FROM
                `project` l
            ORDER BY `id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `project`;")[0]['total']
    ];
    return $r;
  }

  function sublayer($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
                s.*,
                l.`name` as `sublayer_name`
            FROM
                `sublayer` s
                inner join `layer` l
                on s.`layer_id` = l.`id`
            ORDER BY s.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `sublayer`;")[0]['total']
    ];
    return $r;
  }

  function billboard($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
           *
            FROM
                `billboard` s
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `billboard`;")[0]['total']
    ];
    return $r;
  }

  function ads($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
           *
            FROM
                `ads` a
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `ads`;")[0]['total']
    ];
    return $r;
  }

  function point($start)
  {
    $q = "";
    if (isset($_GET['id']) && !empty($_GET['id'])) {
      $q .= " AND p.`id` = '" . $_GET['id']  . "'";
    }

    if (isset($_GET['t']) && !empty($_GET['t'])) {
      $q .= " AND p.`title` like '%" . trim($_GET['t']) . "%'  ";
    }

    if (isset($_GET['sublayer_id']) && !empty($_GET['sublayer_id'])) {
      $q .= " AND p.`sublayer_id`='" . $_GET['sublayer_id'] . "'  ";
    }

    $r = [
      'data' => $this->db->select("
            SELECT
                p.*,
                s.`name` as `sublayer_name`
            FROM
                `point` p
                inner join `sublayer` s on p.`sublayer_id` = s.`id`
            WHERE p.`status` != :status $q
            ORDER BY p.`id` DESC
            LIMIT $start, 10", [':status' => 3]),
      'total' => $this->db->select("select count(`id`) as `total` from `point` p  WHERE p.`status` = :status $q", [':status' => 1])[0]['total']
    ];

    return $r;
  }


  function vr($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
               *
            FROM
                `vr` s
            ORDER BY s.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `vr`;")[0]['total']
    ];
    return $r;
  }
  function sharelink($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
               *
            FROM
                `sharelink` s
            ORDER BY s.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `sharelink`;")[0]['total']
    ];
    return $r;
  }
  function gallery($start)
  {
    $r = [
      'data' => $this->db->select("
            SELECT
               *
            FROM
                `gallery` s
            ORDER BY s.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `gallery`;")[0]['total']
    ];
    return $r;
  }

  function telephone($start)
  {
    $q = " ";
    if (isset($_GET['name']) && !empty($_GET['name'])) {
      $q .= "where `name` LIKE '%" . $_GET['name']  . "%'";
    }

    $r = [
      'data' => $this->db->select("
            SELECT
               *
            FROM
                `tel` s
                $q
            ORDER BY s.`id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`id`) as `total` from `tel`;")[0]['total']
    ];
    return $r;
  }





  /**
   * @return mixed
   */
  public function configuration()
  {
    return $this->db->select('SELECT `configuration_id`, `name`, `email`, `phone`, `avatar`, `sex`, `description` FROM `configuration` LIMIT 1;');
  }

  /**
   * @param $data
   * @return bool
   */
  public function configuration_update($data)
  {
    return $this->db->update('configuration', $data, "`configuration_id`='1'");
  }

  function cashbox($start)
  {
    return [
      'data' => $this->db->select("
      SELECT
          *
      FROM
          cashbox cb
      ORDER BY
          cb.`cashbox_id`
      DESC
      "),
      'total' => $this->db->select("select count(`cashbox_id`) as `total` from `cashbox`")[0]['total'],
      'sum' => $this->db->select("select SUM(totalline) AS `sum` from `cashbox`")[0]['sum'],
      'group_name' => $this->db->select("
SELECT
    group_name,
    SUM(totalline) as `total`
FROM
    `cashbox`
GROUP BY
    `group_name`
")
    ];
  }

  function fee($start)
  {
    return [
      'data' => $this->db->select("
      SELECT
          f.*,
          (SELECT `name` FROM `unit` WHERE `unit_id` = `f`.`buy_unit`) as buy_unit_name,
          (SELECT `name` FROM `unit` WHERE `unit_id` = `f`.`pay_unit`) as pay_unit_name,
          (SELECT `name` FROM `paytype` WHERE `paytype_id` = `f`.`customer_pay_type`) as customer_pay_type_name,
          (SELECT `name` FROM `paytype` WHERE `paytype_id` = `f`.`customer_receive_type`) as customer_receive_type_name
      FROM
          fee f
      ORDER BY f.`fee_id` DESC 
      LIMIT $start, 10"),
      'total' => $this->db->select("select count(*) as `total` from `fee`")[0]['total']
    ];
  }

  function bank($start)
  {
    $r = [
      'data' => $this->db->select("
        SELECT
        b.*,
        c.name as country_name,
        s.name as state_name
        from 
        bank b
        INNER join countries c 
        on b.country_id = c.country_id
        INNER join states s
        on b.state_id = s.state_id
            ORDER BY b.`bank_id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`bank_id`) as `total` from `bank`;")[0]['total']
    ];
    return $r;
  }



  function rate($start)
  {
    return [
      'data' => $this->db->select("
      SELECT
          r.*,
          u.name
      FROM
          rate r
      INNER JOIN unit u ON
          r.unit_id = u.unit_id
      ORDER BY
          r.`createdAt`
      DESC
      LIMIT $start, 10;"),
      'total' => $this->db->select("select count(*) as `total` from `rate`;")[0]['total']
    ];
  }

  function resource($start, $where)
  {
    return [
      'data' => $this->db->select("
      SELECT
          r.*,
          u.name as unit_name,
          a.fullname
      FROM
          resource r
      INNER JOIN unit u ON
          r.unit_id = u.unit_id
      INNER JOIN admin a ON
          a.admin_id = r.admin_id
      $where
      ORDER BY
          r.`resource_id`
      DESC
      LIMIT $start, 10;"),
      'total' => $this->db->select("select count(*) as `total` from `resource`;")[0]['total']
    ];
  }

  function feeSearch($data)
  {
    return $this->db->select("
SELECT
    *
FROM
    fee f
WHERE
    f.buy_unit = :bu AND f.pay_unit = :py AND customer_pay_type = :cpy AND customer_receive_type = :crt
      ", [':bu' => $data->buy_currency, ':py' => $data->pay_currency, ':cpy' => $data->customer_pay_type, ':crt' => $data->customer_receive_type]);
  }

  function rateSearch($buy_currency)
  {
    return $this->db->select("
    SELECT
        *
    FROM
        rate r
    WHERE
        r.unit_id = :ui 
    LIMIT 1
      ", [':ui' => $buy_currency]);
  }

  function exchange($start, $where)
  {
    return [
      'data' => $this->db->select("
SELECT
    e.*,
    e.status AS exchange_status,
    a.fullname,
    c.*,
    r.name AS reason_name,
    rate.buy AS rate_buy,
    rate.sell AS rate_sell,
    COUNT(t.transaction_id) AS transaction_count,
    f.*,
    (SELECT `name` FROM `unit` WHERE `unit_id` = `f`.`buy_unit`) as buy_unit_name,
    (SELECT `name` FROM `unit` WHERE `unit_id` = `f`.`pay_unit`) as pay_unit_name,
    (SELECT `name` FROM `paytype` WHERE `paytype_id` = `f`.`customer_pay_type`) as customer_pay_type_name,
    (SELECT `name` FROM `paytype` WHERE `paytype_id` = `f`.`customer_receive_type`) as customer_receive_type_name
FROM
    exchange e
left JOIN `transaction` t ON
    t.exchange_id = e.exchange_id
INNER JOIN admin a ON
    a.admin_id = e.admin_id
INNER JOIN CLIENT c ON
    c.client_id = e.client_id
INNER JOIN reason r ON
    r.reason_id = e.reason_id
INNER JOIN rate ON rate.rate_id = e.rate_id
INNER JOIN fee f ON
    f.fee_id = e.fee_id
$where
GROUP BY
    e.exchange_id
ORDER BY
    e.`exchange_id`
DESC
    
      LIMIT $start, 10;"),
      'total' => $this->db->select("select count(*) as `total` from `exchange`;")[0]['total']
    ];
  }

  function exchangeFetch($id)
  {
    return  $this->db->select("
SELECT
    e.*,
    f.*
FROM
    exchange e
INNER JOIN fee f ON
    e.fee_id = f.fee_id
    WHERE e.exchange_id=:id
LIMIT 1;
      ", [':id' => $id]);
  }


  function transaction($start, $where)
  {
    return [
      'data' => $this->db->select("
      SELECT
      t.*,
      `t`.`createdAt` as `trans_createdAt`,
      `t`.`amount` as `trans_amount`,
      e.`total_customer`,
      e.`amount` as `exchange_amount`,
      a.*,
      r.*,
      u.name as unit_name
  FROM
      transaction t
  INNER JOIN exchange e ON
      e.exchange_id = t.exchange_id
  left JOIN account a ON
      a.account_id = t.account_id
  left JOIN resource r ON
      r.resource_id = t.resource_id
  INNER JOIN unit u ON
      u.unit_id = t.unit_id
      $where
  ORDER BY
      t.`transaction_id`
  DESC
LIMIT $start, 10;
      "),
      'total' => $this->db->select("select count(*) as `total` from `transaction`;")[0]['total']
    ];
  }

  function fetchUserBank($exchange_id)
  {
    return $this->db->select("
SELECT
    *
FROM
    `account` a
INNER JOIN CLIENT c ON
    c.client_id = a.client_id
INNER JOIN bank b ON
    b.bank_id = a.bank_id
WHERE
    a.client_id =(
    SELECT
        client_id
    FROM
        exchange
    WHERE
        exchange_id = :id
)
", ['id' => $exchange_id]);
  }

  function account($start, $where = null)
  {
    return [
      'data' => $this->db->select("
SELECT
    a.*,
       a.`status` as `account_status`,
    c.`client_id`,
       c.`firstname`,
       c.`lastname`,
    b.*,
       b.`name` as `bank_name`,
    u.`name` as `unit_name`
FROM
    account a
INNER JOIN CLIENT c ON
    a.client_id = c.client_id
INNER JOIN bank b ON
    b.bank_id = a.bank_id
INNER JOIN unit u ON
    u.unit_id = a.unit_id
" . $where . "
ORDER BY
    a.`account_id`
DESC
    
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`account_id`) as `total` from `account`;")[0]['total']
    ];
  }

  /**
   * ROOT
   */

  public function state()
  {
    return $this->db->select('SELECT * FROM tbl_state;');
  }

  public function city()
  {
    return $this->db->select('SELECT * FROM tbl_city;');
  }

  public function area()
  {
    return $this->db->select('SELECT * FROM tbl_area;');
  }

  public function base()
  {
    return $this->db->select('SELECT * FROM tbl_base;');
  }

  public function fieldlist()
  {
    return $this->db->select('SELECT * FROM tbl_fieldlist;');
  }

  public function edu()
  {
    return $this->db->select('SELECT * FROM tbl_edu WHERE `status`=:status;', array(':status' => 1));
  }

  function msgadmin()
  {
    return $this->db->select('SELECT * FROM `tbl_msgadmin` WHERE `status` = "1" ORDER BY `msgadmin_id` ASC;');
  }

  public function countries()
  {
    $data = $this->db->select('SELECT * FROM countries;');
    return $data;
  }

  function xhrInsert()
  {
    $text = $_POST['text'];

    $this->db->insert('data', array('text' => $text));
    $data = array('text' => $text, 'id' => $this->db->lastInsertId());
    echo json_encode($data);
  }

  function xhrGetListings()
  {
    $result = $this->db->select("SELECT * FROM data");
    echo json_encode($result);
  }

  function xhrDelete()
  {
    $id = (int)$_POST['id'];
    $this->db->delete('data', "id = '$id'");
  }
  /**
   * @param $start
   * @return array
   */


  /**
   * unit
   */
  function unit($start, $filter = false)
  {
    if ($filter) $filter = 'WHERE c.`class_id`=' . $filter;
    $r = $this->db->select("
            SELECT
                *
            FROM
                `unit`
            LIMIT $start, 10;");
    return $r;
  }


  /**
   * @param $start
   * @return array
   */


  /**
   * @param integer $start
   * @return array
   */
  function blog($start)
  {
    $r = array(
      'data' => $this->db->select("
            SELECT
                b.*,
                c.*
            FROM
                `blog` b
            INNER JOIN `blogcategory` c ON
                b.`category_id` = c.`category_id`
            ORDER BY `blog_id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`blog_id`) as `total` from `blog`;")[0]['total']
    );
    return $r;
  }

  /**
   * feedback
   */
  function feedback($start)
  {
    $r = array(
      'data' => $this->db->select("
            SELECT
                hf.*,
                hf.`voice` as `hf_voice`,
                hf.`content` as `hf_content`,
                hf.`img` as `hf_img`,
                h.*,
                u.*
            FROM
                `homework_feedback` hf
            INNER JOIN `homework` h ON
                    h.`homework_id` = hf.`homework_id`
                INNER JOIN `user` u ON
                    u.`user_id` = hf.`user_id`    
            ORDER BY hf.`hf_id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`hf_id`) as `total` from `homework_feedback`;")[0]['total']
    );
    return $r;
  }

  /**
   * @param integer $start
   * @return array
   */
  function quiz($start)
  {
    return [
      'data' => $this->db->select("
            SELECT
                q.*,
                b.`subcategory_id`,
                b.`name` AS `subcategory_name`,
                c.`category_id`,
                c.`name` AS `category_name`
            FROM
                `quiz` q
            INNER JOIN `subcategory` b ON
                    q.`subcategory_id` = b.`subcategory_id`
                INNER JOIN `category` c ON
                    b.`category_id` = c.`category_id`    
            ORDER BY `quiz_id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`quiz_id`) as `total` from `quiz`;")[0]['total']
    ];
  }

  /**
   * @param integer $start
   * @return array
   */
  function quizfeedback($start)
  {
    $r = array(
      'data' => $this->db->select("
            SELECT
                q.*,
                u.*,
                c.*
            FROM
                `quizfeedback` q
            INNER JOIN `user` u ON
                    q.`user_id` = u.`user_id`
                INNER JOIN `createquiz` c ON
                    q.`createquiz_id` = c.`createquiz_id`    
            ORDER BY `quizfeedback_id` DESC LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`quizfeedback_id`) as `total` from `quizfeedback`;")[0]['total']
    );
    return $r;
  }

  /**
   * contact
   */
  function contact($start)
  {
    $r = array(
      'data' => $this->db->select("
            SELECT
                b.*
            FROM
                `contact` b
            ORDER BY `contact_id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`contact_id`) as `total` from `contact`;")[0]['total']
    );
    return $r;
  }

  /**
   * question
   * @param integer $start
   * @return array
   */
  function question($start)
  {
    $r = array(
      'data' => $this->db->select("
                SELECT
                    a.*,
                    STRCMP(a.`q`, a.`a`) as `similarity`,
                    b.`subcategory_id`,
                    b.`name` AS `subcategory_name`,
                    c.`category_id`,
                    c.`name` AS `category_name`
                FROM
                    `question` a
                INNER JOIN `subcategory` b ON
                    a.`subcategory_id` = b.`subcategory_id`
                INNER JOIN `category` c ON
                    b.`category_id` = c.`category_id`
                ORDER BY
                    a.`question_id`
                DESC LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`question_id`) as `total` from `question`;")[0]['total']
    );
    return $r;
  }

  function question_duplicate($id)
  {
    $r = $this->db->select("
                INSERT INTO `homework`(
                    `subcategory_id`,
                    `type`,
                    `title`,
                    `description`,
                    `image`,
                    `video`,
                    `voice`,
                    `countdown`,
                    `tip`
                )
                SELECT
                    `subcategory_id`,
                    `type`,
                    `title`,
                    `description`,
                    `image`,
                    `video`,
                    `voice`,
                    `countdown`,
                    `tip`
                FROM
                    homework
                WHERE
                    homework_id = (SELECT
                    `homework_id` as `last_id`
                FROM
                    `homework`
                WHERE
                    `homework_id`=:id
                ORDER BY `homework_id` DESC
                LIMIT 1)
                ", array(':id' => $id));
    return $r;
  }

  /**
   * ticket
   * @param integer $start
   * @return array
   */
  function ticket($start)
  {
    $r = array(
      'data' => $this->db->select("
                SELECT
                    t.*,
                    u.`fullname`,
                    CASE WHEN t.`status` = '0' THEN 'در انتظار پاسخ' WHEN t.`status` = '1' THEN 'در حال رسیدگی' WHEN t.`status` = '2' THEN 'بسته شده' ELSE 'Unknown' END AS `statusText`
                FROM
                    `ticket` t
                INNER JOIN `user` u ON
                    t.`user_id` = u.`user_id`
                ORDER BY
                    t.`ticket_id` DESC LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`ticket_id`) as `total` from `ticket`;")[0]['total']
    );
    return $r;
  }

  function ticketCount()
  {
    $r = $this->db->select("
                SELECT CASE WHEN
                    t.`status` = '0' THEN 'در انتظار پاسخ' WHEN t.`status` = '1' THEN 'در حال رسیدگی' WHEN t.`status` = '2' THEN 'بسته شده' ELSE 'Unknown'
                END AS `statusText`,
                COUNT(t.`ticket_id`) AS `count`
                FROM
                    `ticket` t
                GROUP BY
                    t.`status`");
    return $r;
  }

  /**
   * @param integer $start
   * @return array
   */
  function invoice($start)
  {
    $r = array(
      'data' => $this->db->select("
             SELECT
                `t1`.*,`t1`.`status` as `invoice_status`,
                `t2`.*,
                `t3`.*
            FROM
                `invoice` t1
            INNER JOIN `pack` t2 ON
                t1.`pack_id` = t2.`pack_id`
            INNER JOIN `user` t3 ON
                t1.`user_id` = t3.`user_id`
            ORDER BY t1.`invoice_id` DESC LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`pack_id`) as `total` from `pack`;")[0]['total']
    );
    return $r;
  }

  /**
   * @param integer $start
   * @return array
   */
  function pack($start)
  {
    $r = array(
      'data' => $this->db->select("
            SELECT
                t.*
            FROM
                `pack` t
            ORDER BY t.`pack_id` DESC
            LIMIT $start, 10;"),
      'total' => $this->db->select("select count(`pack_id`) as `total` from `pack`;")[0]['total']
    );
    return $r;
  }


  function allUnit()
  {
    $r = $this->db->select("
            SELECT
                b.`name` as `unit_name`,
                b.*,
                c.`name` as `class_name`,
                c.`class_id`
            FROM
                `unit` b
            INNER JOIN `class` c ON
                b.`class_id` = c.`class_id`
            ORDER BY `unit_id` DESC");
    return $r;
  }

  function set_question_homework($question_id, $unit_id, $data)
  {

    return $this->db->update('unit', $data, "`unit_id`='{$unit_id}'");
  }

  function unit_total()
  {
    $r = $this->db->select("
            SELECT
            COUNT(`unit_id`) as `total`
            FROM
                `unit`;
                ")[0]['total'];
    return $r;
  }

  function vocabulary($start, $filter = false)
  {
    if ($filter) $filter = 'WHERE c.`vocabsubcategory_id`=' . $filter;
    $r = $this->db->select("
            SELECT
                v.`name` AS `vocabulary_name`,
                v.*,
                vc.*,
                vc.`name` AS `vocabcategory_name`
            FROM
                `vocabulary` v
            INNER JOIN `vocabulary_subcategory` vc ON
                v.`vocabsubcategory_id` = vc.`vocabsubcategory_id`  $filter
            ORDER BY
                v.`vocabulary_id`
            DESC LIMIT $start, 10;");
    return $r;
  }

  function vocabulary_total()
  {
    $r = $this->db->select("
            SELECT
            COUNT(`vocabulary_id`) as `total`
            FROM
                `vocabulary`;
                ")[0]['total'];
    return $r;
  }

  /**
   * classification
   */
  function classification($start)
  {
    $r = $this->db->select("
            SELECT
            c.*,
            c.`name` as `class_name`,
            p.name as `pack_name`
            FROM
                `class` c
            INNER JOIN `pack` p ON
                c.`pack_id` = p.`pack_id`
            ORDER BY `class_id` DESC
            LIMIT $start, 10;");
    return $r;
  }

  /**
   * gap
   */
  function gap($start)
  {
    $r = $this->db->select("
            SELECT
                *
            FROM
                `gap` g
            ORDER BY `gap_id` DESC
            LIMIT $start, 10;");
    return $r;
  }

  /**
   * catquestion
   */
  function catquestion($start)
  {
    $r = $this->db->select("
            SELECT
                *
            FROM
                `category` a
            ORDER BY `category_id` DESC
            LIMIT $start, 10;");
    return $r;
  }

  function subcatquestion($start)
  {
    $r = $this->db->select("
            SELECT
                a.*,
                a.`name` as `subcategory_name`,
                b.*,
                b.`name` as `category_name`
            FROM
                `subcategory` a
            INNER JOIN `category` b ON
                a.`category_id` = b.`category_id`
            ORDER BY `subcategory_id` DESC
            LIMIT $start, 10;");
    return $r;
  }


  function subcategory()
  {
    $r = $this->db->select("
            SELECT
                s.`subcategory_id`,
                 c.`name` AS `category_name`,
                s.`name` AS `subcategory_name`
            FROM
                `category` c
            INNER JOIN `subcategory` s ON
                c.`category_id` = s.`category_id`");
    return $r;
  }

  function vocabsubcategory()
  {
    $r = $this->db->select("
            SELECT
            cat.*,
            sub.*,
                cat.`name` AS `category_name`,
                sub.`name` AS `subcategory_name`
            FROM
                `vocabulary_category` cat
            INNER JOIN `vocabulary_subcategory` sub ON
                cat.`vocabcategory_id` = sub.`vocabcategory_id`");
    return $r;
  }

  function vocabularySubCategory($start)
  {
    $r = $this->db->select("
            SELECT
                cat.*,
                sub.*,
                cat.`name` AS `category_name`,
                sub.`name` AS `subcategory_name`
            FROM
                `vocabulary_category` cat
            INNER JOIN `vocabulary_subcategory` sub ON
                cat.`vocabcategory_id` = sub.`vocabcategory_id`
            LIMIT $start, 10;");
    return $r;
  }

  /**
   * sub menu
   * @param $start
   * @return mixed
   */
  function submenu($start)
  {
    $r = $this->db->select("
            SELECT
                s.*,
                m.*,
                s.`name` as `submenu_name`,
                s.`link` as `submenu_link`,
                s.`priority` as `submenu_priority`
            FROM
                `submenu` s
            INNER JOIN `menu` m ON
                s.`menu_id` = m.`menu_id`
            LIMIT $start, 10;");
    return $r;
  }


  /**
   * userclass
   * @param $start
   * @return mixed
   */
  function userclass($start)
  {
    $r = $this->db->select("
            SELECT
                uc.*,
                u.`fullname` AS `user_fullname`,
                u.`email` AS `user_email`,
                c.`name` AS `class_name`
            FROM
                `userclass` uc
            INNER JOIN `user` u ON
                u.`user_id` = uc.`user_id`
            INNER JOIN `class` c ON
                c.`class_id` = uc.`class_id`
            ORDER BY
                `userclass_id`
            DESC
            LIMIT $start, 10;");
    return $r;
  }


  function security($data)
  {
    $r = $this->db->select(
      'SELECT *,COUNT(admin_id) AS `total` FROM admin WHERE email = :email AND password = :password;',
      array(
        ':email' => $data['email'],
        ':password' => $data['password']
      )
    );

    if ($r[0]['total'] > 0) :
      return $this->db->update('admin', array('password' => $data['newpassword']), "`email`='{$data['email']}'");
    endif;

    return false;
  }

  public function admin_update($data, $id = 1)
  {
    return $this->db->update('admin', $data, "`id`='{$id}'");
  }
}
