<?php
/**
 * 数字格式化类
 * 
 * $result = $this->_weObj->setEmail($param)->get_response();
 * $result = $this->_weObj->request($param,'outbox_send',"POST")->get_response();
 */

namespace atasdk;

use atasdk\Constant;
use Curl\Curl;
use atasdk\Base;

class Atasdk extends Base
{
    /**
     * @api  setEmail 1-2:发送邮件
     * @apiGroup  邮件
     * @apiDescription 发送邮件
     *
     * @apiParam {number} project_id 项目
     * @apiParam {number} template_id 模板
     * @apiParam {number} from_id 发送者id
     * @apiParam {number} from_role 发送者角色
     * @apiParam {number} from_name 发送者名称
     * @apiParam {number} to_role 接收者角色
     * @apiParam {array} to_list[]['email'] 邮箱 
     * @apiParam {number} title 群发标题(非必填)
     * @apiParam {number} remark 群发备注(非必填)
     * @apiParam {number} enable 群发(非必填)
     * @apiParam {number} schedule 群发(非必填)
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function setEmail($param = null){
        if (empty($param)) {
            return false;
        }
        $this->assembleParam($param,Base::EVENT_SERVICE_EMAIL);
        $this->sendMode($param['to_list']);
        return $this;
    }

    /**
     * @api  setEmail 1-4:全站发送站内信
     * @apiGroup  邮件
     * @apiDescription 全站发送站内信
     *
     * @apiParam {number} project_id 项目
     * @apiParam {number} template_id 模板
     * @apiParam {number} from_id 发送者id
     * @apiParam {number} from_role 发送者角色
     * @apiParam {number} from_name 发送者名称
     * @apiParam {number} to_role 接收者角色
     * @apiParam {array} to_list[]['email'] 邮箱 
     * @apiParam {number} title 群发标题(非必填)
     * @apiParam {number} remark 群发备注(非必填)
     * @apiParam {number} enable 群发(非必填)
     * @apiParam {number} schedule 群发(非必填)
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function sendWholeStation($param = null){
        if (empty($param)) {
            return false;
        }
        $this->request($param,'outbox_sendmessage',"POST");
        return $this;
    }

    /**
     * @api  setSMS 1-2:发送短信
     * @apiGroup  短信
     * @apiDescription 发送短信
     *
     * @apiParam {number} project_id 项目
     * @apiParam {number} template_id 模板
     * @apiParam {number} from_id 发送者id
     * @apiParam {number} from_role 发送者角色
     * @apiParam {number} from_name 发送者名称
     * @apiParam {number} to_role 接收者角色
     * @apiParam {array} to_list[]['phone'] 手机 
     * @apiParam {number} title 群发标题(非必填)
     * @apiParam {number} remark 群发备注(非必填)
     * @apiParam {number} enable 群发(非必填)
     * @apiParam {number} schedule 群发(非必填)
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function setSMS($param = null){
        if (empty($param)) {
            return false;
        }
        $this->assembleParam($param,Base::EVENT_SERVICE_SMS);
        $this->sendMode($param['to_list']);
        return $this;
    }

    /**
     * @api  setSMS 1-2:发送站内信
     * @apiGroup  站内信
     * @apiDescription 发送站内信
     *
     * @apiParam {number} project_id 项目
     * @apiParam {number} template_id 模板
     * @apiParam {number} from_id 发送者id
     * @apiParam {number} from_role 发送者角色
     * @apiParam {number} from_name 发送者名称
     * @apiParam {number} to_role 接收者角色
     * @apiParam {array} to_list[]['id'] 接收者id 
     * @apiParam {number} title 群发标题(非必填)
     * @apiParam {number} remark 群发备注(非必填)
     * @apiParam {number} enable 群发(非必填)
     * @apiParam {number} schedule 群发(非必填)
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function setInLine($param = null){
        if (empty($param)) {
            return false;
        }
        $this->assembleParam($param,Base::EVENT_SERVICE_INBOX);
        $this->sendMode($param['to_list']);
        return $this;
    }


    /**
     * @api  setSMS 1-3:获取站内信列表
     * @apiGroup  站内信
     * @apiDescription 发送站内信
     *
     * @apiParam {number} project_id 项目
     * @apiParam {number} role 角色
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function getInLineList($param = null){
        if (empty($param)) {
            return false;
        }
        $this->request($param,'inbox','GET');
        return $this;
    }



    /**
     * @api setTemplateAddUpdate 1-1:添加修改模板
     * @apiGroup  模板
     * @apiDescription 添加修改模板
     *
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function setTemplateAddUpdate($param = null){
        if (empty($param)) {
            return false;
        }
        if (!empty($param['id'])) {
            //修改
            $this->request($param,'template',"PUT");
        } else {
            //创建
            $this->request($param,'template',"POST");
        }
        return $this;
    }


   /**
     * @api  getTemplateDelete 1-2:模板删除
     * @apiGroup  模板
     * @apiDescription 模板删除
     *
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function setTemplateDelete($param = []){
        if (empty($param)) {
            return false;
        }
        $this->request($param,'template','DELETE');
        return $this;
    }

     /**
     * @api  getTemplateDetail 1-3:获取模板详情
     * @apiGroup  邮件
     * @apiDescription 发送邮件
     *
     * @apiParam {number} page 页数
     * @apiParam {number} perPage 条数
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function getTemplateDetail($param = []){
        if (empty($param)) {
            return false;
        }
        $this->request($param,'template','GET');
        return $this;
    }

    /**
     * @api {post,get} getTemplateList 1-2:获取模板列表
     * @apiGroup  邮件
     * @apiDescription 发送邮件
     *
     * @apiParam {number} page 页数
     * @apiParam {number} perPage 条数
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    public function getTemplateList($param = []){
        if (empty($param)) {
            return false;
        }
        $this->request($param,'template','GET');
        return $this;
    }


    /**
     * @api {post,get} getTemplateList 1-2:获取模板列表
     * @apiGroup  邮件
     * @apiDescription 发送邮件
     *
     * @apiParam {number} page 页数
     * @apiParam {number} perPage 条数
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */
    // public function getTemplateList($array = []){
    //     if (empty($param)) {
    //         return false;
    //     }
    //     $this->request($param,'template','GET');
    //     return $this;
    // }

