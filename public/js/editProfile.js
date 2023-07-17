// Select2 JS
$.getScript("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js", function() {

    $(document).ready(function() {
        $('#favorite_movies').select2({
            ajax: {
                url: '/moviechat/movies/search',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        query: params.term,
                        page: params.page
                    };
                },
                processResults: function(data) {
                    let movies = data.results.map(function(movie) {
                        return {
                            id: movie.id,
                            text: movie.title
                        };
                    });

                    return {
                        results: movies
                    };
                }
            },
            minimumInputLength: 1
        });

        // Initialize other select2 fields here
    });
});
