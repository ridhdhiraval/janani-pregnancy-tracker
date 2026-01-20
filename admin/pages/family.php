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
    <title>Family Users | Janani Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { margin: 0; font-family: 'Poppins', sans-serif; background: #eef2f7; }
        .main { margin-left: 250px; padding: 40px 50px; }
        .page-title { font-size: 30px; font-weight: 700; color: #1a1a1a; }
        .page-sub { font-size: 15px; color: #6f6f6f; margin-top: 5px; margin-bottom: 25px; }

        .card-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 25px; margin-bottom: 35px; }
        .card { background: linear-gradient(135deg, #fff, #f5f7fb); padding: 28px; border-radius: 18px; box-shadow: 0 8px 22px rgba(0, 0, 0, 0.08); border: 1px solid #f0f0f0; transition: 0.25s ease; }
        .card:hover { transform: translateY(-6px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12); }
        .card i { color: #2173ffff; font-size: 38px; margin-bottom: 12px; }
        .card:nth-child(2) i { color: #1DD1A1; }
        .card .count { font-size: 34px; font-weight: 700; margin-bottom: 4px; }
        .card .label { color: #6f6f6f; font-size: 15px; }

        .filter-bar { display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; align-items: center; }
        .search-input { flex: 1; padding: 12px 14px; border-radius: 10px; border: 1px solid #d1d5db; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.06); font-size: 14px; }
        .filter-select { flex: 0 0 200px; padding: 12px 14px; border-radius: 10px; border: 1px solid #d1d5db; background: #fff; font-size: 14px; }
        .add-btn { flex: 0 0 auto; padding: 12px 20px; background-color: #2173ffff; color: white; border: none; border-radius: 12px; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 6px; }
        .add-btn:hover { background-color: #005bf7ff; }

        .table-box { background: #fff; padding: 30px; border-radius: 18px; box-shadow: 0 6px 20px rgba(0,0,0,0.08); border:1px solid #e6e6e6; }
        .table-box h3 { font-size: 20px; margin:0 0 15px 0; font-weight:600; color:#222; }
        table { width:100%; border-collapse: separate; border-spacing:0; border-radius:12px; overflow:hidden; margin-top:15px; }
        th { padding:14px; background:#2173ffff; color:white; text-align:left; font-size:13px; letter-spacing:0.4px; text-transform:uppercase; }
        td { padding:14px; background:#fff; border-bottom:1px solid #f0f0f0; font-size:14px; transition:0.3s; }
        tbody tr:nth-child(odd) td { background:#f9f9f9; }
        tbody tr:nth-child(even) td { background:#ffffff; }
        tbody tr:hover td { background:#e6f0ff; }

        .status-active, .status-inactive, .status-blocked { padding:6px 12px; border-radius:20px; font-size:12px; font-weight:600; }
        .status-active { background:#d4f7d4; color:#0a7f0a; }
        .status-inactive { background:#ffe8b3; color:#b37a00; }
        .status-blocked { background:#ffd2d2; color:#b30000; }

        .action-btn { padding:8px 14px; border-radius:10px; font-size:13px; font-weight:500; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
        .edit-btn { color:#ffae00; }
        .edit-btn:hover { color:#e89c00; }
        .deletee-btn { color:#f80c0cff; }
        .deletee-btn:hover { color:#d93636; }

        .modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(40,40,40,0.15); justify-content:center; align-items:center; z-index:2000; }
        .modal-content { background:#fff; width:460px; padding:32px; border-radius:20px; box-shadow:0 15px 35px rgba(0,0,0,0.18); animation: popup 0.3s ease; border-top:5px solid #2173ffff; }
        .modal-content h2 { font-size:22px; font-weight:700; margin-bottom:20px; color:#2173ffff; text-align:center; }
        .modal-content label { font-weight:500; margin-bottom:6px; display:block; color:#222; }
        .modal-content input, .modal-content select { width:100%; padding:12px 14px; border-radius:12px; border:1px solid #d1d5db; margin-bottom:16px; font-size:14px; background:#fefefe; }
        .modal-content input:focus, .modal-content select:focus { border-color:#2173ffff; box-shadow:0 0 0 4px rgba(33,115,255,0.15); outline:none; background:#fff; }

        .save-btn, .close-btn, .delete-btn { width:100%; padding:12px; border-radius:12px; font-weight:600; font-size:14px; color:white; margin-bottom:12px; cursor:pointer; }
        .save-btn { background:#2173ffff; border:none; }
        .save-btn:hover { background:#005bf7ff; }
        .close-btn { background:#a7abafff; border:none; }
        .close-btn:hover { background:#656c72ff; }
        .delete-btn { background:#f80c0cff; border:none; }
        .delete-btn:hover { background:#d93636; }

        @keyframes popup { 0%{transform:scale(0.8);opacity:0;} 100%{transform:scale(1);opacity:1;} }
    </style>
</head>

<body>
    <div class="main">
        <div class="page-title">Family Users</div>
        <div class="page-sub">Manage all family members linked to pregnant and postpartum women</div>

        <div class="card-container">
            <div class="card" style="border-left:5px solid #2173ffff;">
                <i class="fa-solid fa-people-group"></i>
                <div class="count" id="totalCount">0</div>
                <div class="label">Total Family Members</div>
            </div>
            <div class="card" style="border-left:5px solid #1DD1A1;">
                <i class="fa-solid fa-user-check"></i>
                <div class="count" id="activeCount">0</div>
                <div class="label">Active Members</div>
            </div>
        </div>

        <div class="filter-bar">
            <input type="text" class="search-input" placeholder="Search family members..." id="searchInput">
            <select class="filter-select" id="relationFilter">
                <option value="">Filter by Relation</option>
                <option>Husband</option>
                <option>Mother</option>
                <option>Father</option>
                <option>Sister</option>
                <option>Brother</option>
            </select>
            <select class="filter-select" id="statusFilter">
                <option value="">Filter by Status</option>
                <option>Active</option>
                <option>Inactive</option>
                <option>Blocked</option>
            </select>
            <button class="add-btn" onclick="openModal('addModal')"><i class="fa-solid fa-plus"></i> Add Member</button>
        </div>

        <div class="table-box">
            <h3>Family Members List</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Relation</th>
                        <th>Linked Mother</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="familyBody"></tbody>
            </table>
            <p id="noResult" style="display:none; font-size:15px; color:#888; padding-top:10px;">No results found</p>
        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <h2>Add Family Member</h2>
            <label>ID</label>
            <input type="text" id="add_id" placeholder="#F001">
            <label>Name</label>
            <input type="text" id="add_name" placeholder="Name">
            <label>Relation</label>
            <select id="add_relation">
                <option>Husband</option>
                <option>Mother</option>
                <option>Father</option>
                <option>Sister</option>
                <option>Brother</option>
            </select>
            <label>Linked Mother</label>
            <input type="text" id="add_linkedMother" placeholder="Mother Name">
            <label>Status</label>
            <select id="add_status">
                <option>Active</option>
                <option>Inactive</option>
                <option>Blocked</option>
            </select>
            <button class="save-btn" onclick="addMember()">Add Member</button>
            <button class="close-btn" onclick="closeModal('addModal')">Close</button>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2>Edit Family Member</h2>
            <label>ID</label>
            <input type="text" id="edit_id" disabled>
            <label>Name</label>
            <input type="text" id="edit_name">
            <label>Relation</label>
            <select id="edit_relation">
                <option>Husband</option>
                <option>Mother</option>
                <option>Father</option>
                <option>Sister</option>
                <option>Brother</option>
            </select>
            <label>Linked Mother</label>
            <input type="text" id="edit_linkedMother">
            <label>Status</label>
            <select id="edit_status">
                <option>Active</option>
                <option>Inactive</option>
                <option>Blocked</option>
            </select>
            <button class="save-btn" onclick="saveMember()">Save Changes</button>
            <button class="close-btn" onclick="closeModal('editModal')">Close</button>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h2>Delete Family Member</h2>
            <p>Are you sure you want to delete <span id="delete_name"></span>?</p>
            <button class="delete-btn" onclick="confirmDelete()">Delete</button>
            <button class="close-btn" onclick="closeModal('deleteModal')">Cancel</button>
        </div>
    </div>

    <script>
        let family = [
            {id:"#F001", name:"Diya Tandel", relation:"Sister", linkedMother:"Sita Sharma", status:"Active"},
            {id:"#F002", name:"Ridhdhi Raval", relation:"Mother", linkedMother:"Pinal", status:"Inactive"},
            {id:"#F003", name:"Karan Patel", relation:"Husband", linkedMother:"Anita Desai", status:"Active"},
            {id:"#F004", name:"Meera", relation:"Sister", linkedMother:"Anita Desai", status:"Active"},
            {id:"#F005", name:"Rajesh", relation:"Father", linkedMother:"Sita Sharma", status:"Blocked"}
        ];
        let selectedIndex = null;

        function renderTable() {
            const tbody = document.getElementById("familyBody");
            tbody.innerHTML = "";
            const searchValue = document.getElementById("searchInput").value.toLowerCase();
            const relationValue = document.getElementById("relationFilter").value;
            const statusValue = document.getElementById("statusFilter").value;
            let visibleCount = 0;

            family.forEach((f,index)=>{
                const matchesSearch = f.name.toLowerCase().includes(searchValue) || f.linkedMother.toLowerCase().includes(searchValue);
                const matchesRelation = !relationValue || f.relation===relationValue;
                const matchesStatus = !statusValue || f.status===statusValue;

                if(matchesSearch && matchesRelation && matchesStatus){
                    visibleCount++;
                    const row = document.createElement("tr");
                    row.innerHTML = `
                    <td>${f.id}</td>
                    <td>${f.name}</td>
                    <td>${f.relation}</td>
                    <td>${f.linkedMother}</td>
                    <td><span class="status-${f.status.toLowerCase()}">${f.status}</span></td>
                    <td>
                        <a class="action-btn edit-btn" onclick="openEdit(${index})"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                        <a class="action-btn deletee-btn" onclick="openDelete(${index})"><i class="fa-solid fa-trash"></i></a>
                    </td>
                    `;
                    tbody.appendChild(row);
                }
            });

            document.getElementById("noResult").style.display = visibleCount? "none":"block";
            document.getElementById("totalCount").innerText = family.length;
            document.getElementById("activeCount").innerText = family.filter(f=>f.status==="Active").length;
        }

        function openModal(id){ document.getElementById(id).style.display="flex"; document.body.style.overflow="hidden"; }
        function closeModal(id){ document.getElementById(id).style.display="none"; document.body.style.overflow="auto"; }

        function addMember(){
            const id = document.getElementById("add_id").value.trim();
            const name = document.getElementById("add_name").value.trim();
            const relation = document.getElementById("add_relation").value;
            const linkedMother = document.getElementById("add_linkedMother").value.trim();
            const status = document.getElementById("add_status").value;

            if(!id||!name||!linkedMother){ Swal.fire("Error","Fill all fields","error"); return; }

            family.push({id,name,relation,linkedMother,status});
            Swal.fire("Added!","Family member added","success");
            document.getElementById("add_id").value="";
            document.getElementById("add_name").value="";
            document.getElementById("add_linkedMother").value="";
            document.getElementById("add_relation").value="Husband";
            document.getElementById("add_status").value="Active";
            closeModal("addModal");
            renderTable();
        }

        function openEdit(index){
            selectedIndex = index;
            const f = family[index];
            document.getElementById("edit_id").value = f.id;
            document.getElementById("edit_name").value = f.name;
            document.getElementById("edit_relation").value = f.relation;
            document.getElementById("edit_linkedMother").value = f.linkedMother;
            document.getElementById("edit_status").value = f.status;
            openModal("editModal");
        }

        function saveMember(){
            if(selectedIndex===null) return;
            family[selectedIndex].name = document.getElementById("edit_name").value.trim();
            family[selectedIndex].relation = document.getElementById("edit_relation").value;
            family[selectedIndex].linkedMother = document.getElementById("edit_linkedMother").value.trim();
            family[selectedIndex].status = document.getElementById("edit_status").value;
            Swal.fire("Updated!","Family member updated","success");
            closeModal("editModal");
            renderTable();
        }

        function openDelete(index){
            selectedIndex = index;
            document.getElementById("delete_name").innerText = family[index].name;
            openModal("deleteModal");
        }

        function confirmDelete(){
            if(selectedIndex!==null){
                family.splice(selectedIndex,1);
                Swal.fire("Deleted!","Family member deleted","success");
                closeModal("deleteModal");
                renderTable();
            }
        }

        document.getElementById("searchInput").addEventListener("input", renderTable);
        document.getElementById("relationFilter").addEventListener("change", renderTable);
        document.getElementById("statusFilter").addEventListener("change", renderTable);

        renderTable();
    </script>
</body>
</html>
