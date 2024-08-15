<?php echo $header; ?>
    <div id="panel" class="row mt-3">
        <div class="col-sm-4 col-lg-4 mt-3">
            <div class="card bg-body-secondary border-0 rounded-3">
                <div class="card-header bg-transparent border-0 p-0 px-3 pt-3">
                    <?php echo __('panel.comments_total'); ?>
                </div>
                <div class="card-body p-0 px-3 py-2">
                    <a class="d-flex" href="<?php echo Uri::to('admin/comments'); ?>">
                        <div class="icon-square bg-primary text-body-emphasis flex-shrink-0 me-3 shadow">
                            <svg class="bi p-2" fill="white"><use href="#bi-chat-left-text"></use></svg>
                        </div>
                        <div class="flex-grow-1">
                            <div class="float-end">
                                <h1 class="text-body-emphasis"><?php echo $total_comments; ?></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-4 mt-3">
            <div class="card bg-body-secondary border-0 rounded-3">
                <div class="card-header bg-transparent border-0 p-0 px-3 pt-3">
                    <?php echo __('panel.posts_total'); ?>
                </div>
                <div class="card-body p-0 px-3 py-2">
                    <a class="d-flex" href="<?php echo Uri::to('admin/posts'); ?>">
                        <div class="icon-square bg-info text-body-emphasis flex-shrink-0 me-3 shadow">
                            <svg class="bi p-2" fill="white"><use href="#bi-pencil-square"></use></svg>
                        </div>
                        <div class="flex-grow-1">
                            <div class="float-end">
                                <h1 class="text-body-emphasis"><?php echo $total_posts; ?></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-4 mt-3">
            <div class="card bg-body-secondary border-0 rounded-3">
                <div class="card-header bg-transparent border-0 p-0 px-3 pt-3">
                    <?php echo __('panel.pages_total'); ?>
                </div>
                <div class="card-body p-0 px-3 py-2">
                    <a class="d-flex" href="<?php echo Uri::to('admin/pages'); ?>">
                        <div class="icon-square bg-warning text-body-emphasis flex-shrink-0 me-3 shadow">
                            <svg class="bi p-2" fill="white"><use href="#bi-file-earmark"></use></svg>
                        </div>
                        <div class="flex-grow-1">
                            <div class="float-end">
                                <h1 class="text-body-emphasis"><?php echo $total_pages; ?></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-4 mt-3">
            <div class="card bg-body-secondary border-0 rounded-3">
                <div class="card-header bg-transparent border-0 p-0 px-3 pt-3">
                    <?php echo __('panel.total_visitors'); ?>
                </div>
                <div class="card-body p-0 px-3 py-2">
                    <a class="d-flex" href="<?php echo Uri::to('admin/extend/reports/visitors'); ?>">
                        <div class="icon-square bg-secondary-subtle text-body-emphasis flex-shrink-0 me-3 shadow">
                            <svg class="bi p-2" fill="white"><use href="#bi-people"></use></svg>
                        </div>
                        <div class="flex-grow-1">
                            <div class="float-end">
                                <h1 class="text-body-emphasis"><?php echo $total_visitors; ?></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-4 mt-3">
            <div class="card bg-body-secondary border-0 rounded-3">
                <div class="card-header bg-transparent border-0 p-0 px-3 pt-3">
                    <?php echo __('panel.online_visitors'); ?>
                </div>
                <div class="card-body p-0 px-3 py-2">
                    <span class="d-flex cursor-pointer">
                        <div class="icon-square bg-success-subtle text-body-emphasis flex-shrink-0 me-3 shadow">
                            <svg class="bi p-2" fill="white"><use href="#bi-people-fill"></use></svg>
                        </div>
                        <div class="flex-grow-1">
                            <div class="float-end">
                                <h1 class="text-body-emphasis"><?php echo countOnlineVisitors(); ?></h1>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-lg-4 mt-3">
            <div class="card bg-body-secondary border-0 rounded-3">
                <div class="card-header bg-transparent border-0 p-0 px-3 pt-3">
                    <?php echo __('panel.subscribers_total'); ?>
                </div>
                <div class="card-body p-0 px-3 py-2">
                    <a class="d-flex" href="<?php echo Uri::to('admin/extend/subscribers'); ?>">
                        <div class="icon-square bg-primary-subtle text-body-emphasis flex-shrink-0 me-3 shadow">
                            <svg class="bi p-2" fill="white"><use href="#bi-envelope-open"></use></svg>
                        </div>
                        <div class="flex-grow-1">
                            <div class="float-end">
                                <h1 class="text-body-emphasis"><?php echo $total_subscribers; ?></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo asset('app/views/assets/js/chartist.min.js'); ?>"></script>
    <script src="<?php echo asset('app/views/assets/js/chartist-plugin-legend.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo asset('app/views/assets/css/chartist.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('app/views/assets/css/chartist-plugin-legend.min.css'); ?>">
    <div class="row mt-3 mb-3">
        <div class="col">
            <div class="my-4 pt-4 border-top">
                <div class="ct-chart "></div>
                <p class="visits-today">Visits today: </p>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Fetching chart data
            $.ajax({
                url: '<?php echo Uri::to('admin/panel/visitors_chart'); ?>', // Ensure this URL is correct and accessible
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Check if the chart container exists to avoid `TypeError`
                    if ($('.ct-chart').length > 0) {
                        // Data for the chart
                        var data = {
                            labels: response.labels,
                            series: response.series

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

            $.ajax({
                url: '<?php echo Uri::to('admin/panel/get_visits_today'); ?>',
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    // Update the HTML element with the count
                    $('.visits-today').text('Visits today: ' + response.visitsToday);
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching visits today: ", error);
                }
            });
        });
    </script>
<?php echo $footer; ?>