document.addEventListener('DOMContentLoaded', () => {
    const searchButton = document.getElementById('search-btn');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');
    const selectedMoviesContainer = document.getElementById('selected-movies');
    let selectedMovieIds = [];  
    let currentPage = 1;
 
    window.groupMovies.forEach(movie => {
        selectedMovieIds.push(movie.tmdb_id); 
        addSelectedMovie(movie);
    });
 
    searchButton.addEventListener('click', (event) => {
        event.preventDefault();

        const query = searchInput.value;
        fetchMovies(query);
    });

    function fetchMovies(query, page = 1) {
        const url = '/moviechat/movies/search?query=' + query + '&page=' + page;
    
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const movies = data.results;
                const totalPages = data.total_pages;
                searchResults.innerHTML = '';
    
                movies.forEach(movie => {
                    const movieContainer = document.createElement('div');
                    movieContainer.className = "movie-container";
    
                    const movieTitle = document.createElement('p');
                    movieTitle.textContent = movie.title;
                    movieTitle.className = "movie-title";
                    movieContainer.appendChild(movieTitle);
    
                    const movieImage = document.createElement('img');
                    movieImage.src = 'https://image.tmdb.org/t/p/w500' + movie.poster_path;
                    movieContainer.appendChild(movieImage);
    
                    const movieOverview = document.createElement('p');
                    movieOverview.textContent = movie.overview;
                    movieOverview.className ="movie-overview";
                    movieContainer.appendChild(movieOverview);
    
                    const selectButton = document.createElement('button');
                    selectButton.textContent = selectedMovieIds.includes(movie.id) ? '選択済み' : '選択';
                    selectButton.disabled = selectedMovieIds.includes(movie.id);

                    selectButton.addEventListener('click', (event) => {
                        event.preventDefault();

                        if (!selectedMovieIds.includes(movie.id)) {
                            selectedMovieIds.push(movie.id);
                            
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'movies[]';
                            hiddenInput.value = movie.id;
                            document.getElementById('movies').appendChild(hiddenInput);
                            
                            const selectedMovieTitle = document.createElement('p');
                            selectedMovieTitle.textContent = movie.title;
                            selectedMoviesContainer.appendChild(selectedMovieTitle);
                            selectedMovieTitle.classList.add('tag', 'is-danger');
                            
                            const removeButton = document.createElement('button');
                            removeButton.textContent = '取り消し';
                            removeButton.className ="remove-button"
                            removeButton.addEventListener('click', (event) => {
                                event.preventDefault();

                                const index = selectedMovieIds.indexOf(movie.id);
                                selectedMovieIds.splice(index, 1);
                                selectedMovieTitle.remove();
                                removeButton.remove();
                                
                                document.querySelector(`input[name="movies[]"][value="${movie.id}"]`).remove();

                                selectButton.textContent = '選択';
                                selectButton.disabled = false;
                                
                                if (selectedMoviesContainer.childElementCount === 0) { 
                                    selectedMoviesContainer.classList.remove('tag', 'is-danger'); 
                                }
                            });
                            selectedMoviesContainer.appendChild(removeButton);
                            selectButton.textContent = '選択済み';
                            selectButton.disabled = true;
                        }
                    });

                    movieContainer.appendChild(selectButton);
    
                    searchResults.appendChild(movieContainer);
    
                });

                const paginationDiv = document.createElement('div');
                paginationDiv.classList.add('pagination');
                
                if (currentPage > 1) {
                    const prevPageButton = document.createElement('button');
                    prevPageButton.textContent = '前へ';
                    prevPageButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        currentPage--;
                        fetchMovies(query, currentPage);
                    });
                    paginationDiv.appendChild(prevPageButton);
                } else {
                    paginationDiv.appendChild(document.createElement('div'));
                }
                
                if (currentPage < totalPages) {
                    const nextPageButton = document.createElement('button');
                    nextPageButton.textContent = '次へ';
                    nextPageButton.addEventListener('click', (event) => {
                        event.preventDefault();
                        currentPage++;
                        fetchMovies(query, currentPage);
                    });
                    paginationDiv.appendChild(nextPageButton);
                } else {
                    paginationDiv.appendChild(document.createElement('div'));
                }

                searchResults.appendChild(paginationDiv);

            });
    }
    
    function addSelectedMovie(movie) {
        const selectedMovieTitle = document.createElement('p');
        selectedMovieTitle.textContent = movie.title;
        selectedMoviesContainer.appendChild(selectedMovieTitle);
        selectedMovieTitle.classList.add('tag', 'is-danger');
    
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'movies[]';
        hiddenInput.value = movie.tmdb_id;
        document.getElementById('movies').appendChild(hiddenInput);
    
        const removeButton = document.createElement('button');
        removeButton.textContent = '取り消し';
        removeButton.className = "remove-button";
        removeButton.addEventListener('click', (event) => {
            event.preventDefault();
            
            const index = selectedMovieIds.indexOf(movie.tmdb_id);
            selectedMovieIds.splice(index, 1);
            selectedMovieTitle.remove();
            removeButton.remove();
    
            document.querySelector(`input[name="movies[]"][value="${movie.tmdb_id}"]`).remove();
            
            const movieInSearchResults = [...document.querySelectorAll('.movie-container .movie-title')].find(el => el.textContent === movie.title);
                if (movieInSearchResults) {
                    const correspondingSelectButton = movieInSearchResults.parentElement.querySelector('button');
                    correspondingSelectButton.textContent = '選択';
                    correspondingSelectButton.disabled = false;
                }
            
                if (selectedMoviesContainer.childElementCount === 0) {  
                    selectedMoviesContainer.classList.remove('tag', 'is-danger');  
                }
        });
        selectedMoviesContainer.appendChild(removeButton);
    }
});







