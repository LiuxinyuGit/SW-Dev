<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Orders extends Model
{
    //获取订单列表
    public static function getLists()
    {
        $lists = DB::table('orders')->where('ord_status', '<>', 2)->get();
        // return DB::getQueryLog();
        return $lists;
    }

    //根据ID获取信息
    public static function getInfo($id)
    {
        $info = DB::table('orders')->where('ord_id', $id)->first();
        return $info;
    }

    //新增/更新信息
    public static function saveInfo($data)
    {
        $ord_id = $data->ord_id;
        $info   = array(
            'ord_client'         => $data->ord_client,
            'client_phone'       => $data->client_phone,
            'ord_scheduled_time' => $data->ord_scheduled_time,
            'ord_area'           => $data->ord_area,
            'ord_address'        => $data->ord_address,
            'ord_amount'         => $data->ord_amount,
            'ord_content'        => $data->ord_content,
        );

        if ($ord_id) {
            $result = DB::table('orders')->where('ord_id', $ord_id)->update($info);
        } else {
            $info['ord_create_time'] = date('Y-m-d');
            $expend                  = array();
            $ord_content             = explode('|', $data->ord_content);
            DB::beginTransaction();
            try {
                $val = 1;
                foreach ($ord_content as $value) {
                    $content   = explode(':', $value);
                    $goods_num = array_pop($content);
                    $goods_id  = array_shift($content);
                    $go_number = DB::table('goods')->where('go_id', $goods_id)->value('go_number');
                    $ori_price = DB::table('goods')->where('go_id', $goods_id)->value('go_price');
                    $val       = $val && (DB::table('goods')->where('go_id', $goods_id)->decrement('go_number', $goods_num));
                    $val       = $val && (DB::table('goods_logs')->insert(['go_id' => $goods_id, 'sign' => 2, 'number' => $goods_num, 'date' => date('Y-m-d'), 'residue' => ($go_number - $goods_num), 'ori_price' => $ori_price]));
                }
                $result = DB::table('orders')->insertGetId($info);
                if ($result && $val) {
                    DB::commit();
                    return $result;
                }
            } catch (\Exception $e) {
                DB::rollback();
                return $e;
            }
        }
    }

    public static function chgBtn($ord_id)
    {
        $sta    = DB::table('orders')->where('ord_id', $ord_id)->value('ord_status');
        $result = DB::table('orders')->where('ord_id', $ord_id)->update(['ord_status' => !$sta]);
        return $result;
    }

    //根据ID删除记录
    public static function delById($id)
    {
        $result = DB::table('orders')->where('ord_id', $id)->update(['ord_status' => 2]);
        // if ($result) {
        //     $info = array(
        //         'ord_id'    => $id,
        //         'number'    => 0,
        //         'residue'   => 0,
        //         'ori_price' => $ori_price,
        //         'sign'      => 3,
        //         'date'      => date('Y-m-d'),
        //     );
        //     $result = DB::table('orders_logs')->insert($info);
        // }
        return $result;
    }
}
