<?php

declare(strict_types = 1);
namespace App\yh\c\merchant;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\UserMerchant;
use Main\Core\Request;
use Main\Core\Controller\HttpController;

class Info extends HttpController {
    
    /**
     * 查询商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     * @return type
     */
    public function select(Request $request, UserMerchant $merchant) {
        $userinfo = $request->userinfo;
       
        return $this->returnData(
            $merchant->getInfo( (int)$userinfo['id'] )
        );
    }

    /**
     * 更新商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function update(Request $request, UserMerchant $merchant) {
        echo 'this is update';
    }

    /**
     * 删除商户信息
     * @return type
     */
    public function destroy() {
        return $this->returnMsg(0, '不可以删除');
    }

    /**
     * 新增商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function create(Request $request, UserMerchant $merchant) {
        $userinfo = $request->userinfo;
        
        
        $merchant->orm = $userinfo;
        return $this->returnData(
            $merchant->create()
        );
    }
    
    
}
