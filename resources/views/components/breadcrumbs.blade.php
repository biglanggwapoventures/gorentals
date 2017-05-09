<header class="bread_crumb">
    {!! isset($banner) ? $banner : '' !!}
    <!--<div class="pag_titl_sec">
        <h1 class="pag_titl"> Property Details </h1>
        <h4 class="sub_titl"> Echo Park occupy mustache gastropub </h4>
    </div>-->
    <div class="pg_links">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="lnk_pag"><a href="{{ url('/') }}"> Home </a> </p>
                    <p class="lnk_pag"> / </p>
                    {{ $slot }}
                    
                </div>
                <div class="col-md-6 text-right">
                    <p class="lnk_pag"><a href="{{ url('/') }}"> Go Back to Home </a> </p>
                </div>
            </div>
        </div>
    </div>
</header>