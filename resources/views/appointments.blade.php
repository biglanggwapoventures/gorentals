@extends('layouts.app')
@section('content')
    <style type="text/css">
        .status p {
    font-size: 0.8em;
    margin: 0;
}
.status {
    /* border: 1px solid; */
    background: #ddd;
    padding: 10px;
}
    </style>
    <section id="feat_propty">
        <div class="container">
            <div class="row">
                <div class="titl_sec">
                    <div class="col-xs-6">
                        <h3 class="main_titl text-left">MY APPOINTMENTS</h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- /.container -->
    </section>
    <section id="feat_propty">
        <div class="container">
            <div class="row">
                @if(Auth::user()->login_type == 'USER')
                <div class="titl_sec appointments">
                    @foreach($appointments as $appointment)
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                @if(!is_null($appointment->unit->user->profile_picture))
                                <img class="media-object" src="{{asset("storage/{$appointment->unit->user->profile_picture}")}}" alt="..." style="width:60px;height:60px">
                                @else
                                <img class="media-object" src="/images/preview_default_profile.png" alt="..." style="width:60px;height:60px">
                                @endif

                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading"><a href="/users/{{$appointment->unit->user->id}}">{{$appointment->unit->user->fullname()}}</a> @if($appointment->status == 'ACCEPT') <small>have approved your appointment</small> @endif @if($appointment->status == 'DECLINE') <small>has decline your appointment</small> @endif</h4>
                            <p>@if($appointment->property->extension) {{$appointment->property->extension}} - @endif  <strong>UNIT NAME: </strong><a target="_blank" href="{{ url("units/{$appointment->unit_id}/view")}}"">{{$appointment->property->building_name}} </a><br>
                            <strong>PROPERTY ADDRESS: </strong>{{$appointment->address}}</p>
                            <p><strong>APPOINTMENT DATE &amp; TIME:</strong> {{ $appointment->date }}</p> 
                        </div>
                    </div>
                    @endforeach
                    <div class="clearfix"></div>
                </div>
                @endif
                @if(Auth::user()->login_type == 'PROPERTY_OWNER')
                <div class="titl_sec appointments">
                    @foreach($appointments as $appointment)
                    <div class="media">
                        <div class="media-left">
                            <a href="#">
                                @if(!is_null($appointment->user->profile_picture))
                                <img class="media-object" src="{{asset("storage/{$appointment->user->profile_picture}")}}" alt="..." style="width:60px;height:60px">
                                @else
                                <img class="media-object" src="/images/preview_default_profile.png" alt="..." style="width:60px;height:60px">
                                @endif
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading"><a href="/users/{{$appointment->user->id}}">{{$appointment->user->fullname()}}</a></h4>
                            <p>@if($appointment->property->extension) {{$appointment->property->extension}} - @endif 
                            <strong>UNIT NAME: </strong><a target="_blank" href="{{ url("units/{$appointment->unit_id}/view")}}"">{{$appointment->property->building_name}} </a><br>
                            <strong>PROPERTY ADDRESS: </strong>{{$appointment->address}}</p>
                            <strong>APPOINTMENT DATE &amp; TIME: </strong>{{ $appointment->date }} 
                            <div class="clearfix"></div>
                            <div class="status">
                                @if($appointment->status == 'ACCEPT')
                                    <p>Status: <span>APPROVED</span></p>
                                @elseif($appointment->status == 'DECLINE')
                                    <p>Status: <span>DECLINED</span></p>
                                @else
                                    <p>Status: <span>PENDING</span></p>
                                @endif
                                @if($appointment->status == 'ACCEPT')
                                    <a href="/appointments/{{$appointment->id}}/1" class="btn btn-default btn-sm">ACCEPT</a>
                                    <a href="/appointments/{{$appointment->id}}/-1" class="btn btn-primary btn-sm">DECLINE</a>
                                @elseif($appointment->status == 'DECLINE')
                                    <a href="/appointments/{{$appointment->id}}/1" class="btn btn-primary btn-sm">ACCEPT</a>
                                    <a href="/appointments/{{$appointment->id}}/-1" class="btn btn-default btn-sm">DECLINE</a>
                                @else
                                     <a href="/appointments/{{$appointment->id}}/1" class="btn btn-primary btn-sm">ACCEPT</a>
                                    <a href="/appointments/{{$appointment->id}}/-1" class="btn btn-primary btn-sm">DECLINE</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div class="clearfix"></div>
                </div>
                @endif
            </div>
        </div>
        <!-- /.container -->
    </section>
@endsection
@push('scripts')
    <!-- GMaps JavaScript -->
<!-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyC1MUjOwwLeP2Jv5Q8o0nt5RH-oSKY5RUw"></script> -->
<script src="http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=false"></script>
    <script src="{{ asset('js/infobox.js') }}"></script>
    <script src="{{ asset('js/markerwithlabel_packed.js') }}"></script>
    <script src="{{ asset('js/custom_map.js') }}"></script>
@endpush