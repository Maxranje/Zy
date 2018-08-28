<?php
/**
 * 支付宝支付
 * 电脑网站支付, 选择商品点击支付跳转到支付宝页面扫码支付
 *
 * 需要支持:
 * 付款
 * 交易查询
 * 退款
 * 退款查询
 * 关闭
 */
require_once dirname( __FILE__ ).'/AopSdk.php';

class Alipay_Api{

    // 支付时间限制
    public $timeout_express ;

    //支付宝网关地址
    protected $gateway_url = "https://openapi.alipay.com/gateway.do";

    //支付宝公钥
    protected $alipay_public_key = '';

    //商户私钥
    protected $private_key     = '';

    //应用id
    protected $appid           = '';

    //编码格式
    protected $charset         = "UTF-8";

    protected $token           = NULL;

    //返回数据格式
    protected $format          = "json";

    //签名方式
    protected $signtype        = "RSA2";

    // 同步回调接口
    protected $return_url      = '';

    // 异步通知接口
    protected $notify_url      = '';

    private static $instance = NULL;

    // check alipay config
    private function __construct() {
        if(empty($this->appid)||trim($this->appid)==""){
            throw new Exception("[Error] alipay error [Detail] appid should not be NULL!");
        }
        if(empty($this->private_key)||trim($this->private_key)==""){
            throw new Exception("[Error] alipay error [Detail] private_key should not be NULL!");
        }
        if(empty($this->alipay_public_key)||trim($this->alipay_public_key)==""){
            throw new Exception("[Error] alipay error [Detail] alipay_public_key should not be NULL!");
        }
        if(empty($this->charset)||trim($this->charset)==""){
            throw new Exception("[Error] alipay error [Detail] charset should not be NULL!");
        }
        if(empty($this->gateway_url)||trim($this->gateway_url)==""){
            throw new Exception("[Error] alipay error [Detail] gateway_url should not be NULL!");
        }
        if (empty($this->return_url)||trim($this->return_url) === "") {
            $this->return_url = '';
        }
        if (empty($this->notify_url)||trim($this->notify_url) === "") {
            $this->notify_url = '';
        }
        $this->timeout_express = '90m';
    }

    public static function getInstance () {
        if (self::$instance === NULL) {
            self::$instance = new Alipay_Api ();
        }
        return self::$instance ;
    }

    /**
     * 付款接口
     *
     * @param  [string]     $out_trade_no [商户订单号，商户网站订单系统中唯一订单号]
     * @param  [string]     $subject      [订单名称, 必填]
     * @param  [string]     $total_amount [付款金额, 必填]
     * @param  [string]     $body         [商品描述, 非必填]
     * @return [type]               [description]
     */
    public function pay ($out_trade_no, $subject, $total_amount, $body = "") {
        $payRequestBuilder = array(
            'out_trade_no'    => $out_trade_no,
            'product_code'    => 'FAST_INSTANT_TRADE_PAY',
            'body'            => $body,
            'subject'         => $subject,
            'total_amount'    => $total_amount,
            'timeout_express' => $this->timeout_express,
            'passback_params' => Zy_Token::getFixedToken(),
            'qr_pay_mode'     => 2,
        );
        $payRequestBuilder = json_encode($payRequestBuilder,JSON_UNESCAPED_UNICODE);

        $request = new AlipayTradePagePayRequest();
        $request->setNotifyUrl($this->notify_url);
        $request->setReturnUrl($this->return_url);
        $request->setBizContent ( $payRequestBuilder );

        // 首先调用支付api
        $response = $this->aopclientRequestExecute ($request,true);
        return $response;
    }


    /**
     * 查询订单接口, 请求参数二选一即可
     *
     * @param  [string]     $out_trade_no [商户订单号，商户网站订单系统中唯一订单号]
     * @param  [string]     $trade_no      [支付宝交易号]
     * @return [type]               [description]
     */
    public function query ($out_trade_no, $trade_no) {
        $requestBuilder = array(
            'trade_no'      => $tradeNo,
            'out_trade_no'  => $out_trade_no,
        );
        $requestBuilder = json_encode($this->requestBuilder,JSON_UNESCAPED_UNICODE);

        $request = new AlipayTradeQueryRequest();
        $request->setBizContent ( $requestBuilder );

        $response = $this->aopclientRequestExecute ($request);
        $response = $response->alipay_trade_query_response;
        return $response;
    }

