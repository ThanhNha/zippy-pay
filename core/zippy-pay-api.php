<?php

namespace ZIPPY_Pay\Core;

use PDO;
use WC_Admin_Settings;
use ZIPPY_Pay\Core\ZIPPY_Pay_Core;


defined('ABSPATH') || exit;

class ZIPPY_Pay_Api
{

  /*
   * @param $url
   *
   * @return mixed
   */
  public static function getToken($url)
  {
    $path = '/v1/User/eCommerce/GetToken';

    $domain = ZIPPY_Pay_Core::get_domain_name();

    $url_token = $url . $path;

    $timestamp = time();

    $merchant_id =  WC_Admin_Settings::get_option(PREFIX . '_merchant_id');

    $hash_hmac_data = self::build_hash_hmac($path, $merchant_id, $timestamp, '', 'GET');

    $headers = array(
      'Content-Type: application/json',
      'Authorization:' . $merchant_id . ':' . $timestamp . ':' .  $domain . ':' . $hash_hmac_data,
    );

    $curl = curl_init();

    // Set the cURL options
    curl_setopt($curl, CURLOPT_URL, $url_token);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($curl);

    if ($httpcode === 200) {
      return json_decode($response);
    } else {
      return $httpcode;
    }
  }

  public static function get_token_from_zippy($url)
  {

    $token  = self::getToken($url);

    $access_token = $token->Result->Token;

    setcookie("access_token", $access_token); // the first save token

    return $access_token;
  }
  /*
   * @param $url
   *
   * @return mixed
   */
  public static function getConfigs($url = '', $token = '')
  {

    $token = empty($token) ? $_COOKIE['access_token'] : $token;

    $path = '/v1/payment/adyen/ecommerce/config';

    $url_config = $url . $path;

    $timestamp = time();

    $merchant_id =  WC_Admin_Settings::get_option(PREFIX . '_merchant_id');

    $hash_hmac_data = self::build_hash_hmac($path, $merchant_id, $timestamp, '', 'GET');

    $domain = ZIPPY_Pay_Core::get_domain_name();

    $headers = array(
      'Content-Type: application/json',
      'AccessToken:' . $token,
      'Authorization:' . $merchant_id . ':' . $timestamp . ':' .  $domain . ':' . $hash_hmac_data,
    );

    $curl = curl_init();

    // Set the cURL options
    curl_setopt($curl, CURLOPT_URL, $url_config);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($curl);

    if ($httpcode === 401) {
      return $httpcode;
    }
    return json_decode($response);
  }

  /*
   * @param $url
   *
   * @param $params
   *
   * @return mixed
   */

  public static function checkout($url, $params = array(), $token = '')
  {

    $token = empty($token) ? $_COOKIE['access_token'] : $token;

    $data = json_encode($params);

    unset($params['paymentMethod'], $params['browserInfo'], $params['returnUrl']);

    $path = '/v1/payment/adyen/ecommerce/payment';

    $endpoint = $url . $path;

    $timestamp = time();

    $domain = ZIPPY_Pay_Core::get_domain_name();

    $merchant_id =  WC_Admin_Settings::get_option(PREFIX . '_merchant_id');

    $hash_hmac_data = self::build_hash_hmac($path, $merchant_id, $timestamp,  json_encode($params), 'POST');

    $headers = array(
      'Content-Type: application/json',
      'AccessToken:' . $token,
      'Authorization:' . $merchant_id . ':' . $timestamp . ':' .  $domain . ':' . $hash_hmac_data,
    );

    $curl = curl_init();

    // Set the cURL options
    curl_setopt($curl, CURLOPT_URL, $endpoint);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    // curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    // Execute the cURL request
    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($curl);

    if ($httpcode === 401) {
      return $httpcode;
    }
    return json_decode($response);
  }

  /*
   * @param $url
   *
   * @param $params
   *
   * @return mixed
   */

  public static function transactionStatus($url_params, $params, $token = '')
  {
    $token = empty($token) ? $_COOKIE['access_token'] : $token;

    $path_url = '/v1/payment/adyen/ecommerce/transactionStatus';

    $url = $url_params . $path_url;

    $path = sprintf("%s?%s", $path_url, http_build_query($params));

    $endpoint = sprintf("%s?%s", $url, http_build_query($params));

    $timestamp = time();

    $merchant_id =  WC_Admin_Settings::get_option(PREFIX . '_merchant_id');

    $hash_hmac_data = self::build_hash_hmac($path, $merchant_id, $timestamp, '', 'GET');

    $domain = ZIPPY_Pay_Core::get_domain_name();

    $headers = array(
      'Content-Type: application/json',
      'AccessToken:' . $token,
      'Authorization:' . $merchant_id . ':' . $timestamp . ':' .  $domain . ':' . $hash_hmac_data,
    );

    $curl = curl_init();

    // Set the cURL options
    curl_setopt($curl, CURLOPT_URL, $endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $response = curl_exec($curl);

    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($curl);

    if ($httpcode === 401) {
      return $httpcode;
    }
    return json_decode($response);
  }

  private static function build_hash_hmac($path_query, $merchant_id, $timestamp, $payload, $method)
  {
    $secret_key = WC_Admin_Settings::get_option(PREFIX . '_secret_key');

    $domain = ZIPPY_Pay_Core::get_domain_name();

    $raw_signature =  $secret_key . ":" . $merchant_id   . ":" . $domain  . ":" . $timestamp  . ":" . $method  . ":" . $path_query . ":" . $payload;

    return hash_hmac('sha256', str_replace(' ', '', $raw_signature), $secret_key, false);
  }
}
