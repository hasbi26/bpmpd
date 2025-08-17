<div class="card">
    <div class="card-header">
        <h3 class="card-title">Buat Template <?= ucfirst($type) ?> Baru</h3>
    </div>
    <div class="card-body">
        <form id="templateForm" onsubmit="submitTemplateForm(event, '<?= $type ?>')">
            <div class="form-group">
                <label>Judul Template</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="loadContent('templates')">Kembali</button>
            </div>
        </form>
    </div>
</div>

<script>
function submitTemplateForm(event, type) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`/templates/${type}/store`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadContent('templates'); // Reload content setelah simpan
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    });
}
</script>