    /**
     * 退款接口, 商户订单号和支付宝交易号二选一既可以
     *
     * @param  [string] $out_trade_no   [商户订单号，商户网站订单系统中唯一订单号]
     * @param  [string] $trade_no       [支付宝交易号]
     * @param  [string] $refund_amount  [需要退款的金额，该金额不能大于订单金额，必填]
     * @param  [string] $refund_reason  [退款的原因说明]
     * @param  [string] $out_request_no [标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传]
     * @return [type]                 [description]
     */
    public function refund ($out_trade_no, $trade_no, $refund_amount, $refund_reason, $out_request_no) {
        $requestBuilder = array(
            'out_trade_no'  => $out_trade_no,
            'trade_no'      => $trade_no,
            'refund_amount' => $refund_amount,
            'refund_reason' => $refund_reason,
            'out_request_no'=> $out_request_no,
        );
        $requestBuilder = json_encode($this->requestBuilder,JSON_UNESCAPED_UNICODE);

        $request = new AlipayTradeRefundRequest();
        $request->setBizContent ( $biz_content );

        $response = $this->aopclientRequestExecute ($request);
        $response = $response->alipay_trade_refund_response;
        return $response;
    }



    /**
     * 退款查询, 其中商户订单号和支付宝交易号二选一
     *
     * @param  [string] $out_trade_no   [商户订单号，商户网站订单系统中唯一订单号]
     * @param  [string] $trade_no       [支付宝交易号]
     * @param  [string] $out_request_no [请求退款接口时，传入的退款请求号，如果在退款请求时未传入，则该值为创建交易时的外部交易号，必填]
     * @return [type]                 [description]
     */
    public function refundQuery ($out_trade_no, $trade_no, $out_request_no) {
        $requestBuilder = array(
            'out_trade_no'  => $out_trade_no,
            'trade_no'      => $trade_no,
            'out_request_no'=> $out_request_no,
        );
        $requestBuilder = json_encode($this->requestBuilder,JSON_UNESCAPED_UNICODE);

        $request = new AlipayTradeFastpayRefundQueryRequest();
        $request->setBizContent ( $biz_content );

        $response = $this->aopclientRequestExecute ($request);
        return $response;
    }

    /**
     * 关闭交易, 其中商户订单号和支付宝交易号二选一
     *
     * @param  [string] $out_trade_no [商户订单号，商户网站订单系统中唯一订单号]
     * @param  [string] $trade_no     [支付宝交易号]
     * @return [type]               [description]
     */
    public function close ($out_trade_no, $trade_no) {
        require_once dirname(__FILE__).'/build/AlipayTradeCloseContentBuilder.php';
        $requestBuilder = array(
            'out_trade_no'  => $out_request_no,
            'trade_no'      => $trade_no,
        );
        $requestBuilder = json_encode($this->requestBuilder,JSON_UNESCAPED_UNICODE);

        $request = new AlipayTradeCloseRequest();
        $request->setBizContent ( $biz_content );

        $response = $this->aopclientRequestExecute ($request);
        $response = $response->alipay_trade_close_response;
        return $response;
    }


    /**
     * alipay.data.dataservice.bill.downloadurl.query (查询对账单下载地址)
     * @param $builder 业务参数，使用buildmodel中的对象生成。
     * @return $response 支付宝返回的信息
     */
    public function downloadurlQuery($builder){
        $biz_content=$builder->getBizContent();
        //打印业务参数
        $this->writeLog($biz_content);
        $request = new alipaydatadataservicebilldownloadurlqueryRequest();
        $request->setBizContent ( $biz_content );

        $response = $this->aopclientRequestExecute ($request);
        $response = $response->alipay_data_dataservice_bill_downloadurl_query_response;
        return $response;
    }

    /**
     * 验签方法
     * @param $arr 验签支付宝返回的信息，使用支付宝公钥。
     * @return boolean
     */
    public function check($arr){
        $aop = new AopClient();
        $aop->alipayrsaPublicKey = $this->alipay_public_key;
        $result = $aop->rsaCheckV1($arr, $this->alipay_public_key, $this->signtype);

        return $result;
    }


    /**
     * sdkClient
     * @param $request 接口请求参数对象。
     * @param $ispage  是否是页面接口，电脑网站支付是页面表单接口。
     * @return $response 支付宝返回的信息
    */
    public function aopclientRequestExecute($request,$ispage=false) {

        $aop = new AopClient ();
        $aop->gatewayUrl = $this->gateway_url;
        $aop->appId = $this->appid;
        $aop->rsaPrivateKey =  $this->private_key;
        $aop->alipayrsaPublicKey = $this->alipay_public_key;
        $aop->apiVersion ="1.0";
        $aop->postCharset = $this->charset;
        $aop->format= $this->format;
        $aop->signType=$this->signtype;
        // 开启页面信息输出
        $aop->debugInfo=true;
        if($ispage)
        {
            $result = $aop->pageExecute($request,"post");
            echo $result;
        }
        else
        {
            $result = $aop->Execute($request);
        }
        return $result;
    }
}