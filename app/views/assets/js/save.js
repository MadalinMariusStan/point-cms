(function ($) {
    const zone = $(document),
        form = $('form').first(),
        submitButton = form.find('.save'),
        originalSubmitText = submitButton.html(),
        notificationWrapper = $('.notifications'),
        originalTitle = document.title;

    // Function to create and display notifications
    function createNotification(message, type = 'success') {
        const alertType = type === 'error' ? 'alert-danger' : 'alert-success';
        const notification = $(`
            <div class="alert ${alertType} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        notificationWrapper.append(notification);

        setTimeout(() => {
            notification.fadeOut(6000 , function () {
                $(this).remove();
            });
        }, 3000);

        scrollToNotifications();
    }

    // Function to scroll to the notifications element
    function scrollToNotifications() {
        $('html, body').animate({
            scrollTop: notificationWrapper.offset().top
        }, 'slow');
    }

    // Press `CTRL + S` to `Save`
    zone.on('keydown', function (event) {
        if (event.ctrlKey && event.keyCode === 83 && !event.altKey) {
            event.preventDefault(); // Prevent the browser's save dialog
            form.trigger('submit');
            return false;
        }
    });

    // AJAX form submit
    form.on('submit', function (event) {
        event.preventDefault();

        const data = form.serializeArray().reduce((obj, item) => {
            obj[item.name] = item.value;
            return obj;
        }, {});

        submitButton.prop('disabled', true).css('cursor', 'wait').html('Saving...');
        document.title = 'Saving...';

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: data,
            success: function (response) {
                document.title = response.notification ? response.notification : originalTitle;

                if (response.notification) {
                    createNotification(response.notification);
                }

                if (response.errors) {
                    Object.keys(response.errors).forEach(key => {
                        createNotification(response.errors[key], 'error');
                    });
                }

                if (response.redirect && response.redirect !== window.location.href) {
                    setTimeout(() => {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            },
            error: function () {
                createNotification('An error occurred while saving. Please try again.', 'error');
            },
            complete: function () {
                submitButton.prop('disabled', false).html(originalSubmitText).removeAttr('style');
                scrollToNotifications(); // Ensure visibility of the notification after actions
            }
        });
    });
}(jQuery));
