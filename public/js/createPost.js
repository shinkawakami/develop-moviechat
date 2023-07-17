document.addEventListener('DOMContentLoaded', () => {
    const movieSearchButton = document.getElementById('movie-search-btn');
    const movieSearchInput = document.getElementById('movie-search');
    const movieSearchResults = document.getElementById('movie-search-results');
    const selectedMovie = document.getElementById('movie');
    const selectedMovieContainer = document.getElementById('selected-movie');
    let selectedMovieId = null;  // To keep track of selected movie id
    let selectButtons = [];  // To keep track of select buttons
    let currentPage = 1;

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
                selectButtons = [];  // Clear the select buttons
    
                movies.forEach(movie => {
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
                    selectButton.textContent = selectedMovieId === movie.id ? '選択済み' : '選択';
                    selectButton.disabled = selectedMovieId === movie.id;
                    selectButton.addEventListener('click', (event) => {
                        event.preventDefault();

                        selectedMovieId = movie.id;
                        selectedMovie.value = movie.id;

                        selectedMovieContainer.innerHTML = '';  // Clear the existing movie title

                        const selectedMovieTitle = document.createElement('p');
                        selectedMovieTitle.textContent = movie.title;
                        selectedMovieContainer.appendChild(selectedMovieTitle);

                        const removeButton = document.createElement('button');
                        removeButton.textContent = '取り消し';
                        removeButton.addEventListener('click', (event) => {
                            event.preventDefault();

                            selectedMovieId = null;
                            selectedMovie.value = '';
                            selectedMovieTitle.remove();
                            removeButton.remove();

                            updateSelectButtons();
                        });
                        selectedMovieContainer.appendChild(removeButton);

                        updateSelectButtons();
                    });
                    movieContainer.appendChild(selectButton);

                    selectButtons.push({ id: movie.id, button: selectButton });  // Add the select button to the array

                    movieSearchResults.appendChild(movieContainer);
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

    function updateSelectButtons() {
        selectButtons.forEach(({ id, button }) => {
            button.textContent = selectedMovieId === id ? '選択済み' : '選択';
            button.disabled = selectedMovieId === id;
        });
    }
});
