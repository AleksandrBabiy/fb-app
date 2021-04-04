app.controller('CreateEditAccountController', function ($scope, $rootScope, $mdDialog) {
	$scope.$on("showUpdateModal", function(event, data) {
		$scope.showModal(data.$event, data.id);
	});

	$scope.showModal = function (event, accountId = '') {
		$mdDialog.show({
			controller: DialogController,
			templateUrl: '../../templates/modal.html?v=' + Math.random().toString(36).slice(2),
			parent: angular.element(document.body),
			targetEvent: event,
			clickOutsideToClose: true,
			locals: { id: accountId },
			fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
		});
	};

	function DialogController($scope, $rootScope, $mdDialog, id) {
		$scope.id = id;
		$scope.account = {
			username: '',
			email: '',
			title: '',
			phone: ''
		};

		if ($scope.id) {
			$rootScope.getAccount(id).then(function (data) {
				$scope.account = Object.assign($scope.account, data[0]);
				$scope.$apply();
			});
		}

		$scope.createAccount = function() {
			const data = {data: $scope.account};

			if ($scope.accountForm.$valid) {
				$rootScope.createAccount(data);
				$mdDialog.hide();
			}
		};

		$scope.updateAccount = function() {
			const data = {data: $scope.account};

			if ($scope.accountForm.$valid) {
				$rootScope.editAccount($scope.id, data);
				$mdDialog.hide();
			}
		};

		$scope.cancel = function() {
			$mdDialog.cancel();
		};
	}
});
