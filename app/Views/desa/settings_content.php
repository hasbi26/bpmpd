<h3 class="mb-3" id="content-title">Pengaturan <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?> </h3>

<div class="row">
  <!-- Email Form -->
  <div class="col-md-6">
    <div class="card card-primary card-outline h-100">
      <div class="card-header">
        <div class="card-title">Tambahkan Email</div>
      </div>
      <form>
        <div class="card-body">
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input
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
      <form>
        <div class="card-body">
          <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <input
              type="password"
              class="form-control"
              id="password"
              placeholder="Masukkan password baru"
            />
          </div>
          <div class="mb-3">
            <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
            <input
              type="password"
              class="form-control"
              id="confirmPassword"
              placeholder="Ulangi password baru"
            />
            <div id="passwordHelp" class="form-text">
              <!-- Minimal 8 karakter kombinasi huruf dan angka. -->
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