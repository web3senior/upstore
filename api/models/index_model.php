<?php

class Index_Model extends Model
{

  public function __construct()
  {
    parent::__construct();
  }

  public function product()
  {
    return $this->db->select('select * from `product` where `status`="1" limit 6;');
  }

  public function service()
  {
    return $this->db->select('select * from `service` where `status`="1" limit 10;');
  }

  public function category()
  {
    return $this->db->select('select * from `blogcategory` limit 20;');
  }

  public function slider()
  {
    return $this->db->select('select * from `slider`;');
  }

  public function blog()
  {
    return $this->db->select('
       SELECT
            b.*,
            CONCAT(SUBSTR(b.`content`, 1, 65),
            "...") AS `content`,
            c.*
        FROM
            `blog` b
         INNER JOIN `blogcategory` c ON
             b.`category_id` = c.`category_id`
        WHERE
            b.`status` = "1" LIMIT 2');
  }
}