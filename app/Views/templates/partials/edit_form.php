<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Template <?= ucfirst($type) ?></h3>
    </div>
    <div class="card-body">
        <form id="templateForm" onsubmit="submitTemplateForm(event, '<?= $type ?>', <?= $template['id'] ?>)">
            <div class="form-group">
                <label>Judul Template</label>
                <input type="text" name="title" class="form-control" value="<?= esc($template['title']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= esc($template['deskripsi']) ?></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-secondary" onclick="loadContent('templates')">Kembali</button>
            </div>
        </form>
    </div>
</div>

<script>
function submitTemplateForm(event, type, id) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`/templates/${type}/update/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadContent('templates'); // Reload content setelah update
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    });
}
</script>