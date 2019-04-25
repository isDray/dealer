<?php
namespace App\freeHelper;

use App\Category;
/*----------------------------------------------------------------
 | 商品類別輔助類別 
 |----------------------------------------------------------------
 | 提供一些常用、通用的方法
 | 
 | 方法清單:
 |
 | 1.取出所有類別         - getAllCategory()
 | 2.取出所有類別下拉選單 - getAllCategoryForSelect()
 | 3.遞迴取出類別         - getCategoryChild()
 | 4.檢查特定類別是否存在 - categoryExist()
 |
 |
 */
Class categoryTool{
    
    public static $categorySelect = [];
    
    // 取出所有類別  : 無參數
    public static function getAllCategory(){

        return Category::get();
    }
    

    // 取出所有類別下拉選單
    public static function getAllCategoryForSelect(){
        
        $allParents = Category::where('parent',0)->orderBy('sort', 'asc')->get();
        $allParents = $allParents->toArray();
        
        // 將categorySelect重置一次
        self::$categorySelect = [];
        
        // 取得子類別
        foreach ($allParents as $allParentk => $allParent) {

            $tmp = [ 'id'   => $allParent['id'],
                     'name' => $allParent['name'],
                     'desc' => $allParent['desc'],
                     'status' => $allParent['status'],
                     'updated_at' => $allParent['updated_at'],
                     'level' => '',
                     'levelIcon' => '',
                   ];

            array_push(self::$categorySelect, $tmp );

            self::getCategoryChild( $allParent['id'] , 1 );

        }

        return self::$categorySelect;
    }
    
    // 遞迴搜尋分類
    public static function getCategoryChild( $_id , $_level ){
        
        // 計算各階層箭頭數
        $level = '';

        for ($i=0; $i < $_level ; $i++) { 

            $level .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        }

        // 確認有無類別以此類別作為父類別
        if( Category::where('parent', '=', $_id)->exists() ){

            $allCategorys = Category::where('parent', '=', $_id)->orderBy('sort', 'asc')->get();
            $allCategorys = $allCategorys->toArray();

            foreach ($allCategorys as $allCategory) {
                
                $tmp = [  'id'   => $allCategory['id'],
                          'name' => $allCategory['name'],
                          'desc' => $allCategory['desc'],
                          'status' => $allCategory['status'],
                          'updated_at' => $allCategory['updated_at'],
                          'level'=>$level,
                          'levelIcon' => '<i class="material-icons">subdirectory_arrow_right</i>'
                       ];

                array_push(self::$categorySelect, $tmp );
                
                // 如果有類別以當下類別為父類別則繼續向下做遞迴
                self::getCategoryChild( $allCategory['id'] , $_level+1 );

            }
        }
    }


    // 檢查特定類別是否存在 : $_id => 類別id
    public static function categoryExist( $_id ){

        return Category::where('id', '=', $_id )->exists();
    }
    

    // 取出指定的商品類別 : $_id => 類別id
    public static function getCategory( $_id ){
        
        return Category::find( $_id );

    }
}
?>