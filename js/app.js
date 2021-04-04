angular.module('facebookApp1', ['ngMaterial'])

.controller('AppCtrl', function($scope, $mdDialog, $http) {
  $scope.status = '  ';
  $scope.customFullscreen = false;

  $scope.showModal = function(ev) {
    $mdDialog.show({
      controller: DialogController,
      templateUrl: 'templates/modal.html',
      parent: angular.element(document.body),
      targetEvent: ev,
      clickOutsideToClose:true,
      fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
    });
  };

  function DialogController($scope, $mdDialog) {
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

  }

  $scope.getAccounts = function() {
    $http({
      url: '/?function=get&data=all',
      method: 'GET',
    }).then(function(res){
      console.log(res);
      // $scope.data = res.data.data;
    });
  };

  console.log(1);

  $scope.getAccounts();

});
