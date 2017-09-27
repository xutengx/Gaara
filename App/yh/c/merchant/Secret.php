<?php

declare(strict_types = 1);
namespace App\yh\c\merchant;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\MerchantSecret;
use Main\Core\Request;
use Main\Core\Controller\HttpController;

/**
 * 商户公私钥资料
 */
class Secret extends HttpController {
    
    /**
     * 查询商户的密钥信息
     * @param Request $request
     * @param MerchantSecret $secret
     * @return type
     */
    public function select(Request $request, MerchantSecret $secret) {
        $merchant_id = (int)$request->userinfo['id'];
        
        return $this->returnData(function() use ($secret, $merchant_id){
            return $secret->getByMerchantId( $merchant_id );
        });
    }

    /**
     * 新增 (初始化) 商户的密钥信息
     * @param Request $request
     * @param MerchantSecret $secret
     */
    public function create(Request $request, MerchantSecret $secret) {
        $merchant_id = (string)$request->userinfo['id'];
        $yh_key = md5($merchant_id.(string)time());

        //创建公钥和私钥   返回资源  
        $res = openssl_pkey_new([
            //"digest_alg" => "sha512",  
            "private_key_bits" => 1024, //字节数    512 1024  2048   4096 等  
            "private_key_type" => OPENSSL_KEYTYPE_RSA, //加密类型
        ]);

        //从得到的资源中获取私钥    并把私钥赋给 $privKey
        openssl_pkey_export($res, $private_key);

        //从得到的资源中获取公钥    返回公钥 $pubKey
        $info = openssl_pkey_get_details($res);

        $public_key = $info["key"];
        
        return $this->returnData(function() use ($secret, $merchant_id, $yh_key, $public_key, $private_key){
            return $secret->createInfo( (int)$merchant_id, $yh_key, $public_key, $private_key);
        });
    }

    /**
     * 更新商户的密钥信息
     * @param Request $request
     * @param MerchantSecret $secret
     */
    public function update(Request $request, MerchantSecret $secret) {
        $secret->orm = $request->input;
        $secret->orm['id'] = $request->userinfo['id'];
        
        return $this->returnData(function() use ($secret){
            return $secret->save();
        });
    }

    /**
     * 删除商户的密钥信息
     * @param Request $request
     * @param MerchantSecret $secret
     * @return type
     */
    public function destroy(Request $request, MerchantSecret $secret) {
        $merchant_id = (int)$request->userinfo['id'];

        return $this->returnData(function() use ($secret, $merchant_id){
            return $secret->delById( $merchant_id );
        });
       
    }
}
