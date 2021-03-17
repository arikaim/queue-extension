/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function JobsView() {
    var self = this;

    this.init = function() {     
        this.loadMessages('queue::admin');
        paginator.init('jobs_rows',"queue::admin.jobs.view.items",'jobs'); 
        
        arikaim.ui.button('.delete-jobs',function(element) {
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });

            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                paginator.reload();
            });
        });
    };

    this.loadJobItem = function(uuid) {
        return arikaim.page.loadContent({
            id: 'row_' + uuid,           
            component: 'queue::admin.jobs.view.item',
            params: { uuid: uuid }            
        },function(result) {
            self.initRows();
        });  
    };

    this.initDetails = function() {

        arikaim.ui.button('.suspend-job',function(element) {
            var uuid = $(element).attr('uuid');

            jobs.disable(uuid,function(result) {
                self.loadJobItem(result.uuid);
                $('#job_details').html('');
            });
        });

        arikaim.ui.button('.enable-job',function(element) {
            var uuid = $(element).attr('uuid');

            jobs.enable(uuid,function(result) {
                self.loadJobItem(result.uuid);
                $('#job_details').html('');
            });
        });

        arikaim.ui.button('.delete-job',function(element) {
            var uuid = $(element).attr('uuid');

            jobs.delete(uuid,function(result) {
                self.loadJobItem(result.uuid);
                $('#job_details').html('');
            });
        });
    };

    this.initRows = function() {
        arikaim.ui.button('.job-details',function(element) {
            var uuid = $(element).attr('uuid');
            
            return arikaim.page.loadContent({
                id: 'job_details',           
                component: 'queue::admin.jobs.details',
                params: { uuid: uuid }            
            });  
        });
    };
}

var jobsView = createObject(JobsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    jobsView.init();
    jobsView.initRows();
});