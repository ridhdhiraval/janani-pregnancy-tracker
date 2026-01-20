<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
        header("Location: ../login.php");
        exit;
}
include('../inc/sidebar.php');
include '../inc/config.php';
include '../inc/db.php';

// Fetch users from database
$sql = "SELECT 
            u.id,
            CONCAT(up.first_name, ' ', COALESCE(up.last_name, '')) AS full_name,
            up.phone,
            u.email,
            CASE 
                WHEN p.status = 'active' THEN 'Pregnant'
                WHEN p.status = 'completed' THEN 'Postpartum'
                ELSE 'Unknown'
            END AS user_type,
            CASE 
                WHEN u.status = 1 THEN 'Active'
                ELSE 'Inactive'
            END AS user_status,
            u.updated_at
        FROM users u
        LEFT JOIN user_profiles up ON u.id = up.user_id
        LEFT JOIN pregnancies p ON u.id = p.user_id
        ORDER BY u.id DESC";

$result = mysqli_query($conn, $sql);
// Count Pregnant Users
$pregQuery = "SELECT COUNT(*) AS total_pregnant 
              FROM pregnancies 
              WHERE status = 'active'";
$pregResult = mysqli_query($conn, $pregQuery);
$pregData = mysqli_fetch_assoc($pregResult);
$pregnantCount = $pregData['total_pregnant'];

// Count Postpartum Users
$postQuery = "SELECT COUNT(*) AS total_postpartum 
              FROM pregnancies 
              WHERE status = 'completed'";
