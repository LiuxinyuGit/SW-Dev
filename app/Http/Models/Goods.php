<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Goods extends Model
{
    //获取商品列表
    public static function getLists()
    {
        $lists = DB::table('goods')->where('go_status', '<>', 2)->get();
        return $lists;
    }

    //获取商品记录
    public static function getLogs()
    {
        $logs = DB::table('goods_logs')->leftJoin('goods', 'goods_logs.go_id', '=', 'goods.go_id')->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        return $logs;
    }

    //根据ID获取信息
    public static function getInfo($id)
    {
        $info = DB::table('goods')->where('go_id', $id)->first();
        return $info;
    }

    //新增/更新信息
    public static function saveInfo($data)
    {
        $info = array(
            'go_name'   => $data->go_name,
            'go_number' => $data->go_number,
            'go_price'  => $data->go_price,
        );
        $info2 = array(
            'number'  => $data->go_number,
            'residue' => $data->go_number,
            'sign'    => 1,
            'date'    => date('Y-m-d'),
        );
        // DB::enableQueryLog();
        if (isset($data->go_id)) {
            $ori_price          = DB::table('goods')->where('go_id', $data->go_id)->value('go_price');
            $result             = DB::table('goods')->where('go_id', $data->go_id)->update($info);
            $info2['go_id']     = $data->go_id;
            $info2['ori_price'] = $ori_price;
        } else {
            // return $info;
            $result         = DB::table('goods')->insertGetId($info);
            $info2['go_id'] = $result;
        }
        if ($result) {
            $result = DB::table('goods_logs')->insert($info2);
        } else {
            $result = 0;
        }
        // return DB::getQueryLog();
        return $result;
    }

    public static function chgBtn($id)
    {
        $sta    = DB::table('goods')->where('go_id', $id)->value('go_status');
        $result = DB::table('goods')->where('go_id', $id)->update(['go_status' => !$sta]);
        return $result;
    }

    //根据ID删除记录
    public static function delById($id)
    {
        $ori_price = DB::table('goods')->where('go_id', $id)->value('go_price');
        $result    = DB::table('goods')->where('go_id', $id)->update(['go_status' => 2]);
        if ($result) {
            $info = array(
                'go_id'     => $id,
                'number'    => 0,
                'residue'   => 0,
                'ori_price' => $ori_price,
                'sign'      => 3,
                'date'      => date('Y-m-d'),
            );
            $result = DB::table('goods_logs')->insert($info);
        }
        return $result;
    }

    //补充商品
    public static function increase($id, $numb, $date)
    {
        // DB::enableQueryLog();
        DB::beginTransaction();
        try {
            $result1 = DB::update('update sw_goods set go_number = go_number + ' . $numb . ', go_last_date = \'' . $date . '\' where go_id = ' . $id);
            $residue = DB::table('goods')->where('go_id', $id)->value('go_number');
            $result2 = DB::table('goods_logs')->insert(['go_id' => $id, 'number' => $numb, 'residue' => $residue, 'date' => $date]);
            if ($result1 && $result2) {
                DB::commit();
                return 1;
            } else {
                DB::rollback(); //事务回滚
                return 0;
                // return DB::getQueryLog();
            }
        } catch (\Exception $e) {
            return $e;
            // return DB::getQueryLog();
            // return $sql;
        }
    }
}
