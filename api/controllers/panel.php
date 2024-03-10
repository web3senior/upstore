<?php

class Panel extends Controller
{
  private array $_error = [];
  private $_table = [];
  private $_admin_id = null;

  function __construct()
  {
    parent::__construct();
    $token = (new Cookie)->get('token');
    $decoded = (new JWTAuth)->decode($token);
    $this->_admin_id = $decoded['response']->data->admin;
    if (!$decoded["result"]) {
      Header("Location: " . URL . 'admin');
    }
  }

  private function baseLoad()
  {
  }

  private function viewRender($arg = false)
  {
    $this->view->render('panel/inc/header');
    if ($arg == true) $this->view->render('panel/' . $arg);
    $this->view->render('panel/inc/footer');
  }

  private function header($where)
  {
    // echo  URL . strtolower(__CLASS__) . '/' . $where;die;
    if (isset($_GET['page']))
      header('location: ' . URL . strtolower(__CLASS__) . '/' . $where . '?page=' . $_GET['page']);
    else
      header('location: ' . URL . strtolower(__CLASS__) . '/' . $where);
  }

  private function showError($arg = false)
  {
    if ($arg == true) {
      if (!empty($arg)) {
        //$this->view->render('panel/header');
        echo '<h1 class="text-danger text-center">' . $this->_error . '</h1>';
        //$this->view->render('panel/footer');
      }
    }
  }

  private function printr($array)
  {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    die;
  }

  function index()
  {
    header('Location: ' . URL . 'panel/dashboard');
  }

  function dashboard()
  {
    $this->view->title = 'پیشخوان';
    $this->baseLoad();
    $this->view->data = $this->model->dashboard();
    $this->viewRender('dashboard');
  }

