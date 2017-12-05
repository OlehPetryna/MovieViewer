<?php
/**
 * @var $model \app\model\MovieModel
 */
?>

<div class="popup">
    <div class="popup__body">
        <div class="popup__close-popup">X</div>
        <div class="popup__content">
            <form class="movie-edit" action="">
                <input name="MovieModel[id]"  required type="hidden" value="<?=$model->id?>">
                <label class="movie-edit__edit-name">
                    Movie name
                    <input type="text" name="MovieModel[name]" required value="<?=$model->name?>">
                </label>
                <label class="movie-edit__edit-release-year">
                    Release Year
                    <input type="text" name="MovieModel[releaseYear]" required value="<?=$model->releaseYear?>">
                </label>
                <label class="movie-edit__edit-format">
                    Format
                    <select name="MovieModel[format]">
                        <option value="DVD" <?=$model->format === "DVD" ? "selected" : ""?>>DVD</option>
                        <option value="VHS" <?=$model->format === "VHS" ? "selected" : ""?> >VHS</option>
                        <option value="Blu-ray" <?=$model->format === "Blu-ray" ? "selected" : ""?>>Blu-ray</option>
                    </select>
                </label>
                <label class="movie-edit__edit-actors">
                    Actors
                    <textarea name="MovieModel[actors]"  required><?=implode(", ", $model->actors)?></textarea>
                </label>
                <button class="movie-edit__save-btn" type="submit">Save</button>
                <span class="movie-edit__response-msg"></span>
            </form>
        </div>
    </div>
</div>