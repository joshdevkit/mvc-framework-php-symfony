<div class="jumbotron">
    <div class="row">
        <div class="col-md-6">
            <h1 class="display-4">Profile</h1>
            <p class="lead">Welcome, <?= htmlspecialchars($user->name) ?>!</p>
        </div>
        <div class="col-md-6 text-right">
            <?php if (empty($user->info[0]->profile_picture)) : ?>
                <img style="border-radius: 50%;" height="140" width="130" class="border border-2 border-dark" src="/images/default.webp" alt="Profile Picture">
            <?php elseif (!empty($user->info[0]->profile_picture)) : ?>
                <img style="border-radius: 50%;" height="140" width="130" class="border border-2 border-dark" src="<?= htmlspecialchars($user->info[0]->profile_picture) ?>" alt="Profile Picture">
            <?php endif; ?>
        </div>
    </div>
    <hr class="my-4">
    <div class="row">
        <div class="col-md-6">
            <p>Email: <?= htmlspecialchars($user->email) ?></p>
            <?php if (!empty($user->info[0]->contact)) : ?>
                <p>Contact Number: <?= htmlspecialchars($user->info[0]->contact) ?></p>
            <?php endif; ?>
            <?php if (!empty($user->info[0]->address)) : ?>
                <p>Address: <?= htmlspecialchars($user->info[0]->address) ?></p>
            <?php endif; ?>
        </div>
    </div>
    <a class="btn btn-primary btn-lg" href="/update-profile" role="button">Edit Profile</a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php

        use App\Framework\Session\Flash;

        if ($flashMessage = Flash::get('success')) : ?>
            toastr.success('<?= htmlspecialchars($flashMessage) ?>');
        <?php elseif ($flashMessage = Flash::get('error')) : ?>
            toastr.error('<?= htmlspecialchars($flashMessage) ?>');
        <?php endif; ?>
    });
</script>