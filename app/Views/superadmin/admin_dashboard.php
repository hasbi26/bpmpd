<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>DPMPD | <?= esc(ucfirst($user['username']));?></title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE | Dashboard v3" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="<?= base_url('assets/adminlte/css/adminlte.css') ?>" as="style">

    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">

    <!--end::Required Plugin(AdminLTE)-->
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
  </head>
  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a href="<?= base_url('auth/logoutadmin') ?>" class="btn btn-secondary btn-flat float-end">Sign out</a>

              </a>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
            <!--begin::Brand Image-->
            <img
              src="<?= base_url('assets/adminlte/img/') ?>smd.png"
              alt="Kabupaten Sumedang Logo"
              class="brand-image opacity-75 shadow"
              style="width: 40px;"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
  <nav class="mt-2">
    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" 
        aria-label="Main navigation" data-accordion="false" id="navigation">
      <li class="nav-item">
        <a href="#" class="nav-link active" data-content="status" id="menu-status">
          <i class="nav-icon bi bi-speedometer"></i>
          <p>Status</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link" data-content="upload" id="menu-upload">
          <i class="nav-icon bi bi-clipboard-fill"></i>
          <p>Create Templates</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="#" class="nav-link" data-content="settings" id="menu-settings">
          <i class="nav-icon bi bi-pencil-square"></i>
          <p>Pengaturan</p>
        </a>
      </li>
    </ul>
  </nav>
</div>
</aside>
<main class="app-main">
  <div class="app-content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert" id="flashMessage">
          <?= session()->getFlashdata('success') ?>
        </div>
        <?php elseif (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert" id="flashMessage">
          <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>
        
        </div>
      </div>
    </div>
  </div>
  
  <div class="app-content">
    <div class="container-fluid">
      <div class="row">
        <div id="dynamic-content">
        </div>
      </div>
    </div>
  </div>
</main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          DPMPD | Kabupaten Sumedang        </strong>
        
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
  
  function waitForElements(selectors, callback) {
    const foundElements = {};

    // 🔑 langsung cek dulu sebelum observer
    selectors.forEach(sel => {
        if (document.querySelector(sel)) {
            foundElements[sel] = true;
            callback(sel, document.querySelector(sel));
        }
    });

    const observer = new MutationObserver(() => {
        selectors.forEach(sel => {
            if (!foundElements[sel] && document.querySelector(sel)) {
                foundElements[sel] = true;
                callback(sel, document.querySelector(sel));
            }
        });

        if (Object.keys(foundElements).length === selectors.length) {
            observer.disconnect();
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
}
  </script>


    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->

    <script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>

    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
<script>
// Fungsi terpisah untuk load content
function loadContent(contentType) {
  const dynamicContent = document.getElementById('dynamic-content');
  if (!dynamicContent) {
    console.error('Target element #dynamic-content not found');
    return;
  }
  dynamicContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div></div>';

  fetch(`/load-content/${contentType}?role=<?= esc($user['role']) ?>`)
    .then(response => {
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      return response.text();
    })
    .then(html => {
      dynamicContent.innerHTML = html;
    waitForElements(['#desaTableBody', '#kecamatanTableBody', '#nav-tabContent'], (selector, el) => {
        if (selector === '#desaTableBody') {
            LoadDocumentDesa(1, 10, "");

              // Fill Edit Desa Modal
              document.getElementById('desaTableBody').addEventListener('click', function(e) {
            if (e.target.closest('.btnEditDesa')) {
                const btn = e.target.closest('.btnEditDesa');
                editDocument(btn.dataset.id);
                 //document.getElementById('desa_id').value = btn.dataset.id;
                // document.getElementById('desa_title').value = btn.dataset.title;
                // document.getElementById('desa_deskripsi').value = btn.dataset.deskripsi;
                //        // ✅ set checkbox berdasarkan data-is_active
                //  const isActiveCheckbox = document.getElementById('desa_is_active');
                //  isActiveCheckbox.checked = btn.dataset.is_active === "1";
            }
        });
        
          }
        if (selector === '#kecamatanTableBody') {
          LoadDocumentKecamatan(1, 10, "");

            document.getElementById('kecamatanTableBody').addEventListener('click', function(e) {
            if (e.target.closest('.btnEditKecamatan')) {
                const btn = e.target.closest('.btnEditKecamatan');
                document.getElementById('kecamatan_id').value = btn.dataset.id;
                document.getElementById('kecamatan_title').value = btn.dataset.title;
                document.getElementById('kecamatan_deskripsi').value = btn.dataset.deskripsi;
                                       // ✅ set checkbox berdasarkan data-is_active
                const isActiveCheckbox = document.getElementById('kecamatan_is_active');
                isActiveCheckbox.checked = btn.dataset.is_active === "1";
            }
        });
        }
        if (selector === '#nav-tabContent') {
          renderNav();
        }

      });
    initDynamicContentScripts();

    })
    .catch(error => {
      console.error('Fetch error:', error);
      dynamicContent.innerHTML = `
        <div class="alert alert-danger">
          Error loading content: ${error.message}
        </div>
      `;
    });
}

// Fungsi untuk inisialisasi komponen dinamis
function initDynamicContentScripts() {
  // Inisialisasi komponen JS di content yang di-load
  if (typeof OverlayScrollbarsGlobal !== 'undefined') {
    OverlayScrollbarsGlobal.OverlayScrollbars(document.querySelector('.sidebar-wrapper'), {
      scrollbars: {
        theme: 'os-theme-light',
        autoHide: 'leave',
        clickScroll: true
      }
    });
  }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
  // Set active menu
  let currentActiveMenu = localStorage.getItem('activeMenu') || 'menu-status';
  
  function setActiveMenu(menuId) {
    document.querySelectorAll('.sidebar-menu a.nav-link').forEach(link => {
      link.classList.remove('active');
      link.parentElement.classList.remove('menu-open');
    });
    
    const activeLink = document.getElementById(menuId);
    if (activeLink) {
      activeLink.classList.add('active');
      activeLink.parentElement.classList.add('menu-open');
      currentActiveMenu = menuId;
      localStorage.setItem('activeMenu', menuId);
    }
  }

  // Attach click handlers
  document.querySelectorAll('.sidebar-menu a.nav-link').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const menuId = this.id;
      const contentType = this.dataset.content;
      setActiveMenu(menuId);
      loadContent(contentType);
      console.log("contenttype", contentType)
    });
  });

  // Initial load
  setActiveMenu(currentActiveMenu);
  const initialContent = document.querySelector(`#${currentActiveMenu}`)?.dataset.content || 'status';
  loadContent(initialContent);
});
</script>
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
<script>
  
  const baseDeleteUrl = "<?= base_url('templates/delete_desa/') ?>";

