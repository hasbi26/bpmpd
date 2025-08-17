<div class="card">
    <div class="card-header">
        <h3 class="card-title">Template <?= ucfirst($type) ?></h3>
        <div class="card-tools">
            <button class="btn btn-sm btn-primary" 
                    onclick="loadTemplateForm('<?= $type ?>')">
                <i class="fas fa-plus"></i> Buat Baru
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if(session()->getFlashdata('message')) : ?>
            <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
        <?php endif ?>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($templates as $index => $template) : ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= esc($template['title']) ?></td>
                    <td><?= esc($template['deskripsi']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($template['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" 
                                onclick="loadTemplateForm('<?= $type ?>', <?= $template['id'] ?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" 
                                onclick="deleteTemplate('<?= $type ?>', <?= $template['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function loadTemplateForm(type, id = null) {
    const url = id 
        ? `/load-content/templates/${type}/form/${id}`
        : `/load-content/templates/${type}/form`;
        
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('dynamic-content').innerHTML = html;
        });
}

function deleteTemplate(type, id) {
    if (confirm('Yakin ingin menghapus template ini?')) {
        fetch(`/templates/${type}/delete/${id}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadContent('templates'); // Reload content
            }
        });
    }
}
</script>