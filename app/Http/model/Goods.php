<?php

namespace App\Http\model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    // //与模型关联的表名
    // protected $table="member";
    // protected $primarykey='id';
    // //指示模型是否自动维护时间戳
    // public $timetamps=false;
    // //模型的连接名称
    // protected $connection='mysql';
    protected $primaryKey ='id';
	protected $table = 'goods';
	public $timestamps = false;
	protected $guarded = [];
}
