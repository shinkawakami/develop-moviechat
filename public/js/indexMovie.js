document.addEventListener('DOMContentLoaded', () => {
    const elements = {
        searchButton: document.getElementById('search-btn'),
        searchInput: document.getElementById('movie-search'),
        showSearchFormButton: document.getElementById('show-search-form'),
        searchFormContainer: document.getElementById('search-form-container'),
        searchResults: document.getElementById('search-results'),
        popularMoviesContainer: document.getElementById('popular-movies'),
        startYearSelect: document.getElementById('start-year'),
        endYearSelect: document.getElementById('end-year'),
        prevPageButton: document.getElementById('prev-page'),
        nextPageButton: document.getElementById('next-page'),
        keywordSearchButton: document.getElementById('keyword-search-btn'),
        filterSearchButton: document.getElementById('filter-search-btn')
    };
    
    let selectedGenres = [];
    let currentPage = 1;
    let lastSearchParams = {
        query: '',
        genres: '',
        startYear: '',
        endYear: ''
    };

    // 検索フォームの表示
    elements.showSearchFormButton.addEventListener('click', function() {
        elements.searchFormContainer.style.display = 'block';
        this.style.display = 'none';
    });

    // ジャンルボタンのイベントリスナー
    document.querySelectorAll('.genre-button').forEach(button => {
        button.addEventListener('click', function() {
            const genreId = this.getAttribute('data-genre-id');
            if (selectedGenres.includes(genreId)) {
                selectedGenres = selectedGenres.filter(id => id !== genreId);
                this.classList.remove('selected');
            } else {
                selectedGenres.push(genreId);
                this.classList.add('selected');
            }
        });
    });

    // キーワード検索
    elements.keywordSearchButton.addEventListener('click', (event) => {
        event.preventDefault();
        const query = elements.searchInput.value;
        currentPage = 1;
        fetchMovies(query);
    });

    // ジャンル・年代検索
    elements.filterSearchButton.addEventListener('click', (event) => {
        event.preventDefault();
        const startYear = elements.startYearSelect.value;
        const endYear = elements.endYearSelect.value;
        currentPage = 1;
        fetchMovies('', selectedGenres.join(','), startYear, endYear);
    });

    // ページネーションのボタン更新
    function updatePaginationButtons(totalPages) {
        elements.prevPageButton.style.display = (currentPage > 1) ? 'inline-block' : 'none';
        elements.nextPageButton.style.display = (currentPage < totalPages) ? 'inline-block' : 'none';
    }

    // 前のページへ
    elements.prevPageButton.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            fetchMovies();
        }
    });

    // 次のページへ
    elements.nextPageButton.addEventListener('click', function() {
        currentPage++;
        fetchMovies();
    });

    // 映画の取得
    function fetchMovies(query = lastSearchParams.query, genres = lastSearchParams.genres, startYear = lastSearchParams.startYear, endYear = lastSearchParams.endYear) {
        lastSearchParams = { query, genres, startYear, endYear };
        let url = `/moviechat/movies/search?page=${currentPage}`;
        
        if (query) url += `&query=${query}`;
        if (genres) url += `&genres=${genres}`;
        if (startYear) url += `&startYear=${startYear}`;
        if (endYear) url += `&endYear=${endYear}`;

        fetch(url)
            .then(response => response.json())
            .then(data => handleFetchResponse(data))
            .catch(() => {
                elements.searchResults.innerHTML = '<p>エラーが発生しました。もう一度試してください。</p>';
            });
    }

    // 映画の表示
    function displayMovies(movies) {
        let html = '';
        for (const movie of movies) {
            html += createMovieHTML(movie);
        }
        elements.searchResults.innerHTML = html;
    }

    // 映画HTMLの生成
    function createMovieHTML(movie) {
        return `
            <div class="movie-container">
                <a href="/moviechat/movies/${movie.id}">${movie.title}</a>
                <img src="https://image.tmdb.org/t/p/w500${movie.poster_path}" alt="${movie.title}">
                <p>${movie.overview}</p>
            </div>
        `;
    }

    // 映画取得後の応答処理
    function handleFetchResponse(data) {
        elements.popularMoviesContainer.style.display = 'none';
        if (data.results && data.results.length) {
            displayMovies(data.results);
            updatePaginationButtons(data.total_pages);
            document.getElementById("pagination-container").style.display = (data.total_pages > 1) ? 'block' : 'none';
        } else {
            elements.searchResults.innerHTML = '<p>該当する映画はありませんでした。</p>';
            document.getElementById("pagination-container").style.display = 'none';
        }
    }
});