  function service($o = false, $id = false, $val = false)
  {
    $tbl = ['service', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $_POST['dt'] = jdate('Y-m-d H:i:s', time(), null, null, 'en');
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->service($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->service($tbl, 0);
      }

      $this->view->title = 'خدمات';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function legal($o = false, $id = false, $val = false)
  {
    $tbl = ['legal', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->legal($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->legal($tbl, 0);
      }

      $this->view->title = 'حقوقی';
      $this->view->data = [
        "data" => $data,
        "company" => $this->model->command("fetch", ["company", "id"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }


  function accounting($o = false, $id = false, $val = false)
  {
    $tbl = ['accounting', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->accounting($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->accounting($tbl, 0);
      }

      $this->view->title = 'حسابداری';
      $this->view->data = [
        "data" => $data,
        "company" => $this->model->command("fetch", ["company", "id"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }


  function setting($o = false, $id = false, $val = false)
  {
    $tbl = ['setting', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->setting($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->setting($tbl, 0);
      }

      $this->view->title = 'تنظیمات';
      $this->view->data = [
        "data" => $data,
        "company" => $this->model->command("fetch", ["company", "id"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }
  function legal2($o = false, $id = false, $val = false)
  {
    $tbl = ['legal2', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->legal2($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->legal2($tbl, 0);
      }

      $this->view->title = 'حقوقی';
      $this->view->data = [
        "data" => $data,
        "company" => $this->model->command("fetch", ["company", "id"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function access($o = false, $id = false)
  {
    $tbl = ['access', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->access($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->access($tbl, 0);
      }

      $this->view->title = 'دسترسی';
      $this->view->data = [
        "data" => $data,
        "employee" => $this->model->command("fetch", ['employee', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function department($o = false, $id = false)
  {
    $tbl = ['department', 'id'];
    $this->view->endpoint = __FUNCTION__;
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->department($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->department($tbl, 0);
      }

      $this->view->title = 'دپارتمان';
      $this->view->data = [
        "data" => $data,
        "employee" => $this->model->command("fetch", ['employee', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }
  function deal($o = false, $id = false, $val = false)
  {
    $tbl = ['deal', 'id'];
    $this->view->endpoint = __FUNCTION__;
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "view":
          $this->view->title = 'مشاهده اطلاعات';
          $this->view->data = [
            "deal" => $this->model->deal_view($_GET['id'])
          ];
          $this->viewRender('deal_view');
          break;
        case "update":
          if (!empty($_FILES['file']['name'])) {
            $_POST['file'] = (new Upload)->documentUpload('file', 0, time());
            if (!empty($_POST['file_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['file_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['file'] = $_POST['file_hidden'];

          unset($_POST["file_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          if (!empty($_FILES['file']['name'])) {
            $_POST['file'] = (new Upload)->documentUpload('file', 0, time());
            if (!empty($_POST['file_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['file_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['file'] = $_POST['file_hidden'];

          unset($_POST["file_hidden"]);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->deal($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->deal($tbl, 0);
      }

      $this->view->title = 'قرارداد مهندسین';
      $this->view->data = [
        "data" => $data,
        "expert" => $this->model->command("fetch", ["expert"]),
        "department" => $this->model->command("fetch", ["department"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function deal2($o = false, $id = false, $val = false)
  {
    $tbl = ['deal2', 'id'];
    $this->view->endpoint = __FUNCTION__;
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "view":
          $this->view->title = 'مشاهده اطلاعات';
          $this->view->data = [
            "deal" => $this->model->deal2_view($_GET['id'])
          ];
          $this->viewRender('deal2_view');
          break;
        case "update":
          if (!empty($_FILES['file']['name'])) {
            $_POST['file'] = (new Upload)->documentUpload('file', 0, time());
            if (!empty($_POST['file_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['file_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['file'] = $_POST['file_hidden'];

          unset($_POST["file_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          if (!empty($_FILES['file']['name'])) {
            $_POST['file'] = (new Upload)->documentUpload('file', 0, time());
            if (!empty($_POST['file_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['file_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['file'] = $_POST['file_hidden'];

          unset($_POST["file_hidden"]);
          $id = $this->model->command("insert", $tbl, $_POST);




          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->deal2($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->deal2($tbl, 0);
      }

      $this->view->title = 'قرارداد مشتریان';
      $this->view->data = [
        "data" => $data,
        "client" => $this->model->command("fetch", ["client"]),
        "department" => $this->model->command("fetch", ["department"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function password($o = false, $id = false, $val = false)
  {
    $tbl = ['password', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->password($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->password($tbl, 0);
      }

      $this->view->title = 'گذرواژه ها';
      $this->view->data = [
        "data" => $data,
        "company" => $this->model->command("fetch", ['company', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function education($o = false, $id = false, $val = false)
  {
    $tbl = ['education', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":

          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->education($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->education($tbl, 0);
      }

      $this->view->title = 'تحصیلات';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function field_study($o = false, $id = false, $val = false)
  {
    $tbl = ['field_study', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->field_study($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->field_study($tbl, 0);
      }

      $this->view->title = 'تحصیلات';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }
  /**
   * Employee
   */
  function employee($o = false, $id = false, $val = false)
  {
    $tbl = ['employee', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['picture']['name'])) {
            $_POST['picture'] = (new Upload)->storeImageFile('picture', 0);
            if (!empty($_POST['picture_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['picture_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['picture'] = $_POST['picture_hidden'];

          unset($_POST["picture_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          if (!empty($_FILES['picture']['name'])) {
            $_POST['picture'] = (new Upload)->storeImageFile('picture', 0);
            if (!empty($_POST['picture_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['picture_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['picture'] = $_POST['picture_hidden'];

          unset($_POST["picture_hidden"]);

          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->employee($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->employee($tbl, 0);
      }

      $this->view->title = 'کارمندان';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function expert($o = false, $id = false, $val = false)
  {
    $tbl = ['expert', 'id'];
    $tbl_upload = ["expert_upload", 'id'];
    $this->view->endpoint = __FUNCTION__;
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "export":
          echo $this->model->expert_export($tbl)[0]["mobiles"];
          break;
        case "view":
          $this->view->title = 'مشاهده اطلاعات';
          $this->view->data = [
            "expert" => $this->model->expert_view($_GET['id']),
            "expertInCompany" => $this->model->expert_in_company($_GET['id'])
          ];
          $this->viewRender('expert_view');
          break;
        case "upload_delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl_upload, false, $_GET['id']);
          $this->header(__FUNCTION__ . '/upload?expert_id=' . $_GET['expert_id']);
          break;
        case "upload":
          $this->view->title = 'آپلود مدارک';
          $this->view->data = [
            "expert" => $this->model->command("info", ['expert', 'id'], false, $_GET['expert_id']),
            "upload" => $this->model->expert_upload($tbl_upload, $_GET['expert_id'])
          ];
          $this->viewRender('expert_upload');
          break;
        case "upload_info":
          echo json_encode($this->model->command("info", $tbl_upload, false, $id)[0]);
          break;
        case "upload_update":
          if (!empty($_FILES['url']['name'])) {
            $_POST['url'] = (new Upload)->documentUpload('url', 0, time() . '-');
            if (!empty($_POST['url_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['url_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['url'] = $_POST['url_hidden'];
          $_POST['dt'] = jdate('Y-m-d H:i:s', time(), '', '', 'en');
          unset($_POST["url_hidden"]);

          $this->model->command("update", $tbl_upload, $_POST, $id);
          $this->header(__FUNCTION__ . '/upload?expert_id=' . $_GET['expert_id']);
          break;
        case "upload_insert":
          if (!empty($_FILES['url']['name'])) {
            $_POST['url'] = (new Upload)->documentUpload('url', 0, time());
            if (!empty($_POST['url_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['url_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['url'] = $_POST['url_hidden'];
          $_POST['dt'] = jdate('Y-m-d H:i:s', time(), '', '', 'en');
          unset($_POST["url_hidden"]);

          $id = $this->model->command("insert", $tbl_upload, $_POST);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__  . "/upload?expert_id=" . $_GET['expert_id'] . "&insert=1");
          else
            $this->header(__FUNCTION__  . "/upload?expert_id=" . $_GET['expert_id'] . "&insert=0&msg=try again");
          break;
        case "update":
          if (!empty($_FILES['picture']['name'])) {
            $_POST['picture'] = (new Upload)->storeImageFile('picture', 0);
            if (!empty($_POST['picture_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['picture_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['picture'] = $_POST['picture_hidden'];

          unset($_POST["picture_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["picture_hidden"]);
          $_POST['admin_id'] = $this->_admin_id;
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->expert($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->expert($tbl, 0);
      }

      $this->view->title = 'مهندسین';
      $this->view->data = [
        "data" => $data,
        "education" => $this->model->command("fetch", ["education"]),
        "field_study" => $this->model->command("fetch", ["field_study"]),
        "province" => $this->model->command("fetch", ["province"]),
        "city" => $this->model->command("fetch", ["city"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function report()
  {
    $this->view->endpoint = __FUNCTION__;
    $this->view->title = 'گزارش گیری';
    $this->view->data = [
      "data" => $this->model->report(),
      "education" => $this->model->command("fetch", ["education"]),
      "province" => $this->model->command("fetch", ["province"]),
      "city" => $this->model->command("fetch", ["city"])
    ];
    $this->viewRender(__FUNCTION__);
  }

  function timeline()
  {
    $this->view->endpoint = __FUNCTION__;
    $this->view->title = 'زمانبندی';
    $this->viewRender(__FUNCTION__);
  }

  function legaltype()
  {
    $this->view->title = 'انتخاب بخش حقوقی';
    $this->viewRender(__FUNCTION__);
  }

  function client($o = false, $id = false, $val = false)
  {
    $tbl = ['client', 'id'];
    $tbl_upload = ["client_upload", 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
          case "export":
            echo $this->model->client_export($tbl)[0]["mobiles"];
            break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "view":
          $this->view->title = 'مشاهده اطلاعات';
          $this->view->data = [
            "client" => $this->model->command("info", ['client', 'id'], false, $_GET['id'])
          ];
          $this->viewRender('client_view');
          break;
        case "upload_delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl_upload, false, $_GET['id']);
          $this->header(__FUNCTION__ . '/upload?client_id=' . $_GET['client_id']);
          break;
        case "upload":
          $this->view->title = 'آپلود مدارک';
          $this->view->data = [
            "client" => $this->model->command("info", ['client', 'id'], false, $_GET['client_id']),
            "upload" => $this->model->client_upload($tbl_upload, $_GET['client_id'])
          ];
          $this->viewRender('client_upload');
          break;
        case "upload_info":
          echo json_encode($this->model->command("info", $tbl_upload, false, $id)[0]);
          break;
        case "upload_update":
          if (!empty($_FILES['url']['name'])) {
            $_POST['url'] = (new Upload)->documentUpload('url', 0, time() . '-');
            if (!empty($_POST['url_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['url_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['url'] = $_POST['url_hidden'];
          $_POST['dt'] = jdate('Y-m-d H:i:s', time(), '', '', 'en');
          unset($_POST["url_hidden"]);

          $this->model->command("update", $tbl_upload, $_POST, $id);
          $this->header(__FUNCTION__ . '/upload?client_id=' . $_GET['client_id']);
          break;
        case "upload_insert":
          if (!empty($_FILES['url']['name'])) {
            $_POST['url'] = (new Upload)->documentUpload('url', 0, time() . '-');
            if (!empty($_POST['url_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['url_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['url'] = $_POST['url_hidden'];
          $_POST['dt'] = jdate('Y-m-d H:i:s', time(), '', '', 'en');
          unset($_POST["url_hidden"]);

          $id = $this->model->command("insert", $tbl_upload, $_POST);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__  . "/upload?client_id=" . $_GET['client_id'] . "&insert=1");
          else
            $this->header(__FUNCTION__  . "/upload?client_id=" . $_GET['client_id'] . "&insert=0&msg=try again");
          break;
        case "update":
          if (!empty($_FILES['picture']['name'])) {
            $_POST['picture'] = (new Upload)->storeImageFile('picture', 0);
            if (!empty($_POST['picture_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['picture_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['picture'] = $_POST['picture_hidden'];

          unset($_POST["picture_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["picture_hidden"]);
          $_POST['admin_id'] = $this->_admin_id;
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->client($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->client($tbl, 0);
      }

      $this->view->title = 'مشتریان';
      $this->view->data = [
        "data" => $data,
        "education" => $this->model->command("fetch", ["education"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function company($o = false, $id = false, $val = false)
  {
    $tbl = ['company', 'id'];
    $tbl_upload = ["company_upload", 'id'];
    $tbl_member = ["company_member", 'id'];
    $tbl_rate = ["company_rate", 'id'];
    $tbl_expert = ["company_expert", 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "info":
          echo json_encode($this->model->command("info", $tbl, false, $id)[0]);
          break;
        case "view":
          $this->view->title = 'مشاهده اطلاعات';
          $this->view->data = [
            "data" => $this->model->company_view($tbl, $_GET['id'])
          ];
          $this->viewRender('company_view');
          break;



        case "rate_delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl_rate, false, $_GET['id']);
          $this->header(__FUNCTION__ . '/rate?company_id=' . $_GET['company_id']);
          break;
        case "rate":
          $this->view->title = 'تعریف رتبه';
          $this->view->data = [
            "data" => $this->model->command("info", $tbl, false, $_GET['company_id']),
            "rate" => $this->model->company_rate($tbl_rate, $_GET['company_id'])
          ];
          $this->viewRender('company_rate');
          break;
        case "rate_info":
          echo json_encode($this->model->command("info", $tbl_rate, false, $id)[0]);
          break;
        case "rate_update":
          $this->model->command("update", $tbl_rate, $_POST, $id);
          $this->header(__FUNCTION__ . '/rate?company_id=' . $_GET['company_id']);
          break;
        case "rate_insert":
          $id = $this->model->command("insert", $tbl_rate, $_POST);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__  . "/rate?company_id=" . $_GET['company_id'] . "&insert=1");
          else
            $this->header(__FUNCTION__  . "/rate?company_id=" . $_GET['company_id'] . "&insert=0&msg=try again");
          break;



        case "expert_delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl_expert, false, $_GET['id']);
          $this->header(__FUNCTION__ . '/expert?company_id=' . $_GET['company_id']);
          break;
        case "expert":
          $this->view->title = 'تعریف مهندس';
          $this->view->data = [
            "data" => $this->model->command("info", $tbl, false, $_GET['company_id']),
            "expert" => $this->model->company_expert($tbl_expert, $_GET['company_id']),
            "all_expert" =>$this->model->command("fetch", ["expert","id"], false, null)
          ];
          $this->viewRender('company_expert');
          break;
        case "expert_info":
          echo json_encode($this->model->command("info", $tbl_expert, false, $id)[0]);
          break;
        case "expert_update":
          $this->model->command("update", $tbl_expert, $_POST, $id);
          $this->header(__FUNCTION__ . '/expert?company_id=' . $_GET['company_id']);
          break;
        case "expert_insert":
          $id = $this->model->command("insert", $tbl_expert, $_POST);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__  . "/expert?company_id=" . $_GET['company_id'] . "&insert=1");
          else
            $this->header(__FUNCTION__  . "/expert?company_id=" . $_GET['company_id'] . "&insert=0&msg=try again");
          break;








        case "member_delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl_member, false, $_GET['id']);
          $this->header(__FUNCTION__ . '/member?company_id=' . $_GET['company_id']);
          break;
        case "member":
          $this->view->title = 'تعریف اعضا';
          $this->view->data = [
            "data" => $this->model->command("info", $tbl, false, $_GET['company_id']),
            "member" => $this->model->company_member($tbl_member, $_GET['company_id']),
            "expert" => $this->model->command("fetch", ["expert"])
          ];
          $this->viewRender('company_member');
          break;
        case "member_info":
          echo json_encode($this->model->command("info", $tbl_member, false, $id)[0]);
          break;
        case "member_update":
          $this->model->command("update", $tbl_member, $_POST, $id);
          $this->header(__FUNCTION__ . '/member?company_id=' . $_GET['company_id']);
          break;
        case "member_insert":
          $id = $this->model->command("insert", $tbl_member, $_POST);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__  . "/member?company_id=" . $_GET['company_id'] . "&insert=1");
          else
            $this->header(__FUNCTION__  . "/member?company_id=" . $_GET['company_id'] . "&insert=0&msg=try again");
          break;

        case "upload_delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl_upload, false, $_GET['id']);
          $this->header(__FUNCTION__ . '/upload?company_id=' . $_GET['company_id']);
          break;
        case "upload":
          $this->view->title = 'آپلود فایل';
          $this->view->data = [
            "data" => $this->model->command("info", $tbl, false, $_GET['company_id']),
            "upload" => $this->model->company_upload($tbl_upload, $_GET['company_id'])
          ];
          $this->viewRender('company_upload');
          break;
        case "upload_info":
          echo json_encode($this->model->command("info", $tbl_upload, false, $id)[0]);
          break;
        case "upload_update":
          if (!empty($_FILES['url']['name'])) {
            $_POST['url'] = (new Upload)->documentUpload('url', 0, time() . '-');
            if (!empty($_POST['url_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['url_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['url'] = $_POST['url_hidden'];
          $_POST['dt'] = jdate('Y-m-d H:i:s', time(), '', '', 'en');
          unset($_POST["url_hidden"]);

          $this->model->command("update", $tbl_upload, $_POST, $id);
          $this->header(__FUNCTION__ . '/upload?company_id=' . $_GET['company_id']);
          break;
        case "upload_insert":
          if (!empty($_FILES['url']['name'])) {
            $_POST['url'] = (new Upload)->documentUpload('url', 0, time() . '-');
            if (!empty($_POST['url_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['url_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['url'] = $_POST['url_hidden'];
          $_POST['dt'] = jdate('Y-m-d H:i:s', time(), '', '', 'en');
          unset($_POST["url_hidden"]);

          $id = $this->model->command("insert", $tbl_upload, $_POST);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__  . "/upload?company_id=" . $_GET['company_id'] . "&insert=1");
          else
            $this->header(__FUNCTION__  . "/upload?company_id=" . $_GET['company_id'] . "&insert=0&msg=try again");
          break;
        case "update":
          if (!empty($_FILES['stamp']['name'])) {
            $_POST['stamp'] = (new Upload)->storeImageFile('stamp', 0);
            if (!empty($_POST['stamp_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['stamp_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['stamp'] = $_POST['stamp_hidden'];

          if (!empty($_FILES['logo']['name'])) {
            $_POST['logo'] = (new Upload)->storeImageFile('logo', 0);
            if (!empty($_POST['logo_hidden'])) {
              $filename = UPLOAD_IMAGE_PATH . $_POST['logo_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['logo'] = $_POST['logo_hidden'];

          unset($_POST["stamp_hidden"]);
          unset($_POST["logo_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["stamp_hidden"]);
          unset($_POST["logo_hidden"]);
          $_POST['admin_id'] = $this->_admin_id;
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->company($tbl, ($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->company($tbl, 0);
      }

      $this->view->title = 'شرکت ها';
      $this->view->data = [
        "data" => $data,
        "province" => $this->model->command("fetch", ["province"]),
        "city" => $this->model->command("fetch", ["city"])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }







  function p_admin($o = false, $id = false, $val = false)
  {
    $tbl = ['p_admin', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['img']['name'])) {
            $_POST['img'] = (new Upload)->storeImageFile('img', 0);
            if (!empty($_POST['img_hidden'])) {
              $filename = "upload/images/" . $_POST['img_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['img'] = $_POST['img_hidden'];

          unset($_POST["img_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["img_hidden"]);
          if (!empty($_FILES['img']['name'])) $_POST['img'] = (new Upload)->storeImageFile('icon', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->p_admin(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->p_admin(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data,
        "layer" =>  $this->model->command("fetch", ['layer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }
  /**
   * Service
   */
  function link($o = false, $id = false, $val = false)
  {
    $tbl = ['link', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['icon']['name'])) {
            $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
            if (!empty($_POST['icon_hidden'])) {
              $filename = "upload/images/" . $_POST['icon_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['icon'] = $_POST['icon_hidden'];

          unset($_POST["icon_hidden"]);



          $result = $this->model->command('update', $tbl, $_POST, $id);

          $this->header(__FUNCTION__);
          break;
        case "insert":
          $_POST["place_id"] = $this->_place_id;
          unset($_POST["icon_hidden"]);
          $_POST["category_level1_id"] = !empty($_POST["category_level1_id"]) ?  $_POST["category_level1_id"] : NULL;
          $_POST["category_level2_id"] = !empty($_POST["category_level2_id"]) ?  $_POST["category_level2_id"] : NULL;
          $_POST["category_level3_id"] = !empty($_POST["category_level3_id"]) ?  $_POST["category_level3_id"] : NULL;
          // echo "<pre>";
          // print_r($_POST);
          // die;

          if (!empty($_FILES['img']['name'])) $_POST['img'] = (new Upload)->storeImageFile('img', 0);

          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->link(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->link(0);
      }

      $this->view->title = 'سرویس ها';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function billboard($o = false, $id = false, $val = false)
  {
    $tbl = ['billboard', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $_POST["place_id"] = $this->_place_id;
          unset($_POST["icon_hidden"]);
          $_POST["category_level1_id"] = !empty($_POST["category_level1_id"]) ?  $_POST["category_level1_id"] : NULL;
          $_POST["category_level2_id"] = !empty($_POST["category_level2_id"]) ?  $_POST["category_level2_id"] : NULL;
          $_POST["category_level3_id"] = !empty($_POST["category_level3_id"]) ?  $_POST["category_level3_id"] : NULL;
          // echo "<pre>";
          // print_r($_POST);
          // die;

          if (!empty($_FILES['img']['name'])) $_POST['img'] = (new Upload)->storeImageFile('img', 0);

          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->billboard(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->billboard(0);
      }

      $this->view->title = 'سرویس ها';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function gallery($o = false, $id = false, $val = false)
  {
    $tbl = ['gallery', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['url']['name'])) {
            $_POST['url'] = (new Upload)->storeImageFile('url', 0);
            if (!empty($_POST['url_hidden'])) {
              $filename = "upload/images/" . $_POST['url_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['url'] = $_POST['url_hidden'];

          unset($_POST["url_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
          /* Insert */
        case "insert":
          unset($_POST["url_hidden"]);
          if (!empty($_FILES['url']['name'])) $_POST['url'] = (new Upload)->storeImageFile('url', 0);

          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->gallery(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->gallery(0);
      }

      $this->view->title = 'سرویس ها';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function sharelink($o = false, $id = false, $val = false)
  {
    $tbl = ['sharelink', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->sharelink(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->sharelink(0);
      }

      $this->view->title = 'لینک های مفید';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function telephone($o = false, $id = false, $val = false)
  {
    $tbl = ['tel', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":

          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->telephone(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->telephone(0);
      }

      $this->view->title = 'لینک های مفید';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  /**
   * Service
   */
  function layer($o = false, $id = false, $val = false)
  {
    $tbl = ['layer', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['icon']['name'])) {
            $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
            if (!empty($_POST['icon_hidden'])) {
              $filename = "upload/images/" . $_POST['icon_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['icon'] = $_POST['icon_hidden'];

          unset($_POST["icon_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["icon_hidden"]);
          if (!empty($_FILES['icon']['name'])) $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->layer(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->layer(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function gis($o = false, $id = false, $val = false)
  {
    $tbl = ['gis', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->gis(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->gis(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }


  function statistics($o = false, $id = false, $val = false)
  {
    $tbl = ['statistics', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['icon']['name'])) {
            $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
            if (!empty($_POST['icon_hidden'])) {
              $filename = "upload/images/" . $_POST['icon_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['icon'] = $_POST['icon_hidden'];

          unset($_POST["icon_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["icon_hidden"]);
          if (!empty($_FILES['icon']['name'])) $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->statistics(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->statistics(0);
      }

      $this->view->title = 'آمار استان';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function vr($o = false, $id = false, $val = false)
  {
    $tbl = ['vr', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['cover']['name'])) {
            $_POST['cover'] = (new Upload)->storeImageFile('cover', 0);
            if (!empty($_POST['cover_hidden'])) {
              $filename = "upload/images/" . $_POST['cover_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['cover'] = $_POST['cover_hidden'];

          unset($_POST["cover_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["cover_hidden"]);
          if (!empty($_FILES['icon']['name'])) $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->vr(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->vr(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function ideacategory($o = false, $id = false, $val = false)
  {
    $tbl = ['ideacategory', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->ideacategory(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->ideacategory(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }


  function idea($o = false, $id = false, $val = false)
  {
    $tbl = ['idea', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['cover']['name'])) {
            $_POST['cover'] = (new Upload)->storeImageFile('cover', 0);
            if (!empty($_POST['cover_hidden'])) {
              $filename = "upload/images/" . $_POST['cover_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['cover'] = $_POST['cover_hidden'];

          unset($_POST["cover_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["cover_hidden"]);
          if (!empty($_FILES['icon']['name'])) $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->idea(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->idea(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function event($o = false, $id = false, $val = false)
  {
    $tbl = ['event', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['img']['name'])) {
            $_POST['img'] = (new Upload)->storeImageFile('img', 0);
            if (!empty($_POST['img_hidden'])) {
              $filename = "upload/images/" . $_POST['img_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['img'] = $_POST['img_hidden'];

          unset($_POST["img_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["img_hidden"]);
          if (!empty($_FILES['img']['name'])) $_POST['img'] = (new Upload)->storeImageFile('icon', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->event(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->event(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data,
        "layer" =>  $this->model->command("fetch", ['layer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function sublayer($o = false, $id = false, $val = false)
  {
    $tbl = ['sublayer', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['icon']['name'])) {
            $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
            if (!empty($_POST['icon_hidden'])) {
              $filename = "upload/images/" . $_POST['icon_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['icon'] = $_POST['icon_hidden'];

          unset($_POST["icon_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["icon_hidden"]);
          if (!empty($_FILES['icon']['name'])) $_POST['icon'] = (new Upload)->storeImageFile('icon', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->sublayer(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->sublayer(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data,
        "layer" =>  $this->model->command("fetch", ['layer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function broadcast($o = false, $id = false, $val = false)
  {
    $tbl = ['broadcast', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['img']['name'])) {
            $_POST['img'] = (new Upload)->storeImageFile('img', 0);
            if (!empty($_POST['img_hidden'])) {
              $filename = "upload/images/" . $_POST['img_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['img'] = $_POST['img_hidden'];

          unset($_POST["img_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["img_hidden"]);
          if (!empty($_FILES['img']['name'])) $_POST['img'] = (new Upload)->storeImageFile('img', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->broadcast(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->broadcast(0);
      }

      $this->view->title = 'پیام همگانی';
      $this->view->data = [
        "data" => $data,
        "layer" =>  []
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function config($o = false, $id = false, $val = false)
  {
    $tbl = ['config', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['hero']['name'])) {
            $_POST['hero'] = (new Upload)->storeImageFile('hero', 0);
            if (!empty($_POST['hero_hidden'])) {
              $filename = "upload/images/hero.png"; //. $_POST['hero_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['hero'] = $_POST['hero_hidden'];

          unset($_POST["hero_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["hero_hidden"]);
          if (!empty($_FILES['hero']['name'])) $_POST['hero'] = (new Upload)->storeImageFile('hero', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->config(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->config(0);
      }

      $this->view->title = 'پیام همگانی';
      $this->view->data = [
        "data" => $data,
        "layer" =>  $this->model->command("fetch", ['layer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function book($o = false, $id = false, $val = false)
  {
    $tbl = ['book', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['image']['name'])) {
            $_POST['image'] = (new Upload)->storeImageFile('image', 0);
            if (!empty($_POST['image_hidden'])) {
              $filename = "upload/images/" . $_POST['image_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['image'] = $_POST['image_hidden'];

          unset($_POST["image_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["image_hidden"]);
          if (!empty($_FILES['image']['name'])) $_POST['image'] = (new Upload)->storeImageFile('image', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->book(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->book(0);
      }

      $this->view->title = 'کتابخانه مجازی';
      $this->view->data = [
        "data" => $data,
        "layer" =>  $this->model->command("fetch", ['layer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function project($o = false, $id = false, $val = false)
  {
    $tbl = ['project', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['image']['name'])) {
            $_POST['image'] = (new Upload)->storeImageFile('image', 0);
            if (!empty($_POST['image_hidden'])) {
              $filename = "upload/images/" . $_POST['image_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['image'] = $_POST['image_hidden'];

          unset($_POST["image_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["image_hidden"]);
          if (!empty($_FILES['image']['name'])) $_POST['image'] = (new Upload)->storeImageFile('image', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->project(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->project(0);
      }

      $this->view->title = 'کتابخانه مجازی';
      $this->view->data = [
        "data" => $data,
        "layer" =>  $this->model->command("fetch", ['layer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function ads($o = false, $id = false, $val = false)
  {
    $tbl = ['ads', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          if (!empty($_FILES['banner']['name'])) {
            $_POST['banner'] = (new Upload)->storeImageFile('banner', 0);
            if (!empty($_POST['banner_hidden'])) {
              $filename = "upload/images/" . $_POST['banner_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['banner'] = $_POST['banner_hidden'];

          unset($_POST["banner_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["banner_hidden"]);
          if (!empty($_FILES['banner']['name'])) $_POST['banner'] = (new Upload)->storeImageFile('banner', 0);
          //$this->printr($_POST);
          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->ads(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->ads(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data,
        "layer" =>  $this->model->command("fetch", ['layer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }



  function point($o = false, $id = false, $val = false)
  {
    $tbl = ['point', 'id'];
    $this->view->endpoint = __FUNCTION__;
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          if (!empty($_FILES['img']['name'])) {
            $_POST['gallery'] = (new Upload)->storeImageFile('img', 0);
            if (!empty($_POST['img_hidden'])) {
              $filename = "upload/images/" . $_POST['img_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['gallery'] = $_POST['img_hidden'];

          unset($_POST["img_hidden"]);

          $this->model->command("update", $tbl, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["img_hidden"]);
          if (!empty($_FILES['img']['name'])) $_POST['gallery'] = [json_encode((new Upload)->storeImageFile('img', 0))];

          $id = $this->model->command("insert", $tbl, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->point(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->point(0);
      }

      $this->view->title = 'لایه';
      $this->view->data = [
        "data" => $data,
        "sublayer" =>  $this->model->command("fetch", ['sublayer', 'id'])
      ];
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function weather()
  {
    $weather = (new Weather)->weather();
    echo json_encode($weather);
  }

  function admin()
  {
    $this->baseLoad();
    if (isset($_GET["mod"]) && $_GET["mod"] == "update") {
      $data = array();
      $data['fullname'] = $_POST['fullname'];
      $data['purpose'] = $_POST['purpose'];
      if (!empty($_FILES['avatar']['name'])) {
        $data['avatar'] = (new Upload)->storeImageFile('avatar', 0);
        $filename = "build/images/" . $_POST['oldavatar'];
        if (file_exists($filename) && !empty($_POST['oldavatar'])) {
          unlink($filename);
        }
      } else $data['avatar'] = $_POST['oldavatar'];

      $this->model->admin_update($data);
      $this->header(__FUNCTION__);
    } else {
      $this->view->title = 'Admin';
      $this->view->admin = $this->model->command("fetch", [0 => "admin", 1 => "admin_id"]);
      $this->viewRender('admin');
    }
  }


  function configuration()
  {
    $tbl = array(0 => "configuration", 1 => "configuration_id");
    if (isset($_GET["mod"]) && $_GET["mod"] == "update") {
      file_put_contents(JSON . 'admin.json', $_POST['admin']);
      $data = array();
      $data['description'] = $_POST['description'];
      $data['keyword'] = $_POST['keyword'];
      $data['meta'] = $_POST['meta'];
      $data['color'] = json_encode(array($_POST['bgcolor'], $_POST['textcolor']));

      if (!empty($_FILES['logo']['name'])) {
        $data['logo'] = (new Upload)->storeImageFile('logo', 0);
        $filename = "uploads/images/" . $_POST['ex_logo'];
        if (file_exists($filename) && !empty($_POST['ex_logo'])) : unlink($filename);
        endif;
      } else {
        $data['logo'] = $_POST['ex_logo'];
      }

      if (!empty($_FILES['favicon']['name'])) {
        $data['favicon'] = (new Upload)->storeImageFile('favicon', 0);
        $filename = "uploads/images/" . $_POST['ex_favicon'];
        if (file_exists($filename) && !empty($_POST['ex_favicon'])) : unlink($filename);
        endif;
      } else {
        $data['favicon'] = $_POST['ex_favicon'];
      }

      if (!empty($_FILES['limg']['name'])) {
        $data['large_img'] = (new Upload)->storeImageFile('limg', 0);
        $filename = "uploads/images/" . $_POST['ex_limg'];
        if (file_exists($filename) && !empty($_POST['ex_limg'])) : unlink($filename);
        endif;
      } else {
        $data['large_img'] = $_POST['ex_limg'];
      }

      if (!empty($_FILES['simg']['name'])) {
        $data['small_img'] = (new Upload)->storeImageFile('simg', 0);
        $filename = "uploads/images/" . $_POST['ex_simg'];
        if (file_exists($filename) && !empty($_POST['ex_simg'])) : unlink($filename);
        endif;
      } else {
        $data['small_img'] = $_POST['ex_simg'];
      }

      $r = $this->model->command("update", $tbl, $data, $_POST['id']);
      (is_numeric((int)$r) && $r > 0) ? $this->header(__FUNCTION__ . '?update=1') : $this->header(__FUNCTION__ . '?update=0&msg=try again');
    } else {
      $this->view->title = 'Configuration';
      $this->view->admin = file_get_contents(JSON . "admin.json");
      $this->view->data = $this->model->command("fetch", $tbl);
      $this->viewRender('configuration');
    }
  }

  function blog($o = false, $id = false, $val = false)
  {
    $tbl = array(0 => "blog", 1 => "blog_id");
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "status":
          $this->model->command("update", $tbl, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $id = $_GET['id'];
          $r = $this->model->command("info", $tbl, false, $id);
          $this->view->title = 'edit';
          $this->view->category = $this->model->command("fetch", array(0 => "blogcategory", 1 => "category_id"));
          $this->view->data = $r;
          $this->viewRender('blog_edit');
          break;
        case "update":
          $data = array(
            'category_id' => $_POST['category_id'],
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'tag' => $_POST['tag'],
            'date' => $_POST['date']
          );
          if (!empty($_FILES['img']['name'])) {
            $data['img'] = (new Upload)->storeImageFile('img', 0);
            if (!empty($_POST['img_hidden'])) {
              $filename = "uploads/images/" . $_POST['img_hidden'];
              if (file_exists($filename))
                unlink($filename);
            }
          } else
            $data['img'] = $_POST['img_hidden'];
          $this->model->command("update", $tbl, $data, $_POST['id']);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $data = array(
            'category_id' => $_POST['category_id'],
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'tag' => $_POST['tag'],
            'date' => $_POST['date']
          );
          if (!empty($_FILES['img']['name'])) {
            $data['img'] = (new Upload)->storeImageFile('img', 0);
          } else
            $data['img'] = "";

          $id = $this->model->command("insert", $tbl, $data);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->blog(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->blog(0);
      }

      $this->view->title = 'blog';
      $this->view->category = $this->model->command("fetch", array(0 => "blogcategory", 1 => "category_id"));
      $this->view->data = $data;
      $this->viewRender('blog');
    } else {
      $this->showError($this->_error);
    }
  }

  function category($o = false, $id = false, $val = false)
  {
    $tbl = array(0 => "blogcategory", 1 => "category_id");
    $this->baseLoad();
    if ($o == true && empty($this->_error)) {
      switch ($o) {
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $tbl, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $id = $_GET['id'];
          $r = $this->model->command("info", $tbl, false, $id);
          echo json_encode($r);
          break;
        case "update":
          $data = array();
          $data['name'] = $_POST["name"];
          $data['cover'] = $_POST["cover"];
          $this->model->command("update", $tbl, $data, $_POST['id']);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $data = array();
          $data['name'] = $_POST["name"];
          $id = $this->model->command("insert", $tbl, $data);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = 'category';
      $this->view->data = $this->model->command("fetch", $tbl);
      $this->viewRender('category');
    } else {
      $this->showError($this->_error);
    }
  }

  function security()
  {
    if (isset($_GET["mod"]) && $_GET["mod"] == "update") {
      $data = array();
      $email = !empty($_POST['email']) ? $_POST['email'] : '';
      $password = !empty($_POST['password']) ? (new Hash)->create('md5', $_POST['password'], HASH_PASSWORD_KEY) : '';
      $newpassword = (new Hash)->create('md5', $_POST['newpassword'], HASH_PASSWORD_KEY);
      $repassword = (new Hash)->create('md5', $_POST['repassword'], HASH_PASSWORD_KEY);

      if ($newpassword == $repassword) {

        $data = array(
          'email' => $email,
          'password' => $password,
          'newpassword' => $newpassword
        );
        $res = $this->model->security($data);

        if ($res) :
          $this->header(__FUNCTION__ . '?update=1');
        else :
          $this->header(__FUNCTION__ . '?update=0&msg=try again');
        endif;
      } else {
        $this->header(__FUNCTION__ . '?update=0&msg=try again');
      }
    } else {
      $this->view->title = __FUNCTION__;
      $this->viewRender(__FUNCTION__);
    }
  }

  function upload()
  {
    if (isset($_GET["mod"]) && $_GET["mod"] == "up") {
      if (!empty($_FILES['fileup']['name'])) {
        $name = (new Upload)->storeImageFile('fileup', 0);
        (new Session)->init();
        (new Session)->set("upload", $name);
      }
      $this->header(__FUNCTION__);
    } else {
      $this->view->title = 'Upload';
      $this->viewRender('upload');
    }
  }


  function citizenship($action = false, $id = false)
  {
    $table = ["citizenship", "citizenship_id"];
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }


  // function vr($action = false, $id = false)
  // {
  //   $table = ["vr", "id"];
  //   $this->view->endpoint = __FUNCTION__;
  //   if ($action) {
  //     switch ($action) {
  //       case "status":
  //         $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
  //         $this->header(__FUNCTION__);
  //         break;
  //       case "delete":
  //         if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
  //         $this->header(__FUNCTION__);
  //         break;
  //       case "info":
  //         $r = $this->model->command("info", $table, false, $id);
  //         echo json_encode($r[0]);
  //         break;
  //       case "update":
  //         $this->model->command("update", $table, $_POST, $id);
  //         $this->header(__FUNCTION__);
  //         break;
  //       case "insert":
  //         unset($_POST["id"]);
  //         $id = $this->model->command("insert", $table, $_POST);
  //         $this->header(__FUNCTION__);
  //         break;
  //       default:
  //         $this->_error = "This page doesnt exist";
  //         $this->showError($this->_error);
  //         break;
  //     }
  //   } else if (empty($this->_error)) {
  //     $this->view->title = __FUNCTION__;
  //     $this->view->data = $this->model->command("fetch", $table);
  //     $this->viewRender(__FUNCTION__);
  //   } else {
  //     $this->showError($this->_error);
  //   }
  // }



  function occupation($action = false, $id = false)
  {
    $table = ["occupation", "occupation_id"];
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function reason($action = false, $id = false)
  {
    $table = ['reason', 'reason_id'];
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function cashbox($action = false, $id = false)
  {
    $table = ['cashbox', 'cashbox_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "xhrDelete":
          $id = $_POST["id"];
          if (is_numeric($id)) {
            $flag = $this->model->blog_delete($id);
            if ($flag == 1) {
              $response = array(
                'res' => 1,
                'msg' => $id
              );
              echo json_encode($response);
            } else {
              $response = array(
                'res' => 0,
                'msg' => $id
              );
              echo json_encode($response);
            }
          }
          break;
        case "info":
          $result = $this->model->command("info", $table, false, $id);
          echo json_encode($result[0]);
          break;
        case "update":
          if (!empty($_FILES['unit']['name'])) {
            $_POST['unit'] = (new Upload)->storeImageFile('unit', 0);
            if (!empty($_POST['unit_hidden'])) {
              $filename = "upload/images/" . $_POST['unit_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['unit'] = $_POST['unit_hidden'];

          unset($_POST["unit_hidden"]);

          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          if (!empty($_FILES['unit']['name'])) $_POST['unit'] = (new Upload)->storeImageFile('unit', 0);
          unset($_POST['unit_hidden']);
          $id = $this->model->command("insert", $table, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
        default:
          $this->showError($this->_error);
          break;
      }
    } else {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->cashbox(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->cashbox(0);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->viewRender(__FUNCTION__);
    }
  }

  function unit($action = false, $id = false)
  {
    $table = ["unit", "unit_id"];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function paytype($action = false, $id = false)
  {
    $table = ['paytype', 'paytype_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function contact($action = false, $id = false)
  {
    $table = ['contact', 'contact_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function operator($action = false, $id = false)
  {
    $table = ['admin', 'admin_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          // $_POST["password"] = (new Hash)->create('md5', $_POST["password"], HASH_PASSWORD_KEY);
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $_POST['password'] = (new Hash)->create('md5', $_POST['password'], HASH_PASSWORD_KEY);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function country($action = false, $id = false)
  {
    $table = ['countries', 'country_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function state($action = false, $id = false)
  {
    $table = ['states', 'state_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function city($action = false, $id = false)
  {
    $table = ['cities', 'city_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "info":
          $r = $this->model->command("info", $table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error = "This page doesnt exist";
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $this->view->title = __FUNCTION__;
      $this->view->data = $this->model->command("fetch", $table);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function fee($action = false, $id = false)
  {
    $table = ['fee', 'fee_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "xhrDelete":
          $id = $_POST["id"];
          if (is_numeric($id)) {
            $flag = $this->model->blog_delete($id);
            if ($flag == 1) {
              $response = array(
                'res' => 1,
                'msg' => $id
              );
              echo json_encode($response);
            } else {
              $response = array(
                'res' => 0,
                'msg' => $id
              );
              echo json_encode($response);
            }
          }
          break;
        case "fetch":
          $result = $this->model->command("info", $table, false, $id);
          echo json_encode($result[0]);
          break;
        case "search":
          $result = $this->model->feeSearch(json_decode($_GET['data']));
          if (!empty($result))
            echo json_encode(['result' => true, 'data' => $result[0]]);
          else
            echo json_encode(['result' => false, 'message' => 'Not found fee']);
          break;
        case "update":
          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $id = $this->model->command("insert", $table, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
        default:
          $this->showError($this->_error);
          break;
      }
    } else {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->fee(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->fee(0);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->view->unit = $this->model->command("fetch", ['unit', 'unit_id']);
      $this->view->paytype = $this->model->command("fetch", ['paytype', 'paytype_id']);
      $this->viewRender(__FUNCTION__);
    }
  }

  function rate($action = false, $id = false)
  {
    $this->_table = ['rate', 'rate_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $this->_table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $this->_table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "fetch":
          $r = $this->model->command("info", $this->_table, false, $id);
          echo json_encode($r[0]);
          break;
        case "search":
          $result = $this->model->rateSearch(json_decode($_GET['data'])->buy_currency);
          if (!empty($result)) {
            echo json_encode(['result' => true, 'data' => $result[0]]);
          } else
            echo json_encode(['result' => false, 'message' => 'There is no latest rate for given currency']);
          break;
        case "update":
          $this->model->command("update", $this->_table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $id = $this->model->command("insert", $this->_table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error[] = 'This page doesnt exist';
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->rate(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->rate(0);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->view->unit = $this->model->command("fetch", ['unit', 'unit_id']);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function bank($action = false, $id = false)
  {
    $table = ['bank', 'bank_id'];
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "xhrDelete":
          $id = $_POST["id"];
          if (is_numeric($id)) {
            $flag = $this->model->blog_delete($id);
            if ($flag == 1) {
              $response = array(
                'res' => 1,
                'msg' => $id
              );
              echo json_encode($response);
            } else {
              $response = array(
                'res' => 0,
                'msg' => $id
              );
              echo json_encode($response);
            }
          }
          break;
        case "info":
          $result = $this->model->command("info", $table, false, $id);
          echo json_encode($result[0]);
          break;
        case "update":
          if (!empty($_FILES['logo']['name'])) {
            $_POST['logo'] = (new Upload)->storeImageFile('logo', 0);
            if (!empty($_POST['logo_hidden'])) {
              $filename = "upload/images/" . $_POST['logo_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['logo'] = $_POST['logo_hidden'];

          unset($_POST["logo_hidden"]);

          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          if (!empty($_FILES['logo']['name'])) $_POST['logo'] = (new Upload)->storeImageFile('logo', 0);
          $id = $this->model->command("insert", $table, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
        default:
          $this->showError($this->_error);
          break;
      }
    } else {

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->bank(($_GET['page'] - 1) * 10);
      } else {
        $this->view->pg = 1;
        $data = $this->model->bank(0);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->view->country = $this->model->command("fetch", ["countries", "id"]);
      $this->view->state = $this->model->command("fetch", ["states", "id"]);

      $this->viewRender(__FUNCTION__);
    }
  }

  function resource($action = false, $id = false)
  {
    $this->_table = ['resource', 'resource_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $this->_table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $this->_table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "fetch":
          $r = $this->model->command("info", $this->_table, false, $id);
          echo json_encode($r[0]);
          break;
        case "update":
          $_POST['admin_id'] = json_decode($_COOKIE['admin_info'])->admin_id;
          $this->model->command("update", $this->_table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          unset($_POST["id"]);
          $_POST['admin_id'] = json_decode($_COOKIE['admin_info'])->admin_id;
          $id = $this->model->command("insert", $this->_table, $_POST);
          $this->header(__FUNCTION__);
          break;
        default:
          $this->_error[] = 'This page doesnt exist';
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {
      $where = (isset($_GET['id']) && !empty($_GET['id']) ? ('WHERE r.resource_id=' . $_GET['id']) : null);

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->resource(($_GET['page'] - 1) * 10, $where);
      } else {
        $this->view->pg = 1;
        $data = $this->model->resource(0, $where);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->view->unit = $this->model->command("fetch", ['unit', 'unit_id']);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }


  function exchange($action = false, $id = false)
  {
    $this->_table = ['exchange', 'exchange_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $this->_table, array("status" => $_GET["val"]), $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $this->_table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "fetch":
          $result = $this->model->exchangeFetch($id);
          echo json_encode($result[0]);
          break;
        case "fetchUserBank":
          $result = $this->model->fetchUserBank($id);
          echo json_encode($result);
          break;
        case "update":
          $_POST['admin_id'] = json_decode($_COOKIE['admin_info'])->admin_id;
          unset($_POST['buy_currency'], $_POST['pay_currency'], $_POST['customer_pay_type'], $_POST['customer_receive_type']);
          $this->model->command("update", $this->_table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          $_POST['admin_id'] = json_decode($_COOKIE['admin_info'])->admin_id;
          unset($_POST['buy_currency'], $_POST['pay_currency'], $_POST['customer_pay_type'], $_POST['customer_receive_type']);
          $id = $this->model->command("insert", $this->_table, $_POST);
          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1&exchange_id=' . $id);
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
          break;
        default:
          $this->_error[] = 'This page doesnt exist';
          $this->showError($this->_error);
          break;
      }
    } else if (empty($this->_error)) {

      $where = ((isset($_GET['exchange_id']) && !empty($_GET['exchange_id'])) ? ('WHERE e.exchange_id=' . $_GET['exchange_id']) : null);

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->exchange(($_GET['page'] - 1) * 10, $where);
      } else {
        $this->view->pg = 1;
        $data = $this->model->exchange(0, $where);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->view->unit = $this->model->command("fetch", ['unit', 'unit_id']);
      $this->view->client = $this->model->command("fetch", ['client', 'client_id']);
      $this->view->reason = $this->model->command("fetch", ['reason', 'reason_id']);
      $this->view->rate = $this->model->command("fetch", ['rate', 'rate_id']);
      $this->view->fee = $this->model->command("fetch", ['fee', 'fee_id']);
      $this->view->paytype = $this->model->command("fetch", ['paytype', 'paytype_id']);
      $this->viewRender(__FUNCTION__);
    } else {
      $this->showError($this->_error);
    }
  }

  function transaction($action = false, $id = false)
  {
    $table = ['transaction', 'transaction_id'];
    $this->view->endpoint = __FUNCTION__;
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "xhrDelete":
          $id = $_POST["id"];
          if (is_numeric($id)) {
            $flag = $this->model->blog_delete($id);
            if ($flag == 1) {
              $response = array(
                'res' => 1,
                'msg' => $id
              );
              echo json_encode($response);
            } else {
              $response = array(
                'res' => 0,
                'msg' => $id
              );
              echo json_encode($response);
            }
          }
          break;
        case "info":
          $result = $this->model->command("info", $table, false, $id);
          echo json_encode($result[0]);
          break;
        case "update":
          if (isset($_GET['type']) && !empty($_GET['type'])) {
            $_POST['admin_id'] = json_decode($_COOKIE['admin_info'])->admin_id;


            if ($_GET['type']) {
              if (!empty($_FILES['proof']['name'])) $_POST['proof'] = (new Upload)->storeImageFile('proof', 0);
              unset($_POST["proof_hidden"]);
              $_POST['resource_id'] = null;
              $id = $this->model->command("insert", $table, $_POST);
            } else if (!$_GET['type']) {

              if (!empty($_FILES['proof']['name'])) $_POST['proof'] = (new Upload)->storeImageFile('proof', 0);
              $_POST['account_id'] = null;
              unset($_POST["proof_hidden"]);
              $id = $this->model->command("insert", $table, $_POST);
            }

            if (is_numeric($id) && $id > 0)
              $this->header(__FUNCTION__ . '?insert=1');
            else
              $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          }

          unset($_POST["proof_hidden"]);

          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          if (isset($_GET['type'])) {
            $_POST['admin_id'] = json_decode($_COOKIE['admin_info'])->admin_id;




            if ($_GET['type'] == '1') {
              if (!empty($_FILES['proof']['name'])) $_POST['proof'] = (new Upload)->storeImageFile('proof', 0);
              unset($_POST["proof_hidden"]);
              $_POST['account_id'] = null;
              $id = $this->model->command("insert", $table, $_POST);
            } else if ($_GET['type'] == '0') {

              if (!empty($_FILES['proof']['name'])) $_POST['proof'] = (new Upload)->storeImageFile('proof', 0);
              $_POST['resource_id'] = null;
              unset($_POST["proof_hidden"]);
              $id = $this->model->command("insert", $table, $_POST);
            }

            if (is_numeric($id) && $id > 0)
              $this->header(__FUNCTION__ . '?insert=1');
            else
              $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          }
          break;
        default:
          $this->showError($this->_error);
          break;
      }
    } else {
      $where = (isset($_GET['id']) && !empty($_GET['id']) ? ('WHERE t.exchange_id=' . $_GET['id']) : null);

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->transaction(($_GET['page'] - 1) * 10, $where);
      } else {
        $this->view->pg = 1;
        $data = $this->model->transaction(0, $where);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->view->exchange = $this->model->command("fetch", ["exchange", "id"]);
      $this->view->unit = $this->model->command('fetch', ['unit', 'unit_id']);
      $this->view->resource = $this->model->command('fetch', ['resource', 'resource_id']);

      $this->viewRender(__FUNCTION__);
    }
  }

  function account($action = false, $id = false)
  {
    $table = ['account', 'account_id'];
    if ($action) {
      switch ($action) {
        case "status":
          $this->model->command("update", $table, ["status" => $_GET["val"]], $_GET["id"]);
          $this->header(__FUNCTION__);
          break;
        case "delete":
          if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) $this->model->command("delete", $table, false, $_GET['id']);
          $this->header(__FUNCTION__);
          break;
        case "xhrDelete":
          $id = $_POST["id"];
          if (is_numeric($id)) {
            $flag = $this->model->blog_delete($id);
            if ($flag == 1) {
              $response = array(
                'res' => 1,
                'msg' => $id
              );
              echo json_encode($response);
            } else {
              $response = array(
                'res' => 0,
                'msg' => $id
              );
              echo json_encode($response);
            }
          }
          break;
        case "info":
          $result = $this->model->command("info", $table, false, $id);
          echo json_encode($result[0]);
          break;
        case "update":
          if (!empty($_FILES['card_image']['name'])) {
            $_POST['card_image'] = (new Upload)->storeImageFile('card_image', 0);
            if (!empty($_POST['card_image_hidden'])) {
              $filename = "upload/images/" . $_POST['card_image_hidden'];
              if (file_exists($filename)) unlink($filename);
            }
          } else $_POST['card_image'] = $_POST['card_image_hidden'];

          unset($_POST["card_image_hidden"]);

          $this->model->command("update", $table, $_POST, $id);
          $this->header(__FUNCTION__);
          break;
        case "insert":
          if (!empty($_FILES['logo']['name'])) $_POST['logo'] = (new Upload)->storeImageFile('logo', 0);
          $id = $this->model->command("insert", $table, $_POST);

          if (is_numeric($id) && $id > 0)
            $this->header(__FUNCTION__ . '?insert=1');
          else
            $this->header(__FUNCTION__ . '?insert=0&msg=try again');
          break;
        default:
          $this->showError($this->_error);
          break;
      }
    } else {

      $where = ((isset($_GET['id']) && !empty($_GET['id'])) ? ('WHERE account_number=' . $_GET['id']) : null);

      if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $this->view->pg = $_GET['page'];
        $data = $this->model->account(($_GET['page'] - 1) * 10, $where);
      } else {
        $this->view->pg = 1;
        $data = $this->model->account(0, $where);
      }

      $this->view->title = __FUNCTION__;
      $this->view->data = $data;
      $this->view->country = $this->model->command("fetch", ["countries", "country_id"]);
      $this->view->state = $this->model->command("fetch", ["states", "state_id"]);
      $this->view->bank = $this->model->command("fetch", ['bank', 'bank_id']);
      $this->view->unit = $this->model->command("fetch", ['unit', 'unit_id']);
      $this->view->client = $this->model->command("fetch", ['client', 'client_id']);
      $this->viewRender(__FUNCTION__);
    }
  }

  function help()
  {
    $this->view->title = __FUNCTION__;
    $this->viewRender(__FUNCTION__);
  }

  function logout()
  {
    if (isset($_COOKIE['token'])) {
      unset($_COOKIE['token']);
      setcookie('token', '', -1, '/');
      Header('Location: ', URL . 'admin');
    } else {
      return false;
    }
  }
}
