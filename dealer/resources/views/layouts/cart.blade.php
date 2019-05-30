<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <title> 購物車 </title>
    <!-- Favicon-->
    <link rel="icon" href="{{asset('/adminbsb-materialdesign/favicon.ico')}}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <!-- Bootstrap Core Css -->
    <link href="{{asset('/adminbsb-materialdesign/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{asset('/adminbsb-materialdesign/plugins/node-waves/waves.css')}}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{asset('/adminbsb-materialdesign/plugins/animate-css/animate.css')}}" rel="stylesheet" />

    <!-- Custom Css -->
    <!-- <link href="{{asset('/adminbsb-materialdesign/css/style.css')}}" rel="stylesheet"> -->

    <link href="{{asset('/adminbsb-materialdesign/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">

    <link href="{{asset('/adminbsb-materialdesign/css/cart.css')}}" rel="stylesheet">
    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{asset('/adminbsb-materialdesign/css/themes/all-themes.css')}}" rel="stylesheet" />

    <!--<link href="{{asset('adminbsb-materialdesign/plugins/sweetalert/sweetalert.css')}}" rel="stylesheet" />-->

    <link href="{{asset('/sweetalert2/dist/sweetalert2.css')}}" rel="stylesheet" />
    <!-- Jquery Core Js -->
    <script src="{{asset('adminbsb-materialdesign/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap Core Js -->
    <script src="{{asset('adminbsb-materialdesign/plugins/bootstrap/js/bootstrap.js')}}"></script>

    <!-- Select Plugin Js -->
    <script src="{{asset('adminbsb-materialdesign/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{asset('adminbsb-materialdesign/plugins/jquery-slimscroll/jquery.slimscroll.js')}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset('adminbsb-materialdesign/plugins/node-waves/waves.js')}}"></script>

    <!-- Custom Js -->
    <script src="{{asset('adminbsb-materialdesign/js/admin.js')}}"></script>
    
    <script src="{{asset('adminbsb-materialdesign/js/demo.js')}}"></script>

    <script src="{{asset('sweetalert2/dist/sweetalert2.js')}}"></script>
</head>

<style type="text/css">
/*#logoBox{
    position: fixed;
    top:0px;
    left:0px;
    width: 100%;
    z-index: 99;
    height: 100px;

background: {{$dealerDatas['logo_color1']}}; 
background: -moz-linear-gradient(45deg, {{$dealerDatas['logo_color1']}} 0%, {{$dealerDatas['logo_color2']}} 100%); /
background: -webkit-linear-gradient(45deg, {{$dealerDatas['logo_color1']}} 0%,{{$dealerDatas['logo_color2']}} 100%); 
background: linear-gradient(45deg, {{$dealerDatas['logo_color1']}} 0%,{{$dealerDatas['logo_color2']}} 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{{$dealerDatas['logo_color1']}}', endColorstr='{{$dealerDatas['logo_color2']}}',GradientType=1 );
}*/
</style>

<body class="theme-red">
    
    <div id="fixBox">
    <div id='logoBox' class="container-fluid _np">
        <div id="webLogo" class='col-md-12 _w _np'>
            @if( !empty($dealerDatas['logo1']) && $dealerDatas['banner_type_w'] == 0 )
                <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo1']}}" width='100%'> 
            @elseif( empty($dealerDatas['logo1']) && $dealerDatas['banner_type_w'] == 0 )
                <img src="{{url('banner')}}/web/default1.jpg" width='100%'>
            @else
                <img src="{{url('banner')}}/web/default{{$dealerDatas['banner_type_w']}}.jpg" width='100%'>
            @endif
            <!-- <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo1']}}"> -->
            
        </div>
        <div id="webLogo" class='col-md-12 _p _np'>
            <!-- <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo2']}}"> -->
            @if( !empty($dealerDatas['logo2']) && $dealerDatas['banner_type_m'] == 0 )
                <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo2']}}" width='100%'> 
            @elseif( empty($dealerDatas['logo2']) && $dealerDatas['banner_type_m'] == 0 )
                <img src="{{url('banner')}}/mobile/default1.jpg" width='100%'>
            @else
                <img src="{{url('banner')}}/mobile/default{{$dealerDatas['banner_type_m']}}.jpg" width='100%'>
            @endif            

        </div>        
    </div>

    <nav id='cartNav' class="navbar navbar-default ">
      <div class="container-fluid">
    
        <div class="navbar-header ">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          
          <!--  手機搜尋 -->
