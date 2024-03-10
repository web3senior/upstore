<?php

class About_Model extends Model
{

    function __construct()
    {
        parent::__construct();
        //echo 'about model';
    }

    public function description()
    {
        return $this->db->select('
                SELECT
                    public_description
                FROM
                    tbl_public
                LIMIT 0, 1
                ');
    }

    public function keywords()
    {
        return $this->db->select('
                SELECT
                    public_keywords
                FROM
                    tbl_public
                LIMIT 0, 1
                ');
    }

    public function logo()
    {
        return $this->db->select('
                SELECT
                    logo_image, logo_alt, logo_link, logo_title, logo_effect
                FROM
                    tbl_logo
                LIMIT 0, 1
                ');
    }

    public function footerinfo()
    {
        return $this->db->select('
                SELECT
                    footer_id, footer_content, footer_text_color, footer_bg_color
                FROM
                    tbl_footer
                ');
    }

    public function navigation()
    {
        return $this->db->select('
                SELECT
                    nav_bg_color, nav_text_color, nav_active_color
                FROM
                    tbl_navigation
                LIMIT 0, 1
                ');
    }

}
