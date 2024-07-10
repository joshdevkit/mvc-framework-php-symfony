<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 2rem;
    }

    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-control {
        border: 1px solid #ced4da;
        padding: 1.5rem 1.5rem 1.5rem 1.5rem;
        transition: border-color 0.3s, box-shadow 0.3s;
        border-radius: 5px;
        background-color: #f8f9fa;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        background-color: #fff;
    }

    .form-group label {
        position: absolute;
        top: 0.75rem;
        left: 1.5rem;
        pointer-events: none;
        transition: 0.3s;
        color: #6c757d;
        font-size: 1rem;
    }

    .form-control:focus+label,
    .form-control:not(:placeholder-shown)+label {
        top: -0.5rem;
        left: 1.5rem;
        font-size: 0.85rem;
        color: #007bff;
        background-color: #fff;
        padding: 0 0.25rem;
    }
</style>

<div class="row justify-content-center align-items-center">
    <div class="col-md-7 col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-body">
                <?php if (isset($errors['auth_failed'])) : ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($errors['auth_failed']) ?>
                    </div>
                <?php endif; ?>
                <div class=" text-center">
                    <p>Sign in to start your session!</p>
                </div>
                <form action="/login" method="POST">
                    <div class="form-group">
                        <input class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" type="email" name="email" value="<?= htmlspecialchars($input['email'] ?? '') ?>" placeholder=" ">
                        <label>Email Address</label>
                        <?php if (isset($errors['email'])) : ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['email'][0]) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <input class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" type="password" name="password" value="<?= htmlspecialchars($input['password'] ?? '') ?>" placeholder=" ">
                        <label>Password</label>
                        <?php if (isset($errors['password'])) : ?>
                            <div class="invalid-feedback">
                                <?= htmlspecialchars($errors['password'][0]) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit">Sign In</button>
                    </div>
                    <div class="col-md-12 mb-2 mt-2 text-center">
                        <a class="text-decoration-none" href="">Forgot Password?</a>
                    </div>
                    <div class="form-group text-center">
                        <a href="/register" class="btn btn_custom">Don't have an Account? Register Here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>