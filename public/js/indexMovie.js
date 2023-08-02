document.addEventListener('DOMContentLoaded', () => {
    const searchButton = document.getElementById('search-btn');
    const searchInput = document.getElementById('movie-search');
    const searchResults = document.getElementById('search-results');
    const popularMoviesContainer = document.getElementById('popular-movies');
    let currentPage = 1;
    
    searchInput.setAttribute('required', '');
    searchInput.setAttribute('maxlength', '50');

    searchButton.addEventListener('click', (event) => {

        const query = searchInput.value;
        
        if (query.trim() === '') {  // ユーザーが空白または空文字列を入力した場合
            alert('検索キーワードを入力してください。');  // ユーザーに警告メッセージを表示
            return;  // 何もせずに関数を終了
        }
        
        fetchMovies(query);
    });

    function fetchMovies(query, page = 1) {
        const url = '/moviechat/movies/search?query=' + query + '&page=' + page;
    
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const movies = data.results;
                const totalPages = data.total_pages;
                searchResults.innerHTML = '';  // Clear the search results

                popularMoviesContainer.style.display = 'none';  // Hide the popular movies container
    
                movies.forEach(movie => {
                    const movieContainer = document.createElement('div');
                    movieContainer.className = "movie-container";
            
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
                    // これが空の要素で、左側のボタン��ない場合にスペースを埋める役割を果たします。
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
});
