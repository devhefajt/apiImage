<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <h1 class="text-center">Photo Gallery</h1>
            <a href="{{ route('photos.create') }}" class="btn btn-primary mb-3 float-end">+</a>
            <table class="table table-striped" id="itemsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Images</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="itemsTableBody">
                    <!-- Data will be injected here via Axios -->
                </tbody>
            </table>

            <!-- Pagination Controls -->
            <div id="paginationControls" class="d-flex gap-3 justify-content-center">
                <button id="prevBtn" class="btn btn-danger rounded-circle" onclick="loadItems(currentPage - 1)" disabled> <- </button>
                        <span id="pageNumber" class="d-flex align-items-center"></span>
                        <button id="nextBtn" class="btn btn-danger rounded-circle" onclick="loadItems(currentPage + 1)" disabled> -> </button>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        let currentPage = 1; // Start from the first page

        document.addEventListener('DOMContentLoaded', function() {
            // Load items on page load
            loadItems(currentPage);
        });

        function loadItems(page) {
            axios.get(`/api/photos?page=${page}`) // Pass the current page to the API
                .then(response => {
                    const items = response.data.data; // Extract items from response
                    const pagination = response.data; // Pagination data
                    const itemsTableBody = document.getElementById('itemsTableBody');
                    const pageNumber = document.getElementById('pageNumber');
                    const prevBtn = document.getElementById('prevBtn');
                    const nextBtn = document.getElementById('nextBtn');

                    itemsTableBody.innerHTML = ''; // Clear any existing rows
                    pageNumber.textContent = `Page ${pagination.current_page} of ${pagination.last_page}`; // Display current page number

                    // Enable/Disable the buttons based on the current page
                    prevBtn.disabled = pagination.current_page === 1;
                    nextBtn.disabled = pagination.current_page === pagination.last_page;

                    // Loop through the items and generate table rows
                    items.forEach((item, index) => {
                        let images = 'No images'; // Default message if no images
                        if (Array.isArray(item.images) && item.images.length > 0) {
                            images = item.images.map(img => `<img src="${('/' + img)}" height="80px" width="80px" class="me-1">`).join('');
                        }

                        const row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.name}</td>
                            <td>${item.description}</td>
                            <td>${images}</td>
                            <td><a class="btn btn-primary btn-sm" onclick="editItem(${item.id})"><i class="fa fa-edit"></i></a>
                            <button class="btn btn-danger btn-sm"  onclick="deleteItem(${item.id})"><i class="fa fa-trash"></i></button></td>
                        </tr>
                    `;
                        itemsTableBody.innerHTML += row;
                    });

                    currentPage = pagination.current_page; // Update the current page
                })
                .catch(error => {
                    console.error('Error fetching items:', error);
                });
        }

        function editItem(id) {
            // Redirect to the edit route dynamically
            window.location.href = `/photos/${id}/edit`;
        }

        // Function to delete an item
        function deleteItem(id) {
            // if (confirm('Are you sure you want to delete this item?')) {
            axios.delete(`/api/photos/${id}`)
                .then(response => {
                    alert(response.data.message); // Assuming the response has a message property
                    loadItems(currentPage); // Reload items after deletion
                })
                .catch(error => {
                    console.error('Error deleting item:', error);
                });
            // }
        }
    </script>

</body>

</html>