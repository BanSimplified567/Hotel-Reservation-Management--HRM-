<div class="edit-container">
    <h1>Edit Admin Profile</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Profile Image Upload -->
    <div class="image-upload">
        <img src="<?= !empty($user['profile_image']) ? 'uploads/profiles/' . htmlspecialchars($user['profile_image']) : 'assets/default-avatar.png' ?>"
             alt="Profile" class="profile-preview" id="profilePreview">
        <br>
        <input type="file" id="profileImage" accept="image/*" style="display: none;">
        <button type="button" class="btn" onclick="document.getElementById('profileImage').click()">
            Change Profile Picture
        </button>
        <p><small>Max file size: 2MB. Allowed: JPG, PNG, GIF</small></p>
    </div>

    <!-- Profile Update Form -->
    <form method="POST" action="?route=admin/profile&sub_action=update">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="first_name">First Name *</label>
            <input type="text" id="first_name" name="first_name"
                   value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name *</label>
            <input type="text" id="last_name" name="last_name"
                   value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone"
                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city"
                       value="<?= htmlspecialchars($user['city'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="state">State/Province</label>
                <input type="text" id="state" name="state"
                       value="<?= htmlspecialchars($user['state'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" id="country" name="country"
                       value="<?= htmlspecialchars($user['country'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input type="text" id="postal_code" name="postal_code"
                       value="<?= htmlspecialchars($user['postal_code'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role" disabled>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= $role ?>" <?= ($user['role'] ?? '') === $role ? 'selected' : '' ?>>
                        <?= ucfirst($role) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small>Role cannot be changed from profile. Contact system administrator.</small>
        </div>

        <button type="submit" class="btn">Update Profile</button>
        <a href="?route=admin/profile" class="btn btn-secondary">Cancel</a>
    </form>

    <!-- Password Change Section -->
    <div style="margin-top: 40px; padding-top: 30px; border-top: 2px solid #eee;">
        <h2>Change Password</h2>
        <form method="POST" action="?route=admin/profile&sub_action=change-password">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-group">
                <label for="current_password">Current Password *</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password *</label>
                <input type="password" id="new_password" name="new_password" required>
                <small>Must be at least 8 characters long</small>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">Change Password</button>
        </form>
    </div>
</div>

<script>
    // Profile image preview and upload
    document.getElementById('profileImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profilePreview').src = e.target.result;
            }
            reader.readAsDataURL(file);

            // Upload image via AJAX
            const formData = new FormData();
            formData.append('profile_image', file);

            fetch('?route=admin/profile&sub_action=update-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Optionally update the image src with new URL
                    if (data.image_url) {
                        document.getElementById('profilePreview').src = 'uploads/profiles/' + data.image_url;
                    }
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while uploading the image.');
            });
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const email = document.getElementById('email').value.trim();

        if (!firstName || !lastName || !email) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }

        if (!validateEmail(email)) {
            e.preventDefault();
            alert('Please enter a valid email address.');
            return false;
        }
    });

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
</script>
