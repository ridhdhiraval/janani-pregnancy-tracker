<?php
// Minimal PHP needed for the file extension, though the content is mostly static HTML/CSS/JS now.
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Sign In</title>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
/* --- Simplified CSS for Sign In Only --- */
:root {
    --primary-color: #F8B4C3;
    --secondary-color: #FFC0CB;
    --white: #fff;
    --gray: #efefef;
    --gray-2: #757575;
    --text-color: #333;
    --shadow: rgba(0,0,0,0.25) 0px 8px 24px;
}

@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap');

* { font-family:'Poppins',sans-serif; margin:0; padding:0; box-sizing:border-box; }
body, html { 
    height:100vh; 
    overflow: hidden; /* Keep background static */
    background-color: var(--secondary-color); /* Use a light background color */
    display: flex;
    justify-content: center;
    align-items: center;
    color: var(--text-color);
}

.auth-container { 
    position: relative; 
    min-height: 100vh; 
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-image: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
}

.login-panel {
    background-color: var(--white);
    padding: 3rem 2rem;
    border-radius: 2rem;
    width: 100%;
    max-width: 400px;
    box-shadow: var(--shadow);
    text-align: center;
    transition: transform 0.5s ease-in-out;
}

.login-panel h2 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 2rem;
    color: var(--primary-color);
}

.input-group { 
    position:relative; 
    width:100%; 
    margin:1.5rem 0; 
}
.input-group i { 
    position:absolute; 
    top:50%; 
    left:1rem; 
    transform:translateY(-50%); 
    font-size:1.4rem; 
    color:var(--gray-2);
}
.input-group input { 
    width:100%; 
    padding:1rem 3rem; 
    font-size:1rem; 
    background-color:var(--gray); 
    border-radius:.75rem; /* Slightly more rounded */
    border:0.125rem solid var(--gray); 
    outline:none; 
    transition: border 0.3s;
}
.input-group input:focus { 
    border:0.125rem solid var(--primary-color); 
    background-color: var(--white);
}

.login-panel button { 
    cursor:pointer; 
    width:100%; 
    padding:1rem 0; /* Increased padding */
    border-radius:.75rem; 
    border:none; 
    background-color:var(--primary-color); 
    color:var(--white); 
    font-size:1.2rem; 
    font-weight: 600;
    transition: background-color 0.3s, transform 0.3s;
    margin-top: 1rem;
    box-shadow: 0 4px 15px rgba(248, 180, 195, 0.6);
}

.login-panel button:hover {
    background-color: #e8a3b5; /* Darker primary color on hover */
    transform: translateY(-2px);
}

.login-panel p { 
    margin-top: 2rem; 
    font-size:.9rem; 
    color: var(--gray-2);
}

/* Optional: Add a subtle animation to the panel on load */
@keyframes fadeInScale {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}

.login-panel {
    animation: fadeInScale 0.6s ease-out forwards;
}

</style>
</head>
<body>

<div class="auth-container">
    <div class="login-panel">
        <form id="signinForm">
            <h2>Doctor Sign In</h2>
            
            <div class="input-group">
                <i class='bx bxs-id-card'></i>
                <input type="text" placeholder="Registration Number" name="reg_no_in" required>
            </div>
            
            <div class="input-group">
                <i class='bx bxs-lock-alt'></i>
                <input type="password" placeholder="Password" name="password_in" required>
            </div>
            
            <button type="submit">Sign In</button>
        </form>
        
        <p>If you don't have an account, please contact your administrator.</p>
    </div>
</div>

<script>
    // Simple form submission handler
    document.getElementById('signinForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        
        // --- Placeholder Sign In Logic ---
        const regNo = this.querySelector('[name="reg_no_in"]').value;
        const password = this.querySelector('[name="password_in"]').value;

        // In a real application, you would send these credentials to a server-side API (e.g., via fetch)
        console.log("Attempting sign in with Reg No:", regNo);

        // Simple check (for demo purposes)
        if (regNo.length > 0 && password.length > 0) {
            alert(`Doctor with Reg No. ${regNo} signed in successfully!`);
            // Redirect to dashboard on success: window.location.href = 'doc_dashboard.php';
        } else {
            alert("Please enter both registration number and password.");
        }

        this.reset();
    });
</script>
</body>
</html>