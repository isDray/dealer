<?php

use Illuminate\Database\Seeder;

class GoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $nowtme = date("Y-m-d H:i:s");
        DB::table('goods')->insert([

    [
        'goods_sn' => 'A538145',
        'name' => '《AIMEI GIAREN》呢喃索愛！低腰情趣蕾絲美臀三角褲﹝白﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> 'thumbnail/20190510/20190510_3.jpeg',
        'main_pic'=> 'main/20190510/20190510_3.jpeg',
        'price'=>0,
        'w_price'=>30,
        'status'=>1,
        'desc' =>'test',
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    [
        'goods_sn' => 'A538023',
        'name' => '《AIMEI GIAREN》迷漾甜心！情趣薄透寬版緹花蕾絲丁字褲﹝白﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> 'thumbnail/20190510/20190510_4.jpeg',
        'main_pic'=> 'main/20190510/20190510_4.jpeg',
        'price'=>0,
        'w_price'=>28,
        'status'=>1,
        'desc' =>'test',
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    ]);

    }
}
