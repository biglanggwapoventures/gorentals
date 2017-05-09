@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        <p class="lnk_pag"><a> pending units</a> </p>
    @endcomponent

    <div class="spacer-30"></div>
    <div class="container">
      <div class="row">
          <div class="col-sm-12">
            <section id="feat_propty">
                <div class="container">
                        <div class="row">
                            <div class="titl_sec">
                                <div class="col-xs-6">

                                    <h3 class="main_titl text-left">
                                {{$label_unit}}
                            </h3>

                                </div>
                                <div class="clearfix"></div>
                            </div>

                            @forelse($units->chunk(3) AS $row)
                                <div class="row">
                                @foreach($row AS $unit)
                                    <div class="col-md-4">
                                        <div class="panel panel-default unit-item" data-lat="{{ $unit->property->latitude }}" data-lng="{{ $unit->property->longitude }}" data-address="{{ $unit->property->address }}">
                                            <div class="panel-image tile" style="background-image:url(@if(isset($unit->property->photos['primary'])){{ asset("storage/{$unit->property->photos['primary'][0]}") }}@endif);background-size:cover;background-position:center;background-repeat:no-repeat;">
                                                <!-- <img class="img-responsive img-hover" src="@if(isset($unit->property->photos['primary'])){{ asset("storage/{$unit->property->photos['primary'][0]}") }}@endif" alt=""> -->
                                                <div class="img_hov_eff">
                                                    <a class="btn btn-default btn_trans" href="{{ url("units/{$unit->id}/view")}}"> More Details </a>
                                                </div>

                                            </div>

                                            <div class="panel-body">
                                                <div class="prop_feat">
                                                    <p class="area"><i class="fa fa-home"></i> {{  $unit->property->getTypeDescription() }}</p>
                                                    <p class="bedrom"><i class="fa fa-bed"></i> {{ $unit->bedrooms }} Bed(s)</p>
                                                    <p class="bedrom"><i class="fa fa-bath"></i> {{ $unit->bathrooms }} Bath(s)</p>
                                                </div>
                                                <h3 class="sec_titl">{{ $unit->property->building_name }}</h3>

                                                <p class="sec_desc">
                                                    {{ $unit->property->address }}
                                                    <table class="table table-condensed">
                                                        <tr>
                                                            <td>Rental Term</td>
                                                            <td class="text-right"><strong>{{ $unit->rental_terms === 'LONG' ? 'Long Term' :  'Short Term' }}</strong></td>
                                                            @if($unit->rental_terms === 'LONG')
                                                                <tr>
                                                                    <td>Rate</td>
                                                                    <td class="text-right"><strong>Php {{ number_format($unit->long_term_rate, 2) }}</strong></td>
                                                                </tr>
                                                            @else

                                                                <tr>
                                                                    <td>Daily Rate</td>
                                                                    <td class="text-right"><strong>Php {{ number_format($unit->short_term_daily_rate, 2) }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Weekly Rate</td>
                                                                    <td class="text-right"><strong>Php {{ number_format($unit->short_term_weekly_rate, 2) }}</strong></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Monthly Rate</td>
                                                                    <td class="text-right"><strong>Php {{ number_format($unit->short_term_monthly_rate, 2) }}</strong></td>
                                                                </tr>
                                                            @endif
                                                        </tr>
                                                    </table>
                                                </p>
                                            </div>
                                        </div>


                                    </div>
                                @endforeach
                                </div>

                            @empty
                                <div class="col-md-12">
                                <div class="bs-callout bs-callout-danger" >
                                    No units to approve
                                </div>
                                </div>
                            @endforelse

                        </div>
                        <!-- /.row -->
                </div>
                     <!-- /.container -->
            </section>
          </div>
      </div>
            
    </div>
        <div class="spacer-60"></div>
@endsection