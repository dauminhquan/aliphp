<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products of Aliexpress</title>
    <link href="{{asset('images/favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="{{asset("css/common.css")}}" rel="stylesheet" type="text/css">

</head>
<body>

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="#"><i class="icon-search4"></i> Amazon Team</a>

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <p class="navbar-text">
            <span class="label bg-success">Online</span>
        </p>

        <div class="navbar-right">
            <ul class="nav navbar-nav">


                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-file-text"></i>
                        <span class="visible-xs-inline-block position-right">Logs</span>
                        <span class="badge bg-warning-400">2</span>
                    </a>

                    <div class="dropdown-menu dropdown-content width-350">
                        <div class="dropdown-content-heading">
                            Logs
                            <ul class="icons-list">
                                <li><a href="#"><i class="icon-compose"></i></a></li>
                            </ul>
                        </div>

                        <ul class="media-list dropdown-content-body">
                            <li class="media">
                                <div class="media-left">
                                    <img src="data:image/png;base64,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" class="img-circle img-sm" alt="">
                                    <span class="badge bg-danger-400 media-badge">5</span>
                                </div>

                                <div class="media-body">
                                    <a href="#" class="media-heading">
                                        <span class="text-semibold">James Alexander</span>
                                        <span class="media-annotation pull-right">04:58</span>
                                    </a>

                                    <span class="text-muted">who knows, maybe that would be the best thing for me...</span>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left">
                                    <img src="data:image/png;base64,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" class="img-circle img-sm" alt="">
                                    <span class="badge bg-danger-400 media-badge">4</span>
                                </div>

                                <div class="media-body">
                                    <a href="#" class="media-heading">
                                        <span class="text-semibold">Margo Baker</span>
                                        <span class="media-annotation pull-right">12:16</span>
                                    </a>

                                    <span class="text-muted">That was something he was unable to do because...</span>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left"><img src="data:image/png;base64,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" class="img-circle img-sm" alt=""></div>
                                <div class="media-body">
                                    <a href="#" class="media-heading">
                                        <span class="text-semibold">Jeremy Victorino</span>
                                        <span class="media-annotation pull-right">22:48</span>
                                    </a>

                                    <span class="text-muted">But that would be extremely strained and suspicious...</span>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOgAAADZCAMAAAAdUYxCAAAAgVBMVEX///9APz9HRkY9PDw4Nzc1NDQ5ODguLS0xMDAsKiopKCjw8PAvLi74+PjR0dEqKCjd3d2qqqqgoKC/v79TUlKFhYWRkJBLSkpoZ2fr6+vk5OTJycmwsLB4d3fT09Obmpq7urpvb29RUFBfXl6TkpJ2dnaIiIhbWlojISFkZGR/f3+aaZOVAAAHHUlEQVR4nO2da3u6PAzGxVIORRygU4dnd3Db9/+AD+D2n48U5JCYgP5e8Jr7apsmaZoOBg8ePLhv/OzrBcvdKtpuo3A1GQexR/xT8HiRPwiivaHckaPCZez51H+EQziMw7krLWGkSMeRYnjcf0STTa/GNDiMhpY8ifyHEMKUju2K14/Vph/ju3IvNF4oNqXrTGeTzo/t9qlM5j+1jjuNAup/bUPoVtB5EivdQ9jZSRyrqjozrY476+gUXlt1hCZI9Ub9z42oPHHPpFpL6r+uSbyLvusOaIpQ39S/XgM/nCtHNtGZDuowpv7/ivhvo0sPod6g2mNqCZXYmbKFykzpUxcW6lq1Gc0f+Cv13xcAOhOlG2olVwjiOYhQIZn7Dr4A0ZkonVJLKWdhwug0DGdGraWMZS3vthzFeZlOgSZuiphTqykmABzQZPJG1HoK2bZ1FS6UsrW8r4AzN0GytUegMzfBZjqkfgg7dQ3JNQ5fOrBCDYdpGukNzF34FbqilqQHchvNEAdqSVp8aGOURDEs071Bg2zYFcwPalE6JtC2KEFSi9IRAe8uKSzzRx/QRtdgupXuGyY4y2Bpd4/Qu0uKYugzwKSLLuC4SA0MoZKhc4QilONOiiJULKhl5RmiCGWYOgL36TNMall5vlCEutSy8jQ6+b0ulN9GOkNwAVkKhU4ZsRWKEaaxFIoQeBssjZEHn0pJENSyNNyLwzCAOda/ELqnVqUB+JApg6NTj5MdC6lVaXhBsEYjlpU4UKUaZ6gXalE6nuGdQIdakxb4RWpxNLoYLgNLWzRAyHi6LE+ZEAIYjg5gsr1MVsBCxSKc8dtfws8RuGskpPnJbZkGn9Aqf+BWyYBxapjhMqsKRPAWTtjMVinGqeFJKLO6KoxoNIWdc4Q1ouziF6w1yq5eA8nq8ksxLG0Uoexm7sDHEcowgV37vmgVOKY7YUvqf7A4XrMELjXPYBl5Y5gjZ0KtSgfCInVZXptFOPP+5Gd0E3bgWUBxpNakpV7nhSrILbUmPeA1VdyC7l/A6zV4JuoHgzHw6T5LdyED+JiJWxbljzfQWE3wzF+nxFU6GFWGq81NeYf0Gbilxc6BHFKH8YAOBmGiFMIimcLkeEPijEjJ9qeH1npjvDKeuBme3/5QTfK9yX5O+6pAjrdAdLSOYhimxLTsWy5SptFZnrZH/B1Zou0XKdcajTxWu7nL7rylkHaBKb/zlkI2rTxBtyObS0qbO5bCov77GrRJCHKOzvK0yDV8sixdLWLVeEjZFS1cofGQPnXIFKWEDYeUe6e8PA2HlG/mr4hmt0NYdl4op1n4bbLtNFZIs62U5RF3Oc2uEjg76v+uTTOh/OqKrtJMaHdC0X/cjdBmxqiDQpsljrqS6DyjWblnB61uswLebgWjGc1qz8W0I7nrPxp2wxF2V5K6PywbJ7GdKfdztDOC1xY5bOF0Zo+ZtXtpQXAtpLogPrR+OMPugtKJ3f5sX0j+qcAdSOmjmHPfZqCeHzBfqZWUswGrN5cMO+X9sQFYn7+Yc5Z15hkhaImcUEzfjBsfR5A6E0y12HEzSv7qUP42ZTOEo9ac0kjxm+Mg3ZQ1LNucMfEJx3uFdZX9hKmmK/Ip7IVzF+t+9x/Csb9JsyzBt9Pq4bsaWO48JLLC/mrqYnUl0CGk/U4wrMGzRDNAhVj28LbD6oWHmw7mH0K665sN63Jv32pl6khX6w2McDCzbHwzW06yWr9xY/OXaK5opuwlljqg9XZ/CY+KcspeIBz3A8FlSlQ+MVJ5wlRH2MvRccRqLM8QI3MLFbfG0YGpyhPyaQEQ4HjhlN+MvUTYRtQubRh8u+xVZiRuRAvvcPmlqDfMGjR2+sdHjJQBJokbsa7tRgSLrsnMsNSxntRZlybt/xCqxotVG4GbGsFlVLm0OYJ4X52Qqkn+NU6zqRviVOpr8NXlafuDW6FIdNEDnYahrvq/zyiPfNycqzczYY5xGTAqj8pfOm+HfhFWaVbpnUeaBILS27YoLe+okCVDitYkl4KSR6Mxnr2go6RLJMaLLYQUX7hFeZ6QjsIGV/Bd4IixC4RCPxxATtGFPpTGsZSYz3qh/XEWftE3dHgBrYZigdIezSz7Ebeco39LGe01CDqs9V3YoqLOgj1zFzKUJnWP1H+dFt1Vtxjl/VBidFfdxn0cUd2VcZynb4nRpRl65+mmSM05TA+3UX1bh7f+ubr6rl5or0dRohPaQ8dILxTr9ShSdBEpxuMe5OiETu9FaB99eq1Q8PcROKAzRn00ulqhfdxGtUL76AHqhPr9FJr3df0+Rml3JDQfpvVTqCYe9aBv87JA0zXI62PKSJdKeQjtNJosYE+F5k+ZvD7mr3UJ7IfQTnM/QvNX9B5CO42mi20/hWrOR3sqNH+tqZ8Og52vv+mnUE273ofQTqO5/uKZwx5y1kfxP660n2mK7qgZAAAAAElFTkSuQmCC" class="img-circle img-sm" alt=""></div>
                                <div class="media-body">
                                    <a href="#" class="media-heading">
                                        <span class="text-semibold">Beatrix Diaz</span>
                                        <span class="media-annotation pull-right">Tue</span>
                                    </a>

                                    <span class="text-muted">What a strenuous career it is that I've chosen...</span>
                                </div>
                            </li>

                            <li class="media">
                                <div class="media-left"><img src="data:image/png;base64,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" class="img-circle img-sm" alt=""></div>
                                <div class="media-body">
                                    <a href="#" class="media-heading">
                                        <span class="text-semibold">Richard Vango</span>
                                        <span class="media-annotation pull-right">Mon</span>
                                    </a>

                                    <span class="text-muted">Other travelling salesmen live a life of luxury...</span>
                                </div>
                            </li>
                        </ul>

                        <div class="dropdown-content-footer">
                            <a href="#" data-popup="tooltip" title="Tất cả logs"><i class="icon-menu display-block"></i></a>
                        </div>
                    </div>
                </li>

                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <img src="data:image/png;base64,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" alt="">
                        <span>{{Auth::user()->name}}</span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="#"><i class="icon-cog5"></i> Account settings</a></li>
                        <li><a href="{{route('logout')}}"><i class="icon-switch2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /main navbar -->


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content" id="page-content">

        <!-- Main sidebar -->
        <div class="sidebar sidebar-main">
            <div class="sidebar-content">

                <!-- User menu -->
                <div class="sidebar-user">
                    <div class="category-content">
                        <div class="media">
                            <a href="#" class="media-left"><img src="data:image/png;base64,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" class="img-circle img-sm" alt=""></a>
                            <div class="media-body">
                                <span class="media-heading text-semibold">{{Auth::user()->name}}</span>
                                <div class="text-size-mini text-muted">
                                    <i class="icon-pin text-size-small"></i> &nbsp;Hà Nội, Việt Nam
                                </div>
                            </div>

                            <div class="media-right media-middle">
                                <ul class="icons-list">
                                    <li>
                                        <a href="#"><i class="icon-cog3"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /user menu -->


                <!-- Main navigation -->
                <div class="sidebar-category sidebar-category-visible">
                    <div class="category-content no-padding">
                        <ul class="navigation navigation-main navigation-accordion">

                            <!-- Main -->
                            <li class="navigation-header"><span>Main</span> <i class="icon-menu" title="Main pages"></i></li>
                            <li><a href="{{route('home')}}"><i class="icon-home4"></i> <span>Danh sách sản phẩm</span></a></li>
                            <li><a href="{{route('columns')}}"><i class="icon-add-to-list"></i> <span>Danh sách cột</span></a></li>
                            <li><a href="{{route('templates')}}"><i class="icon-list"></i> <span>Danh sách Template</span></a></li>
                            {{--<li><a href="{{route('excels')}}"><i class="icon-list"></i> <span>Xuất Excel</span></a></li>--}}
                        </ul>
                    </div>
                </div>
                <!-- /main navigation -->

            </div>
        </div>
        <!-- /main sidebar -->


        @section('content')
        @show

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

<script src="{{asset('js/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>
<script src="{{asset('js/plugins/select2.min.js')}}"></script>
@section('script')
    @show

</body>
</html>
