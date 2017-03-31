angular.module('gamingPassion', []);

angular.module('gamingPassion').controller('PostController', ['$scope', function() {

}]);

angular.module('gamingPassion').directive('postList', function() {

    return {
        scope: {
            category: "@"
        },
        templateUrl: 'js/templates/post.html',
        controller: ['$scope', '$q', 'RetrievePostsService', 'RetrieveRatingsService', function PostController($scope, $q, RetrievePostsService, RetrieveRatingsService) {

                if($scope.category === undefined)
                    var postService = RetrievePostsService.getData();
                else
                    var postService = RetrievePostsService.getForCategory($scope.category);

                postService.then(function(data){

                    $scope.posts = data;

                    var indexes = [];

                    for(var key in data){
                        indexes.push(data[key].id)
                    }

                    getRatings(0, indexes[0], indexes, data.length);

                    function getRatings(index, actualIndex, indexes, toDo){

                        if(index === toDo)
                            return;

                        RetrieveRatingsService.getData(actualIndex).then(function(data){

                            $scope.posts[index].ratings = data;
                            ++index;
                            getRatings(index, indexes[index], indexes, toDo);
                        });
                    }
                });
            }
        ]
    };
});

angular.module('gamingPassion').factory('RetrievePostsService', function($http) {

    var months = ["January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    var getData = function() {

        return $http.get('api/posts').then(function(response) {

            var parsedResponse = response.data;

            for(var key in parsedResponse){
                var date = new Date(parsedResponse[key].createdAt * 1000);

                parsedResponse[key].formattedDate = date.getHours() + ':' + date.getMinutes() + ' on ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
            }

            return parsedResponse;
        });
    };

    var getForCategory = function(category) {

        return $http.get('api/posts/' + category).then(function(response) {

            var parsedResponse = response.data;

            for(var key in parsedResponse){
                var date = new Date(parsedResponse[key].createdAt * 1000);

                parsedResponse[key].formattedDate = date.getHours() + ':' + date.getMinutes() + ' on ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
            }

            return parsedResponse;
        });
    };

    return {
        getData: getData,
        getForCategory: getForCategory
    };
});

angular.module('gamingPassion').factory('RetrieveRatingsService', function($http) {

    var getData = function(postId) {

        return $http.get('api/ratings/' + postId).then(function (response) {

            var parsedData = response.data;

            parsedData.average = response.data.average === null ? 0 : response.data.average;
            parsedData.count = response.data.ratings === null ? 0 : response.data.ratings.length;

            return parsedData;
        });
    };

    return { getData: getData };
});