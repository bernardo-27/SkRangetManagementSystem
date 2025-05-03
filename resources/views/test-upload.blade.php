<!-- resources/views/test-upload.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Upload Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        button { padding: 8px 16px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        .result { margin-top: 20px; padding: 15px; border: 1px solid #ddd; display: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Upload Test</h1>
    <p>This form tests basic file upload functionality.</p>

    <div class="form-group">
        <form id="uploadForm" action="/test-upload" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="test_image">Select Image:</label>
                <input type="file" id="test_image" name="test_image">
            </div>
            <button type="submit">Upload</button>
        </form>
    </div>

    <div class="result" id="resultBox">
        <h3>Result:</h3>
        <pre id="resultContent"></pre>
        <div id="imagePreview" style="margin-top: 10px;"></div>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultBox = document.getElementById('resultBox');
            const resultContent = document.getElementById('resultContent');
            const imagePreview = document.getElementById('imagePreview');
            
            fetch('/test-upload', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                resultBox.style.display = 'block';
                
                if (data.success) {
                    resultContent.innerHTML = JSON.stringify(data, null, 2);
                    resultContent.classList.remove('error');
                    
                    // Display the image if successful
                    imagePreview.innerHTML = `<img src="${data.full_url}" style="max-width: 100%; max-height: 300px;">`;
                } else {
                    resultContent.innerHTML = JSON.stringify(data, null, 2);
                    resultContent.classList.add('error');
                    imagePreview.innerHTML = '';
                }
            })
            .catch(error => {
                resultBox.style.display = 'block';
                resultContent.innerHTML = 'Error: ' + error;
                resultContent.classList.add('error');
                imagePreview.innerHTML = '';
            });
        });
    </script>
</body>
</html>