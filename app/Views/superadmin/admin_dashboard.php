<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>BPMPD | <?= esc(ucfirst($user['username']));?></title>
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
          BPMPD | Kabupaten Sumedang        </strong>
        
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->

    <script>
  
  function waitForElements(selectors, callback) {
    const foundElements = {};
    const observer = new MutationObserver(() => {
        selectors.forEach(sel => {
            if (!foundElements[sel] && document.querySelector(sel)) {
                foundElements[sel] = true;
                callback(sel, document.querySelector(sel));
            }
        });

        // kalau semua selector sudah ketemu → stop observer
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

  dynamicContent.innerHTML = '<div class="text-center py-5">Memuat data... dashboard</div>';

  fetch(`/load-content/${contentType}?role=<?= esc(ucfirst($user['role'])) ?>`)
    .then(response => {
      if (!response.ok) throw new Error(`HTTP ${response.status}`);
      return response.text();
    })
    .then(html => {
      dynamicContent.innerHTML = html;
    waitForElements(['#desaTableBody', '#kecamatanTableBody'], (selector, el) => {
        if (selector === '#desaTableBody') {
            loadDesaTemplates();
              // Fill Edit Desa Modal
              document.getElementById('desaTableBody').addEventListener('click', function(e) {
            if (e.target.closest('.btnEditDesa')) {
                const btn = e.target.closest('.btnEditDesa');
                document.getElementById('desa_id').value = btn.dataset.id;
                document.getElementById('desa_title').value = btn.dataset.title;
                document.getElementById('desa_deskripsi').value = btn.dataset.deskripsi;
                       // ✅ set checkbox berdasarkan data-is_active
                const isActiveCheckbox = document.getElementById('desa_is_active');
                isActiveCheckbox.checked = btn.dataset.is_active === "1";
            }
        });
        
          }
        if (selector === '#kecamatanTableBody') {
            loadKecamatanTemplates();

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
function loadDesaTemplates() {
    fetch('<?= base_url('templates/get_desa') ?>')
        .then(response => response.json())
        .then(res => {
            let tbody = document.getElementById('desaTableBody');
            tbody.innerHTML = '';

            if (res.success && res.data.length > 0) {
                let no = 1;
                res.data.forEach(desa => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${no++}</td>
                            <td>${desa.title}</td>
                            <td>${desa.deskripsi ?? ''}</td>
                            <td>${desa.username}</td>
                            <td>
                            ${desa.is_active == 1 
                            ? '<i class="bi bi-check2-square text-success"></i>' 
                            : '<i class="bi bi-dash-square text-danger"></i>'}
                            </td>
                            <td>${desa.created_at}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditDesa" 
                                    data-id="${desa.id}" 
                                    data-title="${desa.title}" 
                                    data-is_active="${desa.is_active}" 
                                    data-deskripsi="${desa.deskripsi ?? ''}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditDesa">
                                    <i class="bi bi-pencil-square"></i>
                                    
                                </button>
                                <a href="<?= base_url('templates/delete_desa/') ?>${desa.id}" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Hapus template ini?')">
                                   <i class="bi bi-trash"></i>                                </a>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                `;
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById('desaTableBody').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger">Gagal memuat data</td>
                </tr>
            `;
        });
      }

function loadKecamatanTemplates() {
    fetch('<?= base_url('templates/get_kecamatan') ?>')
        .then(response => response.json())
        .then(res => {
            let tbody = document.getElementById('kecamatanTableBody');
            tbody.innerHTML = '';

            if (res.success && res.data.length > 0) {
                let no = 1;
                res.data.forEach(kecamatan => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${no++}</td>
                            <td>${kecamatan.title}</td>
                            <td>${kecamatan.deskripsi ?? ''}</td>
                            <td>${kecamatan.username}</td>
                            <td>
                            ${kecamatan.is_active == 1 
                            ? '<i class="bi bi-check2-square text-success"></i>' 
                            : '<i class="bi bi-dash-square text-danger"></i>'}
                            </td>
                            <td>${kecamatan.created_at}</td>
                            <td>
                                <button class="btn btn-warning btn-sm btnEditKecamatan" 
                                    data-id="${kecamatan.id}" 
                                    data-title="${kecamatan.title}" 
                                    data-is_active="${kecamatan.is_active}" 
                                    data-deskripsi="${kecamatan.deskripsi ?? ''}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditKecamatan">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="<?= base_url('templates/delete_kecamatan/') ?>${kecamatan.id}" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Hapus template ini?')">
                                   <i class="bi bi-trash"></i>                                </a>
                                </a>
                            </td>
                        </tr>
                    `;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                `;
            }
        })
        .catch(err => {
            console.error(err);
            document.getElementById('kecamatanTableBody').innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger">Gagal memuat data</td>
                </tr>
            `;
        });
      }

// Panggil saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadKecamatanTemplates);
document.addEventListener('DOMContentLoaded', loadDesaTemplates);
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



  </body>
</html>