function LoadDocumentDesa(page = 1, length = 10, search = "") {
    $.ajax({
      url: "<?= site_url('templates/get_templates') ?>",
      method: "GET",
      data: {
        page: page,
        length: length,
        search: search
      },
      success: function (res) {
        let rows = "";
        let no = (page - 1) * length + 1;

        if (res.data.length > 0) {
          res.data.forEach(function (item) {
            rows += `
              <tr>
                <td>${no++}</td>
                <td>${item.title}</td>
                <td>${item.deskripsi ?? '-'}</td>
                <td>${item.username ?? '-'}</td>
                <td>
                ${item.is_active == 1 
                  ? '<i class="bi bi-check2-square text-success"></i>' 
                  : '<i class="bi bi-dash-square text-danger"></i>'}
                  </td>
                  <td>${item.created_at ?? '-'}</td>
                  <td>
                                <button class="btn btn-warning btn-sm btnEditDesa" 
                                    data-id="${item.id}" 
                                    data-title="${item.title}" 
                                    data-is_active="${item.is_active}" 
                                    data-deskripsi="${item.deskripsi ?? ''}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditDesa">
                                    <i class="bi bi-pencil-square"></i>
                                    
                                </button>
                                <a href="${baseDeleteUrl}${item.id}" 
                                  class="btn btn-danger btn-sm" 
                                  onclick="return confirm('Hapus template ini?')">
                                  <i class="bi bi-trash"></i>
                                </a>
                            </td>
              </tr>
            `;
          });
        } else {
          rows = `<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>`;
        }

        $("#desaTableBody").html(rows);

        // ✅ buat pagination bootstrap
        let totalPages = Math.ceil(res.recordsTotal / length);
        let pagination = `
          <ul class="pagination pagination-sm m-0 float-end">
        `;

        // tombol prev
        let prevPage = page > 1 ? page - 1 : 1;
        pagination += `
          <li class="page-item ${page === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${prevPage}">&laquo;</a>
          </li>
        `;

        // nomor halaman
        for (let i = 1; i <= totalPages; i++) {
          pagination += `
            <li class="page-item ${i === page ? 'active' : ''}">
              <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
          `;
        }

        // tombol next
        let nextPage = page < totalPages ? page + 1 : totalPages;
        pagination += `
          <li class="page-item ${page === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${nextPage}">&raquo;</a>
          </li>
        `;

        pagination += `</ul>`;

        $("#pagination").html(pagination);
      }
    });
  }

  // 🚀 handler pagination klik
  $(document).on("click", "#pagination a.page-link", function (e) {
    e.preventDefault();
    let page = $(this).data("page");
    let length = $("#perPage").val();
    let search = $("#searchInput").val();
    if (page) {
      LoadDocumentDesa(page, length, search);
    }
  });

  // 🚀 handler ganti jumlah per halaman
  $(document).on("change", "#perPage", function () {
    let length = $(this).val();
    let search = $("#searchInput").val();
    LoadDocumentDesa(1, length, search);
  });

  // 🚀 handler search
  $(document).on("keyup", "#searchInput", function () {
    let search = $(this).val();
    let length = $("#perPage").val();
    LoadDocumentDesa(1, length, search);
  });

  // 🚀 pertama kali load
  LoadDocumentDesa(1, 10, "");

