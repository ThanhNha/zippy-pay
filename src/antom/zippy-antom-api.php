<?php

namespace ZIPPY_Pay\Src\Antom;

use Request\pay\AlipayPaymentSessionRequest;
use Client\DefaultAlipayClient;
use Model\Amount;
use Model\Order;
use Model\PaymentMethod;
use Model\ProductCodeType;
use Model\SettlementStrategy;
use WC_Order;

class ZIPPY_Antom_Api
{
  private WC_Order $order;
  private string $errorMessage = 'We cannot process the payment at the moment. Please try again later.';
  private string $gatewayUrl = "https://open-sea-global.alipay.com/";
  private string $clientId = "SANDBOX_5YER1S2ZQWQ806246";
  private string $merchantPrivateKey = "MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQCvdaEOLDBOIvMQD4E+ophSNDGX6k6+9aq3Aahn1XEZpKqNupLUFVwN352ze4HNIA6rPC42bilc5SZsvYsrkO6RwXJ8ZisVc+PbpDNQx9/z5Jnqlki1iLQ4IVnphdNPJqjndaTMXVhxUMcx29PGH6bEBxcXxC7kI1vTFF1HAvDBVnN17/nSjTQGjoLHiE1NivOZlAj9S4ceHbQ6OUNn8jkP9BvnknIKOf0iEBoeByF7SVdFuwzL3qFDt1tiuD4XeblQqTo333/gNAEhm4uGsGCboUIUlH93MfZpwXXjA4SLaXZ8VfqSAeamZnwDJqkfSJMJ/YNqD1OUIZDBs2DhfY/bAgMBAAECggEAWT/ERKlFiPlOGgVJAMtEH8xczaFpnMjppV06hFaVyS1xc9ZLiYWz5XqxhPJ1/BkqXP+nohg3AgGtofJoMCjwsUNtMMnncGoapUbIs3Z3/F9zy1fWdECtK0ALtDVWXxSTJek5gpqYuksUh9AB0O2Yrm3M2VH5aCJ0OtJJAC4GuNCgyjmrh+xDtVBodKyZ6UbVZPBfqloW+svYoUzV0OE3JfXLkYe2OE3yCiUPCvA8ZO84erB3C2KyLYqFOoDTuiYM+DWDxMEA/z08E9w/PT7LbCNnFBlp/thh1WUAEDSbZ8SAeEfPPbxojG2gG7ojDFhLHle/06zweGG+paf/cY8IwQKBgQDf8D4ZZS6J41MWwUR1cXSqmbIRuNayI0+F2LADYRY3QjJe538PZg8G3ePUyIucpDz6j1SJUabtB/H68QYEghF4d2hN6qAwR6BkBOizMlJ1QZMdC5LbVrZBAkK0K4VFfo1CB8Fn50KI5ZAsp3WXhkozceOnNIVUMvrioomMa60jNwKBgQDIlIsRIg1L8g1smYwpxNve30cdNp64b7HVUnFMFeserz89CUQLOBJO+lW6+2yYZeslSIwZOgErG8nm01TbcTVUlSoZn5hY/mNcmMuEpL0zNVN67N2ILAPIOHHf9tu5Btcana2kYQ8PijxB5LxxkiYQiaRJmUXlSke9XLtUmhKSfQKBgQDD6LMLGH+sVdl80LuJWyZLkYY9Bs2crlOFrtndOvRqh1j1ueRonkqLIVeN8Zxh/zTxpq64K1yzhP3nITC4hOmv0BbKA3b8hc0Fc134Vw/YUPPYtyVVCfkLymR8po5DC8GcgJhsw95rURneVuoDE5Kaiwo0Xzg9PxRJbRSUaibf5QKBgQCodnISkrn+Ni7mLlRVZHySRI8SzyQhndYLZ7G2iJYv8Fo5pkWa8p4V7RUB9Vcw7DDB4JuiOGBw12cM0iPsJrn2700rtiobXJURWVddcYtirgAON1CcpPMMP7QMueWzEjapqRMFA2vFzoFrinRnIquLqj7sfaoBuRBlz7Oai8jbdQKBgQC53wz5B7lcZ4Rxgut7PeZEC3JHuXlqIdSj40PSM2fq3YGVP8stOXJymnN0jLAUj97fw/cQyltH7x80+yuwad6gWb8/juq8TMZr7aj54Esn7plZJdeb8Z6BCXXGPcYbj16+o88pScs6UODgrtbI0Gd76k6m61Mzeh5R68iuPtbguA==";
  private string $alipayPublicKey = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAl+93fM4yqSRTSN5MXtNd8P2ugG5hh7wLl6BjZRCmtNKxGNV+qjs7t47NLNmozQ6mez9pQF5kEd6GmptrFFFDRI2YoaEuP4sM40s32TLEzd/DJH8LlXKHUpdCegsCaf2tuDcVrDUb/GUDcg29Yv4GO9NXyHuyW7xItNMip/zbzyAQFxVdZcVOezO3k8LE/kq5BPmPS3v2wktur82X5/Z9gaX/xBeSWQ1HKLu047KyQjewtcUE3QxbuZYvVSq5MoP96B+d2q6udeDcWNlCmtbbMvf+90gIpedQ5iSyMsUuDtOAXC7vFSkbg86PTaBjuwobqNkBY3kGkknky3TD2uwWdwIDAQAB";

  public function __construct(int $order_id)
  {
    $this->order = new WC_Order($order_id);
  }

  public function createPaymentSession()
  {
    $request = $this->initializePaymentRequest();
    $alipayClient = new DefaultAlipayClient($this->gatewayUrl, $this->merchantPrivateKey, $this->alipayPublicKey);
    $response = $alipayClient->execute($request);
    return $response;
  }

  private function initializePaymentRequest(): AlipayPaymentSessionRequest
  {
    $request = new AlipayPaymentSessionRequest();

    $request->setPaymentNotifyUrl("https://www.alipay.com/notify");
    $request->setPaymentRedirectUrl($this->getPaymentRedirectUrl());

    $order = $this->buildOrder();
    $request->setOrder($order);

    $paymentAmount = new Amount();
    $paymentAmount->setCurrency($order->getOrderAmount()->getCurrency());
    $paymentAmount->setValue($order->getOrderAmount()->getValue());

    $request->setPaymentAmount($paymentAmount);
    $request->setPaymentRequestId($this->order->get_order_key());

    $paymentMethod = new PaymentMethod();
    $paymentMethod->setPaymentMethodType('CARD');
    $request->setPaymentMethod($paymentMethod);

    $request->setProductCode(ProductCodeType::CASHIER_PAYMENT);

    $settlementStrategy = new SettlementStrategy();
    $settlementStrategy->setSettlementCurrency($order->getOrderAmount()->getCurrency());
    $request->setSettlementStrategy($settlementStrategy);

    $request->setClientId($this->clientId);

    return $request;
  }

  private function buildOrder(): Order
  {
    $order = new Order();
    $order->setOrderDescription("Test order description");
    $order->setReferenceOrderId($this->order->get_id());

    $orderAmount = new Amount();
    $orderAmount->setCurrency($this->order->get_currency());
    $orderAmount->setValue($this->order->get_total());

    $order->setOrderAmount($orderAmount);
    return $order;
  }

  private function getPaymentRedirectUrl(): string
  {
    return str_replace('https:', 'http:', add_query_arg([
      'wc-api'  => 'WC_Zippy_Antom_Redirect',
      'order_id' => $this->order->get_id()
    ], home_url('/')));
  }
}
