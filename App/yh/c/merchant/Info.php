<?php

declare(strict_types = 1);
namespace App\yh\c\merchant;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\UserMerchant;
use Main\Core\Request;
use Main\Core\Controller\HttpController;

/**
 * 商户操作
 */
class Info extends HttpController {
    
    /**
     * 查询商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     * @return type
     */
    public function select(Request $request, UserMerchant $merchant) {
        $userid = (int)$request->userinfo['id'];
        
        return $this->returnData(function() use ($merchant, $userid){
            return $merchant->getInfo( $userid );
        });
    }

    /**
     * 新增商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function create(Request $request, UserMerchant $merchant) {
        $userinfo = $request->userinfo;
        $merchantInfo = $request->input;
        
        $merchant->orm = $merchantInfo;
        $merchant->orm['id'] = $userinfo['id'];
        
        return $this->returnData(function() use ($merchant){
            return $merchant->create();
        });
    }

    /**
     * 更新商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function update(Request $request, UserMerchant $merchant) {
        $userid = $request->userinfo['id'];
        $merchantInfo = $request->input;
        
        $merchant->orm = $merchantInfo;
        $merchant->orm['modify_at'] = date('Y-m-d H:i:s');
        
        return $this->returnData(function() use ($merchant, $userid){
            $merchant->save($userid);
        });
    }

    /**
     * 删除商户信息
     * @return type
     */
    public function destroy(Request $request, UserMerchant $merchant) {
        $userid = (int)$request->userinfo['id'];
        
        return $this->returnData(function() use ($merchant, $userid){
            $merchant->delById( $userid );
        });
    }
}
