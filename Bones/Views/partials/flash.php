<?php if(\Bones\Core\Flash::has('flash', 'error')): ?>
    <div class="alert alert-danger">
    <?php foreach(\Bones\Core\Flash::get('error') as $error): ?>
        <?=$error?><br>
    <?php endforeach; ?>
    </div>
<?php elseif(\Bones\Core\Flash::has('flash', 'success')): ?>
    <div class="alert alert-success">
        <?php foreach(\Bones\Core\Flash::get('success') as $success): ?>
            <?=$success?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
