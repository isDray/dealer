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

            [ 'name' => '情趣用品', 'parent' => '0','keyword'=>'情趣用品','desc'=> '情趣用品集合','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],

            [ 'name' => '女性High品', 'parent' => '1','keyword'=>'女性High品','desc'=> '女性High品','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],

            [ 'name' => '男性增強', 'parent' => '1','keyword'=>'男性增強','desc'=> '男性增強','status'=> '1','sort'=>2,'created_at'=>$nowtme,'updated_at'=>$nowtme],

            [ 'name' => '另類商品', 'parent' => '1','keyword'=>'另類商品','desc'=> '另類商品','status'=> '1','sort'=>3,'created_at'=>$nowtme,'updated_at'=>$nowtme],

            [ 'name' => '衣服', 'parent' => '0','keyword'=>'衣服','desc'=> '所有衣服相關集合','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],

            [ 'name' => '情趣睡衣', 'parent' => '5','keyword'=>'情趣睡衣','desc'=> '情趣睡衣','status'=> '1','sort'=>1,'created_at'=>$nowtme,'updated_at'=>$nowtme],

            [ 'name' => '誘惑丁字褲/開襠褲', 'parent' => '5','keyword'=>'誘惑丁字褲/開襠褲','desc'=> '誘惑丁字褲/開襠褲','status'=> '1','sort'=>2,'created_at'=>$nowtme,'updated_at'=>$nowtme],

        ]);  

        DB::table('multiple')->insert([
    
            ['multiple'=>2.0 , 'created_at'=>$nowtme,'updated_at'=>$nowtme],
            ['multiple'=>2.2 , 'created_at'=>$nowtme,'updated_at'=>$nowtme],
            ['multiple'=>2.5 , 'created_at'=>$nowtme,'updated_at'=>$nowtme],
        ]);        

    }
}
