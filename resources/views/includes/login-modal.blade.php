<div id="login_box" class="modal fade">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <div class="log_form">
                    <h2 class="frm_titl" style="margin-bottom:10px !important"> Login Form </h2>
                    <form name="sentMessage" id="loginForm" method="POST" action="{{ url('/login') }}" class="ajax" novalidate>
                        {{ csrf_field() }}
                        <div class="bs-callout bs-callout-danger hidden"></div>
                        <div class="control-group form-group">
                            <div class="controls">
                                <input type="text" class="form-control" name="username" placeholder="Username">
                                <p class="help-block"></p>
                            </div>

                            <div class="controls">
                                <input type="password" class="form-control" name="password"  placeholder="Password">

                                <p class="help-block"></p>
                            </div>
                            <div class="forg_pass">
                                <a class="" href="/password/reset"> Forgot your password?  </a>
                            </div>
                            <div class="clearfix"></div>

                            <button type="submit" class="btn btn-primary">Sign In</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>