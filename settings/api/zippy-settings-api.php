<?php

namespace ZIPPY_Pay\Settings\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

defined('ABSPATH') || exit;

class ZIPPY_Settings_Api
{
  /**
   * @var Client
   */
  private static $client;

  /**
   * ZIPPY_Settings_Api constructor.
   */

  public function __construct()
  {
    self::$client = new Client([
      'base_uri' => 'http://192.168.1.28:4466/',
      'timeout'  => 5.0,
    ]);
  }
  public static function GetConfigs()
  {

    if (self::$client === null) {
      new self();
    }
    try {


      $configs = self::$client->request("GET", "v1/payment/ecommerce/paymentoptions", ['query' => ['merchantId' => '445adf53-3a26-4296-8723-b6a28299f712']]);

      if ($configs->getStatusCode() == 200) {
        $response = array(
          'status' => true,
          'message' => 'Sync config is successfully',
          'data' => json_decode($configs->getBody())
        );
      }
    } catch (ClientException $e) {
      $response = array(
        'status' => $e->getResponse()->getStatusCode(),
        'message' => 'Store config is failed',
      );
    } catch (ConnectException $e) {
      $response = array(
        'status' => false,
        'message' => 'Store config is failed',
      );
    }

    return $response;
  }
}
