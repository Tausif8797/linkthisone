<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Downloader</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
   

</head>
<body class="container py-5">
    <h1 class="text-center mb-4">Facebook/Instagram video Downloader</h1>
    <form action="/download" method="POST" class="w-50 mx-auto">
        @csrf
        <div class="mb-3">
            <label for="video_url" class="form-label">Video URL</label>
            <div class="input-group">
            <input type="url" id="video_url" name="video_url" class="form-control" placeholder="Enter video URL" required>
            <button type="button" id="pasteButton" class="btn btn-primary">Paste</button>
            </div>
            @error('video_url')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Download</button>
    </form>

    <script>
        document.getElementById('pasteButton').addEventListener('click', async function() {
            try {
                const text = await navigator.clipboard.readText();
                document.getElementById('video_url').value = text;
            } catch (err) {
                alert('Failed to read clipboard: ' + err.message);
            }
        });


//         document.querySelector('form').addEventListener('submit', function () {
//     this.reset();
// });
    </script>
       
</body>
</html>
