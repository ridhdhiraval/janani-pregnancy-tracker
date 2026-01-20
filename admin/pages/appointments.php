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
    <title>Appointments Management | Janani Admin</title>
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

        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-input,
        .filter-select,
        input[type="date"] {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

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

        .expand-row {
            background: #fafafa;
            padding: 15px;
            display: none;
            border-left: 3px solid #1DD1A1;
        }

        .status-pending {
            background: #ffe5b3;
            color: #a56b00;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
        }

        .status-confirmed {
            background: #c9eaff;
            color: #0066a3;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
        }

        .status-completed {
            background: #d1f2d1;
            color: #0d8c0d;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
        }

        .status-cancelled {
            background: #ffd9d9;
            color: #990000;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
        }

        .action-btn {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            color: #fff;
            margin-right: 4px;
            cursor: pointer;
        }

        .edit-btn {
            background: #6c63ff;
        }

        .approve-btn {
            background: #4caf50;
        }

        .reject-btn {
            background: #f44336;
        }

        .reschedule-btn {
            background: #ff9800;
        }

        .cancel-btn {
            background: #999;
        }

        .modal-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.25);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .modal-box {
            background: #fff;
            padding: 30px;
            width: 450px;
            border-radius: 16px;
            position: relative;
            animation: popup 0.3s ease;
            border-top: 5px solid #6c63ff;
        }

        .modal-box h3 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
        }

        .modal-box input,
        .modal-box select {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .modal-box button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .save-btn {
            background: #6c63ff;
        }

        .save-btn:hover {
            background: #4b46d1;
        }

        .close-btn {
            position: absolute;
            top: 12px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
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

        <div class="page-title">Appointments Management</div>
        <div class="page-sub">View, manage, and schedule appointments for users with doctors.</div>
        <!-- SUMMARY CARDS -->
        <div class="card-container">
            <div class="card" style="border-left:4px solid #1DD1A1;"> <i class="fa-solid fa-calendar-check"></i>
                <div class="count">5</div>
                <div class="label">Total Appointments</div>
            </div>
            <div class="card" style="border-left:4px solid #ff6b6b;"> <i class="fa-solid fa-clock"></i>
                <div class="count">3</div>
                <div class="label">Upcoming</div>
            </div>
            <div class="card" style="border-left:4px solid #57dafe;"> <i class="fa-solid fa-check"></i>
                <div class="count">1</div>
                <div class="label">Completed</div>
            </div>
            <div class="card" style="border-left:4px solid #fc9d5e;"> <i class="fa-solid fa-ban"></i>
                <div class="count">1</div>
                <div class="label">Cancelled</div>
            </div>
        </div>

        <div class="table-box">
            <h3>Appointments List</h3>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Doctor</th>
                        <th>Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="appointmentsBody"></tbody>
            </table>
        </div>

    </div>

    <!-- EDIT MODAL -->
    <div class="modal-bg" id="editModal">
        <div class="modal-box">
            <span class="close-btn" onclick="closeModal('editModal')">&times;</span>
            <h3>Edit Appointment</h3>
            <input type="text" id="edit_user" placeholder="User Name">
            <input type="text" id="edit_doctor" placeholder="Doctor Name">
            <input type="datetime-local" id="edit_datetime">
            <select id="edit_status">
                <option>Pending</option>
                <option>Confirmed</option>
                <option>Completed</option>
                <option>Cancelled</option>
            </select>
            <input type="text" id="edit_reason" placeholder="Reason">
            <input type="text" id="edit_notes" placeholder="Notes">
            <button class="save-btn" onclick="saveAppointment()">Save Changes</button>
        </div>
    </div>

    <script>
        let appointments = [{
                id: "1",
                user: "Pinal",
                doctor: "Dr. Anita Gupta",
                datetime: "2025-11-28T12:30:00",
                status: "Pending",
                reason: "Regular Checkup",
                notes: "Make sure you take your medicines",
                contact: "pinal0108@gmail.com"
            },
            {
                id: "2",
                user: "Rahul Verma",
                doctor: "Dr. Sunita Roy",
                datetime: "2025-11-30T14:00:00",
                status: "Confirmed",
                reason: "Follow-up",
                notes: "N/A",
                contact: "rahul@gmail.com"
            },
            {
                id: "3",
                user: "Anjali Mehta",
                doctor: "Dr. Vikram Singh",
                datetime: "2025-12-02T10:00:00",
                status: "Completed",
                reason: "Consultation",
                notes: "Patient recovering well",
                contact: "anjali.mehta@gmail.com"
            },
            {
                id: "4",
                user: "Shivam Kumar",
                doctor: "Dr. Neha Joshi",
                datetime: "2025-12-05T16:30:00",
                status: "Cancelled",
                reason: "Rescheduled",
                notes: "Patient requested reschedule",
                contact: "shivam.kumar@gmail.com"
            },
            {
                id: "5",
                user: "Sneha Reddy",
                doctor: "Dr. Arjun Nair",
                datetime: "2025-12-08T11:15:00",
                status: "Pending",
                reason: "Initial Consultation",
                notes: "Patient has a history of allergies",
                contact: "sneha.reddy@gmail.com"
            }

        ];

        let selectedIndex = null;

        function renderAppointments() {
            const tbody = document.getElementById("appointmentsBody");
            tbody.innerHTML = "";
            appointments.forEach((a, index) => {
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td><i class="fa fa-chevron-down toggle"></i></td>
                    <td>${a.id}</td>
                    <td>${a.user}</td>
                    <td>${a.doctor}</td>
                    <td>${new Date(a.datetime).toLocaleString('en-GB', { day:'2-digit', month:'short', year:'numeric', hour:'2-digit', minute:'2-digit' })}</td>
                    <td><span class="status-${a.status.toLowerCase()}">${a.status}</span></td>
                    <td>
                        <a class="action-btn edit-btn" onclick="openEdit(${index})"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <a class="action-btn approve-btn" onclick="approve(${index})">Approve</a>
                        <a class="action-btn reject-btn" onclick="reject(${index})">Reject</a>
                        <a class="action-btn reschedule-btn" onclick="reschedule(${index})">Reschedule</a>
                        <a class="action-btn cancel-btn" onclick="cancel(${index})">Cancel</a>
                    </td>
                `;
                tbody.appendChild(row);

                const expandRow = document.createElement("tr");
                expandRow.className = "expand-row";
                expandRow.innerHTML = `<td colspan="7">
                    <strong>Appointment Details:</strong><br>
                    User Contact: ${a.contact}<br>
                    Reason: ${a.reason}<br>
                    Notes: ${a.notes}
                </td>`;
                tbody.appendChild(expandRow);

                row.querySelector(".toggle").onclick = () => {
                    expandRow.style.display = expandRow.style.display === "table-row" ? "none" : "table-row";
                };
            });
        }

        function openEdit(index) {
            selectedIndex = index;
            const a = appointments[index];
            document.getElementById("edit_user").value = a.user;
            document.getElementById("edit_doctor").value = a.doctor;
            document.getElementById("edit_datetime").value = a.datetime;
            document.getElementById("edit_status").value = a.status;
            document.getElementById("edit_reason").value = a.reason;
            document.getElementById("edit_notes").value = a.notes;
            document.getElementById("editModal").style.display = "flex";
            document.body.style.overflow = "hidden";
        }

        function closeModal(id) {
            document.getElementById(id).style.display = "none";
            document.body.style.overflow = "auto";
        }

        function saveAppointment() {
            if (selectedIndex === null) return;
            const a = appointments[selectedIndex];
            a.user = document.getElementById("edit_user").value;
            a.doctor = document.getElementById("edit_doctor").value;
            a.datetime = document.getElementById("edit_datetime").value;
            a.status = document.getElementById("edit_status").value;
            a.reason = document.getElementById("edit_reason").value;
            a.notes = document.getElementById("edit_notes").value;
            closeModal("editModal");
            renderAppointments();
        }

        // Simple actions
        function approve(i) {
            appointments[i].status = "Confirmed";
            renderAppointments();
        }

        function reject(i) {
            appointments[i].status = "Cancelled";
            renderAppointments();
        }

        function reschedule(i) {
            const newTime = prompt("Enter new datetime (YYYY-MM-DDTHH:MM):", appointments[i].datetime);
            if (newTime) {
                appointments[i].datetime = newTime;
                renderAppointments();
            }
        }

        function cancel(i) {
            appointments[i].status = "Cancelled";
            renderAppointments();
        }

        renderAppointments();
    </script>

</body>

</html>