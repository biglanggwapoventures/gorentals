@extends('layouts.app')

@section('content')
    @component('components.breadcrumbs')
        <p class="lnk_pag"><a> notifications</a> </p>
    @endcomponent

    <div class="spacer-30"></div>
    <div class="container">
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
        <div class="spacer-30"></div>
@endsection

@push('scripts')
    <script type="text/javascript">
    
        $(document).ready(function(){
            var objDiv = document.getElementById("message-box");
            objDiv.scrollTop = objDiv.scrollHeight;
        })  
    </script>
@endpush