<!--           <div id='phonebox' class='_p' >
            <form class="navbar-form navbar-right" action="{{url('')}}/{{$dealerDetect}}/cartSearch/1/" method="get" style="margin:0px;padding:0px;">
                {{ csrf_field() }}
              <div class="input-group">
                 <input type="text" class="form-control" placeholder="搜尋商品" name='keyword'>
                 <span class="input-group-btn">
                      <button class="btn btn-primary" type="submit">搜尋</button>
                 </span>
              </div>
            </form>
          </div> -->
          <!--  /手機搜尋-->

          <div id='cartBox' class="dropdown">
            
            <span id="cartLabel" type="button"  aria-haspopup="true" aria-expanded="false">
              <!-- data-toggle="dropdown" -->
              <span class="glyphicon glyphicon-shopping-cart"></span>購物車
              @if(Session::has('carts'))
                  @php
                      $cartsItems = Session::get('carts');
                      $totalNum = 0;
                      foreach( $cartsItems as $cartsItem ){
                          $totalNum += $cartsItem['num'];
                      }
                  @endphp

                  @if( $totalNum > 0)
                      <span class='carNum' style='display:inline-block;'>{{$totalNum}} </span>
                  @else
                      <span class='carNum' style='display:none;'>0</span>
                  @endif
              @else
                  <span class='carNum' style='display:none;'>0</span>
              @endif
              
              <!-- <span class="caret"></span> -->
            </span>

            <ul class="dropdown-menu dropdown-menu-right cartList" aria-labelledby="dLabel">

                <a href="{{url('')}}/{{$dealerDetect}}/checkout" class="btn btn-primary">去結帳</a>
                
                <div id='cartItem'>

                @if(Session::has('carts'))
                
                @php
                    $cartsItems = Session::get('carts');
                    $cartAmount = 0;
                    if( count($cartsItems) <= 0){

                        $cartAmount = '目前無商品';
                    }
                @endphp
                    @foreach($cartsItems as $cartsItem)

                        <div class='media' style='border-bottom:1px solid #333;padding-bottom:4px;'><div class='media-left'>
                        <img src="{{url('images')}}/{{$cartsItem['thumbnail']}}" style='width:60px;height:60px;'>
                        </div><div class='media-body' >
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                        <h5>{{$cartsItem['name']}}</h5>
                        <h6>{{$cartsItem['goodsPrice']}}*{{$cartsItem['num']}}={{$cartsItem['subTotal']}}</h6>
                        </div>
                        <div class='col-md-12 col-sm-12 col-xs-12'>
                        <button class='btn btn-danger cartDelete' style='width:100%' goodsID="{{$cartsItem['id']}}">移除</button>
                        </div>
                        </div></div>    

                        @php
                            $cartAmount += $cartsItem['subTotal'];
                        @endphp              
                    @endforeach
                    <h4>
                        @if( $cartAmount == '目前無商品')
                        {{$cartAmount}}
                        @else
                        總金額:{{$cartAmount}}
                        @endif
                    </h4>
                @else
                    <h4>目前無商品</h4>
                @endif


                </div>

            </ul>
          </div>

        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<!--           <ul class="nav navbar-nav">
            <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
            <li><a href="#">Link</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Separated link</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul> -->


          <ul id='navItem' class="nav navbar-nav navbar-left">
            <li><a href="{{url('')}}/{{$dealerDetect}}">首頁</a></li>

            @foreach( $categorys as $category)

                <li class='_p _ph'><a href="{{url('')}}/{{$dealerDetect}}/cartCategory/{{$category['id']}}/1">{{$category['name']}}</a></li>

          
            @endforeach

            <li class="dropdown _w _ps">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">分類<span class="caret"></span></a>
              
              
              <ul class="dropdown-menu">
                @foreach( $categorys as $category)
                <li><a href="{{url('')}}/{{$dealerDetect}}/cartCategory/{{$category['id']}}/1">{{$category['name']}}</a></li>
                @endforeach
              </ul>              
              


            </li> 

            <li class='_w'><a href="{{url('')}}/{{$dealerDetect}}/article/1">購物流程</a></li>

            <li class='_w'><a href="{{url('')}}/{{$dealerDetect}}/article/2">常見Q&A</a></li>
          </ul>
          




<!--           <form class="navbar-form navbar-right _w" action="{{url('')}}/{{$dealerDetect}}/cartSearch/1/" method="get">
            {{ csrf_field() }}
            <div class="form-group">
              <input type="text" class="form-control" placeholder="搜尋商品" name='keyword'>
            </div>
            <button type="submit" class="btn btn-primary">搜尋</button>
          </form> -->





        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    </div>


    @yield('content')

    <!-- footer -->
    <div id='footer' class="container-fluid">

