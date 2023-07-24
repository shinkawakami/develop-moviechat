document.addEventListener('DOMContentLoaded', () => {
    const searchButton = document.getElementById('search-btn');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');
    const selectedMovie = document.getElementById('movie');
    const selectedMovieContainer = document.getElementById('selected-movie');
    let selectedMovieId = null;  // To keep track of selected movie id
    let selectButtons = [];  // To keep track of select buttons
    let currentPage = 1;

   
    searchButton.addEventListener('click', (event) => {
        event.preventDefault();

        const query = searchInput.value;
        fetchMovies(query);
    });
    
    setExistingMovie();

    function setExistingMovie() {
        if (window.postMovieId) {
            selectedMovieId = window.postMovieId; // 既存の映画のIDを設定
            selectedMovie.value = window.postMovieId;

            const selectedMovieTitle = document.createElement('p');
            selectedMovieTitle.textContent = window.postMovieTitle; // ここで適切な映画のタイトルを設定する必要があります。
            selectedMovieContainer.appendChild(selectedMovieTitle);
            selectedMovieTitle.classList.add('tag', 'is-danger');

            const removeButton = document.createElement('button');
            removeButton.textContent = '取り消し';
            removeButton.className ="remove-button";
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
        }
    }

    function fetchMovies(query, page = 1) {
        const url = '/moviechat/movies/search?query=' + query + '&page=' + page;
    
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const movies = data.results;
                const totalPages = data.total_pages;
                searchResults.innerHTML = '';
                selectButtons = [];  // Clear the select buttons
    
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
                        selectedMovieTitle.classList.add('tag', 'is-danger');

                        const removeButton = document.createElement('button');
                        removeButton.textContent = '取り消し';
                        removeButton.className ="remove-button"
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
                    // これが空の要素で、左側のボタンがない場合にスペースを埋める役割を果たします。
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
                    // これが空の要素で、右側のボタンがない場合にスペースを埋める役割を果たします。
                    paginationDiv.appendChild(document.createElement('div'));
                }

                searchResults.appendChild(paginationDiv);
            });
    }

    function updateSelectButtons() {
        selectButtons.forEach(({ id, button }) => {
            button.textContent = selectedMovieId === id ? '選択済み' : '選択';
            button.disabled = selectedMovieId === id;
        });
    }
});
