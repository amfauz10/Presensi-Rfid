

<?php $__env->startSection('title', 'Kelola User'); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4 text-start">

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3 border-bottom border-light pb-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Kelola User</h3>
            <p class="text-muted small mb-0">
                Manajemen akun Admin dan Guru SDN Tengah 03 Jakarta Timur.
            </p>
        </div>

        <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2 px-3 shadow-sm hover-up rounded-3" style="height: 40px; font-weight: 600; font-size: 0.85rem; transition: all 0.25s ease; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
            <i class="bi bi-plus-circle-fill fs-6"></i>
            Tambah User
        </a>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center p-3" role="alert" style="border-radius: 12px; background-color: #dcfce7; color: #15803d;">
            <i class="bi bi-check-circle-fill me-3 fs-5 text-success"></i>
            <div class="fw-medium"><?php echo e(session('success')); ?></div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close" style="top: 1rem; right: 1rem;"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 d-flex align-items-center p-3" role="alert" style="border-radius: 12px; background-color: #fef2f2; color: #b91c1c;">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-5 text-danger"></i>
            <div class="fw-medium"><?php echo e(session('error')); ?></div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close" style="top: 1rem; right: 1rem;"></button>
        </div>
    <?php endif; ?>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card border-0 p-3 position-relative overflow-hidden shadow-premium bg-white rounded-4">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block text-uppercase fw-bold tracking-wider mb-1" style="font-size: 0.7rem;">Total User</small>
                        <h4 class="fw-bold text-dark mb-0 font-monospace" style="letter-spacing: -0.5px;"><?php echo e($users->count()); ?></h4>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-2 px-3 rounded-3 text-secondary">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-secondary opacity-50" style="height: 3px;"></div>
            </div>
        </div>
        
        <div class="col-6 col-md-4">
            <div class="card border-0 p-3 position-relative overflow-hidden shadow-premium bg-white rounded-4">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-primary d-block text-uppercase fw-bold tracking-wider mb-1" style="font-size: 0.7rem;">Admin</small>
                        <h4 class="fw-bold text-dark mb-0 font-monospace" style="letter-spacing: -0.5px;"><?php echo e($users->where('role', 'admin')->count()); ?></h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 text-primary">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px;"></div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="card border-0 p-3 position-relative overflow-hidden shadow-premium bg-white rounded-4">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-success d-block text-uppercase fw-bold tracking-wider mb-1" style="font-size: 0.7rem;">Guru</small>
                        <h4 class="fw-bold text-dark mb-0 font-monospace" style="letter-spacing: -0.5px;"><?php echo e($users->where('role', 'guru')->count()); ?></h4>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 px-3 rounded-3 text-success">
                        <i class="bi bi-mortarboard-fill fs-5"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    
    <div class="card border-0 mb-5 position-relative overflow-hidden rounded-4 shadow-premium bg-white">
        
        
        <div class="card-header bg-white pt-4 pb-3 px-4 border-0">
            <h5 class="mb-1 fw-bold text-dark" style="font-size: 1rem;">Daftar Kontrol Pengguna</h5>
        </div>

        
        <div class="px-4 pb-3 border-0 bg-white">
            <div class="row g-2">
                <div class="col-12 col-md-8">
                    <div class="input-group search-merge-group">
                        <span class="input-group-text bg-light border-end-0 py-2 px-3 text-muted">
                            <i class="bi bi-search small"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control bg-light border-start-0 py-2 ps-1 shadow-none" placeholder="Cari nama atau email user..." style="font-size: 0.9rem;">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group search-merge-group">
                        <select id="roleFilter" class="form-select bg-light py-2 shadow-none" style="font-size: 0.9rem; color: #475569;">
                            <option value="all">Semua Jenis Role</option>
                            <option value="admin">Admin</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="70" class="text-center">No</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat Email</th>
                            <th width="140" class="text-center">Hak Akses</th>
                            <th width="140" class="text-center">Aksi Terjadwal</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="user-row" data-name="<?php echo e(strtolower($user->name)); ?>" data-email="<?php echo e(strtolower($user->email)); ?>" data-role="<?php echo e($user->role); ?>" style="transition: background-color 0.2s ease;">
                            
                            
                            <td class="text-center">
                                <span class="d-inline-flex align-items-center justify-content-center text-secondary rounded-circle fw-semibold font-monospace" style="width: 28px; height: 28px; background-color: #f1f5f9; font-size: 12px;">
                                    <?php echo e($index + 1); ?>

                                </span>
                            </td>

                            
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 38px; height: 38px; background-color: #eff6ff; flex-shrink: 0;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-uppercase" style="font-size: 0.84rem; letter-spacing: 0.2px;"><?php echo e($user->name); ?></div>
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            <?php echo e($user->role == 'admin' ? 'Administrator Sistem' : 'Tenaga Pendidik'); ?>

                                        </span>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="text-secondary" style="font-size: 0.88rem;">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-envelope text-muted opacity-75" style="font-size: 13px;"></i>
                                    <span><?php echo e($user->email); ?></span>
                                </div>
                            </td>

                            
                            <td class="text-center">
                                <?php if($user->role == 'admin'): ?>
                                    <span class="badge px-2.5 py-1.5 d-inline-flex align-items-center gap-1 border-0 text-primary bg-primary bg-opacity-10" style="border-radius: 6px; font-weight: 700; font-size: 0.72rem; letter-spacing: 0.3px;">
                                        <i class="bi bi-shield-lock-fill" style="font-size: 11px;"></i> ADMIN
                                    </span>
                                <?php else: ?>
                                    <span class="badge px-2.5 py-1.5 d-inline-flex align-items-center gap-1 border-0 text-success bg-success bg-opacity-10" style="border-radius: 6px; font-weight: 700; font-size: 0.72rem; letter-spacing: 0.3px;">
                                        <i class="bi bi-mortarboard-fill" style="font-size: 11px;"></i> GURU
                                    </span>
                                <?php endif; ?>
                            </td>

                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    
                                    <a href="<?php echo e(route('users.edit', $user->id)); ?>"
                                       class="btn p-0 d-flex align-items-center justify-content-center text-warning hover-action-btn shadow-none"
                                       style="width: 32px; height: 32px; background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; transition: all 0.2s ease;"
                                       title="Ubah Data">
                                        <i class="bi bi-pencil-square" style="font-size: 14px;"></i>
                                    </a>

                                    
                                    <?php if($user->role != 'admin'): ?>
                                        <!-- TRIGGER DIUBAH DENGAN MODAL BOOTSTRAP DINAMIS MENGGUNAKAN BUTTON DATA ATTRIBUTE -->
                                        <button type="button" 
                                                class="btn p-0 d-flex align-items-center justify-content-center text-danger hover-action-btn shadow-none"
                                                style="width: 32px; height: 32px; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px; transition: all 0.2s ease;"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#confirmDeleteModal"
                                                data-user-id="<?php echo e($user->id); ?>"
                                                data-user-name="<?php echo e($user->name); ?>"
                                                title="Hapus Data">
                                            <i class="bi bi-trash" style="font-size: 14px;"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        
                        <tr id="emptyRow">
                            <td colspan="5" class="text-center py-5" style="background-color: #ffffff;">
                                <div class="py-4 opacity-75">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 bg-light text-secondary" style="width: 56px; height: 56px;">
                                        <i class="bi bi-people-fill fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Pengguna</h6>
                                    <p class="text-muted small mb-0 px-3">Sistem tidak mendeteksi data user. Silakan klik tombol <strong>"Tambah User"</strong> untuk meregistrasikan akun baru.</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>

                        
                        <tr id="searchEmptyRow" style="display: none;">
                            <td colspan="5" class="text-center py-5" style="background-color: #ffffff;">
                                <div class="py-4 opacity-75">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 bg-light text-secondary" style="width: 56px; height: 56px;">
                                        <i class="bi bi-search fs-3"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Data Tidak Ditemukan</h6>
                                    <p class="text-muted small mb-0">Kata kunci atau filter role yang Anda masukkan tidak cocok dengan data manapun.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            
            <?php if(method_exists($users, 'links') && $users->hasPages()): ?>
                <div class="card-footer bg-white py-3 px-4 border-top d-flex justify-content-end">
                    <div class="pagination-sm mb-0">
                        <?php echo e($users->links()); ?>

                    </div>
                </div>
            <?php endif; ?>

        </div>
        
        
        <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 4px;"></div>
    </div>
