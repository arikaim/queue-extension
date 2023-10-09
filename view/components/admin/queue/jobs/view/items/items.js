'use strict';

arikaim.component.onLoaded(function() {
    safeCall('jobsView',function(obj) {
        obj.initRows();
    },true);   
});