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
            
            <span id="cartLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="glyphicon glyphicon-shopping-cart"></span>購物車
              <!-- <span class="caret"></span> -->
            </span>

            <ul class="dropdown-menu dropdown-menu-right cartList" aria-labelledby="dLabel">
                <button type="submit" class="btn btn-primary">去結帳</button>
                
                <div id='cartItem'>
                <h4>目前無商品</h4>
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
</body>

</html>
