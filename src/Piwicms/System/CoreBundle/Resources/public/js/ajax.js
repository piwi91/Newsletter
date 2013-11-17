Application = {
    ajax: function(options) {
        var deferred = $.Deferred(function (d) {
            var defaults = {
                    cache: false,
                    type: 'post',
                    dataType: 'json'
                },
                settings = $.extend({}, defaults, options);

            d.done(settings.success);
            d.fail(settings.error);
            d.done(settings.complete);

            var jqXHRSettings = $.extend({}, settings, {
                success: function (response, textStatus, jqXHR) {
                    /*
                     JSON Reponse
                     {
                     status: 'error' or 'success',
                     code: 200, (HTTP codes or own codes between 600 and 700 for business logic errors)
                     data: { <result> }
                     }
                     */
                    if (settings.dataType === 'json') {
                        if (response.status == 'success') {
                            // Just resolve and give data back
                            d.resolve(response.data);
                        } else if (response.status == 'error') {
                            // Implement error handling
                            d.reject(response.data);
                        }
                    } else {
                        d.resolve(response);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    d.reject(jqXHR);
                },
                complete: d.resolve
            });

            $.ajax(jqXHRSettings);
        });

        var promise = deferred.promise();
        promise.success = deferred.done;
        promise.error = deferred.fail;
        promise.complete = deferred.done;

        return promise;
    }
};