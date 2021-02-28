$(document).ready(function () {
    let fromDatePicker = $('input[name="fromDate"]');
    let toDatePicker = $('input[name="toDate"]');
    let container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    let options = {
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    };
    toDatePicker.datepicker(options);
    fromDatePicker.datepicker(options);

});

function message(id, content, type, target) {
    $('<div class="alert alert-' + type + '" id="' + id + '">' + content + '</div>').hide().insertBefore(target).slideDown('500', function () {
        setTimeout(function () {
            $('#' + id).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    });
}

function getLastMonth() {
    let today = new Date().toISOString().slice(0, 10);
    let lastMonth = subtractDays(30);

    fetchData(lastMonth, today);

}

function subtractDays(days) {
    let result = new Date();
    result.setDate(result.getDate() - days);
    return result.toISOString().slice(0, 10);
}

function fetchData(fromDate, toDate) {
    let labels = [];
    let orders = [];
    let customers = [];
    let orderItems = [];
    let totalCountCustomers = 0;
    let totalCountOrders = 0;
    let totalRevenue = 0;
    let statisticsPerDayResponses;
    $.ajax({
        url: "/dashboard.php?fromDate=" + fromDate + "&toDate=" + toDate,
        type: "GET",
        success: function (result) {
            totalCountCustomers = result["totalCountCustomers"];
            totalCountOrders = result["totalCountOrders"];
            totalRevenue = result["totalRevenue"];
            statisticsPerDayResponses = result["statisticsPerDayResponses"];
            labels = statisticsPerDayResponses.map(a => a.purchaseDate);
            orders = statisticsPerDayResponses.map(a => a.orderCount);
            customers = statisticsPerDayResponses.map(a => a.customerCount);
            orderItems = statisticsPerDayResponses.map(a => a.totalRevenue);

            addStatisticsTotal(totalCountCustomers, totalCountOrders, totalRevenue);
            addDataToChart(labels, orders, customers, orderItems);

        },
        error: function (xhr, status, error) {
            let responseJSON = xhr["responseJSON"];
            let errorMessage = responseJSON["errorMessage"];
            message("error", errorMessage, "danger", '#fromDateLabel');
        }
    });
}

function addStatisticsTotal(totalCountCustomers, totalCountOrders, totalRevenue) {
    $('#totalCustomersValue').text(totalCountCustomers);
    $('#totalOrdersValue').text(totalCountOrders);
    $('#totalRevenueValue').text(totalRevenue);
}

$('#submit').on('click', function () {
    let fromDate = $('#fromDate').val();
    let toDate = $('#toDate').val();

    if (fromDate === '' || toDate === '') {
        message("error", "Fill in from/to date", "danger", '#fromDateLabel');
        return;
    }

    if (new Date(fromDate) > new Date(toDate)) {
        message("error", "From date greater than to date", "danger", '#fromDateLabel');
        return;
    }

    fetchData(fromDate, toDate);
});

function addDataToChart(labels, orders, customers, orderItems) {
    $('#myChart').remove();
    $('#chartRow').append('<canvas id="myChart"><canvas>');
    const ctx = document.getElementById("myChart");
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Customers',
                    data: customers,
                    backgroundColor: 'rgba(240, 173, 78, 1)',
                    borderColor: 'rgba(240, 173, 78, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Orders',
                    data: orders,
                    backgroundColor: 'rgb(92, 184, 92)',
                    borderColor: 'rgb(92, 184, 92)',
                    borderWidth: 1
                },
                {
                    label: 'Revenue',
                    data: orderItems,
                    backgroundColor: 'rgb(91, 192, 222)',
                    borderColor: 'rgb(91, 192, 222)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}