<?php

namespace app\controller;
use app\base\Controller;
use app\model\MovieModel;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $models = MovieModel::find();

        return $this->view->render("index", ["movies" => $models]);
    }

    public function actionDetailedView()
    {
        $model = MovieModel::find(["id" => $_POST["movieId"]]);
        $model = $model[0] ?? null;

        return $this->view->renderWithoutLayout("detailedViewPopup", ["model" => $model]);
    }

    public function actionEditView()
    {
        $model = MovieModel::find(["id" => $_POST["movieId"]]);
        $model = $model[0] ?? null;

        return $this->view->renderWithoutLayout("editViewPopup", ["model" => $model]);
    }

    public function actionSaveMovie()
    {
        $model = new MovieModel($_REQUEST["MovieModel"] ?? []);
        $res = !empty($model->getAttributes()) ? "success" : "fail";

        if($res === "success"){
            if(isset($model->id))
                $model->update();
            else
                $model->save();
        }

        return json_encode(["$res" => 1], JSON_UNESCAPED_UNICODE);
    }

    public function actionAddMovieView()
    {
        return $this->view->renderWithoutLayout("addMoviePopup");
    }

    public function actionImportFile()
    {
        if(isset($_FILES["MoviesFile"])){
            MovieModel::importFromFile($_FILES["MoviesFile"]["tmp_name"], $_FILES["MoviesFile"]["name"]);
            return json_encode(["success" => "1"], JSON_UNESCAPED_UNICODE);
        }
        return $this->view->renderWithoutLayout("importFilePopup");
    }

    public function actionDeleteMovie()
    {
        $model = MovieModel::find(["id" => $_POST["movieId"]]);
        $model = $model[0] ?? null;

        if(isset($_POST["deleteMovie"]) && $_POST["deleteMovie"] === "delete"){
            $res = $model->delete();
            return json_encode($res ? ["success" => 1] : ["error" => 1],JSON_UNESCAPED_UNICODE);
        }

        return $this->view->renderWithoutLayout("confirmPopup", ["model" => $model]);
    }

    public function actionRenderMovies()
    {
        $query = $_POST["query"] ?? "";
        $query = strtolower(trim($query));
        $models = MovieModel::find(!empty($query) ? [["LIKE", $_POST["type"] === "title" ? "name" : "actors", "'%$query%'"]] : []);

        return $this->view->renderWithoutLayout("moviesList",["movies" => $models]);
    }
}