<!--         <div id='footerLogo' class='col-md-3 col-md-offset-2 col-sm-12 col-xs-12'>
            <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo1']}}">
        </div> -->

        <div id='dealerInfo' class='col-md-4 col-md-offset-4 col-sm-12 col-xs-12 text-center'>
            <h3>{{$dealerDatas['hotel_name']}}</h3>
            <ul>
<!--                 @if( !empty($dealerDatas['hotel_address']) )
                <li><p><span class="glyphicon glyphicon-map-marker"></span> {{$dealerDatas['hotel_address']}}</p></li>
                @endif -->

                @if( !empty($dealerDatas['hotel_tel']) )
                <li><p><span class="glyphicon glyphicon-earphone"></span> {{$dealerDatas['hotel_tel']}}</p></li>
                @endif

<!--                 @if( !empty($dealerDatas['hotel_email']) )
                <li><p><span class="glyphicon glyphicon-envelope"></span> {{$dealerDatas['hotel_email']}}</p></li>
                @endif

                @if( !empty($dealerDatas['web_url']) )
                <li><p><span class="glyphicon glyphicon-globe"></span> {{$dealerDatas['web_url']}}</p></li>
                @endif  -->                               
            </ul>

        </div>
    </div>
    <!-- /footer -->
     
    @if( isset($isviewGoods) && $isviewGoods == true)
    <div id='phoneBar' class="container-fluid _p">
        <a href="{{url('')}}/{{$dealerDetect}}">
        <div style='padding-left:5px; padding-right:5px;width:20%;float:left;text-align:center;'>
            <i class="glyphicon glyphicon-home"></i><br>
            首頁
        </div>
        </a>
        <a href="{{url('')}}/{{$dealerDetect}}/article/1">
        <div style='padding-left:5px; padding-right:5px;width:20%;float:left;text-align:center;'>
            <i class="glyphicon glyphicon-info-sign"></i><br>
            購物流程
        </div>
        </a>   
        <a href="{{url('')}}/{{$dealerDetect}}/article/2">
        <div style='padding-left:5px; padding-right:5px;width:20%;float:left;text-align:center;'>
            <i class="glyphicon glyphicon-question-sign"></i><br>
            Q&A
        </div>
        </a>    
        <a > 
        <div style='padding-left:5px; padding-right:5px;width:20%;float:left;text-align:center;' class='addone' goodsid="{{$goods['id']}}">
            <i class="glyphicon glyphicon-plus"></i><br>
            購買
        </div>
        </a>
        <a href="{{url('')}}/{{$dealerDetect}}/checkout">
        <div style='padding-left:5px; padding-right:5px;width:20%;float:left;text-align:center;'>
            <i class="glyphicon glyphicon-shopping-cart"></i><br>
            結帳
        </div>
        </a>        
    </div>
    @else
    <div id='phoneBar' class="container-fluid _p">
        <a href="{{url('')}}/{{$dealerDetect}}">
        <div class="col-xs-3 col-sm-3 text-center" style="padding-right:5px;padding-left:5px;">
            <i class="glyphicon glyphicon-home"></i><br>
            首頁
        </div>
        </a>
        <a href="{{url('')}}/{{$dealerDetect}}/article/1">
        <div class="col-xs-3 col-sm-3 text-center" style="padding-right:5px;padding-left:5px;">
            <i class="glyphicon glyphicon-info-sign"></i><br>
            購物流程
        </div>
        </a>   
        <a href="{{url('')}}/{{$dealerDetect}}/article/2">
        <div class="col-xs-3 col-sm-3 text-center" style="padding-right:5px;padding-left:5px;">
            <i class="glyphicon glyphicon-question-sign"></i><br>
            Q&A
        </div>
        </a>              
        <a href="{{url('')}}/{{$dealerDetect}}/checkout">
        <div class="col-xs-3 col-sm-3 text-center" style="padding-right:5px;padding-left:5px;">
            <i class="glyphicon glyphicon-shopping-cart"></i><br>
            結帳
        </div>
        </a>        
    </div>    
    @endif



    @if(session()->has('successMsg'))
    <script type="text/javascript">
        $(function(){
        swal.fire({
            title: "執行成功",
            html: "{!!session('successMsg')!!}",
            type:'success'
        });
        });
    </script>
    @endif

    @if(session()->has('errorMsg'))
    <script type="text/javascript">
        $(function(){
        swal.fire({
            title: "執行失誤",
            html: "{!!session('errorMsg')!!}",
            type:'error'
        });
        });
    </script>
    @endif    
    
    @if ($errors->any())
    <script type="text/javascript">
        var errorHtml = '';

        @foreach( $errors->all() as $error)
            errorHtml += "{{$error}}"+"<br/>";
        @endforeach
        $(function(){
            swal.fire({
                title: "執行失誤",
                html: errorHtml,
                type:'error'
            });    
        });    
    </script>

    @endif

    <!-- 移除購物車項目 -->
    <script type="text/javascript">
        
        $(function(){
            
            $('#cartLabel').on('click', function (event) {
                $(this).parent().toggleClass('open');
            });
            
            $('body').on('click', function (e) {

                if (!$('.cartList').is(e.target) 
                    && $('.cartList').has(e.target).length === 0 
                    && $('.open').has(e.target).length === 0
                ) {
                    $('.cartList').removeClass('open');
                }
            });   
                     
            $(document).on( 'click', '.cartDelete', function(){
                 
                var request = $.ajax({
                    url: "{{url('/')}}"+"/{{$dealerDetect}}"+"/deleteItem",
                    method: "POST",
                    data: { goodsId : $(this).attr('goodsID'),
                            _token:"{{ csrf_token() }}"
                    },
                    dataType: "json"
                });
 
                request.done(function( data ) {
                    
                    if( data['res'] == true){
                        
                        refreshItem( data['cartDatas'] );
                        cusMsg( data['res'] , data['msg'] );
                    }
                });
                 
                request.fail(function( jqXHR, textStatus ) {
                  //alert( "Request failed: " + textStatus );
                });                

            });
            


            /*----------------------------------------------------------------
             | 加入單筆商品
             |----------------------------------------------------------------
             |
             */

            $(document).on( 'click', '.addone', function(){
                 
                var request = $.ajax({
                    url: "{{url('/')}}"+"/{{$dealerDetect}}"+"/addToCart",
                    method: "POST",
                    data: { goodsId : $(this).attr('goodsId'),
                            goodsNum : 1,
                            _token:"{{ csrf_token() }}"
                    },
                    dataType: "json"
                });
 
                request.done(function( data ) {
                    
                    if( data['res'] == true){
                        
                        refreshItem( data['cartDatas'] );
                        cusMsg( data['res'] , data['msg'] );
                    }else{
                        cusMsg( data['res'] , data['msg'] );
                    }
                });
                 
                request.fail(function( jqXHR, textStatus ) {
                    //alert( "Request failed: " + textStatus );
                });                

            });             
            
        });

