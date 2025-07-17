<!-- Add Personnel Modal -->
<div class="modal fade" id="addPersonnelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Maintenance Personnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=personnel">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="create_personnel">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name">
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Personnel Modal -->
<div class="modal fade" id="editPersonnelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Maintenance Personnel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=personnel">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="update_personnel">
                <input type="hidden" name="personnel_id" id="edit_personnel_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="edit_middle_name" name="middle_name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Location Modal -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=locations">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="create_location">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="location_name" class="form-label">Location Name</label>
                        <input type="text" class="form-control" id="location_name" name="location_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="location_type" class="form-label">Location Type</label>
                        <select class="form-select" id="location_type" name="location_type">
                            <option value="">Select Location Type</option>
                            <?php foreach ($locationTypes ?? [] as $type): ?>
                                <option value="<?= htmlspecialchars($type['id']) ?>"><?= htmlspecialchars($type['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="parent_location_id" class="form-label">Parent Location</label>
                        <select class="form-select" id="parent_location_id" name="parent_location_id">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($parentLocations ?? [] as $parent): ?>
                                <option value="<?= $parent['location_id'] ?>">
                                    <?= htmlspecialchars($parent['location_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Location Modal -->
<div class="modal fade" id="editLocationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=locations" id="editLocationForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="update_location">
                <input type="hidden" name="location_id" id="edit_location_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_location_name" class="form-label">Location Name</label>
                        <input type="text" class="form-control" id="edit_location_name" name="location_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_location_type" class="form-label">Location Type</label>
                        <select class="form-select" id="edit_location_type" name="location_type">
                            <option value="">Select Location Type</option>
                            <?php foreach ($locationTypes ?? [] as $type): ?>
                                <option value="<?= htmlspecialchars($type['id']) ?>">
                                    <?= htmlspecialchars($type['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_parent_location_id" class="form-label">Parent Location</label>
                        <select class="form-select" id="edit_parent_location_id" name="parent_location_id">
                            <option value="">None (Top Level)</option>
                            <?php foreach ($parentLocations ?? [] as $parent): ?>
                                <option value="<?= $parent['location_id'] ?>">
                                    <?= htmlspecialchars($parent['location_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="updateLocationBtn">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Sub-Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=services" id="addServiceForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="create_service">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="service_name" class="form-label">Sub-Service Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="service_name" name="service_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="service_id" class="form-label">Parent Service <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="service_id" name="service_id">
                            <option value="">Select Parent Service</option>
                            <?php foreach ($parentServices ?? [] as $parent): ?>
                                <option value="<?= $parent['id'] ?>">
                                    <?= htmlspecialchars($parent['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted">Please select a parent service for this sub-service</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"
                        onclick="return validateAddServiceForm()">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Sub-Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=services" id="editServiceForm">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="update_service">
                <input type="hidden" name="service_id" id="edit_service_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_service_name" class="form-label">Sub-Service Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_service_name" name="service_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_parent_service_id" class="form-label">Parent Service <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="edit_parent_service_id" name="parent_service_id">
                            <option value="">Select Parent Service</option>
                            <?php foreach ($parentServices ?? [] as $parent): ?>
                                <option value="<?= $parent['id'] ?>">
                                    <?= htmlspecialchars($parent['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted">Please select a parent service for this sub-service</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"
                        onclick="return validateEditServiceForm()">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=employees"
                onsubmit="return validateEmployeeForm('emp_first_name', 'emp_last_name', 'emp_gov_id')">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="create_employee">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="emp_first_name" class="form-label">First Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="emp_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="emp_last_name" class="form-label">Last Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="emp_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="emp_gov_id" class="form-label">Government ID (Numbers Only)</label>
                        <input type="text" class="form-control" id="emp_gov_id" name="gov_id" pattern="[0-9]*"
                            title="Only numbers are allowed">
                        <small class="form-text text-muted">Enter numbers only for Government ID</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="process/content-management.php?tab=employees"
                onsubmit="return validateEmployeeForm('edit_emp_first_name', 'edit_emp_last_name', 'edit_emp_gov_id')">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="action" value="update_employee">
                <input type="hidden" name="employee_id" id="edit_employee_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_emp_first_name" class="form-label">First Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_emp_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_emp_last_name" class="form-label">Last Name <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_emp_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_emp_gov_id" class="form-label">Government ID (Numbers Only)</label>
                        <input type="text" class="form-control" id="edit_emp_gov_id" name="gov_id" pattern="[0-9]*"
                            title="Only numbers are allowed">
                        <small class="form-text text-muted">Enter numbers only for Government ID</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Delete Forms -->
<form id="deletePersonnelForm" method="post" action="process/content-management.php?tab=personnel"
    style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="action" value="delete_personnel">
    <input type="hidden" name="personnel_id" id="delete_personnel_id">
</form>

<form id="deleteLocationForm" method="post" action="process/content-management.php?tab=locations" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="action" value="delete_location">
    <input type="hidden" name="location_id" id="delete_location_id">
</form>

<form id="deleteServiceForm" method="post" action="process/content-management.php?tab=services" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="action" value="delete_service">
    <input type="hidden" name="service_id" id="delete_service_id">
</form>

<form id="deleteEmployeeForm" method="post" action="process/content-management.php?tab=employees" style="display:none;">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="hidden" name="action" value="delete_employee">
    <input type="hidden" name="employee_id" id="delete_employee_id">
</form>