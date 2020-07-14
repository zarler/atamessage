<?php
/**
 * 数字格式化类
 * @author liujinsheng
 */

namespace atasdk;

use atasdk\Constant;
use Curl\Curl;

class Base  
{

    // private $token;
    // private $encodingAesKey;
    // private $encrypt_type;
    // private $appid;
    // private $appsecret;
    // private $access_token;
    // private $jsapi_ticket;
    // private $api_ticket;
    // private $user_token;
    // private $partnerid;
    // private $partnerkey;
    // private $paysignkey;
    // private $postxml;

    protected $_msg = null;
    protected $_code = null;
    protected $_data = '';
    protected $_url = '';

    private $_funcflag = false;
    private $_receive;
    private $_text_filter = true;
    public $debug =  false;
    public $errCode = 40001;
    public $errMsg = "no access";
    public $logcallback;

    public $_type;   //类型

    public $_curl;   //接口请求

    const EVENT_SERVICE_EMAIL = 3;  //邮件
    const EVENT_SERVICE_SMS = 2;  //SMS
    const EVENT_SERVICE_INBOX = 1;  //站内信

    const CONST_SEND_REALTIME_MAX = 20;  //实时最大发送数

    const MODE_SEND_REALTIME = 1;  //实时
    const MODE_SEND_ASYNCHRONOUS = 2;  //异步

    //发送信息
    public $_send_info = [
         "project_id"=> "",
          "title"=> "title",
          "remark"=> "remark",
          "enable"=> 1,
          "schedule"=> 0,
          "scheduled_sent_at"=> "",
          "templates"=> [],
          "from"=> [
            "role"=> '',
            "id"=> '',
            "name"=> ""
          ],
          "to"=> [
            "role"=> 1,
            "list"=> []
          ]
    ];



    public function __construct($options)
    {
        $this->token = isset($options['token'])?$options['token']:'';
        // $this->encodingAesKey = isset($options['encodingaeskey'])?$options['encodingaeskey']:'';
        // $this->appid = isset($options['appid'])?$options['appid']:'';
        // $this->appsecret = isset($options['appsecret'])?$options['appsecret']:'';
        // $this->debug = isset($options['debug'])?$options['debug']:false;
        // $this->logcallback = isset($options['logcallback'])?$options['logcallback']:false;
        // $this->_type = isset($options['type'])?$options['type']:false;
        $this->_url = isset($options['url'])?$options['url']:false;
        $this->_curl = new Curl();
    }




