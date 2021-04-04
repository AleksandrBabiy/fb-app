var app = angular.module('facebookApp1', ['ngMaterial']);

app.config(function ($httpProvider, $httpParamSerializerJQLikeProvider){
  $httpProvider.defaults.transformRequest.unshift($httpParamSerializerJQLikeProvider.$get());
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8';
});

app.controller('AppCtrl', function($rootScope, $http, $mdDialog, $mdToast, $mdTheming) {
  $rootScope.status = '  ';
  $rootScope.customFullscreen = false;
  $rootScope.accounts = [];

  // CRUD

  $rootScope.getAccounts = function() {
    $http({
      url: '/?function=get&data=all',
      method: 'GET'
    }).then(function(res) {
      $rootScope.accounts = res.data;
    }).catch(function(error) {
      console.error(error);
    });
  };

  $rootScope.getAccount = function(id) {
    return new Promise(function (resolve, reject) {
      $http({
        url: `/?function=get&data=${id}`,
        method: 'GET'
      }).then(function (res) {
        resolve(res.data)
      }).catch(function (error) {
        reject(console.error(error));
      });
    });
  };

  $rootScope.createAccount = function(data) {
    $http({
      url: '/?function=insert',
      data: data,
      method: 'POST'
    }).then(function(res) {
      $rootScope.showSimpleToast('Account created successfully!');
      $rootScope.getAccounts();
    }).catch(function(error) {
      console.error(error);
    });
  };

  $rootScope.editAccount = function(id, data) {
    $http({
      url: `/?function=update&data=${id}`,
      data: data,
      method: 'POST'
    }).then(function() {
      $rootScope.showSimpleToast('Account edited successfully!');
      $rootScope.getAccounts();
    }).catch(function(error) {
      console.error(error);
    });
  };

  $rootScope.deleteAccount = function(event, id) {
    var confirm = $mdDialog.confirm()
        .title('Would you like to delete this account?')
        .textContent('')
        .ariaLabel('Delete account')
        .targetEvent(event)
        .ok('Delete')
        .cancel('Cancel');

    $mdDialog.show(confirm).then(function () {
      $rootScope.showSimpleToast('Account deleted successfully!');
      $http({
        url: `/?function=delete&data=${id}`,
        method: 'POST'
      }).then(function(res) {
        $rootScope.getAccounts();
      }).catch(function(error) {
        console.error(error);
      });
    }, function () {});
  };

  $rootScope.showSimpleToast = function(message, isSuccess = true) {
    var pinTo = "top right";
    var classes = isSuccess ? 'success' : 'error';

    $mdToast.show(
        $mdToast.simple()
            .textContent(message)
            .position(pinTo)
            .toastClass(classes)
            .hideDelay(3000))
        .then(function() {})
        .catch(function(error) {
          console.error(error);
        });
  };

  $rootScope.showUpdateModal = function($event, id) {
    $rootScope.$broadcast("showUpdateModal", { $event, id });
  }

  $rootScope.getAccounts();
});