/*----------------------------------------------------------------
 | 加入購物車回饋訊息
 |----------------------------------------------------------------
 |
 */
function cusMsg( _res , _msg ){

    if( _res == true){

        var msgTile = '執行成功';
        var msgType ='success';

    }else{

        var msgTile = '執行失敗';
        var msgType ='error';
    }

    swal.fire({
        
        title: msgTile,
        html: _msg,
        type:msgType
    
    });
}




/*----------------------------------------------------------------
 | 刷新購物車清單
 |----------------------------------------------------------------
 |
 */
function refreshItem( _datas ){
    //alert('SS');
    // 先將購物車清單清空
    $("#cartItem").empty();
    
    htmlCode = '';


    if( _datas.length == 0 ){
        
        htmlCode = '<h4>目前無商品</h4>';
    }

    var amount = 0;
    var cartGoodsNum = 0;
    $.each( _datas , function( index , element ) {
        
        htmlCode += "<div class='media' style='border-bottom:1px solid #333;padding-bottom:4px;'><div class='media-left'>";
        htmlCode += "<img src='{{url('images')}}"+"/"+element['thumbnail']+"' style='width:60px;height:60px;'>";
        htmlCode += "</div><div class='media-body' >";
        htmlCode += "<div class='col-md-12 col-sm-12 col-xs-12'>"
        htmlCode += "<h5>"+element['name']+"</h5>";
        htmlCode += "<h6>"+element['goodsPrice']+'*'+element['num']+'='+element['subTotal']+"</h6>";
        htmlCode += "</div>";
        htmlCode += "<div class='col-md-12 col-sm-12 col-xs-12'>"
        htmlCode += "<button class='btn btn-danger cartDelete' style='width:100%' goodsID='"+element['id']+"'>移除</button>";
        htmlCode += "</div>"
        htmlCode += "</div></div>";

        amount += element['subTotal'];
        cartGoodsNum += parseInt(element['num']);

    });
    
    
    if( Object.keys(_datas).length > 0 ){

        htmlCode += "<h4>總金額:"+amount+"</h4>";
    }


    if( cartGoodsNum > 0 ){
        $(".carNum").html('');
        $(".carNum").html( cartGoodsNum );
        $(".carNum").show();
    }else{
        $(".carNum").html('');
        $(".carNum").html( '0' );
        $(".carNum").hide();
    }

    $("#cartItem").append(htmlCode);
}

    </script>
    <!-- /移除購物車項目 -->
</body>

</html>
