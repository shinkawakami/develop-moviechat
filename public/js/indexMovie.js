document.addEventListener('DOMContentLoaded', () => {
    const movieSearchButton = document.getElementById('movie-search-btn');
    const movieSearchInput = document.getElementById('movie-search');
    const movieSearchResults = document.getElementById('movie-search-results');
    const popularMoviesContainer = document.getElementById('popular-movies');
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
                movieSearchResults.innerHTML = '';  // Clear the search results

                popularMoviesContainer.style.display = 'none';  // Hide the popular movies container
    
                movies.forEach(movie => {
                    const movieContainer = document.createElement('div');
    
                    const movieTitle = document.createElement('a');
                    movieTitle.href = '/moviechat/movies/' + movie.id;  // Set the link to the movie detail page
                    movieTitle.textContent = movie.title;
                    movieContainer.appendChild(movieTitle);
    
                    const movieImage = document.createElement('img');
                    movieImage.src = 'https://image.tmdb.org/t/p/w500' + movie.poster_path;
                    movieContainer.appendChild(movieImage);
    
                    const movieOverview = document.createElement('p');
                    movieOverview.textContent = movie.overview;
                    movieContainer.appendChild(movieOverview);
    
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
});
