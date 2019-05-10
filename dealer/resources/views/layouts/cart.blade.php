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

<body class="theme-red">
    <div id='logoBox' class="container-fluid">
        <div id="webLogo" class='col-md-4 col-md-offset-4 _w'>
            <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo1']}}">
        </div>
        <div id="webLogo" class='col-md-4 col-md-offset-4 _p'>
            <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo2']}}">
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

          <div id='cartBox' class="dropdown">
            
            <span id="cartLabel" type="button"  aria-haspopup="true" aria-expanded="false">
              <!-- data-toggle="dropdown" -->
              <span class="glyphicon glyphicon-shopping-cart"></span>購物車
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
            <li><a href="#">首頁</a></li>

            @foreach( $categorys as $category)
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$category['name']}} <span class="caret"></span></a>
              @if( !empty($category['child']) )
              
              <ul class="dropdown-menu">
                @foreach( $category['child'] as $subchild)
                <li><a href="#">{{$subchild['name']}}</a></li>
                @endforeach
              </ul>              
              
              @endif

            </li>            
            @endforeach

            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">購買須知 <span class="caret"></span></a>

        
              <ul class="dropdown-menu">

                <li><a href="#">購買流程</a></li>

              </ul>              
            

            </li>

          </ul>
          




          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" class="form-control" placeholder="搜尋商品">
            </div>
            <button type="submit" class="btn btn-primary">搜尋</button>
          </form>





        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>


    @yield('content')

    <!-- footer -->
    <div id='footer' class="container-fluid">

        <div id='footerLogo' class='col-md-3 col-md-offset-2 col-sm-12 col-xs-12'>
            <img src="{{url('logo')}}/{{$cartUser}}/{{$dealerDatas['logo1']}}">
        </div>

        <div id='dealerInfo' class='col-md-4 col-md-offset-1 col-sm-12 col-xs-12'>
            <h3>{{$dealerDatas['hotel_name']}}</h3>
            <ul>
                <li><p><span class="glyphicon glyphicon-map-marker"></span> {{$dealerDatas['hotel_address']}}</p></li>
                <li><p><span class="glyphicon glyphicon-earphone"></span> {{$dealerDatas['hotel_tel']}}</p></li>
            </ul>

        </div>
    </div>
    <!-- /footer -->



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

    });
    
    
    if( Object.keys(_datas).length > 0 ){

        htmlCode += "<h4>總金額:"+amount+"</h4>";
    }

    $("#cartItem").append(htmlCode);
}

    </script>
    <!-- /移除購物車項目 -->
</body>

</html>
