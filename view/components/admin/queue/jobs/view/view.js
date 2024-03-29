/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function JobsView() {
    var self = this;

    this.init = function() {     
        this.loadMessages('queue::admin');
        paginator.init('jobs_rows',"queue::admin.queue.jobs.view.items",'jobs'); 
        
        arikaim.ui.button('.delete-completed',function(element) {
            modal.confirmDelete({ 
                title: self.getMessage('completed.title'),
                description: self.getMessage('completed.content')
            },function() {
                jobs.deleteCompleted(function(result) {
                    self.loaItems();
                });
            });
        });
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

        arikaim.ui.button('.run-job',function(element) {
            var uuid = $(element).attr('uuid');

            return jobs.run(uuid,function(result) {
                self.loadDetails(result.uuid);                
            });
        });

    };

    this.initRows = function() {
        arikaim.ui.button('.edit-job',function(element) {
            var uuid = $(element).attr('uuid');
            return arikaim.page.loadContent({
                id: 'job_details',           
                component: 'queue::admin.queue.jobs.edit',
                params: { uuid: uuid }            
            }); 
        });

        arikaim.ui.button('.delete-job',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');
            var message = arikaim.ui.template.render(self.getMessage('remove.content'),{ title: title });

            modal.confirmDelete({ 
                title: self.getMessage('remove.title'),
                description: message
            },function() {
                jobs.delete(uuid,function(result) {
                    arikaim.ui.table.removeRow('#row_' + uuid);     
                    arikaim.page.toastMessage(result.message);
                });               
            });           
        });

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

var jobsView = createObject(JobsView,ControlPanelView);

arikaim.component.onLoaded(function() {
    jobsView.init();
    jobsView.initRows();
});