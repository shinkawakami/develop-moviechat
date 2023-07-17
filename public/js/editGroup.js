document.addEventListener('DOMContentLoaded', () => {
    const movieSearchButton = document.getElementById('movie-search-btn');
    const movieSearchInput = document.getElementById('movie-search');
    const movieSearchResults = document.getElementById('movie-search-results');
    const selectedMovies = document.getElementById('movies');
    const selectedMoviesContainer = document.getElementById('selected-movies');
    let selectedMovieIds = selectedMovies.value ? selectedMovies.value.split(',').map(Number) : [];
    let currentPage = 1;

    // Display already selected movies
    selectedMovieIds.forEach((movieId) => {
        fetchMovie(movieId);
    });

    function fetchMovie(movieId) {
        const url = '/moviechat/movies/' + movieId;
        fetch(url)
            .then(response => response.json())
            .then(movie => {
                displaySelectedMovie(movie);
            });
    }

    function displaySelectedMovie(movie) {
        const selectedMovieTitle = document.createElement('p');
        selectedMovieTitle.textContent = movie.title;
        selectedMoviesContainer.appendChild(selectedMovieTitle);

        const removeButton = document.createElement('button');
        removeButton.textContent = '取り消し';
        removeButton.addEventListener('click', (event) => {
            event.preventDefault();
            const index = selectedMovieIds.indexOf(movie.id);
            selectedMovieIds.splice(index, 1);
            selectedMovies.value = selectedMovieIds.join(',');
            selectedMovieTitle.remove();
            removeButton.remove();
        });
        selectedMoviesContainer.appendChild(removeButton);
    }

    movieSearchButton.addEventListener('click', (event) => {
        event.preventDefault();
        const query = movieSearchInput.value;
        fetchMovies(query);
    });

    function fetchMovies(query, page = 1) {
        const url = '/moviechat/movies/search?query=' + query + '&page=' + page;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const movies = data.results;
                const totalPages = data.total_pages;
                movieSearchResults.innerHTML = '';
                movies.forEach(movie => {
                    displaySearchedMovie(movie);
                });
                if (currentPage < totalPages) {
                    const nextPageButton = document.createElement('button');
                    nextPageButton.textContent = '次へ';
                    nextPageButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        currentPage++;
                        fetchMovies(query, currentPage);
                    });
                    movieSearchResults.appendChild(nextPageButton);
                }
                if (currentPage > 1) {
                    const prevPageButton = document.createElement('button');
                    prevPageButton.textContent = '前へ';
                    prevPageButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        currentPage--;
                        fetchMovies(query, currentPage);
                    });
                    movieSearchResults.appendChild(prevPageButton);
                }
            });
    }

    function displaySearchedMovie(movie) {
        const movieContainer = document.createElement('div');
        const movieTitle = document.createElement('p');
        movieTitle.textContent = movie.title;
        movieContainer.appendChild(movieTitle);
        const movieImage = document.createElement('img');
        movieImage.src = 'https://image.tmdb.org/t/p/w500' + movie.poster_path;
        movieContainer.appendChild(movieImage);
        const movieOverview = document.createElement('p');
        movieOverview.textContent = movie.overview;
        movieContainer.appendChild(movieOverview);
        const selectButton = document.createElement('button');
        selectButton.textContent = selectedMovieIds.includes(movie.id) ? '選択済み' : '選択';
        selectButton.disabled = selectedMovieIds.includes(movie.id);
        selectButton.addEventListener('click', (event) => {
            event.preventDefault();
            selectedMovieIds.push(movie.id);
            selectedMovies.value += ',' + movie.id;
            displaySelectedMovie(movie);
            selectButton.textContent = '選択済み';
            selectButton.disabled = true;
        });
        movieContainer.appendChild(selectButton);
        movieSearchResults.appendChild(movieContainer);
    }
});
