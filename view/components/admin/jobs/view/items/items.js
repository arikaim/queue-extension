'use strict';

arikaim.component.onLoaded(function() {
    safeCall('jobsRegistryView',function(obj) {
        obj.initRows();
    },true);   
});