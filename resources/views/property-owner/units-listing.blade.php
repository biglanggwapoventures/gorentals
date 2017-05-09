@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        @slot('banner')
        <div class="pag_titl_sec">
            <h1 class="pag_titl"> {{ $property->building_name }} </h1>
            <h4 class="sub_titl"> @if($property->extension) {{$property->extension}} - @endif {{ $property->address }} </h4>
        </div>
        @endslot
        <p class="lnk_pag"><a href="{{ url('/properties') }}"> properties </a> </p>
        <p class="lnk_pag"> / </p>
        <p class="lnk_pag"><a> units </a> </p>
    @endcomponent
    <div class="spacer-60"></div>
    <div class="container">
        <div class="row">
            <!-- Proerty Details Section -->
            <section id="feat_propty">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                              <a href="{{ url("properties/{$property->id}/units/create") }}" class="btn btn-primary">Create new unit</a>
                        </div>
                    </div>
                    <div class="spacer-20"></div>
                    @forelse($units->chunk(3) AS $chunk)
                        <div class="row" style="margin-top:20px;">
                            @foreach($chunk AS $unit)
                                 <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-image img" style="background-image:url({{ asset("storage/{$unit->photos}") }});background-position:center;background-size:cover;background-repeat:no-repeat;height: 190px;">
                                            <!-- <img class="img-responsive img-hover" src="{{ asset("storage/{$unit->photos}") }}" alt=""> -->
                                            <div class="img_hov_eff">
                                                <a href="/properties/{{$property->id}}/units/{{$unit->id}}" class="btn btn-default btn_trans"> Edit</a>
                                            </div>
                                        </div>
                                        <div class="panel-body" style="padding-bottom:20px">
                                            <div class="prop_feat">
                                                <table class="table table-condensed">
                                                    <tbody>
                                                        @if($unit->rental_terms === 'LONG')
                                                            <tr><td colspan="2" class="warning text-center">Long Term Rate</td></tr>
                                                        <tr><td>Monthly</td> <td colspan="2" class="text-right"><strong>Php {{ number_format($unit->long_term_rate, 2) }}</strong></td></tr>
                                                        @else
                                                            <tr><td colspan="2" class="warning text-center">Short Term Rate</td> </tr>
                                                        <tr><td>Daily</td> <td colspan="2" class=" text-right"><strong>Php {{ number_format($unit->short_term_daily_rate, 2) }}</strong></td></tr>
                                                        <tr><td>Weekly</td> <td colspan="2" class="text-right"><strong>Php {{ number_format($unit->short_term_weekly_rate, 2) }}</strong></td></tr>
                                                        <tr><td>Monthly</td> <td colspan="2" class="text-right"><strong>Php {{ number_format($unit->short_term_monthly_rate, 2) }}</strong></td></tr>
                                                        @endif
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                            Status:
                                            @if($unit->isAvailable())
                                                <a href="?unit={{$unit->id}}&status=1" class="btn btn-primary btn-xs">Vacant</a>
                                                <a href="?unit={{$unit->id}}&status=0" class="btn btn-default btn-xs">Occupied</a>
                                            @else
                                                <a href="?unit={{$unit->id}}&status=1" class="btn btn-default btn-xs">Vacant</a>
                                                <a href="?unit={{$unit->id}}&status=0" class="btn btn-primary btn-xs">Occupied</a>
                                            @endif 
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="spacer-40"></div>
                    @empty
                     <div class="row">
                         <div class="col-sm-12">
                             <div class="bs-callout bs-callout-warning text-ceter">No units have been added for this property</div>
                         </div>
                     </div>
                    @endforelse

                </div>
                <!-- /.container -->
            </section>
            <div class="spacer-60"></div>

        </div>
    </div>
@endsection
