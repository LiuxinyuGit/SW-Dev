<?php

namespace App\Http\Controllers;

use App\Http\Models\Goods;
use App\Http\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class OrdersController extends Controller
{
    private $result = array('model' => '订单管理');

    public function test()
    {
        $msg = DB::table('goods')->where('go_id', 5)->value('go_number');
        // ->decrement('go_number', 5);
        // ->select('go_number', 'go_price')->get();
        // return '.' . (true && 1) . '.';
        // dump($msg);die;
        echo '测试方法';
    }

    //显示列表
    public function lists()
    {
        $result           = $this->result;
        $result['title']  = '列表';
        $result['active'] = '4-1';
        return view('orders/lists')->with('result', $result);
    }

    //获取全部订单信息
    public function getLists()
    {
        $lists = Orders::getLists();
        return json_encode($lists);
    }

    //新增/修改页面
    public function add()
    {
        $area             = SZ_DISTRICT;
        $result           = $this->result;
        $result['active'] = '4-2';
        $id               = Input::get('go_id');
        $goods            = Goods::getLists();
        if ($id) {
            $data            = Orders::getInfo($id);
            $result['data']  = $data;
            $result['title'] = '修改';
        } else {
            $result['title'] = '新增';
        }

        return view('orders/add')->with('result', $result)->with('area', $area)->with('goods', $goods);
    }

    //保存信息
    public function save(Request $request)
    {
        $result = Orders::saveInfo($request);
        return json_encode($result);
    }

    //改变状态按钮
    public function chgBtn()
    {
        $ord_id = Input::get('ord_id');
        $result = Orders::chgBtn($ord_id);
        return $result;
    }

    //删除信息
    public function del()
    {
        $ord_id = Input::get('ord_id');
        $result = Orders::delById($ord_id);
        return $result;
    }
}
