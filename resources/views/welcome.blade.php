<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

    </style>
</head>

<body class="antialiased">
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-8">
                <div class="row">
                    <div class="col-xs-4 col-sm-2">
                        <a href="{{route('integrate.google')}}" class="btn btn-lg btn-block omb_btn-facebook">
                            <button type="button" class="btn btn-info">Google auth</button>
                        </a>
                    </div>
                    <div class="col-xs-4 col-sm-2">
                        <a href="{{route('list.sites')}}" class="btn btn-lg btn-block omb_btn-facebook">
                            <button type="button" class="btn btn-info">Get list Sites</button>
                        </a>
                    </div>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger">{{$errors->first()}}</div>
                @endif
                <div class="col-md-4">
                    @if(isset($urlList) && count($urlList) > 0)

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    Site list</h3>
                            </div>
                            <ul class="list-group">
                                @foreach($urlList as $url)
                                    <a href="{{route('search.analytics', ['url' => $url])}}"
                                       class="list-group-item">{{$url}}</a>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div>
                    @if(isset($analyticResults) && count($analyticResults) > 0)
                        <h3>{{$url}}</h3>
                        <table class="table" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Keyword</th>
                                <th>Link</th>
                                <th>Clicks</th>
                                <th>Impressions</th>
                                <th>CTR</th>
                                <th>AVG position</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($analyticResults as $result)
                                <tr>
                                    <td>{{$result['keyword']}}</td>
                                    <td>{{$result['link']}}</td>
                                    <td>{{$result['clicks']}}</td>
                                    <td>{{$result['impressions']}}</td>
                                    <td>{{$result['ctr']}}</td>
                                    <td>{{$result['avg_position']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
</body>
</html>
