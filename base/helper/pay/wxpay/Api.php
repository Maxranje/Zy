<?php
/**
 * 微信扫码支付
 *
 * 需要支持:
 * 付款
 * 交易查询
 * 退款
 * 退款查询
 * 关闭
 */

require_once SYSPATH.'/helper/pay/wxwpay/lib/WxPay.Api.php';
require_once SYSPATH.'/helper/pay/wxwpay/ext/WxPay.NativePay.php';

class Zy_Helper_Pay_Wxpay_Api{

    public static $instance;

    private function __construct() {
    }

    public static function getInstance () {
        if (self::$instance === NULL) {
            self::$instance = new Zy_Helper_Pay_Wxpay_Api ();
        }
        return self::$instance ;
    }

    /**
     * 获取支付的二维码
     *
     * @param  [string]     $out_trade_no [商户订单号，商户网站订单系统中唯一订单号]
     * @param  [string]     $productid    [商品id, 必填]
     * @param  [string]     $subject      [订单名称, 必填]
     * @param  [string]     $total_amount [付款金额, 必填]
     * @param  [string]     $body         [商品描述, 非必填]
     * @return [type]               [description]
     */
    public function getQrCode ($out_trade_no, $product_id, $total_amount, $body = "") {

        $input = new WxPayUnifiedOrder();
        $input->SetBody($body);
        $input->SetAttach("zdby");
        $input->SetOut_trade_no($out_trade_no);
        $input->SetTotal_fee($total_amount);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("none");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/notify.php");
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($product_id);

        $notify = new NativePay();
        $result = $notify->GetPayUrl($input);
        $url = $result["code_url"];
        return $url;
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
            'trade_no'      => $trade_no,
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
        $request->setBizContent ( $requestBuilder );

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
        $request->setBizContent ( $requestBuilder );

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
            'out_trade_no'  => $out_trade_no,
            'trade_no'      => $trade_no,
        );
        $requestBuilder = json_encode($requestBuilder,JSON_UNESCAPED_UNICODE);

        $request = new AlipayTradeCloseRequest();
        $request->setBizContent ( $requestBuilder );

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