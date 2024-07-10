<form action="/send-mail" method="POST">
    <button type="submit">send mail</button>
</form>



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