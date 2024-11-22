<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Create</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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

            <div class="card shadow-lg p-4 col-md-6 ">
                <h2 class="card-title text-center mb-4 text-primary">Photo</h2>
                <form novalidate id="insert">
                    @csrf

                    <h5 class="mb-3 text-secondary">Photo</h5>
                    <div class="row g-2">

                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <span id="name_error" style="color: red;" class="error"></span>

                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        <span id="description_error" style="color: red;" class="error"></span>

                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" required></textarea>
                        <span id="notes_error" style="color: red;" class="error"></span>

                        <label for="images" class="form-label">Images</label>
                        <input type="file" class="form-control" id="images" name="images[]" accept="image/**" onchange="previewImage(event)" multiple required>
                        <span id="images_error" style="color: red;" class="error"></span>
                        <div id="image-preview" class="mt-2"></div>

                    </div>

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>


    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const selectedBase64Images = []; // To store selected images with base64 and metadata

        function previewImage(event) {
            const preview = document.getElementById('image-preview');
            const files = event.target.files;

            // Clear previous previews if needed
            preview.innerHTML = '';

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                // Check for duplicates
                const isDuplicate = selectedBase64Images.some(f => f.name === file.name && f.size === file.size);
                if (isDuplicate) {
                    continue;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const base64String = e.target.result;

                    // Add the new file to the selectedBase64Images array
                    selectedBase64Images.push({
                        name: file.name,
                        size: file.size,
                        base64: base64String,
                    });

                    // Create a container for the image and delete button
                    const container = document.createElement('div');
                    container.style.display = 'inline-block';
                    container.style.position = 'relative';
                    container.style.marginRight = "10px";

                    // Create image element for preview
                    const img = document.createElement('img');
                    img.src = base64String;
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

                    // Delete button click handler
                    deleteButton.addEventListener('click', () => {
                        // Remove the image from selectedBase64Images
                        const index = selectedBase64Images.findIndex(f => f.name === file.name && f.size === file.size);
                        if (index > -1) {
                            selectedBase64Images.splice(index, 1);
                        }

                        // Remove the container from the DOM
                        container.remove();

                        // Update the input file list using DataTransfer
                        const inputElement = document.getElementById('images');
                        const dt = new DataTransfer();

                        // Add remaining files to the DataTransfer object
                        selectedBase64Images.forEach(img => {
                            const matchingFile = Array.from(files).find(f => f.name === img.name && f.size === img.size);
                            if (matchingFile) {
                                dt.items.add(matchingFile);
                            }
                        });

                        // Update the input element's file list
                        inputElement.files = dt.files;
                    });

                    // Append image and delete button to the container
                    container.appendChild(img);
                    container.appendChild(deleteButton);

                    // Append the container to the preview area
                    preview.appendChild(container);
                };

                reader.readAsDataURL(file); // Convert file to base64
            }
        }

        // Form submission handler
        const form = document.getElementById('insert');

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = {
                images: selectedBase64Images,
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                notes: document.getElementById('notes').value,
            };

            console.log("formData:", formData);

            // Uncomment the following code to enable submission via Axios

            axios.post("{{ route('photos.store') }}", formData)
                .then(function(response) {
                    if (response.data.success === true) {
                        alert('Form submitted successfully');
                        window.location.href = '{{ route("photos.index") }}';
                    }
                })
                .catch(function(error) {
                    console.error(error);
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