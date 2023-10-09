/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function JobsRegistryView() {
    var self = this;

    this.init = function() {     
        this.loadMessages('queue::admin');
        paginator.init('jobs_rows',"queue::admin.jobs.view.items",'jobs.registry'); 

    };

    this.loaItems = function() {
        return arikaim.page.loadContent({
            id: 'jobs_rows',           
            component: 'queue::admin.queue.jobs.view.items'                  
        },function(result) {
            self.initRows();
        });         
    };

    this.loadJobItem = function(uuid) {
        return arikaim.page.loadContent({
            id: 'row_' + uuid,           
            component: 'queue::admin.queue.jobs.view.item',
            params: { uuid: uuid }            
        },function(result) {
            self.initRows();
        });  
    };

    this.initDetails = function() {

        arikaim.ui.button('.run-job',function(element) {
            var uuid = $(element).attr('uuid');

            return jobs.run(uuid,function(result) {
                self.loadDetails(result.uuid);                
            });
        });

    };

    this.initRows = function() {
    
        arikaim.ui.button('.job-details',function(element) {
            var uuid = $(element).attr('uuid');            
            self.loadDetails(uuid);
        });
    };

    this.loadDetails = function(uuid) {
        return arikaim.page.loadContent({
            id: 'job_details',           
            component: 'queue::admin.queue.jobs.details',
            params: { uuid: uuid }            
        });  
    }
}

var jobsRegistryView = createObject(JobsRegistryView,ControlPanelView);

arikaim.component.onLoaded(function() {
    jobsRegistryView.init();
    jobsRegistryView.initRows();
});