    /**
     * 获取站内信列表
     * @param array $array 请求参数
     */
    public function getConfigList($param = []){
        $this->http_get($this->_url.Constant::URL_REQUEST['config'],$array);   
        return $this;
    }


    /**
     * @api {post,get} getChannelList 3-1:单项目通道列表
     * @apiGroup  项目
     * @apiDescription 单项目通道列表
     *
     * @apiParam {number} page 页数
     * @apiParam {number} perPage 条数
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */

    public function getChannelList($param = []){
        if (empty($param)) {
            return false;
        }
        $this->request($param,'config','GET');
        return $this;
    }

    /**
     * @api {post,get} setConfigAddUpdate 3-2:项目渠道添加修改
     * @apiGroup  项目
     * @apiDescription 项目渠道添加修改
     *
     * @apiParam {number} page 页数
     * @apiParam {number} perPage 条数
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */

    public function setConfigAddUpdate($param = []){
        if (empty($param)) {
            return false;
        }
        if (!empty($param['id'])) {
            //修改
            $this->request($param,'config',"PUT");
        } else {
            //创建
            $this->request($param,'config',"POST");
        }
        return $this;
    }

    /**
     * @api {post,get} setConfigDetail 3-3:项目渠道详情
     * @apiGroup  项目
     * @apiDescription 项目渠道详情
     *
     * @apiParam {number} project_id 项目id
     * @apiParam {number} id 渠道id
     *
     * @apiSuccess {number} code
     * @apiSuccess {string} message
     * @apiSuccess {array} data
     */

    public function setConfigDetail($param = []){
        if (empty($param)) {
            return false;
        }
        $this->request($param,'config',"GET");
        return $this;
    }


}