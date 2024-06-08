<!DOCTYPE html>
<html>
<head>
    <title>Upload Image to AWS S3</title>
</head>
<body>
    @if(session('success'))
        <div>
            <p>{{ session('success') }}</p>
            <p><a href="{{ session('url') }}" target="_blank">{{ session('url') }}</a></p>
            <img src="{{ session('url') }}" alt="Uploaded Image" style="max-width: 100%;">
        </div>
    @endif

    @if(session('error'))
        <div>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>