document.addEventListener('DOMContentLoaded', () => LoadDocumentDesa(1, 10, ""));

</script>

<script>
    setTimeout(function() {
        let flash = document.getElementById('flashMessage');
        if (flash) {
            flash.style.transition = 'opacity 0.5s ease';
            flash.style.opacity = '0';
            setTimeout(() => flash.remove(), 500); // hapus elemen setelah animasi
        }
    }, 3000); // 5000ms = 5 detik
</script>
  

<script>
  function renderNav(){
  $(document).on("click", "#add-desa", function () {
    $("#desa-container").append(`
      <div class="input-group mb-2 desa-input">
        <input type="text" name="desa[]" class="form-control" placeholder="Nama Document Desa">
        <button class="btn btn-danger remove-desa" type="button">Hapus</button>
      </div>
    `);
  });

  // Hapus Desa
  $(document).on("click", ".remove-desa", function() {
    $(this).closest(".desa-input").remove();
  });

  $(document).on("click", "#add-kecamatan", function() {
    $("#kecamatan-container").append(`
      <div class="input-group mb-2 kecamatan-input">
        <input type="text" name="kecamatan[]" class="form-control" placeholder="Nama Kecamatan">
        <button class="btn btn-danger remove-kecamatan" type="button">Hapus</button>
      </div>
    `);

  });

  // Hapus Kecamatan
  $(document).on("click", ".remove-kecamatan", function() {
    $(this).closest(".kecamatan-input").remove();
  });

  }
</script>

<script>
function editDocument(idDoc){

  // let id = $(this).data("id");
let id =idDoc
  // kosongkan container dulu
  $("#desa-container-edit").html("");
  $("#kecamatan-container-edit").html("");
  $.ajax({
  url: "<?= site_url('templates/edit') ?>/" + id,
  type: "GET",
  success: function (res) {
    console.log(res);

    if (res.length > 0) {
      // isi field utama
      $("#edit_id").val(res[0].id);
      $("#edit_title").val(res[0].title);
      $("#edit_deskripsi").val(res[0].deskripsi ?? "");

      // kosongkan container dulu biar gak dobel saat buka modal kedua kali
      $("#desa-container-edit").empty();
      $("#kecamatan-container-edit").empty();

      // render detail berdasarkan role
      res.forEach(function (item) {
        if (item.role === "desa") {
          $("#desa-container-edit").append(`
            <div class="input-group mb-2 desa-input">
              <input type="text" name="desa[]" class="form-control" value="${item.nama_file}" placeholder="Nama Document Desa">
              <button class="btn btn-danger remove-desa" type="button">Hapus</button>
            </div>
          `);
        }
        if (item.role === "kecamatan") {
          $("#kecamatan-container-edit").append(`
            <div class="input-group mb-2 kecamatan-input">
              <input type="text" name="kecamatan[]" class="form-control" value="${item.nama_file}" placeholder="Nama Kecamatan">
              <button class="btn btn-danger remove-kecamatan" type="button">Hapus</button>
            </div>
          `);
        }
      });

      if (res[0].is_active == "1") {
      $("#edit_is_active").prop("checked", true);
    } else {
      $("#edit_is_active").prop("checked", false);
    }


      // kalau kosong sama sekali kasih input default
      if ($("#desa-container-edit").children().length === 0) {
        $("#desa-container-edit").append(`
          <div class="input-group mb-2 desa-input">
            <input type="text" name="desa[]" class="form-control" placeholder="Nama Document Desa">
            <button class="btn btn-danger remove-desa" type="button">Hapus</button>
          </div>
        `);
      }
      if ($("#kecamatan-container-edit").children().length === 0) {
        $("#kecamatan-container-edit").append(`
          <div class="input-group mb-2 kecamatan-input">
            <input type="text" name="kecamatan[]" class="form-control" placeholder="Nama Kecamatan">
            <button class="btn btn-danger remove-kecamatan" type="button">Hapus</button>
          </div>
        `);
      }
    }
  }
});

  // Tambah Desa Edit
$(document).on("click", "#add-desa-edit", function () {
  $("#desa-container-edit").append(`
    <div class="input-group mb-2 desa-input">
      <input type="text" name="desa[]" class="form-control" placeholder="Nama Document Desa">
      <button class="btn btn-danger remove-desa" type="button">Hapus</button>
    </div>
  `);
});

// Hapus Desa
$(document).on("click", ".remove-desa", function () {
  $(this).closest(".desa-input").remove();
});

// Tambah Kecamatan Edit
$(document).on("click", "#add-kecamatan-edit", function () {
  $("#kecamatan-container-edit").append(`
    <div class="input-group mb-2 kecamatan-input">
      <input type="text" name="kecamatan[]" class="form-control" placeholder="Nama Kecamatan">
      <button class="btn btn-danger remove-kecamatan" type="button">Hapus</button>
    </div>
  `);
});

// Hapus Kecamatan
$(document).on("click", ".remove-kecamatan", function () {
  $(this).closest(".kecamatan-input").remove();
});


}



</script>


</body>
</html>
