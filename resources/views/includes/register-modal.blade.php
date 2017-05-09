<div id="reg_box" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="log_form">
                    <h2 class="frm_titl"> Create Account </h2>
                    <span class="this-is-required">* is required</span>
                    <form name="sentMessage" id="RegisForm" method="POST" action="{{ url('/register') }}" class="ajax" novalidate>
                        {{ csrf_field() }}
                        <div class="bs-callout bs-callout-danger hidden"></div>
                        <div class="control-group form-group">
                            <input type="hidden" name="status" value="ENABLE"/>
                            <div class="text-center" style="margin-bottom:20px">
                                 <label class="radio-inline">
                                    <input type="radio" name="login_type" id="inlineRadio1" value="USER"> I am a tenant
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="login_type" id="inlineRadio2" value="PROPERTY_OWNER"> I am a property owner
                                </label>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="firstname" placeholder="First Name*">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="lastname" placeholder="Last Name*">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="username" placeholder="Username*">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="email" placeholder="Email Address*">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <input type="password" class="form-control" name="password" placeholder="Password*">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password*">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <select name="gender" class="form-control">
                                            <option value="" disabled selected>Gender*</option>
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="controls">
                                        <input type="text" class="form-control" name="mobile_number" placeholder="Mobile Number">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">
                                    <div class="controls">
                                        <label for="">Profile Picture</label>
                                        <input type="file" name="profile_picture">
                                    </div>
                                </div>
                            </div> -->
                            <hr>
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>