</div>


<div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content modal-saas">
            <div class="modal-header modal-saas-header justify-content-center position-relative">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
                <button type="button" class="btn-close position-absolute" style="top: 20px; right: 20px;" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-saas-body text-center">
                <h5 class="fw-bold text-dark mb-2">Hapus Akun Pengguna?</h5>
                <p class="text-muted mb-0 small px-2">Tindakan ini akan mencabut hak akses masuk <strong id="deleteModalTargetName" class="text-dark"></strong> secara permanen dari sistem manajemen SDN Tengah 03.</p>
            </div>
            <div class="modal-footer modal-saas-footer d-flex gap-2">
                <button type="button" class="btn btn-saas-secondary flex-grow-1 py-2 fw-semibold" data-bs-dismiss="modal">Tidak, Batal</button>
                
                <!-- BINDING ROUTE DIKONTROL OLEH FORM ACTION SECARA DINAMIS VIA JAVASCRIPT -->
                <form id="deleteModalForm" method="POST" class="flex-grow-1 m-0">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger btn-saas-primary w-100 py-2 fw-semibold shadow-none">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>


<style>
    body {
        background-color: #f8fafc;
    }
    .shadow-premium {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
    }
    .search-merge-group {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    .search-merge-group:focus-within {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
    }
    .search-merge-group .form-control,
    .search-merge-group .form-select,
    .search-merge-group .input-group-text {
        border: none !important;
        background-color: #f8f9fa !important;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 14px 16px !important;
        background-color: #f8fafc !important;
        border-bottom: 2px solid #edeff1 !important;
    }
    .table td {
        padding: 14px 16px !important;
        border-bottom: 1px solid #f1f3f5 !important;
    }
    .user-row:hover {
        background-color: #fcfdfe !important;
    }
    .hover-up:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.2) !important;
    }
    .hover-action-btn:hover {
        transform: translateY(-1px);
    }
    .hover-action-btn.text-warning:hover {
        background-color: #f59e0b !important;
        color: #ffffff !important;
        border-color: #f59e0b !important;
    }
    .hover-action-btn.text-danger:hover {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        border-color: #ef4444 !important;
    }

    /* Premium SaaS Modal Confirmation Stylings */
    .modal-saas {
        border-radius: 20px !important;
        border: none !important;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1) !important;
    }
    .modal-saas-header {
        border-bottom: none !important;
        padding: 24px 24px 8px 24px !important;
    }
    .modal-saas-body {
        padding: 8px 24px 24px 24px !important;
    }
    .modal-saas-footer {
        border-top: none !important;
        padding: 0 24px 28px 24px !important;
        background: transparent !important;
    }
    .btn-saas-secondary {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        font-size: 0.9rem;
    }
    .btn-saas-secondary:hover {
        background-color: #e2e8f0 !important;
    }
    .btn-saas-primary {
        border-radius: 10px !important;
        font-size: 0.9rem;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const rows = document.querySelectorAll('.user-row');
        const searchEmptyRow = document.getElementById('searchEmptyRow');
        const staticEmptyRow = document.getElementById('emptyRow');

        // Logic Modal Dinamis
        const confirmDeleteModal = document.getElementById('confirmDeleteModal');
        if (confirmDeleteModal) {
            confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                const modalForm = confirmDeleteModal.querySelector('#deleteModalForm');
                const modalTargetName = confirmDeleteModal.querySelector('#deleteModalTargetName');
                
                // Set form action sesuai route destroy user secara dinamis
                modalForm.action = `/users/${userId}`;
                modalTargetName.textContent = userName;
            });
        }

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const filterValue = roleFilter.value;
            let visibleCount = 0;

            if (staticEmptyRow) return;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                const role = row.getAttribute('data-role');

                const matchesSearch = name.includes(searchValue) || email.includes(searchValue);
                const matchesRole = (filterValue === 'all') || (role === filterValue);

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                searchEmptyRow.style.display = '';
            } else {
                searchEmptyRow.style.display = 'none';
            }
        }

        if (searchInput && roleFilter) {
            searchInput.addEventListener('input', filterTable);
            roleFilter.addEventListener('change', filterTable);
        }
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/users/index.blade.php ENDPATH**/ ?>