<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products of Aliexpress</title>
    <link href="{{asset('assets/images/favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset("css/common.css")}}" rel="stylesheet" type="text/css">

</head>

<body class="login-container">


<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{route('home')}}"><img src="assets/images/logo_light.png" alt=""></a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <a href="mailto:my.heart.osa.95@gmail.com">
                    <i class="glyphicon glyphicon-send"></i> <span class="visible-xs-inline-block position-right"> Contact admin</span>
                </a>
            </li>
        </ul>
    </div>
</div>




<div class="page-container">


    <div class="page-content">


        <div class="content-wrapper">


            <div class="content">


                <form method="post">
                    <div class="panel panel-body login-form">
                        <div class="text-center">
                            <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                            <h5 class="content-group">Login to your account <small class="display-block">Enter your credentials below</small></h5>
                        </div>

                        <div class="form-group has-feedback has-feedback-left">
                            <input type="email" name="email" required class="form-control" placeholder="Email">
                            <div class="form-control-feedback">
                                <i class="icon-mail5 text-muted"></i>
                            </div>
                        </div>

                        <div class="form-group has-feedback has-feedback-left">
                            <input type="password" class="form-control" name="password" required placeholder="Password">
                            <div class="form-control-feedback">
                                <i class="icon-lock2 text-muted"></i>
                            </div>
                        </div>
                        <div class="form-group login-options">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" class="styled" name="remember">
                                        Remember
                                    </label>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <a href="mailto:my.heart.osa.95@gmail.com">Forgot password?</a>
                                </div>
                            </div>
                        </div>
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
                        </div>
                    </div>
                </form>




                <div class="footer text-muted text-center">
                    &copy; 2018. <a href="#">Production</a> by <a href="https://amazon.com" target="_blank">AMAZON TEAM</a>
                </div>


            </div>

        </div>


    </div>


</div>

<script src="{{asset('js/common.js')}}"></script>
@section('script')
@show
</body>
</html>
