@extends('layouts.app')

@section('content')
<div class="container">
    <div class="spacer-10"></div>
    <div class="container">
        <div class="panel panel-default contact">
         <!-- Proerty Slider Images -->
          <div class="panel-image">
              <h1>Contact Us</h1>
          </div>
          <div class="panel-body">    
            <form method="POST" action="/contact">
              {{csrf_field()}}
              <div class="form-group">
                <label>Your Name</label>
                <input type="text" class="form-control" name="name" required="" value="{{$name}}">
              </div>
              <div class="form-group">
                <label>Your Email</label>
                <input type="email" class="form-control" id="email" name="email" required="" value="{{$email}}">
              </div>
              <div class="form-group">
                <label>Message</label>
                <textarea class="form-control" rows="10" name="message" required=""></textarea>
              </div>
              <button type="submit" class="btn btn-primary">Send</button>
            </form>
          </div>
      </div>   
    </div>
    <div class="spacer-40"></div>
</div>
@endsection
