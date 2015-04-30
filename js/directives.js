app.directive(
				'loginDialog',
				function() {
					return {
						restrict : 'A',
						template : '<div ng-if="visible" ng-include="\'snippets/login-form.html\'">',
						link : function(scope) {
							var showDialog = function() {
								scope.visible = true;
							};

							scope.visible = scope.getCurrentUser() == null;
						}
					};
				})

app.directive('logoutDialog', function() {
	return {
		restrict : 'A',
		template : '<div ng-include="\'snippets/logout-form.html\'">',
	};
})

app.directive('bootstrapSwitch', [ function() {
	return {
		restrict : 'A',
		require : '?ngModel',
		link : function(scope, element, attrs, ngModel) {
			element.bootstrapSwitch();
			element.on('switchChange.bootstrapSwitch', function(event, state) {
				if (ngModel) {
					scope.$apply(function() {
						ngModel.$setViewValue(state);
					});
				}
			});

			scope.$watch(attrs.ngModel, function(newValue, oldValue) {
				if (newValue !== undefined) {
					if (newValue) {
						element.bootstrapSwitch('state', true, true);
						scope.checkin();
					} else if (!newValue) {
						element.bootstrapSwitch('state', false, true);
						scope.checkout();
					}
				}
			});
		}
	};
} ]);

app.directive('selectWatcher', function ($timeout) {
    return {
        link: function (scope, element, attr) {
            var last = attr.last;
            if (last === "true") {
                $timeout(function () {
//                    $(element).parent().selectpicker('val', 'any');
                    $(element).parent().selectpicker('refresh');
                });
            }
        }
    };
});
