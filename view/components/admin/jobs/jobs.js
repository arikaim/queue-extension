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
       $('#worker_dropdown').dropdown({
            onChange: function(value) {
                if (value == 1) {
                    $('#worker_status_icon').attr('class','icon olive check');
                } else {
                    $('#worker_status_icon').attr('class','icon delete orange');
                }
            }
       });
    };

    this.showJobsList = function() {
        return arikaim.page.loadContent({
            id: 'jobs_list',           
            component: 'queue::admin.jobs.items'            
        },function(result) {
            self.initRows();
        });  
    };

    this.initRows = function() {
        var component = arikaim.component.get('queue::admin');
        var removeMessage = component.getProperty('messages.page.content');

        arikaim.ui.button('.delete-jobs',function(element) {
            var uuid = $(element).attr('uuid');
            var title = $(element).attr('data-title');

            var message = arikaim.ui.template.render(removeMessage,{ title: title });
            modal.confirmDelete({ 
                title: component.getProperty('messages.page.title'),
                description: message
            },function() {
                queueControlPanel.deleteJob(uuid,function(result) {
                    $('#' + uuid).remove();                                 
                });
            });
        });
    };
}

var jobsView = new JobsView();

arikaim.page.onReady(function() {
    jobsView.init();
    jobsView.initRows();
});