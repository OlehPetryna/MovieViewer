<?php
/**
 * @var $movies \app\model\MovieModel []
 */

if(empty($movies)):?>
    <h3>No movies</h3>
<?php endif;

foreach ($movies as $movie): ?>
    <div class="single-movie-row" data-id="<?= $movie->id ?>">
        <div class="single-movie-row__id"><?= $movie->id ?></div>
        <div class="single-movie-row__name"><?= $movie->name ?></div>
        <div class="single-movie-row__release-year"><?= $movie->releaseYear ?></div>
        <div class="single-movie-row__format"><?= $movie->format ?></div>
        <div class="single-movie-row__actors"><?= implode(', ', $movie->actors) ?></div>
        <div class="single-movie-row__controls">
            <div class="movie-control__detailed"></div>
            <div class="movie-control__edit"></div>
            <div class="movie-control__delete"></div>
        </div>
    </div>
<?php endforeach; ?>