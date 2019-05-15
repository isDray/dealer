<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
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

        DB::table('category')->insert([

            [ 'name' => '女High ‧ 多功能/旋轉/滾珠/吸吮', 'parent' => '0','keyword'=>'女High ‧ 多功能/旋轉/滾珠/吸吮','desc'=> '女High ‧ 多功能/旋轉/滾珠/吸吮','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            [ 'name' => '女High ‧ 伸縮蠕動按摩棒', 'parent' => '0','keyword'=>'女High ‧ 伸縮蠕動按摩棒','desc'=> '女High ‧ 伸縮蠕動按摩棒','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '女High ‧ G點震動按摩棒', 'parent' => '0','keyword'=>'女High ‧ G點震動按摩棒','desc'=> '女High ‧ G點震動按摩棒','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '女High ‧ 逼真老二按摩棒', 'parent' => '0','keyword'=>'女High ‧ 逼真老二按摩棒','desc'=> '女High ‧ 逼真老二按摩棒','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '女High ‧ AV女優最愛按摩棒', 'parent' => '0','keyword'=>'女High ‧ AV女優最愛按摩棒','desc'=> '女High ‧ AV女優最愛按摩棒','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '女High ‧ 激愛挑逗跳蛋', 'parent' => '0','keyword'=>'女High ‧ 激愛挑逗跳蛋','desc'=> '女High ‧ 激愛挑逗跳蛋','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => ' 猛男 ‧ 加強裝備/長/粗/久', 'parent' => '0','keyword'=>' 猛男 ‧ 加強裝備/長/粗/久','desc'=> ' 猛男 ‧ 加強裝備/長/粗/久','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '男女合歡輔助品', 'parent' => '0','keyword'=>'男女合歡輔助品','desc'=> '男女合歡輔助品','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => ' 其他/特殊商品', 'parent' => '0','keyword'=>' 其他/特殊商品','desc'=> ' 其他/特殊商品','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '挑逗心機情趣睡衣', 'parent' => '0','keyword'=>'挑逗心機情趣睡衣','desc'=> '挑逗心機情趣睡衣','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '誘惑角色扮演戰鬥服', 'parent' => '0','keyword'=>'誘惑角色扮演戰鬥服','desc'=> '誘惑角色扮演戰鬥服','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '野性貓裝網衣/絲網襪', 'parent' => '0','keyword'=>'野性貓裝網衣/絲網襪','desc'=> '野性貓裝網衣/絲網襪','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
            
            [ 'name' => '情慾丁字/開檔褲', 'parent' => '0','keyword'=>'情慾丁字/開檔褲','desc'=> '情慾丁字/開檔褲','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],
           
            [ 'name' => '性感造型三角褲', 'parent' => '0','keyword'=>'性感造型三角褲','desc'=> '性感造型三角褲','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],


           

        ]);  

        DB::table('multiple')->insert([
    
            ['multiple'=>2.0 , 'created_at'=>$nowtme,'updated_at'=>$nowtme],
            ['multiple'=>2.2 , 'created_at'=>$nowtme,'updated_at'=>$nowtme],
            ['multiple'=>2.5 , 'created_at'=>$nowtme,'updated_at'=>$nowtme],
        ]);        

    }
}
