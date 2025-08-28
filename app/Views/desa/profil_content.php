<h3 class="mb-3" id="content-title">Profil <?= esc(ucfirst($role)) ?> <?= esc(ucfirst(strtolower($namaWilayah))) ?> </h3>
            
              
              
              
              
                      <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="flashMessage">
                    <?= session()->getFlashdata('success') ?>
                </div>
                <?php elseif (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="flashMessage">
                    <?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>
              
              
              <!--begin::Input Group-->
                <div class="card card-success card-outline mb-4">
                  <!--begin::Header-->
                  <form action="<?= base_url('update/profil-desa') ?>" method="POST">
                  <div class="card-header"><div class="card-title">Form Profil</div></div>
                  <input type="hidden" name="id_desa" id="id_desa" value="<?= esc($idprofil) ?>">
                  <!--end::Header-->
                  <!--begin::Body-->
                  <div class="card-body">
                    <div class="mb-3">
                      <div class="input-group">
                        <span class="input-group-text" id="basic-namaKepalaDesa">Nama Kepala Desa</span>
                        <input type="text" class="form-control" name="namaKepalaDesa" id="namaKepalaDesa" value="<?= esc($profilDesa['kepala_desa']) ?>" aria-describedby="basic-addon3 basic-addon4"/>
                      </div>
                      <div class="form-text" id="basic-namaKepalaDesa">
                        <!-- Example help text goes outside the input group. -->
                      </div>
                    </div>
                    <div class="mb-3">
                      <div class="input-group">
                        <span class="input-group-text" id="basic-alamatDesa">Alamat Desa</span>
                        <input type="text" class="form-control" id="alamatDesa" name="alamatDesa" value="<?= esc($profilDesa['alamat_desa']) ?>" aria-describedby="basic-addon3 basic-addon4"/>
                      </div>
                      <div class="form-text" id="basic-alamatDesa">
                        <!-- Example help text goes outside the input group. -->
                      </div>
                    </div>
                    <div class="mb-3">
                      <div class="input-group">
                        <span class="input-group-text" id="basic-rekeningDesa">No Rekening Desa</span>
                        <input type="text" class="form-control" id="rekeningDesa" name="rekeningDesa" value="<?= esc($profilDesa['no_rekening']) ?>" aria-describedby="basic-addon3 basic-addon4"/>
                      </div>
                      <div class="form-text" id="basic-rekeningDesa">
                        <!-- Example help text goes outside the input group. -->
                      </div>
                    </div>
                  </div>
                  <!--end::Body-->
                  <!--begin::Footer-->
                  <div class="card-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                  </div>
                  <!--end::Footer-->
                </div>
                <!--end::Input Group-->