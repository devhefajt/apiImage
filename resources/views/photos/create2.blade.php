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


    <!-- Form submit with axios -->
    <!-- <script>
        document.getElementById('insert').addEventListener('submit', function(event) {
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

            axios.post("{{ route('photos.store') }}", formData)
                .then(function(response) {

                    if (response.data.success === true) {
                        alert('Form submitted successfully');
                        document.getElementById('insert').reset();
                        document.getElementById('image-preview').innerHTML = '';
                    }
                })


                .catch(function(error) {
                    // Error handler: Display validation errors if any
                    if (error.response && error.response.data.errors) {
                        const errors = error.response.data.errors;

                        // Loop through each error and display it next to the field
                        for (const field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                const errorMessages = errors[field];
                                const errorElement = document.getElementById(`${field}_error`);

                                // Clear any previous error messages
                                errorElement.innerHTML = '';

                                // Append new error messages
                                errorMessages.forEach(message => {
                                    const errorText = document.createElement('div');
                                    errorText.classList.add('error-message');
                                    errorText.innerText = message;
                                    errorElement.appendChild(errorText);
                                });
                            }
                        }
                    }
                });

        });
    </script> -->

    <!-- //Multiple Image Preview  -->

    <!-- <script>
        const selectedFiles = [];

        function previewImage(event) {
            const preview = document.getElementById('image-preview');

            const files = event.target.files; // Get the file list
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                // console.log(`File Name: ${file.name}`);
                // console.log(`File Size: ${file.size} bytes`);
                // console.log(`File Type: ${file.type}`);

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
    </script> -->


    <script>
        const form = document.getElementById('insert');

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

            axios.post("{{ route('photos.store') }}", formData)
                .then(function(response) {

                    if (response.data.success === true) {
                        alert('Form submitted successfully');
                        // form.reset();
                        window.location.href = '{{ route("photos.index") }}';
                        document.getElementById('image-preview').innerHTML = '';
                    }
                })

                .catch(error => {

                    console.log("error:", error.response.data.errors) 

                    // Display validation errors
                    if (error.response && error.response.data.errors) {
                        const errors = error.response.data.errors;

                        // Loop through each field in the errors object
                        for (const field in errors) {
                            const errorElement = document.getElementById(`${field}_error`);
                            if (errorElement) {
                                errorElement.innerText = errors[field].join(', ');
                            }
                        }
                    }

                })

                // .catch(error => {
                //     // Display validation errors
                //     if (error.response && error.response.data.errors) {
                //         const errors = error.response.data.errors;

                //         // Loop through each error field
                //         for (const field in errors) {
                //             const errorMessages = errors[field];

                //             // Check if the error belongs to the images array (like images.0, images.1, etc.)
                //             const match = field.match(/^images\.(\d+)$/);
                //             if (match) {
                //                 const fileIndex = match[1]; // Get index (0, 1, 2, etc.)

                //                 // Handle specific image errors (e.g., mime type or required)
                //                 let errorElement = document.getElementById(`images_${fileIndex}_error`);
                //                 if (!errorElement) {
                //                     errorElement = document.createElement('div');
                //                     errorElement.id = `images_${fileIndex}_error`;
                //                     errorElement.style.color = 'red';
                //                     errorElement.classList.add('error');
                //                     const previewContainer = document.getElementById('image-preview').children[fileIndex];
                //                     if (previewContainer) {
                //                         previewContainer.appendChild(errorElement);
                //                     }
                //                 }

                //                 // Clear and append error messages for this specific image
                //                 errorElement.innerHTML = '';
                //                 errorMessages.forEach(message => {
                //                     errorElement.innerText = message;
                //                 });

                //             } else {
                //                 // General validation errors (non-image fields like name, description, etc.)
                //                 const errorElement = document.getElementById(`${field}_error`);
                //                 if (errorElement) {
                //                     errorElement.innerText = errorMessages.join(', ');
                //                 }
                //             }
                //         }
                //     }
                // });

        });


        const selectedFiles = [];

        function previewImage(event) {
            const preview = document.getElementById('image-preview');

            const files = event.target.files; // Get the file list
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                // console.log(`File Name: ${file.name}`);
                // console.log(`File Size: ${file.size} bytes`);
                // console.log(`File Type: ${file.type}`);

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


                        // if (selectedFiles.length > 0) {
                        //     console.log("First selected file:", selectedFiles[0].name); 
                        // } else {
                        //     console.log("No files remaining in the preview.");
                        // }

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
    </script>



</body>

</html>