<?php echo $header; ?>
    <div class="row mt-3">
        <div class="col">
            <h3 class="float-start"><?php echo __('reports.visitors_online'); ?></h3>
            <button id="reset-visitors-button"
                    class="btn btn-danger btn-sm float-end"><?php echo __('reports.reset_visitors'); ?></button>
        </div>
    </div>
    <script src="<?php echo asset('app/views/assets/js/chartist.min.js'); ?>"></script>
    <script src="<?php echo asset('app/views/assets/js/chartist-plugin-legend.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo asset('app/views/assets/css/chartist.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('app/views/assets/css/chartist-plugin-legend.min.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-6 col-xl-3 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-1 mt-0 fw-normal">Today</h5>
                        <div class="progress-w-percent">
                            <span class="progress-value fw-bold visits-today"></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-1 mt-0 fw-normal">Week</h5>
                        <div class="progress-w-percent">
                            <span class="progress-value fw-bold visits-week"></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-1 mt-0 fw-normal">Month</h5>
                        <div class="progress-w-percent">
                            <span class="progress-value fw-bold visits-month"></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-1 mt-0 fw-normal">Year</h5>
                        <div class="progress-w-percent">
                            <span class="progress-value fw-bold visits-year"></span>
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Fetching visit data and updating progress bars
            $.ajax({
                url: '<?php echo Uri::to('admin/extend/reports/visitors/get_visits'); ?>',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Update the HTML elements with the counts
                    $('.visits-today').text(response.visitsToday);
                    $('.visits-week').text(response.visitsWeek);
                    $('.visits-month').text(response.visitsMonth);
                    $('.visits-year').text(response.visitsYear);

                    // Calculate widths for each progress bar
                    var widthToday = (response.visitsToday);
                    var widthWeek = (response.visitsWeek);
                    var widthMonth = (response.visitsMonth);
                    var widthYear = (response.visitsYear);

                    // Ensure no progress bar is 100% unless it's supposed to be fully filled
                    widthToday = widthToday > 0 ? widthToday : 5;
                    widthWeek = widthWeek > 0 ? widthWeek : 5;
                    widthMonth = widthMonth > 0 ? widthMonth : 5;
                    widthYear = widthYear > 0 ? widthYear : 5;

                    // Update progress bar widths and values
                    $('.visits-today').siblings('.progress').find('.progress-bar')
                        .css('width', widthToday + '%')
                        .attr('aria-valuenow', widthToday);

                    $('.visits-week').siblings('.progress').find('.progress-bar')
                        .css('width', widthWeek + '%')
                        .attr('aria-valuenow', widthWeek);

                    $('.visits-month').siblings('.progress').find('.progress-bar')
                        .css('width', widthMonth + '%')
                        .attr('aria-valuenow', widthMonth);

                    $('.visits-year').siblings('.progress').find('.progress-bar')
                        .css('width', widthYear + '%')
                        .attr('aria-valuenow', widthYear);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching visit data: ", error);
                }
            });
        });
    </script>
    <div class="row mb-3">
        <div class="col">
            <div class="my-4 pt-4 border-top">
                <div class="ct-chart"></div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Fetching chart data
            $.ajax({
                url: '<?php echo Uri::to('admin/extend/reports/visitors/chart'); ?>',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if ($('.ct-chart').length > 0) {
                        // Ensure series is an array of arrays
                        var seriesData = response.series.map(function (dataSet) {
                            return Array.isArray(dataSet) ? dataSet : [dataSet];
                        });

                        // Data for the chart
                        var data = {
                            labels: response.labels,
                            series: seriesData
                        };

                        // Chart options
                        var options = {
                            fullWidth: true,
                            chartPadding: {
                                right: 40
                            },
                            height: 250,
                            axisY: {
                                onlyInteger: true,
                            },
                            plugins: [
                                Chartist.plugins.legend({
                                    legendNames: ['Total Visitors', 'Unique Visitors'],
                                })
                            ]
                        };

                        // Initialize the chart
                        new Chartist.Line('.ct-chart', data, options);
                    } else {
                        console.error("Chart container '.ct-chart' not found.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching visitors chart data: ", error);
                }
            });

        });
    </script>

    <div class="row mt-3 mb-3">
        <div class="col">
            <?php if ($visitors_online->count): ?>
                <div class="table-responsive mt-3">
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th class="text-start" scope="col"><?php echo __('reports.ip'); ?></th>
                            <th class="text-start" scope="col"><?php echo __('reports.referer'); ?></th>
                            <th class="text-end" scope="col"><?php echo __('reports.date_added'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($visitors_online->results as $visitor): ?>
                            <tr>
                                <td class="text-start">
                                    <a class="text-reset"
                                       href="http://whatismyipaddress.com/ip/<?php echo $visitor->visitor_ip; ?>"
                                       target="_blank"><?php echo $visitor->visitor_ip; ?>
                                    </a>
                                </td>
                                <td class="text-start"><?php echo $visitor->referer; ?></td>
                                <td class="text-end">
                                    <?php echo $visitor->created_at; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <nav class="mt-3" aria-label="Page navigation">
                        <ul class="pagination">
                            <?php echo $visitors_online->links(); ?>
                        </ul>
                    </nav>
                </div>
            <?php else: ?>
                <p class="mt-3 text-center">
                    <?php echo __('global.text_no_results'); ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#reset-visitors-button').click(function () {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Uri::to('admin/extend/reports/visitors/reset'); ?>',
                    success: function (response) {
                        if (response.notification) {
                            showSuccessAlert(response.notification);
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            showErrorAlert('An error occurred while resetting visitors.');
                        }
                    }
                });

                function showSuccessAlert(message) {
                    $('.notifications').html('<div class="alert alert-success">' + message + '</div>');
                }

                function showErrorAlert(message) {
                    $('.notifications').html('<div class="alert alert-danger">' + message + '</div>');
                }
            });
        });
    </script>
<?php echo $footer; ?>