$(document).ready(function() {
    $('#movies').select2({
        ajax: {
            url: '/moviechat/movies/search',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    query: params.term,
                    page: params.page || 1
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
});
