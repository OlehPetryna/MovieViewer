<div class="popup">
    <div class="popup__body">
        <div class="popup__close-popup">X</div>
        <div class="popup__content">
            <form class="movie-save-new" action="">
                <label class="movie-edit__edit-name" >
                    Movie name
                    <input type="text" name="MovieModel[name]" required>
                </label>
                <label class="movie-edit__edit-release-year">
                    Release Year
                    <input type="text" name="MovieModel[releaseYear]" required>
                </label>
                <label class="movie-edit__edit-format">
                    Format
                    <select name="MovieModel[format]" required>
                        <option value="DVD">DVD</option>
                        <option value="VHS">VHS</option>
                        <option value="Blu-ray">Blu-ray</option>
                    </select>
                </label>
                <label class="movie-edit__edit-actors">
                    Actors
                    <textarea required name="MovieModel[actors]"></textarea>
                </label>
                <button class="movie-edit__save-btn" type="submit">Save</button>
                <span class="movie-edit__response-msg"></span>
            </form>
        </div>
    </div>
</div>