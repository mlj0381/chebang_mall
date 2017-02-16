<?php
/**
 * 被外部调用api
 * 2015-06-08
 */
class sysopen_ctl_chebang_default extends sysopen_chebang_api_controller
{
    /*
     * user_id
     * token
     * cp_id
     * */
    public function index()
    {
       /* $area='';
        base_kvstore::instance('ecos')->fetch('areaFileContents', $area);
        echo '<pre>';
        print_r($area);
        exit;*/
		$params = input::get();

        if ($this->auth($params['access_token'])){
            list($className, $methodName) = explode('.', $params['method']);
            if ($className && $methodName){
                unset($params['method'], $params['access_token']);
                if ($this->checkClass($className)){
                    $obj = kernel::single('sysopen_apis_chebang_'.$className);
                    if (method_exists($obj, $methodName)){
                        $result = $obj->$methodName($params);
                    }else{
                        $result =  array(
                            "errcode" => CHEBNAGAPI_TOKEN_ERR_METHOD,
                            "errmsg" => "不合法的API调用方法",
                        );
                    }
                }else{
                    $result =  array(
                        "errcode" => CHEBNAGAPI_TOKEN_ERR_METHOD,
                        "errmsg" => "不合法的API调用方法",
                    );
                }
            }else{
                $result =  array(
                    "errcode" => CHEBNAGAPI_TOKEN_ERR_METHOD,
                    "errmsg" => "不合法的API调用方法",
                );
            }
        }else{
            $result =  array(
                "errcode" => CHEBNAGAPI_TOKEN_ERROR,
                "errmsg" => "不合法的凭证类型",
            );
        }
        return response::json($result);exit;
    }

}
