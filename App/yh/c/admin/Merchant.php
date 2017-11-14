<?php

declare(strict_types = 1);
namespace App\yh\c\admin;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m\UserMerchant;
use Gaara\Core\Request;
use Gaara\Core\Controller;
use PDOException;

/**
 * 管理员, 对商户的权限管理
 */
class Merchant extends Controller {
    
    /**
     * 查询商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     * @return type
     */
    public function select(UserMerchant $merchant) {
        return $this->returnData(function() use ($merchant){
            return $merchant->getAll();
        });
    }

    /**
     * 新增商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function create() {
        return $this->returnMsg(0, '管理员不可以新增商户');
    }

    /**
     * 更新商户信息
     * @param Request $request
     * @param UserMerchant $merchant
     */
    public function update(Request $request, UserMerchant $merchant) {
        $admin_id = (int)$request->userinfo['id'];
        $merchant_id = (int)$this->input('id');
        $merchantInfo = $request->input;
        // 原数据
        $merchantOldInfo = $merchant->getInfo( $merchant_id );
        if(empty($merchantOldInfo))
            return $this->returnMsg(0, '要修改的商户不存在');
        // 将要被替换的文件
        $oldFileArr = [];
        
        $merchant->orm = $merchantInfo;
        $merchant->orm['reviewe_at'] = date('Y-m-d H:i:s');
        $merchant->orm['admin_id'] = $admin_id;
        // 保存文件
        foreach($request->file as $k => $file){
            if($file->is_img() && $file->is_less()){
                if($file->move_uploaded_file()){
                    $merchant->orm[$k] = $file->saveFilename;
                    $oldFileArr[] = $merchantOldInfo[$k];
                }
            } else {
                $request->file->cleanAll();
                return $this->returnMsg(0, '上传类型不为图片, 或者大于8m');
            }
        }
        
        // 写入数据库, 若失败则删除已保存的文件
        try{
            $res = $merchant->save($merchantOldInfo['id']);
            $this->clean($oldFileArr);
            return $this->returnData($res);
        }catch(PDOException $pdo){
            $request->file->cleanAll();
            return $this->returnMsg(0, $pdo->getMessage());
        }
    }

    /**
     * 删除商户信息
     * @return type
     */
    public function destroy() {
        return $this->returnMsg(0, '管理员不可以删除商户');
    }
    
    /**
     * 删除数组中的文件
     * @param array $arr
     */
    private function clean(array $arr){
        foreach ($arr as $v) {
            if(is_file(ROOT.$v)){
                unlink(ROOT.$v);
            }elseif(is_file($v)){
                unlink($v);
            }
        }
    }
}
