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
		template : '<div ng-include="\'snippets/logout-form.html\'">'
	};
})

app.directive('checkinDialog', function(){
	return {
		restrict : 'A',
		template : '<div ng-include="\'snippets/checkin.html\'">'
	}
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
						if (newValue && !oldValue) {
							scope.checkin();
						}
					} else if (!newValue) {
						element.bootstrapSwitch('state', false, true);
						if (!newValue && oldValue) {
							scope.checkout();
						}
					}
				}
			});
		}
	};
} ]);

app.directive('dagwidget', function(){
	return {
		restrict : 'A',
		template : '<div ng-include="\'snippets/dag.html\'">'
	}
})

app.directive('focusMe', function($timeout) {
  return {
    link: function(scope, element, attrs) {
      scope.$watch(attrs.focusMe, function(value) {
        if(value === true) { 
          //$timeout(function() {
            element[0].focus();
            scope[attrs.focusMe] = false;
          //});
        }
      });
    }
  };
});