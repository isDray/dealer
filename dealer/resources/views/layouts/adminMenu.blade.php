<!--

主要操作選單:

    依照不同身分及權限給予不同menu

-->

<div class="menu">
    
    <ul class="list">
                    <li class="header">操作選單</li>
                    
                    <li class="@if($controller == 'HomeController') active @endif">
                        <a href="{{url('/home')}}">
                            <i class="material-icons">home</i>
                            <span>起始頁面</span>
                        </a>
                    </li>

                    <!-- 商品管理 -->
                    @role('Admin')
                    <li class="@if($controller == 'GoodsController' || $controller == 'CategoryController') active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_compact</i>
                            <span>商品管理模組</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{url('/goods')}}">商品管理</a>
                            </li>                              
                            <li>
                                <a href="{{url('/category')}}">商品分類管理</a>
                            </li>                              
                        </ul>
                    </li>
                    @endrole
                    <!-- /商品管理 -->

                    <!-- 經銷商商品管理 -->
                    @role('Dealer')
                    <li class="@if($controller == 'GoodsController' || $controller == 'CategoryController') active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_compact</i>
                            <span>商品管理模組</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{url('/price')}}">商品管理</a>
                            </li>                                                        
                          
                        </ul>
                    </li>
                    @endrole
                    <!-- /商品管理 -->

                    <!-- 訂單管理 -->
                    <li class="@if($controller == 'OrderController' ) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>訂單管理模組</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{url('/order')}}">訂單管理</a>
                            </li>                              
                           
                          
                        </ul>
                    </li>
                    <!-- /訂單管理 -->

                    <!-- 進貨管理 -->
                    <li class="@if($controller == 'PurchaseController' ) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">shopping_cart</i>
                            <span>進貨管理模組</span>
                        </a>
                        <ul class="ml-menu">
                            
                            <li>
                               <a href="{{url('/purchaseEstimate')}}">進貨單新增</a>
                            </li>

                            <li>
                                <a href="{{url('/purchaseList')}}">進貨單管理</a>
                            </li>                                                       
                          
                        </ul>
                    </li>                    
                    <!-- /進貨管理 -->

                    <!-- 報表模組 -->
                    
                    <li class="@if($controller == 'ReportController' ) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">trending_up</i>
                            <span>報表模組</span>
                        </a>
                        <ul class="ml-menu">
                            
                            <li>
                               <a href="{{url('/reportOrder')}}">銷售報表</a>
                            </li>

                            <li>
                                <a href="{{url('/reportPurchase')}}">進貨報表</a>
                            </li> 

                            <li>
                                <a href="{{url('/reportGoodsSale')}}">未銷售報表</a>
                            </li>                           
                        </ul>
                    </li>
                                         
                    <!-- /報表模組-->

                    <!-- 經銷商管理 -->
                    <li class="@if($controller == 'DealerController' ) active @endif">

                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">device_hub</i>
                            <span>經銷商網站管理模組</span>
                        </a>

                        <ul class="ml-menu">

                            @role('Admin')
                            <li>
                               <a href="{{url('/dealer')}}">經銷商網站設定</a>
                            </li>         
                            @endrole

                            @role('Dealer')
                            <li>
                               <a href="{{url('/dealerEdit')}}/{{Auth::id()}}">經銷商網站設定</a>
                            </li>                            
                            @endrole
                          
                        </ul>
                    </li>                    
                    <!-- /經銷商管理 -->

                    <!-- 權限管理 -->
                    @role('Admin')
                    <li  class="@if($controller == 'PermissionsController'||$controller == 'AccountController' || $controller == 'RoleController') active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_identity</i>
                            <span>權限管理模組</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{url('/permissions')}}">權限管理</a>
                            </li>

                            <li>
                                <a href="{{url('/role')}}">身分管理</a>
                            </li>

                            <li>
                                <a href="{{url('/account')}}">帳號管理</a>
                            </li>
                        </ul>
                    </li> 
                    @endrole                 
                    <!-- /權限管理 -->

                    <!-- 網站設置 -->
                    @role('Admin')
                    <li class="@if($controller == 'SetController' ) active @endif">
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings_applications</i>
                            <span>網站設置模組</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="{{url('/set')}}">網站資料設定</a>
                            </li>
                            <li>
                                <a href="{{url('/setFee')}}">運費設置</a>
                            </li>                            
                            <li>
                                <a href="{{url('/articleList')}}">文章管理</a>
                            </li>
                            <li>
                                <a href="{{url('/announcementList')}}">公告管理</a>
                            </li>                            
                        </ul>
                    </li>
                    @endrole              
                    <!-- /網站設置 -->
                    
                </ul>
            </div>
