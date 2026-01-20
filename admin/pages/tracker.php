<?php
session_start();
if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit;
}
include('../inc/sidebar.php');
include '../inc/config.php';
include '../inc/db.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Tracker | Janani Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            background: #f7f8fb;
            font-family: 'Poppins', sans-serif;
        }

        .main {
            margin-left: 250px;
            padding: 40px 50px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
        }

        .page-sub {
            font-size: 15px;
            color: #777;
            margin-bottom: 25px;
        }

        /* FORCE REMOVE SIDEBAR HIGHLIGHT ONLY ON TRACKER PAGE */
        .sidebar .menu-item.active,
        .sidebar .menu-link.active,
        .sidebar .active-page,
        .sidebar li.active {
            background: none !important;
            color: #333 !important;
        }

        .sidebar .menu-item.active i,
        .sidebar .menu-link.active i,
        .sidebar li.active i {
            color: #333 !important;
        }


        /* Cards */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
        }

        .card i {
            font-size: 32px;
        }

        .card .count {
            font-size: 30px;
            font-weight: 600;
        }

        .card .label {
            color: #777;
        }

        /* Filters */
        .filter-bar {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
            align-items: center;
        }

        .search-input,
        .filter-select {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .search-input {
            flex: 1;
        }

        /* Table */
        .table-box {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th {
            background: #f1f3f7;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        /* Expand Row */
        .expand-row td {
            background: #fafafa;
            padding: 18px;
            border-left: 3px solid #1DD1A1;
        }

        .expand-row {
            display: none;
        }

        /* Action Buttons */
        .action-btn {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            color: #fff;
            cursor: pointer;
            margin-right: 4px;
            display: inline-block;
        }

        .view-btn {
            background: #6c63ff;
        }

        .edit-btn {
            background: #4caf50;
        }

        .notes-btn {
            background: #ff9800;
        }

        /* Modals */
        .modal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999 !important;

        }

        .modal-box {
            background: #fff;
            padding: 20px;
            width: 450px;
            border-radius: 12px;
            animation: pop 0.25s ease;
            position: relative;
            z-index: 100000 !important;

        }

        @keyframes pop {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .close-btn {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }
    </style>
</head>

<body>

    <div class="main">

        <div class="page-title">User Tracker</div>
        <div class="page-sub">Monitor and track pregnancy and postpartum progress for users in real-time.</div>

        <!-- Summary Cards -->
        <div class="card-container">
            <div class="card" style="border-left:4px solid #1DD1A1;">
                <i class="fa-solid fa-female"></i>
                <div class="count" id="pregnantCount">0</div>
                <div class="label">Pregnant Users</div>
            </div>
            <div class="card" style="border-left:4px solid #ff6b6b;">
                <i class="fa-solid fa-baby"></i>
                <div class="count" id="postpartumCount">0</div>
                <div class="label">Postpartum Users</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-bar">
            <input type="text" class="search-input" id="searchInput" placeholder="Search by user name...">

            <select class="filter-select" id="typeFilter">
                <option value="">Filter by User Type</option>
                <option value="Pregnant">Pregnant</option>
                <option value="Postpartum">Postpartum</option>
            </select>

            <select class="filter-select" id="stageFilter">
                <option value="">Filter by Trimester / Stage</option>
                <option value="First">First Trimester</option>
                <option value="Second">Second Trimester</option>
                <option value="Third">Third Trimester</option>
                <option value="Postpartum">Postpartum Stage</option>
            </select>
        </div>

        <!-- Table -->
        <div class="table-box">
            <h3>Tracker Entries</h3>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>User Type</th>
                        <th>Week</th>
                        <th>LMP / Delivery Date</th>
                        <th>Trimester / Stage</th>
                        <th>Weight</th>
                        <th>BP</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="trackerBody"></tbody>
            </table>
        </div>
    </div>

    <!-- View Modal -->
    <div class="modal-bg" id="viewModal">
        <div class="modal-box">
            <span class="close-btn" onclick="closeModal('viewModal')">&times;</span>
            <h3>View Tracker Details</h3>
            <p id="viewContent"></p>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal-bg" id="editModal">
        <div class="modal-box">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3>Edit Tracker Entry</h3>

            <label>Weight</label>
            <input type="text" id="editWeight" style="width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;"><br><br>

            <label>Blood Pressure</label>
            <input type="text" id="editBP" style="width:100%;padding:10px;border-radius:8px;border:1px solid #ccc;"><br><br>

            <label>Doctor Notes</label>
            <textarea id="editNotes" style="width:100%;height:80px;padding:10px;border-radius:8px;border:1px solid #ccc;"></textarea><br><br>

            <button class="action-btn edit-btn" onclick="saveEdit()">Save Changes</button>
        </div>
    </div>

    <!-- Notes Modal -->
    <div class="modal-bg" id="notesModal">
        <div class="modal-box">
            <span class="close-btn" onclick="closeModal('notesModal')">&times;</span>
            <h3>Add Notes</h3>
            <textarea id="notesInput" style="width:100%;height:100px;padding:10px;"></textarea><br><br>
            <button class="action-btn notes-btn" onclick="saveNotes()">Save Notes</button>
        </div>
    </div>

    <script>
        let trackerList = [{
                id: "#TR001",
                name: "Pinal",
                type: "Pregnant",
                week: 20,
                date: "05 May 2025",
                stage: "Second Trimester",
                weight: "60 kg",
                bp: "110/70",
                lastUpdated: "27 Nov 2025",
                symptoms: "Mild nausea",
                activity: "Normal",
                doctorNotes: "Continue prenatal vitamins"
            },

            {
                id: "#TR002",
                name: "Komal Mishra",
                type: "Postpartum",
                week: 2,
                date: "20 Nov 2025",
                stage: "Postpartum Stage",
                weight: "62 kg",
                bp: "115/75",
                lastUpdated: "28 Nov 2025",
                symptoms: "Tiredness",
                activity: "Low",
                doctorNotes: "Monitor diet"
            }
        ];

        let currentIndex = null;

        /* Render Tracker Table */
        function renderTracker() {
            const tbody = document.getElementById("trackerBody");
            tbody.innerHTML = "";

            const search = document.getElementById("searchInput").value.toLowerCase();
            const typeFilter = document.getElementById("typeFilter").value;
            const stageFilter = document.getElementById("stageFilter").value;

            let preg = 0,
                post = 0;

            trackerList.forEach((t, index) => {

                if (search && !t.name.toLowerCase().includes(search)) return;
                if (typeFilter && t.type !== typeFilter) return;
                if (stageFilter && !t.stage.toLowerCase().includes(stageFilter.toLowerCase())) return;

                if (t.type === "Pregnant") preg++;
                if (t.type === "Postpartum") post++;

                const row = document.createElement("tr");
                row.innerHTML = `
            <td><i class="fa fa-chevron-down toggle" style="cursor:pointer"></i></td>
            <td>${t.id}</td>
            <td>${t.name}</td>
            <td>${t.type}</td>
            <td>${t.week}</td>
            <td>${t.date}</td>
            <td>${t.stage}</td>
            <td>${t.weight}</td>
            <td>${t.bp}</td>
            <td>${t.lastUpdated}</td>
            <td>
                <span class="action-btn view-btn">View</span>
                <span class="action-btn edit-btn">Edit</span>
                <span class="action-btn notes-btn">Notes</span>
            </td>
        `;

                tbody.appendChild(row);

                const expand = document.createElement("tr");
                expand.className = "expand-row";
                expand.innerHTML = `
            <td colspan="11">
                <b>Symptoms:</b> ${t.symptoms}<br>
                <b>Activity:</b> ${t.activity}<br>
                <b>Doctor Notes:</b> ${t.doctorNotes}
            </td>
        `;
                tbody.appendChild(expand);

                /* Expand Toggle */
                row.querySelector(".toggle").onclick = () => {
                    expand.style.display = expand.style.display === "table-row" ? "none" : "table-row";
                };

                /* Button Actions */
                row.querySelector(".view-btn").onclick = () => {
                    openModal("viewModal");
                    document.getElementById("viewContent").innerHTML = `
                <b>Name:</b> ${t.name}<br>
                <b>Week:</b> ${t.week}<br>
                <b>Weight:</b> ${t.weight}<br>
                <b>BP:</b> ${t.bp}<br>
                <b>Notes:</b> ${t.doctorNotes}
            `;
                };

                row.querySelector(".edit-btn").onclick = () => {
                    currentIndex = index;
                    document.getElementById("editWeight").value = t.weight;
                    document.getElementById("editBP").value = t.bp;
                    document.getElementById("editNotes").value = t.doctorNotes;
                    openModal("editModal");
                };

                row.querySelector(".notes-btn").onclick = () => {
                    currentIndex = index;
                    document.getElementById("notesInput").value = t.doctorNotes;
                    openModal("notesModal");
                };
            });

            document.getElementById("pregnantCount").textContent = preg;
            document.getElementById("postpartumCount").textContent = post;
        }

        /* Open / Close Modal */
        function openModal(id) {
            document.getElementById(id).style.display = "flex";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        /* Save Edit */
        function saveEdit() {
            if (currentIndex !== null) {
                trackerList[currentIndex].weight = document.getElementById("editWeight").value;
                trackerList[currentIndex].bp = document.getElementById("editBP").value;
                trackerList[currentIndex].doctorNotes = document.getElementById("editNotes").value;
                closeModal("editModal");
                renderTracker();
            }
        }

        /* Save Notes */
        function saveNotes() {
            if (currentIndex !== null) {
                trackerList[currentIndex].doctorNotes = document.getElementById("notesInput").value;
                closeModal("notesModal");
                renderTracker();
            }
        }

        /* Filters */
        document.getElementById("searchInput").oninput = renderTracker;
        document.getElementById("typeFilter").onchange = renderTracker;
        document.getElementById("stageFilter").onchange = renderTracker;

        /* Init */
        renderTracker();
    </script>

</body>

</html>