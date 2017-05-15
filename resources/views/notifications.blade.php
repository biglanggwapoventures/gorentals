@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        <p class="lnk_pag"><a> notifications</a> </p>
    @endcomponent

    <div class="spacer-30"></div>
    <div class="container">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills" role="tablist">
                <li role="presentation" class=" {{ empty(request()->input('tab')) ? 'active' : '' }}">
                    <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">
                        <i class="fa fa-envelope fa-fw"></i> Messages
                        @if($notificationCount)
                            <span class="badge">{{ $notificationCount }}</span>
                        @endif
                    </a>
                </li>
                <li role="presentation" class=" {{ request()->input('tab') == 'appointments' ? 'active' : '' }}">
                    <a href="#appointments" aria-controls="appointments" role="tab" data-toggle="tab">
                        
                        <i class="fa fa-briefcase fa-fw"></i> Appointments
                        @if($appointmentCount)
                            <span class="badge">{{ $appointmentCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            <hr>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane {{ empty(request()->input('tab')) ? 'active' : '' }}" id="messages">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="list-group">
                                @foreach($threads AS $t)
                                    <a class="list-group-item {{ isset($currentThread) && $currentThread == $t->sender_id ? 'active': '' }}"  href="{{ url('/notifications', ['partner' => $t->sender_id])}}">
                                        @if((int)$t->unseen_count)
                                            <span class="badge">{{ $t->unseen_count }}</span>
                                        @endif
                                        {{ $t->sender }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-8">
                            @if(!isset($currentThread))
                                <p class="text-center"><i class="fa fa-envelope fa-3x"></i></p>
                                <p class="text-center">
                                    
                                    Select a person to chat in the left!
                                </p>
                            @else
                                <div class="row">
                                    <div class="col-sm-12" style="max-height:50vh;overflow:auto;margin-bottom:10px" id="message-box">
                                        @php
                                            $userDP = Auth::user()->profile_picture;
                                        @endphp
                                        @foreach($messages AS $m)
                                        <div class="media">
                                            <div class="media-left">
                                                <a href="#">
                                                    <img class="media-object" src="@if($m->sent_from == Auth::id()) @if(!is_null($userDP)){{ asset("storage/{$userDP}") }} @else /images/preview_default_profile.png @endif @else @if(!is_null($partnerInfo->profile_picture)){{ asset("storage/{$partnerInfo->profile_picture}") }} @else /images/preview_default_profile.png @endif @endif" alt="..." style="width:60px;height:60px">
                                                </a>
                                            </div>
                                            <div class="media-body">
                                                <h4 class="media-heading">{{ $m->sent_from == Auth::id() ?  'You' : $partnerInfo->fullname() }} <small>{{ date_create($m->created_at)->format('m/d/Y \@ h:i A') }} </small></h4>
                                                {{ $m->message }} 
                                            </div>
                                        </div>
                                        @endforeach
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <form method="post" action="{{ url('/send-message', ['partner' => $currentThread]) }}" class="ajax">
                                        <div class="col-sm-10">
                                            {{ csrf_field() }}
                                            <div class="form-group">
                                                <textarea class="form-control" name="message"></textarea>
                                            </div>
                                            
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-primary">Send</button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane  {{ request()->input('tab') == 'appointments' ? 'active' : '' }}" id="appointments">
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
                                    <p><strong>REMARK</strong> {{ $appointment->remarks }}</p> 
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
                                        <form class="update-appointment" method="post">
                                        {!! csrf_field() !!}
                                        {!! method_field('PATCH') !!}
                                        @if($appointment->status == 'ACCEPT')
                                            <p>Status: <span>APPROVED</span></p>
                                        @elseif($appointment->status == 'DECLINE')
                                            <p>Status: <span>DECLINED</span></p>
                                        @else
                                            <p>Status: <span>PENDING</span></p>
                                        @endif
                                       <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea name="remarks"  rows="3" class="form-control">{{ $appointment->remarks }}</textarea>
                                       </div>
                                        @if($appointment->status == 'ACCEPT')
                                            <button type="submit" data-url="{{ route('appointment.update', ['id' => $appointment->id, 'flag' => -1]) }}" class="btn btn-primary btn-sm submit">DECLINE</button>
                                        @elseif($appointment->status == 'DECLINE')
                                            <button type="submit" data-url="{{ route('appointment.update', ['id' => $appointment->id, 'flag' => 1]) }}" class="btn btn-primary btn-sm submit">ACCEPT</button>
                                        @else
                                            <button type="submit" data-url="{{ route('appointment.update', ['id' => $appointment->id, 'flag' => 1]) }}" class="btn btn-primary btn-sm submit">ACCEPT</button>
                                            <button type="submit" data-url="{{ route('appointment.update', ['id' => $appointment->id, 'flag' => -1]) }}" class="btn btn-primary btn-sm submit" >DECLINE</button>
                                        @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div class="clearfix"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>

    
            
    </div>
        <div class="spacer-30"></div>
@endsection

@push('scripts')
    <script type="text/javascript">
    
        $(document).ready(function(){
           
            var objDiv = document.getElementById("message-box");
            if(objDiv){
                objDiv.scrollTop = objDiv.scrollHeight;
            }
            

            $('.submit').click(function (e){
                e.preventDefault();
                var $this = $(this)

                $this.closest('form').attr('action', $(this).data('url'));
                $this.closest('form').submit();
            })

            // $('.update-appointment').submit(function(e) {
            //     e.preventDefault();

            //     console.log(e);
            // })
        })  


    </script>
@endpush

