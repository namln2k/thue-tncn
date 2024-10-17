(function ($) {
    'use strict';
    console.info("%cWincher Loaded.", "color:green");

    const Wincher = {
        DashboardData: function () {
            return {
                keywords: [],
                tracked_keywords_count: 0,
            }
        },
        formatDateString: function (dateString) {
            const options = {
                day: 'numeric',
                month: 'short',
                // year: 'numeric'
            };
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', options);
        },
        getWebsiteFaveicon: function (domain) {
            let favicon_url = `https://www.google.com/s2/favicons?sz=64&domain_url=${domain}`;
            return favicon_url;
        },
        dashboardChart: function (ctx, data, title = '', type = 'bar') {
            /** Account Chart */

            let plugins = {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: title
                }
            };
            let options = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: plugins
            };

            const myChart = new Chart(ctx, {
                type: type,
                data: data,
                options: options
            });

            window.addEventListener('afterprint', () => {
                myChart.resize();
            });
            window.addEventListener('onResize', () => {
                myChart.resize();
            });
            /** /Account Chart */
        },
        trafficChart: function ($data) {

            let $labels = [];
            let $value = [];

            $data.forEach(element => {
                $labels.push(Wincher.formatDateString(element.datetime));
                $value.push(element.value);
            });

            // only show last 30 days
            $labels = $labels.slice(Math.max($labels.length - 30, 0));
            $value = $value.slice(Math.max($value.length - 30, 0));

            const ctx = document.getElementById('bdt-ep-wincher-traffic-graph').getContext("2d");

            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#0BB3E5');
            gradientStroke.addColorStop(1, '#20E2AD');

            var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
            gradientFill.addColorStop(0, "rgba(11, 179, 229, 0.30)");
            gradientFill.addColorStop(1, "rgba(32, 226, 173, 0.30)");

            let usersChartData = {
                labels: $labels,
                datasets: [{
                    label: 'Traffic',
                    data: $value,
                    backgroundColor: gradientFill,
                    borderWidth: 2,
                    borderColor: gradientStroke,
                    hoverBorderColor: "red",
                    pointColor: "#3287f0",
                    fill: true,
                    dash_border: "",
                    background_fill: "yes",
                    borderDash: [],
                    pointStyle: "circle",
                    pointBorderColor: gradientStroke,
                    pointBorderWidth: 1.5,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    tension: 0.5,
                }]
            };
            Wincher.dashboardChart(ctx, usersChartData, 'Estimated Traffic', 'line');
        },
        trafficValueChart: function ($data) {

            let $labels = [];
            let $value = [];

            $data.forEach(element => {
                $labels.push(Wincher.formatDateString(element.datetime));
                $value.push(element.value);
            });

            // only show last 30 days
            $labels = $labels.slice(Math.max($labels.length - 30, 0));
            $value = $value.slice(Math.max($value.length - 30, 0));

            const ctx = document.getElementById('bdt-ep-wincher-traffic-value-graph').getContext("2d");

            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#0BB3E5');
            gradientStroke.addColorStop(1, '#20E2AD');

            var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
            gradientFill.addColorStop(0, "rgba(11, 179, 229, 0.30)");
            gradientFill.addColorStop(1, "rgba(32, 226, 173, 0.30)");

            let usersChartData = {
                labels: $labels,
                datasets: [{
                    label: 'Traffic Value',
                    data: $value,
                    backgroundColor: gradientFill,
                    borderWidth: 2,
                    borderColor: gradientStroke,
                    hoverBorderColor: "red",
                    pointColor: "#3287f0",
                    fill: true,
                    dash_border: "",
                    background_fill: "yes",
                    borderDash: [],
                    pointStyle: "circle",
                    pointBorderColor: gradientStroke,
                    pointBorderWidth: 1.5,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    tension: 0.5,
                }]
            };
            Wincher.dashboardChart(ctx, usersChartData, 'Traffic Value', 'line');
        },
        trafficAvgPositionChart: function ($data) {

            let $labels = [];
            let $value = [];

            $data.forEach(element => {
                $labels.push(Wincher.formatDateString(element.datetime));
                $value.push(element.value);
            });

            // only show last 30 days
            $labels = $labels.slice(Math.max($labels.length - 30, 0));
            $value = $value.slice(Math.max($value.length - 30, 0));

            const ctx = document.getElementById('bdt-ep-wincher-traffic-average-position-graph').getContext("2d");

            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#0BB3E5');
            gradientStroke.addColorStop(1, '#20E2AD');

            var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
            gradientFill.addColorStop(0, "rgba(11, 179, 229, 0.30)");
            gradientFill.addColorStop(1, "rgba(32, 226, 173, 0.30)");

            let usersChartData = {
                labels: $labels,
                datasets: [{
                    label: 'Position Value',
                    data: $value,
                    backgroundColor: gradientFill,
                    borderWidth: 2,
                    borderColor: gradientStroke,
                    hoverBorderColor: "red",
                    pointColor: "#3287f0",
                    fill: true,
                    dash_border: "",
                    background_fill: "yes",
                    borderDash: [],
                    pointStyle: "circle",
                    pointBorderColor: gradientStroke,
                    pointBorderWidth: 1.5,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    tension: 0.5,
                }]
            };
            Wincher.dashboardChart(ctx, usersChartData, 'Average Position', 'line');
        },
        shareVoiceChart: function ($data) {

            let $labels = [];
            let $value = [];

            $data.forEach(element => {
                $labels.push(Wincher.formatDateString(element.datetime));
                $value.push(element.value);
            });

            // only show last 30 days
            $labels = $labels.slice(Math.max($labels.length - 30, 0));
            $value = $value.slice(Math.max($value.length - 30, 0));

            const ctx = document.getElementById('bdt-ep-wincher-share-voice-graph').getContext("2d");

            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#0BB3E5');
            gradientStroke.addColorStop(1, '#20E2AD');

            var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
            gradientFill.addColorStop(0, "rgba(11, 179, 229, 0.30)");
            gradientFill.addColorStop(1, "rgba(32, 226, 173, 0.30)");

            let usersChartData = {
                labels: $labels,
                datasets: [{
                    label: 'Value',
                    data: $value,
                    backgroundColor: gradientFill,
                    borderWidth: 2,
                    borderColor: gradientStroke,
                    hoverBorderColor: "red",
                    pointColor: "#3287f0",
                    fill: true,
                    dash_border: "",
                    background_fill: "yes",
                    borderDash: [],
                    pointStyle: "circle",
                    pointBorderColor: gradientStroke,
                    pointBorderWidth: 1.5,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    tension: 0.5,
                }]
            };
            Wincher.dashboardChart(ctx, usersChartData, 'Share Of Voice', 'line');
        },
        keywordCountHistoryChart: function ($data) {

            let $labels = [];
            let $value = [];


            $data.forEach(element => {
                $labels.push(Wincher.formatDateString(element.datetime));
                $value.push(element.value || 0);
            });

            // only show last 30 days
            $labels = $labels.slice(Math.max($labels.length - 30, 0));
            $value = $value.slice(Math.max($value.length - 30, 0));

            const ctx = document.getElementById('bdt-ep-wincher-keyword-count-history').getContext("2d");

            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#0BB3E5');
            gradientStroke.addColorStop(1, '#20E2AD');

            var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
            gradientFill.addColorStop(0, "rgba(11, 179, 229, 0.30)");
            gradientFill.addColorStop(1, "rgba(32, 226, 173, 0.30)");

            let usersChartData = {
                labels: $labels,
                datasets: [{
                    label: 'Keywords',
                    data: $value,
                    backgroundColor: gradientFill,
                    borderWidth: 2,
                    borderColor: gradientStroke,
                    // hoverBorderColor: "red",
                    pointColor: "#3287f0",
                    fill: true,
                    dash_border: "",
                    background_fill: "yes",
                    borderDash: [],
                    pointStyle: "circle",
                    pointBorderColor: gradientStroke,
                    pointBorderWidth: 1.5,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    tension: 0.5,
                }],

            };

            const myChart = new Chart(ctx, {
                type: 'bar',
                data: usersChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },

                    },
                    scales: {
                        xAxis: {
                            display: false
                        },
                        yAxis: {
                            display: false
                        }
                    }

                }
            });

            window.addEventListener('afterprint', () => {
                myChart.resize();
            });
            window.addEventListener('onResize', () => {
                myChart.resize();
            });
        },
        rankingPagesHistoryChart: function ($data) {

            let $labels = [];
            let $value = [];

            $data.forEach(element => {
                $labels.push(Wincher.formatDateString(element.datetime));
                $value.push(element.value || 0);
            });

            // only show last 30 days
            $labels = $labels.slice(Math.max($labels.length - 30, 0));
            $value = $value.slice(Math.max($value.length - 30, 0));

            const ctx = document.getElementById('bdt-ep-wincher-ranking-pages-history').getContext("2d");

            var gradientStroke = ctx.createLinearGradient(500, 0, 100, 0);
            gradientStroke.addColorStop(0, '#0BB3E5');
            gradientStroke.addColorStop(1, '#20E2AD');

            var gradientFill = ctx.createLinearGradient(500, 0, 100, 0);
            gradientFill.addColorStop(0, "rgba(11, 179, 229, 0.30)");
            gradientFill.addColorStop(1, "rgba(32, 226, 173, 0.30)");

            let usersChartData = {
                labels: $labels,
                datasets: [{
                    label: 'Pages',
                    data: $value,
                    backgroundColor: gradientFill,
                    borderWidth: 2,
                    borderColor: gradientStroke,
                    hoverBorderColor: "red",
                    pointColor: "#3287f0",
                    fill: true,
                    dash_border: "",
                    background_fill: "yes",
                    borderDash: [],
                    pointStyle: "circle",
                    pointBorderColor: gradientStroke,
                    pointBorderWidth: 1.5,
                    pointBackgroundColor: gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor: gradientStroke,
                    tension: 0.5,
                }],

            };

            const myChart = new Chart(ctx, {
                type: 'line',
                data: usersChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },

                    },
                    scales: {
                        xAxis: {
                            display: false
                        },
                        yAxis: {
                            display: false
                        }
                    }

                }
            });

            window.addEventListener('afterprint', () => {
                myChart.resize();
            });
            window.addEventListener('onResize', () => {
                myChart.resize();
            });
        },
        numberToKconverter: function (number) {
            if (number > 999 && number <= 999999) {
                number = (number / 1000).toFixed(1) + 'K'; // convert to K for number from > 1000 < 1 million
            } else if (number > 999999) {
                number = (number / 1000000).toFixed(1) + 'M'; // convert to M for number from > 1 million
            }
            return number;
        },
        tokenRequest: function (code) {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/token',
                method: 'GET',
                data: {
                    code: code
                },
                success: function (token) {
                    // console.log(token);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        getStatus: function () {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/status',
                method: 'GET',
                success: function (status) {
                    // console.log(status);
                    let domains = status.domains;
                    let html = '';
                    domains.forEach(element => {
                        html += `<option value="${element.id}">${element.domain}</option>`;
                    });
                    $('#bdt-wincher-domains').append(html);

                    $("#bdt-wincher-domains option[value='" + EP_WINCHER_CONFIG.domain_id + "']").attr("selected", "selected");

                    // compare this domain name with current domain name
                    let current_domain = window.location.hostname;
                    let current_domain_id = 0;
                    domains.forEach(element => {
                        if (element.domain == current_domain) {
                            current_domain_id = element.id;
                        }
                    });

                    // if (current_domain_id) {
                    //     $('#bdt-wincher-domains').val(current_domain_id);
                    // }

                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        getCompetitionValue: function (_keywords) {
            // Function to get competition value asynchronously
            function getCompetitionValue(element) {
                return new Promise((resolve, reject) => {
                    // Assuming some asynchronous operation to get competition value
                    // Replace the setTimeout with your actual asynchronous operation
                    setTimeout(() => {
                        const competition_value = element.competition && element.competition.value ? element.competition.value : 0;
                        resolve(competition_value);
                    }, 500); // Simulating a delay of 500 milliseconds
                });
            }

            // Function to determine competition level
            function getCompetitionLevel(competition_value) {
                if (competition_value >= 0.75) {
                    return 'High';
                } else if (competition_value >= 0.5) {
                    return 'Medium';
                } else {
                    return 'Low';
                }
            }

            // Array of keywords
            const keywords = _keywords; // Replace with your actual array of keywords

            // Use Promise.all to handle asynchronous operations
            Promise.all(keywords.map(getCompetitionValue))
                .then(competitionValues => {
                    const competitionLevels = competitionValues.map(getCompetitionLevel);

                    const competitionLevel = competitionLevels.sort((a, b) =>
                        competitionLevels.filter(v => v === a).length -
                        competitionLevels.filter(v => v === b).length
                    ).pop();

                    $('#bdt-wincher-competition-level').html(competitionLevel);
                })
                .catch(error => {
                    console.error('Error fetching competition values:', error);
                });

        },
        getTrafficLoss: function (trafficLoss) {

            trafficLoss = trafficLoss.slice(0, 10);

            let html = '';
            trafficLoss.forEach(element => {
                let position = element.ranking.position && element.ranking.position.value ? element.ranking.position.value : 0;
                let traffic = element.ranking.traffic && element.ranking.traffic.value ? element.ranking.traffic.value : 0;
                let traffic_change = element.ranking.traffic && element.ranking.traffic.change ? element.ranking.traffic.change : 0;

                html += `<tr>
                            <td>${element.keyword}</td>
                            <td class="bdt-text-center">${position}</td>
                            <td class="bdt-flex bdt-flex-middle bdt-flex-right">
                                <span>${traffic}</span>
                                <span class="bdt-flex bdt-flex-middle bdt-text-danger bdt-margin-small-left">
                                    ${traffic_change}
                                    <span class="bdt-text-danger" bdt-icon="icon: triangle-down; ratio: 1"></span>
                                </span>
                            </td>
                        </tr>`;

            });
            $('#bdt-wincher-traffic-loss-list').html(html);
        },
        trafficOpportunities: function (trafficOpportunities) {
            trafficOpportunities = trafficOpportunities.slice(0, 10);

            let html = '';
            trafficOpportunities.forEach(element => {
                let position = element.ranking.position && element.ranking.position.value ? element.ranking.position.value : 0;
                let traffic = element.ranking.traffic && element.ranking.traffic.value ? element.ranking.traffic.value : 0;
                let traffic_change = element.ranking.traffic && element.ranking.traffic.change ? element.ranking.traffic.change : 0;

                html += `<tr>
                            <td>${element.keyword}</td>
                            <td class="bdt-text-center">${position}</td>
                            <td class="bdt-flex bdt-flex-middle bdt-flex-right">
                                <span>${traffic_change}</span>
                            </td>
                        </tr>`;


            });
            $('#bdt-wincher-traffic-opportunities-list').html(html);
        },
        getKeywords: function () {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/keywords',
                method: 'GET',
                data: {
                    id: EP_WINCHER_CONFIG.domain_id
                },
                success: function (keywords) {

                    const rankingKeywords = keywords.filter(keyword => keyword.ranking && keyword.ranking.position && keyword.ranking.position.value <= 100);

                    if (rankingKeywords.length) {
                        $('#bdt-wincher-ranking-keywords-count').html(rankingKeywords.length);
                        let html_ranking_keywords = '';

                        let _rankingKeywords = rankingKeywords.slice(0, 10);

                        _rankingKeywords.forEach(element => {
                            let position = element.ranking.position && element.ranking.position.value ? element.ranking.position.value : 0;
                            let traffic = element.ranking.traffic && element.ranking.traffic.value ? element.ranking.traffic.value : 0;
                            let traffic_change = element.ranking.traffic && element.ranking.traffic.change ? element.ranking.traffic.change : 0;

                            html_ranking_keywords += `<tr>
                                <td>${element.keyword}</td>
                                <td class="bdt-text-center">${position}</td>
                                <td class="bdt-flex bdt-flex-middle bdt-flex-right">
                                    <span class="bdt-flex bdt-flex-middle bdt-text-success bdt-margin-small-left">
                                        ${traffic}
                                        <span class="bdt-text-success" bdt-icon="icon: triangle-up; ratio: 1"></span>
                                    </span>
                                </td>
                            </tr>`;
                        });
                        $('#bdt-wincher-ranking-keywords-list').html(html_ranking_keywords);
                    }

                    Wincher.getCompetitionValue(keywords);

                    const trafficLoss = keywords.filter(keyword => keyword.ranking && keyword.ranking.position && keyword.ranking.position.change_status == 'DECLINED');

                    if (trafficLoss.length) {
                        Wincher.getTrafficLoss(trafficLoss);
                    }

                    const trafficOpportunities = keywords.filter(keyword => keyword.ranking && keyword.ranking.position && keyword.ranking.position.value >= 5 && keyword.ranking.position.value <= 20);

                    if (trafficOpportunities.length) {
                        Wincher.trafficOpportunities(trafficOpportunities);
                    }

                    if (keywords.length) {
                        $('#bdt-wincher-keywords-count').html(keywords.length);
                    }

                    let html = '';
                    keywords.forEach(element => {
                        let pages = element.ranking && 'pages' in element.ranking ? element.ranking.pages : [];
                        let volume = element.volume && 'value' in element.volume ? element.volume.value : 0;
                        // let pageArr = Object.entries(pages);

                        let search_intents = element.search_intents;
                        let search_intents_html = '';
                        if (search_intents.length) {
                            search_intents.forEach(intent => {
                                let sign, class_name;

                                class_name = 'bdt-label-default';
                                sign = 'I';

                                if (intent.intent == 'NAVIGATIONAL') {
                                    class_name = 'bdt-label-warning';
                                    sign = 'N';
                                }
                                if (intent.intent == 'COMMERCIAL') {
                                    class_name = 'bdt-label-success';
                                    sign = 'C';
                                }
                                if (intent.intent == 'TRANSACTIONAL') {
                                    class_name = 'bdt-label-success';
                                    sign = 'T';
                                }
                                search_intents_html += `<span class="bdt-label ${class_name} bdt-border-rounded">${sign}</span>`;
                            });
                        }

                        html += `<tr>
							<td class="">${element.keyword}</td>
                            <td class="bdt-text-center bdt-intents-row">${search_intents_html}</td>
							<td class="bdt-text-center">${element.difficulty || 1}</td>
							<td class="bdt-text-center">$ ${element.cpc && 'value' in element.cpc ? element.cpc.value : 0}</td>
							<td class="bdt-text-center">${Wincher.numberToKconverter(volume)}</td>
							<td class="bdt-text-right">${element.ranking.traffic && 'value' in element.ranking.traffic ? element.ranking.traffic.value : 0}</td>
						</tr>`;
                    });
                    $('#bdt-keywords-list').html(html);
                    Wincher.tablePagination();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        create_keyword: function () {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/keywords',
                method: 'POST',
                data: {
                    domain_id: EP_WINCHER_CONFIG.domain_id,
                    keyword: 'test',
                },
                success: function (keywords) {
                    // console.log(keywords);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        getCompetitors: function () {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/competitors',
                method: 'GET',
                data: {
                    id: EP_WINCHER_CONFIG.domain_id
                },
                success: function (competitors) {
                    let html = '';
                    competitors.forEach(element => {
                        html += `<div class="bdt-flex bdt-flex-middle bdt-flex-between bdt-competitors-name-wrap bdt-margin-top">
                                <div class="bdt-flex bdt-flex-middle bdt-border-rounded bdt-competitors-name bdt-text-secondary">
                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 16 16">
                                        <rect width="16" height="16" rx="1.18" style="fill:#fff" />
                                        <polyline points="6.45 10.02 6.45 4.46 4.28 3.41 4.28 11.46 6.48 12.59 11.72 9.1 11.72 6.5 7.69 4.85 7.69 7.17 9.54 7.94 6.45 10.02" style="fill:#272e38" />
                                    </svg> -->
                                    <span class="">${element.domain}</span>
                                </div>
                                <span>465</span>
                            </div>`;
                    });
                    // $('#bdt-wincher-competitor-list').html(html);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        getCompetitorsSummaries() {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/competitors-ranking-summaries',
                method: 'GET',
                data: {
                    id: EP_WINCHER_CONFIG.domain_id
                },
                success: function (competitors_summaries) {
                    let html = '';
                    competitors_summaries.forEach(competitors => {
                        let trafficValue = Wincher.numberToKconverter(competitors.ranking.traffic.value || 0);
                        let favicon_url = Wincher.getWebsiteFaveicon(competitors.domain);
                        /**
                         * Progress bar value
                         */
                        let progress_value = 0;
                        if (competitors.ranking.traffic.value) {
                            progress_value = competitors.ranking.traffic.value;
                        }
                        if (progress_value > 100) {
                            progress_value = 100;
                        }
                        /**
                         * Progress bar color
                         */
                        let progress_color = '';
                        if (progress_value >= 0 && progress_value <= 25) {
                            progress_color = 'bdt-background-0';
                        }
                        if (progress_value > 25 && progress_value <= 50) {
                            progress_color = 'bdt-background-25';
                        }
                        if (progress_value > 50 && progress_value <= 75) {
                            progress_color = 'bdt-background-50';
                        }
                        if (progress_value > 75 && progress_value <= 100) {
                            progress_color = 'bdt-background-75';
                        }

                        html += `<div class="bdt-flex bdt-flex-middle bdt-flex-between bdt-competitors-name-wrap bdt-margin-top">
                                <div class = "bdt-flex bdt-flex-middle bdt-border-rounded bdt-competitors-name bdt-text-secondary ${progress_color}"
                                style = "width:${progress_value}%;" >
                                   <div class="bdt-wincher-tc-logo"><img src="${favicon_url}"/></div>
                                    <span class="">${competitors.domain}</span>
                                </div>
                                <span>${trafficValue}</span>
                            </div>`;
                    });
                    $('#bdt-wincher-competitor-list').html(html);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        getWebsiteData: function () {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/website-data',
                method: 'POST',
                data: {
                    id: EP_WINCHER_CONFIG.domain_id
                },
                success: function (website) {

                    var options = Wincher.DashboardData();

                    let keyword_count = website.keyword_count ? website.keyword_count : 0;
                    options.tracked_keywords_count = keyword_count;

                    let position_changed = website.ranking.traffic.change ? website.ranking.traffic.change : 0;
                    options.traffic_value = position_changed;

                    let position_value = website.ranking.traffic.value ? website.ranking.traffic.value : 0;
                    options.estimated_traffic = position_value;

                    let avg_position = website.ranking.avg_position.value ? website.ranking.avg_position.value : 0;
                    options.ranking_avg_position = avg_position;

                    let avg_position_change = website.ranking.avg_position.change ? website.ranking.avg_position.change : 0;
                    options.ranking_avg_position_change = avg_position_change;

                    let share_of_voice = website.ranking.share_of_voice ? website.ranking.share_of_voice : '-';
                    options.share_of_voice = share_of_voice;

                    let ranking_pages = website.ranking.ranking_pages.value ? website.ranking.ranking_pages.value : 0;
                    options.ranking_pages = ranking_pages;

                    if (website.ranking.traffic.history) {
                        Wincher.trafficChart(website.ranking.traffic.history);
                    }

                    if (website.ranking.traffic_value.history) {
                        Wincher.trafficValueChart(website.ranking.traffic_value.history);
                    }
                    if (website.ranking.avg_position.history) {
                        Wincher.trafficAvgPositionChart(website.ranking.avg_position.history);
                    }
                    if (null == website.ranking.share_of_voice) {
                        Wincher.shareVoiceChart([{
                            datetime: new Date(),
                            value: 0
                        }]);
                    }
                    if (null !== website.ranking.share_of_voice && website.ranking.share_of_voice.history) {
                        Wincher.shareVoiceChart(website.ranking.share_of_voice.history);
                    }


                    // console.log(website.ranking);
                    if (website.keyword_count_history) {
                        Wincher.keywordCountHistoryChart(website.keyword_count_history);
                    }
                    if (website.ranking.ranking_pages.history) {
                        Wincher.rankingPagesHistoryChart(website.ranking.ranking_pages.history);
                    }

                    if (website.competitor_count) {

                    }
                    // console.log(website.competitor_count);

                    // console.log(options);

                    $('#bdt_keyword_count').html(options.tracked_keywords_count);
                    $('#bdt_estimated_traffic').html(options.estimated_traffic);
                    $('#bdt_traffic_value').html(options.traffic_value);
                    $('#bdt_avg_position').html(options.ranking_avg_position);
                    $('#bdt_share_of_voice').html(options.share_of_voice);
                    $('#bdt_ranking_pages').html(options.ranking_pages);
                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        saveDomain: function (domain_id) {
            $.ajax({
                url: EP_WINCHER_CONFIG.apiBaseUrl + '/save-domain',
                method: 'POST',
                data: {
                    domain_id: domain_id
                },
                success: function (status) {
                    // console.log(status);
                    if (status) {
                        location.reload();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        },
        dashBoardReport: function (data) {
            // var options = Wincher.DashboardData();
            // console.log(options);
            // $('#bdt_estimated_traffic').html(Wincher.numberToKconverter(data.estimated_traffic));
            // console.log(data.estimated_traffic);
        },
        tablePagination: function () {
            var rowsPerPage = 10; // Number of rows per page
            var $tableRows = $("#bdt-wincher-keywords tbody tr");
            var totalPages = Math.ceil($tableRows.length / rowsPerPage);

            function showPage(pageNumber) {
                var start = (pageNumber - 1) * rowsPerPage;
                var end = start + rowsPerPage;

                $tableRows.hide().slice(start, end).show();
            }

            function renderPagination() {
                var $paginationContainer = $("#bdt-wk-pagination-container");
                var $paginationList = $('<ul class="bdt-pagination bdt-flex-right"></ul>');

                for (var i = 1; i <= totalPages; i++) {
                    var $pageItem = $("<li>" + i + "</li>");

                    // Add 'active' class to the first pagination item
                    if (i === 1) {
                        $pageItem.addClass('bdt-active');
                    }
                    
                    $paginationList.append($pageItem);
                }

                $paginationContainer.html($paginationList);

                $paginationList.on("click", "li", function () {
                    var pageNumber = parseInt($(this).text(), 10);
                    showPage(pageNumber);

                    // Add active class to the clicked pagination item
                    $paginationList.find('li').removeClass('bdt-active');
                    $(this).addClass('bdt-active');
                });
            }

            // Initial render
            showPage(1);
            renderPagination();
        },
        init: function () {
            $('#wincher-auth-save').on('click', function (e) {
                $.ajax({
                    url: EP_WINCHER_CONFIG.apiBaseUrl + '/authorization-url',
                    method: 'GET',
                    success: function (authorizationUrl) {
                        let currentPopup;

                        function displayLoginPopup(url, successCallback) {
                            if (currentPopup && !currentPopup.closed) {
                                currentPopup.focus();
                                return;
                            }

                            const popupFeatures = 'width=500,height=700,title=Wincher_login';

                            currentPopup = window.open(url, 'Login Popup', popupFeatures);

                            const checkPopupInterval = setInterval(() => {
                                if (currentPopup.closed) {
                                    clearInterval(checkPopupInterval);
                                    // Popup closed, perform any necessary actions
                                    console.log('Popup closed');
                                    location.reload();
                                }
                            }, 1000);

                            // Attach a message event listener to handle messages from the popup
                            window.addEventListener('message', async (event) => {
                                if (event.data && event.data.type === 'wincher:oauth:success') {
                                    // Handle success message
                                    await successCallback({
                                        params: {
                                            code: event.data.code
                                        }
                                    });
                                    // Close the popup after handling the success
                                    currentPopup.close();
                                } else if (event.data && event.data.type === 'wincher:oauth:error') {
                                    // Handle error message
                                    console.error('OAuth error:', event.data.error);
                                    // Close the popup after handling the error
                                    currentPopup.close();
                                }
                            });
                        }

                        // Call the function to display the login popup
                        displayLoginPopup(authorizationUrl, async (data) => {
                            // Handle success callback
                            // console.log('Authorization code:', data.params);

                            Wincher.tokenRequest(data.params.code);
                        });

                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('change', '#bdt-wincher-domains', function (e) {
                e.preventDefault();
                let domain_id = $(this).val();
                // console.log(domain_id);
                Wincher.saveDomain(domain_id);
            });

            $(document).on('click', '#bdt-wincher-keyword-create', function (e) {
                e.preventDefault();
                Wincher.create_keyword();
            });

            if (EP_WINCHER_CONFIG.tokenStatus == true) {
                // if (EP_WINCHER_CONFIG.domain_id !== 'empty') {
                // }
                Wincher.getStatus();
                Wincher.getKeywords();
                // Wincher.getCompetitors();
                Wincher.getCompetitorsSummaries();
                Wincher.getWebsiteData();

            } else {
                $('#bdt-wincher-data').remove();
                $('#bdt-wincher-login').removeClass('bdt-hidden');
            }

        }
    };

    $(document).ready(function () {
        Wincher.init();
    });

    /**
     * Tabs
     */
    $(document).ready(function () {
        var previousActiveTabIndex = 0;

        $(".bdt-ep-tab-switcher").on('click keypress', function (event) {
            // event.which === 13 means the "Enter" key is pressed

            if ((event.type === "keypress" && event.which === 13) || event.type === "click") {

                var tabClicked = $(this).data("tab-index");

                // remove active class from previous tab and add it to newly clicked tab
                $(".bdt-ep-tab-switcher").removeClass("bdt-active");
                $(this).addClass("bdt-active");

                if (tabClicked != previousActiveTabIndex) {
                    $("#bdt-ep-graph-tabs-container .bdt-ep-tab-container").each(function () {
                        if ($(this).data("tab-index") == tabClicked) {
                            $(".bdt-ep-tab-container").addClass('bdt-hidden');
                            $(this).removeClass('bdt-hidden');
                            previousActiveTabIndex = $(this).data("tab-index");
                            return;
                        }
                    });
                }
            }
        });
    });

}(jQuery));