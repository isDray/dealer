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
        $folder = date("Ymd");
        $desc = '第一批';
        DB::table('goods')->insert([
    // 1
    [
        'goods_sn' => 'A538145',
        'name' => '《AIMEI GIAREN》呢喃索愛！低腰情趣蕾絲美臀三角褲﹝白﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_1.jpg",
        'main_pic'=> "main/20190513/20190513_1.jpg",
        'price'=>69,
        'w_price'=>30,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 2
    [
        'goods_sn' => 'A538023',
        'name' => '《AIMEI GIAREN》迷漾甜心！情趣薄透寬版緹花蕾絲丁字褲﹝白﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_2.jpg",
        'main_pic'=> "main/20190513/20190513_2.jpg",
        'price'=>65,
        'w_price'=>28,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 3
    [
        'goods_sn' => 'A538010',
        'name' => '《AIMEI GIAREN》俏麗柔情！環扣絲滑鑽飾蝴蝶結丁字褲﹝孔雀藍﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_3.jpg",
        'main_pic'=> "main/20190513/20190513_3.jpg",
        'price'=>65,
        'w_price'=>28,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 4
    [
        'goods_sn' => 'A532788',
        'name' => '《Yisiting》性感火爆！珍珠按摩鏤空透視T字褲﹝紅色﹞ [ 大陸依思婷 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_4.jpg",
        'main_pic'=> "main/20190513/20190513_4.jpg",
        'price'=>59,
        'w_price'=>27,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 5
    [
        'goods_sn' => 'A538191',
        'name' => '《AIMEI GIAREN》柔媚攻勢！愛心造型網格柔紗綁帶丁字褲﹝紅﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_5.jpg",
        'main_pic'=> "main/20190513/20190513_5.jpg",
        'price'=>59,
        'w_price'=>26,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 6
    [
        'goods_sn' => 'A538208',
        'name' => '《AIMEI GIAREN》獨寵天使！誘臀珍珠按摩愛心網紗丁字褲﹝紅﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_6.jpg",
        'main_pic'=> "main/20190513/20190513_6.jpg",
        'price'=>55,
        'w_price'=>25,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 7
    [
        'goods_sn' => 'A532607',
        'name' => '《Yisiting》狂野豹紋！性感誘惑環扣T字褲 C [ 大陸依思婷 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_7.jpg",
        'main_pic'=> "main/20190513/20190513_7.jpg",
        'price'=>69,
        'w_price'=>30,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 8
    [
        'goods_sn' => 'A538392',
        'name' => '《AIMEI GIAREN》傾戀蜜愛！花漾蕾絲裸肌美臀三角褲﹝深藍﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_8.jpg",
        'main_pic'=> "main/20190513/20190513_8.jpg",
        'price'=>79,
        'w_price'=>35,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 9
    [
        'goods_sn' => 'A538070',
        'name' => '《AIMEI GIAREN》愛戀遐想！性感緹花刺繡露臀開襠褲﹝深藍﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_9.jpg",
        'main_pic'=> "main/20190513/20190513_9.jpg",
        'price'=>65,
        'w_price'=>28,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 10
    [
        'goods_sn' => 'A538127',
        'name' => '《AIMEI GIAREN》美臀風采！交叉細帶寬版蕾絲T字褲﹝紫﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_10.jpg",
        'main_pic'=> "main/20190513/20190513_10.jpg",
        'price'=>75,
        'w_price'=>32,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],
    // 11
    [
        'goods_sn' => 'A538121-1',
        'name' => '《AIMEI GIAREN》傾戀宣言！圓點網紗花朵造型丁字褲﹝紫﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_11.jpg",
        'main_pic'=> "main/20190513/20190513_11.jpg",
        'price'=>55,
        'w_price'=>25,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],    
    // 12
    [
        'goods_sn' => 'A538157',
        'name' => '《AIMEI GIAREN》戀戀日記！調整型細帶蕾絲丁字褲﹝黃﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_12.jpg",
        'main_pic'=> "main/20190513/20190513_12.jpg",
        'price'=>65,
        'w_price'=>28,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],  
    // 13
    [
        'goods_sn' => 'A538465',
        'name' => '《Yisiting》貓咪造型一片式C字褲 - 黑﹝隱形無痕+重複黏貼﹞ [ 大陸依思婷 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_13.jpg",
        'main_pic'=> "main/20190513/20190513_13.jpg",
        'price'=>159,
        'w_price'=>80,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],  
    // 14
    [
        'goods_sn' => 'A532792',
        'name' => '《Yisiting》性感火爆！珍珠按摩鏤空透視T字褲﹝黑色﹞ [ 大陸依思婷 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_14.jpg",
        'main_pic'=> "main/20190513/20190513_14.jpg",
        'price'=>59,
        'w_price'=>27,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ], 
    // 15
    [
        'goods_sn' => 'A538016',
        'name' => '《AIMEI GIAREN》情迷之夜！性感低腰花邊透明蕾絲T字褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_15.jpg",
        'main_pic'=> "main/20190513/20190513_15.jpg",
        'price'=>65,
        'w_price'=>28,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ], 
    // 16
    [
        'goods_sn' => 'A538203-1',
        'name' => '《AIMEI GIAREN》春光挑逗！動人露臀蕾絲超低腰T字褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_16.jpg",
        'main_pic'=> "main/20190513/20190513_16.jpg",
        'price'=>55,
        'w_price'=>25,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ], 
    // 17
    [
        'goods_sn' => 'A532562',
        'name' => '《Yisiting》情誘愛戀！細帶透明網紗情趣丁字褲﹝黑﹞ [ 大陸依思婷 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_17.jpg",
        'main_pic'=> "main/20190513/20190513_17.jpg",
        'price'=>79,
        'w_price'=>35,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ], 
    // 18
    [
        'goods_sn' => 'A538193',
        'name' => '《AIMEI GIAREN》柔媚攻勢！愛心造型網格柔紗綁帶丁字褲﹝黑﹞',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_18.jpg",
        'main_pic'=> "main/20190513/20190513_18.jpg",
        'price'=>59,
        'w_price'=>26,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ], 
    // 19
    [
        'goods_sn' => 'A538215',
        'name' => '《AIMEI GIAREN》美妙情網！開襠珍珠按摩蕾絲丁字褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_19.jpg",
        'main_pic'=> "main/20190513/20190513_19.jpg",
        'price'=>69,
        'w_price'=>30,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ], 
    // 20
    [
        'goods_sn' => 'A538050',
        'name' => '《AIMEI GIAREN》窺探之夜！誘人側綁帶蕾絲丁字褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_20.jpg",
        'main_pic'=> "main/20190513/20190513_20.jpg",
        'price'=>59,
        'w_price'=>26,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],     
    // 21
    [
        'goods_sn' => 'A538158',
        'name' => '《AIMEI GIAREN》戀戀日記！調整型細帶蕾絲丁字褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_21.jpg",
        'main_pic'=> "main/20190513/20190513_21.jpg",
        'price'=>65,
        'w_price'=>28,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],    
    // 22
    [
        'goods_sn' => 'A538164',
        'name' => '《AIMEI GIAREN》寵愛滋味！性感網紗愛心丁字褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_22.jpg",
        'main_pic'=> "main/20190513/20190513_22.jpg",
        'price'=>59,
        'w_price'=>26,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],  
    // 23
    [
        'goods_sn' => 'A538090',
        'name' => '《AIMEI GIAREN》午夜遐想！蕾絲雙細帶環扣丁字褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_23.jpg",
        'main_pic'=> "main/20190513/20190513_23.jpg",
        'price'=>59,
        'w_price'=>26,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],  
    // 24
    [
        'goods_sn' => 'A538248',
        'name' => '《AIMEI GIAREN》溫柔誘惑！鏤空線條蝴蝶結造型三角褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_24.jpg",
        'main_pic'=> "main/20190513/20190513_24.jpg",
        'price'=>69,
        'w_price'=>30,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ],  
    // 25
    [
        'goods_sn' => 'A538323',
        'name' => '《AIMEI GIAREN》舞動倩影！大蝴蝶刺繡細帶鏤空造型內褲﹝黑﹞ [ 大陸曖昧 ]',
        'cid'=>7,
        'thumbnail'=> "thumbnail/20190513/20190513_25.jpg",
        'main_pic'=> "main/20190513/20190513_25.jpg",
        'price'=>75,
        'w_price'=>32,
        'status'=>1,
        'desc' =>$desc,
        'created_at'=>$nowtme,
        'updated_at'=>$nowtme,
    ], 



    ]);


    }
}
