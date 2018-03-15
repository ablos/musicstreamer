<?php

  // Generates a random string
  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  // Get variables from url
  $pass = $_GET['pass'];
  $url = $_GET['url'];

  if ($pass !== "AblosStream00") {
    die("ERROR:401");
  }

  if (empty($url)) {
    die("ERROR:404");
  }

  $id = generateRandomString(30);

  while (file_exists(__DIR__ . "/streams/" . $id . ".mp3")) {
    $id = generateRandomString(30);
  }

  use Sabre\DAV\Client;
  include("SabreDAV/vendor/autoload.php");

  $settings = array(
    'baseUri' => "https://ablos.stackstorage.com/remote.php/webdav/",
    'userName' => 'ablos',
    'password' => 'AblosStack00'
  );

  $client = new Client($settings);
  $response = $client->request('GET', $url);
  if ($response['statusCode'] === 200) {
    $filepath = __DIR__ . "/streams/" . $id . ".mp3";
    $fh = fopen($filepath, 'w');
    fwrite($fh, $response['body']);
    fclose($fh);

    echo "StreamID:" . $id;
  }else if ($response['statusCode'] === 404) {
    echo "ERROR:404";
  }else {
    echo "ERROR:" . $response['statusCode'];
  }


?>
