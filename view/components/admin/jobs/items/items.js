'use strict';

$(document).ready(function() {
    safeCall('jobsView',function(obj) {
        obj.initRows();
    },true);   
});