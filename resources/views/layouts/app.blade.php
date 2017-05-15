<!DOCTYPE html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> {{ config('app.name', 'Go Rentals') }}</title>

    <!-- Bootstrap Core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

    <!-- Owl Carousel Assets -->
    <link href="{{ asset('css/owl.carousel.css') }}" rel="stylesheet">
    <link href="{{ asset('css/owl.theme.css') }}" rel="stylesheet">
    <link href="{{ asset('css/owl.transitions.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/main_style.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>

    <link href="{{ asset('css/jquery.bxslider.css') }}" rel="stylesheet">
    <link href="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css">
    <style>
        .unit-map .gm-style-iw + div {display: none;}
    </style>

    @stack('styles')
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
    
</body>

<!-- Top Bar -->
<section class="top_sec">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-6 top_lft">
                <div class="soc_ico">
                    <ul>
                        <li class="tweet">
                            <a href="#">
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                        <li class="fb">
                            <a href="#">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li class="insta">
                            <a href="#">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>

                    </ul>

                </div>
                <div class="inf_txt">
                    @if(Auth::check())
                        <p>Hello, {{ Auth::user()->fullname() }}!</p>
                    @endif
                </div>
            </div>
            <!-- /.top-left -->
            @if(!Auth::check())
                <div class="col-xs-12 col-md-6 top_rgt">
                    <div class="sig_in">
                        <p>




                        </p>
                    </div>
                    <div class="submit_prop">
                        <h3 class="subm_btn"><a href="#login_box" class="log_btn" data-toggle="modal"> Sign in </a> | <a class="reg_btn" href="#reg_box" data-toggle="modal">  create account </a>
                        </h3>
                    </div>

                </div>
                @endif
                        <!-- /.top-right -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
</section>

<!-- Navigation -->
<nav class="navbar" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}"><img src="{{ asset('images/logo.png') }}" alt="logo">
            </a>
        </div>
        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    @if(Auth::user()->isAdmin())
                        <li>
                            <a href="{{ url('/admin') }}">Pending Approvals</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="" href="{{ url('/admin') }}">Units</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ url('/admin?approved=true') }}">Approved Units</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="" href="{{ url('/admin?approved=true') }}">Units</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#">User List</a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="" href="{{ url('/admin/users?type=owners') }}">Property Owners</a>
                                </li>
                                <li>
                                    <a class="" href="{{ url('/admin/users?type=standard') }}">Standard  Tenants</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a class="" href="#"><i class="fa fa-user"></i>
                                {{ Auth::user()->displayName() }} <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ url('/logout') }}"> Logout </a>
                                </li>
                            </ul>

                        </li>
                    @else
                        <li>
                            <a href="{{ url('/notifications') }}"> 
                                @php
                                    $notificationCount += Auth::user()->getAppointmentsCount();
                                @endphp
                                @if($notificationCount)
                                    <span class="badge">{{ $notificationCount }}</span>
                                @endif
                                Notifications
                            </a>
                        </li>
                        @if(Auth::user()->login_type === 'PROPERTY_OWNER')
                            <li>
                                <a href="#">Property</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="" href="{{ url('/properties') }}">My Properties</a>
                                    </li>
                                    <li>
                                        <a class="" href="{{ url('/new-property') }}">Add Property</a>
                                    </li>
                                </ul>
                            </li>
                        @elseif(Auth::user()->login_type === 'USER')
                            <li>
                                <a class="" href="{{ url('/?fav=true')}}">My Favorites</a>
                            </li>
                        @endif
                        <li>
                            <a class="" href="#"><i class="fa fa-user"></i>
                                
                                {{ Auth::user()->displayName() }} <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="/profile"> Profile </a>
                                </li>
                                <li>
                                    <a href="{{ url('/logout') }}"> Logout </a>
                                </li>
                            </ul>

                        </li>
                    @endif
                @endif
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>



@section('content')
@show

        <!-- Footer -->
@include('includes.footer')

        <!-- Modal HTML -->
@include('includes.login-modal')
@include('includes.register-modal')
        <!-- jQuery -->
<script src="{{ asset('js/jquery.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
<script src="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.js"></script>
<script src="https://rawgit.com/yaronyam/bootstrap-table/feature/print/src/extensions/print/bootstrap-table-print.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>

<!-- Script to Activate the Carousel -->
<script type="text/javascript">
    $(function(){

    //     $('.radio-inline').each(function() {
    //     var text = $(this).text();
    //     $(this).text(text.replace('user', 'tenant')); 
    // });

    //     $('.nav li:nth-child(3)').each(function() {
    //     var text = $(this).text();
    //     $(this).text(text.replace('users list', 'tenants')); 
    // });

        var def_prof_img = "/images/preview_default_profile.png";
        var def_sldr_img = "/images/default-slider-thumbnail.jpg";
        var def_prp_img = "/images/default-property-img.jpg";

        // $('#profile_pic').find('img').each(function(){
        //     $(this).attr('src', def_prof_img);
        // });
        // $('#sidebar').find('.agen_info img').each(function(){
        //     $(this).attr('src', def_prof_img);
        // });
        // $('#message-box').find('.media-left a img').each(function(){
        //     $(this).attr('src', def_prof_img);
        // });

        // $('#prop_slid').find('li img').each(function(){
        //     if($(this).is(':hidden')){
        //         $(this).attr('src', def_sldr_img).css("width", "100%");
        //     }
        // });
        // $('#feat_propty').find(' .panel-image img').each(function(){
        //     if($(this).is(':hidden')){
        //         $(this).attr('src', def_prp_img).css("width", "100%");
        //     }
        // });
    });

    $("#PrintButton").click(function(){
        PrintPageContent($("#PageContent").html());
    });

    $(document).ready(function () {
        'use strict';
        $('.ajax').submit(function(e){
            e.preventDefault();

            var $this = $(this),
                    submitBtn = $this.find('[type=submit]'),
                    errorBox = $this.find('.bs-callout.bs-callout-danger');

           // submitBtn.addClass('disabled');
            errorBox.addClass('hidden');

            var payload = new FormData($this[0]);

            $.ajax({
                url: $this.attr('action'),
                data: payload,
                method: $this.attr('method'),
                contentType: false,
                processData: false,
                success: function(response){
                    console.log(response);
                    if(response.result){
                        if($this.data('next')){
                            window.location.href = $this.data('next');
                        }else if(response.hasOwnProperty('next')){
                            window.location.href = response.next;
                        }else{
                            window.location.reload();
                        }
                    }else{
                        errorBox.removeClass('hidden')
                                .html('<ul class="list-unstyled"><li>'+response.messages.join('</li><li>')+('</li><li>')+'</li></ul>');
                    }
                },
                complete: function(){
                    submitBtn.removeClass('disabled');
                },
                error: function(xhr, err){
                    // console.log(xhr);return;
                    console.log(xhr);
                    if(xhr.status === 422){
                        errorBox.removeClass('hidden')
                                .html('<ul class="list-unstyled"><li>'+xhr.responseJSON.join('</li><li>')+('</li><li>')+'</li></ul>');
                    }
                },
            })
        })

    });
</script>

@stack('scripts')

</body>

</html>