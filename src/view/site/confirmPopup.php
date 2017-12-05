<?php /**@var $model \app\model\MovieModel*/?>
<div class="popup">
    <div class="popup__body">
        <div class="popup__close-popup">X</div>
        <div class="popup__content">
            <p class="confirm-delete__confirm-message">Are you sure? </p>
            <button class="confirm-delete__confirm-action" data-movie="<?=$model->id?>">Delete</button>
            <button class="confirm-delete__cancel-action">Cancel</button>
            <p class="confirm-delete__message"></p>
        </div>
    </div>
</div>