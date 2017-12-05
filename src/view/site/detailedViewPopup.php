<?php
/**
 * @var $model \app\model\MovieModel
 */
?>

<div class="popup">
    <div class="popup__body">
        <div class="popup__close-popup">X</div>
        <div class="popup__content">
            <table class="movie-detailed-view">
                <thead>
                    <th class="movie-detailed-view__cell-header">Key</th>
                    <th class="movie-detailed-view__cell-header">Value</th>
                </thead>
            <tbody>
            <?php foreach ($model as $key => $value):?>
                <tr class="movie-detailed-view__movie-row">
                    <td class="movie-detailed-view__value-cell"><?=$key?></td>
                    <td class="movie-detailed-view__value-cell"><?=is_array($value) ? implode("<br>", $value) : $value?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>
</div>