<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reset Password Page</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="BPMPD | Forgot Password" />
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
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <!-- <link rel="stylesheet" href="../css/adminlte.css" /> -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="login-page bg-body-secondary">
    <div class="login-box">
      <div class="login-logo">
        <!-- <a href="../index2.html"><b>Admin</b>LTE</a> -->
        <b>Reset Password Page</b>

      </div>
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body">
          <p class="login-box-msg">Masukan Password Baru</p>

          <?php if(session()->getFlashdata('error')): ?>
        <p style="color:red"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
        <p style="color:green"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form action="<?= base_url('process-reset-password') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= $token ?>">

        <label>Password Baru</label><br>
        <div class="input-group mb-3">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
              <div class="input-group-text" style="cursor:pointer;" id="togglePassword">
              <i class="bi bi-eye"></i>
            </div>
            </div>

        <label>Konfirmasi Password</label><br>
        <div class="input-group mb-3">
        <input type="password" id="password2" name="confirm" class="form-control" placeholder="Password" />
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
              <div class="input-group-text" style="cursor:pointer;" id="togglePassword2">
              <i class="bi bi-eye"></i>
            </div>
            </div> 
        <button type="submit" class="btn btn-primary">Ubah Password</button>
    </form>

          <!-- /.social-auth-links -->
        </div>
        <!-- /.login-card-body-->
      </div>
    </div>
    <!-- /.login-box -->
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

  const togglePassword2 = document.querySelector('#togglePassword2');
  const passwordInput2 = document.querySelector('#password2');

  togglePassword.addEventListener('click', function () {
    // Ganti tipe input
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Ganti ikon
    this.innerHTML = type === 'password' 
      ? '<i class="bi bi-eye"></i>' 
      : '<i class="bi bi-eye-slash"></i>';
  });

  togglePassword2.addEventListener('click', function () {
    // Ganti tipe input
    const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput2.setAttribute('type', type);

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








<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
        <label>Username</label><br>
        <input type="text" name="username"><br><br>

        <label>Password</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html> -->







