<?php
/**
 * @var $movies \app\model\MovieModel[]
 * @var $this \app\base\View
 */
?>
<div class="container">
    <div class="movies-list">
        <div class="control-output">
            <div class="control-output__search-panel">
                <span class="control-output__search-hint">Search</span>
                <input type="text" class="control-output__search-name-input" placeholder="Type movie name">
                <input type="text" class="control-output__search-actors-input" placeholder="Type actor name">
            </div>
            <div class="control-output__manage-movie-block">
                <div class="manage-movie__add-new">
                    Add new movie
                </div>
                <div class="manage-movie__import-from-file">
                    Import from file
                </div>
            </div>
        </div>
        <div class="movies">
            <?=$this->renderWithoutLayout("moviesList",["movies" => $movies])?>
        </div>
    </div>
</div>