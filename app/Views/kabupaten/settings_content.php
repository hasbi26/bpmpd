<style>


input[type="text"].show-password {
  -webkit-text-security: none !important;
}
</style>
<h3 class="mb-3" id="content-title">Pengaturan <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?> </h3>


<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success">
    <?= session()->getFlashdata('success') ?>
</div>
<?php elseif (session()->getFlashdata('error')): ?>
<div class="alert alert-danger">
    <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>


<div class="row">
  <!-- Email Form -->
  <div class="col-md-6">
    <div class="card card-primary card-outline h-100">
      <div class="card-header">
        <div class="card-title">Update Email</div>
      </div>
      <form action="<?= base_url('/email/update') ?>" method="POST">
    <?= csrf_field() ?>
        <div class="card-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input
            name="email"
              type="email"
              class="form-control"
              id="exampleInputEmail1"
              aria-describedby="emailHelp"
              placeholder="user@example.com"
            />
            <div id="emailHelp" class="form-text">
              Email untuk menerima link jika lupa password akun.
            </div>
          </div>

          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email Terdaftar</label>
            <input disabled
              type="text"
              class="form-control"
              id="currentemail"
              aria-describedby="emailHelp" value="<?php print_r(session()->get('email')); ?>"
            />
            <div id="emailHelp" class="form-text">
              Email untuk menerima link jika lupa password akun.
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Simpan Email</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Password Form -->
  <div class="col-md-6">
  
  <div class="card card-success card-outline h-100">
      <div class="card-header">
        <div class="card-title">Ganti Password</div>
      </div>
      <form action="<?= base_url('/user/update-password') ?>" method="POST">
        <div class="card-body">
          <div class="input-group mb-3">
            <input type="password" name="ChangePassword" class="form-control" id="ChangePassword" placeholder="Masukkan password baru"/>
            <div class="input-group-text" style="cursor:pointer;" id="toggleChangePassword">
              <i class="bi bi-eye"></i>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Ulangi password baru"/>
            <div class="input-group-text" style="cursor:pointer;" id="togglePassword2">
              <i class="bi bi-eye"></i>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-success">Simpan Password</button>
        </div>
      </form>
    </div>
  </div>
</div>




<script>
toggleChangePassword.addEventListener('click', function () {
  const type = ChangePassword.getAttribute('type') === 'password' ? 'text' : 'password';
  ChangePassword.setAttribute('type', type);

  if (type === 'text') {
  ChangePassword.style.setProperty('-webkit-text-security', 'none', 'important');
} else {
  ChangePassword.style.setProperty('-webkit-text-security', 'disc', 'important');
}

  this.innerHTML = type === 'password' 
    ? '<i class="bi bi-eye"></i>' 
    : '<i class="bi bi-eye-slash"></i>';
});

togglePassword2.addEventListener('click', function () {
  const type = passwordInput2.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput2.setAttribute('type', type);

  if (type === 'text') {
  passwordInput2.style.setProperty('-webkit-text-security', 'none', 'important');
} else {
  passwordInput2.style.setProperty('-webkit-text-security', 'disc', 'important');
}


  this.innerHTML = type === 'password' 
    ? '<i class="bi bi-eye"></i>' 
    : '<i class="bi bi-eye-slash"></i>';
});

</script>