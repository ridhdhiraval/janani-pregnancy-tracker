<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit;
}
include('../inc/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Doctors Management | Janani Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #eef2f7;
        }

        .main {
            margin-left: 250px;
            padding: 40px 50px;
        }

        .page-title {
            font-size: 30px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .page-sub {
            font-size: 15px;
            color: #6f6f6f;
            margin-top: 5px;
            margin-bottom: 25px;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .card {
            background: linear-gradient(135deg, #fff, #f5f7fb);
            padding: 28px;
            border-radius: 18px;
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
            border: 1px solid #f0f0f0;
            transition: 0.25s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .card i {
            color: #2173ffff;
            font-size: 38px;
            margin-bottom: 12px;
        }

        .card:nth-child(2) i {
            color: #1DD1A1;
        }

        .card .count {
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .card .label {
            color: #6f6f6f;
            font-size: 15px;
        }

        /* Filter + Add button alignment */
        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-input {
            flex: 1;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            transition: 0.2s;
            font-size: 14px;
        }

        .search-input:focus {
            border-color: #2173ffff;
            box-shadow: 0 0 0 4px rgba(33, 115, 255, 0.15);
        }

        .filter-select {
            flex: 0 0 200px;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            font-size: 14px;
        }

        .filter-select:focus {
            border-color: #2173ffff;
            box-shadow: 0 0 0 4px rgba(33, 115, 255, 0.15);
        }

        .add-btn {
            flex: 0 0 auto;
            padding: 12px 20px;
            background-color: #2173ffff;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.25s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .add-btn:hover {
            background-color: #005bf7ff;
        }

        .table-box {
            background: #fff;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e6e6e6;
        }

        .table-box h3 {
            font-size: 20px;
            margin: 0 0 15px 0;
            font-weight: 600;
            color: #222;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 15px;
        }

        th {
            padding: 14px;
            background: #2173ffff;
            color: white;
            text-align: left;
            font-size: 13px;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }

        td {
            padding: 14px;
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            position: relative;
            transition: background 0.3s ease;
            z-index: 1;
        }

        tbody tr:nth-child(odd) td {
            background: #f9f9f9ff;
        }

        tbody tr:nth-child(even) td {
            background: #ffffff;
        }

        tbody tr:hover td {
            background: #e6f0ff;
        }

        .status-active,
        .status-inactive,
        .status-blocked {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-active {
            background: #d4f7d4;
            color: #0a7f0a;
        }

        .status-inactive {
            background: #ffe8b3;
            color: #b37a00;
        }

        .status-blocked {
            background: #ffd2d2;
            color: #b30000;
        }

        .action-btn {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            margin-right: 6px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
        }

        .view-btn {
            color: #2173ffff;
        }

        .view-btn:hover {
            color: #005bf7ff;
        }

        .edit-btn {
            color: #ffae00;
        }

        .edit-btn:hover {
            color: #e89c00;
        }

        .deletee-btn {
            color: #f80c0cff;
        }

        .deletee-btn:hover {
            color: #d93636;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(40, 40, 40, 0.15);
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .modal-content {
            background: #ffffff;
            width: 460px;
            padding: 32px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18);
            animation: popup 0.3s ease;
            border-top: 5px solid #2173ffff;
        }

        .modal-content h2 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #2173ffff;
            text-align: center;
        }

        .modal-content label {
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            color: #222222;
        }

        .modal-content input,
        .modal-content select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            margin-bottom: 16px;
            transition: 0.25s;
            font-size: 14px;
            background-color: #fefefe;
        }

        .modal-content input:focus,
        .modal-content select:focus {
            border-color: #2173ffff;
            box-shadow: 0 0 0 4px rgba(33, 115, 255, 0.15);
            outline: none;
            background-color: #fff;
        }

        .save-btn,
        .close-btn,
        .delete-btn {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            color: white;
            margin-bottom: 12px;
            cursor: pointer;
            transition: 0.25s;
        }

        .save-btn {
            background: #2173ffff;
            border: none;
        }

        .save-btn:hover {
            background: #005bf7ff;
        }

        .close-btn {
            background: #a7abafff;
            border: none;
        }

        .close-btn:hover {
            background: #656c72ff;
        }

        .delete-btn {
            background: #f80c0cff;
            border: none;
        }

        .delete-btn:hover {
            background: #d93636;
        }

        @keyframes popup {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="page-title">Doctors Management</div>
        <div class="page-sub">Manage all registered doctors, their specialties & status</div>

        <!-- CARDS -->
        <div class="card-container">
            <div class="card" style="border-left:5px solid #2173ffff;">
                <i class="fa-solid fa-user-doctor"></i>
                <div class="count" id="totalCount">0</div>
                <div class="label">Total Doctors</div>
            </div>
            <div class="card" style="border-left:5px solid #1DD1A1;">
                <i class="fa-solid fa-user-check"></i>
                <div class="count" id="activeCount">0</div>
                <div class="label">Active Doctors</div>
            </div>
        </div>

        <!-- FILTER + ADD BUTTON -->
        <div class="filter-bar">
            <input type="text" class="search-input" placeholder="Search doctors..." id="searchInput">
            <select class="filter-select" id="statusFilter">
                <option value="">Filter by Status</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>Blocked</option>
            </select>
            <button class="add-btn" onclick="openModal('addModal')"><i class="fa-solid fa-plus"></i> Add Doctor</button>
        </div>

        <!-- ADD MODAL -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <h2>Add Doctor</h2>
                <label>ID</label>
                <input type="text" id="add_id" placeholder="#D003">
                <label>Name</label>
                <input type="text" id="add_name" placeholder="Dr. Name">
                <label>Phone</label>
                <input type="text" id="add_phone" placeholder="+91 12345 67890">
                <label>Email</label>
                <input type="text" id="add_email" placeholder="email@gmail.com">
                <label>Patients</label>
                <input type="number" id="add_patients" placeholder="0">
                <label>Status</label>
                <select id="add_status">
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>Blocked</option>
                </select>
                <button class="save-btn" onclick="addDoctor()">Add Doctor</button>
                <button class="close-btn" onclick="closeModal('addModal')">Close</button>
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-box">
            <h3>Doctors List</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Patients</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="doctorBody"></tbody>
            </table>
            <p id="noResult" style="display:none; font-size:15px; color:#888; padding-top:10px;">No results found</p>
        </div>
    </div>

    <!-- VIEW, EDIT, DELETE MODALS here (same as before) -->

    <script>
        let doctors = [{
                id: "#D001",
                name: "Dr. Priya Sharma",
                phone: "+91 98765 12345",
                email: "priya.sharma@gmail.com",
                patients: 240,
                status: "Active"
            },
            {
                id: "#D002",
                name: "Dr. Radhika Patel",
                phone: "+91 99876 54321",
                email: "radhika@gamil.com",
                patients: 110,
                status: "Inactive"
            }
        ];

        let selectedIndex = null;

        function renderTable() {
            const tbody = document.getElementById("doctorBody");
            tbody.innerHTML = "";
            const searchValue = document.getElementById("searchInput").value.toLowerCase();
            const statusValue = document.getElementById("statusFilter").value;
            let visibleCount = 0;

            doctors.forEach((d, index) => {
                const matchesSearch = d.name.toLowerCase().includes(searchValue) || d.email.toLowerCase().includes(searchValue);
                const matchesStatus = !statusValue || d.status === statusValue;

                if (matchesSearch && matchesStatus) {
                    visibleCount++;
                    const row = document.createElement("tr");
                    row.innerHTML = `
<td>${d.id}</td>
<td>${d.name}</td>
<td>${d.phone}</td>
<td>${d.email}</td>
<td>${d.patients}</td>
<td><span class="status-${d.status.toLowerCase()}">${d.status}</span></td>
<td>
    <a class="action-btn view-btn" onclick="openView(${index})"><i class="fa-solid fa-eye"></i> View</a>
    <a class="action-btn edit-btn" onclick="openEdit(${index})"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
    <a class="action-btn deletee-btn" onclick="openDelete(${index})"><i class="fa-solid fa-trash"></i></a>
</td>`;
                    tbody.appendChild(row);
                }
            });

            document.getElementById("noResult").style.display = visibleCount ? "none" : "block";
            document.getElementById("totalCount").innerText = doctors.length;
            document.getElementById("activeCount").innerText = doctors.filter(d => d.status === "Active").length;
        }

        function openModal(id) {
            document.getElementById(id).style.display = "flex";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        function addDoctor() {
            const id = document.getElementById("add_id").value.trim();
            const name = document.getElementById("add_name").value.trim();
            const phone = document.getElementById("add_phone").value.trim();
            const email = document.getElementById("add_email").value.trim();
            const patients = parseInt(document.getElementById("add_patients").value);
            const status = document.getElementById("add_status").value;

            if (!id || !name || !phone || !email || isNaN(patients)) {
                Swal.fire("Error", "Please fill all fields correctly", "error");
                return;
            }

            doctors.push({
                id,
                name,
                phone,
                email,
                patients,
                status
            });
            Swal.fire("Added!", "Doctor has been added", "success");

            document.getElementById("add_id").value = "";
            document.getElementById("add_name").value = "";
            document.getElementById("add_phone").value = "";
            document.getElementById("add_email").value = "";
            document.getElementById("add_patients").value = "";
            document.getElementById("add_status").value = "Active";

            closeModal("addModal");
            renderTable();
        }

        document.getElementById("searchInput").addEventListener("input", renderTable);
        document.getElementById("statusFilter").addEventListener("change", renderTable);

        renderTable();

        function openView(index) {
            selectedIndex = index;
            const d = doctors[index];

            Swal.fire({
                title: "Doctor Details",
                html: `
            <b>ID:</b> ${d.id}<br>
            <b>Name:</b> ${d.name}<br>
            <b>Phone:</b> ${d.phone}<br>
            <b>Email:</b> ${d.email}<br>
            <b>Patients:</b> ${d.patients}<br>
            <b>Status:</b> ${d.status}
        `,
                icon: "info"
            });
        }

        function openEdit(index) {
            selectedIndex = index;
            const d = doctors[index];

            Swal.fire({
                title: "Edit Doctor",
                html: `
            <input id="edit_id" class="swal2-input" value="${d.id}">
            <input id="edit_name" class="swal2-input" value="${d.name}">
            <input id="edit_phone" class="swal2-input" value="${d.phone}">
            <input id="edit_email" class="swal2-input" value="${d.email}">
            <input id="edit_patients" type="number" class="swal2-input" value="${d.patients}">
            <select id="edit_status" class="swal2-input">
                <option ${d.status=="Active"?"selected":""}>Active</option>
                <option ${d.status=="Inactive"?"selected":""}>Inactive</option>
                <option ${d.status=="Blocked"?"selected":""}>Blocked</option>
            </select>
        `,
                showCancelButton: true,
                confirmButtonText: "Save",
            }).then(result => {
                if (result.isConfirmed) {
                    doctors[index] = {
                        id: document.getElementById("edit_id").value,
                        name: document.getElementById("edit_name").value,
                        phone: document.getElementById("edit_phone").value,
                        email: document.getElementById("edit_email").value,
                        patients: parseInt(document.getElementById("edit_patients").value),
                        status: document.getElementById("edit_status").value
                    };

                    Swal.fire("Updated!", "Doctor details updated", "success");
                    renderTable();
                }
            });
        }

        function openDelete(index) {
            Swal.fire({
                title: "Are you sure?",
                text: "This doctor will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Delete"
            }).then((result) => {
                if (result.isConfirmed) {
                    doctors.splice(index, 1);
                    Swal.fire("Deleted!", "Doctor removed successfully", "success");
                    renderTable();
                }
            });
        }
    </script>
</body>

</html>