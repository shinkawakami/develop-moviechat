document.addEventListener('DOMContentLoaded', () => {
    const searchButton = document.getElementById('search-btn');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');
    const selectedMovie = document.getElementById('movie');
    const selectedMovieContainer = document.getElementById('selected-movie');
    const viewingToggleBtn = document.getElementById('viewing-toggle-btn');
    const viewingSection = document.getElementById('viewing-section');
    const viewingRequestBtn = document.getElementById('viewing-request-btn');
    const viewingCancelButton = document.getElementById('viewing-cancel-btn');
    const startTime = document.querySelector('input[name="start_time"]');
    let selectedMovieId = null;  
    let selectButtons = []; 
    let currentPage = 1;

    // トリガーボタンのクリック
    viewingToggleBtn.addEventListener('click', () => {
        event.preventDefault();
        viewingSection.style.display = 'block';
    });

    // キャンセルボタンのクリック
    viewingCancelButton.addEventListener('click', (event) => {
        event.preventDefault();
        searchResults.innerHTML = ''; 
        viewingSection.style.display = 'none';  
    });
    
    // 同時視聴申請ボタンのクリック
    viewingRequestBtn.addEventListener('click', (event) => {
        const recipients = document.querySelectorAll('input[name="recipients[]"]:checked');
        if (!recipients.length || !startTime.value || !selectedMovieId) {
            event.preventDefault();
            alert('申請を送るユーザー、視聴開始時間、映画を選択してください。');
            return;
        } else {
            viewingSection.style.display = 'none';
        }
    });

    // 検索ボタンのクリック
    searchButton.addEventListener('click', (event) => {
        event.preventDefault();
        const query = searchInput.value;
        fetchMovies(query);
    });

    // TMDBから映画の情報を取得
    function fetchMovies(query, page = 1) {
        const url = '/moviechat/movies/search?query=' + query + '&page=' + page;
        
        // キャンセルボタンの作成
        const cancelButton = document.createElement('button');
        cancelButton.textContent = '閉じる';
        cancelButton.classList.add('cancel-button', 'tag', 'is-danger');
        cancelButton.addEventListener('click', (event) => {
            event.preventDefault();
            searchResults.innerHTML = ''; 
        });
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const movies = data.results;
                const totalPages = data.total_pages;
                searchResults.innerHTML = '';
                searchResults.appendChild(cancelButton);
                selectButtons = []; 
    
                // 検索結果の作成
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
                            updateSelectButtons();
                        });
                        
                        selectedMovieContainer.appendChild(removeButton);
                        updateSelectButtons();
                        searchResults.innerHTML = ''; 
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

    // 映画選択ボタンの更新
    function updateSelectButtons() {
        selectButtons.forEach(({ id, button }) => {
            button.textContent = selectedMovieId === id ? '選択済み' : '選択';
            button.disabled = selectedMovieId === id;
        });
    }
});
