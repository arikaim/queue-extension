'use strict';

arikaim.component.onLoaded(function() {
    $('#drivers_dropdown').on('change', function() {
        var name = $(this).val();                      
        options.save('queue.worker.name',name);       
    });

    arikaim.events.on('driver.config',function(element,name,category) {      
        drivers.loadConfig(name,'driver_config',null,'sixteen wide');
    },'driversList',self)
});