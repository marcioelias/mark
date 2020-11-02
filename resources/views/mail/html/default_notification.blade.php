<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link href="{{ asset(mix('css/quill.snow.css')) }}" rel="stylesheet">
    <link href="{{ asset(mix('css/quill.bubble.css')) }}" rel="stylesheet"> --}}
    {{-- <link href="//cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <link href="//cdn.quilljs.com/1.3.7/quill.bubble.css" rel="stylesheet"> --}}
    <title>{{ $title ?? 'Título' }}</title>
</head>
<body>
    <div class="container-fluid">
        {!! $msg !!}
    </div>
</body>
</html>
