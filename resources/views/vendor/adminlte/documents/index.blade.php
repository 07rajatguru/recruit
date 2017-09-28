<html>
<head></head>
<body>
<h1>Documents</h1>
<p><a href="{{ route('documents.create') }}">Create Document</a></p>
@foreach ($documents as $document)
    <ul id="document-{{ $document->id }}">
        <li>ID: {{ $document->id }}</li>
        <li>Name: <a href="{{ $document->url }}">{{ $document->name }}</a></li>
        <li class="preview-url">
            @if ($document->preview_url)
                <a href="{{ $document->preview_url }}">Preview</a>
            @else
                No Preview
            @endif
        </li>
    </ul>
@endforeach
</body>
</html>