$postResult = mysqli_query($conn, $postQuery);
$postData = mysqli_fetch_assoc($postResult);
$postpartumCount = $postData['total_postpartum'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <title>Users | Janani Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <style>
                /* ---------------- GLOBAL PAGE LAYOUT ---------------- */
                body {
                        margin: 0;
                        font-family: 'Poppins', sans-serif;
                        background: #eef2f7;
                }

                /* MAIN CONTENT */
                .main {
                        margin-left: 250px;
                        padding: 40px 50px;
                }

                /* PAGE TITLES */
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

                /* TOP CARDS */
                .card-container {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                        gap: 25px;
                        margin-bottom: 35px;
                }

                .card {
                        background: linear-gradient(135deg, #ffffff, #f5f7fb);
                        padding: 28px;
                        border-radius: 18px;
                        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08);
                        transition: 0.25s ease;
                        border: 1px solid #f0f0f0;
                }

                .card:hover {
                        transform: translateY(-6px);
                        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
                }

                .card i {
                        font-size: 38px;
                        margin-bottom: 12px;
                        color: #FF6B6B;
                }

                .card i.fa-baby {
                        color: #FF9F43;
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

                /* FILTER BAR */
                .filter-bar {
                        display: flex;
                        gap: 15px;
                        margin-bottom: 25px;
                        flex-wrap: wrap;
                }

                .search-input,
                .filter-select {
                        padding: 12px 14px;
                        border-radius: 10px;
                        border: 1px solid #d1d5db;
                        background: #ffffff;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
                        transition: 0.2s;
                        font-size: 14px;
                }

                .search-input:focus,
                .filter-select:focus {
                        border-color: #2173ffff;
                        box-shadow: 0 0 0 4.6px rgba(33, 115, 255, 0.15);
                }

                /* TABLE WRAPPER */
                .table-box {
                        background: #ffffff;
                        padding: 30px;
                        border-radius: 18px;
                        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
                        border: 1px solid #e6e6e6;
                }

                .table-box h3 {
                        font-size: 20px;
                        margin: 0;
                        font-weight: 600;
                        margin-bottom: 15px;
                        color: #222;
                }

                /* TABLE STYLES */
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

                /* STATUS BADGES */
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

                /* ACTION BUTTONS */
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

                .action-btn i {
                        font-size: 14px;
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

                /* ---------------- MODALS ENHANCED ---------------- */
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
                        /* slightly wider */
                        padding: 32px;
                        /* more padding */
                        border-radius: 20px;
                        /* more rounded */
                        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18);
                        /* stronger shadow */
                        animation: popup 0.3s ease;
                        /* smoother animation */
                        border-top: 5px solid #2173ffff;
                        /* added theme accent on top */

                }

                .modal-content h2 {
                        font-size: 22px;
                        font-weight: 700;
                        margin-bottom: 20px;
                        color: #2173ffff;
                        /* header color theme accent */
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
                        transition: 0.25s, box-shadow 0.25s;
                        font-size: 14px;
                        background-color: #fefefe;
                }

                .modal-content input:focus,
                .modal-content select:focus {
                        border-color: #2173ffff;
                        box-shadow: 0 0 0 4px rgba(33, 115, 255, 0.15);
                        outline: none;
                        background-color: #ffffff;

                }

                /* BUTTONS INSIDE MODALS */
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
                        border: none;
                }

                .close-btn {
                        background: #a7abafff;
                        border: none;
                }

                .close-btn:hover {
                        background: #656c72ff;
                        border: none;
                }

                .delete-btn {
                        background: #2173ffff;
                        border: none;
                }

                .delete-btn:hover {
                        background: #005bf7ff;
                        border: none;
                }

                /* POPUP ANIMATION */
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

                <div class="page-title">Users Management</div>
                <div class="page-sub">Manage all pregnant and postpartum users</div>

                <!-- TOP CARDS -->
                <div class="card-container">
                        <div class="card" style="border-left: 5px solid #FF6B6B;">
                                <i class="fa-solid fa-heart-pulse"></i>
                                <div class="count"><?php echo $pregnantCount; ?></div>
                                <div class="label">Pregnant Users</div>
                        </div>
                        <div class="card" style="border-left: 5px solid #FF9F43;">
                                <i class="fa-solid fa-baby"></i>
                                <div class="count"><?php echo $postpartumCount; ?></div>
                                <div class="label">Postpartum Users</div>
                        </div>
                </div>

                <!-- FILTER BAR -->
                <div class="filter-bar">
                        <input type="text" class="search-input" placeholder="Search users...">
                        <select class="filter-select">
                                <option value="">Filter by Type</option>
                                <option>Pregnant</option>
                                <option>Postpartum</option>
                        </select>
                        <select class="filter-select">
                                <option value="">Filter by Status</option>
                                <option>Active</option>
                                <option>Inactive</option>
                                <option>Blocked</option>
                        </select>
                </div>

                <!-- USERS TABLE -->
                <div class="table-box">
                        <h3>All Users</h3>

                        <table>
                                <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>User Type</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Action</th>
                                </tr>
                                <tr id="noResultsRow" style="display:none;">
                                        <td colspan="8" style="text-align:center; padding:15px; font-size:16px; color:#888;">
                                                No results found
                                        </td>
                                </tr>

                                <tr id="noResultsRow" style="display:none;">
                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                        <td>#<?php echo $row['id']; ?></td>
                                        <td><?php echo $row['full_name']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['user_type']; ?></td>

                                        <td>
                                                <?php
                                                $statusClass = strtolower($row['user_status']) === 'active' ? 'status-active' : 'status-inactive';
                                                ?>
                                                <span class="<?php echo $statusClass; ?>">
                                                        <?php echo $row['user_status']; ?>
                                                </span>
                                        </td>

                                        <td><?php echo $row['updated_at']; ?></td>

                                        <td>
                                                <a class="action-btn view-btn" onclick="viewUser(this)">
                                                        <i class="fa-solid fa-eye"></i> View
                                                </a>
                                                <a class="action-btn edit-btn" onclick="editUser(this)">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                </a>
                                                <a class="action-btn deletee-btn" onclick="deleteUser(this)">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                        </td>
                                </tr>
                        <?php endwhile; ?>


                        </table>
                </div>
        </div>

        <!-- VIEW MODAL -->
        <div id="viewModal" class="modal">
                <div class="modal-content">
                        <h2>View User</h2>
                        <p><strong>ID:</strong> <span id="view_id"></span></p>
                        <p><strong>Name:</strong> <span id="view_name"></span></p>
                        <p><strong>Phone:</strong> <span id="view_phone"></span></p>
                        <p><strong>Email:</strong> <span id="view_email"></span></p>
                        <p><strong>User Type:</strong> <span id="view_type"></span></p>
                        <p><strong>Status:</strong> <span id="view_status"></span></p>
                        <p><strong>Last Login:</strong> <span id="view_login"></span></p>
                        <button onclick="closeModal('viewModal')" class="close-btn">Close</button>
                </div>
        </div>

        <!-- EDIT MODAL -->
        <div id="editModal" class="modal">
                <div class="modal-content">
                        <h2>Edit User</h2>
                        <label>Name</label>
                        <input type="text" id="edit_name">
                        <label>Phone</label>
                        <input type="text" id="edit_phone">
                        <label>Email</label>
                        <input type="text" id="edit_email">
                        <label>User Type</label>
                        <select id="edit_type">
                                <option>Pregnant</option>
                                <option>Postpartum</option>
                        </select>
                        <label>Status</label>
                        <select id="edit_status">
                                <option>Active</option>
                                <option>Inactive</option>
                                <option>Blocked</option>
                        </select>
                        <button onclick="saveUser()" class="save-btn">Save Changes</button>
                        <button onclick="closeModal('editModal')" class="close-btn">Cancel</button>
                </div>
        </div>

        <!-- DELETE MODAL -->
        <div id="deleteModal" class="modal">
                <div class="modal-content">
                        <h2>Delete User</h2>
                        <p>Are you sure you want to delete <span id="delete_name"></span>?</p>
                        <button onclick="confirmDelete()" class="delete-btn">Delete</button>
                        <button onclick="closeModal('deleteModal')" class="close-btn">Cancel</button>
                </div>
        </div>

        <script>
                let selectedRow = null;

                /* ---------------- MODALS ---------------- */
                function openModal(id) {
                        document.getElementById(id).style.display = "flex";
                }

                function closeModal(id) {
                        document.getElementById(id).style.display = "none";
                }

                /* ---------------- VIEW USER ---------------- */
                function viewUser(row) {
                        let data = row.parentElement.parentElement.children;

                        document.getElementById("view_id").innerText = data[0].innerText;
                        document.getElementById("view_name").innerText = data[1].innerText;
                        document.getElementById("view_phone").innerText = data[2].innerText;
                        document.getElementById("view_email").innerText = data[3].innerText;
                        document.getElementById("view_type").innerText = data[4].innerText;
                        document.getElementById("view_status").innerText = data[5].innerText;
                        document.getElementById("view_login").innerText = data[6].innerText;

                        openModal("viewModal");
                }

                /* ---------------- EDIT USER ---------------- */
                function editUser(row) {
                        selectedRow = row.parentElement.parentElement;
                        let data = selectedRow.children;

                        document.getElementById("edit_name").value = data[1].innerText;
                        document.getElementById("edit_phone").value = data[2].innerText;
                        document.getElementById("edit_email").value = data[3].innerText;
                        document.getElementById("edit_type").value = data[4].innerText.trim();
                        document.getElementById("edit_status").value = data[5].innerText.trim();

                        openModal("editModal");
                }

                function saveUser() {
                        if (!selectedRow) return;

                        selectedRow.children[1].innerText = document.getElementById("edit_name").value;
                        selectedRow.children[2].innerText = document.getElementById("edit_phone").value;
                        selectedRow.children[3].innerText = document.getElementById("edit_email").value;
                        selectedRow.children[4].innerText = document.getElementById("edit_type").value;

                        let newStatus = document.getElementById("edit_status").value;

                        selectedRow.children[5].innerHTML = `
        <span class="status-${newStatus.toLowerCase()}">${newStatus}</span>
    `;

                        Swal.fire({
                                icon: "success",
                                title: "Updated!",
                                text: "User updated successfully",
                                timer: 1500
                        });

                        closeModal("editModal");
                }

                /* ---------------- DELETE USER ---------------- */
                function deleteUser(row) {
                        selectedRow = row.parentElement.parentElement;
                        document.getElementById("delete_name").innerText = selectedRow.children[1].innerText;
                        openModal("deleteModal");
                }

                function confirmDelete() {
                        selectedRow.remove();
                        closeModal("deleteModal");

                        Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: "User has been removed.",
                                timer: 1500
                        });
                }

                /* ----------------------------------------------------
                   🔍 LIVE SEARCH + Filter Type + Filter Status
                ------------------------------------------------------*/

                const searchInput = document.querySelector(".search-input");
                const typeFilter = document.querySelectorAll(".filter-select")[0];
                const statusFilter = document.querySelectorAll(".filter-select")[1];

                const allRows = document.querySelectorAll("table tr");
                const noRow = document.getElementById("noResultsRow");

                // REAL data rows only (skip header + skip noResultRow)
                const dataRows = Array.from(allRows).filter(r =>
                        r.id !== "noResultsRow" && r.querySelector("td")
                );

                function applyFilters() {
                        const searchValue = searchInput.value.toLowerCase();
                        const typeValue = typeFilter.value.toLowerCase();
                        const statusValue = statusFilter.value.toLowerCase();

                        let visibleCount = 0;

                        dataRows.forEach(row => {
                                const cols = row.children;

                                const name = cols[1].innerText.toLowerCase();
                                const phone = cols[2].innerText.toLowerCase();
                                const email = cols[3].innerText.toLowerCase();
                                const type = cols[4].innerText.toLowerCase();
                                const status = cols[5].innerText.toLowerCase();

                                let matchesSearch =
                                        name.includes(searchValue) ||
                                        phone.includes(searchValue) ||
                                        email.includes(searchValue);

                                let matchesType = !typeValue || type === typeValue;
                                let matchesStatus = !statusValue || status.includes(statusValue);

                                if (matchesSearch && matchesType && matchesStatus) {
                                        row.style.display = "";
                                        visibleCount++;
                                } else {
                                        row.style.display = "none";
                                }
                        });

                        // 🔥 Show/Hide "No Results Found"
                        if (visibleCount === 0) {
                                noRow.style.display = "";
                        } else {
                                noRow.style.display = "none";
                        }
                }

                searchInput.addEventListener("input", applyFilters);
                typeFilter.addEventListener("change", applyFilters);
                statusFilter.addEventListener("change", applyFilters);
        </script>
</body>

</html>