'use strict';

arikaim.component.onLoaded(function() {
    $('.interval-dropdown').dropdown({
        onChange: function(value) {
            var uuid =$('#job_uuid').val();
           
            jobs.updateInterval(uuid,value,function(result) {
                arikaim.page.toastMessage(result.message);
            });
        }
    });
});