    /**
     * For weixin server validation
     */
    private function checkSignature($str='')
    {
        $signature = isset($_GET["signature"])?$_GET["signature"]:'';
        $signature = isset($_GET["msg_signature"])?$_GET["msg_signature"]:$signature; //如果存在加密验证则用加密验证段
        $timestamp = isset($_GET["timestamp"])?$_GET["timestamp"]:'';
        $nonce = isset($_GET["nonce"])?$_GET["nonce"]:'';

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce,$str);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 日志记录，可被重载。
     * @param mixed $log 输入日志
     * @return mixed
     */
    private function log($log){
        if ($this->debug && function_exists($this->logcallback)) {
            if (is_array($log)) $log = print_r($log,true);
            return call_user_func($this->logcallback,$log);
        }
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     */
    protected  function http_post($url,$param){
        $this->_curl->post($url, $param);
        // D(array($url,$param,$this->_curl->response));
    }

    /**
     * GET 请求(有id为详情，无为列表)
     * @param string $url
     * @param array $param
     */
    protected  function http_get($url,$param){
         //对id进行判断
        if (!isset($param['id'])||empty($param['id'])) {
            $this->_curl->get($url, $param);
        } else {
            $id = $param['id'];
            unset($param['id']);
            $this->_curl->get($url.'/'.$id, $param);
        }
    }

     /**
     * GET 请求
     * @param string $url
     * @param array $param
     */
    protected  function http_put($url,$param){
        //对id进行判断
        $url = $this->http_url($url,$param);
        $this->_curl->put($url, $param);
    }

     /**
     * GET 请求
     * @param string $url
     * @param array $param
     */
    protected  function http_delete($url,$param){
        //对id进行判断
        $url = $this->http_url($url,$param);
        $this->_curl->delete($url,$param);
    }

    /**
     * url组装
     * @param string $url
     * @param array $param
     */
    protected  function http_url($url,&$param){
        //对id进行判断
        if (!isset($param['id'])||empty($param['id'])) {
            $this->_code = 3000;
            $this->_msg = 'id参数错误';
            return $url;
        } else {
            $id = $param['id'];
            unset($param['id']);
            return $url.'/'.$id;
        }
    }

    /**
     * request
     * @param array $array 请求参数
     */

     public function request($array = [], $modular='',$type = ''){
        //初始化
        $this->_code = null;
        if (empty($type)) {
            $this->_code = 3001;
            $this->_msg = atasdk\Constant::ErrorCode[3001];
            $this->_data = '';
            return $this;
        }
        if (empty($modular)) {
            $this->_code = 3002;
            $this->_msg = atasdk\Constant::ErrorCode[3002];
            $this->_data = '';
            return $this;
        }
        switch ($type) {
            case 'GET':
                $this->http_get($this->_url.Constant::URL_REQUEST[$modular],$array);   
                break;
            case 'POST':
                $this->http_post($this->_url.Constant::URL_REQUEST[$modular],$array);   
                break;
            case 'DELETE':
                $this->http_delete($this->_url.Constant::URL_REQUEST[$modular],$array);   
                break;
            case 'PUT':
                $this->http_put($this->_url.Constant::URL_REQUEST[$modular],$array);   
                break;
            default:
                # code...
                break;
        }
        return $this;
    }



     /**
     * 格式化数据
     * @param string $url
     * @param array $param
     * @return string content
     */
    public function get_response(){

        if (!empty($this->_code)) {
            return $this->get_result();
        }
        if ($this->_curl->error_code ||$this->_curl->error_code!=0) {
            $this->_code = $this->_curl->error_code;
            $this->_msg = Constant::ErrorCode[4001];
            $this->_data = '';
        }else {
            if ($this->_curl->response) {
                $json = json_decode($this->_curl->response,true);
                if (!isset($json['code']) || $json['code']!=1) {
                    $this->_code = $json['code'];
                    $this->_msg = $json['message'];
                } else {
                    $this->_code = $json['code']??4003;
                    $this->_msg = $json['message']??Constant::ErrorCode[4003];
                    $this->_data = $json['data']??'';
                }
            }
        }  
        return $this->get_result();
    }

    /**
     * 组装数据
     */
    public function assembleParam($param = null,$server = null)
    {

        if (empty($param) || empty($server)) {
            return false;
        }
        switch ($server) {
            case self::EVENT_SERVICE_EMAIL:
                $templates = ['mode'=>self::EVENT_SERVICE_EMAIL,'template_id'=>$param['template_id'],'data'=>$param['template_data']??''];
                break;
            case self::EVENT_SERVICE_SMS:
                $templates = ['mode'=>self::EVENT_SERVICE_SMS,'template_id'=>$param['template_id'],'data'=>$param['template_data']??''];
                break;
            case self::EVENT_SERVICE_INBOX:
                $templates = ['mode'=>self::EVENT_SERVICE_INBOX,'template_id'=>$param['template_id'],'data'=>$param['template_data']??''];
                break;
            default:
                break;
        }
        if (isset($param['title']) && !empty($param['title'])) {
            $this->_send_info['title'] = $param['title'];
        }

        if (isset($param['remark']) && !empty($param['remark'])) {
            $this->_send_info['remark'] = $param['remark'];
        }

        if ($templates) {
             $this->_send_info['templates'][] = $templates; 
        }

        $this->_send_info['project_id'] = $param['project_id']??''; 
        $this->_send_info['from']['id'] = $param['from_id']??''; 
        $this->_send_info['from']['role'] = $param['from_role']??''; 
        $this->_send_info['from']['name'] = $param['from_name']??''; 
        $this->_send_info['enable'] = $param['enable']??'';
        $this->_send_info['schedule'] = $param['schedule']??''; 
        $this->_send_info['scheduled_sent_at'] = $param['scheduled_sent_at']??''; 
        $this->_send_info['to']['role'] = $server; 
        $this->_send_info['to']['list'] = $param['to_list'];

    }
    
    /**
     * 实时，群发，大于20为异步发送
     */
    public function sendMode($list = null)
    {
      if (count($list) <= self::CONST_SEND_REALTIME_MAX) {
            //实时发送
            $this->request($this->_send_info,'outbox_send',"POST");
      } else {        
            //队列
            $this->request($this->_send_info,'batch',"POST");
      }
    }

     /**
     * 组装数据
     * @return array $array
     */
    protected  function get_result(){
        return array(
            'code'=>$this->_code,
            'message'=>$this->_msg,
            'data'=>$this->_data
        );
    }
}