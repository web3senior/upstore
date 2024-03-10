<?php


class Paging
{
  protected $_path, $_total;
  private $_HTMLcountent, $first_page_icon, $last_page_icon;

  public function __construct()
  {
    $this->first_page_icon = '<i class="ms-Icon ms-Icon--ChevronLeftEnd6 v-m" aria-hidden="true"></i>';
    $this->last_page_icon = ' <i class="ms-Icon ms-Icon--ChevronRightEnd6 v-m" aria-hidden="true"></i>';
  }

  public function getTotal($total)
  {
    $this->_total = ceil(($total / 10));
    return $this->_total;
  }

  private function header()
  {
    return '<div class="paging d-flex flex-row align-content-center justify-content-center">';
  }


  private function footer()
  {
    return '</div>';
  }

  private function firstPage()
  {
    return '<a href="' . URL . $this->_path . '?page=1" class="page">' . $this->first_page_icon . '</a>';
  }

  private function lastPage()
  {
    return '<a href="' . URL . $this->_path . '?page=' . $this->_total . '" class="page">' . $this->last_page_icon . '</a>';
  }

  function show($path, $total, $curr_page, $filter = false)
  {
    $this->_path = $path;
    $this->_total = ceil(($total / 10));


    if ($this->_total > 1) {
      $this->_HTMLcountent .= $this->header();

      $this->_HTMLcountent .= ($curr_page > 1) ? $this->firstPage() : null;

      if ($curr_page >= 3) {
        for ($i = $curr_page - 2; $i <= $curr_page - 1; $i++) {
          $this->_HTMLcountent .= "<a href='" . URL . $this->_path . "?page=" . $i . $filter . "' class='page " . (($curr_page == $i) ? 'active' : null) . "'>$i</a>";
        }
      }

      if ($this->_total <= 3) {
        for ($i = 1; $i <= $this->_total; $i++)
          $this->_HTMLcountent .= "<a href='" . URL . $this->_path . "?page=" . $i . $filter . "' class='page " . (($curr_page == $i) ? 'active' : null) . "'>$i</a>";

      } else if ($this->_total > 3) {
        $conditionNumber = (($curr_page + 3 <= $this->_total) ? $curr_page + 3 : $this->_total + 1);

        for ($i = $curr_page; $i < $conditionNumber; $i++) {
          $this->_HTMLcountent .= "<a href='" . URL . $this->_path . "?page=" . $i . $filter . "' class='page " . (($curr_page == $i) ? 'active' : null) . "'>$i</a>";
        }

        $this->_HTMLcountent .= ($curr_page + 3 <= $this->_total) ? '<a href="javascript:void(0)" class="dot">...</a>' : null;
      }

      $this->_HTMLcountent .= ($curr_page < $this->_total) ? $this->lastPage() : null;
      $this->_HTMLcountent .= $this->footer();

      return $this->_HTMLcountent;
    } else return false;
  }
}