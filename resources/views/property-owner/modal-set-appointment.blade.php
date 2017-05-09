<div id="appointment_box" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body" style="text-align: center">
            	<form method="POST" action="/appointments/units/{{$unit->id}}" id="set-app">
            	{{ csrf_field() }}
				<div class="bs-callout bs-callout-danger hidden error">
				</div>
               <div class="form-group">
	               <label>Date and Time</label>
	                <div class='input-group date' id='datetimepicker1'>
	                    <input type='text' class="form-control" name="schedule" required="" />
	                    <span class="input-group-addon">
	                        <span class="glyphicon glyphicon-calendar"></span>
	                    </span>
	                </div>
	            </div>
	            <button type="submit" class="btn btn-primary">Set</button>
	            </form>
            </div>
        </div>
    </div>
</div>