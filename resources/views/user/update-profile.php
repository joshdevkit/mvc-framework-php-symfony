<style>
    .profile-card {
        max-width: 400px;
        margin: 0 auto;
        border: none;

    }

    .profile-img {
        width: 150px;
        height: 150px;
        object-fit: contain;
        margin-top: -75px;
        border: 1px solid black;
    }

    .card-title {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .card-subtitle {
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .card-text {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .card-header {
        background-color: #fff;
    }

    @media (max-width: 576px) {
        .profile-card {
            max-width: 90%;
        }
    }
</style>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="col-md-12 mt-2" id="infoCard">
                        <form action="/update-profile" method="POST" enctype="multipart/form-data">
                            <div class="card profile-card">
                                <div class="card-header text-center">
                                    <label for="profilePicture">
                                        <img style="cursor:pointer;" src="/images/default.webp" alt="Profile Picture" class="rounded-circle profile-img border border-3 border-dark <?= isset($errors['profilePicture']) ? 'is-invalid' : '' ?>" id="profileImgPreview">
                                    </label>
                                    <input type="file" id="profilePicture" name="profilePicture" style="display: none;" accept="image/*" onchange="previewProfilePicture(event)">
                                    <?php
                                    if (isset($errors['profilePicture'])) : ?>
                                        <div class="alert alert-danger">
                                            <?= htmlspecialchars($errors['profilePicture'][0]) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title text-center"><?= $user->name; ?></h3>
                                    <h5 class="card-subtitle mb-2 text-muted text-center"><?= $user->email; ?></h5>
                                    <div class="form-group">
                                        <label class="fw-bold">Address</label>

                                        <input type="text" name="address" value="<?= $user->info[0]->address ?? '' ?>" class="form-control <?= isset($errors['address']) ? 'is-invalid' : '' ?>">
                                        <?php
                                        if (isset($errors['address'])) : ?>
                                            <div class="invalid-feedback">
                                                <?= htmlspecialchars($errors['address'][0]) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label class="fw-bold">Contact</label>
                                        <input type="number" value="<?= $user->info[0]->contact ?? '' ?>" name="contact" id="contact" class="form-control <?= isset($errors['contact']) ? 'is-invalid' : '' ?>">
                                        <?php if (isset($errors['contact'])) : ?>
                                            <div class="invalid-feedback">
                                                <?= htmlspecialchars($errors['contact'][0]) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
                                    <button type="button" class="btn btn-light mt-2 btn-block" data-toggle="modal" data-target="#changePasswordModal">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="/change-password" method="POST">
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" placeholder="Enter current password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block">Change Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<script>
    function previewProfilePicture(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('profileImgPreview').src = e.target.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>