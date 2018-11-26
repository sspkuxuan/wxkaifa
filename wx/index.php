<?php
/**
 * wechat php test
 */
 
//define your token
define("TOKEN", "liuxuan");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();//接口验证
$wechatObj->responseMsg();//调用回复消息方法
class wechatCallbackapiTest
{
 public function valid()
 {
 $echoStr = $_GET["echostr"];
 
 //valid signature , option
 if($this->checkSignature()){
 echo $echoStr;
 exit;
 }
 }
 
 public function responseMsg()
 {
 //get post data, May be due to the different environments
 $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
 
 //extract post data
 if (!empty($postStr)){
 /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
  the best way is to check the validity of xml by yourself */
 libxml_disable_entity_loader(true);
  $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
 $fromUsername = $postObj->FromUserName;
 $toUsername = $postObj->ToUserName;
 $keyword = trim($postObj->Content);
 $time = time();
 $msgType = $postObj->MsgType;//消息类型
 $event = $postObj->Event;//时间类型，subscribe（订阅）、unsubscribe（取消订阅）
 $textTpl = "<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>0</FuncFlag>
  </xml>"; 
   
 switch($msgType){
  case "event":
  if($event=="subscribe"){
  $contentStr = "Hi,欢迎关注刘玄的公众号!"."\n"."回复'我要微信',获取刘玄的微信号."."\n"."回复数字'天气',获取天气信息哦."."\n"."回复数字'不同城市天气',可以选择不同城市天气信息哦.";
  } 
  break;
  case "text":
  switch($keyword){
  case "我要微信":
  $contentStr = "你猜？"."\n"."就不告诉你，嘿嘿嘿."; 
  break;
  case "天气":
  $contentStr = "蒙城现在天气：
                			温度：3~12°C
                            天气：晴转多云
                            风级：3级
                            风向：东北风
                            PM2.5：15
                            相对湿度：85%
                            明天：晴转多云
                            后天：多云转晴";
  break;
  case "不同城市天气":
  $contentStr = "点击链接选择不同城市天气\nhttp://188.131.156.195/index/weather/index.html"; 
  break;
  default:
  $contentStr = "走开，走开,我在吃火锅，别打扰我";
  }
  break;
 }
 $msgType = "text";
 $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
 echo $resultStr;
 }else {
 echo "";
 exit;
 }
 }
  
 private function checkSignature()
 {
 // you must define TOKEN by yourself
 if (!defined("TOKEN")) {
 throw new Exception('TOKEN is not defined!');
 }
  
 $signature = $_GET["signature"];
 $timestamp = $_GET["timestamp"];
 $nonce = $_GET["nonce"];
  
 $token = TOKEN;
 $tmpArr = array($token, $timestamp, $nonce);
 // use SORT_STRING rule
 sort($tmpArr, SORT_STRING);
 $tmpStr = implode( $tmpArr );
 $tmpStr = sha1( $tmpStr );
  
 if( $tmpStr == $signature ){
 return true;
 }else{
 return false;
 }
 }
}
 
 
?>