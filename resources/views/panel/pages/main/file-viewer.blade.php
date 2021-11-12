<!doctype html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$document->name}}</title>
</head>
<body>
    <div class="row m-0" style="position: relative">
        @php($images = ['image/jpeg', 'image/jpg', 'image/png'])
        @if(in_array($document->type, $images))
            <img src="{{route('document.temporaryUrl', $document)}}" alt="img" style="width: 500px">
        @else
            <iframe
                    src="https://view.officeapps.live.com/op/embed.aspx?src={{route('document.temporaryUrl', $document)}}"
                    style="width:100%;height:600px;border: 0"
            >
                <p>Your browser does not support iframes.</p>
            </iframe>
        @endif
        <br/>
        <a href="{{route('document.temporaryUrl', $document)}}" download class="btn btn-outline-primary">Download</a>
    </div>
</body>
</html>
