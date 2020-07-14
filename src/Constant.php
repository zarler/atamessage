<?php

namespace atasdk;

class Constant
{
   
    const REQUEST_CURL = [
        1000=>'请求成功',
    ];
	
    //错误码
    const ErrorCode = [
    	3001=>'请求方式参数错误',
        3002=>'模块参数错误',
        3501=>'请求接口异常',
        3502=>'数据为空',
        4001=>'接口异常',
        4002=>'获取数据异常',
        4003=>'接口返回数据为空'
    ];
    //请求地址
    const URL_REQUEST = [
        'inbox'=>'/api/inbox'
        ,'config'=>'/api/config'
        ,'template'=>'/api/template'
        ,'batch'=>'/api/batch'
        ,'outbox_send'=>'/api/outbox/send'
        ,'outbox_sendmessage'=>'/api/outbox/sendMessage'
        ,'label'=>'/api/label'
        ,'student'=>'/api/student'
    ];

}