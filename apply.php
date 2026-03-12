<!-- Hidden Apply Form -->
<div id="applyForm" class="card mt-4" style="display:none;">
    <div class="card-header bg-primary text-white">Job Application Form</div>
    <div class="card-body">
        <form method="post" action="apply_job.php">
            <input type="hidden" name="job_id" id="job_id">
            <input type="hidden" name="company_id" id="company_id">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="">Select</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
            </div>

            <h5 class="mt-3">Post Graduation</h5>
            <div class="form-group">
                <label>PG Degree</label>
                <input type="text" name="pg_degree" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Year of Passing</label>
                <input type="number" name="pg_year" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Percentage</label>
                <input type="number" step="0.01" name="pg_percentage" class="form-control" required>
            </div>

            <h5 class="mt-3">Graduation</h5>
            <div class="form-group">
                <label>UG Degree</label>
                <input type="text" name="ug_degree" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Year of Passing</label>
                <input type="number" name="ug_year" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Percentage</label>
                <input type="number" step="0.01" name="ug_percentage" class="form-control" required>
            </div>

            <h5 class="mt-3">HSC</h5>
            <div class="form-group">
                <label>Passing Year</label>
                <input type="number" name="hsc_year" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Percentage</label>
                <input type="number" step="0.01" name="hsc_percentage" class="form-control" required>
            </div>

            <h5 class="mt-3">SSC</h5>
            <div class="form-group">
                <label>Passing Year</label>
                <input type="number" name="ssc_year" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Percentage</label>
                <input type="number" step="0.01" name="ssc_percentage" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Apply</button>
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('applyForm').style.display='none'">Cancel</button>
        </form>
    </div>
</div>
