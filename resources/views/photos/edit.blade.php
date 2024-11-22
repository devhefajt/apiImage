<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Update</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }

        .sidebar a {
            color: wheat;
            padding: 15px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: whitesmoke;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #fff;
        }

        .navbar-brand:hover {
            color: #ccc;
        }


        /* Image css */
        #image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        #image-preview div img {
            transition: transform 0.3s;
        }

        #image-preview div:hover img {
            transform: scale(1.05);
        }

        #image-preview div button {
            transition: background-color 0.3s, transform 0.3s;
        }

        #image-preview div button:hover {
            background-color: rgba(255, 0, 0, 0.8);
            /* Red on hover */
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar position-fixed d-flex flex-column p-3" style="width: 250px;">
        <a href="" class="navbar-brand">Dashboard</a>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('photos.index') }}">Photo</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Photo</a>
                <div class="d-flex ms-auto">
                    <form id="logout-form" action="" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </nav>


        <div class="container mt-5">
            <div class="card shadow-lg p-4 col-md-6">
                <h2 class="card-title text-center mb-4 text-primary">Update Photo</h2>
                <form novalidate id="update">

                    <h5 class="mb-3 text-secondary">Photo</h5>

                    <div class="row g-2">

                        <!-- Name -->
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $photo->name }}" required>
                        <span id="name_error" style="color: red;" class="error"></span>

                        <!-- Description -->
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required>{{ $photo->description }}</textarea>
                        <span id="description_error" style="color: red;" class="error"></span>

                        <!-- Notes -->
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" required>{{ $photo->notes }}</textarea>
                        <span id="notes_error" style="color: red;" class="error"></span>

                        <!-- Images -->
                        <label for="images" class="form-label">Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" accept="image/**" onchange="previewImage(event)" multiple>
                        <span id="images_error" style="color: red;" class="error"></span>

                        <!-- Existing and New Images Preview -->
                        <div id="image-preview" class="mt-2">
                            <!-- Existing Images Preview -->
                            @foreach ($photo->images as $image)
                            <div style="display: inline-block; position: relative; margin-right: 10px;" data-existing-image="{{ asset($image) }}">
                                <img src="{{ asset($image) }}" style="max-width: 150px; margin-top: 10px;">
                                <button type="button" style="position: absolute; top: 12px; right: 3px; background-color: rgba(0, 0, 0, 0.6); color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer;" onclick="removeExistingImage(this, '{{ asset($image) }}')">x</button>
                            </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- <script>
            // Handle Image Preview
            const selectedFiles = [];

            function previewImage(event) {
                const preview = document.getElementById('image-preview');
                const files = event.target.files; // Get the file list

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    // Check for duplicates
                    const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (isDuplicate) {
                        continue; // Skip this file
                    }

                    // Add file to selectedFiles list
                    selectedFiles.push(file);

                    if (file) {
                        // Create a container for the image and delete button
                        const container = document.createElement('div');
                        container.style.display = 'inline-block';
                        container.style.position = 'relative';
                        container.style.marginRight = "10px";

                        // Create image element for preview
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.style.maxWidth = "150px";
                        img.style.marginTop = "10px";

                        // Create delete button
                        const deleteButton = document.createElement('button');
                        deleteButton.type = 'button';
                        deleteButton.innerText = 'x';
                        deleteButton.style.position = 'absolute';
                        deleteButton.style.top = '12px';
                        deleteButton.style.right = '3px';
                        deleteButton.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
                        deleteButton.style.color = 'white';
                        deleteButton.style.border = 'none';
                        deleteButton.style.borderRadius = '50%';
                        deleteButton.style.width = '24px';
                        deleteButton.style.height = '24px';
                        deleteButton.style.cursor = 'pointer';

                        deleteButton.addEventListener('click', () => {
                            const index = selectedFiles.findIndex(f => f.name === file.name && f.size === file.size);
                            if (index > -1) {
                                selectedFiles.splice(index, 1);
                            }

                            container.remove();

                            // Update the input file list using DataTransfer
                            const inputElement = document.getElementById('images');
                            const dt = new DataTransfer();

                            // Add remaining files to the DataTransfer object
                            selectedFiles.forEach(file => dt.items.add(file));

                            // Update the input element's file list
                            inputElement.files = dt.files;
                        });

                        // Append image and delete button to the container
                        container.appendChild(img);
                        container.appendChild(deleteButton);

                        // Append the container to the preview area
                        preview.appendChild(container);
                    }
                }
            }

            // Handle removal of existing images
            function removeExistingImage(button, imagePath) {

                // Remove the image preview container
                const container = button.parentElement;
                container.remove();
            }

            // Handle the form submission
            const form = document.getElementById('update');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                document.querySelectorAll('.error').forEach(error => error.textContent = '');

                const formData = new FormData(this);
                const files = document.getElementById('images').files;
                formData.delete('images[]');

                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        formData.append('images[]', files[i]);
                    }
                }

                axios.post("{{ route('photos.update', $photo->id) }}", formData)
                    .then(function(response) {
                        if (response.data.success === true) {
                            alert('Photo updated successfully');
                            window.location.href = '{{ route("photos.index") }}';
                        }
                    })
                    .catch(function(error) {
                        if (error.response && error.response.data.errors) {
                            const errors = error.response.data.errors;
                            for (const field in errors) {
                                const errorElement = document.getElementById(`${field}_error`);
                                if (errorElement) {
                                    errorElement.innerText = errors[field].join(', ');
                                }
                            }
                        }
                    });
            });
        </script> -->

        <script>
            const selectedFiles = []; // Store newly uploaded files
            const imagesToRemove = []; // Track existing images to delete

            // Preview new image uploads
            function previewImage(event) {
                const preview = document.getElementById('image-preview');
                const files = event.target.files;

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    // Check for duplicates
                    const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (isDuplicate) continue;

                    // Add to selected files
                    selectedFiles.push(file);

                    // Create container for preview
                    const container = document.createElement('div');
                    container.style.display = 'inline-block';
                    container.style.position = 'relative';
                    container.style.marginRight = '10px';

                    // Create image element
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '150px';
                    img.style.marginTop = '10px';

                    // Create delete button
                    const deleteButton = document.createElement('button');
                    deleteButton.type = 'button';
                    deleteButton.innerText = 'x';
                    deleteButton.style.position = 'absolute';
                    deleteButton.style.top = '12px';
                    deleteButton.style.right = '3px';
                    deleteButton.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
                    deleteButton.style.color = 'white';
                    deleteButton.style.border = 'none';
                    deleteButton.style.borderRadius = '50%';
                    deleteButton.style.width = '24px';
                    deleteButton.style.height = '24px';
                    deleteButton.style.cursor = 'pointer';

                    deleteButton.addEventListener('click', () => {
                        // Remove file from selected files
                        const index = selectedFiles.findIndex(f => f.name === file.name && f.size === file.size);
                        if (index > -1) selectedFiles.splice(index, 1);

                        container.remove();
                        updateInputFileList();
                    });

                    container.appendChild(img);
                    container.appendChild(deleteButton);
                    preview.appendChild(container);
                }
            }

            // Remove existing image preview and mark for deletion
            function removeExistingImage(button, imagePath) {
                const container = button.parentElement;
                container.remove();

                // Add to the imagesToRemove list
                imagesToRemove.push(imagePath);
            }

            // Update the input file list for form submission
            function updateInputFileList() {
                const inputElement = document.getElementById('images');
                const dt = new DataTransfer();

                selectedFiles.forEach(file => dt.items.add(file));
                inputElement.files = dt.files;
            }

            // Handle form submission
            const form = document.getElementById('update');
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Clear error messages
                document.querySelectorAll('.error').forEach(error => error.textContent = '');

                const formData = new FormData(this);

                // Append newly uploaded files
                selectedFiles.forEach(file => formData.append('images[]', file));

                // Append removed images
                imagesToRemove.forEach(imagePath => formData.append('removed_images[]', imagePath));

                // Submit the form via Axios
                axios.put("{{ route('photos.update', $photo->id) }}", formData)
                    .then(response => {
                        if (response.data.success) {
                            alert('Photo updated successfully!');
                            window.location.href = '{{ route("photos.index") }}';
                        }
                    })
                    .catch(error => {
                        if (error.response && error.response.data.errors) {
                            const errors = error.response.data.errors;
                            for (const field in errors) {
                                const errorElement = document.getElementById(`${field}_error`);
                                if (errorElement) {
                                    errorElement.innerText = errors[field].join(', ');
                                }
                            }
                        }
                    });
            });
        </script>


</body>

</html>