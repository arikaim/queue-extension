'use strict';

arikaim.component.onLoaded(function() {
    $('#drivers_dropdown').dropdown({
        onChange: function(value) {                    
            options.save('queue.worker.name',value);
        }
    });

    arikaim.events.on('driver.config',function(element,name,category) {      
        drivers.loadConfig(name,'driver_config',null,'sixteen wide');
    },'driversList',self)
});