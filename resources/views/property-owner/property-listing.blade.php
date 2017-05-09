@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        <p class="lnk_pag"><a> my properties </a> </p>
    @endcomponent
    <div class="spacer-60"></div>
    <div class="container">
        <div class="row">
            <!-- Properties Section -->
            <section id="feat_propty">
                <div class="container">
                    @forelse($properties->chunk(3) AS $chunk)
                        <div class="row" style="margin-top:20px;">
                            @foreach($chunk AS $property)
                                 <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-image tile" style="background-image:url(@if(isset($property->photos['primary'])){{ asset("storage/{$property->photos['primary'][0]}") }} @endif);background-size:cover;background-position:center;background-repeat:no-repeat;">
                                            <!-- <img class="img-responsive img-hover" src="@if(isset($property->photos['primary'])){{ asset("storage/{$property->photos['primary'][0]}") }} @endif" alt=""> -->
                                            <div class="img_hov_eff">
                                                <a href="{{ url("properties/{$property->id}/units") }}" class="btn btn-default btn_trans"> View units</a>
                                            </div>
                                        </div>
                                       <!--  <div class="sal_labl">
                                            For Sale
                                        </div> -->

                                        <div class="panel-body">
                                            <div class="prop_feat">
                                                <ul class="list-unstyled">
                                                    <li><i class="fa fa-home"></i> @if($property->extension) {{ $property->extension }} - @endif {{ $property->address }}</li>
                                                    <li><i class="fa fa-cog"></i> {{ $property->getTypeDescription() }}</li>
                                                </ul>
                                            </div>
                                            <h3 class="sec_titl">{{ $property->building_name }}</h3>
                                            <div class="panel_bottom">
                                                <div class="col-md-6">
                                                    <a href="/properties/{{$property->id}}" class="btn btn-primary"> Edit</a>
                                                    <a href="/deleteproperty?property={{$property->id}}" class="btn btn-primary"> Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty

                    @endforelse 
                </div>
                <!-- /.container -->
            </section>
            <div class="spacer-60"></div>

        </div>
    </div>
@endsection
