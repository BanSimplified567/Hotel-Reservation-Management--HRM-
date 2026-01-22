<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New User</h1>
        <a href="index.php?action=admin/users" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Back to Users</span>
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $error; ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=admin/users&sub_action=create">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="first_name" class="font-weight-bold text-primary">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="<?php echo htmlspecialchars($old['first_name'] ?? ''); ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="last_name" class="font-weight-bold text-primary">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="<?php echo htmlspecialchars($old['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="username" class="font-weight-bold text-primary">Username *</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo htmlspecialchars($old['username'] ?? ''); ?>" required>
                                <small class="form-text text-muted">Must be unique</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email" class="font-weight-bold text-primary">Email *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password" class="font-weight-bold text-primary">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Minimum 6 characters</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="confirm_password" class="font-weight-bold text-primary">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="phone" class="font-weight-bold text-primary">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?php echo htmlspecialchars($old['phone'] ?? ''); ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="role" class="font-weight-bold text-primary">Role *</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="customer" <?php echo ($old['role'] ?? '') == 'customer' ? 'selected' : ''; ?>>Customer</option>
                                    <option value="staff" <?php echo ($old['role'] ?? '') == 'staff' ? 'selected' : ''; ?>>Staff</option>
                                    <option value="admin" <?php echo ($old['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="font-weight-bold text-primary">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?php echo htmlspecialchars($old['address'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                <label class="custom-control-label" for="is_active">Active Account</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="reset" class="btn btn-secondary">Reset</button>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            All fields marked with * are required
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-user-shield text-warning mr-2"></i>
                            Choose role carefully based on permissions needed
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-key text-danger mr-2"></i>
                            Password must be at least 6 characters
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-envelope text-success mr-2"></i>
                            Email must be unique and valid
                        </div>
                        <div class="list-group-item border-0 px-0 py-2">
                            <i class="fas fa-user-check text-info mr-2"></i>
                            Inactive accounts cannot login
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
