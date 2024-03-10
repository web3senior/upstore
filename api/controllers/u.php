<?php

class U extends Controller
{

  function __construct()
  {
    parent::__construct();
  }

  function index($username)
  {
    $readyProfileDebug = json_decode('{
      "LSP3Profile": {
        "name": "Undefined",
        "description": "Undefined",
        "links": [],
        "tags": ["Universal Link"],
        "profileImage": [
          {
            "width": 288,
            "height": 320,
            "verification": {
              "method": "keccak256(bytes)",
              "data": "0x61017bc4388775dac674ff82188589934da6cbdc79aacf3dd1d90d2af0597c8e"
            },
            "url": ""
          }
        ],
        "backgroundImage": []
      }
    }
    ');
    
    $this->view->title = $username;
    $config = $this->model->config($username);
    $this->view->data = [
      'config' =>  $config,
      'profile' => $this->lukso($config[0]['wallet_addr'])
    ];
    $this->view->render('u/u');
  }

  function lukso($up_contract_address)
  {
    try {
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://rpc.lukso.gateway.fm',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode(
          [
            "jsonrpc" => "2.0",
            "method" => "eth_call",
            "params" => [
              [
                "to" => $up_contract_address,
                "data" => "0x54f6127f5ef83ad9559033e6e941db7d7c495acdce616347d28e90c7ce47cbfcfcad3bc5"
              ],  "latest"
            ],
          ]
        ),
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);

      $result = json_decode($response)->result;
      $url =  substr($result, 210, 106);
      // echo $url;
      // Are hexadecimal digits?
      if (ctype_xdigit($url)) {
        $cid = hex2bin($url);
        // echo $cid;
        $ipfs = $this->getIPFS('https://api.universalprofile.cloud/ipfs/' . str_replace('ipfs://', '', $cid));
        return json_decode($ipfs);
      }

      return false;
    } catch (Exception $e) {
      echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
  }

  function getIPFS($url)
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HEADER  => false,
    ));

    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpcode === 200) return $response;
    else return '{
      "LSP3Profile": {
        "name": "Undefined",
        "description": "Undefined",
        "links": [],
        "tags": ["Universal Link"],
        "profileImage": [
          {
            "width": 288,
            "height": 320,
            "verification": {
              "method": "keccak256(bytes)",
              "data": "0x61017bc4388775dac674ff82188589934da6cbdc79aacf3dd1d90d2af0597c8e"
            },
            "url": ""
          }
        ],
        "backgroundImage": []
      }
    }
    ';
  }
}
