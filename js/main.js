/**POPUP**/
var Popup = {
    showLoader: function () {
        var div = document.createElement("div");
        div.className = "popup__loader-wrapper";
        div.innerHTML = "<div class='popup__loader'></div>";

        document.querySelector("body .content").appendChild(div);
    },
    removeLoader:function () {
        document.querySelector("body .content .popup__loader-wrapper").remove();
    },
    renderPopup:function (url,data, onClose) {
        var $this = this;
        onClose = onClose || function () {};

        var xhr = new XMLHttpRequest();
        var body = JSON.stringify(data);


        this.showLoader();

        xhr.open("POST", url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(body);


        xhr.onreadystatechange = function () {
            if (this.readyState !== 4)
                return;


            var data = document.createElement('div');
            data.innerHTML = this.response;
            data = data.querySelector(".popup");

            document.querySelector("body .content").appendChild(data);
            $this.removeLoader();
            $this.popup = {
                popup:data,
                onClose:onClose
            };

            window.addEventListener("keyup",function (ev) {
                ev.keyCode === 27 && $this.closePopup();
            })
        }
    },
    closePopup:function () {
        this.popup.popup.remove();
        this.popup.onClose();
        window.removeEventListener("keyup",function (ev) {
            ev.keyCode === 27 && $this.closePopup();
        })
    }
};

/**END OF POPUP**/

function ajaxRequest(method, body, url, callback, context, btn, contentType) {
    var xhr = new XMLHttpRequest();
    callback = callback || function () {
    };

    if(btn){
        var btnHtml = btn.innerHTML;
        btn.innerHTML = "<span class='btn-loader'></span>";
    }

    xhr.open(method, url, true);
    if(contentType)
        xhr.setRequestHeader('Content-Type', contentType);

    xhr.send(body);

    xhr.onreadystatechange = function (ev) {
        if (this.readyState !== 4)
            return;
        if(btn)
            btn.innerHTML = btnHtml;

        if(this.status !== 200){
            callback.call(context, {error:this.statusText});
            return;
        }

        callback.call(context, this.response);

    }
}

var lastSearchReq = 0;
var prevSearchQueryType = "";

var searchInputs = document.querySelectorAll("input[class^='control-output__search-']");
searchInputs.forEach(function (value) {
    value.addEventListener("keyup",function () {
        var queryType = value.className === "control-output__search-name-input" ? "title" : "actors";
        if(value.value.length >= 1 && Date.now() - lastSearchReq >= 250){
            lastSearchReq = Date.now();
            prevSearchQueryType = queryType;
            renderMoviesList(JSON.stringify({query:value.value, type:queryType}));
        }else if(value.value.length === 0 && prevSearchQueryType === queryType){
            lastSearchReq = Date.now();
            renderMoviesList();
        }
    });
});


function getSearchBarInput () {
    var res = [];
    if(searchInputs[0].value.length > 0)
        res.push({query:searchInputs[0].value, type:searchInputs[0].className === "control-output__search-name-input" ? "title" : "actors"});
    if(searchInputs[1].value.length > 0)
        res.push({query:searchInputs[1].value, type:searchInputs[1].className === "control-output__search-name-input" ? "title" : "actors"});

    return res;

}

function renderMoviesList(query) {
    var body = document.querySelector(".movies-list");
    var data = query || {};
    ajaxRequest("POST",data,"render-movies",function (data) {
        var div = document.createElement("div");
        div.className = "movies";
        div.innerHTML = data;
        body.replaceChild(div,document.querySelector(".movies"));
    }, this, null, "application/json");
}

document.querySelector('body').addEventListener('click', function (ev) {
    var btn, id, data;
    if (ev.target.className === "popup")
        Popup.closePopup();

    if (ev.target.className === "popup__close-popup")
        Popup.closePopup();

    if (ev.target.className.indexOf("movie-control__") !== -1) {
        btn = ev.target;

        id = btn.parentNode.parentNode.getAttribute("data-id");
        data = {movieId: id};
        var search;
        if (btn.className === "movie-control__edit") {
            Popup.renderPopup("edit-movie", data,function() {
                search = getSearchBarInput();
                renderMoviesList(search.length > 0 ? JSON.stringify(search[0]) : {});
            });
        } else if (btn.className === "movie-control__detailed") {
            Popup.renderPopup("show-details", data, function () {
                search = getSearchBarInput();
                renderMoviesList(search.length > 0 ? JSON.stringify(search[0]) : {});
            });
        } else if (btn.className === "movie-control__delete") {
            Popup.renderPopup("delete-movie", data, function() {
                search = getSearchBarInput();
                renderMoviesList(search.length > 0 ? JSON.stringify(search[0]) : {});
            });
        }
    }

    if(ev.target.className === "confirm-delete__confirm-action"){
        btn = ev.target;
        id = btn.getAttribute("data-movie");
        data = JSON.stringify({movieId:id, deleteMovie:"delete"});
        ajaxRequest("POST",data,"delete-movie",function(data){
            data = JSON.parse(data);
            if(data.success)
                Popup.closePopup();
            else
                document.querySelector(".confirm-delete__message").innerHTML = data.success ? "Movie deleted" : "Error while deleting";
        }, this, btn, "application/json");
    }

    if(ev.target.className === "confirm-delete__cancel-action"){
        Popup.closePopup();
    }

    if(ev.target.className.indexOf("control-output__search-") !== -1 && ev.target.tagName === "INPUT"){

    }
});
document.querySelector('body').addEventListener('submit', function (ev) {
    var form,
        data;
    if (ev.target.className === "movie-edit" || ev.target.className === "movie-save-new") {
        ev.preventDefault();
        form = ev.target;
        var $this = this;
        data = new FormData();

        if (form.className === "movie-edit")
            data.append("MovieModel[id]", form.querySelector("[name='MovieModel[id]']").value.trim());
        data.append("MovieModel[name]", form.querySelector("[name='MovieModel[name]']").value.trim());
        data.append("MovieModel[format]", form.querySelector("[name='MovieModel[format]']").value.trim());
        data.append("MovieModel[releaseYear]", form.querySelector("[name='MovieModel[releaseYear]']").value.trim());
        data.append("MovieModel[actors]", JSON.stringify(form.querySelector("[name='MovieModel[actors]']").value.trim().split(', ')));

        ajaxRequest("POST", data, "save-movie", function (data) {
            if(data.error)
                ev.target.querySelector(".movie-edit__response-msg").innerHTML = data.error;
            else{
                data = JSON.parse(data);
                if(data.success && form.className === "movie-save-new")
                    Popup.closePopup();
                else
                    ev.target.querySelector(".movie-edit__response-msg").innerHTML = data.success ? "Data saved": "Error while saving";
            }
        }, $this, document.querySelector(".movie-edit__save-btn"));
    } else if (ev.target.className === "import-file") {
        ev.preventDefault();
        form = ev.target;
        data = new FormData();
        data.append("MoviesFile", form.querySelector("input").files[0]);
        ajaxRequest("POST", data, "import-movie", function (data) {
            if(data.error)
                document.querySelector(".import-file__message").innerHTML = data.error;
            else {
                data = JSON.parse(data);
                document.querySelector(".import-file__message").innerHTML = data.success ? "Successfully imported movies" : "Error while importing movies";
            }
        }, this, document.querySelector(".import-file__save-btn"));
    }

});


document.querySelector(".manage-movie__add-new").addEventListener("click", function () {
    Popup.renderPopup("add-movie", {},function() {
        renderMoviesList();
    });
});

document.querySelector(".manage-movie__import-from-file").addEventListener("click", function () {
    Popup.renderPopup("import-movie", {}, function() {
        renderMoviesList();
    });
});