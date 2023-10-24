'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.addRules('#push_job_form',{});

    $('.checkbox').checkbox();
    $('.interval-dropdown').dropdown({});
    
    arikaim.ui.form.onSubmit("#push_job_form",function() {  
        return jobs.push('#push_job_form');
    },function(result) {
        console.log(result);
    });
});