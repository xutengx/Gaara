<?php

declare(strict_types = 1);
namespace Gaara\Core\Controller;

use Closure;
use PDOException;
use Response;

abstract class HttpController extends \Gaara\Core\Controller {

    // 可以使用 $this->post('id', '/^1[3|4|5|7|8][0-9]\d{8}$/', 'id不合法!'); 过滤参数
    use Traits\RequestTrait;

    // 可以使用 $this->display(); 展示视图
    use Traits\ViewTrait;

    // 可以使用 $this->getInfoOnWechatProfessional(); 一键授权(对数据库字段有一定要求) 
    // use Traits\WechatTrait; // 已过期

    /**
     * 返回一个msg响应
     * @param int $code 状态标记
     * @param string $msg 状态描述
     * @return string
     */
    protected function returnMsg(int $code = 0, string $msg = 'fail !'): string {
        $data = ['code' => $code, 'msg' => $msg];
        return Response::returnData($data);
    }

    /**
     * 返回一个data响应,当接收的参数是Closure时,会捕获PDOException异常,一旦捕获成功,将返回msg响应
     * @param mixed $content 响应内容
     * @return string
     */
    protected function returnData($content = ''): string {
        if ($content instanceof Closure) {
            try {
                $content = call_user_func($content);
            } catch (PDOException $pdo) {
                return $this->returnMsg(0, $pdo->getMessage());
            }
        }
        if ($content === false || $content === null || $content === 0 || $content === -1)
            return $this->returnMsg(0);
        $data = ['code' => 1, 'data' => $content];
        return Response::returnData($data);
    }
}
