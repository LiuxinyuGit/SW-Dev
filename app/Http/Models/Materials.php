<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Materials extends Model
{
  //获取材料列表
  static public function getLists(){
    $lists = DB::table('materials')->where('ma_status','<>',1)->get();
    return $lists;
  }

  //获取材料记录
  static public function getLogs(){
    $logs = DB::table('materials_logs')->leftJoin('materials', 'materials_logs.ma_id', '=', 'materials.ma_id')->orderBy('date', 'desc')->orderBy('id', 'desc')->get();
    return $logs;
  }

  //根据ID获取信息
  static public function getInfo($id){
    $info = DB::table('materials')->where('ma_id',$id)->first();
    return $info;
  }

  //新增/更新信息
  static public function saveInfo($data){
    $info = array(
      'ma_name' => $data->ma_name,
      'ma_number' => $data->ma_number,
      'ma_price' => $data->ma_price,
      'ma_last_date' => date('Y-m-d')
    );
    $info2 = array(
      'number' => $data->ma_number,
      'residue' => $data->ma_number,
      'sign' => 1,
      'date' => date('Y-m-d')
    );
    if(isset($data->ma_id)){
      $ori_price = DB::table('materials')->where('ma_id',$data->ma_id)->value('ma_price');
      $result = DB::table('materials')->where('ma_id',$data->ma_id)->update($info);
      $info2['ma_id'] = $data->ma_id;
      $info2['ori_price'] = $ori_price;
    } else {
      $result = DB::table('materials')->insertGetId($info);
      $info2['ma_id'] = $result;
    }
    if($result){
      $result = DB::table('materials_logs')->insert($info2);
    } else {
      $result = 0;
    }
    return $result;
  }

  //根据ID删除记录
  static public function delById($id){
    $ori_price = DB::table('materials')->where('ma_id',$id)->value('ma_price');
    $result = DB::table('materials')->where('ma_id',$id)->update(['ma_status' => 1]);
    if($result){
      $info = array(
        'ma_id' => $id,
        'number' => 0,
        'residue' => 0,
        'ori_price' =>$ori_price,
        'sign' => 3,
        'date' => date('Y-m-d')
      );
      $result = DB::table('materials_logs')->insert($info);
    }
    return $result;
  }

  //补充材料
  static public function increase($id,$numb,$date){
    // DB::enableQueryLog();
    DB::beginTransaction();
      try{
        $result1 = DB::update('update sw_materials set ma_number = ma_number + '.$numb.', ma_last_date = \''.$date.'\' where ma_id = '.$id);
        $residue = DB::table('materials')->where('ma_id',$id)->value('ma_number');
        $result2 = DB::table('materials_logs')->insert(['ma_id' => $id,'number' => $numb,'residue' => $residue,'date' => $date]);
        if($result1 && $result2){
          DB::commit();
          return 1;
        } else {
          DB::rollback();//事务回滚
          return 0;
          // return DB::getQueryLog();
        }
      } catch (\Exception $e){
        return $e;
        // return DB::getQueryLog();
        // return $sql;
      }
  }
}
