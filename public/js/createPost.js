document.addEventListener('DOMContentLoaded', () => {
    const searchButton = document.getElementById('search-btn');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');
    const selectedMovie = document.getElementById('movie');
    const selectedMovieContainer = document.getElementById('selected-movie');
    const postForm = document.querySelector('form[data-post-form]');
    let selectedMovieId = null; 
    let selectButtons = []; 
    let currentPage = 1;
    
    postForm.addEventListener('submit', (event) => {
        if (!selectedMovieId) {
            event.preventDefault();  
            alert('映画を選択してください。'); 
        } else {
            const ratingValue = document.getElementById('movie-rating').value;
            if (!ratingValue) {
                event.preventDefault();  
                alert('評価を選択してください。'); 
            }
        }
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
                selectButtons = [];  
    
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

                        selectedMovieContainer.innerHTML = '';  

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
                            
                            selectedMovieContainer.innerHTML = '';

                            updateSelectButtons();
                        });
                        selectedMovieContainer.appendChild(removeButton);

                        updateSelectButtons();
                        
                        createRatingSystem(selectedMovieContainer);
                    });
                    movieContainer.appendChild(selectButton);

                    selectButtons.push({ id: movie.id, button: selectButton }); 
                    
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

    function updateSelectButtons() {
        selectButtons.forEach(({ id, button }) => {
            button.textContent = selectedMovieId === id ? '選択済み' : '選択';
            button.disabled = selectedMovieId === id;
        });
    }
    
    function createRatingSystem(container) {
        const ratingContainer = document.createElement('div');
        ratingContainer.className = 'rating-container';
    
        for (let i = 1; i <= 5; i++) {
            const star = document.createElement('span');
            star.textContent = '☆'; 
            star.className = 'rating-star';
            star.dataset.value = i;
    
            star.addEventListener('click', function() {
                const selectedRating = this.dataset.value;
                document.getElementById('movie-rating').value = selectedRating;
    
                document.querySelectorAll('.rating-star').forEach(star => {
                    star.textContent = '☆';
                });
    
                for (let j = 1; j <= selectedRating; j++) {
                    document.querySelector(`.rating-star[data-value="${j}"]`).textContent = '★';
                }
            });
    
            ratingContainer.appendChild(star);
        }
    
        container.appendChild(ratingContainer);
    }
});
