'use strict';

arikaim.component.onLoaded(function() {
    $('#drivers_dropdown').dropdown({
        onChange: function(value) {                    
            options.save('queue.worker.name',value);
        }
    });
});