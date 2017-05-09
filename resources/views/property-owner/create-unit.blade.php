@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        @slot('banner')
        <div class="pag_titl_sec">
            <h1 class="pag_titl"> {{ $property->building_name }} </h1>
<h4 class="sub_titl"> @if($property->extension) {{$property->extension}} - @endif {{ $property->address }} </h4>        </div>
        @endslot
        <p class="lnk_pag"><a href="{{ url('/properties') }}"> properties </a> </p>
        <p class="lnk_pag"> / </p>
        <p class="lnk_pag"><a> new unit </a> </p>
    @endcomponent

    <div class="spacer-60"></div>
    <div class="container">
        <div class="row">
            <!-- Proerty Details Section -->
            <section id="prop_detal" class="col-md-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="titl_sec">
                            <h3 class="main_titl text-left">unit details</h3>
                            <span class="this-is-required">* is required</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body" style="padding-top:25px">
                                <form name="sentMessage" id="contactForm" novalidate="" class="ajax" action="{{ url("properties/{$property->id}/units") }}" method="POST" data-next="{{ url("properties/{$property->id}/units") }}">
                                    {{ csrf_field() }}
                                    <div class="bs-callout bs-callout-danger hidden" ></div>
                                    <input type="hidden" name="latitude">
                                    <input type="hidden" name="longitude">
                                    <input type="hidden" name="address">
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label for="">Rental Terms*</label>
                                            <ul class="list-group">
                                                <li class="list-group-item">
                                                    <div class="radio">
                                                        <label><input type="radio" name="rental_terms" value="LONG">For Long Term <i>(if minimum is 6 months rental period)</i></label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item">
                                                    <div class="radio">
                                                        <label><input type="radio" name="rental_terms" value="SHORT">For Short Term <i>(if available for daily, weekly or monthly rent)</i></label>
                                                    </div>
                                                </li>            
                                            </ul>
                                        </div>
                                        
                                        <div class="controls col-md-6">
                                            <div class="form-group">
                                                <label>Minimum Stay (Long Term)</label>
                                                <select class="form-control" name="long_term_minimum" data-disabled="SHORT">
                                                    <option value="" disabled selected></option>
                                                    @foreach($terms['LONG'] AS $val => $label)
                                                        <option value="{{ $val }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                           <div class="form-group">
                                                <label>Minimum Stay (Short Term)</label>
                                                <select class="form-control" name="short_term_minimum" data-disabled="LONG">
                                                    <option value="" disabled selected></option>
                                                    @foreach($terms['SHORT'] AS $val => $label)
                                                        <option value="{{ $val }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Long Term Rate*</label>
                                            <input type="text" class="form-control" name="long_term_rate" data-disabled="SHORT">
                                        </div>
                                        <div class="controls col-md-6">
                                            <div class="form-group">
                                                <label>Short Term Rate (Daily)</label>
                                                <input type="text" class="form-control" name="short_term_daily_rate" data-disabled="LONG">
                                            </div>
                                            <div class="form-group">
                                                <label>Short Term Rate (Weekly)</label>
                                                <input type="text" class="form-control" name="short_term_weekly_rate" data-disabled="LONG">
                                            </div>
                                            <div class="form-group">
                                                <label>Short Term Rate (Monthly)</label>
                                                <input type="text" class="form-control" name="short_term_monthly_rate" data-disabled="LONG">
                                            </div>
                                            
                                            
                                            
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                     <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Unit Number*</label>
                                           <input type="text" class="form-control" name="unit_number">
                                        </div>
                                        <div class="controls col-md-6">
                                            <label>Unit Floor*</label>
                                           <input type="text" class="form-control" name="unit_floor">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="control-group form-group">
                                        <div class="controls col-md-6 first">
                                            <label>Furnishing*</label>
                                            <select class="form-control" name="furnishing">
                                                <option value="" disabled selected></option>
                                                @foreach($furnishing AS $val => $label)
                                                    <option value="{{ $val }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="controls col-md-6">
                                            <div class="form-group">
                                                <label>@if(strtolower($property->building_name) == 'bed spacer') Capacity @else Bedrooms @endif *</label>
                                                <select class="form-control" name="bedrooms">
                                                    <option value="" disabled selected></option>
                                                    @foreach(range(1,5) AS $num)
                                                        <option value="{{ $num }}">{{ $num }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Bathrooms*</label>
                                                <select class="form-control" name="bathrooms">
                                                    <option value="" disabled selected></option>
                                                    @foreach(range(1,5) AS $num)
                                                        <option value="{{ $num }}">{{ $num }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <hr>
                                    <div class="row" style="margin-top:20px">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Features and Amenities*</label>
                                                <ul class="list-group">
                                                    @foreach($amenities AS $a)
                                                        <li class="list-group-item" style="padding: 2px 10px;">
                                                            <div class="checkbox">
                                                                <label><input type="checkbox" name="amenities[]" value="{{ $a }}">{{ $a }}</label>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="">Inclusions</label>
                                                <textarea name="inclusions" id="" rows="5"  class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="">Photo*</label>
                                               <input type="file" name="photos">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- For success/fail messages -->
                                    <button type="submit" class="btn btn-primary">Post</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="spacer-30"></div>

            </section>

            <!-- Sidebar Section -->       

        </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('[name=rental_terms]').change(function(){
                
                if($(this).val() === 'SHORT'){
                    $('[data-disabled=LONG]').removeAttr('disabled')
                    $('[data-disabled=SHORT]').attr('disabled', 'disabled')
                    
                }else{
                    $('[data-disabled=SHORT]').removeAttr('disabled')
                    $('[data-disabled=LONG]').attr('disabled', 'disabled')
                }
            })
        })
    </script>
@endpush