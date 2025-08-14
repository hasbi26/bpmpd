<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login Page </title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="BPMPD | Login Page" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="BPMPD"
    />
    <meta
      name="keywords"
      content="BPMPD KABUPATEN SUMEDANG"
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

    <!-- <link rel="stylesheet" href="../css/adminlte.css" /> -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">

    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo">
        <!-- <a href="../index2.html"><b>Admin</b>LTE</a> -->
        <b>Login Page</b>

      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Masuk Untuk Memulai</p>

          <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

        <form action="<?= base_url('/auth/login') ?>" method="post">

            <div class="input-group mb-3">
              <input type="text" name="username" class="form-control" placeholder="UserName" value="<?= old('username') ?>" />
              <div class="input-group-text"><span class="bi bi-person-fill"></span></div>
            </div>
            <div class="input-group mb-3">
                <select class="form-select" id="" name="role" required>
                          <option selected disabled value="">Pilih role</option>
                          <option value="kabupaten">Kabupaten</option>
                          <option value="kecamatan">Kecamatan</option>
                          <option value="desa">Desa</option>
                          </select>
                          <!-- <div class="invalid-feedback">Please select a valid state.</div> -->
                        </div>
            <div class="input-group mb-3">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
              <div class="input-group-text" style="cursor:pointer;" id="togglePassword">
              <i class="bi bi-eye"></i>
            </div>
            </div>
            <!--begin::Row-->
            <div class="row">
              <div class="col-8">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault"> Remember Me </label>
                </div>
              </div>
              <!-- /.col -->
              <div class="col-4">
                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-primary">Masuk</button>
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!--end::Row-->
          </form>
          <div class="social-auth-links text-center mb-3 d-grid gap-2">
            <!-- <p>- OR -</p> -->
            <!-- <a href="#" class="btn btn-primary">
              <i class="bi bi-facebook me-2"></i> Sign in using Facebook
            </a> -->
            <!-- <a href="#" class="btn btn-danger">
              <i class="bi bi-google me-2"></i> Sign in using Google+
            </a> -->
          </div> 
          <!-- /.social-auth-links -->
     <p class="mb-1"><a href="<?= base_url('/auth/forgot') ?>">Lupa password ?</a></p>
          <p class="mb-0">
            <!-- <a href="register.html" class="text-center"> Register a new membership </a> -->
          </p>
        </div>
        <!-- /.login-card-body-->
      </div>
    </div>
    <!-- /.login-box -->

        <!--begin::Third Party Plugin(Bootstrap Icons)-->
        <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->


    <!--begin::Third Party Plugin(OverlayScrollbars)-->
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
    <!-- <script src="../js/adminlte.js"></script> -->
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
  const togglePassword = document.querySelector('#togglePassword');
  const passwordInput = document.querySelector('#password');

  togglePassword.addEventListener('click', function () {
    // Ganti tipe input
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Ganti ikon
    this.innerHTML = type === 'password' 
      ? '<i class="bi bi-eye"></i>' 
      : '<i class="bi bi-eye-slash"></i>';
  });
</script>


    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>