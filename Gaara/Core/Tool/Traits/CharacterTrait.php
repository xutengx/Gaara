<?php

declare(strict_types = 1);
namespace Gaara\Core\Tool\Traits;

/**
 * 字符操作
 */
trait CharacterTrait {

    /**
     * 人性化相对时间
     * @param int $sTime 目标时间戳
     * @param string $format 时间格式
     * @return string
     */
    final public function friendlyDate(int $sTime = 0, string $format = 'Y-m-d H:i'): string {
        $dTime = time() - $sTime;
        $state = $dTime > 0 ? '前' : '后';
        $dTime = abs($dTime);
        if ($dTime < 60) {
            return $dTime . ' 秒' . $state;
        } else if ($dTime < 3600) {
            return intval($dTime / 60) . ' 分钟' . $state;
        } else if ($dTime < 3600 * 24) {
            return intval($dTime / 3600) . ' 小时' . $state;
        } else if ($dTime < 3600 * 24 * 7) {
            return intval($dTime / (3600 * 24)) . ' 天' . $state;
        } else
            return date($format, $sTime);
    }

    /**
     * 字符串长度控制(截取)
     * @param string $string 原字符串
     * @param int $length 目标长度
     * @param string $havedot 多余展示符, 如 ...
     * @param string $charset 字符编码
     * @return string
     */
    public function cutstr(string $string = '', int $length = 9, string $havedot = '', string $charset = 'utf8'): string {
        if (strtolower($charset) === 'gbk')
            $charset = 'gbk';
        else
            $charset = 'utf8';
        if (strlen($string) <= $length)
            return $string;
        if (function_exists('mb_strcut'))
            $string = mb_substr($string, 0, $length, $charset);
        else {
            $pre = '{%';
            $end = '%}';
            $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
            $strlen = strlen($string);
            $n = $tn = $noc = 0;
            if ($charset === 'utf8') {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t === 9 || $t === 10 || (32 <= $t && $t <= 126)) {
                        $tn = 1;
                        $n++;
                        $noc++;
                    } elseif (194 <= $t && $t <= 223) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } elseif (224 <= $t && $t <= 239) {
                        $tn = 3;
                        $n += 3;
                        $noc++;
                    } elseif (240 <= $t && $t <= 247) {
                        $tn = 4;
                        $n += 4;
                        $noc++;
                    } elseif (248 <= $t && $t <= 251) {
                        $tn = 5;
                        $n += 5;
                        $noc++;
                    } elseif ($t === 252 || $t === 253) {
                        $tn = 6;
                        $n += 6;
                        $noc++;
                    } else {
                        $n++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            } else {
                while ($n < $strlen) {
                    $t = ord($string[$n]);
                    if ($t > 127) {
                        $tn = 2;
                        $n += 2;
                        $noc++;
                    } else {
                        $tn = 1;
                        $n++;
                        $noc++;
                    }
                    if ($noc >= $length) {
                        break;
                    }
                }
                if ($noc > $length) {
                    $n -= $tn;
                }
                $strcut = substr($string, 0, $n);
            }
            $string = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        }
        return $string . $havedot;
    }

    /**
     * 解析XML格式的字符串
     * @param string $str
     * @return false|array 解析正确就返回解析结果,否则返回false,说明字符串不是XML格式
     */
    public function xml_decode(string $str) {
        $bool = null;
        $xml_parser = xml_parser_create();
        if (xml_parse($xml_parser, $str, true))
            $bool = (json_decode(json_encode(simplexml_load_string($str)), true));
        xml_parser_free($xml_parser);
        return $bool;
    }

    /**
     * XML编码
     * @param type $data        数据
     * @param string $encoding  数据编码
     * @param string $root      根节点名
     * @param string $item      数字索引的子节点名
     * @param string $attr      根节点属性
     * @param string $id        数字索引子节点key转换的属性名
     * @return string
     */
    public function xml_encode($data, string $encoding = 'utf-8', string $root = 'root', string $item = 'item', string $attr = '', string $id = 'id'): string {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= $this->data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed  $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id   数字索引key转换为的属性名
     * @return string
     */
    public function data_to_xml($data, string $item = 'item', string $id = 'id'): string {
        $xml = $attr = '';
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (is_numeric($key)) {
                    $id && $attr = " {$id}=\"{$key}\"";
                    $key = $item;
                }
                $xml .= "<{$key}{$attr}>";
                $xml .= (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
                $xml .= "</{$key}>";
            }
        } else {
            return $data;
        }
        return $xml;
    }

}
