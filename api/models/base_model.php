<?php

class Base_Model extends Model
{

    function __construct()
    {
        parent::__construct();
    }

    function access()
    {
        return $this->db->select('SELECT * FROM `tbl_access` WHERE `access_status` = 1', array());
    }

    function script()
    {
        return $this->db->select('SELECT * FROM `tbl_script` WHERE `script_status` = 1', array());
    }

    function text()
    {
        return $this->db->select('SELECT * FROM `tbl_text` LIMIT 0, 1');
    }

    public function logo()
    {
        return $this->db->select('
                SELECT logo_image, logo_alt, logo_link, logo_title, logo_effect FROM tbl_logo LIMIT 0, 1 ');
    }

    function theme()
    {
        return $this->db->select('SELECT * FROM `tbl_theme` LIMIT 0, 1');
    }

    function slider()
    {
        return $this->db->select('SELECT * FROM `tbl_slider` LIMIT 0, 10');
    }

    function blog()
    {
        return $this->db->select('SELECT * FROM `tbl_blog` WHERE `status` = "1" ORDER BY `blog_id` DESC;');
    }

    function news()
    {
        return $this->db->select('SELECT * FROM `tbl_news` WHERE `status` = "1" ORDER BY `news_id` DESC LIMIT 0, 6;');
    }

    function links()
    {
        return $this->db->select('SELECT * FROM `tbl_links` LIMIT 0, 10');
    }

    function product()
    {
        return $this->db->select('SELECT * FROM `tbl_product` WHERE `product_status` = "1" LIMIT 0, 10');
    }

    function pictures()
    {
        return $this->db->select('SELECT * FROM `tbl_gallery` WHERE `gallery_status` = "1" ORDER BY `gallery_id` DESC LIMIT 0, 10;');
    }

    function state()
    {
        return $this->db->select('SELECT * FROM `tbl_state` WHERE `status` = "1" ORDER BY `name` ASC;');
    }

    function city()
    {
        return $this->db->select('SELECT * FROM `tbl_city` WHERE `status` = "1" ORDER BY `name` ASC;');
    }

    function university()
    {
        return $this->db->select('SELECT * FROM `tbl_university` WHERE `status` = "1" ORDER BY `name` DESC;');
    }

    public function fact()
    {
        $data = array(
            'student' => $this->db->select('SELECT COUNT(`student_id`) AS `total` FROM `tbl_student`;'),
            'film' => $this->db->select('SELECT COUNT(`film_id`) AS `total` FROM `tbl_film`;'),
            'course' => $this->db->select('SELECT SUM(time_to_sec(`time`)) As timeSum FROM `tbl_film`;'), /* ('SELECT SEC_TO_TIME( SUM(time_to_sec(`time`))) As timeSum FROM `tbl_film`;'), */
            'learncenter' => $this->db->select('SELECT COUNT(`film_id`) AS `total` FROM `tbl_film`;'),
        );
        return $data;
    }

}
