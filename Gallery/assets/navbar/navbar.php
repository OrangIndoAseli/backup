<nav class="navbar navbar-expand-lg navbar-dark bg-dark wrapper">
    <a class="navbar-brand brand">
        <span class="firstname">My</span><span class="lastname">Gallery</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse navigation" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../../app/views/home_views.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../app/views/upload_views.php">Postingan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../app/views/AlbumKU_views.php">AlbumKU</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../app/views/about_views.php">About</a>
            </li>
            <li class="nav-item profile position-relative">
                <a class="nav-link" href="#" onclick="toggleDropdown(event)">
                    <i class="fas fa-user"></i>
                </a>
                <!-- Dropdown menu for Profile -->
                <ul class="profile-dropdown">
                    <li><a href="?action=logout">Logout</a></li>
                    <li><a href="edit_profile.php">Edit Profile</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- JavaScript for Navbar Dropdown -->
<script>
   function toggleDropdown(event) {
    event.preventDefault();
    const profileItem = event.currentTarget.closest('.profile');
    profileItem.classList.toggle('active');
}

// Close dropdown if clicked outside
document.addEventListener('click', function(event) {
    const profileItem = document.querySelector('.nav-item.profile');
    if (profileItem && !profileItem.contains(event.target)) {
        profileItem.classList.remove('active');
    }
});

</script>
